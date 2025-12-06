# Tweak Easy - Setup Guide

## Overview
Tweak Easy is a comprehensive harm reduction order and case management system designed to support outreach teams in delivering effective services to clients.

## System Requirements

- **PHP**: 8.0 or higher
- **Database**: SQLite 3 (development) or MySQL 8.0+ (production)
- **Web Server**: Apache/Nginx or PHP built-in server (development)
- **Browser**: Chrome 80+, Firefox 75+, Safari 13+, Edge 80+

## Installation Steps

### 1. Clone the Repository
```bash
git clone https://github.com/acesonder/jarvis-bot.git
cd jarvis-bot
```

### 2. Database Setup

#### For Development (SQLite)
The application comes pre-configured with SQLite for easy development:

```bash
# The database is already created at database/tweak_easy.db
# To recreate it:
sqlite3 database/tweak_easy.db < database/schema_sqlite.sql
```

#### For Production (MySQL)
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE tweak_easy;"

# Import schema
mysql -u root -p tweak_easy < database/schema.sql

# Update config/config.php with your MySQL credentials
cp config/config_mysql.php config/config.php
# Edit config/config.php with your database credentials
```

### 3. File Permissions
```bash
# Make uploads directory writable
mkdir -p assets/uploads
chmod 755 assets/uploads

# Make database writable (SQLite only)
chmod 664 database/tweak_easy.db
chmod 775 database/
```

### 4. Start Development Server
```bash
# Using PHP built-in server
php -S localhost:8000 server.php
```

### 5. Access the Application
Open your browser and navigate to: `http://localhost:8000`

## Default Credentials

### Admin Account
- **Username**: `admin`
- **Email**: `admin@tweakeasy.org`
- **Password**: `Admin@123`

⚠️ **IMPORTANT**: Change this password immediately after first login in production!

## Application Features

### Implemented & Tested Features

#### 1. Authentication & Security ✅
- [x] User login/logout with session management
- [x] Password hashing with bcrypt
- [x] CSRF token protection
- [x] XSS protection via input sanitization
- [x] SQL injection prevention (prepared statements)
- [x] Rate limiting (5 login attempts per minute)
- [x] Strong password requirements
- [x] Secure session cookies
- [x] Role-based access control (RBAC)

#### 2. API Endpoints ✅
All API endpoints are RESTful and return JSON responses:

**Authentication API** (`/api/auth/`)
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout
- `GET /api/auth/me` - Get current user info
- `GET /api/auth/csrf-token` - Generate CSRF token

**Clients API** (`/api/clients/`)
- `GET /api/clients` - List clients (with search/filter)
- `GET /api/clients/{id}` - Get client details
- `POST /api/clients` - Create new client
- `PUT /api/clients/{id}` - Update client

**Orders API** (`/api/orders/`)
- `GET /api/orders` - List orders
- `GET /api/orders/{id}` - Get order details
- `POST /api/orders` - Create new order
- `PUT /api/orders/{id}` - Update order status

**Inventory API** (`/api/inventory/`)
- `GET /api/inventory` - List products
- `GET /api/inventory/{id}` - Get product details
- `GET /api/inventory/low-stock` - Get low stock alerts
- `POST /api/inventory` - Create product
- `PUT /api/inventory/{id}` - Update product/adjust stock

**Messages API** (`/api/messages/`)
- `GET /api/messages` - List messages
- `GET /api/messages/{id}` - Get message thread
- `GET /api/messages/unread-count` - Get unread count
- `POST /api/messages` - Send message
- `PUT /api/messages/{id}` - Mark as read
- `DELETE /api/messages/{id}` - Delete message

**Appointments API** (`/api/appointments/`)
- `GET /api/appointments` - List appointments
- `GET /api/appointments/{id}` - Get appointment details
- `POST /api/appointments` - Create appointment
- `PUT /api/appointments/{id}` - Update appointment

**Referrals API** (`/api/referrals/`)
- `GET /api/referrals` - List referrals
- `GET /api/referrals/{id}` - Get referral details
- `POST /api/referrals` - Create referral
- `PUT /api/referrals/{id}` - Update referral

**Reports API** (`/api/reports/`)
- `GET /api/reports/kpi` - Get KPI dashboard metrics
- `GET /api/reports/demographics` - Client demographics
- `GET /api/reports/inventory-usage` - Inventory usage stats
- `GET /api/reports/order-fulfillment` - Order metrics
- `GET /api/reports/worker-productivity` - Worker productivity

#### 3. Database Schema ✅
Complete database with 20+ tables including:
- Users and authentication
- Client profiles and care plans
- Orders and inventory
- Messages and notifications
- Appointments and referrals
- Service providers directory
- Audit logging
- And more...

#### 4. User Interfaces ✅
- Landing page with feature showcase
- Login/Registration pages
- Admin dashboard
- Client dashboard
- Outreach worker dashboard
- Service provider dashboard

#### 5. Security Headers ✅
All configured via `.htaccess`:
- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- X-XSS-Protection: 1; mode=block
- Referrer-Policy: strict-origin-when-cross-origin

## API Usage Examples

### Authentication
```bash
# Get CSRF token
curl http://localhost:8000/api/auth/csrf-token

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "X-CSRF-Token: YOUR_TOKEN" \
  -d '{"username":"admin","password":"Admin@123"}'

# Get current user
curl http://localhost:8000/api/auth/me \
  -H "Cookie: PHPSESSID=YOUR_SESSION_ID"
```

