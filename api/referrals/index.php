<?php
/**
 * Referrals API - Referral System
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
            // Get specific referral
            $referral = $db->fetch("
                SELECT r.*, 
                       c.first_name as client_first_name, c.last_name as client_last_name,
                       by.first_name as referred_by_first_name, by.last_name as referred_by_last_name,
                       to.first_name as referred_to_first_name, to.last_name as referred_to_last_name
                FROM referrals r
                JOIN client_profiles cp ON r.client_id = cp.id
                JOIN users c ON cp.user_id = c.id
                JOIN users by ON r.referred_by = by.id
                LEFT JOIN users to ON r.referred_to = to.id
                WHERE r.id = :id
            ", ['id' => $action]);
            
            if (!$referral) {
                Response::error('Referral not found', 404);
            }
            
            Response::success($referral);
            
        } else {
            // List referrals
            $status = $_GET['status'] ?? '';
            $clientId = $_GET['client_id'] ?? '';
            $page = max(1, intval($_GET['page'] ?? 1));
            $perPage = min(100, max(1, intval($_GET['per_page'] ?? 20)));
            $offset = ($page - 1) * $perPage;
            
            $where = ['1=1'];
            $params = [];
            
            if ($status) {
                $where[] = "r.status = :status";
                $params['status'] = $status;
            }
            
            if ($clientId) {
                $where[] = "r.client_id = :client_id";
                $params['client_id'] = $clientId;
            }
            
            // Role-based filtering
            if ($userRole === 'outreach_worker') {
                $where[] = "(r.referred_by = :user_id OR r.referred_to = :user_id)";
                $params['user_id'] = $currentUserId;
            } elseif ($userRole === 'service_provider') {
                $where[] = "r.referred_to = :user_id";
                $params['user_id'] = $currentUserId;
            }
            
            $whereClause = implode(' AND ', $where);
            
            $total = $db->fetch("SELECT COUNT(*) as count FROM referrals r WHERE $whereClause", $params)['count'];
            
            $referrals = $db->fetchAll("
                SELECT r.*, 
                       c.first_name as client_first_name, c.last_name as client_last_name,
                       by.first_name as referred_by_first_name, by.last_name as referred_by_last_name,
                       to.first_name as referred_to_first_name, to.last_name as referred_to_last_name
                FROM referrals r
                JOIN client_profiles cp ON r.client_id = cp.id
                JOIN users c ON cp.user_id = c.id
                JOIN users by ON r.referred_by = by.id
                LEFT JOIN users to ON r.referred_to = to.id
                WHERE $whereClause
                ORDER BY r.created_at DESC
                LIMIT :limit OFFSET :offset
            ", array_merge($params, ['limit' => $perPage, 'offset' => $offset]));
            
            Response::success([
                'referrals' => $referrals,
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
        $auth->requireRole(['outreach_worker', 'service_provider', 'admin']);
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate CSRF token
        if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            Response::error('Invalid CSRF token', 403);
        }
        
        try {
            // Create referral
            $referralId = $db->insert('referrals', [
                'client_id' => intval($data['client_id']),
                'referred_by' => $currentUserId,
                'referred_to' => $data['referred_to'] ?? null,
                'service_provider_name' => Security::sanitize($data['service_provider_name'] ?? ''),
                'service_type' => Security::sanitize($data['service_type'] ?? ''),
                'reason' => Security::sanitize($data['reason'] ?? ''),
                'status' => 'pending'
            ]);
            
            // Create notification if referred_to is specified
            if (!empty($data['referred_to'])) {
                $db->insert('notifications', [
                    'user_id' => intval($data['referred_to']),
                    'type' => 'new_referral',
                    'title' => 'New Referral Received',
                    'message' => 'You have received a new client referral',
                    'link' => '/referrals/' . $referralId
                ]);
            }
            
            Response::success(['referral_id' => $referralId], 'Referral created successfully');
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    case 'PUT':
        if (!$action) {
            Response::error('Referral ID required');
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate CSRF token
        if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            Response::error('Invalid CSRF token', 403);
        }
        
        try {
            $updateData = [];
            $allowedFields = ['status', 'referred_to', 'outcome'];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updateData[$field] = Security::sanitize($data[$field]);
                }
            }
            
            if (!empty($updateData)) {
                $db->update('referrals', $updateData, 'id = :id', ['id' => $action]);
            }
            
            Response::success(null, 'Referral updated successfully');
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    default:
        Response::error('Method not allowed', 405);
}
