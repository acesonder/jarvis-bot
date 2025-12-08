/**
 * Admin Dashboard - Load Real-Time Data
 */

// Dashboard Data Manager
const AdminDashboard = {
    init() {
        this.loadDashboardStats();
        // Refresh stats every 30 seconds
        setInterval(() => this.loadDashboardStats(), 30000);
    },
    
    async loadDashboardStats() {
        try {
            const response = await fetch('../../api/dashboard/?action=stats');
            const result = await response.json();
            
            if (result.success && result.data) {
                this.updateStats(result.data.stats);
                this.updateRecentUsers(result.data.recent_users);
                this.updateUserRoleChart(result.data.users_by_role);
                this.updateDistributionChart(result.data.distribution_chart);
            } else {
                console.error('Failed to load dashboard stats:', result.message);
            }
        } catch (error) {
            console.error('Error loading dashboard stats:', error);
        }
    },
    
    updateStats(stats) {
        // Update stat cards with animation
        this.animateValue('total-users', stats.total_users);
        this.animateValue('orders-month', stats.orders_this_month);
        this.animateValue('active-referrals', stats.active_referrals);
        this.animateValue('supplies-distributed', stats.supplies_distributed);
    },
    
    animateValue(elementId, targetValue) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const currentValue = parseInt(element.textContent) || 0;
        const duration = 1000;
        const steps = 20;
        const increment = (targetValue - currentValue) / steps;
        let current = currentValue;
        let step = 0;
        
        const timer = setInterval(() => {
            step++;
            current += increment;
            
            if (step >= steps) {
                element.textContent = targetValue.toLocaleString();
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString();
            }
        }, duration / steps);
    },
    
    updateRecentUsers(users) {
        const tbody = document.getElementById('recent-users-table');
        if (!tbody || !users || users.length === 0) return;
        
        const roleColors = {
            'client': { bg: '#48bb78, #38a169', badge: 'badge-success', label: 'Client' },
            'outreach_worker': { bg: '#4299e1, #3182ce', badge: 'badge-info', label: 'Worker' },
            'service_provider': { bg: '#9f7aea, #805ad5', badge: 'badge-primary', label: 'Provider' },
            'admin': { bg: '#ed8936, #dd6b20', badge: 'badge-warning', label: 'Admin' }
        };
        
        tbody.innerHTML = users.map(user => {
            const roleInfo = roleColors[user.role] || { bg: '#718096, #4a5568', badge: 'badge-secondary', label: user.role };
            const initials = (user.first_name[0] + user.last_name[0]).toUpperCase();
            const fullName = `${user.first_name} ${user.last_name}`;
            const joinedDate = new Date(user.created_at).toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
            const statusClass = user.status === 'active' ? 'active' : 'inactive';
            const statusText = user.status.charAt(0).toUpperCase() + user.status.slice(1);
            
            return `
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="avatar" style="background: linear-gradient(135deg, ${roleInfo.bg});">${initials}</div>
                            <span>${fullName}</span>
                        </div>
                    </td>
                    <td><span class="badge ${roleInfo.badge}">${roleInfo.label}</span></td>
                    <td><span class="status-dot ${statusClass}"></span> ${statusText}</td>
                    <td>${joinedDate}</td>
                </tr>
            `;
        }).join('');
    },
    
    updateUserRoleChart(roleData) {
        if (!roleData || roleData.length === 0) return;
        
        const total = roleData.reduce((sum, item) => sum + item.count, 0);
        const legend = document.querySelector('.pie-legend');
        
        if (!legend) return;
        
        const roleColors = {
            'Clients': '#48bb78',
            'Workers': '#4299e1',
            'Providers': '#9f7aea',
            'Admins': '#ed8936'
        };
        
        legend.innerHTML = roleData.map(item => {
            const percentage = Math.round((item.count / total) * 100);
            const color = roleColors[item.role] || '#718096';
            return `<div class="legend-item"><span style="background: ${color};"></span> ${item.role} (${percentage}%)</div>`;
        }).join('');
    },
    
    updateDistributionChart(chartData) {
        if (!chartData || chartData.length === 0) return;
        
        const chart = document.querySelector('.bar-chart');
        if (!chart) return;
        
        const maxValue = Math.max(...chartData.map(d => d.count), 1);
        
        chart.innerHTML = chartData.map(item => {
            const height = (item.count / maxValue) * 100;
            return `<div class="bar" style="height: ${height}%;" data-label="${item.day}" data-value="${item.count}"></div>`;
        }).join('');
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    AdminDashboard.init();
});
