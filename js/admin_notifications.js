// Admin Notification System
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.unreadCount = 0;
        this.lastCheck = null;
        this.pollInterval = 30000; // 30 seconds
        this.showingAll = false; // Track if showing all notifications
        this.init();
    }

    init() {
        this.loadNotifications();
        this.startPolling();
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Handle notification dropdown toggle
        const notificationDropdown = document.getElementById('notificationDropdown');
        if (notificationDropdown) {
            notificationDropdown.addEventListener('click', () => {
                this.markNotificationsAsRead();
            });
        }
    }

    async loadNotifications(unlimited = false) {
        try {
            const url = unlimited ? 'api/admin/get_notifications.php?unlimited=true' : 'api/admin/get_notifications.php';
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.success) {
                this.notifications = data.notifications;
                this.showingAll = unlimited;
                this.updateNotificationDisplay();
                this.updateNotificationCount();
            } else {
                console.error('Failed to load notifications:', data.error);
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    updateNotificationDisplay() {
        const container = document.getElementById('notifications-container');
        if (!container) return;

        if (this.notifications.length === 0) {
            container.innerHTML = `
                <div class="text-center p-3 text-muted">
                    <i class="bi bi-bell-slash fs-4"></i>
                    <p class="mb-0 mt-2">No notifications</p>
                </div>
            `;
            return;
        }

        // Group notifications by date
        const groupedNotifications = this.groupNotificationsByDate(this.notifications);
        
        let html = '';
        Object.keys(groupedNotifications).forEach(dateGroup => {
            // Add date header
            html += `
                <div class="px-3 py-2 bg-light border-bottom">
                    <small class="text-muted fw-semibold">${dateGroup}</small>
                </div>
            `;
            
            // Add notifications for this date group
            groupedNotifications[dateGroup].forEach(notification => {
                const isNew = this.isNewNotification(notification);
                html += `
                    <div class="dropdown-item notification-item ${isNew ? 'bg-light' : ''}" 
                         data-notification-id="${notification.id}" style="white-space: normal; word-wrap: break-word; overflow-wrap: break-word; padding: 12px 16px; height: auto; min-height: auto;">
                        <div class="d-flex align-items-start">
                            <div class="me-3 flex-shrink-0">
                                <i class="bi ${notification.icon} fs-5 ${notification.color}"></i>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0; width: 100%;">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 fw-semibold" style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal; line-height: 1.3; flex: 1; margin-right: 8px;">${notification.title}</h6>
                                    ${isNew ? '<span class="badge bg-primary rounded-pill flex-shrink-0">New</span>' : ''}
                                </div>
                                <p class="mb-1 text-muted small" style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal; line-height: 1.4; width: 100%; display: block;">${notification.message}</p>
                                <small class="text-muted">${notification.timeAgo}</small>
                            </div>
                        </div>
                    </div>
                `;
            });
        });

        // Add "Show All" or "Show Recent" button if not showing all or if showing all
        if (!this.showingAll && this.notifications.length >= 15) {
            html += `
                <div class="dropdown-divider"></div>
                <div class="text-center" style="padding: 8px 16px; background: none;">
                    <button class="btn btn-outline-primary btn-sm" onclick="showAllNotifications(event)" style="font-size: 0.875rem;">
                        <i class="bi bi-arrow-down-circle me-1"></i>
                        Show All Past Notifications
                    </button>
                </div>
            `;
        } else if (this.showingAll) {
            html += `
                <div class="dropdown-divider"></div>
                <div class="text-center" style="padding: 8px 16px; background: none;">
                    <button class="btn btn-outline-secondary btn-sm" onclick="showRecentNotifications(event)" style="font-size: 0.875rem;">
                        <i class="bi bi-arrow-up-circle me-1"></i>
                        Show Recent Only
                    </button>
                </div>
            `;
        }

        container.innerHTML = html;
    }

    updateNotificationCount() {
        const countElement = document.getElementById('notification-count');
        if (!countElement) return;

        // Count unread notifications based on database is_read field
        const unreadNotifications = this.notifications.filter(n => this.isUnreadNotification(n));
        this.unreadCount = unreadNotifications.length;

        if (this.unreadCount > 0) {
            countElement.textContent = this.unreadCount > 99 ? '99+' : this.unreadCount;
            countElement.style.display = 'block';
        } else {
            countElement.style.display = 'none';
        }
    }

    isUnreadNotification(notification) {
        // Check if notification is unread based on database field
        return notification.is_read == 0 || notification.is_read === false;
    }

    isNewNotification(notification) {
        // For display purposes, show unread notifications with special styling
        return this.isUnreadNotification(notification);
    }

    groupNotificationsByDate(notifications) {
        const groups = {};
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        const thisWeekStart = new Date(today);
        thisWeekStart.setDate(today.getDate() - today.getDay());

        notifications.forEach(notification => {
            const notificationDate = new Date(notification.time);
            const notificationDay = new Date(notificationDate.getFullYear(), notificationDate.getMonth(), notificationDate.getDate());
            
            let groupKey;
            
            if (notificationDay.getTime() === today.getTime()) {
                groupKey = 'Today';
            } else if (notificationDay.getTime() === yesterday.getTime()) {
                groupKey = 'Yesterday';
            } else if (notificationDay >= thisWeekStart) {
                groupKey = 'This Week';
            } else {
                // Format as "Month Day, Year" for older notifications
                groupKey = notificationDate.toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
            }
            
            if (!groups[groupKey]) {
                groups[groupKey] = [];
            }
            groups[groupKey].push(notification);
        });

        // Sort groups by date (newest first)
        const sortedGroups = {};
        const groupOrder = ['Today', 'Yesterday', 'This Week'];
        
        // Add predefined groups first
        groupOrder.forEach(group => {
            if (groups[group]) {
                sortedGroups[group] = groups[group];
            }
        });
        
        // Add other date groups sorted by date (newest first)
        Object.keys(groups)
            .filter(key => !groupOrder.includes(key))
            .sort((a, b) => new Date(b) - new Date(a))
            .forEach(key => {
                sortedGroups[key] = groups[key];
            });

        return sortedGroups;
    }

    markNotificationsAsRead() {
        // Mark all notifications as read by updating the last check time
        this.lastCheck = new Date();
        
        // Update the display to remove "new" badges
        setTimeout(() => {
            const newBadges = document.querySelectorAll('.notification-item .badge');
            newBadges.forEach(badge => badge.remove());
            
            const highlightedItems = document.querySelectorAll('.notification-item.bg-light');
            highlightedItems.forEach(item => item.classList.remove('bg-light'));
            
            this.updateNotificationCount();
        }, 500);
    }

    async markAllNotificationsAsRead() {
        try {
            const response = await fetch('api/admin/mark_notifications_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Reload notifications to reflect the updated read status
                await this.loadNotifications();
                
                // Show success message
                this.showToast('All notifications marked as read', 'success');
            } else {
                throw new Error(data.error || 'Failed to mark notifications as read');
            }
            
        } catch (error) {
            console.error('Error marking notifications as read:', error);
            this.showToast('Failed to mark notifications as read', 'danger');
        }
    }

    startPolling() {
        // Poll for new notifications every 30 seconds
        setInterval(() => {
            this.loadNotifications();
        }, this.pollInterval);
    }

    showToast(message, type = 'info') {
        // Create a simple toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed`;
        toast.style.cssText = `
            top: 20px; 
            right: 20px; 
            z-index: 9999; 
            min-width: 250px;
            animation: slideIn 0.3s ease-out;
        `;
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 3000);
    }
}

// Global functions for onclick handlers
function markNotificationsAsRead() {
    if (window.notificationManager) {
        window.notificationManager.markNotificationsAsRead();
    }
}

function markAllNotificationsAsRead() {
    if (window.notificationManager) {
        window.notificationManager.markAllNotificationsAsRead();
    }
}

function showAllNotifications(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    if (window.notificationManager) {
        window.notificationManager.loadNotifications(true);
    }
}

function showRecentNotifications(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    if (window.notificationManager) {
        window.notificationManager.loadNotifications(false);
    }
}

// Initialize notification manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize on admin pages
    if (document.getElementById('notificationDropdown')) {
        window.notificationManager = new NotificationManager();
    }
});

// Add CSS animation for toast
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .notification-item {
        border-bottom: 1px solid #eee;
        padding: 12px 16px;
        transition: background-color 0.2s ease;
    }
    
    .notification-item:hover {
        background-color: #f8f9fa !important;
    }
    
    .notification-item:last-child {
        border-bottom: none;
    }
`;
document.head.appendChild(style);
