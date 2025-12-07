/**
 * Messages Page - Database Integration
 */

class MessagesApp {
    constructor() {
        this.currentConversationId = null;
        this.currentRecipientId = null;
        this.conversations = [];
        this.users = [];
        this.init();
    }

    init() {
        this.bindElements();
        this.attachEventListeners();
        this.loadConversations();
        this.loadUsers();
        this.startPolling();
    }

    bindElements() {
        this.composeBtn = document.getElementById('composeBtn');
        this.closeComposeBtn = document.getElementById('closeComposeBtn');
        this.cancelComposeBtn = document.getElementById('cancelComposeBtn');
        this.composeArea = document.getElementById('composeArea');
        this.conversationArea = document.getElementById('conversationArea');
        this.composeForm = document.getElementById('composeForm');
        this.replyForm = document.getElementById('replyForm');
        this.messageThread = document.getElementById('messageThread');
        this.messageInput = document.getElementById('messageInput');
        this.recipientSelect = document.getElementById('recipientSelect');
        this.messageListContainer = document.querySelector('.message-list');
    }

    attachEventListeners() {
        // Compose button
        this.composeBtn?.addEventListener('click', () => this.showCompose());
        this.closeComposeBtn?.addEventListener('click', () => this.closeCompose());
        this.cancelComposeBtn?.addEventListener('click', () => this.closeCompose());

        // Forms
        this.composeForm?.addEventListener('submit', (e) => this.handleCompose(e));
        this.replyForm?.addEventListener('submit', (e) => this.handleReply(e));

        // Auto-resize message input
        this.messageInput?.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 150) + 'px';
        });
    }

    async loadConversations() {
        try {
            const response = await fetch('/api/messages?folder=inbox', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to load conversations');

            const data = await response.json();
            
            if (data.success) {
                this.conversations = data.data.messages;
                this.renderConversationList();
                
                // Load first conversation
                if (this.conversations.length > 0 && !this.currentConversationId) {
                    this.loadConversation(this.conversations[0].sender_id, this.conversations[0].id);
                }
            }
        } catch (error) {
            console.error('Error loading conversations:', error);
            this.showError('Failed to load messages');
        }
    }

    async loadUsers() {
        try {
            // Load available users for compose dropdown (care team members)
            // Try to get from a dedicated endpoint, fallback to manual SQL query via PHP
            // For now, we'll populate with mock data and update when endpoint is ready
            this.users = [
                { id: 1, first_name: 'System', last_name: 'Administrator', role: 'admin' },
                { id: 4, first_name: 'Harper', last_name: 'Gonzalez', role: 'outreach_worker' },
                { id: 5, first_name: 'Isabella', last_name: 'Hernandez', role: 'service_provider' }
            ];
            this.populateRecipientSelect();
        } catch (error) {
            console.error('Error loading users:', error);
        }
    }

    populateRecipientSelect() {
        if (!this.recipientSelect) return;

        // Clear existing options except the first one
        this.recipientSelect.innerHTML = '<option value="">Select recipient...</option>';

        // Add users
        this.users.forEach(user => {
            const option = document.createElement('option');
            option.value = user.user_id || user.id;
            option.textContent = `${user.first_name} ${user.last_name}`;
            this.recipientSelect.appendChild(option);
        });
    }

    renderConversationList() {
        if (!this.messageListContainer) return;

        this.messageListContainer.innerHTML = '';

        this.conversations.forEach((msg, index) => {
            const item = this.createConversationListItem(msg, index === 0);
            this.messageListContainer.appendChild(item);
        });
    }

    createConversationListItem(msg, isFirst = false) {
        const item = document.createElement('div');
        item.className = `message-list-item ${!msg.is_read ? 'unread' : ''} ${isFirst ? 'active' : ''}`;
        item.dataset.conversationId = msg.id;
        item.dataset.senderId = msg.sender_id;

        const initials = this.getInitials(msg.sender_first_name, msg.sender_last_name);
        const avatarBg = this.getAvatarColor(msg.sender_id);

        item.innerHTML = `
            <div class="avatar" style="background: ${avatarBg};">${initials}</div>
            <div class="message-content">
                <div class="message-header">
                    <span class="sender-name">${msg.sender_first_name} ${msg.sender_last_name}</span>
                    <span class="message-time">${this.formatTimeAgo(msg.created_at)}</span>
                </div>
                <p class="message-preview">${this.truncate(msg.content, 60)}</p>
            </div>
        `;

        item.addEventListener('click', () => {
            this.selectConversation(msg.sender_id, msg.id, item);
        });

        return item;
    }

    async selectConversation(senderId, messageId, itemElement) {
        // Update UI
        document.querySelectorAll('.message-list-item').forEach(i => i.classList.remove('active'));
        itemElement.classList.add('active');
        itemElement.classList.remove('unread');

        // Hide compose
        this.composeArea.style.display = 'none';
        this.conversationArea.style.display = 'flex';

        // Load conversation
        await this.loadConversation(senderId, messageId);

        // Mark as read
        if (!itemElement.classList.contains('read')) {
            this.markAsRead(messageId);
        }
    }

    async loadConversation(senderId, messageId) {
        try {
            this.currentRecipientId = senderId;
            this.currentConversationId = messageId;

            const response = await fetch(`/api/messages/${messageId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to load conversation');

            const data = await response.json();
            
            if (data.success) {
                this.renderConversation(data.data);
            }
        } catch (error) {
            console.error('Error loading conversation:', error);
            this.showError('Failed to load conversation');
        }
    }

    renderConversation(conversation) {
        // Update header
        const headerAvatar = this.conversationArea.querySelector('.card-header .avatar');
        const headerTitle = this.conversationArea.querySelector('.card-header h3');
        const headerSubtitle = this.conversationArea.querySelector('.card-header p');

        const initials = this.getInitials(conversation.sender_first_name, conversation.sender_last_name);
        const avatarBg = this.getAvatarColor(conversation.sender_id);

        if (headerAvatar) {
            headerAvatar.style.background = avatarBg;
            headerAvatar.textContent = initials;
        }

        if (headerTitle) {
            headerTitle.textContent = `${conversation.sender_first_name} ${conversation.sender_last_name}`;
        }

        if (headerSubtitle) {
            headerSubtitle.textContent = 'Care Team Member';
        }

        // Render messages
        this.messageThread.innerHTML = '';

        // Add original message
        this.addMessageBubble({
            type: 'received',
            sender: `${conversation.sender_first_name} ${conversation.sender_last_name}`,
            time: this.formatDateTime(conversation.created_at),
            content: conversation.content
        });

        // Add replies
        if (conversation.replies && conversation.replies.length > 0) {
            conversation.replies.forEach(reply => {
                const isSent = reply.sender_id === this.getCurrentUserId();
                this.addMessageBubble({
                    type: isSent ? 'sent' : 'received',
                    sender: isSent ? 'You' : `${reply.sender_first_name} ${reply.sender_last_name}`,
                    time: this.formatDateTime(reply.created_at),
                    content: reply.content
                });
            });
        }

        // Scroll to bottom
        this.messageThread.scrollTop = this.messageThread.scrollHeight;
    }

    addMessageBubble(message) {
        const bubble = document.createElement('div');
        bubble.className = `message-bubble ${message.type}`;
        bubble.innerHTML = `
            <div class="message-bubble-header">
                <span class="message-sender">${message.sender}</span>
                <span class="message-timestamp">${message.time}</span>
            </div>
            <div class="message-bubble-content">
                ${message.content}
            </div>
        `;
        this.messageThread.appendChild(bubble);
    }

    async handleReply(e) {
        e.preventDefault();

        const content = this.messageInput.value.trim();
        if (!content || !this.currentRecipientId) return;

        try {
            const response = await fetch('/api/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': this.getCSRFToken()
                },
                body: JSON.stringify({
                    recipient_id: this.currentRecipientId,
                    content: content,
                    parent_message_id: this.currentConversationId
                })
            });

            if (!response.ok) throw new Error('Failed to send message');

            const data = await response.json();
            
            if (data.success) {
                // Add message to UI
                this.addMessageBubble({
                    type: 'sent',
                    sender: 'You',
                    time: this.formatDateTime(new Date().toISOString()),
                    content: content
                });

                // Clear input
                this.messageInput.value = '';
                this.messageInput.style.height = 'auto';

                // Scroll to bottom
                this.messageThread.scrollTop = this.messageThread.scrollHeight;
            }
        } catch (error) {
            console.error('Error sending message:', error);
            this.showError('Failed to send message');
        }
    }

    async handleCompose(e) {
        e.preventDefault();

        const recipientId = document.getElementById('recipientSelect').value;
        const subject = document.getElementById('subjectInput').value;
        const content = document.getElementById('composeMessageInput').value;

        if (!recipientId || !subject || !content) {
            this.showError('Please fill in all fields');
            return;
        }

        try {
            const response = await fetch('/api/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': this.getCSRFToken()
                },
                body: JSON.stringify({
                    recipient_id: parseInt(recipientId),
                    subject: subject,
                    content: content
                })
            });

            if (!response.ok) throw new Error('Failed to send message');

            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Message sent successfully!');
                this.closeCompose();
                this.loadConversations();
            }
        } catch (error) {
            console.error('Error sending message:', error);
            this.showError('Failed to send message');
        }
    }

    async markAsRead(messageId) {
        try {
            await fetch(`/api/messages/${messageId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': this.getCSRFToken()
                },
                body: JSON.stringify({ is_read: true })
            });

            this.updateUnreadBadge();
        } catch (error) {
            console.error('Error marking message as read:', error);
        }
    }

    async updateUnreadBadge() {
        try {
            const response = await fetch('/api/messages/unread-count');
            const data = await response.json();
            
            if (data.success) {
                const badge = document.querySelector('.nav-badge');
                if (badge) {
                    const count = data.data.count;
                    badge.textContent = count;
                    badge.style.display = count > 0 ? 'inline-block' : 'none';
                }
            }
        } catch (error) {
            console.error('Error updating unread count:', error);
        }
    }

    showCompose() {
        this.conversationArea.style.display = 'none';
        this.composeArea.style.display = 'flex';
        document.querySelectorAll('.message-list-item').forEach(item => {
            item.classList.remove('active');
        });
    }

    closeCompose() {
        this.composeArea.style.display = 'none';
        this.conversationArea.style.display = 'flex';
        this.composeForm.reset();
        
        // Reactivate first conversation
        const firstItem = document.querySelector('.message-list-item');
        if (firstItem) {
            firstItem.classList.add('active');
        }
    }

    startPolling() {
        // Poll for new messages every 30 seconds
        setInterval(() => {
            this.loadConversations();
            this.updateUnreadBadge();
        }, 30000);
    }

    // Helper functions
    getInitials(firstName, lastName) {
        return `${firstName?.charAt(0) || ''}${lastName?.charAt(0) || ''}`.toUpperCase();
    }

    getAvatarColor(userId) {
        const colors = [
            'linear-gradient(135deg, #4299e1, #3182ce)',
            'linear-gradient(135deg, #48bb78, #38a169)',
            'linear-gradient(135deg, #ed8936, #dd6b20)',
            'linear-gradient(135deg, #9f7aea, #805ad5)',
            'linear-gradient(135deg, #f56565, #e53e3e)',
            'linear-gradient(135deg, #38b2ac, #319795)'
        ];
        return colors[userId % colors.length];
    }

    formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000); // seconds

        if (diff < 60) return 'Just now';
        if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
        if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
        if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`;
        
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    truncate(text, length) {
        if (!text) return '';
        return text.length > length ? text.substring(0, length) + '...' : text;
    }

    getCurrentUserId() {
        // Get from session/cookie or global variable
        return window.currentUserId || null;
    }

    getCSRFToken() {
        // Get CSRF token from meta tag or cookie
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    showError(message) {
        alert(message); // Replace with better notification system
    }

    showSuccess(message) {
        alert(message); // Replace with better notification system
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.messagesApp = new MessagesApp();
});
