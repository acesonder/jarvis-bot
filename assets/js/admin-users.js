/**
 * Admin Users Management
 */

const UserManagement = {
    currentPage: 1,
    perPage: 50,
    
    init() {
        this.loadUsers();
        this.setupEventListeners();
    },
    
    setupEventListeners() {
        // Add User button
        const addBtn = document.getElementById('addUserBtn');
        if (addBtn) {
            addBtn.addEventListener('click', () => this.showAddUserModal());
        }
        
        // Add User Form
        const addForm = document.getElementById('addUserForm');
        if (addForm) {
            addForm.addEventListener('submit', (e) => this.handleAddUser(e));
        }
        
        // Edit User Form
        const editForm = document.getElementById('editUserForm');
        if (editForm) {
            editForm.addEventListener('submit', (e) => this.handleEditUser(e));
        }
        
        // Modal close buttons
        document.querySelectorAll('.modal-close, .modal-cancel').forEach(btn => {
            btn.addEventListener('click', () => this.closeModals());
        });
        
        // Close modal on outside click
        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                this.closeModals();
            }
        });
    },
    
    async loadUsers() {
        try {
            const response = await fetch(`../../api/users/?page=${this.currentPage}&per_page=${this.perPage}`);
            const result = await response.json();
            
            if (result.success && result.data) {
                this.renderUsersTable(result.data.users);
            } else {
                this.showError('Failed to load users: ' + result.message);
            }
        } catch (error) {
            this.showError('Error loading users: ' + error.message);
        }
    },
    
    renderUsersTable(users) {
        const tbody = document.getElementById('users-table');
        if (!tbody || !users) return;
        
        if (users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">No users found</td></tr>';
            return;
        }
        
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
            const actionBtn = user.status === 'active' ? 'Suspend' : 'Activate';
            const actionStatus = user.status === 'active' ? 'inactive' : 'active';
            
            return `
                <tr data-user-id="${user.id}">
                    <td>
                        <div class="user-cell">
                            <div class="avatar" style="background: linear-gradient(135deg, ${roleInfo.bg});">${initials}</div>
                            <div>
                                <div>${fullName}</div>
                                <small style="color: #718096;">${user.username}</small>
                            </div>
                        </div>
                    </td>
                    <td>${user.email}</td>
                    <td><span class="badge ${roleInfo.badge}">${roleInfo.label}</span></td>
                    <td><span class="status-dot ${statusClass}"></span> ${statusText}</td>
                    <td>${joinedDate}</td>
                    <td>
                        <button class="btn btn-sm" onclick="UserManagement.showEditUserModal(${user.id})">Edit</button>
                        <button class="btn btn-sm" onclick="UserManagement.toggleUserStatus(${user.id}, '${actionStatus}')">${actionBtn}</button>
                    </td>
                </tr>
            `;
        }).join('');
    },
    
    showAddUserModal() {
        const modal = document.getElementById('addUserModal');
        if (modal) {
            modal.style.display = 'block';
            document.getElementById('addUserForm').reset();
        }
    },
    
    async showEditUserModal(userId) {
        try {
            const response = await fetch(`../../api/users/?action=users&id=${userId}`);
            const result = await response.json();
            
            if (result.success && result.data) {
                const user = result.data;
                document.getElementById('edit-user-id').value = user.id;
                document.getElementById('edit-email').value = user.email;
                document.getElementById('edit-first-name').value = user.first_name;
                document.getElementById('edit-last-name').value = user.last_name;
                document.getElementById('edit-phone').value = user.phone || '';
                document.getElementById('edit-role').value = user.role;
                document.getElementById('edit-status').value = user.status;
                document.getElementById('edit-password').value = '';
                
                const modal = document.getElementById('editUserModal');
                if (modal) {
                    modal.style.display = 'block';
                }
            } else {
                this.showError('Failed to load user details');
            }
        } catch (error) {
            this.showError('Error loading user: ' + error.message);
        }
    },
    
    closeModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.style.display = 'none';
        });
    },
    
    async handleAddUser(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch('../../api/users/', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess('User created successfully');
                this.closeModals();
                this.loadUsers();
            } else {
                this.showError(result.message || 'Failed to create user');
            }
        } catch (error) {
            this.showError('Error creating user: ' + error.message);
        }
    },
    
    async handleEditUser(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const userId = formData.get('user_id');
        formData.delete('user_id');
        
        const data = Object.fromEntries(formData.entries());
        
        // Remove password if empty
        if (!data.password) {
            delete data.password;
        }
        
        try {
            const response = await fetch(`../../api/users/?action=users&id=${userId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess('User updated successfully');
                this.closeModals();
                this.loadUsers();
            } else {
                this.showError(result.message || 'Failed to update user');
            }
        } catch (error) {
            this.showError('Error updating user: ' + error.message);
        }
    },
    
    async toggleUserStatus(userId, newStatus) {
        if (!confirm(`Are you sure you want to ${newStatus === 'active' ? 'activate' : 'suspend'} this user?`)) {
            return;
        }
        
        try {
            const response = await fetch(`../../api/users/?action=users&id=${userId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: newStatus })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess(`User ${newStatus === 'active' ? 'activated' : 'suspended'} successfully`);
                this.loadUsers();
            } else {
                this.showError(result.message || 'Failed to update user status');
            }
        } catch (error) {
            this.showError('Error updating user status: ' + error.message);
        }
    },
    
    showSuccess(message) {
        if (window.TweakEasy && window.TweakEasy.Notifications) {
            window.TweakEasy.Notifications.success(message);
        } else {
            alert(message);
        }
    },
    
    showError(message) {
        if (window.TweakEasy && window.TweakEasy.Notifications) {
            window.TweakEasy.Notifications.error(message);
        } else {
            alert(message);
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    UserManagement.init();
});
