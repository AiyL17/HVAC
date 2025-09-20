<?php
// Prevent any output before JSON response
ob_start();
error_reporting(E_ERROR | E_PARSE); // Only show critical errors
header('Content-Type: application/json');

include '../../config/ini.php';
require_once '../../class/notificationClass.php';

$pdo = pdo_init();

// Clear any output buffer to ensure clean JSON
ob_clean();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['user_id'])) {
        $user_id = $input['user_id'];
        
        // Get user data before deletion for notification
        $userDataStmt = $pdo->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $userDataStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $userDataStmt->execute();
        $userToDelete = $userDataStmt->fetch(PDO::FETCH_ASSOC);

        // Delete user from the database
        $stmt = $pdo->prepare("DELETE FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Create notification for administrators after successful user deletion
            try {
                session_start();
                $actorUserId = $_SESSION['uid'] ?? null;
                
                if ($actorUserId && $userToDelete) {
                    $notificationHandler = new NotificationHandler($pdo);
                    
                    $notificationResult = $notificationHandler->createUserManagementNotification(
                        'delete',
                        $user_id,
                        $actorUserId,
                        [], // No updated data for delete
                        ['deleted_user_data' => $userToDelete] // Store deleted user data
                    );
                    
                    // Log notification result for debugging
                    if ($notificationResult['success']) {
                        error_log("User deletion notification created successfully for user ID {$user_id}");
                    } else {
                        error_log("Failed to create user deletion notification: " . $notificationResult['message']);
                    }
                } else {
                    error_log("No actor user ID found in session or user data missing for deletion notification");
                }
            } catch (Exception $e) {
                // Log error but don't fail the user deletion
                error_log('Error creating user deletion notification: ' . $e->getMessage());
            }
            
            $response = [
                'success' => true,
                'message' => 'User deleted successfully.'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to delete user.'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Invalid input.'
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid request method.'
    ];
}

echo json_encode($response);
?>
