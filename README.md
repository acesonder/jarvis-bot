# Tweak Easy - Harm Reduction Order & Case Management System

A comprehensive, professional-grade web application designed to support harm reduction outreach teams in delivering effective services to clients.

## Overview

Tweak Easy is an adaptive, user-friendly platform that unifies:
- Harm reduction supply distribution
- Case/client management
- Incident tracking
- Referral coordination
- Analytics and reporting

## Technology Stack

- **Frontend**: HTML5, CSS3 (with CSS Variables), JavaScript (ES6+)
- **Backend**: PHP 8.x
- **Database**: MySQL 8.x
- **Additional**: AJAX for dynamic content loading

## Features

### User Roles

1. **Clients**
   - Secure personal dashboard
   - Smart needs assessment tools
   - Goal tracking with visual progress
   - Secure messaging with care team
   - Supply delivery/pickup scheduling
   - Appointment management

2. **Outreach Workers**
   - Mobile case management
   - Real-time data entry
   - Supply distribution tracking
   - Geolocation tools
   - Quick referral system
   - Incident reporting

3. **Service Providers**
   - Referral management
   - Multi-party secure communication
   - Analytics dashboard
   - Document management
   - Appointment coordination

4. **Administrative Staff**
   - System-wide analytics
   - User management
   - Compliance monitoring
   - Audit trails
   - Inventory oversight

### Core Features

- **Case Management**: Detailed client profiles, care plans, goal tracking
- **Smart Assessment System**: Trauma-informed assessments with real-time scoring
- **Communication Hub**: Role-based messaging with crisis alerts
- **Resource Directory**: Local service database with mapping
- **Harm Reduction Platform**: Inventory management, order processing, delivery tracking
- **Analytics Dashboard**: KPIs, trend analysis, usage reports

## Project Structure

```
tweak-easy/
├── index.html              # Landing page
├── config/
│   └── config.php          # Database and app configuration
├── includes/
│   └── auth.php            # Authentication functions
├── database/
│   └── schema.sql          # Database schema
├── assets/
│   ├── css/
│   │   ├── main.css        # Core styles
│   │   ├── landing.css     # Landing page styles
│   │   ├── auth.css        # Authentication pages styles
│   │   └── dashboard.css   # Dashboard styles
│   ├── js/
│   │   ├── main.js         # Core JavaScript
│   │   ├── landing.js      # Landing page scripts
│   │   └── dashboard.js    # Dashboard scripts
│   └── images/             # Image assets
├── pages/
│   ├── login.php           # Login page
│   ├── register.php        # Registration page
│   ├── client/
│   │   └── dashboard.php   # Client dashboard
│   ├── worker/
│   │   └── dashboard.php   # Outreach worker dashboard
│   ├── provider/
│   │   └── dashboard.php   # Service provider dashboard
│   └── admin/
│       └── dashboard.php   # Admin dashboard
└── api/                    # API endpoints (REST)
```

## Installation

### Requirements

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server
- HTTPS enabled (recommended)

### Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/your-org/tweak-easy.git
   cd tweak-easy
   ```

2. Create the database:
   ```bash
   mysql -u root -p < database/schema.sql
   ```

3. Configure the application:
   - Edit `config/config.php` with your database credentials
   - Set appropriate file permissions

4. Configure your web server to point to the project root

5. Access the application at `http://your-domain/`

### Default Admin Credentials

- Username: `admin`
- Password: `admin123` (change immediately after first login)

## Themes

The application supports two themes:
- **Light Theme** (default): Clean, professional appearance
- **Dark Theme**: Reduced eye strain for low-light environments

Toggle themes using the button in the bottom-right corner.

## Security Features

- Password hashing using bcrypt
- CSRF token protection
- Prepared statements for SQL injection prevention
- XSS protection via output sanitization
- Role-based access control (RBAC)
- Session management with secure cookies
- Audit logging for compliance

## Browser Support

- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## License

Copyright © 2024 Tweak Easy. All rights reserved.

## Support

For support, please contact: support@tweakeasy.org