<?php
/**
 * Inventory API - Inventory Management
 */

require_once __DIR__ . '/../../includes/auth.php';

$auth = new Auth();
$db = Database::getInstance();

// Require authentication
if (!$auth->isLoggedIn()) {
    Response::error('Authentication required', 401);
}

$currentUserId = Session::get('user_id');

switch ($requestMethod) {
    case 'GET':
        if ($action === 'low-stock') {
            // Get low stock products
            $auth->requireRole(['outreach_worker', 'admin']);
            
            $products = $db->fetchAll("
                SELECT * FROM products 
                WHERE stock_quantity <= reorder_level AND is_active = 1
                ORDER BY stock_quantity ASC
            ");
            
            Response::success(['products' => $products]);
            
        } elseif ($action === 'transactions') {
            // Get transactions
            $auth->requireRole(['outreach_worker', 'admin']);
            
            $productId = $_GET['product_id'] ?? '';
            $page = max(1, intval($_GET['page'] ?? 1));
            $perPage = min(100, max(1, intval($_GET['per_page'] ?? 50)));
            $offset = ($page - 1) * $perPage;
            
            $where = ['1=1'];
            $params = [];
            
            if ($productId) {
                $where[] = "it.product_id = :product_id";
                $params['product_id'] = $productId;
            }
            
            $whereClause = implode(' AND ', $where);
            
            $transactions = $db->fetchAll("
                SELECT it.*, p.name as product_name, u.first_name, u.last_name
                FROM inventory_transactions it
                JOIN products p ON it.product_id = p.id
                LEFT JOIN users u ON it.performed_by = u.id
                WHERE $whereClause
                ORDER BY it.created_at DESC
                LIMIT :limit OFFSET :offset
            ", array_merge($params, ['limit' => $perPage, 'offset' => $offset]));
            
            Response::success(['transactions' => $transactions]);
            
        } elseif ($action) {
            // Get specific product
            $product = $db->fetch("SELECT * FROM products WHERE id = :id", ['id' => $action]);
            
            if (!$product) {
                Response::error('Product not found', 404);
            }
            
            // Get recent transactions
            $product['recent_transactions'] = $db->fetchAll("
                SELECT it.*, u.first_name, u.last_name
                FROM inventory_transactions it
                LEFT JOIN users u ON it.performed_by = u.id
                WHERE it.product_id = :product_id
                ORDER BY it.created_at DESC
                LIMIT 10
            ", ['product_id' => $action]);
            
            Response::success($product);
            
        } else {
            // List products
            $category = $_GET['category'] ?? '';
            $search = $_GET['search'] ?? '';
            $isActive = isset($_GET['is_active']) ? boolval($_GET['is_active']) : true;
            
            $where = ['1=1'];
            $params = [];
            
            if ($category) {
                $where[] = "category = :category";
                $params['category'] = $category;
            }
            
            if ($search) {
                $where[] = "(name LIKE :search OR description LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            if ($isActive !== null) {
                $where[] = "is_active = :is_active";
                $params['is_active'] = $isActive;
            }
            
            $whereClause = implode(' AND ', $where);
            
            $products = $db->fetchAll("
                SELECT * FROM products 
                WHERE $whereClause
                ORDER BY name ASC
            ", $params);
            
            Response::success(['products' => $products]);
        }
        break;
        
    case 'POST':
        $auth->requireRole(['admin']);
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate CSRF token
        if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            Response::error('Invalid CSRF token', 403);
        }
        
        try {
            // Create product
            $productId = $db->insert('products', [
                'name' => Security::sanitize($data['name']),
                'description' => Security::sanitize($data['description'] ?? ''),
                'category' => Security::sanitize($data['category'] ?? ''),
                'tile_color' => Security::sanitize($data['tile_color'] ?? '#3498db'),
                'font_color' => Security::sanitize($data['font_color'] ?? '#ffffff'),
                'stock_quantity' => intval($data['stock_quantity'] ?? 0),
                'reorder_level' => intval($data['reorder_level'] ?? 10),
                'unit' => Security::sanitize($data['unit'] ?? 'unit'),
                'is_active' => boolval($data['is_active'] ?? true)
            ]);
            
            Response::success(['product_id' => $productId], 'Product created successfully');
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    case 'PUT':
        $auth->requireRole(['outreach_worker', 'admin']);
        
        if (!$action) {
            Response::error('Product ID required');
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate CSRF token
        if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            Response::error('Invalid CSRF token', 403);
        }
        
        try {
            if (isset($data['adjust_quantity'])) {
                // Adjust inventory
                $quantity = intval($data['adjust_quantity']);
                $transactionType = $quantity > 0 ? 'in' : 'out';
                
                $db->getConnection()->beginTransaction();
                
                $db->query("
                    UPDATE products 
                    SET stock_quantity = stock_quantity + :quantity 
                    WHERE id = :id
                ", ['quantity' => $quantity, 'id' => $action]);
                
                // Log transaction
                $db->insert('inventory_transactions', [
                    'product_id' => $action,
                    'transaction_type' => $transactionType,
                    'quantity' => abs($quantity),
                    'reference_type' => 'manual_adjustment',
                    'notes' => Security::sanitize($data['notes'] ?? ''),
                    'performed_by' => $currentUserId
                ]);
                
                $db->getConnection()->commit();
                
                Response::success(null, 'Inventory adjusted successfully');
                
            } else {
                // Update product details
                $updateData = [];
                $allowedFields = ['name', 'description', 'category', 'tile_color', 'font_color', 
                                'reorder_level', 'unit', 'is_active'];
                
                foreach ($allowedFields as $field) {
                    if (isset($data[$field])) {
                        $updateData[$field] = Security::sanitize($data[$field]);
                    }
                }
                
                if (!empty($updateData)) {
                    $db->update('products', $updateData, 'id = :id', ['id' => $action]);
                }
                
                Response::success(null, 'Product updated successfully');
            }
            
        } catch (Exception $e) {
            if ($db->getConnection()->inTransaction()) {
                $db->getConnection()->rollBack();
            }
            Response::error($e->getMessage(), 500);
        }
        break;
        
    default:
        Response::error('Method not allowed', 405);
}
