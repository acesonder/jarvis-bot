# 🔐 Test Login Credentials

## Quick Demo Access

A **Quick Demo Access** button has been added to the landing page (http://localhost:8000) that will automatically log you in as a test client.

---

## Test Client Accounts

### Primary Test Client (Featured on Landing Page)

**Username:** `miamoore887`  
**Password:** `Client@123`  
**Email:** mia.moore94@example.com  
**Name:** Mia Moore  
**Role:** Client  

**Dashboard URL:** http://localhost:8000/pages/client/dashboard.php

---

### Additional Test Client

**Username:** `benjaminjackson837`  
**Password:** `Client@123`  
**Email:** benjamin.jackson52@example.com  
**Name:** Benjamin Jackson  
**Role:** Client  

---

## How to Login

### Method 1: Quick Demo Button (Easiest!)
1. Go to http://localhost:8000
2. Click the **"🚀 Login as Demo Client (Mia Moore)"** button
3. You'll be automatically logged in and redirected to the client dashboard

### Method 2: Manual Login
1. Go to http://localhost:8000/pages/login.php
2. Enter username: `miamoore887`
3. Enter password: `Client@123`
4. Click "Login"

---

## What You Can Test

Once logged in as a client, you can access:

✅ **Dashboard** - Overview of your profile and activities  
✅ **Care Plan** - View personalized care plans  
✅ **Assessments** - Complete self-assessments  
✅ **Goals** - Track personal goals and milestones  
✅ **Orders** - View harm reduction supply orders  
✅ **Messages** - Secure messaging with care team  
✅ **Appointments** - View and manage appointments  
✅ **Resources** - Browse local service directory  
✅ **Profile** - View and edit personal information  
✅ **Settings** - Manage account preferences  

---

## Additional Test Accounts (If Generated)

All generated client accounts use the same default password:

**Password:** `Client@123`

To see all available test accounts, run:
```bash
sqlite3 database/tweak_easy.db "SELECT username, email, first_name, last_name FROM users WHERE role='client';"
```

---

## Test Data Available

The database includes mock data for:
- ✅ Client profiles
- ✅ Care plans with goals and milestones
- ✅ Orders and order items
- ✅ Messages between users
- ✅ Appointments with service providers
- ✅ Referrals to service providers
- ✅ Case notes

---

## Security Notes

⚠️ **Important:** These are test credentials for development/demonstration purposes only.

- All test accounts use the same simple password: `Client@123`
- In production, enforce strong password requirements
- Change or remove test accounts before deployment
- The demo login button should be removed in production

---

## Troubleshooting

### If you can't log in:
1. Make sure the PHP server is running: `php -S localhost:8000`
2. Check the database exists: `ls -la database/tweak_easy.db`
3. Verify test users exist: `sqlite3 database/tweak_easy.db "SELECT username FROM users WHERE role='client';"`

### If pages don't load after login:
1. Clear your browser cookies
2. Try logging in again
3. Check browser console for errors

---

## Quick Links

- **Landing Page:** http://localhost:8000
- **Login Page:** http://localhost:8000/pages/login.php
- **Client Dashboard:** http://localhost:8000/pages/client/dashboard.php
- **Test Report:** http://localhost:8000/test_report.html

---

*Last Updated: December 7, 2025*
