-- Tweak Easy - Harm Reduction Order & Case Management System
-- Database Schema

-- Create database
CREATE DATABASE IF NOT EXISTS tweak_easy;
USE tweak_easy;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    role ENUM('client', 'outreach_worker', 'service_provider', 'admin') NOT NULL,
    profile_image VARCHAR(255),
    theme_preference ENUM('light', 'dark') DEFAULT 'light',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Client profiles
CREATE TABLE client_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    date_of_birth DATE,
    housing_status ENUM('housed', 'homeless', 'transitional', 'unknown') DEFAULT 'unknown',
    health_notes TEXT,
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    preferred_location VARCHAR(255),
    notes TEXT,
    risk_level ENUM('low', 'medium', 'high', 'critical') DEFAULT 'low',
    assigned_worker_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_worker_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Care plans
CREATE TABLE care_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('active', 'completed', 'paused') DEFAULT 'active',
    start_date DATE,
    target_end_date DATE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Goals
CREATE TABLE goals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    care_plan_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    progress INT DEFAULT 0,
    status ENUM('pending', 'in_progress', 'completed', 'paused') DEFAULT 'pending',
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (care_plan_id) REFERENCES care_plans(id) ON DELETE CASCADE
);

-- Milestones
CREATE TABLE milestones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    goal_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (goal_id) REFERENCES goals(id) ON DELETE CASCADE
);

-- Assessments
CREATE TABLE assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    assessment_type VARCHAR(50) NOT NULL,
    score INT,
    risk_level ENUM('low', 'medium', 'high', 'critical'),
    responses JSON,
    notes TEXT,
    completed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (completed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Products (Harm Reduction Supplies)
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    category VARCHAR(50),
    tile_color VARCHAR(7) DEFAULT '#3498db',
    font_color VARCHAR(7) DEFAULT '#ffffff',
    icon_url VARCHAR(255),
    stock_quantity INT DEFAULT 0,
    reorder_level INT DEFAULT 10,
    unit VARCHAR(20) DEFAULT 'unit',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    client_id INT,
    placed_by INT NOT NULL,
    order_type ENUM('delivery', 'pickup') NOT NULL,
    status ENUM('pending', 'processing', 'ready', 'in_transit', 'delivered', 'cancelled') DEFAULT 'pending',
    scheduled_date DATE,
    scheduled_time TIME,
    pickup_location VARCHAR(255),
    delivery_address VARCHAR(255),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE SET NULL,
    FOREIGN KEY (placed_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Inventory transactions
CREATE TABLE inventory_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    transaction_type ENUM('in', 'out', 'adjustment') NOT NULL,
    quantity INT NOT NULL,
    reference_type VARCHAR(50),
    reference_id INT,
    notes TEXT,
    performed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Messages
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    subject VARCHAR(200),
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    is_urgent BOOLEAN DEFAULT FALSE,
    parent_message_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_message_id) REFERENCES messages(id) ON DELETE SET NULL
);

-- Appointments
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    provider_id INT NOT NULL,
    appointment_type VARCHAR(50),
    scheduled_date DATE NOT NULL,
    scheduled_time TIME NOT NULL,
    duration_minutes INT DEFAULT 30,
    location VARCHAR(255),
    status ENUM('scheduled', 'confirmed', 'cancelled', 'completed', 'no_show') DEFAULT 'scheduled',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Referrals
CREATE TABLE referrals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    referred_by INT NOT NULL,
    referred_to INT,
    service_provider_name VARCHAR(100),
    service_type VARCHAR(100),
    reason TEXT,
    status ENUM('pending', 'accepted', 'in_progress', 'completed', 'declined') DEFAULT 'pending',
    outcome TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (referred_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (referred_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Incidents
CREATE TABLE incidents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    incident_number VARCHAR(20) UNIQUE NOT NULL,
    reported_by INT NOT NULL,
    client_id INT,
    incident_type VARCHAR(50) NOT NULL,
    severity ENUM('low', 'medium', 'high', 'critical') NOT NULL,
    location VARCHAR(255),
    description TEXT NOT NULL,
    action_taken TEXT,
    status ENUM('open', 'investigating', 'resolved', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE SET NULL
);

-- Service providers directory
CREATE TABLE service_providers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    description TEXT,
    address VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(50),
    zip_code VARCHAR(20),
    phone VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(255),
    hours_of_operation TEXT,
    services_offered TEXT,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    is_emergency BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Case notes
CREATE TABLE case_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    created_by INT NOT NULL,
    note_type VARCHAR(50),
    content TEXT NOT NULL,
    is_private BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Follow-up alerts
CREATE TABLE follow_up_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    assigned_to INT NOT NULL,
    alert_type VARCHAR(50),
    title VARCHAR(100) NOT NULL,
    description TEXT,
    due_date DATE NOT NULL,
    status ENUM('pending', 'completed', 'dismissed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE CASCADE
);

-- User favorites
CREATE TABLE user_favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_type VARCHAR(50) NOT NULL,
    item_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, item_type, item_id)
);

