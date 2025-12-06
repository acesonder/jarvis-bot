<?php
/**
 * Messages API - Communication System
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
        if ($action === 'unread-count') {
            // Get unread message count
            $count = $db->fetch("
                SELECT COUNT(*) as count 
                FROM messages 
                WHERE recipient_id = :user_id AND is_read = FALSE
            ", ['user_id' => $currentUserId])['count'];
            
            Response::success(['count' => $count]);
            
        } elseif ($action) {
            // Get specific message with thread
            $message = $db->fetch("
                SELECT m.*, 
                       s.first_name as sender_first_name, s.last_name as sender_last_name,
                       r.first_name as recipient_first_name, r.last_name as recipient_last_name
                FROM messages m
                JOIN users s ON m.sender_id = s.id
                JOIN users r ON m.recipient_id = r.id
                WHERE m.id = :id AND (m.sender_id = :user_id OR m.recipient_id = :user_id)
            ", ['id' => $action, 'user_id' => $currentUserId]);
            
            if (!$message) {
                Response::error('Message not found', 404);
            }
            
            // Mark as read if recipient
            if ($message['recipient_id'] == $currentUserId && !$message['is_read']) {
                $db->update('messages', ['is_read' => true], 'id = :id', ['id' => $action]);
            }
            
            // Get message thread (replies)
            $message['replies'] = $db->fetchAll("
                SELECT m.*, 
                       s.first_name as sender_first_name, s.last_name as sender_last_name
                FROM messages m
                JOIN users s ON m.sender_id = s.id
                WHERE m.parent_message_id = :parent_id
                ORDER BY m.created_at ASC
            ", ['parent_id' => $action]);
            
            Response::success($message);
            
        } else {
            // List messages
            $folder = $_GET['folder'] ?? 'inbox'; // inbox, sent, urgent
            $page = max(1, intval($_GET['page'] ?? 1));
            $perPage = min(100, max(1, intval($_GET['per_page'] ?? 20)));
            $offset = ($page - 1) * $perPage;
            
            $where = [];
            $params = ['user_id' => $currentUserId];
            
            if ($folder === 'inbox') {
                $where[] = "m.recipient_id = :user_id";
                $where[] = "m.parent_message_id IS NULL";
            } elseif ($folder === 'sent') {
                $where[] = "m.sender_id = :user_id";
                $where[] = "m.parent_message_id IS NULL";
            } elseif ($folder === 'urgent') {
                $where[] = "m.recipient_id = :user_id";
                $where[] = "m.is_urgent = TRUE";
            }
            
            $whereClause = implode(' AND ', $where);
            
            $total = $db->fetch("SELECT COUNT(*) as count FROM messages m WHERE $whereClause", $params)['count'];
            
            $messages = $db->fetchAll("
                SELECT m.*, 
                       s.first_name as sender_first_name, s.last_name as sender_last_name,
                       r.first_name as recipient_first_name, r.last_name as recipient_last_name,
                       (SELECT COUNT(*) FROM messages WHERE parent_message_id = m.id) as reply_count
                FROM messages m
                JOIN users s ON m.sender_id = s.id
                JOIN users r ON m.recipient_id = r.id
                WHERE $whereClause
                ORDER BY m.is_urgent DESC, m.created_at DESC
                LIMIT :limit OFFSET :offset
            ", array_merge($params, ['limit' => $perPage, 'offset' => $offset]));
            
            Response::success([
                'messages' => $messages,
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
        
        // Validate input
        if (empty($data['recipient_id']) || empty($data['content'])) {
            Response::error('Recipient and content are required');
        }
        
        try {
            // Create message
            $messageId = $db->insert('messages', [
                'sender_id' => $currentUserId,
                'recipient_id' => intval($data['recipient_id']),
                'subject' => Security::sanitize($data['subject'] ?? 'No subject'),
                'content' => Security::sanitize($data['content']),
                'is_urgent' => boolval($data['is_urgent'] ?? false),
                'parent_message_id' => $data['parent_message_id'] ?? null
            ]);
            
            // Create notification
            $db->insert('notifications', [
                'user_id' => intval($data['recipient_id']),
                'type' => 'new_message',
                'title' => 'New Message',
                'message' => 'You have received a new message',
                'link' => '/messages/' . $messageId
            ]);
            
            Response::success(['message_id' => $messageId], 'Message sent successfully');
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    case 'PUT':
        if (!$action) {
            Response::error('Message ID required');
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate CSRF token
        if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            Response::error('Invalid CSRF token', 403);
        }
        
        try {
            // Can only mark own messages as read
            $db->update('messages', 
                ['is_read' => boolval($data['is_read'] ?? true)],
                'id = :id AND recipient_id = :user_id',
                ['id' => $action, 'user_id' => $currentUserId]
            );
            
            Response::success(null, 'Message updated successfully');
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    case 'DELETE':
        if (!$action) {
            Response::error('Message ID required');
        }
        
        // Validate CSRF token
        if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            Response::error('Invalid CSRF token', 403);
        }
        
        try {
            // Can only delete own sent messages
            $db->delete('messages', 
                'id = :id AND sender_id = :user_id',
                ['id' => $action, 'user_id' => $currentUserId]
            );
            
            Response::success(null, 'Message deleted successfully');
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
        break;
        
    default:
        Response::error('Method not allowed', 405);
}
