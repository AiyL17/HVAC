// Customer Notification System
class CustomerNotificationManager {
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
    }

    startPolling() {
        // Poll for new notifications every 30 seconds
        setInterval(() => {
            this.loadNotifications();
        }, this.pollInterval);
    }

    async loadNotifications(unlimited = false) {
        try {
            const url = unlimited ? 'api/customer/get_notifications.php?unlimited=true' : 'api/customer/get_notifications.php';
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

    updateNotificationCount() {
        const countElement = document.getElementById('notification-count');
        if (!countElement) return;

        // Count unread notifications based on database is_read field
        const unreadNotifications = this.notifications.filter(n => !n.is_read);
        this.unreadCount = unreadNotifications.length;

        if (this.unreadCount > 0) {
            countElement.textContent = this.unreadCount > 99 ? '99+' : this.unreadCount;
            countElement.style.display = 'block';
        } else {
            countElement.style.display = 'none';
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
                const isNew = !notification.is_read;
                html += `
                    <div class="dropdown-item notification-item ${isNew ? 'bg-light' : ''}" 
                         data-notification-id="${notification.id}" 
                         style="white-space: normal; word-wrap: break-word; overflow-wrap: break-word; padding: 12px 16px; height: auto; min-height: auto; cursor: pointer;"
                         onclick="markNotificationAsRead(${notification.id})">
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
                    <button class="btn btn-outline-primary btn-sm" onclick="showAllCustomerNotifications(event)" style="font-size: 0.875rem;">
                        <i class="bi bi-arrow-down-circle me-1"></i>
                        Show All Past Notifications
                    </button>
                </div>
            `;
        } else if (this.showingAll) {
            html += `
                <div class="dropdown-divider"></div>
                <div class="text-center" style="padding: 8px 16px; background: none;">
                    <button class="btn btn-outline-secondary btn-sm" onclick="showRecentCustomerNotifications(event)" style="font-size: 0.875rem;">
                        <i class="bi bi-arrow-up-circle me-1"></i>
                        Show Recent Only
                    </button>
                </div>
            `;
        }

        container.innerHTML = html;
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

    async markNotificationAsRead(notificationId) {
        try {
            const response = await fetch('api/customer/mark_notification_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    notification_id: notificationId
                })
            });

            const data = await response.json();
            
            if (data.success) {
                // Update the notification in our local array
                const notification = this.notifications.find(n => n.id == notificationId);
                if (notification) {
                    notification.is_read = true;
                }
                
                // Update the display
                this.updateNotificationDisplay();
                this.updateNotificationCount();
                
                console.log('Notification marked as read successfully');
            } else {
                console.error('Failed to mark notification as read:', data.error);
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllNotificationsAsRead() {
        try {
            const response = await fetch('api/customer/mark_notifications_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            const data = await response.json();
            
            if (data.success) {
                // Update all notifications in our local array to be read
                this.notifications.forEach(notification => {
                    notification.is_read = true;
                });
                
                // Update the display and count immediately
                this.updateNotificationDisplay();
                this.updateNotificationCount();
                
                console.log('All notifications marked as read successfully');
            } else {
                console.error('Failed to mark all notifications as read:', data.error);
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }
}

// Global functions for navbar integration
function markNotificationsAsRead() {
    if (window.customerNotificationManager) {
        window.customerNotificationManager.markAllNotificationsAsRead();
    }
}

function markNotificationAsRead(notificationId) {
    if (window.customerNotificationManager) {
        window.customerNotificationManager.markNotificationAsRead(notificationId);
    }
}

function markAllNotificationsAsRead() {
    if (window.customerNotificationManager) {
        window.customerNotificationManager.markAllNotificationsAsRead();
    }
}

function showAllCustomerNotifications(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    if (window.customerNotificationManager) {
        window.customerNotificationManager.loadNotifications(true);
    }
}

function showRecentCustomerNotifications(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    if (window.customerNotificationManager) {
        window.customerNotificationManager.loadNotifications(false);
    }
}

// Initialize the customer notification manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize for customers
    const userType = document.body.getAttribute('data-user-type') || 
                    (window.userDetails && window.userDetails.user_type);
    
    if (userType === 'customer') {
        window.customerNotificationManager = new CustomerNotificationManager();
        console.log('Customer notification system initialized');
    }
});

// Request notification permission function
function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission().then(function(permission) {
            console.log('Notification permission:', permission);
        });
    }
}

// Request notification permission when page loads
document.addEventListener('DOMContentLoaded', function() {
    requestNotificationPermission();
});

// Clean up interval when page unloads
window.addEventListener('beforeunload', function() {
    if (notificationInterval) {
        clearInterval(notificationInterval);
    }
});