-- Notifications
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50),
    title VARCHAR(100) NOT NULL,
    message TEXT,
    link VARCHAR(255),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Audit log
CREATE TABLE audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default harm reduction products
INSERT INTO products (name, description, category, tile_color, font_color, stock_quantity, reorder_level, unit) VALUES
('Syringes (1cc)', 'Sterile 1cc insulin syringes', 'syringes', '#e74c3c', '#ffffff', 500, 100, 'each'),
('Syringes (3cc)', 'Sterile 3cc syringes', 'syringes', '#c0392b', '#ffffff', 300, 75, 'each'),
('Needle Tips (27g)', '27 gauge needle tips', 'needles', '#9b59b6', '#ffffff', 400, 100, 'each'),
('Needle Tips (30g)', '30 gauge needle tips', 'needles', '#8e44ad', '#ffffff', 400, 100, 'each'),
('Cookers', 'Stainless steel cookers', 'preparation', '#3498db', '#ffffff', 200, 50, 'each'),
('Cotton Filters', 'Sterile cotton filters', 'preparation', '#2980b9', '#ffffff', 500, 100, 'pack'),
('Alcohol Swabs', 'Isopropyl alcohol swabs', 'hygiene', '#1abc9c', '#ffffff', 1000, 200, 'pack'),
('Tourniquets', 'Elastic tourniquets', 'accessories', '#16a085', '#ffffff', 150, 30, 'each'),
('Sharps Container', 'Puncture-resistant disposal container', 'disposal', '#f39c12', '#ffffff', 100, 25, 'each'),
('Naloxone Kit', 'Nasal naloxone rescue kit', 'overdose_prevention', '#e67e22', '#ffffff', 50, 15, 'kit'),
('Fentanyl Test Strips', 'Drug checking test strips', 'testing', '#d35400', '#ffffff', 200, 50, 'each'),
('Sterile Water', 'Single-use sterile water vials', 'preparation', '#27ae60', '#ffffff', 300, 75, 'vial'),
('Vitamin C Packets', 'Ascorbic acid for drug preparation', 'preparation', '#2ecc71', '#ffffff', 400, 100, 'packet'),
('Wound Care Kit', 'Basic wound care supplies', 'hygiene', '#34495e', '#ffffff', 75, 20, 'kit'),
('Condoms', 'Safer sex supplies', 'sexual_health', '#95a5a6', '#ffffff', 500, 100, 'each'),
('Lubricant Packets', 'Water-based lubricant', 'sexual_health', '#7f8c8d', '#ffffff', 300, 75, 'packet'),
('Lip Balm', 'Moisturizing lip balm', 'hygiene', '#e91e63', '#ffffff', 200, 50, 'each'),
('Hand Sanitizer', 'Alcohol-based hand sanitizer', 'hygiene', '#00bcd4', '#ffffff', 150, 40, 'bottle'),
('Pipe Stems', 'Glass pipe stems', 'pipes', '#ff5722', '#ffffff', 100, 25, 'each'),
('Pipe Mouthpieces', 'Silicone mouthpieces', 'pipes', '#ff9800', '#ffffff', 200, 50, 'each');

-- NOTE: Default admin user for development only
-- IMPORTANT: Change password immediately in production deployment
-- Default password is 'admin123' - MUST be changed before production use
INSERT INTO users (username, email, password_hash, first_name, last_name, role, status) VALUES
('admin', 'admin@tweakeasy.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System', 'Administrator', 'admin', 'active');

-- Insert sample service providers
INSERT INTO service_providers (name, category, description, address, city, state, zip_code, phone, email, services_offered, is_emergency) VALUES
('Community Health Center', 'healthcare', 'Primary care and mental health services', '123 Main St', 'Portland', 'OR', '97201', '503-555-0100', 'info@chc.org', 'Primary care, mental health, substance use treatment', FALSE),
('City Shelter', 'housing', 'Emergency and transitional housing', '456 Oak Ave', 'Portland', 'OR', '97202', '503-555-0200', 'intake@cityshelter.org', 'Emergency shelter, transitional housing, case management', FALSE),
('Crisis Hotline', 'crisis', '24/7 crisis intervention and support', '', 'Portland', 'OR', '', '503-555-HELP', '', 'Crisis intervention, suicide prevention, referrals', TRUE),
('Food Bank', 'food', 'Food assistance and nutrition programs', '789 Pine St', 'Portland', 'OR', '97203', '503-555-0300', 'help@foodbank.org', 'Food boxes, hot meals, nutrition education', FALSE),
('Legal Aid Services', 'legal', 'Free legal assistance for low-income individuals', '321 Court St', 'Portland', 'OR', '97204', '503-555-0400', 'intake@legalaid.org', 'Legal advice, representation, document assistance', FALSE);
