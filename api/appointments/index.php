<?php
/**
 * Appointments API - Appointment System
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
            // Get specific appointment
            $appointment = $db->fetch("
                SELECT a.*, 
                       c.first_name as client_first_name, c.last_name as client_last_name,
                       p.first_name as provider_first_name, p.last_name as provider_last_name
                FROM appointments a
                JOIN client_profiles cp ON a.client_id = cp.id
                JOIN users c ON cp.user_id = c.id
                JOIN users p ON a.provider_id = p.id
                WHERE a.id = :id
            ", ['id' => $action]);
            
            if (!$appointment) {
                Response::error('Appointment not found', 404);
            }
            
            Response::success($appointment);
            
        } else {
            // List appointments
            $status = $_GET['status'] ?? '';
            $date = $_GET['date'] ?? '';
            $page = max(1, intval($_GET['page'] ?? 1));
            $perPage = min(100, max(1, intval($_GET['per_page'] ?? 20)));
            $offset = ($page - 1) * $perPage;
            
            $where = ['1=1'];
            $params = [];
            
            if ($status) {
                $where[] = "a.status = :status";
                $params['status'] = $status;
            }
            
            if ($date) {
                $where[] = "a.scheduled_date = :date";
                $params['date'] = $date;
            }
            
            // Role-based filtering
            if ($userRole === 'client') {
                $clientProfile = $db->fetch("SELECT id FROM client_profiles WHERE user_id = :user_id", ['user_id' => $currentUserId]);
                if ($clientProfile) {
                    $where[] = "a.client_id = :client_id";
                    $params['client_id'] = $clientProfile['id'];
                }
            } elseif ($userRole === 'service_provider') {
                $where[] = "a.provider_id = :provider_id";
                $params['provider_id'] = $currentUserId;
            }
            
            $whereClause = implode(' AND ', $where);
            
            $total = $db->fetch("SELECT COUNT(*) as count FROM appointments a WHERE $whereClause", $params)['count'];
            
            $appointments = $db->fetchAll("
                SELECT a.*, 
                       c.first_name as client_first_name, c.last_name as client_last_name,
                       p.first_name as provider_first_name, p.last_name as provider_last_name
                FROM appointments a
                JOIN client_profiles cp ON a.client_id = cp.id
                JOIN users c ON cp.user_id = c.id
                JOIN users p ON a.provider_id = p.id
                WHERE $whereClause
                ORDER BY a.scheduled_date DESC, a.scheduled_time DESC
                LIMIT :limit OFFSET :offset
            ", array_merge($params, ['limit' => $perPage, 'offset' => $offset]));
            
            Response::success([
                'appointments' => $appointments,
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
            // Create appointment
            $appointmentId = $db->insert('appointments', [
                'client_id' => intval($data['client_id']),
                'provider_id' => intval($data['provider_id']),
                'appointment_type' => Security::sanitize($data['appointment_type'] ?? ''),
                'scheduled_date' => $data['scheduled_date'],
                'scheduled_time' => $data['scheduled_time'],
                'duration_minutes' => intval($data['duration_minutes'] ?? 30),
                'location' => Security::sanitize($data['location'] ?? ''),
                'status' => 'scheduled',
                'notes' => Security::sanitize($data['notes'] ?? '')
            ]);
            
            // Create notifications
            $db->insert('notifications', [
                'user_id' => intval($data['client_id']),
                'type' => 'appointment_scheduled',
                'title' => 'New Appointment Scheduled',
                'message' => 'An appointment has been scheduled for you',
                'link' => '/appointments/' . $appointmentId
            ]);
            
            Response::success(['appointment_id' => $appointmentId], 'Appointment created successfully');
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    case 'PUT':
        if (!$action) {
            Response::error('Appointment ID required');
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate CSRF token
        if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            Response::error('Invalid CSRF token', 403);
        }
        
        try {
            $updateData = [];
            $allowedFields = ['status', 'scheduled_date', 'scheduled_time', 'duration_minutes', 'location', 'notes'];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updateData[$field] = Security::sanitize($data[$field]);
                }
            }
            
            if (!empty($updateData)) {
                $db->update('appointments', $updateData, 'id = :id', ['id' => $action]);
            }
            
            Response::success(null, 'Appointment updated successfully');
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    default:
        Response::error('Method not allowed', 405);
}
