<?php
require_once __DIR__ . '/../../includes/auth.php';

Session::start();
$auth = new Auth();
$auth->requireRole('admin');
$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tweak Easy</title>
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
                    <circle cx="30" cy="30" r="28" fill="url(#adminLogoGradient)"/>
                    <path d="M20 30 L30 20 L40 30 L30 40 Z" fill="#fff" opacity="0.9"/>
                    <circle cx="30" cy="30" r="8" fill="#fff"/>
                    <defs>
                        <linearGradient id="adminLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ed8936"/>
                            <stop offset="100%" style="stop-color:#dd6b20"/>
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
                    <a href="users.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3z"/>
                        </svg>
                        <span>User Management</span>
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
                <li class="nav-item">
                    <a href="reports.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                        <span>Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="inventory.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M20 2H4c-1 0-2 .9-2 2v3.01c0 .72.43 1.34 1 1.69V20c0 1.1 1.1 2 2 2h14c.9 0 2-.9 2-2V8.7c.57-.35 1-.97 1-1.69V4c0-1.1-1-2-2-2z"/>
                        </svg>
                        <span>Inventory</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="services.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                        </svg>
                        <span>Service Directory</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="compliance.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                        </svg>
                        <span>Compliance</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="audit-log.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                        </svg>
                        <span>Audit Log</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="settings.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M19.14 12.94c.04-.31.06-.63.06-.94 0-.31-.02-.63-.06-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/>
                        </svg>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header admin-header">
            <button class="sidebar-toggle" id="sidebarToggle">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                </svg>
            </button>
            
            <div class="header-search">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5z"/>
                </svg>
                <input type="text" placeholder="Search users, reports...">
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
                        <div class="avatar" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">AD</div>
                        <span class="user-name">Admin</span>
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
                <h1>Admin Dashboard</h1>
                <p>System-wide overview and management</p>
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
                        <span class="stat-value" id="total-users">0</span>
                        <span class="stat-label">Total Users</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #48bb78, #38a169);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42l.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="orders-month">0</span>
                        <span class="stat-label">Orders This Month</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #9f7aea, #805ad5);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="active-referrals">0</span>
                        <span class="stat-label">Active Referrals</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path d="M20 2H4c-1 0-2 .9-2 2v3.01c0 .72.43 1.34 1 1.69V20c0 1.1 1.1 2 2 2h14c.9 0 2-.9 2-2V8.7c.57-.35 1-.97 1-1.69V4c0-1.1-1-2-2-2z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="supplies-distributed">0</span>
                        <span class="stat-label">Supplies Distributed</span>
                    </div>
                </div>
            </div>
            
            <!-- Analytics Charts -->
            <div class="dashboard-grid">
                <div class="dashboard-card chart-card">
                    <div class="card-header">
                        <h3>Distribution Overview</h3>
                        <select class="chart-filter">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Last 90 Days</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <div class="chart-placeholder">
                            <div class="bar-chart">
                                <div class="bar" style="height: 60%;" data-label="Mon" data-value="234"></div>
                                <div class="bar" style="height: 80%;" data-label="Tue" data-value="312"></div>
                                <div class="bar" style="height: 45%;" data-label="Wed" data-value="178"></div>
                                <div class="bar" style="height: 90%;" data-label="Thu" data-value="356"></div>
                                <div class="bar" style="height: 70%;" data-label="Fri" data-value="278"></div>
                                <div class="bar" style="height: 35%;" data-label="Sat" data-value="139"></div>
                                <div class="bar" style="height: 25%;" data-label="Sun" data-value="98"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card chart-card">
                    <div class="card-header">
                        <h3>User Activity</h3>
                        <select class="chart-filter">
                            <option>By Role</option>
                            <option>By Status</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <div class="pie-chart-container">
                            <div class="pie-chart">
                                <div class="pie-segment" style="--color: #48bb78; --percent: 45;"></div>
                                <div class="pie-segment" style="--color: #4299e1; --percent: 30;"></div>
                                <div class="pie-segment" style="--color: #9f7aea; --percent: 15;"></div>
                                <div class="pie-segment" style="--color: #ed8936; --percent: 10;"></div>
                            </div>
                            <div class="pie-legend">
                                <div class="legend-item"><span style="background: #48bb78;"></span> Clients (45%)</div>
                                <div class="legend-item"><span style="background: #4299e1;"></span> Workers (30%)</div>
                                <div class="legend-item"><span style="background: #9f7aea;"></span> Providers (15%)</div>
                                <div class="legend-item"><span style="background: #ed8936;"></span> Admins (10%)</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Users -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Recent Users</h3>
                        <a href="users.php" class="card-link">Manage Users</a>
                    </div>
                    <div class="card-body">
                        <div class="table-wrapper">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-users-table">
                                    <tr>
                                        <td colspan="4" style="text-align: center;">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- System Status -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>System Status</h3>
                    </div>
                    <div class="card-body">
                        <div class="system-status-list">
                            <div class="status-item">
                                <div class="status-info">
                                    <span class="status-name">Database</span>
                                    <span class="status-desc">MySQL 8.0</span>
                                </div>
                                <span class="badge badge-success">Operational</span>
                            </div>
                            <div class="status-item">
                                <div class="status-info">
                                    <span class="status-name">API Server</span>
                                    <span class="status-desc">Response time: 45ms</span>
                                </div>
                                <span class="badge badge-success">Operational</span>
                            </div>
                            <div class="status-item">
                                <div class="status-info">
                                    <span class="status-name">File Storage</span>
                                    <span class="status-desc">78% capacity</span>
                                </div>
                                <span class="badge badge-warning">Warning</span>
                            </div>
                            <div class="status-item">
                                <div class="status-info">
                                    <span class="status-name">Email Service</span>
                                    <span class="status-desc">Last check: 2 min ago</span>
                                </div>
                                <span class="badge badge-success">Operational</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity Log -->
            <div class="activity-log-section">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Recent Activity</h3>
                        <a href="audit-log.php" class="card-link">View Full Log</a>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            <div class="activity-log-item">
                                <div class="activity-icon" style="background: var(--success-color);">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="white">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    </svg>
                                </div>
                                <div class="activity-details">
                                    <span class="activity-action">New user registered</span>
                                    <span class="activity-meta">John Doe - Client • 5 minutes ago</span>
                                </div>
                            </div>
                            <div class="activity-log-item">
                                <div class="activity-icon" style="background: var(--info-color);">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="white">
                                        <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </div>
                                <div class="activity-details">
                                    <span class="activity-action">Order #ORD-2024-0156 placed</span>
                                    <span class="activity-meta">By Sarah Mitchell • 15 minutes ago</span>
                                </div>
                            </div>
                            <div class="activity-log-item">
                                <div class="activity-icon" style="background: var(--warning-color);">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="white">
                                        <path d="M1 21h22L12 2 1 21z"/>
                                    </svg>
                                </div>
                                <div class="activity-details">
                                    <span class="activity-action">Low stock alert: Naloxone Kits</span>
                                    <span class="activity-meta">System • 1 hour ago</span>
                                </div>
                            </div>
                            <div class="activity-log-item">
                                <div class="activity-icon" style="background: var(--primary-color);">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="white">
                                        <path d="M19.14 12.94c.04-.31.06-.63.06-.94 0-.31-.02-.63-.06-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/>
                                    </svg>
                                </div>
                                <div class="activity-details">
                                    <span class="activity-action">System settings updated</span>
                                    <span class="activity-meta">By Admin • 2 hours ago</span>
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
    <script src="../../assets/js/admin-dashboard.js"></script>
</body>
</html>
