/**
 * Dedicated Technician Notification System
 * Completely separate from admin/staff and customer notifications
 */

class TechnicianNotificationManager {
    constructor() {
        this.notificationCount = 0;
        this.notifications = [];
        this.refreshInterval = null;
        this.isLoading = false;
        this.showingAll = false; // Track if showing all notifications
        
        // Initialize the notification system
        this.init();
    }
    
    init() {
        // Load initial notifications
        this.loadNotifications();
        
        // Set up auto-refresh every 30 seconds
        this.refreshInterval = setInterval(() => {
            this.loadNotifications();
        }, 30000);
        
        // Clean up interval when page unloads
        window.addEventListener('beforeunload', () => {
            if (this.refreshInterval) {
                clearInterval(this.refreshInterval);
            }
        });
    }
    
    async loadNotifications(unlimited = false) {
        if (this.isLoading) return;
        
        this.isLoading = true;
        
        try {
            const url = unlimited ? 'api/technician/get_notifications.php?unlimited=true' : 'api/technician/get_notifications.php';
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.notifications = data.notifications || [];
                this.notificationCount = data.count || 0;
                this.showingAll = unlimited;
                this.updateNotificationUI();
            } else {
                console.error('Failed to load technician notifications:', data.error);
                this.showErrorState();
            }
            
        } catch (error) {
            console.error('Error loading technician notifications:', error);
            this.showErrorState();
        } finally {
            this.isLoading = false;
        }
    }
    
    updateNotificationUI() {
        this.updateNotificationCount();
        this.updateNotificationDropdown();
    }
    
    updateNotificationCount() {
        const countElement = document.getElementById('notification-count');
        if (countElement) {
            const unreadCount = this.notifications.filter(n => !n.is_read).length;
            
            if (unreadCount > 0) {
                countElement.textContent = unreadCount > 99 ? '99+' : unreadCount.toString();
                countElement.style.display = 'inline';
            } else {
                countElement.style.display = 'none';
            }
        }
    }
    
    updateNotificationDropdown() {
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
                const isUnread = !notification.is_read;
                const unreadClass = isUnread ? 'bg-light' : '';
                const unreadIndicator = isUnread ? '<span class="badge bg-primary rounded-pill flex-shrink-0">New</span>' : '';
                
                html += `
                    <div class="dropdown-item notification-item ${unreadClass}" 
                         data-notification-id="${notification.id}" style="white-space: normal; word-wrap: break-word; overflow-wrap: break-word; padding: 12px 16px; height: auto; min-height: auto;">
                        <div class="d-flex align-items-start">
                            <div class="me-3 flex-shrink-0">
                                <i class="bi ${notification.icon} fs-5 ${notification.color}"></i>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0; width: 100%;">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 fw-semibold" style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal; line-height: 1.3; flex: 1; margin-right: 8px;">${notification.title}</h6>
                                    ${unreadIndicator}
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
                    <button class="btn btn-outline-primary btn-sm" onclick="showAllTechnicianNotifications(event)" style="font-size: 0.875rem;">
                        <i class="bi bi-arrow-down-circle me-1"></i>
                        Show All Past Notifications
                    </button>
                </div>
            `;
        } else if (this.showingAll) {
            html += `
                <div class="dropdown-divider"></div>
                <div class="text-center" style="padding: 8px 16px; background: none;">
                    <button class="btn btn-outline-secondary btn-sm" onclick="showRecentTechnicianNotifications(event)" style="font-size: 0.875rem;">
                        <i class="bi bi-arrow-up-circle me-1"></i>
                        Show Recent Only
                    </button>
                </div>
            `;
        }

        container.innerHTML = html;
    }
    
    getAdditionalInfo(notification) {
        if (!notification.additional_data) return '';
        
        const data = notification.additional_data;
        let additionalInfo = '';
        
        // Show relevant additional information based on notification type
        switch (notification.type) {
            case 'appointment_accepted':
                if (data.customer_name && data.service_type) {
                    // Build partner info if available
                    let partnerInfo = '';
                    if (data.partner_technician && data.is_team_assignment) {
                        const partnerRole = data.technician_role === 'primary' ? 'Secondary' : 'Primary';
                        partnerInfo = `<br><i class="bi bi-people me-1"></i><strong>Partner:</strong> ${data.partner_technician} (${partnerRole})`;
                    }
                    
                    additionalInfo = `
                        <div class="mt-2 p-2 bg-success bg-opacity-10 rounded">
                            <small class="text-success">
                                <i class="bi bi-person me-1"></i><strong>Customer:</strong> ${data.customer_name}<br>
                                <i class="bi bi-tools me-1"></i><strong>Service:</strong> ${data.service_type}
                                ${data.technician_role ? `<br><i class="bi bi-person-badge me-1"></i><strong>Role:</strong> ${data.technician_role.charAt(0).toUpperCase() + data.technician_role.slice(1)} Technician` : ''}
                                ${partnerInfo}
                            </small>
                        </div>
                    `;
                }
                break;
            case 'technician_assigned':
                if (data.customer_name && data.service_type) {
                    additionalInfo = `
                        <div class="mt-2 p-2 bg-info bg-opacity-10 rounded">
                            <small class="text-info">
                                <i class="bi bi-person me-1"></i><strong>Customer:</strong> ${data.customer_name}<br>
                                <i class="bi bi-tools me-1"></i><strong>Service:</strong> ${data.service_type}
                                ${data.technician_role ? `<br><i class="bi bi-person-badge me-1"></i><strong>Role:</strong> ${data.technician_role.charAt(0).toUpperCase() + data.technician_role.slice(1)} Technician` : ''}
                            </small>
                        </div>
                    `;
                }
                break;
        }
        
        return additionalInfo;
    }
    
    showErrorState() {
        const container = document.getElementById('notifications-container');
        if (container) {
            container.innerHTML = `
                <div class="text-center p-3 text-muted">
                    <i class="bi bi-exclamation-triangle fs-4 text-warning"></i>
                    <p class="mb-0 mt-2">Unable to load notifications</p>
                    <button class="btn btn-sm btn-outline-primary mt-2" onclick="technicianNotificationManager.loadNotifications()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Retry
                    </button>
                </div>
            `;
        }
    }
    
    async markNotificationAsRead(notificationId) {
        try {
            const response = await fetch('api/technician/mark_notification_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    notification_id: notificationId
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    // Update local notification state
                    const notification = this.notifications.find(n => n.id === notificationId);
                    if (notification) {
                        notification.is_read = true;
                        this.updateNotificationUI();
                    }
                }
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }
    
    async markAllNotificationsAsRead() {
        try {
            const response = await fetch('api/technician/mark_notifications_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Reload notifications to reflect the updated read status
                await this.loadNotifications();
                
                // Show success message (if toast function is available)
                if (typeof showToast === 'function') {
                    showToast('All notifications marked as read', 'success');
                }
            } else {
                throw new Error(data.error || 'Failed to mark notifications as read');
            }
            
        } catch (error) {
            console.error('Error marking notifications as read:', error);
            if (typeof showToast === 'function') {
                showToast('Failed to mark notifications as read', 'danger');
            }
        }
    }
    
    async clearAllNotifications() {
        if (this.notifications.length === 0) return;
        
        try {
            // Mark all notifications as read
            const unreadNotifications = this.notifications.filter(n => !n.is_read);
            
            for (const notification of unreadNotifications) {
                await this.markNotificationAsRead(notification.id);
            }
            
            // Reload notifications to reflect changes
            await this.loadNotifications();
            
        } catch (error) {
            console.error('Error clearing all notifications:', error);
        }
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
}

function markNotificationsAsRead() {
    // This function is called when the notification dropdown is opened
    // We'll mark visible notifications as read after a short delay
    setTimeout(() => {
        if (window.technicianNotificationManager) {
            const unreadNotifications = window.technicianNotificationManager.notifications.filter(n => !n.is_read);
            unreadNotifications.slice(0, 5).forEach(notification => {
                window.technicianNotificationManager.markNotificationAsRead(notification.id);
            });
        }
    }, 1000);
}

function markAllNotificationsAsRead() {
    if (window.technicianNotificationManager) {
        window.technicianNotificationManager.markAllNotificationsAsRead();
    }
}

function clearAllNotifications() {
    if (window.technicianNotificationManager) {
        window.technicianNotificationManager.clearAllNotifications();
    }
}

function showAllTechnicianNotifications(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    if (window.technicianNotificationManager) {
        window.technicianNotificationManager.loadNotifications(true);
    }
}

function showRecentTechnicianNotifications(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    if (window.technicianNotificationManager) {
        window.technicianNotificationManager.loadNotifications(false);
    }
}

// Initialize the technician notification manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize for technicians
    const userType = document.body.getAttribute('data-user-type') || 
                    (window.userDetails && window.userDetails.user_type);
    
    if (userType === 'technician') {
        window.technicianNotificationManager = new TechnicianNotificationManager();
        console.log('Technician notification system initialized');
    }
});
