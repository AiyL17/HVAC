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

    if (isset($input['first_name']) && isset($input['middle_name']) && isset($input['last_name']) && isset($input['email']) && isset($input['contact']) && isset($input['house_building_street']) && isset($input['barangay']) && isset($input['municipality_city']) && isset($input['province']) && isset($input['zip_code']) && isset($input['password']) && isset($input['user_type'])) {
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

        // Check for duplicate email and password to prevent multiple insertions
        $duplicateChecks = [
            [
                'query' => "SELECT COUNT(*) as count FROM user WHERE user_email = :email",
                'param' => ':email',
                'value' => $email,
                'message' => 'Email already exists. Please use a different email address.'
            ],
            [
                'query' => "SELECT COUNT(*) as count FROM user WHERE user_pass = :password",
                'param' => ':password',
                'value' => $password,
                'message' => 'Password already exists. Please use a different password.'
            ]
        ];
        
        foreach ($duplicateChecks as $check) {
            $checkStmt = $pdo->prepare($check['query']);
            $checkStmt->bindParam($check['param'], $check['value'], PDO::PARAM_STR);
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

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Get the next user_id by finding the maximum existing user_id and adding 1
        $maxIdStmt = $pdo->prepare("SELECT MAX(user_id) as max_id FROM user");
        $maxIdStmt->execute();
        $maxIdResult = $maxIdStmt->fetch(PDO::FETCH_ASSOC);
        $nextUserId = ($maxIdResult['max_id'] ?? 0) + 1;

        // Insert new user into the database with explicit user_id
        try {
            $stmt = $pdo->prepare("INSERT INTO user (user_id, user_name, user_midname, user_lastname, user_pass, user_email, user_contact, house_building_street, barangay, municipality_city, province, zip_code, user_type_id) VALUES (:user_id, :first_name, :middle_name, :last_name, :password, :email, :contact, :house_building_street, :barangay, :municipality_city, :province, :zip_code, :user_type)");
            $stmt->bindParam(':user_id', $nextUserId, PDO::PARAM_INT);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':middle_name', $middle_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':contact', $contact, PDO::PARAM_STR);
            $stmt->bindParam(':house_building_street', $house_building_street, PDO::PARAM_STR);
            $stmt->bindParam(':barangay', $barangay, PDO::PARAM_STR);
            $stmt->bindParam(':municipality_city', $municipality_city, PDO::PARAM_STR);
            $stmt->bindParam(':province', $province, PDO::PARAM_STR);
            $stmt->bindParam(':zip_code', $zip_code, PDO::PARAM_STR);
            $stmt->bindParam(':user_type', $user_type, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $newUserId = $pdo->lastInsertId();
                
                // Create notification for administrators after successful user creation
                try {
                    // Session should already be started from ini.php
                    $actorUserId = $_SESSION['uid'] ?? null;
                    
                    if ($actorUserId) {
                        $notificationHandler = new NotificationHandler($pdo);
                        $userData = [
                            'user_id' => $nextUserId,
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'email' => $email,
                            'user_type' => $user_type
                        ];
                        $notificationHandler->createUserManagementNotification('add', $nextUserId, $actorUserId, $userData);
                    }
                } catch (Exception $e) {
                    error_log("Failed to create notification: " . $e->getMessage());
                }
                
                $response = [
                    'success' => true,
                    'message' => 'User added successfully.',
                    'user_id' => $newUserId
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to add user.'
                ];
            }
        } catch (PDOException $e) {
            // Handle database errors (including duplicate key violations)
            error_log("Database error in add_user.php: " . $e->getMessage());
            $response = [
                'success' => false,
                'message' => 'Database error occurred. Please try again.'
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
