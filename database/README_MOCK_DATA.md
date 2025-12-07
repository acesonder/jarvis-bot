# Mock Data Installation Guide

## Overview

The Tweak Easy system includes a comprehensive mock data generator that creates realistic sample data for testing, development, and demonstration purposes. This allows you to quickly set up a fully functional system with pre-populated data.

## What Mock Data Includes

The mock data generator creates:

- **Client Users**: Realistic client profiles with demographics
- **Outreach Workers**: Staff accounts for case management
- **Service Providers**: Provider accounts for referrals
- **Client Profiles**: Complete profiles with risk assessments and housing status
- **Care Plans**: Treatment and recovery plans for clients
- **Goals & Milestones**: Trackable objectives with progress indicators
- **Orders**: Sample supply orders with multiple items
- **Inventory Transactions**: Stock movements tied to orders
- **Messages**: Internal communications between users
- **Appointments**: Scheduled appointments with various statuses
- **Referrals**: Service referrals to community providers
- **Case Notes**: Documentation of client interactions

## Installation Methods

### Method 1: Setup with Mock Data (Recommended)

Create a fresh database with mock data in one step:

```bash
# Default amounts (15 clients, 5 workers, 3 providers, etc.)
php database/setup_with_mock_data.php --with-mock-data

# Custom amounts
php database/setup_with_mock_data.php --mock-clients=20 --mock-workers=8 --mock-orders=50
```

### Method 2: Add Mock Data to Existing Database

Add mock data to an already created database:

```bash
# Default amounts
php database/mock_data_generator.php

# Custom amounts
php database/mock_data_generator.php --clients=10 --orders=30 --messages=40
```

### Method 3: Interactive Setup

Run the setup script and choose options interactively:

```bash
php database/setup_with_mock_data.php
# Follow the prompts
```

## Configuration Options

### Command Line Arguments

| Argument | Default | Description |
|----------|---------|-------------|
| `--clients=N` | 15 | Number of client profiles to generate |
| `--workers=N` | 5 | Number of outreach worker accounts |
| `--providers=N` | 3 | Number of service provider accounts |
| `--orders=N` | 25 | Number of supply orders to create |
| `--messages=N` | 30 | Number of internal messages |
| `--appointments=N` | 20 | Number of appointments to schedule |
| `--referrals=N` | 12 | Number of referrals to generate |
| `--casenotes=N` | 40 | Number of case notes to create |

### Examples

```bash
# Minimal dataset for quick testing
php database/mock_data_generator.php --clients=5 --orders=10 --messages=10

# Medium dataset for development
php database/mock_data_generator.php --clients=20 --workers=10 --orders=50

# Large dataset for performance testing
php database/mock_data_generator.php --clients=100 --workers=20 --orders=200 --messages=500
```

## Generated Credentials

### Default Admin Account
Already included in the base schema:
- **Username**: `admin`
- **Email**: `admin@tweakeasy.org`
- **Password**: `Admin@123`

### Generated Client Accounts
Format: `firstnamelastname` + random number
- **Username**: e.g., `emmasmith123`, `johnbrown456`
- **Password**: `Client@123`
- **Email**: e.g., `emma.smith45@example.com`

### Generated Worker Accounts
Format: `worker_` + firstname + random number
- **Username**: e.g., `worker_sarah789`, `worker_michael234`
- **Password**: `Worker@123`
- **Email**: e.g., `worker.sarahlee@tweakeasy.org`

### Generated Provider Accounts
Format: `provider_` + firstname + random number
- **Username**: e.g., `provider_david567`, `provider_lisa890`
- **Password**: `Provider@123`
- **Email**: e.g., `david.martinez@provider.org`

## Sample Data Characteristics

### Client Profiles
- Ages: 20-65 years
- Housing status: Random distribution (housed, homeless, transitional)
- Risk levels: Random distribution (low, medium, high, critical)
- Assigned to random workers
- Emergency contacts included

### Care Plans
- 1-2 plans per client
- Mix of active and completed status
- 6-month duration typically
- Associated with specific workers

### Goals
- 2-4 goals per care plan
- Progress tracking (0-100%)
- Status: pending, in_progress, completed, paused
- Due dates spread over 1-6 months

### Milestones
- 2-3 milestones per goal
- Mix of completed and pending
- Completion timestamps for finished items

### Orders
- Various statuses: pending, processing, ready, completed, cancelled
- 1-5 items per order
- Both delivery and pickup types
- Order numbers in format: ORD-YYYYMMDD-XXXXXX
- Completed orders automatically update inventory

### Messages
- Communications between all user types
- Mix of urgent and normal priority
- Read/unread statuses
- Realistic subjects and content

