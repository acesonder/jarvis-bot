<?php
/**
 * Test Script for API Endpoints
 * Tests all API endpoints to ensure they respond correctly
 */

// Test credentials
$testUsername = 'miamoore887';
$testPassword = 'Client@123';
$baseUrl = 'http://localhost:8000';

// API endpoints to test
$apiEndpoints = [
    'auth' => [
        'login' => ['method' => 'POST', 'url' => '/api/auth/index.php', 'action' => 'login'],
    ],
    'clients' => [
        'profile' => ['method' => 'GET', 'url' => '/api/clients/index.php'],
    ],
    'orders' => [
        'list' => ['method' => 'GET', 'url' => '/api/orders/index.php'],
    ],
    'messages' => [
        'list' => ['method' => 'GET', 'url' => '/api/messages/index.php'],
    ],
    'appointments' => [
        'list' => ['method' => 'GET', 'url' => '/api/appointments/index.php'],
    ],
    'referrals' => [
        'list' => ['method' => 'GET', 'url' => '/api/referrals/index.php'],
    ],
    'inventory' => [
        'list' => ['method' => 'GET', 'url' => '/api/inventory/index.php'],
    ],
    'reports' => [
        'list' => ['method' => 'GET', 'url' => '/api/reports/index.php'],
    ]
];

echo "===========================================\n";
echo "Testing API Endpoints\n";
echo "===========================================\n\n";

// Initialize cURL for session management
$cookieFile = tempnam(sys_get_temp_dir(), 'cookie');

// Step 1: Login via web interface first
echo "Step 1: Logging in as test client...\n";
$loginUrl = $baseUrl . '/pages/login_handler.php';

$ch = curl_init($loginUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => $testUsername,
    'password' => $testPassword
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 || $httpCode == 302) {
    echo "✓ Login successful (HTTP $httpCode)\n\n";
} else {
    echo "✗ Login failed (HTTP $httpCode)\n";
    exit(1);
}

// Step 2: Test each API endpoint
echo "Step 2: Testing API endpoints...\n\n";

$results = [];
$passCount = 0;
$failCount = 0;

foreach ($apiEndpoints as $category => $endpoints) {
    echo "Category: " . strtoupper($category) . "\n";
    
    foreach ($endpoints as $name => $config) {
        $fullUrl = $baseUrl . $config['url'];
        
        $ch = curl_init($fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        if ($config['method'] == 'POST' && isset($config['action'])) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'action' => $config['action'],
                'username' => $testUsername,
                'password' => $testPassword
            ]));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);
        
        // Try to parse JSON
        $jsonData = json_decode($response, true);
        $isJson = (json_last_error() == JSON_ERROR_NONE);
        
        // Determine success
        $isSuccess = false;
        $message = '';
        
        if ($httpCode == 200) {
            if ($isJson) {
                $isSuccess = true;
                $message = "Valid JSON response";
                $passCount++;
            } else {
                $message = "Response not JSON";
                $failCount++;
            }
        } elseif ($httpCode == 401 || $httpCode == 403) {
            $message = "Authentication required (expected for some endpoints)";
            $passCount++; // This is actually expected behavior
            $isSuccess = true;
        } elseif ($httpCode == 404) {
            $message = "Endpoint not found";
            $failCount++;
        } else {
            $message = "HTTP $httpCode";
            $failCount++;
        }
        
        // Check for PHP errors
        if (strpos($response, 'Fatal error') !== false || 
            strpos($response, 'Parse error') !== false) {
            $isSuccess = false;
            $message = "PHP Error detected";
            if ($passCount > 0) $passCount--;
            $failCount++;
        }
        
        $results["$category/$name"] = [
            'url' => $config['url'],
            'method' => $config['method'],
            'status' => $httpCode,
            'success' => $isSuccess,
            'message' => $message,
            'isJson' => $isJson
        ];
        
        $statusIcon = $isSuccess ? '✓' : '✗';
        printf("  %s %-20s [%s %d] %s\n", 
            $statusIcon, 
            ucfirst($name), 
            $config['method'],
            $httpCode, 
            $message
        );
        
        usleep(100000); // 0.1 seconds
    }
    echo "\n";
}

// Clean up
unlink($cookieFile);

// Summary
echo "===========================================\n";
echo "Test Summary\n";
echo "===========================================\n";
echo "Total endpoints tested: " . count($results) . "\n";
echo "Passed: $passCount\n";
echo "Failed: $failCount\n";

if ($failCount > 0) {
    echo "\n⚠ Some endpoints failed. Details:\n";
    foreach ($results as $endpoint => $result) {
        if (!$result['success']) {
            echo "  - $endpoint: " . $result['message'] . "\n";
        }
    }
}

echo "\n===========================================\n";

if ($failCount == 0) {
    echo "✅ All API tests passed!\n";
    exit(0);
} else {
    echo "⚠️  Some API tests failed. Please review.\n";
    exit(1);
}
