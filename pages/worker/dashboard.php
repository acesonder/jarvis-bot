<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outreach Worker Dashboard - Tweak Easy</title>
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
                    <circle cx="30" cy="30" r="28" fill="url(#workerLogoGradient)"/>
                    <path d="M20 30 L30 20 L40 30 L30 40 Z" fill="#fff" opacity="0.9"/>
                    <circle cx="30" cy="30" r="8" fill="#fff"/>
                    <defs>
                        <linearGradient id="workerLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#4299e1"/>
                            <stop offset="100%" style="stop-color:#3182ce"/>
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
                    <a href="clients.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                        <span>Clients</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="case-notes.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                        <span>Case Notes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="orders.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1z"/>
                        </svg>
                        <span>Supply Orders</span>
                        <span class="nav-badge">5</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="inventory.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M20 2H4c-1 0-2 .9-2 2v3.01c0 .72.43 1.34 1 1.69V20c0 1.1 1.1 2 2 2h14c.9 0 2-.9 2-2V8.7c.57-.35 1-.97 1-1.69V4c0-1.1-1-2-2-2zm-5 12H9v-2h6v2zm5-7H4V4h16v3z"/>
                        </svg>
                        <span>Inventory</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="incidents.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                        </svg>
                        <span>Incidents</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="referrals.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                        <span>Referrals</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="messages.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                        </svg>
                        <span>Messages</span>
                        <span class="nav-badge">8</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="map.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <span>Field Map</span>
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
        <header class="top-header worker-header">
            <button class="sidebar-toggle" id="sidebarToggle">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                </svg>
            </button>
            
            <div class="header-search">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
                <input type="text" placeholder="Search clients, orders...">
            </div>
            
            <div class="header-actions">
                <button class="btn btn-primary" id="quickAddBtn">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                    Quick Add
                </button>
                
                <button class="header-btn notification-btn" id="notificationBtn">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                    <span class="notification-dot"></span>
                </button>
                
                <div class="user-menu" id="userMenu">
                    <button class="user-menu-btn">
                        <div class="avatar" style="background: linear-gradient(135deg, #4299e1, #3182ce);">SM</div>
                        <span class="user-name">Sarah Mitchell</span>
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
                <h1>Outreach Dashboard</h1>
                <p>Manage your field work and client interactions</p>
            </div>
            
            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4299e1, #3182ce);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">24</span>
                        <span class="stat-label">Active Clients</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42l.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">5</span>
                        <span class="stat-label">Pending Orders</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #48bb78, #38a169);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">156</span>
                        <span class="stat-label">Supplies Distributed Today</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f56565, #c53030);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">2</span>
                        <span class="stat-label">Active Incidents</span>
                    </div>
                </div>
            </div>
            
            <!-- Main Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Today's Schedule -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Today's Schedule</h3>
                        <a href="schedule.php" class="card-link">Full Schedule</a>
                    </div>
                    <div class="card-body">
                        <div class="schedule-timeline">
                            <div class="timeline-item completed">
                                <div class="timeline-time">9:00 AM</div>
                                <div class="timeline-content">
                                    <span class="timeline-title">Morning Briefing</span>
                                    <span class="timeline-desc">Team check-in at office</span>
                                </div>
                            </div>
                            <div class="timeline-item completed">
                                <div class="timeline-time">10:30 AM</div>
                                <div class="timeline-content">
                                    <span class="timeline-title">Client Visit - John D.</span>
                                    <span class="timeline-desc">Housing follow-up</span>
                                </div>
                            </div>
                            <div class="timeline-item current">
                                <div class="timeline-time">1:00 PM</div>
                                <div class="timeline-content">
                                    <span class="timeline-title">Supply Distribution</span>
                                    <span class="timeline-desc">Downtown outreach route</span>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-time">3:30 PM</div>
                                <div class="timeline-content">
                                    <span class="timeline-title">New Client Intake</span>
                                    <span class="timeline-desc">Assessment with Maria L.</span>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-time">5:00 PM</div>
                                <div class="timeline-content">
                                    <span class="timeline-title">Documentation</span>
                                    <span class="timeline-desc">Case notes and reports</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Orders -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Pending Orders</h3>
                        <a href="orders.php" class="card-link">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="order-list">
                            <div class="order-item">
                                <div class="order-info">
                                    <span class="order-id">#ORD-2024-0156</span>
                                    <span class="order-client">John Doe</span>
                                </div>
                                <div class="order-details">
                                    <span class="order-items">5 items</span>
                                    <span class="badge badge-warning">Ready for Pickup</span>
                                </div>
                                <button class="btn btn-sm btn-outline">Process</button>
                            </div>
                            <div class="order-item">
                                <div class="order-info">
                                    <span class="order-id">#ORD-2024-0155</span>
                                    <span class="order-client">Maria Lopez</span>
                                </div>
                                <div class="order-details">
                                    <span class="order-items">3 items</span>
                                    <span class="badge badge-info">Delivery</span>
                                </div>
                                <button class="btn btn-sm btn-outline">Process</button>
                            </div>
                            <div class="order-item">
                                <div class="order-info">
                                    <span class="order-id">#ORD-2024-0154</span>
                                    <span class="order-client">Anonymous</span>
                                </div>
                                <div class="order-details">
                                    <span class="order-items">8 items</span>
                                    <span class="badge badge-warning">Ready for Pickup</span>
                                </div>
                                <button class="btn btn-sm btn-outline">Process</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Client Activity -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Recent Client Activity</h3>
                        <a href="clients.php" class="card-link">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="avatar" style="background: linear-gradient(135deg, #48bb78, #38a169);">JD</div>
                                <div class="activity-content">
                                    <span class="activity-text"><strong>John Doe</strong> completed housing application</span>
                                    <span class="activity-time">2 hours ago</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="avatar" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">ML</div>
                                <div class="activity-content">
                                    <span class="activity-text"><strong>Maria Lopez</strong> scheduled naloxone training</span>
                                    <span class="activity-time">4 hours ago</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="avatar" style="background: linear-gradient(135deg, #9f7aea, #805ad5);">RJ</div>
                                <div class="activity-content">
                                    <span class="activity-text"><strong>Robert Johnson</strong> new assessment completed</span>
                                    <span class="activity-time">Yesterday</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="avatar" style="background: linear-gradient(135deg, #4299e1, #3182ce);">AS</div>
                                <div class="activity-content">
                                    <span class="activity-text"><strong>Anonymous</strong> received supply order</span>
                                    <span class="activity-time">Yesterday</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Low Stock Alerts -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Low Stock Alerts</h3>
                        <a href="inventory.php" class="card-link">Manage Inventory</a>
                    </div>
                    <div class="card-body">
                        <div class="stock-alerts">
                            <div class="stock-item critical">
                                <div class="stock-info">
                                    <span class="stock-name">Naloxone Kits</span>
                                    <span class="stock-count">12 remaining</span>
                                </div>
                                <div class="stock-bar">
                                    <div class="stock-level" style="width: 24%; background: var(--danger-color);"></div>
                                </div>
                                <button class="btn btn-sm btn-danger">Reorder</button>
                            </div>
                            <div class="stock-item warning">
                                <div class="stock-info">
                                    <span class="stock-name">Fentanyl Test Strips</span>
                                    <span class="stock-count">45 remaining</span>
                                </div>
                                <div class="stock-bar">
                                    <div class="stock-level" style="width: 45%; background: var(--warning-color);"></div>
                                </div>
                                <button class="btn btn-sm btn-outline">Reorder</button>
                            </div>
                            <div class="stock-item warning">
                                <div class="stock-info">
                                    <span class="stock-name">Wound Care Kits</span>
                                    <span class="stock-count">18 remaining</span>
                                </div>
                                <div class="stock-bar">
                                    <div class="stock-level" style="width: 36%; background: var(--warning-color);"></div>
                                </div>
                                <button class="btn btn-sm btn-outline">Reorder</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions-section">
                <h3>Quick Actions</h3>
                <div class="quick-actions-grid">
                    <button class="quick-action-btn">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                            <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span>New Client</span>
                    </button>
                    <button class="quick-action-btn">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                        <span>Add Note</span>
                    </button>
                    <button class="quick-action-btn">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42l.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1z"/>
                        </svg>
                        <span>New Order</span>
                    </button>
                    <button class="quick-action-btn">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                            <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                        </svg>
                        <span>Report Incident</span>
                    </button>
                    <button class="quick-action-btn">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93z"/>
                        </svg>
                        <span>Create Referral</span>
                    </button>
                    <button class="quick-action-btn">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <span>Log Location</span>
                    </button>
                </div>
            </div>
        </div>
    </main>
    
    <div class="theme-toggle" id="themeToggle">
        <span class="theme-icon">🌙</span>
    </div>
    
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/dashboard.js"></script>
    <script>
        // Additional worker-specific JS
        document.getElementById('quickAddBtn')?.addEventListener('click', () => {
            if (window.TweakEasy && window.TweakEasy.Notifications) {
                window.TweakEasy.Notifications.info('Quick add menu coming soon!');
            }
        });
    </script>
</body>
</html>
