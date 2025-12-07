<?php
/**
 * Test Script for Client Dashboard Links
 * Tests all client pages to ensure they load without errors
 */

// Test credentials
$testUsername = 'miamoore887';
$testPassword = 'Client@123';
$baseUrl = 'http://localhost:8000';

// Client pages to test
$clientPages = [
    'dashboard' => '/pages/client/dashboard.php',
    'care-plan' => '/pages/client/care-plan.php',
    'assessments' => '/pages/client/assessments.php',
    'goals' => '/pages/client/goals.php',
    'orders' => '/pages/client/orders.php',
    'messages' => '/pages/client/messages.php',
    'appointments' => '/pages/client/appointments.php',
    'resources' => '/pages/client/resources.php',
    'profile' => '/pages/client/profile.php',
    'settings' => '/pages/client/settings.php'
];

echo "===========================================\n";
echo "Testing Client Dashboard Links\n";
echo "===========================================\n\n";

// Initialize cURL for session management
$cookieFile = tempnam(sys_get_temp_dir(), 'cookie');

// Step 1: Login
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
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 || $httpCode == 302) {
    echo "✓ Login successful (HTTP $httpCode)\n\n";
} else {
    echo "✗ Login failed (HTTP $httpCode)\n";
    echo "Response: " . substr($response, 0, 500) . "\n";
    exit(1);
}

// Step 2: Test each client page
echo "Step 2: Testing client dashboard pages...\n\n";

$results = [];
$passCount = 0;
$failCount = 0;

foreach ($clientPages as $pageName => $pageUrl) {
    $fullUrl = $baseUrl . $pageUrl;
    
    $ch = curl_init($fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $pageContent = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    
    // Check for success
    $isSuccess = false;
    $message = '';
    
    if ($httpCode == 200) {
        // Check if page has expected content
        if (strpos($pageContent, 'Tweak Easy') !== false || 
            strpos($pageContent, 'Dashboard') !== false ||
            strpos($pageContent, '<!DOCTYPE html>') !== false) {
            $isSuccess = true;
            $message = "Page loads successfully";
            $passCount++;
        } else {
            $message = "Page loaded but content looks incorrect";
            $failCount++;
        }
    } elseif ($httpCode == 302 || $httpCode == 301) {
        $message = "Page redirects (may need authentication)";
        $failCount++;
    } else {
        $message = "HTTP Error $httpCode";
        $failCount++;
    }
    
    // Check for PHP errors
    if (strpos($pageContent, 'Fatal error') !== false || 
        strpos($pageContent, 'Parse error') !== false ||
        strpos($pageContent, 'Warning:') !== false) {
        $isSuccess = false;
        $message = "PHP Error detected";
        if ($passCount > 0) $passCount--;
        $failCount++;
    }
    
    $results[$pageName] = [
        'url' => $pageUrl,
        'status' => $httpCode,
        'success' => $isSuccess,
        'message' => $message
    ];
    
    $statusIcon = $isSuccess ? '✓' : '✗';
    $statusColor = $isSuccess ? '' : '';
    
    printf("  %s %-20s [HTTP %d] %s\n", 
        $statusIcon, 
        ucfirst($pageName), 
        $httpCode, 
        $message
    );
    
    // Brief pause between requests
    usleep(100000); // 0.1 seconds
}

// Clean up cookie file
unlink($cookieFile);

// Summary
echo "\n===========================================\n";
echo "Test Summary\n";
echo "===========================================\n";
echo "Total pages tested: " . count($clientPages) . "\n";
echo "Passed: $passCount\n";
echo "Failed: $failCount\n";

if ($failCount > 0) {
    echo "\n⚠ Some pages failed. Details:\n";
    foreach ($results as $page => $result) {
        if (!$result['success']) {
            echo "  - " . ucfirst($page) . ": " . $result['message'] . "\n";
        }
    }
}

echo "\n===========================================\n";

if ($failCount == 0) {
    echo "✅ All tests passed!\n";
    exit(0);
} else {
    echo "⚠️  Some tests failed. Please review.\n";
    exit(1);
}
