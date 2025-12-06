<?php
/**
 * Login Handler - Process login form submissions
 */

require_once __DIR__ . '/../includes/auth.php';

Session::start();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::redirect('login.php');
}

// Get POST data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($username) || empty($password)) {
    Session::set('login_error', 'Username and password are required');
    Response::redirect('login.php');
}

// Attempt login
$auth = new Auth();
$rateLimiter = new RateLimiter();
$ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// Rate limiting: 5 attempts per minute
if (!$rateLimiter->check($ipAddress, 'login', 5, 60)) {
    Session::set('login_error', 'Too many login attempts. Please try again later.');
    Response::redirect('login.php');
}

try {
    $user = $auth->login($username, $password);
    
    if ($user) {
        // Generate CSRF token
        Session::generateCSRFToken();
        
        // Redirect based on role
        $dashboardPages = [
            'client' => 'client/dashboard.php',
            'outreach_worker' => 'worker/dashboard.php',
            'service_provider' => 'provider/dashboard.php',
            'admin' => 'admin/dashboard.php'
        ];
        
        $redirectPage = $dashboardPages[$user['role']] ?? 'client/dashboard.php';
        Response::redirect($redirectPage);
    } else {
        $rateLimiter->increment($ipAddress, 'login');
        Session::set('login_error', 'Invalid username or password');
        Response::redirect('login.php');
    }
} catch (Exception $e) {
    Session::set('login_error', 'An error occurred. Please try again.');
    error_log("Login error: " . $e->getMessage());
    Response::redirect('login.php');
}
