<?php
/**
 * Invoice Notification Cron Job
 * This script checks for pending and overdue invoices and creates notifications
 * Run this script daily via cron job or task scheduler
 */

require_once __DIR__ . '/../config/ini.php';
require_once __DIR__ . '/../class/notificationClass.php';

try {
    $pdo = pdo_init();
    $notificationHandler = new NotificationHandler($pdo);
    
    // Get recently completed appointments with unpaid invoices (completed within last 7 days)
    $stmt = $pdo->prepare("
        SELECT 
            a.app_id,
            a.user_id,
            a.app_created,
            a.app_completed_at,
            a.app_price,
            a.payment_status,
            CONCAT(u.user_name, ' ', COALESCE(u.user_midname, ''), ' ', u.user_lastname) as customer_name,
            st.service_type_name,
            DATEDIFF(NOW(), COALESCE(a.app_completed_at, a.app_created)) as days_since_completion
        FROM appointment a
        JOIN user u ON a.user_id = u.user_id
        LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE a.app_status_id = 3 
        AND a.payment_status = 'Unpaid' 
        AND a.app_price > 0
        AND COALESCE(a.app_completed_at, a.app_created) >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ORDER BY COALESCE(a.app_completed_at, a.app_created) DESC
    ");
    
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $pendingCount = 0;
    $overdueCount = 0;
    $errors = [];
    
    foreach ($appointments as $appointment) {
        $daysSinceCompletion = $appointment['days_since_completion'];
        $invoiceStatus = '';
        
        // Determine invoice status based on days since completion
        if ($daysSinceCompletion <= 1) {
            $invoiceStatus = 'Pending';
        } else {
            $invoiceStatus = 'Overdue';
        }
        
        // Check if notification already exists for this appointment and status
        // Skip immediate notifications (generated when technician completes task)
        $checkStmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM notification 
            WHERE related_appointment_id = ? 
            AND event_type IN ('invoice_pending', 'invoice_overdue', 'admin_invoice_pending', 'admin_invoice_overdue')
            AND JSON_EXTRACT(additional_data, '$.invoice_status') = ?
            AND (
                JSON_EXTRACT(additional_data, '$.immediate') = true 
                OR created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            )
        ");
        $checkStmt->execute([$appointment['app_id'], $invoiceStatus]);
        $existingNotification = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        // Skip if immediate notification already sent OR notification sent within last 24 hours
        if ($existingNotification['count'] > 0) {
            continue;
        }
        
        try {
            // Create customer notification
            $customerResult = $notificationHandler->createCustomerInvoiceNotification(
                $appointment['app_id'], 
                $invoiceStatus,
                [
                    'automated' => true,
                    'days_since_completion' => $daysSinceCompletion
                ]
            );
            
            // Create admin notification
            $adminResult = $notificationHandler->createAdminInvoiceNotification(
                $appointment['app_id'], 
                $invoiceStatus,
                [
                    'automated' => true,
                    'days_since_completion' => $daysSinceCompletion
                ]
            );
            
            if ($customerResult['success'] && $adminResult['success']) {
                if ($invoiceStatus === 'Pending') {
                    $pendingCount++;
                } else {
                    $overdueCount++;
                }
            } else {
                $errors[] = "Failed to create notifications for appointment ID: {$appointment['app_id']}";
            }
            
        } catch (Exception $e) {
            $errors[] = "Error processing appointment ID {$appointment['app_id']}: " . $e->getMessage();
        }
    }
    
    // Log results
    $logMessage = sprintf(
        "[%s] Invoice notifications processed: %d pending, %d overdue. Errors: %d",
        date('Y-m-d H:i:s'),
        $pendingCount,
        $overdueCount,
        count($errors)
    );
    
    // Create log entry
    file_put_contents(
        __DIR__ . '/../logs/invoice_notifications.log', 
        $logMessage . "\n", 
        FILE_APPEND | LOCK_EX
    );
    
    // Output results for manual execution
    echo $logMessage . "\n";
    
    if (!empty($errors)) {
        echo "Errors encountered:\n";
        foreach ($errors as $error) {
            echo "- $error\n";
        }
    }
    
} catch (Exception $e) {
    $errorMessage = "[" . date('Y-m-d H:i:s') . "] Critical error in invoice notification cron: " . $e->getMessage();
    file_put_contents(__DIR__ . '/../logs/invoice_notifications.log', $errorMessage . "\n", FILE_APPEND | LOCK_EX);
    echo $errorMessage . "\n";
}
?>
