<?php
header('Content-Type: application/json');
include '../../config/ini.php';
require_once '../../class/notificationClass.php';

$pdo = pdo_init();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentId = $_POST['appointment_id'];
    $action = $_POST['action'];
    
    // Get current user ID from session (staff member performing the action)
    $actorUserId = $_SESSION['uid'] ?? null;
    
    try {
        if ($action === 'accept') {
            $statusId = 1; // Assuming 1 is the status ID for approved
            $message = 'Appointment accepted successfully';
            $query = $pdo->prepare('UPDATE appointment SET app_status_id = :status_id WHERE app_id = :appointment_id');
            $query->execute(['status_id' => $statusId, 'appointment_id' => $appointmentId]);
            
            // Get updated appointment details for JSON response
            $detailsQuery = $pdo->prepare('
                SELECT a.app_id, a.app_status_id, a_s.app_status_name, a.payment_status,
                       CONCAT(cust.user_name, " ", cust.user_midname, " ", cust.user_lastname) as customer_name,
                       st.service_type_name
                FROM appointment a
                LEFT JOIN appointment_status a_s ON a.app_status_id = a_s.app_status_id
                LEFT JOIN user cust ON a.user_id = cust.user_id
                LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
                WHERE a.app_id = ?
            ');
            $detailsQuery->execute([$appointmentId]);
            $appointmentDetails = $detailsQuery->fetch(PDO::FETCH_OBJ);
            
            // Create notification for administrators
            if ($actorUserId) {
                $notificationHandler = new NotificationHandler($pdo);
                $notificationResult = $notificationHandler->createStatusChangeNotification(
                    $appointmentId,
                    'Pending',
                    'Approved',
                    $actorUserId,
                    ['action_type' => 'accept']
                );
                
                // Log notification result for debugging (optional)
                if (!$notificationResult['success']) {
                    error_log('Failed to create accept notification: ' . $notificationResult['message']);
                }
                
                // Create separate notification for the customer
                $customerNotificationResult = $notificationHandler->createCustomerStatusNotification(
                    $appointmentId,
                    'Pending',
                    'Approved',
                    $actorUserId,
                    ['action_type' => 'accept']
                );
                
                // Log customer notification result for debugging
                if (!$customerNotificationResult['success']) {
                    error_log('Failed to create customer accept notification: ' . $customerNotificationResult['message']);
                }
                
                // Create notifications for technicians (both primary and secondary)
                $technicianNotificationResult = $notificationHandler->createTechnicianAcceptanceNotification(
                    $appointmentId,
                    $actorUserId,
                    ['action_type' => 'accept']
                );
                
                // Log technician notification result for debugging
                if (!$technicianNotificationResult['success']) {
                    error_log('Failed to create technician acceptance notifications: ' . $technicianNotificationResult['message']);
                } else {
                    error_log('Technician acceptance notifications created successfully for appointment ' . $appointmentId);
                }
            }
            
        } elseif ($action === 'decline') {
            $statusId = 4; // Assuming 4 is the status ID for declined
            $justification = $_POST['justification'] ?? '';
            $message = 'Appointment declined successfully';
            $query = $pdo->prepare('UPDATE appointment SET app_status_id = :status_id, decline_justification = :justification WHERE app_id = :appointment_id');
            $query->execute(['status_id' => $statusId, 'justification' => $justification, 'appointment_id' => $appointmentId]);
            
            // Get updated appointment details for JSON response
            $detailsQuery = $pdo->prepare('
                SELECT a.app_id, a.app_status_id, a_s.app_status_name, a.payment_status,
                       CONCAT(cust.user_name, " ", cust.user_midname, " ", cust.user_lastname) as customer_name,
                       st.service_type_name, a.decline_justification
                FROM appointment a
                LEFT JOIN appointment_status a_s ON a.app_status_id = a_s.app_status_id
                LEFT JOIN user cust ON a.user_id = cust.user_id
                LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
                WHERE a.app_id = ?
            ');
            $detailsQuery->execute([$appointmentId]);
            $appointmentDetails = $detailsQuery->fetch(PDO::FETCH_OBJ);
            
            // Create notification for administrators
            if ($actorUserId) {
                $notificationHandler = new NotificationHandler($pdo);
                $notificationResult = $notificationHandler->createStatusChangeNotification(
                    $appointmentId,
                    'Pending',
                    'Declined',
                    $actorUserId,
                    [
                        'action_type' => 'decline',
                        'justification' => $justification
                    ]
                );
                
                // Log notification result for debugging (optional)
                if (!$notificationResult['success']) {
                    error_log('Failed to create decline notification: ' . $notificationResult['message']);
                }
                
                // Create separate notification for the customer
                $customerNotificationResult = $notificationHandler->createCustomerStatusNotification(
                    $appointmentId,
                    'Pending',
                    'Declined',
                    $actorUserId,
                    [
                        'action_type' => 'decline',
                        'justification' => $justification
                    ]
                );
                
                // Log customer notification result for debugging
                if (!$customerNotificationResult['success']) {
                    error_log('Failed to create customer decline notification: ' . $customerNotificationResult['message']);
                }
            }
            
        } elseif ($action === 'mark_as_paid') {
            $message = 'Payment recorded successfully';
            $query = $pdo->prepare('UPDATE appointment SET payment_status = "Paid" WHERE app_id = :appointment_id');
            $query->execute(['appointment_id' => $appointmentId]);
            
            // Get updated appointment details for JSON response
            $detailsQuery = $pdo->prepare('
                SELECT a.app_id, a.app_status_id, a_s.app_status_name, a.payment_status,
                       CONCAT(cust.user_name, " ", cust.user_midname, " ", cust.user_lastname) as customer_name,
                       st.service_type_name, a.app_price
                FROM appointment a
                LEFT JOIN appointment_status a_s ON a.app_status_id = a_s.app_status_id
                LEFT JOIN user cust ON a.user_id = cust.user_id
                LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
                WHERE a.app_id = ?
            ');
            $detailsQuery->execute([$appointmentId]);
            $appointmentDetails = $detailsQuery->fetch(PDO::FETCH_OBJ);
            
            // Create notification for administrators
            if ($actorUserId) {
                $notificationHandler = new NotificationHandler($pdo);
                $notificationResult = $notificationHandler->createStatusChangeNotification(
                    $appointmentId,
                    'Unpaid',
                    'Paid',
                    $actorUserId,
                    ['action_type' => 'payment_update']
                );
                
                // Log notification result for debugging (optional)
                if (!$notificationResult['success']) {
                    error_log('Failed to create payment notification: ' . $notificationResult['message']);
                }
                
                // Create separate payment notification for the customer
                $customerNotificationResult = $notificationHandler->createCustomerPaymentNotification(
                    $appointmentId,
                    'Unpaid',
                    'Paid',
                    $actorUserId,
                    ['action_type' => 'payment_update']
                );
                
                // Log customer notification result for debugging
                if (!$customerNotificationResult['success']) {
                    error_log('Failed to create customer payment notification: ' . $customerNotificationResult['message']);
                }
            }
            
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            exit;
        }
        
        // Return enhanced JSON response with appointment details
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => [
                'appointment_id' => $appointmentId,
                'action' => $action
            ]
        ];
        
        // Add appointment details if available
        if (isset($appointmentDetails) && $appointmentDetails) {
            $response['data']['appointment'] = [
                'id' => $appointmentDetails->app_id,
                'status_id' => $appointmentDetails->app_status_id,
                'status_name' => $appointmentDetails->app_status_name,
                'payment_status' => $appointmentDetails->payment_status,
                'customer_name' => $appointmentDetails->customer_name,
                'service_type' => $appointmentDetails->service_type_name
            ];
            
            // Add action-specific data
            if ($action === 'decline' && isset($appointmentDetails->decline_justification)) {
                $response['data']['appointment']['decline_justification'] = $appointmentDetails->decline_justification;
            }
            if ($action === 'mark_as_paid' && isset($appointmentDetails->app_price)) {
                $response['data']['appointment']['price'] = $appointmentDetails->app_price;
            }
        }
        
        echo json_encode($response);
        
    } catch (Exception $e) {
        // Log error but don't fail the appointment update
        error_log('Error in appointment update with notifications: ' . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'An error occurred while processing the request: ' . $e->getMessage(),
            'data' => [
                'appointment_id' => $appointmentId,
                'action' => $action
            ]
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
