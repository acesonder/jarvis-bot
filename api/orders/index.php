<?php
/**
 * Orders API - Order Management
 */

require_once __DIR__ . '/../../includes/auth.php';

$auth = new Auth();
$db = Database::getInstance();

// Require authentication
if (!$auth->isLoggedIn()) {
    Response::error('Authentication required', 401);
}

$currentUserId = Session::get('user_id');
$userRole = Session::get('user_role');

switch ($requestMethod) {
    case 'GET':
        if ($action) {
            // Get specific order
            $order = $db->fetch("
                SELECT o.*, 
                       u.first_name as placed_by_first_name, u.last_name as placed_by_last_name,
                       c.first_name as client_first_name, c.last_name as client_last_name
                FROM orders o
                JOIN users u ON o.placed_by = u.id
                LEFT JOIN client_profiles cp ON o.client_id = cp.id
                LEFT JOIN users c ON cp.user_id = c.id
                WHERE o.id = :id
            ", ['id' => $action]);
            
            if (!$order) {
                Response::error('Order not found', 404);
            }
            
            // Get order items
            $order['items'] = $db->fetchAll("
                SELECT oi.*, p.name, p.unit, p.category
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :order_id
            ", ['order_id' => $action]);
            
            Response::success($order);
            
        } else {
            // List orders
            $status = $_GET['status'] ?? '';
            $clientId = $_GET['client_id'] ?? '';
            $page = max(1, intval($_GET['page'] ?? 1));
            $perPage = min(100, max(1, intval($_GET['per_page'] ?? 20)));
            $offset = ($page - 1) * $perPage;
            
            $where = ['1=1'];
            $params = [];
            
            if ($status) {
                $where[] = "o.status = :status";
                $params['status'] = $status;
            }
            
            if ($clientId) {
                $where[] = "o.client_id = :client_id";
                $params['client_id'] = $clientId;
            }
            
            // Role-based filtering
            if ($userRole === 'client') {
                $clientProfile = $db->fetch("SELECT id FROM client_profiles WHERE user_id = :user_id", ['user_id' => $currentUserId]);
                if ($clientProfile) {
                    $where[] = "o.client_id = :my_client_id";
                    $params['my_client_id'] = $clientProfile['id'];
                }
            }
            
            $whereClause = implode(' AND ', $where);
            
            $total = $db->fetch("SELECT COUNT(*) as count FROM orders o WHERE $whereClause", $params)['count'];
            
            $orders = $db->fetchAll("
                SELECT o.*, 
                       u.first_name as placed_by_first_name, u.last_name as placed_by_last_name,
                       c.first_name as client_first_name, c.last_name as client_last_name
                FROM orders o
                JOIN users u ON o.placed_by = u.id
                LEFT JOIN client_profiles cp ON o.client_id = cp.id
                LEFT JOIN users c ON cp.user_id = c.id
                WHERE $whereClause
                ORDER BY o.created_at DESC
                LIMIT :limit OFFSET :offset
            ", array_merge($params, ['limit' => $perPage, 'offset' => $offset]));
            
            Response::success([
                'orders' => $orders,
                'pagination' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => ceil($total / $perPage)
                ]
            ]);
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate CSRF token
        if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            Response::error('Invalid CSRF token', 403);
        }
        
        try {
            $db->getConnection()->beginTransaction();
            
            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            
            // Create order
            $orderId = $db->insert('orders', [
                'order_number' => $orderNumber,
                'client_id' => $data['client_id'] ?? null,
                'placed_by' => $currentUserId,
                'order_type' => Security::sanitize($data['order_type'] ?? 'pickup'),
                'status' => 'pending',
                'scheduled_date' => $data['scheduled_date'] ?? null,
                'scheduled_time' => $data['scheduled_time'] ?? null,
                'pickup_location' => Security::sanitize($data['pickup_location'] ?? ''),
                'delivery_address' => Security::sanitize($data['delivery_address'] ?? ''),
                'notes' => Security::sanitize($data['notes'] ?? '')
            ]);
            
            // Add order items and update inventory
            if (!empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    // Insert order item
                    $db->insert('order_items', [
                        'order_id' => $orderId,
                        'product_id' => intval($item['product_id']),
                        'quantity' => intval($item['quantity'])
                    ]);
                    
                    // Update product stock
                    $db->query("
                        UPDATE products 
                        SET stock_quantity = stock_quantity - :quantity 
                        WHERE id = :product_id
                    ", [
                        'quantity' => intval($item['quantity']),
                        'product_id' => intval($item['product_id'])
                    ]);
                    
                    // Log inventory transaction
                    $db->insert('inventory_transactions', [
                        'product_id' => intval($item['product_id']),
                        'transaction_type' => 'out',
                        'quantity' => intval($item['quantity']),
                        'reference_type' => 'order',
                        'reference_id' => $orderId,
                        'performed_by' => $currentUserId
                    ]);
                }
            }
            
            $db->getConnection()->commit();
            
            Response::success([
                'order_id' => $orderId,
                'order_number' => $orderNumber
            ], 'Order created successfully');
            
        } catch (Exception $e) {
            $db->getConnection()->rollBack();
            Response::error($e->getMessage(), 500);
        }
        break;
        
    case 'PUT':
        $auth->requireRole(['outreach_worker', 'admin']);
        
        if (!$action) {
            Response::error('Order ID required');
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate CSRF token
        if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            Response::error('Invalid CSRF token', 403);
        }
        
        try {
            // Update order status
            $allowedFields = ['status', 'scheduled_date', 'scheduled_time', 'notes'];
            $updateData = [];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updateData[$field] = Security::sanitize($data[$field]);
                }
            }
            
            if (!empty($updateData)) {
                $db->update('orders', $updateData, 'id = :id', ['id' => $action]);
            }
            
            Response::success(null, 'Order updated successfully');
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    default:
        Response::error('Method not allowed', 405);
}
