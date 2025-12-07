<?php
/**
 * Enhanced SQLite Database Setup Script with Mock Data Option
 * This script creates the database and optionally populates it with mock data
 * 
 * Usage: php database/setup_with_mock_data.php [--with-mock-data] [--mock-clients=N] [--mock-orders=N]
 */

// Parse command line arguments
$withMockData = false;
$mockOptions = [
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
    if ($arg === '--with-mock-data' || $arg === '--mock' || $arg === '-m') {
        $withMockData = true;
    }
    if (preg_match('/--mock-(\w+)=(\d+)/', $arg, $matches)) {
        $mockOptions[$matches[1]] = (int)$matches[2];
        $withMockData = true;
    }
}

$dbPath = __DIR__ . '/tweak_easy.db';

echo "===========================================\n";
echo "Tweak Easy - Database Setup\n";
echo "===========================================\n\n";

// Check if database already exists
if (file_exists($dbPath)) {
    echo "⚠️  Database already exists at: $dbPath\n";
    echo "Do you want to:\n";
    echo "  1) Keep existing database and skip setup\n";
    echo "  2) Delete and recreate database (ALL DATA WILL BE LOST)\n";
    echo "  3) Cancel\n";
    echo "Enter choice (1-3): ";
    
    $handle = fopen("php://stdin", "r");
    $choice = trim(fgets($handle));
    fclose($handle);
    
    // Validate input
    if (!in_array($choice, ['1', '2', '3'])) {
        echo "Invalid choice. Setup cancelled.\n";
        exit(1);
    }
    
    if ($choice === '2') {
        unlink($dbPath);
        echo "✓ Deleted existing database\n\n";
    } elseif ($choice === '1') {
        echo "✓ Keeping existing database\n\n";
        
        if ($withMockData) {
            echo "Proceeding to add mock data...\n\n";
            require_once __DIR__ . '/mock_data_generator.php';
            
            try {
                $generator = new MockDataGenerator($dbPath);
                $generator->generateUsers($mockOptions['clients'], $mockOptions['workers'], $mockOptions['providers']);
                $generator->generateClientProfiles();
                $generator->generateCarePlansAndGoals();
                $generator->generateOrders($mockOptions['orders']);
                $generator->generateMessages($mockOptions['messages']);
                $generator->generateAppointments($mockOptions['appointments']);
                $generator->generateReferrals($mockOptions['referrals']);
                $generator->generateCaseNotes($mockOptions['casenotes']);
                
                echo "\n✅ Mock data added successfully!\n";
            } catch (Exception $e) {
                echo "\n✗ Error adding mock data: " . $e->getMessage() . "\n";
                exit(1);
            }
        }
        exit(0);
    } else {
        echo "Setup cancelled.\n";
        exit(0);
    }
}

// Create SQLite database
try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Creating SQLite database at: $dbPath\n\n";
    
    // Read the SQLite schema
    $schemaFile = __DIR__ . '/schema_sqlite.sql';
    if (!file_exists($schemaFile)) {
        throw new Exception("Schema file not found: $schemaFile");
    }
    
    $schema = file_get_contents($schemaFile);
    
    // Split into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $schema)),
        function($stmt) { 
            return !empty($stmt) && 
                   !preg_match('/^--/', $stmt) && 
                   !preg_match('/^\/\*/', $stmt); 
        }
    );
    
    // Execute each statement
    $tableCount = 0;
    $insertCount = 0;
    
    foreach ($statements as $statement) {
        if (empty(trim($statement))) continue;
        
        try {
            $pdo->exec($statement);
            
            // Extract table name or operation for logging
            if (preg_match('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?(\w+)/i', $statement, $matches)) {
                echo "✓ Created table: {$matches[1]}\n";
                $tableCount++;
            } else if (preg_match('/INSERT INTO\s+(\w+)/i', $statement, $matches)) {
                $insertCount++;
            }
        } catch (PDOException $e) {
            echo "✗ Error: " . $e->getMessage() . "\n";
            echo "  Statement: " . substr($statement, 0, 100) . "...\n";
            throw $e;
        }
    }
    
    echo "\n✅ Database schema created successfully!\n";
    echo "   Tables created: $tableCount\n";
    echo "   Default data inserted: $insertCount rows\n";
    echo "   Database size: " . number_format(filesize($dbPath)) . " bytes\n\n";
    
    // Check if mock data should be generated
    if ($withMockData) {
        echo "===========================================\n";
        echo "Generating Mock Data\n";
        echo "===========================================\n\n";
        
        require_once __DIR__ . '/mock_data_generator.php';
        
        $generator = new MockDataGenerator($dbPath);
        $generator->generateUsers($mockOptions['clients'], $mockOptions['workers'], $mockOptions['providers']);
        $generator->generateClientProfiles();
        $generator->generateCarePlansAndGoals();
        $generator->generateOrders($mockOptions['orders']);
        $generator->generateMessages($mockOptions['messages']);
        $generator->generateAppointments($mockOptions['appointments']);
        $generator->generateReferrals($mockOptions['referrals']);
        $generator->generateCaseNotes($mockOptions['casenotes']);
        
        echo "\n===========================================\n";
        echo "✅ Mock data generation complete!\n";
        echo "===========================================\n";
        echo "Generated:\n";
        echo "  - " . $mockOptions['clients'] . " client users\n";
        echo "  - " . $mockOptions['workers'] . " outreach workers\n";
        echo "  - " . $mockOptions['providers'] . " service providers\n";
        echo "  - " . $mockOptions['orders'] . " orders\n";
        echo "  - " . $mockOptions['messages'] . " messages\n";
        echo "  - " . $mockOptions['appointments'] . " appointments\n";
        echo "  - " . $mockOptions['referrals'] . " referrals\n";
        echo "  - " . $mockOptions['casenotes'] . " case notes\n";
        echo "  - Plus care plans, goals, and milestones\n\n";
    } else {
        echo "===========================================\n";
        echo "Mock Data Not Generated\n";
        echo "===========================================\n";
        echo "To generate mock data, run:\n";
        echo "  php database/setup_with_mock_data.php --with-mock-data\n\n";
        echo "Or generate it separately:\n";
        echo "  php database/mock_data_generator.php\n\n";
    }
    
    echo "===========================================\n";
    echo "Setup Complete!\n";
    echo "===========================================\n\n";
    echo "Default admin credentials:\n";
    echo "  Username: admin\n";
    echo "  Email: admin@tweakeasy.org\n";
    echo "  Password: Admin@123\n\n";
    
    if ($withMockData) {
        echo "Mock user credentials:\n";
        echo "  Clients: Check generated usernames, password: Client@123\n";
        echo "  Workers: username like worker_firstname123, password: Worker@123\n";
        echo "  Providers: username like provider_firstname123, password: Provider@123\n\n";
    }
    
    echo "Start the development server:\n";
    echo "  php -S localhost:8000 server.php\n\n";
    echo "Then visit: http://localhost:8000\n\n";
    
} catch (Exception $e) {
    echo "\n✗ Fatal Error: " . $e->getMessage() . "\n";
    echo "Database setup failed.\n";
    exit(1);
}
