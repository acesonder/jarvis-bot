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
    <meta name="csrf-token" content="<?php echo Session::generateCSRFToken(); ?>">
    <title>Messages - Tweak Easy</title>
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
                <li class="nav-item active">
                    <a href="messages.php">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                        </svg>
                        <span>Messages</span>
                        <span class="nav-badge">3</span>
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
        
        <!-- Messages Content -->
        <div class="dashboard-content">
            <div class="page-header">
                <h1>Messages</h1>
                <p>Communicate securely with your care team</p>
                <button class="btn btn-primary" id="composeBtn" style="margin-left: auto;">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" style="margin-right: 8px;">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                    </svg>
                    Compose New Message
                </button>
            </div>
            
            <!-- Messages Layout: Inbox List + Conversation/Compose Area -->
            <div style="display: grid; grid-template-columns: 380px 1fr; gap: 20px; height: calc(100vh - 200px);">
                <!-- Messages Inbox List -->
                <div class="dashboard-card" style="margin: 0; display: flex; flex-direction: column; height: 100%;">
                    <div class="card-header" style="flex-shrink: 0; border-bottom: 1px solid var(--border-color);">
                        <h3>Inbox</h3>
                        <span class="nav-badge">3</span>
                    </div>
                    <div class="card-body" style="flex: 1; overflow-y: auto; padding: 0;">
                        <div class="message-list">
                            <div class="message-list-item unread active" data-conversation-id="1">
                                <div class="avatar" style="background: linear-gradient(135deg, #4299e1, #3182ce);">SM</div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="sender-name">Sarah Mitchell</span>
                                        <span class="message-time">2h ago</span>
                                    </div>
                                    <p class="message-preview">Hi John, just following up on our last conversation about...</p>
                                </div>
                            </div>
                            <div class="message-list-item unread" data-conversation-id="2">
                                <div class="avatar" style="background: linear-gradient(135deg, #48bb78, #38a169);">CH</div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="sender-name">Community Health</span>
                                        <span class="message-time">5h ago</span>
                                    </div>
                                    <p class="message-preview">Your appointment confirmation for December 18th...</p>
                                </div>
                            </div>
                            <div class="message-list-item unread" data-conversation-id="3">
                                <div class="avatar" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">DR</div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="sender-name">Dr. Roberts</span>
                                        <span class="message-time">1d ago</span>
                                    </div>
                                    <p class="message-preview">Please remember to take your medication as prescribed...</p>
                                </div>
                            </div>
                            <div class="message-list-item" data-conversation-id="4">
                                <div class="avatar" style="background: linear-gradient(135deg, #9f7aea, #805ad5);">TE</div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="sender-name">Tweak Easy Support</span>
                                        <span class="message-time">2d ago</span>
                                    </div>
                                    <p class="message-preview">Welcome to Tweak Easy! Here are some tips to get started...</p>
                                </div>
                            </div>
                            <div class="message-list-item" data-conversation-id="5">
                                <div class="avatar" style="background: linear-gradient(135deg, #f56565, #e53e3e);">MJ</div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="sender-name">Mary Johnson</span>
                                        <span class="message-time">3d ago</span>
                                    </div>
                                    <p class="message-preview">Thank you for attending the group session today...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Conversation/Compose Area -->
                <div class="dashboard-card" id="conversationArea" style="margin: 0; display: flex; flex-direction: column; height: 100%;">
                    <!-- Conversation Header -->
                    <div class="card-header" style="flex-shrink: 0; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 12px;">
                        <div class="avatar" style="background: linear-gradient(135deg, #4299e1, #3182ce); width: 40px; height: 40px;">SM</div>
                        <div style="flex: 1;">
                            <h3 style="margin: 0; font-size: 16px;">Sarah Mitchell</h3>
                            <p style="margin: 0; font-size: 13px; opacity: 0.7;">Case Manager</p>
                        </div>
                        <button class="btn btn-secondary btn-sm">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Messages Thread -->
                    <div class="card-body" id="messageThread" style="flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 16px;">
                        <!-- Received Message -->
                        <div class="message-bubble received">
                            <div class="message-bubble-header">
                                <span class="message-sender">Sarah Mitchell</span>
                                <span class="message-timestamp">Dec 7, 10:30 AM</span>
                            </div>
                            <div class="message-bubble-content">
                                Hi John, just following up on our last conversation about your housing application. Have you had a chance to gather the required documents?
                            </div>
                        </div>
                        
                        <!-- Sent Message -->
                        <div class="message-bubble sent">
                            <div class="message-bubble-header">
                                <span class="message-sender">You</span>
                                <span class="message-timestamp">Dec 7, 10:45 AM</span>
                            </div>
                            <div class="message-bubble-content">
                                Yes, I've collected most of them. I'm still waiting on my employment verification letter, but should have it by tomorrow.
                            </div>
                        </div>
                        
                        <!-- Received Message -->
                        <div class="message-bubble received">
                            <div class="message-bubble-header">
                                <span class="message-sender">Sarah Mitchell</span>
                                <span class="message-timestamp">Dec 7, 10:50 AM</span>
                            </div>
                            <div class="message-bubble-content">
                                That's great! Once you have everything, we can submit the application together. Would you like to schedule a time to meet this week?
                            </div>
                        </div>
                    </div>
                    
                    <!-- Message Reply Input -->
                    <div class="card-footer" style="flex-shrink: 0; border-top: 1px solid var(--border-color); padding: 16px;">
                        <form id="replyForm" style="display: flex; gap: 12px; align-items: flex-end;">
                            <div style="flex: 1;">
                                <textarea id="messageInput" class="form-control" rows="2" placeholder="Type your message..." style="resize: none;"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Compose New Message Area (Hidden by default) -->
                <div class="dashboard-card" id="composeArea" style="margin: 0; display: none; flex-direction: column; height: 100%;">
                    <div class="card-header" style="flex-shrink: 0; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
                        <h3 style="margin: 0;">New Message</h3>
                        <button class="btn btn-secondary btn-sm" id="closeComposeBtn">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="card-body" style="flex: 1; overflow-y: auto; padding: 20px;">
                        <form id="composeForm">
                            <div class="form-group">
                                <label for="recipientSelect">To:</label>
                                <select id="recipientSelect" class="form-control" required>
                                    <option value="">Select recipient...</option>
                                    <option value="1">Sarah Mitchell - Case Manager</option>
                                    <option value="2">Dr. Roberts - Physician</option>
                                    <option value="3">Mary Johnson - Support Coordinator</option>
                                    <option value="4">Community Health Team</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="subjectInput">Subject:</label>
                                <input type="text" id="subjectInput" class="form-control" placeholder="Enter subject" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="composeMessageInput">Message:</label>
                                <textarea id="composeMessageInput" class="form-control" rows="12" placeholder="Type your message..." required></textarea>
                            </div>
                            
                            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                                <button type="button" class="btn btn-secondary" id="cancelComposeBtn">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" style="margin-right: 8px;">
                                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                                    </svg>
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <div class="theme-toggle" id="themeToggle">
        <span class="theme-icon">🌙</span>
    </div>
    
    <style>
        /* Message List Styles */
        .message-list {
            display: flex;
            flex-direction: column;
        }
        
        .message-list-item {
            display: flex;
            gap: 12px;
            padding: 16px;
            cursor: pointer;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s;
        }
        
        .message-list-item:hover {
            background-color: var(--hover-bg);
        }
        
        .message-list-item.active {
            background-color: rgba(102, 126, 234, 0.1);
            border-left: 3px solid var(--primary-color);
        }
        
        .message-list-item.unread {
            background-color: rgba(102, 126, 234, 0.05);
        }
        
        .message-list-item.unread .sender-name {
            font-weight: 600;
        }
        
        .message-list-item .avatar {
            width: 45px;
            height: 45px;
            flex-shrink: 0;
        }
        
        .message-list-item .message-content {
            flex: 1;
            min-width: 0;
        }
        
        .message-list-item .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }
        
        .message-list-item .sender-name {
            font-size: 14px;
            font-weight: 500;
        }
        
        .message-list-item .message-time {
            font-size: 12px;
            opacity: 0.6;
            white-space: nowrap;
        }
        
        .message-list-item .message-preview {
            font-size: 13px;
            opacity: 0.7;
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Message Bubble Styles */
        .message-bubble {
            max-width: 70%;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .message-bubble.received {
            align-self: flex-start;
        }
        
        .message-bubble.sent {
            align-self: flex-end;
        }
        
        .message-bubble-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 0 4px;
        }
        
        .message-sender {
            font-size: 12px;
            font-weight: 600;
            opacity: 0.7;
        }
        
        .message-timestamp {
            font-size: 11px;
            opacity: 0.5;
        }
        
        .message-bubble-content {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .message-bubble.received .message-bubble-content {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-bottom-left-radius: 4px;
        }
        
        .message-bubble.sent .message-bubble-content {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background-color: var(--card-bg);
            color: var(--text-color);
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        select.form-control {
            cursor: pointer;
        }
        
        textarea.form-control {
            font-family: 'Poppins', sans-serif;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .card-footer {
            background-color: var(--card-bg);
        }
    </style>
    
    <script>
        // Pass user ID to JavaScript
        window.currentUserId = <?php echo $user['id']; ?>;
    </script>
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/dashboard.js"></script>
    <script src="../../assets/js/messages.js"></script>
    
    <script>
        // Messages Page Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const composeBtn = document.getElementById('composeBtn');
            const closeComposeBtn = document.getElementById('closeComposeBtn');
            const cancelComposeBtn = document.getElementById('cancelComposeBtn');
            const composeArea = document.getElementById('composeArea');
            const conversationArea = document.getElementById('conversationArea');
            const composeForm = document.getElementById('composeForm');
            const replyForm = document.getElementById('replyForm');
            const messageListItems = document.querySelectorAll('.message-list-item');
            
            // Show compose new message
            composeBtn.addEventListener('click', function() {
                conversationArea.style.display = 'none';
                composeArea.style.display = 'flex';
                document.querySelectorAll('.message-list-item').forEach(item => {
                    item.classList.remove('active');
                });
            });
            
            // Close compose area
            function closeCompose() {
                composeArea.style.display = 'none';
                conversationArea.style.display = 'flex';
                composeForm.reset();
                // Reactivate first conversation
                if (messageListItems.length > 0) {
                    messageListItems[0].classList.add('active');
                }
            }
            
            closeComposeBtn.addEventListener('click', closeCompose);
            cancelComposeBtn.addEventListener('click', closeCompose);
            
            // Handle compose form submission
            composeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const recipient = document.getElementById('recipientSelect').value;
                const subject = document.getElementById('subjectInput').value;
                const message = document.getElementById('composeMessageInput').value;
                
                if (!recipient || !subject || !message) {
                    alert('Please fill in all fields');
                    return;
                }
                
                // TODO: Send message via API
                console.log('Sending message:', { recipient, subject, message });
                
                alert('Message sent successfully!');
                closeCompose();
            });
            
            // Handle reply form submission
            replyForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const messageInput = document.getElementById('messageInput');
                const message = messageInput.value.trim();
                
                if (!message) {
                    return;
                }
                
                // TODO: Send reply via API
                console.log('Sending reply:', message);
                
                // Add message to thread (demo)
                const messageThread = document.getElementById('messageThread');
                const newMessage = document.createElement('div');
                newMessage.className = 'message-bubble sent';
                
                const now = new Date();
                const timeStr = now.toLocaleString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    hour: 'numeric', 
                    minute: '2-digit',
                    hour12: true 
                });
                
                newMessage.innerHTML = `
                    <div class="message-bubble-header">
                        <span class="message-sender">You</span>
                        <span class="message-timestamp">${timeStr}</span>
                    </div>
                    <div class="message-bubble-content">
                        ${message}
                    </div>
                `;
                
                messageThread.appendChild(newMessage);
                messageThread.scrollTop = messageThread.scrollHeight;
                
                messageInput.value = '';
            });
            
            // Mock conversation data
            const conversations = {
                '1': {
                    name: 'Sarah Mitchell',
                    role: 'Case Manager',
                    avatar: 'SM',
                    avatarBg: 'linear-gradient(135deg, #4299e1, #3182ce)',
                    messages: [
                        { type: 'received', sender: 'Sarah Mitchell', time: 'Dec 7, 10:30 AM', content: 'Hi John, just following up on our last conversation about your housing application. Have you had a chance to gather the required documents?' },
                        { type: 'sent', sender: 'You', time: 'Dec 7, 10:45 AM', content: 'Yes, I\'ve collected most of them. I\'m still waiting on my employment verification letter, but should have it by tomorrow.' },
                        { type: 'received', sender: 'Sarah Mitchell', time: 'Dec 7, 10:50 AM', content: 'That\'s great! Once you have everything, we can submit the application together. Would you like to schedule a time to meet this week?' }
                    ]
                },
                '2': {
                    name: 'Community Health',
                    role: 'Healthcare Team',
                    avatar: 'CH',
                    avatarBg: 'linear-gradient(135deg, #48bb78, #38a169)',
                    messages: [
                        { type: 'received', sender: 'Community Health', time: 'Dec 7, 7:15 AM', content: 'Your appointment confirmation for December 18th at 2:00 PM has been scheduled. Please arrive 10 minutes early.' },
                        { type: 'sent', sender: 'You', time: 'Dec 7, 7:30 AM', content: 'Thank you! I\'ll be there. Is there anything I need to bring?' },
                        { type: 'received', sender: 'Community Health', time: 'Dec 7, 7:45 AM', content: 'Please bring your ID and insurance card. We look forward to seeing you!' }
                    ]
                },
                '3': {
                    name: 'Dr. Roberts',
                    role: 'Physician',
                    avatar: 'DR',
                    avatarBg: 'linear-gradient(135deg, #ed8936, #dd6b20)',
                    messages: [
                        { type: 'received', sender: 'Dr. Roberts', time: 'Dec 6, 2:00 PM', content: 'Please remember to take your medication as prescribed - twice daily with meals.' },
                        { type: 'sent', sender: 'You', time: 'Dec 6, 2:15 PM', content: 'Will do, thank you! Should I schedule a follow-up appointment?' },
                        { type: 'received', sender: 'Dr. Roberts', time: 'Dec 6, 2:30 PM', content: 'Yes, please schedule one for next month. Contact the office at your convenience.' },
                        { type: 'sent', sender: 'You', time: 'Dec 6, 2:35 PM', content: 'Perfect, I\'ll call them tomorrow.' }
                    ]
                },
                '4': {
                    name: 'Tweak Easy Support',
                    role: 'Support Team',
                    avatar: 'TE',
                    avatarBg: 'linear-gradient(135deg, #9f7aea, #805ad5)',
                    messages: [
                        { type: 'received', sender: 'Tweak Easy Support', time: 'Dec 5, 9:00 AM', content: 'Welcome to Tweak Easy! Here are some tips to get started with our platform.' },
                        { type: 'received', sender: 'Tweak Easy Support', time: 'Dec 5, 9:01 AM', content: 'You can manage your appointments, communicate with your care team, and order supplies all in one place. If you need any help, feel free to reach out!' },
                        { type: 'sent', sender: 'You', time: 'Dec 5, 10:30 AM', content: 'Thank you! This looks great.' }
                    ]
                },
                '5': {
                    name: 'Mary Johnson',
                    role: 'Support Coordinator',
                    avatar: 'MJ',
                    avatarBg: 'linear-gradient(135deg, #f56565, #e53e3e)',
                    messages: [
                        { type: 'received', sender: 'Mary Johnson', time: 'Dec 4, 4:00 PM', content: 'Thank you for attending the group session today. It was great to see you participating!' },
                        { type: 'sent', sender: 'You', time: 'Dec 4, 4:15 PM', content: 'Thanks! I really enjoyed it. When is the next session?' },
                        { type: 'received', sender: 'Mary Johnson', time: 'Dec 4, 4:20 PM', content: 'The next session is scheduled for December 11th at 3:00 PM. Hope to see you there!' },
                        { type: 'sent', sender: 'You', time: 'Dec 4, 4:25 PM', content: 'I\'ll be there. Thanks for letting me know!' }
                    ]
                }
            };
            
            // Function to load conversation
            function loadConversation(conversationId) {
                const conversation = conversations[conversationId];
                if (!conversation) return;
                
                const messageThread = document.getElementById('messageThread');
                const conversationHeader = conversationArea.querySelector('.card-header');
                
                // Update header
                const headerAvatar = conversationHeader.querySelector('.avatar');
                const headerTitle = conversationHeader.querySelector('h3');
                const headerSubtitle = conversationHeader.querySelector('p');
                
                if (headerAvatar) {
                    headerAvatar.style.background = conversation.avatarBg;
                    headerAvatar.textContent = conversation.avatar;
                }
                
                if (headerTitle) {
                    headerTitle.textContent = conversation.name;
                }
                
                if (headerSubtitle) {
                    headerSubtitle.textContent = conversation.role;
                }
                
                // Clear existing messages
                messageThread.innerHTML = '';
                
                // Add messages
                conversation.messages.forEach(msg => {
                    const messageBubble = document.createElement('div');
                    messageBubble.className = `message-bubble ${msg.type}`;
                    messageBubble.innerHTML = `
                        <div class="message-bubble-header">
                            <span class="message-sender">${msg.sender}</span>
                            <span class="message-timestamp">${msg.time}</span>
                        </div>
                        <div class="message-bubble-content">
                            ${msg.content}
                        </div>
                    `;
                    messageThread.appendChild(messageBubble);
                });
                
                // Scroll to bottom
                messageThread.scrollTop = messageThread.scrollHeight;
            }
            
            // Handle conversation selection
            messageListItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Hide compose area if showing
                    composeArea.style.display = 'none';
                    conversationArea.style.display = 'flex';
                    
                    // Remove active class from all items
                    messageListItems.forEach(i => i.classList.remove('active'));
                    
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Remove unread status
                    this.classList.remove('unread');
                    
                    const conversationId = this.getAttribute('data-conversation-id');
                    
                    // Load conversation
                    loadConversation(conversationId);
                });
            });
            
            // Auto-resize message input
            const messageInput = document.getElementById('messageInput');
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 150) + 'px';
            });
        });
    </script>
</body>
</html>
