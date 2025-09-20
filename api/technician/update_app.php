<?php
// Prevent any output before JSON response
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to output
ini_set('log_errors', 1); // Log errors instead

header('Content-Type: application/json');
include '../../config/ini.php';
require_once '../../class/notificationClass.php';

$pdo = pdo_init();

// Clear any output buffer that might contain warnings/notices
ob_clean();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required POST parameters
    if (!isset($_POST['appointment_id']) || !isset($_POST['action'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        exit;
    }
    
    $appointmentId = trim($_POST['appointment_id']);
    $action = trim($_POST['action']);
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;
    $justification = isset($_POST['justification']) ? trim($_POST['justification']) : '';
    
    // Validate appointment ID is numeric
    if (!is_numeric($appointmentId) || $appointmentId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid appointment ID']);
        exit;
    }
    
    // Validate action parameter
    $validActions = ['inprogress', 'complete', 'paid'];
    if (!in_array($action, $validActions)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action parameter']);
        exit;
    }

    if ($action === 'inprogress') {
        $statusId = 5; // Status ID for In Progress
        $message = 'Task marked as In Progress';
        // No price validation needed for In Progress
    } elseif ($action === 'paid') {
        // For paid action, we only update payment status, keep existing appointment status
        $statusId = null; // We won't change the status, only payment
        $message = 'Task marked as Paid';
        // No additional validation needed for Paid
    } elseif ($action === 'complete') {
        $statusId = 3; // Status ID for Complete
        $message = 'Task marked as Complete';
        
        // Price and justification validation for Complete action
        if (empty($price) || $price === '') {
            echo json_encode(['status' => 'error', 'message' => 'Price is required for completed tasks']);
            exit;
        }
        
        if (empty($justification) || trim($justification) === '') {
            echo json_encode(['status' => 'error', 'message' => 'Cost justification is required for completed tasks']);
            exit;
        }
        
        if (strlen(trim($justification)) < 10) {
            echo json_encode(['status' => 'error', 'message' => 'Cost justification must be at least 10 characters long']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        exit;
    }

    // Validate appointment exists and get current data before update
    try {
        $validateQuery = $pdo->prepare('SELECT app_id, user_id, app_status_id, payment_status, user_technician FROM appointment WHERE app_id = :appointment_id');
        $validateQuery->execute(['appointment_id' => $appointmentId]);
        $currentAppointment = $validateQuery->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error during validation: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
        exit;
    }
    
    if (!$currentAppointment) {
        error_log("ERROR: Appointment ID {$appointmentId} not found");
        echo json_encode(['status' => 'error', 'message' => 'Appointment not found']);
        exit;
    }
    
    // Log the update attempt with more detail
    error_log("=== UPDATE ATTEMPT START ===");
    error_log("App ID: {$appointmentId}");
    error_log("Action: {$action}");
    error_log("Current Status: {$currentAppointment['app_status_id']}");
    error_log("Current Payment: {$currentAppointment['payment_status']}");
    error_log("Customer ID: {$currentAppointment['user_id']}");
    error_log("Technician ID: {$currentAppointment['user_technician']}");
    
    // Check how many appointments this customer has with this technician
    $customerAppsQuery = $pdo->prepare('SELECT COUNT(*) as count, GROUP_CONCAT(app_id) as app_ids FROM appointment WHERE user_id = :user_id AND user_technician = :technician_id');
    $customerAppsQuery->execute([
        'user_id' => $currentAppointment['user_id'],
        'technician_id' => $currentAppointment['user_technician']
    ]);
    $customerApps = $customerAppsQuery->fetch(PDO::FETCH_ASSOC);
    error_log("Customer has {$customerApps['count']} appointments with this technician: {$customerApps['app_ids']}");

    // Update appointment with status, price, and justification
    try {
        if ($action === 'complete') {
            // For complete action, update status, price, and justification but keep payment_status as Unpaid
            $sql = 'UPDATE appointment SET app_status_id = :status_id, app_price = :price, app_justification = :justification, payment_status = "Unpaid", app_completed_at = NOW() WHERE app_id = :appointment_id';
            $params = [
                'status_id' => $statusId,
                'price' => $price,
                'justification' => trim($justification),
                'appointment_id' => $appointmentId
            ];
            error_log("EXECUTING SQL: {$sql}");
            error_log("PARAMETERS: " . json_encode($params));
            
            $query = $pdo->prepare($sql);
            $result = $query->execute($params);
            
            // Store transaction address when appointment is completed
            if ($result) {
                try {
                    // Get customer address data
                    $addressQuery = $pdo->prepare('
                        SELECT u.municipality_city, u.province, u.barangay, u.zip_code, u.user_address 
                        FROM user u 
                        JOIN appointment a ON u.user_id = a.user_id 
                        WHERE a.app_id = :appointment_id
                    ');
                    $addressQuery->execute(['appointment_id' => $appointmentId]);
                    $addressData = $addressQuery->fetch(PDO::FETCH_ASSOC);
                    
                    if ($addressData) {
                        // Insert transaction address
                        $transactionAddressSQL = '
                            INSERT INTO appointment_transaction_address 
                            (app_id, user_id, municipality_city, province, barangay, zip_code, full_address, created_by) 
                            VALUES (:app_id, :user_id, :municipality_city, :province, :barangay, :zip_code, :full_address, :created_by)
                        ';
                        
                        $transactionParams = [
                            'app_id' => $appointmentId,
                            'user_id' => $currentAppointment['user_id'],
                            'municipality_city' => $addressData['municipality_city'],
                            'province' => $addressData['province'],
                            'barangay' => $addressData['barangay'],
                            'zip_code' => $addressData['zip_code'],
                            'full_address' => $addressData['user_address'],
                            'created_by' => $_SESSION['uid'] ?? null
                        ];
                        
                        $transactionQuery = $pdo->prepare($transactionAddressSQL);
                        $transactionResult = $transactionQuery->execute($transactionParams);
                        
                        if ($transactionResult) {
                            error_log("Transaction address stored successfully for appointment {$appointmentId}");
                        } else {
                            error_log("Failed to store transaction address for appointment {$appointmentId}");
                        }
                    }
                } catch (PDOException $e) {
                    error_log("Error storing transaction address: " . $e->getMessage());
                    // Don't fail the main update if address storage fails
                }
            }
        } elseif ($action === 'paid') {
            // Simple paid action - just update payment status
            $sql = 'UPDATE appointment SET payment_status = "Paid" WHERE app_id = :appointment_id';
            $params = ['appointment_id' => $appointmentId];
            error_log("EXECUTING SQL: {$sql}");
            error_log("PARAMETERS: " . json_encode($params));
            
            $query = $pdo->prepare($sql);
            $result = $query->execute($params);
        } else {
            // For other actions (like inprogress), only update status
            $sql = 'UPDATE appointment SET app_status_id = :status_id WHERE app_id = :appointment_id';
            $params = [
                'status_id' => $statusId,
                'appointment_id' => $appointmentId
            ];
            error_log("EXECUTING SQL: {$sql}");
            error_log("PARAMETERS: " . json_encode($params));
            
            $query = $pdo->prepare($sql);
            $result = $query->execute($params);
        }
    } catch (PDOException $e) {
        error_log("Database error during update: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
        exit;
    }
    
    // Check how many rows were affected
    $rowsAffected = $query->rowCount();
    error_log("UPDATE RESULT: Rows affected = {$rowsAffected} for appointment ID {$appointmentId}");
    error_log("SQL Execution Result: " . ($result ? 'SUCCESS' : 'FAILED'));
    
    // Check what actually happened to all appointments for this customer
    try {
        $afterUpdateQuery = $pdo->prepare('SELECT app_id, app_status_id, payment_status FROM appointment WHERE user_id = :user_id AND user_technician = :technician_id ORDER BY app_id');
        $afterUpdateQuery->execute([
            'user_id' => $currentAppointment['user_id'],
            'technician_id' => $currentAppointment['user_technician']
        ]);
        $afterUpdate = $afterUpdateQuery->fetchAll(PDO::FETCH_ASSOC);
        error_log("AFTER UPDATE - All customer appointments:");
        foreach ($afterUpdate as $app) {
            error_log("  App ID: {$app['app_id']}, Status: {$app['app_status_id']}, Payment: {$app['payment_status']}");
        }
    } catch (PDOException $e) {
        error_log("Database error during verification: " . $e->getMessage());
        // Don't exit here as the main update was successful
    }
    
    if ($rowsAffected === 0) {
        error_log("WARNING: No rows were updated for appointment ID {$appointmentId}");
        echo json_encode(['status' => 'error', 'message' => 'No appointment was updated']);
        exit;
    } elseif ($rowsAffected > 1) {
        error_log("CRITICAL ERROR: {$rowsAffected} rows were affected when only 1 should have been updated for appointment ID {$appointmentId}");
        echo json_encode(['status' => 'error', 'message' => 'Multiple appointments were affected - update cancelled']);
        exit;
    }
    
    error_log("=== UPDATE ATTEMPT END ===");
    
    // Verify the update was successful by checking the specific appointment
    try {
        $verifyQuery = $pdo->prepare('SELECT app_status_id, payment_status FROM appointment WHERE app_id = :appointment_id');
        $verifyQuery->execute(['appointment_id' => $appointmentId]);
        $updatedAppointment = $verifyQuery->fetch(PDO::FETCH_ASSOC);
        
        if ($updatedAppointment) {
            error_log("UPDATE VERIFICATION: App ID={$appointmentId}, New Status={$updatedAppointment['app_status_id']}, New Payment={$updatedAppointment['payment_status']}");
        }
    } catch (PDOException $e) {
        error_log("Database error during final verification: " . $e->getMessage());
        // Don't exit here as the main update was successful
    }
    
    // Create notification for administrators after successful update
    try {
        // Get current user ID from session (technician performing the action)
        session_start();
        $actorUserId = $_SESSION['uid'] ?? null;
        
        if ($actorUserId) {
            $notificationHandler = new NotificationHandler($pdo);
            
            // Determine old and new status for notification
            $oldStatus = '';
            $newStatus = '';
            $additionalInfo = ['action_type' => $action];
            
            if ($action === 'inprogress') {
                $oldStatus = 'Pending';
                $newStatus = 'In Progress';
            } elseif ($action === 'complete') {
                $oldStatus = 'In Progress';
                $newStatus = 'Completed';
                $additionalInfo['price'] = $price;
                $additionalInfo['justification'] = $justification;
            } elseif ($action === 'paid') {
                $oldStatus = 'Unpaid';
                $newStatus = 'Paid';
            }
            
            // Create notification for administrators
            $notificationResult = $notificationHandler->createStatusChangeNotification(
                $appointmentId,
                $oldStatus,
                $newStatus,
                $actorUserId,
                $additionalInfo
            );
            
            // Log notification result for debugging
            if ($notificationResult['success']) {
                error_log("Technician notification created successfully for appointment {$appointmentId}, action: {$action}");
            } else {
                error_log("Failed to create technician notification for appointment {$appointmentId}: " . $notificationResult['message']);
            }
            
            // Create separate notification for the customer
            if ($action === 'paid') {
                // For payment status changes, use the payment notification method
                $customerNotificationResult = $notificationHandler->createCustomerPaymentNotification(
                    $appointmentId,
                    $oldStatus, // 'Unpaid'
                    $newStatus, // 'Paid'
                    $actorUserId,
                    $additionalInfo
                );
            } else {
                // For appointment status changes, use the status notification method
                $customerNotificationResult = $notificationHandler->createCustomerStatusNotification(
                    $appointmentId,
                    $oldStatus,
                    $newStatus,
                    $actorUserId,
                    $additionalInfo
                );
            }
            
            // Log customer notification result for debugging
            if ($customerNotificationResult['success']) {
                error_log("Customer notification created successfully for appointment {$appointmentId}, action: {$action}");
            } else {
                error_log("Failed to create customer notification for appointment {$appointmentId}: " . $customerNotificationResult['message']);
            }
            
            // Create immediate invoice notifications AFTER completion status notification
            if ($action === 'complete') {
                // Wait for status notification to be fully committed
                sleep(1); // 1 second delay to ensure proper chronological order
                
                // Create customer invoice notification immediately
                $customerInvoiceResult = $notificationHandler->createCustomerInvoiceNotification(
                    $appointmentId, 
                    'Pending', // New invoice is pending payment
                    [
                        'immediate' => true,
                        'generated_by_technician' => $actorUserId,
                        'invoice_amount' => $price,
                        'justification' => $justification
                    ]
                );
                
                // Create admin invoice notification immediately
                $adminInvoiceResult = $notificationHandler->createAdminInvoiceNotification(
                    $appointmentId, 
                    'Pending', // New invoice is pending payment
                    [
                        'immediate' => true,
                        'generated_by_technician' => $actorUserId,
                        'invoice_amount' => $price,
                        'justification' => $justification
                    ]
                );
                
                // Log invoice notification results
                if ($customerInvoiceResult['success']) {
                    error_log("Immediate customer invoice notification created for appointment {$appointmentId}");
                } else {
                    error_log("Failed to create immediate customer invoice notification: " . $customerInvoiceResult['message']);
                }
                
                if ($adminInvoiceResult['success']) {
                    error_log("Immediate admin invoice notification created for appointment {$appointmentId}");
                } else {
                    error_log("Failed to create immediate admin invoice notification: " . $adminInvoiceResult['message']);
                }
            }
        } else {
            error_log("No technician user ID found in session for notification creation");
        }
    } catch (Exception $e) {
        // Log error but don't fail the appointment update
        error_log('Error creating technician notification: ' . $e->getMessage());
    }

    // Ensure clean JSON output
    ob_clean();
    echo json_encode(['status' => 'success', 'message' => $message]);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
