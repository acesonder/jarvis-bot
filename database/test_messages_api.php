<?php
/**
 * Comprehensive Messages API Test Script
 */

require_once __DIR__ . '/../includes/auth.php';

Session::start();

// Simulate logged-in user (client)
$_SESSION['user_id'] = 3; // Benjamin Jackson (client)
$_SESSION['role'] = 'client';
$_SESSION['logged_in'] = true;

$db = Database::getInstance();

echo "=== MESSAGES DATABASE INTEGRATION TEST ===\n\n";

// Test 1: Check database connection
echo "Test 1: Database Connection\n";
try {
    $users = $db->fetchAll("SELECT id, first_name, last_name, role FROM users LIMIT 3");
    echo "✓ Database connected successfully\n";
    echo "  Users found: " . count($users) . "\n";
    foreach ($users as $user) {
        echo "  - {$user['first_name']} {$user['last_name']} ({$user['role']})\n";
    }
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// Test 2: Fetch messages for client
echo "Test 2: Fetch Inbox Messages\n";
try {
    $messages = $db->fetchAll("
        SELECT m.*, 
               s.first_name as sender_first_name, s.last_name as sender_last_name,
               r.first_name as recipient_first_name, r.last_name as recipient_last_name,
               (SELECT COUNT(*) FROM messages WHERE parent_message_id = m.id) as reply_count
        FROM messages m
        JOIN users s ON m.sender_id = s.id
        JOIN users r ON m.recipient_id = r.id
        WHERE m.recipient_id = 3 AND m.parent_message_id IS NULL
        ORDER BY m.created_at DESC
    ");
    
    echo "✓ Found " . count($messages) . " messages\n";
    foreach ($messages as $msg) {
        $readStatus = $msg['is_read'] ? 'Read' : 'Unread';
        echo "  - [{$readStatus}] From: {$msg['sender_first_name']} {$msg['sender_last_name']}\n";
        echo "    Subject: {$msg['subject']}\n";
        echo "    Replies: {$msg['reply_count']}\n";
    }
} catch (Exception $e) {
    echo "✗ Failed to fetch messages: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Fetch conversation with replies
echo "Test 3: Fetch Conversation with Replies\n";
try {
    $messageId = 3; // First message
    $message = $db->fetch("
        SELECT m.*, 
               s.first_name as sender_first_name, s.last_name as sender_last_name
        FROM messages m
        JOIN users s ON m.sender_id = s.id
        WHERE m.id = :id
    ", ['id' => $messageId]);
    
    if ($message) {
        echo "✓ Main message found\n";
        echo "  Subject: {$message['subject']}\n";
        echo "  From: {$message['sender_first_name']} {$message['sender_last_name']}\n";
        
        // Get replies
        $replies = $db->fetchAll("
            SELECT m.*, 
                   s.first_name as sender_first_name, s.last_name as sender_last_name
            FROM messages m
            JOIN users s ON m.sender_id = s.id
            WHERE m.parent_message_id = :parent_id
            ORDER BY m.created_at ASC
        ", ['parent_id' => $messageId]);
        
        echo "  Replies: " . count($replies) . "\n";
        foreach ($replies as $reply) {
            echo "    - {$reply['sender_first_name']}: " . substr($reply['content'], 0, 50) . "...\n";
        }
    } else {
        echo "✗ Message not found\n";
    }
} catch (Exception $e) {
    echo "✗ Failed to fetch conversation: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Unread count
echo "Test 4: Unread Message Count\n";
try {
    $count = $db->fetch("
        SELECT COUNT(*) as count 
        FROM messages 
        WHERE recipient_id = 3 AND is_read = 0
    ")['count'];
    
    echo "✓ Unread messages: {$count}\n";
} catch (Exception $e) {
    echo "✗ Failed to get unread count: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Get users for compose dropdown
echo "Test 5: Get Users for Compose\n";
try {
    $careTeam = $db->fetchAll("
        SELECT u.id, u.first_name, u.last_name, u.role
        FROM users u
        WHERE u.role IN ('admin', 'outreach_worker', 'service_provider')
        ORDER BY u.first_name, u.last_name
    ");
    
    echo "✓ Found " . count($careTeam) . " care team members\n";
    foreach ($careTeam as $user) {
        echo "  - {$user['first_name']} {$user['last_name']} ({$user['role']})\n";
    }
} catch (Exception $e) {
    echo "✗ Failed to fetch users: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 6: Simulate API endpoint
echo "Test 6: Simulate Messages API Endpoint\n";
try {
    // This simulates what the API does
    $currentUserId = 3;
    $page = 1;
    $perPage = 20;
    $offset = 0;
    
    $where = "m.recipient_id = :user_id AND m.parent_message_id IS NULL";
    $params = ['user_id' => $currentUserId];
    
    $total = $db->fetch("SELECT COUNT(*) as count FROM messages m WHERE $where", $params)['count'];
    
    $messages = $db->fetchAll("
        SELECT m.*, 
               s.first_name as sender_first_name, s.last_name as sender_last_name,
               r.first_name as recipient_first_name, r.last_name as recipient_last_name,
               (SELECT COUNT(*) FROM messages WHERE parent_message_id = m.id) as reply_count
        FROM messages m
        JOIN users s ON m.sender_id = s.id
        JOIN users r ON m.recipient_id = r.id
        WHERE $where
        ORDER BY m.is_urgent DESC, m.created_at DESC
        LIMIT :limit OFFSET :offset
    ", array_merge($params, ['limit' => $perPage, 'offset' => $offset]));
    
    echo "✓ API simulation successful\n";
    echo "  Total messages: {$total}\n";
    echo "  Messages returned: " . count($messages) . "\n";
    echo "  Pagination: Page {$page}, {$perPage} per page\n";
    
} catch (Exception $e) {
    echo "✗ API simulation failed: " . $e->getMessage() . "\n";
}

echo "\n=== ALL TESTS COMPLETED ===\n";
