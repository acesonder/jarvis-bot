<?php
require_once __DIR__ . '/../../includes/auth.php';

Session::start();
$auth = new Auth();
$auth->requireRole('client');
$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Tweak Easy</title>
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
                    <circle cx="30" cy="30" r="28" fill="url(#sidebarLogoGradient)"/>
                    <path d="M20 30 L30 20 L40 30 L30 40 Z" fill="#fff" opacity="0.9"/>
                    <circle cx="30" cy="30" r="8" fill="#fff"/>
                    <defs>
                        <linearGradient id="sidebarLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#667eea"/>
                            <stop offset="100%" style="stop-color:#764ba2"/>
                        </linearGradient>
                    </defs>
                </svg>
                <span>Tweak Easy</span>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
                <li class="nav-item">
                    <a href="dashboard.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="care-plan.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                        </svg>
                        <span>Care Plan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="assessments.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        <span>Assessments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="goals.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-5-5 1.41-1.41L12 14.17l7.59-7.59L21 8l-9 9z"/>
                        </svg>
                        <span>Goals</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="orders.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                        <span>Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="messages.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                        </svg>
                        <span>Messages</span>
                        <span class="nav-badge">3</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a href="appointments.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM9 14H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2zm-8 4H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2z"/>
                        </svg>
                        <span>Appointments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="resources.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <span>Resources</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <a href="settings.php" class="settings-link">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M19.14 12.94c.04-.31.06-.63.06-.94 0-.31-.02-.63-.06-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                </svg>
                <span>Settings</span>
            </a>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <button class="sidebar-toggle" id="sidebarToggle">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                </svg>
            </button>
            
            <div class="header-search">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
                <input type="text" placeholder="Search...">
            </div>
            
            <div class="header-actions">
                <button class="header-btn notification-btn" id="notificationBtn">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                    <span class="notification-dot"></span>
                </button>
                
                <div class="user-menu" id="userMenu">
                    <button class="user-menu-btn">
                        <div class="avatar"><?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?></div>
                        <span class="user-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </button>
                    <div class="user-dropdown">
                        <a href="profile.php">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            Profile
                        </a>
                        <a href="settings.php">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M19.14 12.94c.04-.31.06-.63.06-.94 0-.31-.02-.63-.06-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/>
                            </svg>
                            Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="../login.php" class="logout-link">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="page-header">
                <h1>My Appointments</h1>
                <p>Schedule and manage your appointments</p>
            </div>
            
            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #48bb78, #38a169);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-5-5 1.41-1.41L12 14.17l7.59-7.59L21 8l-9 9z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">3</span>
                        <span class="stat-label">Active Goals</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4299e1, #3182ce);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">2</span>
                        <span class="stat-label">Upcoming Appointments</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">1</span>
                        <span class="stat-label">Pending Orders</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #9f7aea, #805ad5);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">3</span>
                        <span class="stat-label">Unread Messages</span>
                    </div>
                </div>
            </div>
            
            <!-- Main Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Goals Progress -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Goals Progress</h3>
                        <a href="goals.php" class="card-link">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="goal-item">
                            <div class="goal-info">
                                <span class="goal-title">Complete housing application</span>
                                <span class="goal-progress-text">75% complete</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: 75%; background: linear-gradient(90deg, #48bb78, #38a169);"></div>
                            </div>
                        </div>
                        <div class="goal-item">
                            <div class="goal-info">
                                <span class="goal-title">Attend 4 support group meetings</span>
                                <span class="goal-progress-text">50% complete</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: 50%; background: linear-gradient(90deg, #4299e1, #3182ce);"></div>
                            </div>
                        </div>
                        <div class="goal-item">
                            <div class="goal-info">
                                <span class="goal-title">Schedule medical checkup</span>
                                <span class="goal-progress-text">25% complete</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: 25%; background: linear-gradient(90deg, #ed8936, #dd6b20);"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Appointments -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Upcoming Appointments</h3>
                        <a href="appointments.php" class="card-link">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="appointment-item">
                            <div class="appointment-date">
                                <span class="day">15</span>
                                <span class="month">Dec</span>
                            </div>
                            <div class="appointment-info">
                                <span class="appointment-title">Case Manager Check-in</span>
                                <span class="appointment-time">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                    </svg>
                                    10:00 AM - Video Call
                                </span>
                            </div>
                            <span class="badge badge-info">Confirmed</span>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-date">
                                <span class="day">18</span>
                                <span class="month">Dec</span>
                            </div>
                            <div class="appointment-info">
                                <span class="appointment-title">Naloxone Training</span>
                                <span class="appointment-time">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                    </svg>
                                    2:00 PM - Community Center
                                </span>
                            </div>
                            <span class="badge badge-warning">Pending</span>
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
                        <div class="message-item unread">
                            <div class="avatar" style="background: linear-gradient(135deg, #4299e1, #3182ce);">SM</div>
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="sender-name">Sarah Mitchell</span>
                                    <span class="message-time">2h ago</span>
                                </div>
                                <p class="message-preview">Hi John, just following up on our last conversation about...</p>
                            </div>
                        </div>
                        <div class="message-item unread">
                            <div class="avatar" style="background: linear-gradient(135deg, #48bb78, #38a169);">CH</div>
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="sender-name">Community Health</span>
                                    <span class="message-time">5h ago</span>
                                </div>
                                <p class="message-preview">Your appointment confirmation for December 18th...</p>
                            </div>
                        </div>
                        <div class="message-item">
                            <div class="avatar" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">TE</div>
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="sender-name">Tweak Easy Support</span>
                                    <span class="message-time">1d ago</span>
                                </div>
                                <p class="message-preview">Welcome to Tweak Easy! Here are some tips to get started...</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Order -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Quick Order Supplies</h3>
                        <a href="orders.php" class="card-link">Full Catalog</a>
                    </div>
                    <div class="card-body">
                        <div class="quick-order-grid">
                            <div class="supply-widget" style="--tile-color: #e74c3c;" data-product-id="1">
                                <div class="supply-icon">
                                    <svg viewBox="0 0 60 60" fill="white">
                                        <rect x="25" y="5" width="10" height="50" rx="5"/>
                                        <rect x="22" y="8" width="16" height="8" rx="2"/>
                                    </svg>
                                </div>
                                <span class="supply-name">Syringes</span>
                                <span class="supply-stock">500 in stock</span>
                                <div class="order-bubble add-bubble">+</div>
                                <div class="order-bubble remove-bubble">-</div>
                                <div class="order-count">0</div>
                            </div>
                            <div class="supply-widget" style="--tile-color: #e67e22;" data-product-id="10">
                                <div class="supply-icon">
                                    <svg viewBox="0 0 60 60" fill="white">
                                        <rect x="10" y="15" width="40" height="30" rx="5"/>
                                        <path d="M25 25 L35 25 L35 35 L25 35 Z M28 22 L28 38 M22 30 L38 30" stroke="white" stroke-width="2" fill="none"/>
                                    </svg>
                                </div>
                                <span class="supply-name">Naloxone Kit</span>
                                <span class="supply-stock">50 in stock</span>
                                <div class="order-bubble add-bubble">+</div>
                                <div class="order-bubble remove-bubble">-</div>
                                <div class="order-count">0</div>
                            </div>
                            <div class="supply-widget" style="--tile-color: #d35400;" data-product-id="11">
                                <div class="supply-icon">
                                    <svg viewBox="0 0 60 60" fill="white">
                                        <rect x="15" y="25" width="30" height="20" rx="2"/>
                                        <line x1="20" y1="30" x2="40" y2="30" stroke="rgba(0,0,0,0.3)" stroke-width="2"/>
                                        <line x1="20" y1="35" x2="35" y2="35" stroke="rgba(0,0,0,0.3)" stroke-width="2"/>
                                    </svg>
                                </div>
                                <span class="supply-name">Test Strips</span>
                                <span class="supply-stock">200 in stock</span>
                                <div class="order-bubble add-bubble">+</div>
                                <div class="order-bubble remove-bubble">-</div>
                                <div class="order-count">0</div>
                            </div>
                            <div class="supply-widget" style="--tile-color: #1abc9c;" data-product-id="7">
                                <div class="supply-icon">
                                    <svg viewBox="0 0 60 60" fill="white">
                                        <rect x="15" y="20" width="30" height="25" rx="3"/>
                                        <path d="M20 20 L20 15 L40 15 L40 20"/>
                                    </svg>
                                </div>
                                <span class="supply-name">Alcohol Swabs</span>
                                <span class="supply-stock">1000 in stock</span>
                                <div class="order-bubble add-bubble">+</div>
                                <div class="order-bubble remove-bubble">-</div>
                                <div class="order-count">0</div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block mt-lg" id="placeOrderBtn" disabled>
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Resource Quick Links -->
            <div class="resource-section">
                <h3>Quick Resources</h3>
                <div class="resource-grid">
                    <a href="#" class="resource-card emergency">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                        </svg>
                        <span>Crisis Hotline</span>
                        <small>1-800-273-8255</small>
                    </a>
                    <a href="resources.php#health" class="resource-card">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                            <path d="M19 3H5c-1.1 0-1.99.9-1.99 2L3 19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 11h-4v4h-4v-4H6v-4h4V6h4v4h4v4z"/>
                        </svg>
                        <span>Health Services</span>
                        <small>Find nearby clinics</small>
                    </a>
                    <a href="resources.php#housing" class="resource-card">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                        </svg>
                        <span>Housing Support</span>
                        <small>Shelters & programs</small>
                    </a>
                    <a href="resources.php#food" class="resource-card">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                            <path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/>
                        </svg>
                        <span>Food Resources</span>
                        <small>Food banks & meals</small>
                    </a>
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
