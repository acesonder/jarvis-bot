<?php
/**
 * Users Management API
 */

require_once __DIR__ . '/../../includes/auth.php';

$auth = new Auth();
$db = Database::getInstance();

// Require admin role for all operations
$auth->requireRole('admin');

// Get request method and action
$requestMethod = $_SERVER['REQUEST_METHOD'];
$userId = $_GET['id'] ?? $action ?? null;

switch ($requestMethod) {
    case 'GET':
        if ($userId) {
            // Get single user
            try {
                $user = $db->fetch(
                    "SELECT id, username, email, first_name, last_name, phone, role, status, created_at 
                     FROM users WHERE id = :id",
                    ['id' => $userId]
                );
                
                if (!$user) {
                    Response::error('User not found', 404);
                }
                
                Response::success($user);
            } catch (Exception $e) {
                Response::error($e->getMessage(), 500);
            }
        } else {
            // Get all users with optional filters
            try {
                $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                $perPage = isset($_GET['per_page']) ? max(1, min(100, intval($_GET['per_page']))) : 50;
                $offset = ($page - 1) * $perPage;
                
                $role = $_GET['role'] ?? null;
                $status = $_GET['status'] ?? null;
                $search = $_GET['search'] ?? null;
                
                $where = [];
                $params = [];
                
                if ($role) {
                    $where[] = "role = :role";
                    $params['role'] = $role;
                }
                
                if ($status) {
                    $where[] = "status = :status";
                    $params['status'] = $status;
                }
                
                if ($search) {
                    $where[] = "(username LIKE :search OR email LIKE :search OR first_name LIKE :search OR last_name LIKE :search)";
                    $params['search'] = "%$search%";
                }
                
                $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
                
                // Get total count
                $total = $db->fetch("SELECT COUNT(*) as count FROM users $whereClause", $params)['count'];
                
                // Get users
                $users = $db->fetchAll(
                    "SELECT id, username, email, first_name, last_name, phone, role, status, created_at 
                     FROM users $whereClause 
                     ORDER BY created_at DESC 
                     LIMIT :limit OFFSET :offset",
                    array_merge($params, ['limit' => $perPage, 'offset' => $offset])
                );
                
                Response::success([
                    'users' => $users,
                    'pagination' => [
                        'page' => $page,
                        'per_page' => $perPage,
                        'total' => $total,
                        'pages' => ceil($total / $perPage)
                    ]
                ]);
            } catch (Exception $e) {
                Response::error($e->getMessage(), 500);
            }
        }
        break;
        
    case 'POST':
        // Create new user
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate required fields
            $required = ['username', 'email', 'password', 'first_name', 'last_name', 'role'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    Response::error("$field is required");
                }
            }
            
            // Validate email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                Response::error('Invalid email address');
            }
            
            // Validate password strength
            if (!PasswordValidator::isStrong($data['password'])) {
                Response::error('Password must be at least 8 characters with uppercase, lowercase, number and special character');
            }
            
            // Check if username or email exists
            $existing = $db->fetch(
                "SELECT id FROM users WHERE username = :username OR email = :email",
                ['username' => $data['username'], 'email' => $data['email']]
            );
            
            if ($existing) {
                Response::error("Username or email already exists");
            }
            
            // Create user
            $newUserId = $db->insert('users', [
                'username' => Security::sanitize($data['username']),
                'email' => Security::sanitize($data['email']),
                'password_hash' => Security::hashPassword($data['password']),
                'first_name' => Security::sanitize($data['first_name']),
                'last_name' => Security::sanitize($data['last_name']),
                'phone' => Security::sanitize($data['phone'] ?? ''),
                'role' => Security::sanitize($data['role']),
                'status' => Security::sanitize($data['status'] ?? 'active')
            ]);
            
            // Create profile if client
            if ($data['role'] === 'client') {
                $db->insert('client_profiles', ['user_id' => $newUserId]);
            }
            
            // Log audit
            $db->insert('audit_log', [
                'user_id' => Session::get('user_id'),
                'action' => 'create_user',
                'table_name' => 'users',
                'record_id' => $newUserId,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
            
            Response::success(['user_id' => $newUserId], 'User created successfully');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    case 'PUT':
        // Update user
        if (!$userId) {
            Response::error('User ID is required');
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Check if user exists
            $user = $db->fetch("SELECT id FROM users WHERE id = :id", ['id' => $userId]);
            if (!$user) {
                Response::error('User not found', 404);
            }
            
            $updateData = [];
            
            // Update allowed fields
            if (isset($data['first_name'])) {
                $updateData['first_name'] = Security::sanitize($data['first_name']);
            }
            if (isset($data['last_name'])) {
                $updateData['last_name'] = Security::sanitize($data['last_name']);
            }
            if (isset($data['email'])) {
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    Response::error('Invalid email address');
                }
                $updateData['email'] = Security::sanitize($data['email']);
            }
            if (isset($data['phone'])) {
                $updateData['phone'] = Security::sanitize($data['phone']);
            }
            if (isset($data['role'])) {
                $updateData['role'] = Security::sanitize($data['role']);
            }
            if (isset($data['status'])) {
                $updateData['status'] = Security::sanitize($data['status']);
            }
            
            // Handle password update separately
            if (!empty($data['password'])) {
                if (!PasswordValidator::isStrong($data['password'])) {
                    Response::error('Password must be at least 8 characters with uppercase, lowercase, number and special character');
                }
                $updateData['password_hash'] = Security::hashPassword($data['password']);
            }
            
            if (!empty($updateData)) {
                $db->update('users', $updateData, 'id = :id', ['id' => $userId]);
                
                // Log audit
                $db->insert('audit_log', [
                    'user_id' => Session::get('user_id'),
                    'action' => 'update_user',
                    'table_name' => 'users',
                    'record_id' => $userId,
                    'new_values' => json_encode($updateData),
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
                ]);
            }
            
            Response::success(null, 'User updated successfully');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    case 'DELETE':
        // Delete/deactivate user
        if (!$userId) {
            Response::error('User ID is required');
        }
        
        try {
            // Check if user exists
            $user = $db->fetch("SELECT id FROM users WHERE id = :id", ['id' => $userId]);
            if (!$user) {
                Response::error('User not found', 404);
            }
            
            // Prevent deleting self
            if ($userId == Session::get('user_id')) {
                Response::error('Cannot delete your own account');
            }
            
            // Soft delete - set status to inactive
            $db->update('users', ['status' => 'inactive'], 'id = :id', ['id' => $userId]);
            
            // Log audit
            $db->insert('audit_log', [
                'user_id' => Session::get('user_id'),
                'action' => 'delete_user',
                'table_name' => 'users',
                'record_id' => $userId,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
            
            Response::success(null, 'User deactivated successfully');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    default:
        Response::error('Method not allowed', 405);
}
