<?php
/**
 * Clients API - Case Management
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
            // Get specific client
            $auth->requireRole(['outreach_worker', 'service_provider', 'admin']);
            
            $client = $db->fetch("
                SELECT cp.*, u.username, u.email, u.first_name, u.last_name, u.phone,
                       w.first_name as worker_first_name, w.last_name as worker_last_name
                FROM client_profiles cp
                JOIN users u ON cp.user_id = u.id
                LEFT JOIN users w ON cp.assigned_worker_id = w.id
                WHERE cp.id = :id
            ", ['id' => $action]);
            
            if (!$client) {
                Response::error('Client not found', 404);
            }
            
            // Get care plans
            $carePlans = $db->fetchAll("
                SELECT * FROM care_plans WHERE client_id = :client_id ORDER BY created_at DESC
            ", ['client_id' => $action]);
            
            // Get goals for each care plan
            foreach ($carePlans as &$plan) {
                $plan['goals'] = $db->fetchAll("
                    SELECT g.*, 
                           (SELECT COUNT(*) FROM milestones WHERE goal_id = g.id) as total_milestones,
                           (SELECT COUNT(*) FROM milestones WHERE goal_id = g.id AND completed = TRUE) as completed_milestones
                    FROM goals g WHERE g.care_plan_id = :plan_id
                ", ['plan_id' => $plan['id']]);
            }
            
            $client['care_plans'] = $carePlans;
            
            Response::success($client);
            
        } else {
            // List clients with search/filter
            $auth->requireRole(['outreach_worker', 'service_provider', 'admin']);
            
            $search = $_GET['search'] ?? '';
            $riskLevel = $_GET['risk_level'] ?? '';
            $housingStatus = $_GET['housing_status'] ?? '';
            $page = max(1, intval($_GET['page'] ?? 1));
            $perPage = min(100, max(1, intval($_GET['per_page'] ?? 20)));
            $offset = ($page - 1) * $perPage;
            
            $where = ['1=1'];
            $params = [];
            
            if ($search) {
                $where[] = "(u.first_name LIKE :search OR u.last_name LIKE :search OR u.email LIKE :search OR u.username LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            if ($riskLevel) {
                $where[] = "cp.risk_level = :risk_level";
                $params['risk_level'] = $riskLevel;
            }
            
            if ($housingStatus) {
                $where[] = "cp.housing_status = :housing_status";
                $params['housing_status'] = $housingStatus;
            }
            
            $whereClause = implode(' AND ', $where);
            
            $total = $db->fetch("
                SELECT COUNT(*) as count
                FROM client_profiles cp
                JOIN users u ON cp.user_id = u.id
                WHERE $whereClause
            ", $params)['count'];
            
            $clients = $db->fetchAll("
                SELECT cp.*, u.username, u.email, u.first_name, u.last_name, u.phone,
                       w.first_name as worker_first_name, w.last_name as worker_last_name
                FROM client_profiles cp
                JOIN users u ON cp.user_id = u.id
                LEFT JOIN users w ON cp.assigned_worker_id = w.id
                WHERE $whereClause
                ORDER BY u.last_name, u.first_name
                LIMIT :limit OFFSET :offset
            ", array_merge($params, ['limit' => $perPage, 'offset' => $offset]));
            
            Response::success([
                'clients' => $clients,
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
        $auth->requireRole(['outreach_worker', 'admin']);
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate CSRF token
        if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            Response::error('Invalid CSRF token', 403);
        }
        
        try {
            $db->getConnection()->beginTransaction();
            
            // Create user account
            $userId = $db->insert('users', [
                'username' => Security::sanitize($data['username']),
                'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
                'password_hash' => Security::hashPassword($data['password'] ?? bin2hex(random_bytes(16))),
                'first_name' => Security::sanitize($data['first_name']),
                'last_name' => Security::sanitize($data['last_name']),
                'phone' => Security::sanitize($data['phone'] ?? ''),
                'role' => 'client',
                'status' => 'active'
            ]);
            
            // Create client profile
            $profileId = $db->insert('client_profiles', [
                'user_id' => $userId,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'housing_status' => Security::sanitize($data['housing_status'] ?? 'unknown'),
                'health_notes' => Security::sanitize($data['health_notes'] ?? ''),
                'emergency_contact_name' => Security::sanitize($data['emergency_contact_name'] ?? ''),
                'emergency_contact_phone' => Security::sanitize($data['emergency_contact_phone'] ?? ''),
                'preferred_location' => Security::sanitize($data['preferred_location'] ?? ''),
                'notes' => Security::sanitize($data['notes'] ?? ''),
                'risk_level' => Security::sanitize($data['risk_level'] ?? 'low'),
                'assigned_worker_id' => $currentUserId
            ]);
            
            $db->getConnection()->commit();
            
            Response::success(['client_id' => $profileId], 'Client created successfully');
            
        } catch (Exception $e) {
            $db->getConnection()->rollBack();
            Response::error($e->getMessage(), 500);
        }
        break;
        
    case 'PUT':
        $auth->requireRole(['outreach_worker', 'admin']);
        
        if (!$action) {
            Response::error('Client ID required');
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate CSRF token
        if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            Response::error('Invalid CSRF token', 403);
        }
        
        try {
            // Update client profile
            $updateData = [];
            $allowedFields = ['housing_status', 'health_notes', 'emergency_contact_name', 
                            'emergency_contact_phone', 'preferred_location', 'notes', 'risk_level'];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updateData[$field] = Security::sanitize($data[$field]);
                }
            }
            
            if (!empty($updateData)) {
                $db->update('client_profiles', $updateData, 'id = :id', ['id' => $action]);
            }
            
            Response::success(null, 'Client updated successfully');
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    default:
        Response::error('Method not allowed', 405);
}
