<?php
class NotificationHandler {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Create a notification for appointment creation
     */
    public function createAppointmentNotification($appointmentData, $customerData, $serviceData, $technicianData) {
        try {
            // Format the schedule date for display
            $scheduleDate = new DateTime($appointmentData['app_schedule']);
            $formattedDate = $scheduleDate->format('F j, Y \a\t g:i A');
            
            // Create notification description
            $description = sprintf(
                'Customer %s %s %s created a new appointment - Service: %s, Date: %s',
                $customerData['user_name'],
                $customerData['user_midname'] ?? '',
                $customerData['user_lastname'],
                $serviceData['service_type_name'],
                $formattedDate
            );
            
            // Prepare additional data as JSON
            $additionalData = json_encode([
                'service_type' => $serviceData['service_type_name'],
                'schedule' => $appointmentData['app_schedule'],
                'customer_id' => $appointmentData['user_id'],
                'technician_name' => trim($technicianData['user_name'] . ' ' . ($technicianData['user_midname'] ?? '') . ' ' . $technicianData['user_lastname']),
                'appointment_id' => $appointmentData['app_id']
            ]);
            
            // Insert notification for administrators
            $stmt = $this->pdo->prepare("
                INSERT INTO notification (
                    event_type,
                    event_description,
                    target_user_id,
                    actor_user_id,
                    related_appointment_id,
                    additional_data,
                    is_system_notification,
                    priority,
                    created_at
                ) VALUES (
                    'appointment_created',
                    :description,
                    NULL,
                    :actor_user_id,
                    :appointment_id,
                    :additional_data,
                    0,
                    'medium',
                    NOW()
                )
            ");
            
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':actor_user_id', $appointmentData['user_id']);
            $stmt->bindParam(':appointment_id', $appointmentData['app_id']);
            $stmt->bindParam(':additional_data', $additionalData);
            
            $result = $stmt->execute();
            
            if ($result) {
                return [
                    'success' => true,
                    'notification_id' => $this->pdo->lastInsertId(),
                    'message' => 'Notification created successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to create notification'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating notification: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create a notification for appointment status changes
     */
    public function createStatusChangeNotification($appointmentId, $oldStatus, $newStatus, $actorUserId, $additionalInfo = []) {
        try {
            // Get appointment details
            $stmt = $this->pdo->prepare("
                SELECT 
                    a.*,
                    CONCAT(u.user_name, ' ', COALESCE(u.user_midname, ''), ' ', u.user_lastname) as customer_name,
                    COALESCE(st.service_type_name, 'Unknown Service') as service_type_name
                FROM appointment a
                JOIN user u ON a.user_id = u.user_id
                LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
                WHERE a.app_id = ?
            ");
            $stmt->execute([$appointmentId]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$appointment) {
                return ['success' => false, 'message' => 'Appointment not found'];
            }
            
            // Create notification description
            $description = sprintf(
                'Appointment #%d for %s (%s) status changed from %s to %s',
                $appointmentId,
                $appointment['customer_name'],
                $appointment['service_type_name'],
                $oldStatus,
                $newStatus
            );
            
            // Prepare additional data
            $additionalData = json_encode(array_merge([
                'appointment_id' => $appointmentId,
                'customer_name' => $appointment['customer_name'],
                'service_type' => $appointment['service_type_name'],
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ], $additionalInfo));
            
            // Insert notification
            $stmt = $this->pdo->prepare("
                INSERT INTO notification (
                    event_type,
                    event_description,
                    target_user_id,
                    actor_user_id,
                    related_appointment_id,
                    old_status,
                    new_status,
                    additional_data,
                    is_system_notification,
                    priority,
                    created_at
                ) VALUES (
                    'appointment_status_changed',
                    ?,
                    NULL,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    0,
                    'medium',
                    NOW()
                )
            ");
            
            $result = $stmt->execute([
                $description,
                $actorUserId,
                $appointmentId,
                $oldStatus,
                $newStatus,
                $additionalData
            ]);
            
            return [
                'success' => $result,
                'notification_id' => $result ? $this->pdo->lastInsertId() : null,
                'message' => $result ? 'Status change notification created' : 'Failed to create notification'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating status change notification: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create a notification for user management actions (add, edit, delete)
     */
    public function createUserManagementNotification($action, $targetUserId, $actorUserId, $userData = [], $additionalInfo = []) {
        try {
            // Get target user details (for edit/delete actions)
            $targetUser = null;
            if ($action !== 'add') {
                $stmt = $this->pdo->prepare("
                    SELECT 
                        user_id,
                        CONCAT(user_name, ' ', COALESCE(user_midname, ''), ' ', user_lastname) as full_name,
                        user_email,
                        user_type_id
                    FROM user 
                    WHERE user_id = ?
                ");
                $stmt->execute([$targetUserId]);
                $targetUser = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            
            // Get user type name
            $userTypeName = 'Unknown';
            $userTypeId = $userData['user_type_id'] ?? ($targetUser['user_type_id'] ?? null);
            
            if ($userTypeId) {
                $typeStmt = $this->pdo->prepare("SELECT user_type_name FROM user_type WHERE user_type_id = ?");
                $typeStmt->execute([$userTypeId]);
                $userType = $typeStmt->fetch(PDO::FETCH_ASSOC);
                if ($userType) {
                    $userTypeName = $userType['user_type_name'];
                }
            }
            
            // Create notification description based on action
            $description = '';
            $eventType = 'user_management';
            
            switch ($action) {
                case 'add':
                    $fullName = trim(($userData['first_name'] ?? '') . ' ' . ($userData['middle_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''));
                    $description = sprintf(
                        'New %s user created: %s (%s)',
                        $userTypeName,
                        $fullName,
                        $userData['email'] ?? 'No email'
                    );
                    $eventType = 'user_created';
                    break;
                    
                case 'edit':
                    if ($targetUser) {
                        $description = sprintf(
                            'User information updated: %s (%s) - %s',
                            $targetUser['full_name'],
                            $targetUser['user_email'],
                            $userTypeName
                        );
                    } else {
                        $fullName = trim(($userData['first_name'] ?? '') . ' ' . ($userData['middle_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''));
                        $description = sprintf(
                            'User information updated: %s (%s) - %s',
                            $fullName,
                            $userData['email'] ?? 'No email',
                            $userTypeName
                        );
                    }
                    $eventType = 'user_updated';
                    break;
                    
                case 'delete':
                    if ($targetUser) {
                        $description = sprintf(
                            'User deleted: %s (%s) - %s',
                            $targetUser['full_name'],
                            $targetUser['user_email'],
                            $userTypeName
                        );
                    } else {
                        $description = sprintf('User ID %d deleted', $targetUserId);
                    }
                    $eventType = 'user_deleted';
                    break;
                    
                default:
                    $description = sprintf('User management action: %s for user ID %d', $action, $targetUserId);
                    break;
            }
            
            // Prepare additional data
            $additionalData = json_encode(array_merge([
                'action' => $action,
                'target_user_id' => $targetUserId,
                'user_type' => $userTypeName,
                'target_user_data' => $targetUser,
                'updated_data' => $userData
            ], $additionalInfo));
            
            // Insert notification
            $stmt = $this->pdo->prepare("
                INSERT INTO notification (
                    event_type,
                    event_description,
                    target_user_id,
                    actor_user_id,
                    additional_data,
                    is_system_notification,
                    priority,
                    created_at
                ) VALUES (
                    ?,
                    ?,
                    NULL,
                    ?,
                    ?,
                    0,
                    'medium',
                    NOW()
                )
            ");
            
            $result = $stmt->execute([
                $eventType,
                $description,
                $actorUserId,
                $additionalData
            ]);
            
            return [
                'success' => $result,
                'notification_id' => $result ? $this->pdo->lastInsertId() : null,
                'message' => $result ? 'User management notification created' : 'Failed to create notification'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating user management notification: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get notifications for administrators
     */
    public function getAdminNotifications($limit = 15) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    n.*,
                    CONCAT(actor.user_name, ' ', COALESCE(actor.user_midname, ''), ' ', actor.user_lastname) as actor_name
                FROM notification n
                LEFT JOIN user actor ON n.actor_user_id = actor.user_id
                WHERE n.target_user_id IS NULL OR n.target_user_id IN (
                    SELECT user_id FROM user WHERE user_type_id IN (1, 3)
                )
                ORDER BY n.created_at DESC
                LIMIT :limit
            ");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return [
                'success' => true,
                'notifications' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error fetching notifications: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE notification 
                SET is_read = 1, read_at = NOW() 
                WHERE notification_id = :notification_id
            ");
            $stmt->bindParam(':notification_id', $notificationId);
            $result = $stmt->execute();
            
            return [
                'success' => $result,
                'message' => $result ? 'Notification marked as read' : 'Failed to mark notification as read'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error marking notification as read: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create a notification for appointment rebooking
     */
    public function createRebookNotification($appointmentData, $customerData, $serviceData, $technicianData, $originalAppointmentId) {
        try {
            // Format the schedule date for display
            $scheduleDate = new DateTime($appointmentData['app_schedule']);
            $formattedDate = $scheduleDate->format('F j, Y \a\t g:i A');
            
            // Create notification description
            $description = sprintf(
                'Customer %s %s %s rebooked an appointment - Service: %s, Date: %s',
                $customerData['user_name'],
                $customerData['user_midname'] ?? '',
                $customerData['user_lastname'],
                $serviceData['service_type_name'],
                $formattedDate
            );
            
            // Prepare additional data as JSON
            $additionalData = json_encode([
                'service_type' => $serviceData['service_type_name'],
                'schedule' => $appointmentData['app_schedule'],
                'customer_id' => $appointmentData['user_id'],
                'technician_name' => trim($technicianData['user_name'] . ' ' . ($technicianData['user_midname'] ?? '') . ' ' . $technicianData['user_lastname']),
                'appointment_id' => $appointmentData['app_id'],
                'original_appointment_id' => $originalAppointmentId
            ]);
            
            // Insert notification for administrators
            $stmt = $this->pdo->prepare("
                INSERT INTO notification (
                    event_type,
                    event_description,
                    target_user_id,
                    actor_user_id,
                    related_appointment_id,
                    additional_data,
                    is_system_notification,
                    priority,
                    created_at
                ) VALUES (
                    'appointment_rebooked',
                    :description,
                    NULL,
                    :actor_user_id,
                    :appointment_id,
                    :additional_data,
                    0,
                    'medium',
                    NOW()
                )
            ");
            
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':actor_user_id', $appointmentData['user_id']);
            $stmt->bindParam(':appointment_id', $appointmentData['app_id']);
            $stmt->bindParam(':additional_data', $additionalData);
            
            $result = $stmt->execute();
            
            if ($result) {
                return [
                    'success' => true,
                    'notification_id' => $this->pdo->lastInsertId(),
                    'message' => 'Rebook notification created successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to create rebook notification'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating rebook notification: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create a notification specifically for a customer when their appointment status changes
     */
    public function createCustomerStatusNotification($appointmentId, $oldStatus, $newStatus, $actorUserId, $additionalInfo = []) {
        try {
            // Get appointment details
            $stmt = $this->pdo->prepare("
                SELECT 
                    a.*,
                    CONCAT(u.user_name, ' ', COALESCE(u.user_midname, ''), ' ', u.user_lastname) as customer_name,
                    COALESCE(st.service_type_name, 'Unknown Service') as service_type_name
                FROM appointment a
                JOIN user u ON a.user_id = u.user_id
                LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
                WHERE a.app_id = ?
            ");
            $stmt->execute([$appointmentId]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$appointment) {
                return ['success' => false, 'message' => 'Appointment not found'];
            }
            
            // Create customer-friendly notification description
            $description = sprintf(
                'Your %s appointment status has been updated to %s',
                $appointment['service_type_name'],
                $newStatus
            );
            
            // Add decline justification if available
            if ($newStatus === 'Declined' && isset($additionalInfo['justification']) && !empty($additionalInfo['justification'])) {
                $description .= '. Reason: ' . $additionalInfo['justification'];
            }
            
            // Prepare additional data for customer
            $additionalData = json_encode(array_merge([
                'appointment_id' => $appointmentId,
                'service_type' => $appointment['service_type_name'],
                'new_status' => $newStatus,
                'appointment_schedule' => $appointment['app_schedule'],
                'customer_facing' => true
            ], $additionalInfo));
            
            // Insert customer notification
            $stmt = $this->pdo->prepare("
                INSERT INTO notification (
                    event_type,
                    event_description,
                    target_user_id,
                    actor_user_id,
                    related_appointment_id,
                    old_status,
                    new_status,
                    additional_data,
                    is_system_notification,
                    priority,
                    created_at
                ) VALUES (
                    'appointment_status_changed',
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    0,
                    'high',
                    NOW()
                )
            ");
            
            $result = $stmt->execute([
                $description,
                $appointment['user_id'], // Target the customer
                $actorUserId,
                $appointmentId,
                $oldStatus,
                $newStatus,
                $additionalData
            ]);
            
            return [
                'success' => $result,
                'notification_id' => $result ? $this->pdo->lastInsertId() : null,
                'message' => $result ? 'Customer notification created successfully' : 'Failed to create customer notification'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating customer notification: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create a notification for a customer when their payment status changes
     */
    public function createCustomerPaymentNotification($appointmentId, $oldPaymentStatus, $newPaymentStatus, $actorUserId = null, $additionalInfo = []) {
        try {
            // Get appointment details
            $stmt = $this->pdo->prepare("
                SELECT 
                    a.*,
                    CONCAT(u.user_name, ' ', COALESCE(u.user_midname, ''), ' ', u.user_lastname) as customer_name,
                    COALESCE(st.service_type_name, 'Unknown Service') as service_type_name
                FROM appointment a
                JOIN user u ON a.user_id = u.user_id
                LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
                WHERE a.app_id = ?
            ");
            $stmt->execute([$appointmentId]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$appointment) {
                return ['success' => false, 'message' => 'Appointment not found'];
            }
            
            // Create customer-friendly payment notification description
            $description = '';
            $eventType = 'payment_status_changed';
            $priority = 'medium';
            
            if ($newPaymentStatus === 'Paid') {
                $description = sprintf(
                    'Payment received for your %s appointment. Amount: ₱%s',
                    $appointment['service_type_name'],
                    number_format($appointment['app_price'], 2)
                );
                $priority = 'high';
            } elseif ($newPaymentStatus === 'Overdue') {
                $description = sprintf(
                    'Payment overdue for your %s appointment. Amount: ₱%s. Please make payment as soon as possible.',
                    $appointment['service_type_name'],
                    number_format($appointment['app_price'], 2)
                );
                $eventType = 'invoice_overdue';
                $priority = 'high';
            } else {
                $description = sprintf(
                    'Payment status updated for your %s appointment: %s',
                    $appointment['service_type_name'],
                    $newPaymentStatus
                );
            }
            
            // Prepare additional data for customer
            $additionalData = json_encode(array_merge([
                'appointment_id' => $appointmentId,
                'service_type' => $appointment['service_type_name'],
                'payment_amount' => $appointment['app_price'],
                'old_payment_status' => $oldPaymentStatus,
                'new_payment_status' => $newPaymentStatus,
                'appointment_schedule' => $appointment['app_schedule'],
                'customer_facing' => true
            ], $additionalInfo));
            
            // Insert customer payment notification
            $stmt = $this->pdo->prepare("
                INSERT INTO notification (
                    event_type,
                    event_description,
                    target_user_id,
                    actor_user_id,
                    related_appointment_id,
                    old_status,
                    new_status,
                    additional_data,
                    is_system_notification,
                    priority,
                    created_at
                ) VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    0,
                    ?,
                    NOW()
                )
            ");
            
            $result = $stmt->execute([
                $eventType,
                $description,
                $appointment['user_id'], // Target the customer
                $actorUserId,
                $appointmentId,
                $oldPaymentStatus,
                $newPaymentStatus,
                $additionalData,
                $priority
            ]);
            
            return [
                'success' => $result,
                'notification_id' => $result ? $this->pdo->lastInsertId() : null,
                'message' => $result ? 'Customer payment notification created successfully' : 'Failed to create customer payment notification'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating customer payment notification: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create notifications for technicians when they are assigned to an appointment
     */
    public function createTechnicianAssignmentNotification($appointmentId, $primaryTechnicianId, $secondaryTechnicianId = null, $actorUserId = null, $additionalInfo = []) {
        try {
            // Get appointment details
            $stmt = $this->pdo->prepare("
                SELECT 
                    a.*,
                    CONCAT(customer.user_name, ' ', COALESCE(customer.user_midname, ''), ' ', customer.user_lastname) as customer_name,
                    COALESCE(st.service_type_name, 'Unknown Service') as service_type_name,
                    COALESCE(at.appliances_type_name, 'Unknown Appliance') as appliance_name
                FROM appointment a
                JOIN user customer ON a.user_id = customer.user_id
                LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
                LEFT JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
                WHERE a.app_id = ?
            ");
            $stmt->execute([$appointmentId]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$appointment) {
                return ['success' => false, 'message' => 'Appointment not found'];
            }
            
            $results = [];
            $appointmentDate = new DateTime($appointment['app_schedule']);
            
            // Create notification for primary technician
            if ($primaryTechnicianId) {
                $description = sprintf(
                    'You have been assigned as Primary Technician for %s service on %s for customer %s',
                    $appointment['service_type_name'],
                    $appointmentDate->format('M j, Y g:i A'),
                    $appointment['customer_name']
                );
                
                $additionalData = json_encode(array_merge([
                    'appointment_id' => $appointmentId,
                    'customer_name' => $appointment['customer_name'],
                    'service_type' => $appointment['service_type_name'],
                    'appliance_type' => $appointment['appliance_name'],
                    'appointment_schedule' => $appointment['app_schedule'],
                    'technician_role' => 'primary',
                    'technician_facing' => true
                ], $additionalInfo));
                
                $stmt = $this->pdo->prepare("
                    INSERT INTO notification (
                        event_type,
                        event_description,
                        target_user_id,
                        actor_user_id,
                        related_appointment_id,
                        additional_data,
                        is_system_notification,
                        priority,
                        created_at
                    ) VALUES (
                        'technician_assigned',
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        0,
                        'high',
                        NOW()
                    )
                ");
                
                $primaryResult = $stmt->execute([
                    $description,
                    $primaryTechnicianId,
                    $actorUserId,
                    $appointmentId,
                    $additionalData
                ]);
                
                $results['primary'] = [
                    'success' => $primaryResult,
                    'notification_id' => $primaryResult ? $this->pdo->lastInsertId() : null
                ];
            }
            
            // Create notification for secondary technician if assigned
            if ($secondaryTechnicianId) {
                $description = sprintf(
                    'You have been assigned as Secondary Technician for %s service on %s for customer %s',
                    $appointment['service_type_name'],
                    $appointmentDate->format('M j, Y g:i A'),
                    $appointment['customer_name']
                );
                
                $additionalData = json_encode(array_merge([
                    'appointment_id' => $appointmentId,
                    'customer_name' => $appointment['customer_name'],
                    'service_type' => $appointment['service_type_name'],
                    'appliance_type' => $appointment['appliance_name'],
                    'appointment_schedule' => $appointment['app_schedule'],
                    'technician_role' => 'secondary',
                    'technician_facing' => true
                ], $additionalInfo));
                
                $stmt = $this->pdo->prepare("
                    INSERT INTO notification (
                        event_type,
                        event_description,
                        target_user_id,
                        actor_user_id,
                        related_appointment_id,
                        additional_data,
                        is_system_notification,
                        priority,
                        created_at
                    ) VALUES (
                        'technician_assigned',
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        0,
                        'high',
                        NOW()
                    )
                ");
                
                $secondaryResult = $stmt->execute([
                    $description,
                    $secondaryTechnicianId,
                    $actorUserId,
                    $appointmentId,
                    $additionalData
                ]);
                
                $results['secondary'] = [
                    'success' => $secondaryResult,
                    'notification_id' => $secondaryResult ? $this->pdo->lastInsertId() : null
                ];
            }
            
            $overallSuccess = isset($results['primary']) ? $results['primary']['success'] : true;
            if (isset($results['secondary'])) {
                $overallSuccess = $overallSuccess && $results['secondary']['success'];
            }
            
            return [
                'success' => $overallSuccess,
                'results' => $results,
                'message' => $overallSuccess ? 'Technician assignment notifications created successfully' : 'Some technician notifications failed to create'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating technician assignment notifications: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create notifications for technicians when their appointment is accepted
     */
    public function createTechnicianAcceptanceNotification($appointmentId, $actorUserId, $additionalInfo = []) {
        try {
            // Get appointment details including technician assignments and partner names
            $stmt = $this->pdo->prepare("
                SELECT 
                    a.*,
                    CONCAT(u.user_name, ' ', COALESCE(u.user_midname, ''), ' ', u.user_lastname) as customer_name,
                    COALESCE(st.service_type_name, 'Unknown Service') as service_type_name,
                    COALESCE(at.appliances_type_name, 'Not Specified') as appliance_name,
                    CONCAT(t1.user_name, ' ', COALESCE(t1.user_midname, ''), ' ', t1.user_lastname) as primary_technician_name,
                    CONCAT(t2.user_name, ' ', COALESCE(t2.user_midname, ''), ' ', t2.user_lastname) as secondary_technician_name
                FROM appointment a
                JOIN user u ON a.user_id = u.user_id
                LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
                LEFT JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
                LEFT JOIN user t1 ON a.user_technician = t1.user_id
                LEFT JOIN user t2 ON a.user_technician_2 = t2.user_id
                WHERE a.app_id = ?
            ");
            $stmt->execute([$appointmentId]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$appointment) {
                return ['success' => false, 'message' => 'Appointment not found'];
            }
            
            $results = [];
            $appointmentDate = new DateTime($appointment['app_schedule']);
            
            // Create notification for primary technician
            if ($appointment['user_technician']) {
                // Build description with partner info if secondary technician exists
                $partnerInfo = '';
                if ($appointment['user_technician_2'] && $appointment['secondary_technician_name']) {
                    $partnerInfo = sprintf(' You are paired with %s (Secondary Technician).', trim($appointment['secondary_technician_name']));
                }
                
                $description = sprintf(
                    'Your appointment for %s service on %s has been ACCEPTED by admin/staff. You can now start working on this task.%s',
                    $appointment['service_type_name'],
                    $appointmentDate->format('M j, Y g:i A'),
                    $partnerInfo
                );
                
                $additionalData = json_encode(array_merge([
                    'appointment_id' => $appointmentId,
                    'customer_name' => $appointment['customer_name'],
                    'service_type' => $appointment['service_type_name'],
                    'appliance_type' => $appointment['appliance_name'],
                    'appointment_schedule' => $appointment['app_schedule'],
                    'technician_role' => 'primary',
                    'technician_facing' => true,
                    'action_type' => 'accepted',
                    'partner_technician' => $appointment['user_technician_2'] ? trim($appointment['secondary_technician_name']) : null,
                    'is_team_assignment' => !empty($appointment['user_technician_2'])
                ], $additionalInfo));
                
                $stmt = $this->pdo->prepare("
                    INSERT INTO notification (
                        event_type,
                        event_description,
                        target_user_id,
                        actor_user_id,
                        related_appointment_id,
                        additional_data,
                        is_system_notification,
                        priority,
                        created_at
                    ) VALUES (
                        'appointment_accepted',
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        0,
                        'high',
                        NOW()
                    )
                ");
                
                $primaryResult = $stmt->execute([
                    $description,
                    $appointment['user_technician'],
                    $actorUserId,
                    $appointmentId,
                    $additionalData
                ]);
                
                $results['primary'] = [
                    'success' => $primaryResult,
                    'notification_id' => $primaryResult ? $this->pdo->lastInsertId() : null
                ];
            }
            
            // Create notification for secondary technician if assigned
            if ($appointment['user_technician_2']) {
                // Build description with partner info
                $partnerInfo = '';
                if ($appointment['user_technician'] && $appointment['primary_technician_name']) {
                    $partnerInfo = sprintf(' You are paired with %s (Primary Technician).', trim($appointment['primary_technician_name']));
                }
                
                $description = sprintf(
                    'Your appointment for %s service on %s has been ACCEPTED by admin/staff. You can now start working on this task as secondary technician.%s',
                    $appointment['service_type_name'],
                    $appointmentDate->format('M j, Y g:i A'),
                    $partnerInfo
                );
                
                $additionalData = json_encode(array_merge([
                    'appointment_id' => $appointmentId,
                    'customer_name' => $appointment['customer_name'],
                    'service_type' => $appointment['service_type_name'],
                    'appliance_type' => $appointment['appliance_name'],
                    'appointment_schedule' => $appointment['app_schedule'],
                    'technician_role' => 'secondary',
                    'technician_facing' => true,
                    'action_type' => 'accepted',
                    'partner_technician' => $appointment['user_technician'] ? trim($appointment['primary_technician_name']) : null,
                    'is_team_assignment' => !empty($appointment['user_technician'])
                ], $additionalInfo));
                
                $stmt = $this->pdo->prepare("
                    INSERT INTO notification (
                        event_type,
                        event_description,
                        target_user_id,
                        actor_user_id,
                        related_appointment_id,
                        additional_data,
                        is_system_notification,
                        priority,
                        created_at
                    ) VALUES (
                        'appointment_accepted',
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        0,
                        'high',
                        NOW()
                    )
                ");
                
                $secondaryResult = $stmt->execute([
                    $description,
                    $appointment['user_technician_2'],
                    $actorUserId,
                    $appointmentId,
                    $additionalData
                ]);
                
                $results['secondary'] = [
                    'success' => $secondaryResult,
                    'notification_id' => $secondaryResult ? $this->pdo->lastInsertId() : null
                ];
            }
            
            $overallSuccess = isset($results['primary']) ? $results['primary']['success'] : true;
            if (isset($results['secondary'])) {
                $overallSuccess = $overallSuccess && $results['secondary']['success'];
            }
            
            return [
                'success' => $overallSuccess,
                'results' => $results,
                'message' => $overallSuccess ? 'Technician acceptance notifications created successfully' : 'Some technician notifications failed to create'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating technician acceptance notifications: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get notifications for a specific technician
     */
    public function getTechnicianNotifications($technicianId, $limit = 10) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    n.*,
                    CONCAT(actor.user_name, ' ', COALESCE(actor.user_midname, ''), ' ', actor.user_lastname) as actor_name
                FROM notification n
                LEFT JOIN user actor ON n.actor_user_id = actor.user_id
                WHERE n.target_user_id = :technician_id
                ORDER BY n.created_at DESC
                LIMIT :limit
            ");
            
            $stmt->bindParam(':technician_id', $technicianId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'notifications' => $notifications
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error fetching technician notifications: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get notifications for a specific customer
     */
    public function getCustomerNotifications($customerId, $limit = 10) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    n.*,
                    CONCAT(actor.user_name, ' ', COALESCE(actor.user_midname, ''), ' ', actor.user_lastname) as actor_name
                FROM notification n
                LEFT JOIN user actor ON n.actor_user_id = actor.user_id
                WHERE n.target_user_id = :customer_id
                ORDER BY n.created_at DESC
                LIMIT :limit
            ");
            
            $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'notifications' => $notifications
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error fetching customer notifications: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Mark all admin notifications as read
     */
    public function markAdminNotificationsAsRead($adminUserId) {
        try {
            // Update all unread notifications for admin/staff users
            // Admin notifications are those where target_user_id is NULL (system-wide) or for admin/staff users
            $stmt = $this->pdo->prepare("
                UPDATE notification 
                SET is_read = 1, read_at = NOW() 
                WHERE (target_user_id IS NULL OR target_user_id IN (
                    SELECT user_id FROM user WHERE user_type_id IN (1, 3)
                )) 
                AND is_read = 0
            ");
            
            $result = $stmt->execute();
            
            if ($result) {
                $affectedRows = $stmt->rowCount();
                return [
                    'success' => true,
                    'message' => "Marked {$affectedRows} notifications as read",
                    'affected_rows' => $affectedRows
                ];
            } else {
                throw new Exception('Failed to update notifications');
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error marking admin notifications as read: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Mark all customer notifications as read
     */
    public function markCustomerNotificationsAsRead($customerId) {
        try {
            // Update all unread notifications for this specific customer
            $stmt = $this->pdo->prepare("
                UPDATE notification 
                SET is_read = 1, read_at = NOW() 
                WHERE target_user_id = :customer_id 
                AND is_read = 0
            ");
            
            $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
            $result = $stmt->execute();
            
            if ($result) {
                $affectedRows = $stmt->rowCount();
                return [
                    'success' => true,
                    'message' => "Marked {$affectedRows} notifications as read",
                    'affected_rows' => $affectedRows
                ];
            } else {
                throw new Exception('Failed to update notifications');
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error marking customer notifications as read: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Mark all technician notifications as read
     */
    public function markTechnicianNotificationsAsRead($technicianId) {
        try {
            // Update all unread notifications for this specific technician
            $stmt = $this->pdo->prepare("
                UPDATE notification 
                SET is_read = 1, read_at = NOW() 
                WHERE target_user_id = :technician_id 
                AND is_read = 0
            ");
            
            $stmt->bindParam(':technician_id', $technicianId, PDO::PARAM_INT);
            $result = $stmt->execute();
            
            if ($result) {
                $affectedRows = $stmt->rowCount();
                return [
                    'success' => true,
                    'message' => "Marked {$affectedRows} notifications as read",
                    'affected_rows' => $affectedRows
                ];
            } else {
                throw new Exception('Failed to update notifications');
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error marking technician notifications as read: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create invoice notifications for customers about pending invoices
     */
    public function createCustomerInvoiceNotification($appointmentId, $invoiceStatus, $additionalInfo = []) {
        try {
            // Get appointment and customer details
            $stmt = $this->pdo->prepare("
                SELECT 
                    a.*,
                    CONCAT(u.user_name, ' ', COALESCE(u.user_midname, ''), ' ', u.user_lastname) as customer_name,
                    COALESCE(st.service_type_name, 'Unknown Service') as service_type_name,
                    u.user_email as customer_email
                FROM appointment a
                JOIN user u ON a.user_id = u.user_id
                LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
                WHERE a.app_id = ? AND a.app_status_id = 3 AND a.app_price > 0
            ");
            $stmt->execute([$appointmentId]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$appointment) {
                return ['success' => false, 'message' => 'Completed appointment with price not found'];
            }
            
            // Create notification description based on invoice status
            $description = '';
            $eventType = 'invoice_notification';
            $priority = 'medium';
            
            if ($invoiceStatus === 'Pending') {
                $description = sprintf(
                    'Invoice pending for your %s service. Amount: ₱%s. Please make payment at your earliest convenience.',
                    $appointment['service_type_name'],
                    number_format($appointment['app_price'], 2)
                );
                $eventType = 'invoice_pending';
                $priority = 'medium';
            } elseif ($invoiceStatus === 'Overdue') {
                $description = sprintf(
                    'Invoice overdue for your %s service. Amount: ₱%s. Please make payment immediately to avoid service disruption.',
                    $appointment['service_type_name'],
                    number_format($appointment['app_price'], 2)
                );
                $eventType = 'invoice_overdue';
                $priority = 'high';
            }
            
            // Prepare additional data
            $additionalData = json_encode(array_merge([
                'appointment_id' => $appointmentId,
                'service_type' => $appointment['service_type_name'],
                'invoice_amount' => $appointment['app_price'],
                'invoice_status' => $invoiceStatus,
                'completion_date' => $appointment['app_created'],
                'customer_facing' => true
            ], $additionalInfo));
            
            // Insert customer invoice notification
            $stmt = $this->pdo->prepare("
                INSERT INTO notification (
                    event_type,
                    event_description,
                    target_user_id,
                    actor_user_id,
                    related_appointment_id,
                    additional_data,
                    is_system_notification,
                    priority,
                    created_at
                ) VALUES (
                    ?,
                    ?,
                    ?,
                    NULL,
                    ?,
                    ?,
                    1,
                    ?,
                    NOW()
                )
            ");
            
            $result = $stmt->execute([
                $eventType,
                $description,
                $appointment['user_id'], // Target the customer
                $appointmentId,
                $additionalData,
                $priority
            ]);
            
            return [
                'success' => $result,
                'notification_id' => $result ? $this->pdo->lastInsertId() : null,
                'message' => $result ? 'Customer invoice notification created successfully' : 'Failed to create customer invoice notification'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating customer invoice notification: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create invoice notifications for administrators about pending/overdue invoices
     */
    public function createAdminInvoiceNotification($appointmentId, $invoiceStatus, $additionalInfo = []) {
        try {
            // Get appointment and customer details
            $stmt = $this->pdo->prepare("
                SELECT 
                    a.*,
                    CONCAT(u.user_name, ' ', COALESCE(u.user_midname, ''), ' ', u.user_lastname) as customer_name,
                    COALESCE(st.service_type_name, 'Unknown Service') as service_type_name,
                    u.user_email as customer_email
                FROM appointment a
                JOIN user u ON a.user_id = u.user_id
                LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
                WHERE a.app_id = ? AND a.app_status_id = 3 AND a.app_price > 0
            ");
            $stmt->execute([$appointmentId]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$appointment) {
                return ['success' => false, 'message' => 'Completed appointment with price not found'];
            }
            
            // Create notification description based on invoice status
            $description = '';
            $eventType = 'admin_invoice_notification';
            $priority = 'medium';
            
            if ($invoiceStatus === 'Pending') {
                $description = sprintf(
                    'Invoice pending for customer %s - %s service. Amount: ₱%s',
                    $appointment['customer_name'],
                    $appointment['service_type_name'],
                    number_format($appointment['app_price'], 2)
                );
                $eventType = 'admin_invoice_pending';
                $priority = 'medium';
            } elseif ($invoiceStatus === 'Overdue') {
                $description = sprintf(
                    'Invoice overdue for customer %s - %s service. Amount: ₱%s. Follow up required.',
                    $appointment['customer_name'],
                    $appointment['service_type_name'],
                    number_format($appointment['app_price'], 2)
                );
                $eventType = 'admin_invoice_overdue';
                $priority = 'high';
            }
            
            // Prepare additional data
            $additionalData = json_encode(array_merge([
                'appointment_id' => $appointmentId,
                'customer_name' => $appointment['customer_name'],
                'customer_id' => $appointment['user_id'],
                'service_type' => $appointment['service_type_name'],
                'invoice_amount' => $appointment['app_price'],
                'invoice_status' => $invoiceStatus,
                'completion_date' => $appointment['app_created'],
                'admin_facing' => true
            ], $additionalInfo));
            
            // Insert admin invoice notification
            $stmt = $this->pdo->prepare("
                INSERT INTO notification (
                    event_type,
                    event_description,
                    target_user_id,
                    actor_user_id,
                    related_appointment_id,
                    additional_data,
                    is_system_notification,
                    priority,
                    created_at
                ) VALUES (
                    ?,
                    ?,
                    NULL,
                    NULL,
                    ?,
                    ?,
                    1,
                    ?,
                    NOW()
                )
            ");
            
            $result = $stmt->execute([
                $eventType,
                $description,
                $appointmentId,
                $additionalData,
                $priority
            ]);
            
            return [
                'success' => $result,
                'notification_id' => $result ? $this->pdo->lastInsertId() : null,
                'message' => $result ? 'Admin invoice notification created successfully' : 'Failed to create admin invoice notification'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating admin invoice notification: ' . $e->getMessage()
            ];
        }
    }
}
?>
