<?php
/**
 * Tweak Easy - Database Configuration
 * Harm Reduction Order & Case Management System
 */

// Database configuration - Use environment variables in production
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'tweak_easy');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// Application configuration
define('APP_NAME', 'Tweak Easy');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/tweak-easy');
define('APP_TIMEZONE', 'America/Los_Angeles');

// Security settings
define('SESSION_LIFETIME', 3600); // 1 hour
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_MIN_LENGTH', 8);

// File upload settings
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml']);
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

/**
 * Database connection class using PDO
 */
class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed. Please check configuration.");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
    
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
    
    public function insert($table, $data) {
        $fields = array_keys($data);
        $placeholders = array_map(function($f) { return ":$f"; }, $fields);
        
        $sql = "INSERT INTO $table (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $this->query($sql, $data);
        return $this->pdo->lastInsertId();
    }
    
    public function update($table, $data, $where, $whereParams = []) {
        $setParts = array_map(function($f) { return "$f = :$f"; }, array_keys($data));
        $sql = "UPDATE $table SET " . implode(', ', $setParts) . " WHERE $where";
        
        $params = array_merge($data, $whereParams);
        return $this->query($sql, $params)->rowCount();
    }
    
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        return $this->query($sql, $params)->rowCount();
    }
    
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}

/**
 * Session management
 */
class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => SESSION_LIFETIME,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            session_start();
        }
    }
    
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        unset($_SESSION[$key]);
    }
    
    public static function destroy() {
        session_destroy();
        $_SESSION = [];
    }
    
    public static function regenerate() {
        session_regenerate_id(true);
    }
    
    public static function generateCSRFToken() {
        $token = bin2hex(random_bytes(32));
        self::set(CSRF_TOKEN_NAME, $token);
        return $token;
    }
    
    public static function validateCSRFToken($token) {
        return hash_equals(self::get(CSRF_TOKEN_NAME, ''), $token);
    }
}

/**
 * Security helper functions
 */
class Security {
    public static function sanitize($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
}

/**
 * Response helper
 */
class Response {
    public static function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    public static function success($data = null, $message = 'Success') {
        self::json(['success' => true, 'message' => $message, 'data' => $data]);
    }
    
    public static function error($message, $status = 400) {
        self::json(['success' => false, 'message' => $message], $status);
    }
    
    public static function redirect($url) {
        header("Location: $url");
        exit;
    }
}

/**
 * Rate limiter for API endpoints
 */
class RateLimiter {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->createTableIfNotExists();
    }
    
    private function createTableIfNotExists() {
        try {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS rate_limits (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    identifier VARCHAR(100) NOT NULL,
                    action VARCHAR(50) NOT NULL,
                    attempts INT DEFAULT 1,
                    window_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_identifier_action (identifier, action)
                )
            ");
        } catch (Exception $e) {
            error_log("Rate limiter table creation failed: " . $e->getMessage());
        }
    }
    
    public function check($identifier, $action, $maxAttempts, $windowSeconds) {
        $windowStart = date('Y-m-d H:i:s', time() - $windowSeconds);
        
        // Clean old entries
        $this->db->query(
            "DELETE FROM rate_limits WHERE window_start < :window_start",
            ['window_start' => $windowStart]
        );
        
        // Check current attempts
        $result = $this->db->fetch(
            "SELECT SUM(attempts) as total FROM rate_limits 
             WHERE identifier = :identifier AND action = :action AND window_start >= :window_start",
            ['identifier' => $identifier, 'action' => $action, 'window_start' => $windowStart]
        );
        
        $currentAttempts = $result['total'] ?? 0;
        return $currentAttempts < $maxAttempts;
    }
    
    public function increment($identifier, $action) {
        $this->db->insert('rate_limits', [
            'identifier' => $identifier,
            'action' => $action,
            'attempts' => 1
        ]);
    }
}

/**
 * Password strength validator
 */
class PasswordValidator {
    public static function isStrong($password) {
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            return false;
        }
        
        // Check for uppercase
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        
        // Check for lowercase
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        
        // Check for number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        
        // Check for special character
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            return false;
        }
        
        return true;
    }
    
    public static function getStrength($password) {
        $strength = 0;
        $length = strlen($password);
        
        if ($length >= 8) $strength += 1;
        if ($length >= 12) $strength += 1;
        if (preg_match('/[a-z]/', $password)) $strength += 1;
        if (preg_match('/[A-Z]/', $password)) $strength += 1;
        if (preg_match('/[0-9]/', $password)) $strength += 1;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $strength += 1;
        
        return min($strength, 5); // 0-5 scale
    }
}