### Inventory Management
```bash
# List all products
curl http://localhost:8000/api/inventory \
  -H "Cookie: PHPSESSID=YOUR_SESSION_ID"

# Get low stock products
curl http://localhost:8000/api/inventory/low-stock \
  -H "Cookie: PHPSESSID=YOUR_SESSION_ID"

# Create new product
curl -X POST http://localhost:8000/api/inventory \
  -H "Content-Type: application/json" \
  -H "Cookie: PHPSESSID=YOUR_SESSION_ID" \
  -H "X-CSRF-Token: YOUR_TOKEN" \
  -d '{"name":"Test Product","category":"testing","stock_quantity":100}'
```

### Order Management
```bash
# Create new order
curl -X POST http://localhost:8000/api/orders \
  -H "Content-Type: application/json" \
  -H "Cookie: PHPSESSID=YOUR_SESSION_ID" \
  -H "X-CSRF-Token: YOUR_TOKEN" \
  -d '{
    "client_id": 1,
    "order_type": "pickup",
    "items": [
      {"product_id": 1, "quantity": 10},
      {"product_id": 2, "quantity": 5}
    ]
  }'
```

### Reports & Analytics
```bash
# Get KPI dashboard
curl "http://localhost:8000/api/reports/kpi?start_date=2024-01-01&end_date=2024-12-31" \
  -H "Cookie: PHPSESSID=YOUR_SESSION_ID"

# Get client demographics
curl http://localhost:8000/api/reports/demographics \
  -H "Cookie: PHPSESSID=YOUR_SESSION_ID"
```

## User Roles & Permissions

### Client
- Access personal dashboard
- View own profile and care plans
- Place orders for supplies
- Send/receive messages
- Schedule appointments
- Track goals and milestones

### Outreach Worker
- Manage client profiles
- Create care plans and goals
- Process orders
- Send/receive messages
- Create referrals
- Access reports

### Service Provider
- View referrals
- Manage appointments
- Communicate with clients and workers
- Track outcomes
- Access analytics

### Admin
- Full system access
- User management
- System-wide analytics
- Compliance monitoring
- Audit trail access
- Inventory oversight
- Settings configuration

## Architecture

### Technology Stack
- **Frontend**: HTML5, CSS3 (CSS Variables), JavaScript (ES6+)
- **Backend**: PHP 8.3
- **Database**: SQLite 3 (dev) / MySQL 8.0+ (prod)
- **API**: RESTful JSON API
- **Security**: CSRF tokens, XSS protection, prepared statements

### Directory Structure
```
jarvis-bot/
├── api/                    # RESTful API endpoints
│   ├── auth/              # Authentication
│   ├── clients/           # Client management
│   ├── orders/            # Order processing
│   ├── inventory/         # Inventory management
│   ├── messages/          # Communication system
│   ├── appointments/      # Appointment scheduling
│   ├── referrals/         # Referral system
│   └── reports/           # Analytics & reporting
├── assets/                # Static assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript
│   └── images/           # Images
├── config/               # Configuration files
├── database/             # Database files and schemas
├── includes/             # PHP includes
├── pages/                # Page templates
│   ├── admin/           # Admin dashboard
│   ├── client/          # Client dashboard
│   ├── worker/          # Worker dashboard
│   └── provider/        # Provider dashboard
├── .htaccess            # Apache configuration
├── server.php           # Development server router
└── index.html           # Landing page
```

## Security Best Practices

1. **Change Default Passwords**: Always change the default admin password
2. **Enable HTTPS**: Use SSL/TLS in production
3. **Regular Backups**: Back up the database regularly
4. **Update Dependencies**: Keep PHP and extensions updated
5. **Monitor Logs**: Review audit logs regularly
6. **Restrict File Permissions**: Ensure proper file/directory permissions
7. **Rate Limiting**: Configured for login attempts
8. **Input Validation**: All user input is sanitized and validated

## Troubleshooting

### Database Connection Issues
```bash
# Check database file permissions (SQLite)
ls -la database/tweak_easy.db
chmod 664 database/tweak_easy.db

# Test database connection
sqlite3 database/tweak_easy.db "SELECT COUNT(*) FROM users;"
```

### Login Issues
```bash
# Reset admin password
php -r "echo password_hash('Admin@123', PASSWORD_DEFAULT) . PHP_EOL;"
# Copy the hash and update in database:
sqlite3 database/tweak_easy.db "UPDATE users SET password_hash = 'PASTE_HASH_HERE' WHERE username = 'admin';"
```

### API Not Working
```bash
# Check if server is running
curl http://localhost:8000/api/

# Check PHP error log
tail -f /var/log/php_errors.log
```

## Development

### Running Tests
```bash
# Currently no automated tests - manual testing performed
# Future: PHPUnit for backend, Jest for frontend
```

### Code Style
- PHP: PSR-12 coding standard
- JavaScript: ES6+ with modern best practices
- CSS: BEM naming convention

## Production Deployment

### Apache Configuration
```apache
<VirtualHost *:80>
    ServerName tweakeasy.yourdomain.com
    DocumentRoot /var/www/jarvis-bot
    
    <Directory /var/www/jarvis-bot>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Force HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName tweakeasy.yourdomain.com
    DocumentRoot /var/www/jarvis-bot
    
    SSLEngine on
    SSLCertificateFile /path/to/cert.pem
    SSLCertificateKeyFile /path/to/key.pem
    
    <Directory /var/www/jarvis-bot>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Environment Variables
Set these in production:
```bash
export DB_HOST=localhost
export DB_NAME=tweak_easy
export DB_USER=tweakeasy_user
export DB_PASS=secure_password_here
```

## Support & Contributing

For issues, questions, or contributions, please visit:
https://github.com/acesonder/jarvis-bot

## License

Copyright © 2024 Tweak Easy. All rights reserved.
