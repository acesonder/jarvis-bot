-- Tweak Easy - SQLite Database Schema
-- Harm Reduction Order & Case Management System

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    phone TEXT,
    role TEXT NOT NULL CHECK(role IN ('client', 'outreach_worker', 'service_provider', 'admin')),
    profile_image TEXT,
    theme_preference TEXT DEFAULT 'light' CHECK(theme_preference IN ('light', 'dark')),
    status TEXT DEFAULT 'active' CHECK(status IN ('active', 'inactive', 'suspended')),
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- Client profiles
CREATE TABLE IF NOT EXISTS client_profiles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER UNIQUE NOT NULL,
    date_of_birth TEXT,
    housing_status TEXT DEFAULT 'unknown' CHECK(housing_status IN ('housed', 'homeless', 'transitional', 'unknown')),
    health_notes TEXT,
    emergency_contact_name TEXT,
    emergency_contact_phone TEXT,
    preferred_location TEXT,
    notes TEXT,
    risk_level TEXT DEFAULT 'low' CHECK(risk_level IN ('low', 'medium', 'high', 'critical')),
    assigned_worker_id INTEGER,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_worker_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Care plans
CREATE TABLE IF NOT EXISTS care_plans (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    status TEXT DEFAULT 'active' CHECK(status IN ('active', 'completed', 'paused')),
    start_date TEXT,
    target_end_date TEXT,
    created_by INTEGER,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Goals
CREATE TABLE IF NOT EXISTS goals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    care_plan_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    progress INTEGER DEFAULT 0,
    status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'in_progress', 'completed', 'paused')),
    due_date TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (care_plan_id) REFERENCES care_plans(id) ON DELETE CASCADE
);

-- Milestones
CREATE TABLE IF NOT EXISTS milestones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    goal_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    completed INTEGER DEFAULT 0,
    completed_at TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (goal_id) REFERENCES goals(id) ON DELETE CASCADE
);

-- Assessments
CREATE TABLE IF NOT EXISTS assessments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    assessment_type TEXT NOT NULL,
    score INTEGER,
    risk_level TEXT CHECK(risk_level IN ('low', 'medium', 'high', 'critical')),
    responses TEXT,
    notes TEXT,
    completed_by INTEGER,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (completed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Products (Harm Reduction Supplies)
CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    category TEXT,
    tile_color TEXT DEFAULT '#3498db',
    font_color TEXT DEFAULT '#ffffff',
    icon_url TEXT,
    stock_quantity INTEGER DEFAULT 0,
    reorder_level INTEGER DEFAULT 10,
    unit TEXT DEFAULT 'unit',
    is_active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- Orders
CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_number TEXT UNIQUE NOT NULL,
    client_id INTEGER,
    placed_by INTEGER NOT NULL,
    order_type TEXT NOT NULL CHECK(order_type IN ('delivery', 'pickup')),
    status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'processing', 'ready', 'in_transit', 'delivered', 'cancelled')),
    scheduled_date TEXT,
    scheduled_time TEXT,
    pickup_location TEXT,
    delivery_address TEXT,
    notes TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE SET NULL,
    FOREIGN KEY (placed_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items
CREATE TABLE IF NOT EXISTS order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Inventory transactions
CREATE TABLE IF NOT EXISTS inventory_transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    transaction_type TEXT NOT NULL CHECK(transaction_type IN ('in', 'out', 'adjustment')),
    quantity INTEGER NOT NULL,
    reference_type TEXT,
    reference_id INTEGER,
    notes TEXT,
    performed_by INTEGER,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Messages
CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sender_id INTEGER NOT NULL,
    recipient_id INTEGER NOT NULL,
    subject TEXT,
    content TEXT NOT NULL,
    is_read INTEGER DEFAULT 0,
    is_urgent INTEGER DEFAULT 0,
    parent_message_id INTEGER,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_message_id) REFERENCES messages(id) ON DELETE SET NULL
);

-- Appointments
CREATE TABLE IF NOT EXISTS appointments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    provider_id INTEGER NOT NULL,
    appointment_type TEXT,
    scheduled_date TEXT NOT NULL,
    scheduled_time TEXT NOT NULL,
    duration_minutes INTEGER DEFAULT 30,
    location TEXT,
    status TEXT DEFAULT 'scheduled' CHECK(status IN ('scheduled', 'confirmed', 'cancelled', 'completed', 'no_show')),
    notes TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Referrals
