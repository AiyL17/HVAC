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
    // $response = [
    //     'success' => false,
    //     'message' => $input
    // ];
    if (isset($input['user_id']) && isset($input['first_name']) && isset($input['middle_name']) && isset($input['last_name']) && isset($input['email']) && isset($input['contact']) && isset($input['house_building_street']) && isset($input['barangay']) && isset($input['municipality_city']) && isset($input['province']) && isset($input['zip_code']) && isset($input['user_type'])) {
        $user_id = $input['user_id'];
        $first_name = $input['first_name'];
        $middle_name = $input['middle_name'];
        $last_name = $input['last_name'];
        $email = $input['email'];
        $contact = $input['contact'];
        $house_building_street = $input['house_building_street'];
        $barangay = $input['barangay'];
        $municipality_city = $input['municipality_city'];
        $province = $input['province'];
        $zip_code = $input['zip_code'];
        // Combine address fields for backward compatibility
        $address = $house_building_street . ', ' . $barangay . ', ' . $municipality_city . ', ' . $province . ', ' . $zip_code;
        $password = $input['password'];
        $user_type = $input['user_type'];

        // Check for duplicate email and password (excluding current user)
        $duplicateChecks = [
            [
                'query' => "SELECT COUNT(*) as count FROM user WHERE user_email = :email AND user_id != :user_id",
                'params' => [':email' => $email, ':user_id' => $user_id],
                'message' => 'Email already exists. Please use a different email address.'
            ],
            [
                'query' => "SELECT COUNT(*) as count FROM user WHERE user_pass = :password AND user_id != :user_id",
                'params' => [':password' => $password, ':user_id' => $user_id],
                'message' => 'Password already exists. Please use a different password.'
            ]
        ];
        
        foreach ($duplicateChecks as $check) {
            $checkStmt = $pdo->prepare($check['query']);
            
            // Bind parameters individually with proper types
            if (strpos($check['query'], 'user_email') !== false) {
                $checkStmt->bindParam(':email', $email, PDO::PARAM_STR);
                $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            } elseif (strpos($check['query'], 'user_pass') !== false) {
                $checkStmt->bindParam(':password', $password, PDO::PARAM_STR);
                $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            }
            
            $checkStmt->execute();
            $existingRecord = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existingRecord['count'] > 0) {
                $response = [
                    'success' => false,
                    'message' => $check['message']
                ];
                echo json_encode($response);
                exit;
            }
        }

        // Update user in the database
        $stmt = $pdo->prepare("UPDATE user SET user_name = :first_name, user_midname = :middle_name, user_lastname = :last_name, user_email = :email, user_contact = :contact, house_building_street = :house_building_street, barangay = :barangay, municipality_city = :municipality_city, province = :province, zip_code = :zip_code, user_pass = :password, user_type_id = :user_type WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindParam(':middle_name', $middle_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':contact', $contact, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':house_building_street', $house_building_street, PDO::PARAM_STR);
        $stmt->bindParam(':barangay', $barangay, PDO::PARAM_STR);
        $stmt->bindParam(':municipality_city', $municipality_city, PDO::PARAM_STR);
        $stmt->bindParam(':province', $province, PDO::PARAM_STR);
        $stmt->bindParam(':zip_code', $zip_code, PDO::PARAM_STR);
        $stmt->bindParam(':user_type', $user_type, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Create notification for administrators after successful user update
            try {
                session_start();
                $actorUserId = $_SESSION['uid'] ?? null;
                
                if ($actorUserId) {
                    $notificationHandler = new NotificationHandler($pdo);
                    $userData = [
                        'first_name' => $first_name,
                        'middle_name' => $middle_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'contact' => $contact,
                        'address' => $address,
                        'password' => $password,
                        'user_type_id' => $user_type
                    ];
                    
                    $notificationResult = $notificationHandler->createUserManagementNotification(
                        'edit',
                        $user_id,
                        $actorUserId,
                        $userData
                    );
                    
                    // Log notification result for debugging
                    if ($notificationResult['success']) {
                        error_log("User edit notification created successfully for user ID {$user_id}");
                    } else {
                        error_log("Failed to create user edit notification: " . $notificationResult['message']);
                    }
                } else {
                    error_log("No actor user ID found in session for user edit notification");
                }
            } catch (Exception $e) {
                // Log error but don't fail the user update
                error_log('Error creating user edit notification: ' . $e->getMessage());
            }
            
            $response = [
                'success' => true,
                'message' => 'User updated successfully.'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to update user.'
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
