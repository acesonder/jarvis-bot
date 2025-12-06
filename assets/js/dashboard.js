/**
 * Tweak Easy - Dashboard JavaScript
 */

// Sidebar Toggle
const SidebarManager = {
    init() {
        this.sidebar = document.getElementById('sidebar');
        this.toggle = document.getElementById('sidebarToggle');
        this.overlay = null;
        
        if (this.toggle) {
            this.toggle.addEventListener('click', () => this.toggleSidebar());
        }
        
        this.createOverlay();
    },
    
    createOverlay() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'sidebar-overlay';
        this.overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        `;
        document.body.appendChild(this.overlay);
        
        this.overlay.addEventListener('click', () => this.closeSidebar());
    },
    
    toggleSidebar() {
        if (this.sidebar.classList.contains('open')) {
            this.closeSidebar();
        } else {
            this.openSidebar();
        }
    },
    
    openSidebar() {
        this.sidebar.classList.add('open');
        this.overlay.style.opacity = '1';
        this.overlay.style.visibility = 'visible';
        document.body.style.overflow = 'hidden';
    },
    
    closeSidebar() {
        this.sidebar.classList.remove('open');
        this.overlay.style.opacity = '0';
        this.overlay.style.visibility = 'hidden';
        document.body.style.overflow = '';
    }
};

// User Menu
const UserMenuManager = {
    init() {
        this.menu = document.getElementById('userMenu');
        if (!this.menu) return;
        
        this.menu.addEventListener('click', (e) => {
            e.stopPropagation();
            this.menu.classList.toggle('active');
        });
        
        document.addEventListener('click', () => {
            this.menu.classList.remove('active');
        });
    }
};

// Supply Order Widget
const SupplyOrderManager = {
    orders: {},
    
    init() {
        this.widgets = document.querySelectorAll('.supply-widget');
        this.orderBtn = document.getElementById('placeOrderBtn');
        
        this.widgets.forEach(widget => {
            const productId = widget.dataset.productId;
            this.orders[productId] = 0;
            
            const addBtn = widget.querySelector('.add-bubble');
            const removeBtn = widget.querySelector('.remove-bubble');
            const countDisplay = widget.querySelector('.order-count');
            
            if (addBtn) {
                addBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.addToOrder(productId, countDisplay);
                });
            }
            
            if (removeBtn) {
                removeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.removeFromOrder(productId, countDisplay);
                });
            }
        });
    },
    
    addToOrder(productId, display) {
        this.orders[productId]++;
        this.updateDisplay(productId, display);
        this.updateOrderButton();
        
        // Animation
        display.style.transform = 'scale(1.3)';
        setTimeout(() => {
            display.style.transform = 'scale(1)';
        }, 150);
    },
    
    removeFromOrder(productId, display) {
        if (this.orders[productId] > 0) {
            this.orders[productId]--;
            this.updateDisplay(productId, display);
            this.updateOrderButton();
        }
    },
    
    updateDisplay(productId, display) {
        const count = this.orders[productId];
        display.textContent = count;
        
        if (count > 0) {
            display.classList.add('visible');
        } else {
            display.classList.remove('visible');
        }
    },
    
    updateOrderButton() {
        const totalItems = Object.values(this.orders).reduce((sum, count) => sum + count, 0);
        
        if (this.orderBtn) {
            this.orderBtn.disabled = totalItems === 0;
            this.orderBtn.textContent = totalItems > 0 
                ? `Place Order (${totalItems} items)` 
                : 'Place Order';
        }
    },
    
    getOrderSummary() {
        return Object.entries(this.orders)
            .filter(([id, count]) => count > 0)
            .map(([id, count]) => ({ productId: id, quantity: count }));
    }
};

// Notification Panel
const NotificationManager = {
    init() {
        this.btn = document.getElementById('notificationBtn');
        if (!this.btn) return;
        
        this.btn.addEventListener('click', () => this.togglePanel());
    },
    
    togglePanel() {
        // In a real app, this would open a notification dropdown/panel
        if (window.TweakEasy && window.TweakEasy.Notifications) {
            window.TweakEasy.Notifications.info('You have 3 new notifications');
        }
    }
};

// Dashboard Stats Animation
const StatsAnimator = {
    init() {
        const statValues = document.querySelectorAll('.stat-value');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateValue(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        statValues.forEach(stat => observer.observe(stat));
    },
    
    animateValue(element) {
        const target = parseInt(element.textContent);
        if (isNaN(target)) return;
        
        const duration = 1000;
        const steps = 30;
        const increment = target / steps;
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current);
            }
        }, duration / steps);
    }
};

// Real-time Updates Simulator
const RealTimeUpdates = {
    init() {
        // Simulate receiving new messages periodically
        this.checkForUpdates();
    },
    
    checkForUpdates() {
        setInterval(() => {
            // In a real app, this would poll the server or use WebSockets
            const random = Math.random();
            if (random < 0.1) { // 10% chance every check
                this.showNewMessageNotification();
            }
        }, 30000); // Check every 30 seconds
    },
    
    showNewMessageNotification() {
        if (window.TweakEasy && window.TweakEasy.Notifications) {
            window.TweakEasy.Notifications.info('You have a new message');
        }
        
        // Update badge count
        const badge = document.querySelector('.nav-badge');
        if (badge) {
            const currentCount = parseInt(badge.textContent) || 0;
            badge.textContent = currentCount + 1;
        }
    }
};

// Card Hover Effects
const CardEffects = {
    init() {
        const cards = document.querySelectorAll('.dashboard-card, .stat-card, .resource-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transition = 'all 0.3s ease';
            });
        });
    }
};

// Initialize all dashboard features
document.addEventListener('DOMContentLoaded', () => {
    SidebarManager.init();
    UserMenuManager.init();
    SupplyOrderManager.init();
    NotificationManager.init();
    StatsAnimator.init();
    RealTimeUpdates.init();
    CardEffects.init();
    
    // Handle order button click
    const orderBtn = document.getElementById('placeOrderBtn');
    if (orderBtn) {
        orderBtn.addEventListener('click', () => {
            const summary = SupplyOrderManager.getOrderSummary();
            if (summary.length > 0) {
                if (window.TweakEasy && window.TweakEasy.Notifications) {
                    window.TweakEasy.Notifications.success('Order placed successfully! You will be notified when ready.');
                }
                
                // Reset orders
                SupplyOrderManager.widgets.forEach(widget => {
                    const productId = widget.dataset.productId;
                    const countDisplay = widget.querySelector('.order-count');
                    SupplyOrderManager.orders[productId] = 0;
                    SupplyOrderManager.updateDisplay(productId, countDisplay);
                });
                SupplyOrderManager.updateOrderButton();
            }
        });
    }
});
