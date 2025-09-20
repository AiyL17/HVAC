<?php
header('Content-Type: application/json');

include '../config/ini.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['email']) && isset($input['password'])) {
        $email = $input['email'];
        $password = $input['password'];

        try {
            $db = pdo_init();

            // Check for duplicate email
            $emailCheckStmt = $db->prepare("SELECT COUNT(*) as count FROM user WHERE user_email = :email");
            $emailCheckStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $emailCheckStmt->execute();
            $emailResult = $emailCheckStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($emailResult['count'] > 0) {
                $response['success'] = false;
                $response['message'] = 'Email already exists. Please use a different email address.';
                echo json_encode($response);
                exit;
            }
            
            // Check for duplicate password by comparing with all existing passwords (both hashed and plain text)
            $passwordCheckStmt = $db->prepare("SELECT user_pass FROM user");
            $passwordCheckStmt->execute();
            $existingPasswords = $passwordCheckStmt->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($existingPasswords as $existingPassword) {
                // Check if password is hashed (starts with $2y$ for bcrypt)
                if (password_get_info($existingPassword)['algo'] !== null) {
                    // Password is hashed, use password_verify
                    if (password_verify($password, $existingPassword)) {
                        $response['success'] = false;
                        $response['message'] = 'Password already exists. Please use a different password.';
                        echo json_encode($response);
                        exit;
                    }
                } else {
                    // Password is plain text (legacy), use direct comparison
                    if ($password === $existingPassword) {
                        $response['success'] = false;
                        $response['message'] = 'Password already exists. Please use a different password.';
                        echo json_encode($response);
                        exit;
                    }
                }
            }

            // If we reach here, both email and password are unique
            $response['success'] = true;
            $response['message'] = 'Email and password are available.';

        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = 'Validation error occurred. Please try again.';
            error_log("Registration validation error: " . $e->getMessage());
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Email and password are required for validation.';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
