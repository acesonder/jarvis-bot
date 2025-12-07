<?php
/**
 * API Validation Test Script
 * Usage: php tests/api_validation_test.php
 */

class APIValidator {
    private $baseUrl = 'http://localhost:8000';
    private $sessionId;
    private $csrfToken;
    private $testResults = [];
    private $failedTests = [];
    
    private function makeRequest($method, $path, $data = null, $requiresAuth = false) {
        $url = $this->baseUrl . $path;
        $ch = curl_init($url);
        
        $headers = ['Content-Type: application/json'];
        
        if ($requiresAuth && $this->csrfToken) {
            $headers[] = 'X-CSRF-Token: ' . $this->csrfToken;
        }
        
        if ($this->sessionId) {
            $headers[] = 'Cookie: PHPSESSID=' . $this->sessionId;
        }
        
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADER => true,
            CURLOPT_TIMEOUT => 10
        ]);
        
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        if (preg_match('/Set-Cookie: PHPSESSID=([^;]+)/', $header, $matches)) {
            $this->sessionId = $matches[1];
        }
        
        curl_close($ch);
        
        return [
            'code' => $httpCode,
            'body' => json_decode($body, true),
            'raw' => $body
        ];
    }
    
    /**
     * Execute a single test case
     * @param string $name Test name for display
     * @param callable $callback Test function that returns bool
     */
    private function test($name, $callback) {
        echo "Testing: $name... ";
        try {
            if ($callback()) {
                echo "✓ PASS\n";
                $this->testResults[] = ['name' => $name, 'status' => 'pass'];
            } else {
                echo "✗ FAIL\n";
                $this->failedTests[] = $name;
            }
        } catch (Exception $e) {
            echo "✗ ERROR: " . $e->getMessage() . "\n";
            $this->failedTests[] = $name;
        }
    }
    
    /**
     * Run all validation tests
     * Executes comprehensive API endpoint testing including authentication,
     * data retrieval, and security features
     * @return bool True if all tests pass, false otherwise
     */
    public function runTests() {
        echo "===========================================\n";
        echo "API Validation Test Suite\n";
        echo "===========================================\n\n";
        
        $this->test('Landing Page Loads', function() {
            $response = $this->makeRequest('GET', '/');
            return $response['code'] === 200 && strpos($response['raw'], 'Tweak Easy') !== false;
        });
        
        $this->test('CSRF Token Generation', function() {
            $response = $this->makeRequest('GET', '/api/auth/csrf-token');
            if ($response['code'] === 200 && isset($response['body']['data']['csrf_token'])) {
                $this->csrfToken = $response['body']['data']['csrf_token'];
                return true;
            }
            return false;
        });
        
        $this->test('Admin Login', function() {
            $response = $this->makeRequest('POST', '/api/auth/login', [
                'username' => 'admin',
                'password' => 'Admin@123'
            ], true);
            return $response['code'] === 200 && $response['body']['success'] === true;
        });
        
        $this->test('Get Current User Info', function() {
            $response = $this->makeRequest('GET', '/api/auth/me');
            return $response['code'] === 200 && $response['body']['data']['username'] === 'admin';
        });
        
        $this->test('List Inventory Products', function() {
            $response = $this->makeRequest('GET', '/api/inventory');
            return $response['code'] === 200 && isset($response['body']['data']['products']);
        });
        
        $this->test('Low Stock Alerts', function() {
            $response = $this->makeRequest('GET', '/api/inventory/low-stock');
            return $response['code'] === 200;
        });
        
        $this->test('List Clients', function() {
            $response = $this->makeRequest('GET', '/api/clients');
            return $response['code'] === 200;
        });
        
        $this->test('List Orders', function() {
            $response = $this->makeRequest('GET', '/api/orders');
            return $response['code'] === 200;
        });
        
        $this->test('List Messages', function() {
            $response = $this->makeRequest('GET', '/api/messages');
            return $response['code'] === 200;
        });
        
        $this->test('Unread Message Count', function() {
            $response = $this->makeRequest('GET', '/api/messages/unread-count');
            return $response['code'] === 200;
        });
        
        $this->test('List Appointments', function() {
            $response = $this->makeRequest('GET', '/api/appointments');
            return $response['code'] === 200;
        });
        
        $this->test('List Referrals', function() {
            $response = $this->makeRequest('GET', '/api/referrals');
            return $response['code'] === 200;
        });
        
        $this->test('KPI Dashboard Report', function() {
            $response = $this->makeRequest('GET', '/api/reports/kpi');
            return $response['code'] === 200;
        });
        
        $this->test('Demographics Report', function() {
            $response = $this->makeRequest('GET', '/api/reports/demographics');
            return $response['code'] === 200;
        });
        
        $this->test('Inventory Usage Report', function() {
            $response = $this->makeRequest('GET', '/api/reports/inventory-usage');
            return $response['code'] === 200;
        });
        
        $this->test('Order Fulfillment Metrics', function() {
            $response = $this->makeRequest('GET', '/api/reports/order-fulfillment');
            return $response['code'] === 200;
        });
        
        $this->test('Worker Productivity Report', function() {
            $response = $this->makeRequest('GET', '/api/reports/worker-productivity');
            return $response['code'] === 200;
        });
        
        echo "\n===========================================\n";
        echo "Test Summary\n";
        echo "===========================================\n";
        
        $passed = count($this->testResults) - count($this->failedTests);
        $total = count($this->testResults);
        
        echo "Total Tests: $total\n";
        echo "Passed: $passed ✓\n";
        echo "Failed: " . count($this->failedTests) . "\n";
        echo "Pass Rate: " . round(($passed / $total) * 100, 1) . "%\n\n";
        
        return count($this->failedTests) === 0;
    }
}

$validator = new APIValidator();
$success = $validator->runTests();
exit($success ? 0 : 1);
