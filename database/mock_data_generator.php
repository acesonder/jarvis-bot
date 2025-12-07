<?php
/**
 * Mock Data Generator for Tweak Easy
 * Generates realistic sample data for testing and demonstration
 * 
 * Usage: php database/mock_data_generator.php [options]
 * Options:
 *   --clients=N     Number of client profiles to generate (default: 15)
 *   --orders=N      Number of orders to generate (default: 25)
 *   --messages=N    Number of messages to generate (default: 30)
 *   --appointments=N Number of appointments to generate (default: 20)
 *   --referrals=N   Number of referrals to generate (default: 12)
 */

class MockDataGenerator {
    private $pdo;
    private $userIds = [];
    private $clientIds = [];
    private $workerIds = [];
    private $providerIds = [];
    private $productIds = [];
    private $serviceProviderIds = [];
    
    // Sample data arrays
    private $firstNames = ['Emma', 'Liam', 'Olivia', 'Noah', 'Ava', 'Ethan', 'Sophia', 'Mason', 
                           'Isabella', 'William', 'Mia', 'James', 'Charlotte', 'Benjamin', 'Amelia',
                           'Lucas', 'Harper', 'Henry', 'Evelyn', 'Alexander'];
    
    private $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 
                          'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 
                          'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'];
    
    private $streetNames = ['Main St', 'Oak Ave', 'Maple Dr', 'Cedar Ln', 'Park Blvd', 
                            'Washington St', 'Lake St', 'Hill Rd', 'Pine St', 'River Rd'];
    
    private $cities = ['Seattle', 'Portland', 'San Francisco', 'Los Angeles', 'San Diego',
                       'Phoenix', 'Denver', 'Austin', 'Chicago', 'New York'];
    
    private $appointmentTypes = ['intake', 'follow-up', 'check-in', 'assessment', 'counseling'];
    
    private $appointmentLocations = ['Main Office', 'Community Center', 'Mobile Unit', 
                                     'Partner Facility', 'Client Home'];
    
    private $messageSubjects = [
        'Appointment Reminder',
        'Checking in on your progress',
        'Supply pickup available',
        'Resource information',
        'Follow-up needed',
        'Important update',
        'Weekly check-in',
        'Documentation needed',
        'Referral update',
        'Emergency contact update'
    ];
    
    public function __construct($dbPath) {
        $this->pdo = new PDO("sqlite:$dbPath");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->loadExistingData();
    }
    
    private function loadExistingData() {
        // Load product IDs
        $stmt = $this->pdo->query("SELECT id FROM products");
        $this->productIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Load service provider IDs
        $stmt = $this->pdo->query("SELECT id FROM service_providers");
        $this->serviceProviderIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Load user IDs by role
        $stmt = $this->pdo->query("SELECT id FROM users WHERE role = 'client'");
        $this->clientIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $stmt = $this->pdo->query("SELECT id FROM users WHERE role = 'outreach_worker'");
        $this->workerIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $stmt = $this->pdo->query("SELECT id FROM users WHERE role = 'service_provider'");
        $this->providerIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    private function randomDate($startDate, $endDate) {
        $timestamp = mt_rand(strtotime($startDate), strtotime($endDate));
        return date('Y-m-d H:i:s', $timestamp);
    }
    
    private function randomElement($array) {
        return $array[array_rand($array)];
    }
    
    public function generateUsers($clientCount, $workerCount, $providerCount) {
        echo "Generating users...\n";
        
        // Generate clients
        for ($i = 0; $i < $clientCount; $i++) {
            $firstName = $this->randomElement($this->firstNames);
            $lastName = $this->randomElement($this->lastNames);
            $username = strtolower($firstName . $lastName . rand(100, 999));
            $email = strtolower($firstName . '.' . $lastName . rand(1, 99) . '@example.com');
            
            $stmt = $this->pdo->prepare("
                INSERT INTO users (username, email, password_hash, first_name, last_name, 
                                   phone, role, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, 'client', 'active', ?)
            ");
            
            $phone = '555-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $createdAt = $this->randomDate('-6 months', 'now');
            
            $stmt->execute([
                $username,
                $email,
                password_hash('Client@123', PASSWORD_DEFAULT),
                $firstName,
                $lastName,
                $phone,
                $createdAt
            ]);
            
            $this->clientIds[] = $this->pdo->lastInsertId();
            echo "  ✓ Created client: $firstName $lastName\n";
        }
        
        // Generate outreach workers
        for ($i = 0; $i < $workerCount; $i++) {
            $firstName = $this->randomElement($this->firstNames);
            $lastName = $this->randomElement($this->lastNames);
            $username = 'worker_' . strtolower($firstName . rand(100, 999));
            $email = 'worker.' . strtolower($firstName . $lastName) . '@tweakeasy.org';
            
            $stmt = $this->pdo->prepare("
                INSERT INTO users (username, email, password_hash, first_name, last_name, 
                                   phone, role, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, 'outreach_worker', 'active', ?)
            ");
            
            $phone = '555-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $createdAt = $this->randomDate('-1 year', 'now');
            
            $stmt->execute([
                $username,
                $email,
                password_hash('Worker@123', PASSWORD_DEFAULT),
                $firstName,
                $lastName,
                $phone,
                $createdAt
            ]);
            
            $this->workerIds[] = $this->pdo->lastInsertId();
            echo "  ✓ Created worker: $firstName $lastName\n";
        }
        
        // Generate service providers
        for ($i = 0; $i < $providerCount; $i++) {
            $firstName = $this->randomElement($this->firstNames);
            $lastName = $this->randomElement($this->lastNames);
            $username = 'provider_' . strtolower($firstName . rand(100, 999));
            $email = strtolower($firstName . '.' . $lastName) . '@provider.org';
            
            $stmt = $this->pdo->prepare("
                INSERT INTO users (username, email, password_hash, first_name, last_name, 
                                   phone, role, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, 'service_provider', 'active', ?)
            ");
            
            $phone = '555-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $createdAt = $this->randomDate('-1 year', 'now');
            
            $stmt->execute([
                $username,
                $email,
                password_hash('Provider@123', PASSWORD_DEFAULT),
                $firstName,
                $lastName,
                $phone,
                $createdAt
            ]);
            
            $this->providerIds[] = $this->pdo->lastInsertId();
            echo "  ✓ Created provider: $firstName $lastName\n";
        }
    }
    
    public function generateClientProfiles() {
        echo "\nGenerating client profiles...\n";
        
        $housingStatuses = ['housed', 'homeless', 'transitional', 'unknown'];
        $riskLevels = ['low', 'medium', 'high', 'critical'];
        
        foreach ($this->clientIds as $clientId) {
            $dob = date('Y-m-d', strtotime('-' . rand(20, 65) . ' years'));
            $assignedWorker = !empty($this->workerIds) ? $this->randomElement($this->workerIds) : null;
            
            $stmt = $this->pdo->prepare("
                INSERT INTO client_profiles (user_id, date_of_birth, housing_status, 
                                             health_notes, emergency_contact_name, 
                                             emergency_contact_phone, risk_level, 
                                             assigned_worker_id, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $clientId,
                $dob,
                $this->randomElement($housingStatuses),
                'Health notes for client #' . $clientId,
                $this->randomElement($this->firstNames) . ' ' . $this->randomElement($this->lastNames),
                '555-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
                $this->randomElement($riskLevels),
                $assignedWorker,
                $this->randomDate('-6 months', 'now')
            ]);
            
            echo "  ✓ Created profile for client ID: $clientId\n";
        }
    }
    
    public function generateCarePlansAndGoals() {
        echo "\nGenerating care plans and goals...\n";
        
        // Get client profile IDs
        $stmt = $this->pdo->query("SELECT id, user_id FROM client_profiles");
        $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $carePlanTitles = [
            'Substance Use Reduction Plan',
            'Housing Stability Plan',
            'Health & Wellness Plan',
            'Social Support Development',
            'Employment Readiness Plan'
        ];
        
        $goalTitles = [
            'Reduce substance use frequency',
            'Secure stable housing',
            'Complete health assessment',
            'Attend support group meetings',
            'Obtain identification documents',
            'Apply for benefits',
            'Develop daily routine',
            'Improve nutrition'
        ];
        
        foreach ($profiles as $profile) {
            // Create 1-2 care plans per client
            $numPlans = rand(1, 2);
            for ($i = 0; $i < $numPlans; $i++) {
                $worker = !empty($this->workerIds) ? $this->randomElement($this->workerIds) : 1;
                
                $stmt = $this->pdo->prepare("
                    INSERT INTO care_plans (client_id, title, description, status, 
                                           start_date, target_end_date, created_by, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $startDate = $this->randomDate('-3 months', 'now');
                $endDate = date('Y-m-d', strtotime($startDate . ' +6 months'));
                $status = rand(0, 10) > 3 ? 'active' : 'completed';
                
                $stmt->execute([
                    $profile['id'],
                    $this->randomElement($carePlanTitles),
                    'Comprehensive plan for client development and support',
                    $status,
                    $startDate,
                    $endDate,
                    $worker,
                    $startDate
                ]);
                
                $carePlanId = $this->pdo->lastInsertId();
                echo "  ✓ Created care plan ID: $carePlanId\n";
                
                // Create 2-4 goals per care plan
                $numGoals = rand(2, 4);
                for ($j = 0; $j < $numGoals; $j++) {
                    $goalStmt = $this->pdo->prepare("
                        INSERT INTO goals (care_plan_id, title, description, progress, 
                                          status, due_date, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    
                    $progress = rand(0, 100);
                    $goalStatus = $progress == 100 ? 'completed' : ($progress > 0 ? 'in_progress' : 'pending');
                    
                    $goalStmt->execute([
                        $carePlanId,
                        $this->randomElement($goalTitles),
                        'Detailed goal description and action steps',
                        $progress,
                        $goalStatus,
                        date('Y-m-d', strtotime($startDate . ' +' . rand(1, 6) . ' months')),
                        $startDate
                    ]);
                    
                    $goalId = $this->pdo->lastInsertId();
                    
                    // Create 2-3 milestones per goal
                    for ($k = 1; $k <= rand(2, 3); $k++) {
                        $milestoneStmt = $this->pdo->prepare("
                            INSERT INTO milestones (goal_id, title, completed, completed_at, created_at)
                            VALUES (?, ?, ?, ?, ?)
                        ");
                        
                        $isCompleted = rand(0, 10) > 5 ? 1 : 0;
                        $completedAt = $isCompleted ? $this->randomDate($startDate, 'now') : null;
                        
                        $milestoneStmt->execute([
                            $goalId,
                            "Milestone $k for goal",
                            $isCompleted,
                            $completedAt,
                            $startDate
                        ]);
                    }
                }
            }
        }
    }
    
    public function generateOrders($count) {
        echo "\nGenerating orders...\n";
        
        if (empty($this->productIds)) {
            echo "  ✗ No products available. Skipping orders.\n";
            return;
        }
        
        // Get client profile IDs
        $stmt = $this->pdo->query("SELECT id FROM client_profiles");
        $profileIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($profileIds)) {
            echo "  ✗ No client profiles available. Skipping orders.\n";
            return;
        }
        
        $orderTypes = ['delivery', 'pickup'];
        $statuses = ['pending', 'processing', 'ready', 'completed', 'cancelled'];
        
        for ($i = 0; $i < $count; $i++) {
            $clientId = $this->randomElement($profileIds);
            $orderType = $this->randomElement($orderTypes);
            $status = $this->randomElement($statuses);
            $placedBy = !empty($this->workerIds) ? $this->randomElement($this->workerIds) : 1;
            $createdAt = $this->randomDate('-3 months', 'now');
            
            // Generate order number
            $orderDate = date('Ymd', strtotime($createdAt));
            $orderNum = 'ORD-' . $orderDate . '-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT);
            
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (client_id, order_number, order_type, status, 
                                   scheduled_date, notes, placed_by, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $scheduledDate = date('Y-m-d H:i:s', strtotime($createdAt . ' +' . rand(1, 7) . ' days'));
            
            $stmt->execute([
                $clientId,
                $orderNum,
                $orderType,
                $status,
                $scheduledDate,
                'Order notes and special instructions',
                $placedBy,
                $createdAt
            ]);
            
            $orderId = $this->pdo->lastInsertId();
            
            // Add 1-5 items to each order
            $numItems = rand(1, 5);
            $selectedProducts = array_rand(array_flip($this->productIds), min($numItems, count($this->productIds)));
            if (!is_array($selectedProducts)) $selectedProducts = [$selectedProducts];
            
            foreach ($selectedProducts as $productId) {
                $quantity = rand(1, 20);
                
                $itemStmt = $this->pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity)
                    VALUES (?, ?, ?)
                ");
                
                $itemStmt->execute([$orderId, $productId, $quantity]);
                
                // Create inventory transaction
                if ($status == 'completed') {
                    $transStmt = $this->pdo->prepare("
                        INSERT INTO inventory_transactions (product_id, transaction_type, 
                                                           quantity, reference_type, 
                                                           reference_id, performed_by, created_at)
                        VALUES (?, 'out', ?, 'order', ?, ?, ?)
                    ");
                    
                    $transStmt->execute([$productId, $quantity, $orderId, $placedBy, $createdAt]);
                    
                    // Reduce stock
                    $updateStmt = $this->pdo->prepare("
                        UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?
                    ");
                    $updateStmt->execute([$quantity, $productId]);
                }
            }
            
            echo "  ✓ Created order: $orderNum with $numItems items\n";
        }
    }
    
    public function generateMessages($count) {
        echo "\nGenerating messages...\n";
        
        if (empty($this->clientIds) && empty($this->workerIds)) {
            echo "  ✗ No users available. Skipping messages.\n";
            return;
        }
        
        $allUserIds = array_merge($this->clientIds, $this->workerIds, $this->providerIds);
        if (count($allUserIds) < 2) {
            echo "  ✗ Need at least 2 users for messages. Skipping.\n";
            return;
        }
        
        for ($i = 0; $i < $count; $i++) {
            // Pick random sender and receiver
            $senderId = $this->randomElement($allUserIds);
            $recipientId = $this->randomElement(array_diff($allUserIds, [$senderId]));
            
            $isUrgent = rand(0, 10) > 8 ? 1 : 0;
            $isRead = rand(0, 10) > 3 ? 1 : 0;
            $createdAt = $this->randomDate('-2 months', 'now');
            
            $stmt = $this->pdo->prepare("
                INSERT INTO messages (sender_id, recipient_id, subject, content, 
                                     is_urgent, is_read, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $subject = $this->randomElement($this->messageSubjects);
            $content = "This is a sample message content for testing purposes. Message ID: $i. " . 
                       "Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
            
            $stmt->execute([
                $senderId,
                $recipientId,
                $subject,
                $content,
                $isUrgent,
                $isRead,
                $createdAt
            ]);
            
            echo "  ✓ Created message from user $senderId to user $recipientId\n";
        }
    }
    
    public function generateAppointments($count) {
        echo "\nGenerating appointments...\n";
        
        // Get client profile IDs
        $stmt = $this->pdo->query("SELECT id FROM client_profiles");
        $profileIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($profileIds)) {
            echo "  ✗ No client profiles available. Skipping appointments.\n";
            return;
        }
        
        $statuses = ['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show'];
        
        for ($i = 0; $i < $count; $i++) {
            $clientId = $this->randomElement($profileIds);
            $provider = !empty($this->providerIds) ? $this->randomElement($this->providerIds) : 1;
            $status = $this->randomElement($statuses);
            
            // Generate appointment date and time
            $appointmentDateTime = $this->randomDate('-1 month', '+2 months');
            $appointmentDate = date('Y-m-d', strtotime($appointmentDateTime));
            $appointmentTime = date('H:i:s', strtotime($appointmentDateTime));
            $duration = rand(15, 120); // 15 minutes to 2 hours
            
            $stmt = $this->pdo->prepare("
                INSERT INTO appointments (client_id, provider_id, appointment_type, 
                                         scheduled_date, scheduled_time, duration_minutes, 
                                         location, status, notes, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $clientId,
                $provider,
                $this->randomElement($this->appointmentTypes),
                $appointmentDate,
                $appointmentTime,
                $duration,
                $this->randomElement($this->appointmentLocations),
                $status,
                'Appointment notes and details',
                $this->randomDate('-1 month', 'now')
            ]);
            
            echo "  ✓ Created appointment for client $clientId on " . $appointmentDate . "\n";
        }
    }
    
    public function generateReferrals($count) {
        echo "\nGenerating referrals...\n";
        
        // Get client profile IDs
        $stmt = $this->pdo->query("SELECT id FROM client_profiles");
        $profileIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($profileIds) || empty($this->serviceProviderIds)) {
            echo "  ✗ Missing required data. Skipping referrals.\n";
            return;
        }
        
        $statuses = ['pending', 'accepted', 'in_progress', 'completed', 'declined'];
        $serviceTypes = ['housing', 'healthcare', 'mental_health', 'legal', 'employment'];
        
        for ($i = 0; $i < $count; $i++) {
            $clientId = $this->randomElement($profileIds);
            $referredBy = !empty($this->workerIds) ? $this->randomElement($this->workerIds) : 1;
            $referredTo = !empty($this->providerIds) ? $this->randomElement($this->providerIds) : null;
            $status = $this->randomElement($statuses);
            $createdAt = $this->randomDate('-2 months', 'now');
            
            // Get a service provider name
            $providerStmt = $this->pdo->query("SELECT name FROM service_providers ORDER BY RANDOM() LIMIT 1");
            $providerName = $providerStmt->fetchColumn();
            
            $stmt = $this->pdo->prepare("
                INSERT INTO referrals (client_id, referred_by, referred_to, 
                                      service_provider_name, service_type, 
                                      reason, status, outcome, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $outcome = $status == 'completed' ? 'Successfully connected to services' : null;
            
            $stmt->execute([
                $clientId,
                $referredBy,
                $referredTo,
                $providerName,
                $this->randomElement($serviceTypes),
                'Referral reason and client needs description',
                $status,
                $outcome,
                $createdAt
            ]);
            
            echo "  ✓ Created referral for client $clientId to $providerName\n";
        }
    }
    
    public function generateCaseNotes($count) {
        echo "\nGenerating case notes...\n";
        
        // Get client profile IDs
        $stmt = $this->pdo->query("SELECT id FROM client_profiles");
        $profileIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($profileIds) || empty($this->workerIds)) {
            echo "  ✗ Missing required data. Skipping case notes.\n";
            return;
        }
        
        $noteTypes = ['progress', 'incident', 'assessment', 'contact', 'other'];
        
        for ($i = 0; $i < $count; $i++) {
            $clientId = $this->randomElement($profileIds);
            $createdBy = $this->randomElement($this->workerIds);
            $createdAt = $this->randomDate('-3 months', 'now');
            
            $stmt = $this->pdo->prepare("
                INSERT INTO case_notes (client_id, note_type, content, created_by, created_at)
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $content = "Case note entry #$i. Client showed progress in their recovery journey. " .
                      "Discussed goals and upcoming appointments. Client is engaged and motivated.";
            
            $stmt->execute([
                $clientId,
                $this->randomElement($noteTypes),
                $content,
                $createdBy,
                $createdAt
            ]);
            
            echo "  ✓ Created case note for client $clientId\n";
        }
    }
}

// Parse command line arguments
$options = [
    'clients' => 15,
    'workers' => 5,
    'providers' => 3,
    'orders' => 25,
    'messages' => 30,
    'appointments' => 20,
    'referrals' => 12,
    'casenotes' => 40
];

foreach ($argv as $arg) {
    if (preg_match('/--(\w+)=(\d+)/', $arg, $matches)) {
        $options[$matches[1]] = (int)$matches[2];
    }
}

// Main execution
try {
    $dbPath = __DIR__ . '/tweak_easy.db';
    
    if (!file_exists($dbPath)) {
        die("Error: Database file not found at $dbPath\n" . 
            "Please run setup_sqlite.php first.\n");
    }
    
    echo "===========================================\n";
    echo "Tweak Easy - Mock Data Generator\n";
    echo "===========================================\n\n";
    
    $generator = new MockDataGenerator($dbPath);
    
    // Generate data
    $generator->generateUsers($options['clients'], $options['workers'], $options['providers']);
    $generator->generateClientProfiles();
    $generator->generateCarePlansAndGoals();
    $generator->generateOrders($options['orders']);
    $generator->generateMessages($options['messages']);
    $generator->generateAppointments($options['appointments']);
    $generator->generateReferrals($options['referrals']);
    $generator->generateCaseNotes($options['casenotes']);
    
    echo "\n===========================================\n";
    echo "✅ Mock data generation complete!\n";
    echo "===========================================\n";
    echo "Generated:\n";
    echo "  - " . $options['clients'] . " client users\n";
    echo "  - " . $options['workers'] . " outreach workers\n";
    echo "  - " . $options['providers'] . " service providers\n";
    echo "  - " . $options['orders'] . " orders\n";
    echo "  - " . $options['messages'] . " messages\n";
    echo "  - " . $options['appointments'] . " appointments\n";
    echo "  - " . $options['referrals'] . " referrals\n";
    echo "  - " . $options['casenotes'] . " case notes\n";
    echo "  - Plus care plans, goals, and milestones\n\n";
    
    echo "Default credentials for generated users:\n";
    echo "  Clients: username (e.g., emmasmith123), password: Client@123\n";
    echo "  Workers: username (e.g., worker_liam456), password: Worker@123\n";
    echo "  Providers: username (e.g., provider_olivia789), password: Provider@123\n";
    
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
