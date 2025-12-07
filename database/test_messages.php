<?php
/**
 * Test script to populate messages for testing
 */

require_once __DIR__ . '/../config/config.php';

$db = Database::getInstance();

try {
    // Get users to test with
    $users = $db->fetchAll("SELECT id, first_name, last_name, role FROM users");
    
    if (count($users) < 2) {
        echo "Need at least 2 users in the database\n";
        exit(1);
    }
    
    // Find a client and other users
    $client = null;
    $others = [];
    
    foreach ($users as $user) {
        if ($user['role'] === 'client') {
            $client = $user;
        } else {
            $others[] = $user;
        }
    }
    
    if (!$client) {
        echo "No client user found\n";
        exit(1);
    }
    
    echo "Client: {$client['first_name']} {$client['last_name']} (ID: {$client['id']})\n";
    echo "Creating test messages...\n\n";
    
    // Clear existing messages for clean test
    $db->query("DELETE FROM messages");
    echo "Cleared existing messages\n";
    
    // Create test messages from different users to the client
    $testMessages = [
        [
            'sender_id' => $others[0]['id'] ?? 1,
            'recipient_id' => $client['id'],
            'subject' => 'Housing Application Follow-up',
            'content' => 'Hi, just following up on our last conversation about your housing application. Have you had a chance to gather the required documents?',
            'is_read' => 0
        ],
        [
            'sender_id' => $others[1]['id'] ?? 1,
            'recipient_id' => $client['id'],
            'subject' => 'Appointment Confirmation',
            'content' => 'Your appointment confirmation for December 18th at 2:00 PM has been scheduled. Please arrive 10 minutes early.',
            'is_read' => 0
        ],
        [
            'sender_id' => $others[0]['id'] ?? 1,
            'recipient_id' => $client['id'],
            'subject' => 'Medication Reminder',
            'content' => 'Please remember to take your medication as prescribed - twice daily with meals. If you have any questions, feel free to reach out.',
            'is_read' => 0
        ],
        [
            'sender_id' => 1, // Admin
            'recipient_id' => $client['id'],
            'subject' => 'Welcome to Tweak Easy',
            'content' => 'Welcome to Tweak Easy! Here are some tips to get started with our platform. You can manage your appointments, communicate with your care team, and order supplies all in one place.',
            'is_read' => 1
        ]
    ];
    
    $messageIds = [];
    foreach ($testMessages as $msg) {
        $id = $db->insert('messages', $msg);
        $messageIds[] = $id;
        echo "Created message ID {$id}: {$msg['subject']}\n";
    }
    
    // Create some replies to the first message
    if (isset($messageIds[0])) {
        $replyId = $db->insert('messages', [
            'sender_id' => $client['id'],
            'recipient_id' => $others[0]['id'],
            'subject' => 'RE: Housing Application Follow-up',
            'content' => 'Yes, I\'ve collected most of them. I\'m still waiting on my employment verification letter, but should have it by tomorrow.',
            'parent_message_id' => $messageIds[0],
            'is_read' => 1
        ]);
        echo "Created reply ID {$replyId}\n";
        
        $replyId2 = $db->insert('messages', [
            'sender_id' => $others[0]['id'],
            'recipient_id' => $client['id'],
            'subject' => 'RE: Housing Application Follow-up',
            'content' => 'That\'s great! Once you have everything, we can submit the application together. Would you like to schedule a time to meet this week?',
            'parent_message_id' => $messageIds[0],
            'is_read' => 0
        ]);
        echo "Created reply ID {$replyId2}\n";
    }
    
    // Create replies to appointment message
    if (isset($messageIds[1])) {
        $replyId = $db->insert('messages', [
            'sender_id' => $client['id'],
            'recipient_id' => $others[1]['id'],
            'subject' => 'RE: Appointment Confirmation',
            'content' => 'Thank you! I\'ll be there. Is there anything I need to bring?',
            'parent_message_id' => $messageIds[1],
            'is_read' => 1
        ]);
        echo "Created reply ID {$replyId}\n";
        
        $replyId2 = $db->insert('messages', [
            'sender_id' => $others[1]['id'],
            'recipient_id' => $client['id'],
            'subject' => 'RE: Appointment Confirmation',
            'content' => 'Please bring your ID and insurance card. We look forward to seeing you!',
            'parent_message_id' => $messageIds[1],
            'is_read' => 0
        ]);
        echo "Created reply ID {$replyId2}\n";
    }
    
    echo "\n✓ Successfully created " . count($testMessages) . " messages with replies\n";
    echo "\nMessage counts:\n";
    $unread = $db->fetch("SELECT COUNT(*) as count FROM messages WHERE recipient_id = {$client['id']} AND is_read = 0")['count'];
    $total = $db->fetch("SELECT COUNT(*) as count FROM messages WHERE recipient_id = {$client['id']} AND parent_message_id IS NULL")['count'];
    echo "  - Total conversations: {$total}\n";
    echo "  - Unread messages: {$unread}\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
