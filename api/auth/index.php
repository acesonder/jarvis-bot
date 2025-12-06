<?php
/**
 * Authentication API
 */

require_once __DIR__ . '/../../includes/auth.php';

$auth = new Auth();
$db = Database::getInstance();

// Rate limiting check
$rateLimiter = new RateLimiter();

// Get IP address with proxy support
$ipAddress = 'unknown';
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $ipAddress = trim($ips[0]);
} elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
    $ipAddress = $_SERVER['HTTP_X_REAL_IP'];
} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
    $ipAddress = $_SERVER['REMOTE_ADDR'];
}

switch ($requestMethod) {
    case 'POST':
        if ($action === 'login') {
            // Rate limiting: 5 attempts per minute
            if (!$rateLimiter->check($ipAddress, 'login', 5, 60)) {
                Response::error('Too many login attempts. Please try again later.', 429);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate CSRF token if present
            if (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
                if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'])) {
                    Response::error('Invalid CSRF token', 403);
                }
            }
            
            // Validate input
            if (empty($data['username']) || empty($data['password'])) {
                $rateLimiter->increment($ipAddress, 'login');
                Response::error('Username and password are required');
            }
            
            // Sanitize input
            $username = Security::sanitize($data['username']);
            $password = $data['password'];
            
            try {
                $user = $auth->login($username, $password);
                if ($user) {
                    // Generate new CSRF token
                    $csrfToken = Session::generateCSRFToken();
                    
                    Response::success([
                        'user' => [
                            'id' => $user['id'],
                            'username' => $user['username'],
                            'email' => $user['email'],
                            'first_name' => $user['first_name'],
                            'last_name' => $user['last_name'],
                            'role' => $user['role'],
                            'theme_preference' => $user['theme_preference']
                        ],
                        'csrf_token' => $csrfToken
                    ], 'Login successful');
                } else {
                    $rateLimiter->increment($ipAddress, 'login');
                    Response::error('Invalid credentials', 401);
                }
            } catch (Exception $e) {
                Response::error($e->getMessage(), 500);
            }
            
        } elseif ($action === 'register') {
            // Rate limiting: 3 registrations per hour per IP
            if (!$rateLimiter->check($ipAddress, 'register', 3, 3600)) {
                Response::error('Too many registration attempts. Please try again later.', 429);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate CSRF token
            if (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
                if (!Session::validateCSRFToken($_SERVER['HTTP_X_CSRF_TOKEN'])) {
                    Response::error('Invalid CSRF token', 403);
                }
            }
            
            // Sanitize input
            $sanitizedData = [
                'username' => Security::sanitize($data['username'] ?? ''),
                'email' => filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL),
                'password' => $data['password'] ?? '',
                'first_name' => Security::sanitize($data['first_name'] ?? ''),
                'last_name' => Security::sanitize($data['last_name'] ?? ''),
                'phone' => Security::sanitize($data['phone'] ?? ''),
                'role' => Security::sanitize($data['role'] ?? 'client')
            ];
            
            // Validate password strength
            if (!PasswordValidator::isStrong($sanitizedData['password'])) {
                Response::error('Password must be at least 8 characters with uppercase, lowercase, number and special character');
            }
            
            try {
                $userId = $auth->register($sanitizedData);
                $rateLimiter->increment($ipAddress, 'register');
                Response::success(['user_id' => $userId], 'Registration successful');
            } catch (Exception $e) {
                Response::error($e->getMessage());
            }
            
        } elseif ($action === 'logout') {
            try {
                $auth->logout();
                Response::success(null, 'Logout successful');
            } catch (Exception $e) {
                Response::error($e->getMessage(), 500);
            }
        }
        break;
        
    case 'GET':
        if ($action === 'me') {
            // Get current user info
            if (!$auth->isLoggedIn()) {
                Response::error('Not authenticated', 401);
            }
            
            $user = $auth->getCurrentUser();
            Response::success([
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'role' => $user['role'],
                'theme_preference' => $user['theme_preference']
            ]);
            
        } elseif ($action === 'csrf-token') {
            // Generate CSRF token
            $token = Session::generateCSRFToken();
            Response::success(['csrf_token' => $token]);
        }
        break;
        
    default:
        Response::error('Method not allowed', 405);
}