CREATE TABLE IF NOT EXISTS referrals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    referred_by INTEGER NOT NULL,
    referred_to INTEGER,
    service_provider_name TEXT,
    service_type TEXT,
    reason TEXT,
    status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'accepted', 'in_progress', 'completed', 'declined')),
    outcome TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (referred_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (referred_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Incidents
CREATE TABLE IF NOT EXISTS incidents (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    incident_number TEXT UNIQUE NOT NULL,
    reported_by INTEGER NOT NULL,
    client_id INTEGER,
    incident_type TEXT NOT NULL,
    severity TEXT NOT NULL CHECK(severity IN ('low', 'medium', 'high', 'critical')),
    location TEXT,
    description TEXT NOT NULL,
    action_taken TEXT,
    status TEXT DEFAULT 'open' CHECK(status IN ('open', 'investigating', 'resolved', 'closed')),
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE SET NULL
);

-- Service providers directory
CREATE TABLE IF NOT EXISTS service_providers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    category TEXT,
    description TEXT,
    address TEXT,
    city TEXT,
    state TEXT,
    zip_code TEXT,
    phone TEXT,
    email TEXT,
    website TEXT,
    hours_of_operation TEXT,
    services_offered TEXT,
    latitude REAL,
    longitude REAL,
    is_emergency INTEGER DEFAULT 0,
    is_active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- Case notes
CREATE TABLE IF NOT EXISTS case_notes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    created_by INTEGER NOT NULL,
    note_type TEXT,
    content TEXT NOT NULL,
    is_private INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Follow-up alerts
CREATE TABLE IF NOT EXISTS follow_up_alerts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    assigned_to INTEGER NOT NULL,
    alert_type TEXT,
    title TEXT NOT NULL,
    description TEXT,
    due_date TEXT NOT NULL,
    status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'completed', 'dismissed')),
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    completed_at TEXT,
    FOREIGN KEY (client_id) REFERENCES client_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE CASCADE
);

-- User favorites
CREATE TABLE IF NOT EXISTS user_favorites (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    item_type TEXT NOT NULL,
    item_id INTEGER NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE(user_id, item_type, item_id)
);

-- Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    type TEXT,
    title TEXT NOT NULL,
    message TEXT,
    link TEXT,
    is_read INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Audit log
CREATE TABLE IF NOT EXISTS audit_log (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    action TEXT NOT NULL,
    table_name TEXT,
    record_id INTEGER,
    old_values TEXT,
    new_values TEXT,
    ip_address TEXT,
    user_agent TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
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

-- Insert default admin user
-- Password: Admin@123 (CHANGE THIS IMMEDIATELY IN PRODUCTION!)
-- This is a secure hash generated specifically for this application
INSERT INTO users (username, email, password_hash, first_name, last_name, role, status) VALUES
('admin', 'admin@tweakeasy.org', '$2y$10$A0XipHMG2wmZppoJz4uMkOXQshp19puyT57JN7AkUNlF7Ahz68Wcy', 'System', 'Administrator', 'admin', 'active');

-- Insert sample service providers
INSERT INTO service_providers (name, category, description, address, city, state, zip_code, phone, email, services_offered, is_emergency) VALUES
('Community Health Center', 'healthcare', 'Primary care and mental health services', '123 Main St', 'Portland', 'OR', '97201', '503-555-0100', 'info@chc.org', 'Primary care, mental health, substance use treatment', 0),
('City Shelter', 'housing', 'Emergency and transitional housing', '456 Oak Ave', 'Portland', 'OR', '97202', '503-555-0200', 'intake@cityshelter.org', 'Emergency shelter, transitional housing, case management', 0),
('Crisis Hotline', 'crisis', '24/7 crisis intervention and support', '', 'Portland', 'OR', '', '503-555-HELP', '', 'Crisis intervention, suicide prevention, referrals', 1),
('Food Bank', 'food', 'Food assistance and nutrition programs', '789 Pine St', 'Portland', 'OR', '97203', '503-555-0300', 'help@foodbank.org', 'Food boxes, hot meals, nutrition education', 0),
('Legal Aid Services', 'legal', 'Free legal assistance for low-income individuals', '321 Court St', 'Portland', 'OR', '97204', '503-555-0400', 'intake@legalaid.org', 'Legal advice, representation, document assistance', 0);
