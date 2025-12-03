<?php
/**
 * Tweak Easy - Authentication Functions
 */

require_once __DIR__ . '/../config/config.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function login($username, $password) {
        $user = $this->db->fetch(
            "SELECT * FROM users WHERE (username = :username OR email = :username) AND status = 'active'",
            ['username' => $username]
        );
        
        if ($user && Security::verifyPassword($password, $user['password_hash'])) {
            Session::regenerate();
            Session::set('user_id', $user['id']);
            Session::set('user_role', $user['role']);
            Session::set('user_name', $user['first_name'] . ' ' . $user['last_name']);
            
            $this->logAudit($user['id'], 'login', 'users', $user['id']);
            
            return $user;
        }
        
        return false;
    }
    
    public function logout() {
        $userId = Session::get('user_id');
        if ($userId) {
            $this->logAudit($userId, 'logout', 'users', $userId);
        }
        Session::destroy();
    }
    
    public function register($data) {
        // Validate required fields
        $required = ['username', 'email', 'password', 'first_name', 'last_name'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("$field is required");
            }
        }
        
        // Check password length
        if (strlen($data['password']) < PASSWORD_MIN_LENGTH) {
            throw new Exception("Password must be at least " . PASSWORD_MIN_LENGTH . " characters");
        }
        
        // Check if username or email exists
        $existing = $this->db->fetch(
            "SELECT id FROM users WHERE username = :username OR email = :email",
            ['username' => $data['username'], 'email' => $data['email']]
        );
        
        if ($existing) {
            throw new Exception("Username or email already exists");
        }
        
        // Create user
        $userId = $this->db->insert('users', [
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => Security::hashPassword($data['password']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'] ?? 'client',
            'status' => 'active'
        ]);
        
        // Create profile if client
        if (($data['role'] ?? 'client') === 'client') {
            $this->db->insert('client_profiles', ['user_id' => $userId]);
        }
        
        $this->logAudit($userId, 'register', 'users', $userId);
        
        return $userId;
    }
    
    public function isLoggedIn() {
        return Session::has('user_id');
    }
    
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            Response::redirect(APP_URL . '/pages/login.php');
        }
    }
    
    public function requireRole($roles) {
        $this->requireLogin();
        
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        if (!in_array(Session::get('user_role'), $roles)) {
            Response::error('Access denied', 403);
        }
    }
    
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return $this->db->fetch(
            "SELECT * FROM users WHERE id = :id",
            ['id' => Session::get('user_id')]
        );
    }
    
    public function updateProfile($userId, $data) {
        $allowedFields = ['first_name', 'last_name', 'phone', 'profile_image', 'theme_preference'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));
        
        if (!empty($updateData)) {
            $this->db->update('users', $updateData, 'id = :id', ['id' => $userId]);
            $this->logAudit($userId, 'update_profile', 'users', $userId);
        }
        
        return true;
    }
    
    public function changePassword($userId, $currentPassword, $newPassword) {
        $user = $this->db->fetch("SELECT password_hash FROM users WHERE id = :id", ['id' => $userId]);
        
        if (!$user || !Security::verifyPassword($currentPassword, $user['password_hash'])) {
            throw new Exception("Current password is incorrect");
        }
        
        if (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
            throw new Exception("Password must be at least " . PASSWORD_MIN_LENGTH . " characters");
        }
        
        $this->db->update('users', 
            ['password_hash' => Security::hashPassword($newPassword)],
            'id = :id',
            ['id' => $userId]
        );
        
        $this->logAudit($userId, 'change_password', 'users', $userId);
        
        return true;
    }
    
    private function logAudit($userId, $action, $table, $recordId) {
        try {
            $this->db->insert('audit_log', [
                'user_id' => $userId,
                'action' => $action,
                'table_name' => $table,
                'record_id' => $recordId,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Audit log failed: " . $e->getMessage());
        }
    }
}