### Appointments
- Types: intake, follow-up, check-in, assessment, counseling
- Locations: Main Office, Community Center, Mobile Unit, etc.
- Duration: 15-120 minutes
- Various statuses: scheduled, confirmed, completed, cancelled, no_show
- Dates range from past month to 2 months in future

### Referrals
- Connected to actual service providers
- Service types: housing, healthcare, mental_health, legal, employment
- Status workflow: pending → accepted → in_progress → completed
- Reason and outcome documentation

### Case Notes
- Types: progress, incident, assessment, contact, other
- Created by workers
- Timestamped over past 3 months
- Meaningful content for context

## Data Integrity

The mock data generator ensures:

1. **Foreign Key Consistency**: All references are valid
2. **Realistic Relationships**: Clients assigned to workers, orders linked to clients
3. **Temporal Logic**: Dates and timestamps make sense (created < completed)
4. **Inventory Tracking**: Stock levels updated when orders completed
5. **Status Workflows**: Entities progress through logical status transitions

## Use Cases

### 1. Development & Testing
```bash
# Quick setup for development
php database/setup_with_mock_data.php --mock-clients=10 --mock-orders=20
```
Use for testing features, UI development, and debugging.

### 2. Demonstrations
```bash
# Comprehensive dataset for demos
php database/setup_with_mock_data.php --with-mock-data
```
Show off all system features with realistic data.

### 3. Training
```bash
# Moderate dataset for training
php database/mock_data_generator.php --clients=25 --workers=8
```
Let users practice without affecting real data.

### 4. Performance Testing
```bash
# Large dataset for load testing
php database/mock_data_generator.php --clients=200 --orders=500 --messages=1000
```
Test system performance under heavy data load.

## Clearing Mock Data

To remove all data and start fresh:

```bash
# Delete database and recreate
rm database/tweak_easy.db
sqlite3 database/tweak_easy.db < database/schema_sqlite.sql

# Or use the setup script (will prompt)
php database/setup_with_mock_data.php
```

## Verification

After generating mock data, verify with:

```bash
# Check counts
sqlite3 database/tweak_easy.db "
SELECT 'Users:', COUNT(*) FROM users;
SELECT 'Clients:', COUNT(*) FROM client_profiles;
SELECT 'Orders:', COUNT(*) FROM orders;
SELECT 'Messages:', COUNT(*) FROM messages;
SELECT 'Appointments:', COUNT(*) FROM appointments;
SELECT 'Referrals:', COUNT(*) FROM referrals;
"

# Check sample data
sqlite3 database/tweak_easy.db "
SELECT username, role FROM users WHERE role != 'admin' LIMIT 5;
"
```

## Troubleshooting

### Database Locked Error
If you get "database is locked":
```bash
# Close all connections and retry
pkill -f "php.*server.php"
php database/mock_data_generator.php
```

### Foreign Key Constraint Error
This means the database wasn't set up properly:
```bash
# Recreate database first
rm database/tweak_easy.db
sqlite3 database/tweak_easy.db < database/schema_sqlite.sql
# Then run mock data generator
php database/mock_data_generator.php
```

### Not Enough Data Generated
Increase the counts:
```bash
php database/mock_data_generator.php --clients=50 --orders=100 --messages=200
```

## Integration with Application

The mock data is immediately usable:

1. **Login as admin**: `admin` / `Admin@123`
2. **View clients**: Check client list to see generated profiles
3. **Test orders**: Orders are already created and visible
4. **Check messages**: View inbox to see generated communications
5. **View appointments**: Calendar populated with appointments
6. **Access reports**: Analytics dashboards show real data

## Best Practices

1. **Always use mock data in development**: Never test with production data
2. **Regenerate periodically**: Keep data fresh for realistic testing
3. **Customize amounts**: Match your testing needs
4. **Document mock accounts**: Keep a list of test credentials
5. **Separate environments**: Use mock data only in dev/test, never production

## Security Note

⚠️ **WARNING**: Mock data uses simple, well-known passwords. This is intentional for development/testing but:
- **NEVER** use mock data in production
- **NEVER** expose systems with mock data to the internet
- **ALWAYS** use strong, unique passwords for production
- **ALWAYS** change the default admin password immediately

## Support

For issues or questions:
- Check the main SETUP.md guide
- Review database/schema_sqlite.sql for table structures
- Examine mock_data_generator.php source code
- Open an issue on GitHub

## Files

- `database/mock_data_generator.php` - Standalone generator script
- `database/setup_with_mock_data.php` - Combined setup + mock data
- `database/schema_sqlite.sql` - Database schema with defaults
- `database/README_MOCK_DATA.md` - This documentation

---

**Last Updated**: December 2024  
**Version**: 1.0.0
