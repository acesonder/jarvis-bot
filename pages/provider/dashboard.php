<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Provider Dashboard - Tweak Easy</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="theme-light dashboard-page">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="../../index.html" class="sidebar-logo">
                <svg viewBox="0 0 60 60" width="40" height="40">
                    <circle cx="30" cy="30" r="28" fill="url(#providerLogoGradient)"/>
                    <path d="M20 30 L30 20 L40 30 L30 40 Z" fill="#fff" opacity="0.9"/>
                    <circle cx="30" cy="30" r="8" fill="#fff"/>
                    <defs>
                        <linearGradient id="providerLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#9f7aea"/>
                            <stop offset="100%" style="stop-color:#805ad5"/>
                        </linearGradient>
                    </defs>
                </svg>
                <span>Tweak Easy</span>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
                <li class="nav-item active">
                    <a href="dashboard.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="referrals.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                        </svg>
                        <span>Referrals</span>
                        <span class="nav-badge">12</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="clients.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3z"/>
                        </svg>
                        <span>Clients</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="appointments.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/>
                        </svg>
                        <span>Appointments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="messages.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                        </svg>
                        <span>Messages</span>
                        <span class="nav-badge">5</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="documents.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6z"/>
                        </svg>
                        <span>Documents</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="analytics.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                        </svg>
                        <span>Analytics</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <a href="settings.php" class="settings-link">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M19.14 12.94c.04-.31.06-.63.06-.94 0-.31-.02-.63-.06-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/>
                </svg>
                <span>Settings</span>
            </a>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header provider-header">
            <button class="sidebar-toggle" id="sidebarToggle">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                </svg>
            </button>
            
            <div class="header-search">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5z"/>
                </svg>
                <input type="text" placeholder="Search referrals, clients...">
            </div>
            
            <div class="header-actions">
                <button class="header-btn notification-btn">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                    <span class="notification-dot"></span>
                </button>
                
                <div class="user-menu" id="userMenu">
                    <button class="user-menu-btn">
                        <div class="avatar" style="background: linear-gradient(135deg, #9f7aea, #805ad5);">CH</div>
                        <span class="user-name">Community Health</span>
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </button>
                    <div class="user-dropdown">
                        <a href="profile.php">Profile</a>
                        <a href="settings.php">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a href="../login.php" class="logout-link">Logout</a>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="page-header">
                <h1>Provider Dashboard</h1>
                <p>Manage referrals and coordinate client services</p>
            </div>
            
            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">12</span>
                        <span class="stat-label">Pending Referrals</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #48bb78, #38a169);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">34</span>
                        <span class="stat-label">Active Clients</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4299e1, #3182ce);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">8</span>
                        <span class="stat-label">Appointments Today</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #9f7aea, #805ad5);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">156</span>
                        <span class="stat-label">Completed This Month</span>
                    </div>
                </div>
            </div>
            
            <!-- Main Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Pending Referrals -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Pending Referrals</h3>
                        <a href="referrals.php" class="card-link">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="referral-list">
                            <div class="referral-item">
                                <div class="referral-info">
                                    <span class="referral-client">John Doe</span>
                                    <span class="referral-type">Mental Health Assessment</span>
                                    <span class="referral-from">From: Sarah Mitchell (Outreach)</span>
                                </div>
                                <div class="referral-actions">
                                    <button class="btn btn-sm btn-primary">Accept</button>
                                    <button class="btn btn-sm btn-outline">Decline</button>
                                </div>
                            </div>
                            <div class="referral-item">
                                <div class="referral-info">
                                    <span class="referral-client">Maria Lopez</span>
                                    <span class="referral-type">Primary Care Visit</span>
                                    <span class="referral-from">From: City Shelter</span>
                                </div>
                                <div class="referral-actions">
                                    <button class="btn btn-sm btn-primary">Accept</button>
                                    <button class="btn btn-sm btn-outline">Decline</button>
                                </div>
                            </div>
                            <div class="referral-item urgent">
                                <div class="referral-info">
                                    <span class="referral-client">Anonymous Client</span>
                                    <span class="referral-type">Urgent: Wound Care</span>
                                    <span class="referral-from">From: Mobile Outreach Team</span>
                                </div>
                                <div class="referral-actions">
                                    <button class="btn btn-sm btn-danger">Urgent Accept</button>
                                    <button class="btn btn-sm btn-outline">Decline</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Today's Appointments -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Today's Appointments</h3>
                        <a href="appointments.php" class="card-link">Full Schedule</a>
                    </div>
                    <div class="card-body">
                        <div class="appointment-list">
                            <div class="appointment-item">
                                <div class="appointment-date">
                                    <span class="time">9:00</span>
                                    <span class="period">AM</span>
                                </div>
                                <div class="appointment-info">
                                    <span class="appointment-title">John D. - Follow-up</span>
                                    <span class="appointment-type">In-Person • Room 102</span>
                                </div>
                                <span class="badge badge-info">Confirmed</span>
                            </div>
                            <div class="appointment-item current">
                                <div class="appointment-date">
                                    <span class="time">10:30</span>
                                    <span class="period">AM</span>
                                </div>
                                <div class="appointment-info">
                                    <span class="appointment-title">Maria L. - Initial Assessment</span>
                                    <span class="appointment-type">In-Person • Room 105</span>
                                </div>
                                <span class="badge badge-success">In Progress</span>
                            </div>
                            <div class="appointment-item">
                                <div class="appointment-date">
                                    <span class="time">1:00</span>
                                    <span class="period">PM</span>
                                </div>
                                <div class="appointment-info">
                                    <span class="appointment-title">Robert J. - Care Plan Review</span>
                                    <span class="appointment-type">Video Call</span>
                                </div>
                                <span class="badge badge-warning">Pending</span>
                            </div>
                            <div class="appointment-item">
                                <div class="appointment-date">
                                    <span class="time">3:30</span>
                                    <span class="period">PM</span>
                                </div>
                                <div class="appointment-info">
                                    <span class="appointment-title">Team Meeting</span>
                                    <span class="appointment-type">Conference Room A</span>
                                </div>
                                <span class="badge badge-info">Scheduled</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Messages -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Recent Messages</h3>
                        <a href="messages.php" class="card-link">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="message-list">
                            <div class="message-item unread">
                                <div class="avatar" style="background: linear-gradient(135deg, #4299e1, #3182ce);">SM</div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="sender-name">Sarah Mitchell</span>
                                        <span class="message-time">1h ago</span>
                                    </div>
                                    <p class="message-preview">Urgent: Client needs wound care follow-up...</p>
                                </div>
                            </div>
                            <div class="message-item unread">
                                <div class="avatar" style="background: linear-gradient(135deg, #48bb78, #38a169);">JD</div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="sender-name">John Doe</span>
                                        <span class="message-time">3h ago</span>
                                    </div>
                                    <p class="message-preview">Thank you for the appointment reminder...</p>
                                </div>
                            </div>
                            <div class="message-item">
                                <div class="avatar" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">CS</div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="sender-name">City Shelter</span>
                                        <span class="message-time">Yesterday</span>
                                    </div>
                                    <p class="message-preview">Housing application update for Maria L...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Service Metrics -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Service Metrics</h3>
                        <a href="analytics.php" class="card-link">Full Analytics</a>
                    </div>
                    <div class="card-body">
                        <div class="metrics-list">
                            <div class="metric-item">
                                <div class="metric-info">
                                    <span class="metric-label">Referral Acceptance Rate</span>
                                    <span class="metric-value">92%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 92%; background: linear-gradient(90deg, #48bb78, #38a169);"></div>
                                </div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-info">
                                    <span class="metric-label">Appointment Completion</span>
                                    <span class="metric-value">87%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 87%; background: linear-gradient(90deg, #4299e1, #3182ce);"></div>
                                </div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-info">
                                    <span class="metric-label">Client Satisfaction</span>
                                    <span class="metric-value">4.8/5</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 96%; background: linear-gradient(90deg, #9f7aea, #805ad5);"></div>
                                </div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-info">
                                    <span class="metric-label">Average Response Time</span>
                                    <span class="metric-value">2.4 hrs</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 76%; background: linear-gradient(90deg, #ed8936, #dd6b20);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <div class="theme-toggle" id="themeToggle">
        <span class="theme-icon">🌙</span>
    </div>
    
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/dashboard.js"></script>
</body>
</html>
