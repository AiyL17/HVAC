<?php
session_start();
include_once __DIR__ . '/../config/ini.php';
include_once __DIR__ . '/../class/userClass.php';

header('Content-Type: application/json');

// Check if user is logged in
if (empty($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit();
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

try {
    $pdo = pdo_init();
    $userClass = new userClass();
    $user_id = $_SESSION['uid'];
    
    // Get current user details for validation
    $currentUser = $userClass->userDetails($user_id);
    if (!$currentUser) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit();
    }
    
    // Validate and sanitize input data
    $user_name = trim($_POST['user_name'] ?? '');
    $user_midname = trim($_POST['user_midname'] ?? '');
    $user_lastname = trim($_POST['user_lastname'] ?? '');
    $user_email = trim($_POST['user_email'] ?? '');
    $user_contact = trim($_POST['user_phone'] ?? ''); // Note: form field is user_phone but DB column is user_contact
    
    // Handle new address fields
    $house_building_street = trim($_POST['house_building_street'] ?? '');
    $barangay = trim($_POST['barangay'] ?? '');
    $municipality_city = trim($_POST['municipality_city'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');
    
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Handle profile picture upload
    $profile_picture_filename = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_result = handleProfilePictureUpload($_FILES['profile_picture'], $user_id);
        if ($upload_result['success']) {
            $profile_picture_filename = $upload_result['filename'];
        } else {
            echo json_encode(['success' => false, 'message' => $upload_result['message']]);
            exit();
        }
    }
    
    // Validation
    if (empty($user_name) || empty($user_lastname) || empty($user_email)) {
        echo json_encode(['success' => false, 'message' => 'First name, last name, and email are required']);
        exit();
    }
    
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit();
    }
    
    // Check if email is already taken by another user
    $emailCheckQuery = "SELECT user_id FROM user WHERE user_email = :email AND user_id != :user_id";
    $emailCheckStmt = $pdo->prepare($emailCheckQuery);
    $emailCheckStmt->bindParam(':email', $user_email);
    $emailCheckStmt->bindParam(':user_id', $user_id);
    $emailCheckStmt->execute();
    
    if ($emailCheckStmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email address is already in use by another account']);
        exit();
    }
    
    // Password validation if new password is provided
    if (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
            exit();
        }
        
        if (strlen($new_password) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long']);
            exit();
        }
    }
    
    // Prepare update query
    $updateFields = [
        'user_name = :user_name',
        'user_midname = :user_midname',
        'user_lastname = :user_lastname',
        'user_email = :user_email',
        'user_contact = :user_contact',
        'house_building_street = :house_building_street',
        'barangay = :barangay',
        'municipality_city = :municipality_city',
        'province = :province',
        'zip_code = :zip_code'
    ];
    
    $params = [
        ':user_name' => $user_name,
        ':user_midname' => $user_midname,
        ':user_lastname' => $user_lastname,
        ':user_email' => $user_email,
        ':user_contact' => $user_contact,
        ':house_building_street' => $house_building_street,
        ':barangay' => $barangay,
        ':municipality_city' => $municipality_city,
        ':province' => $province,
        ':zip_code' => $zip_code,
        ':user_id' => $user_id
    ];
    
    // Add profile picture to update if uploaded
    if ($profile_picture_filename !== null) {
        $updateFields[] = 'user_profile_picture = :user_profile_picture';
        $params[':user_profile_picture'] = $profile_picture_filename;
    }
    
    // Add password to update if provided
    if (!empty($new_password)) {
        $updateFields[] = 'user_pass = :user_pass';
        $params[':user_pass'] = password_hash($new_password, PASSWORD_DEFAULT);
    }
    
    $updateQuery = "UPDATE user SET " . implode(', ', $updateFields) . " WHERE user_id = :user_id";
    $updateStmt = $pdo->prepare($updateQuery);
    
    // Execute the update
    if ($updateStmt->execute($params)) {
        $affectedRows = $updateStmt->rowCount();
        
        if ($affectedRows > 0) {
            // Profile was actually updated
            try {
                // Log the profile update (optional - only if user_logs table exists)
                $logQuery = "INSERT INTO user_logs (user_id, action, details, created_at) VALUES (:user_id, 'profile_update', :details, NOW())";
                $logStmt = $pdo->prepare($logQuery);
                $logDetails = "Profile updated: " . $user_name . " " . $user_lastname;
                $logStmt->bindParam(':user_id', $user_id);
                $logStmt->bindParam(':details', $logDetails);
                $logStmt->execute();
            } catch (PDOException $logError) {
                // Log table might not exist, continue without logging
                error_log("Profile update log failed: " . $logError->getMessage());
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Profile updated successfully',
                'affected_rows' => $affectedRows,
                'data' => [
                    'user_name' => $user_name,
                    'user_midname' => $user_midname,
                    'user_lastname' => $user_lastname,
                    'user_email' => $user_email,
                    'user_contact' => $user_contact,
                    'house_building_street' => $house_building_street,
                    'barangay' => $barangay,
                    'municipality_city' => $municipality_city,
                    'province' => $province,
                    'zip_code' => $zip_code,
                    'user_profile_picture' => $profile_picture_filename
                ]
            ]);
        } else {
            // No rows were affected (data was the same)
            echo json_encode([
                'success' => true, 
                'message' => 'No changes were made to your profile',
                'affected_rows' => 0
            ]);
        }
    } else {
        $errorInfo = $updateStmt->errorInfo();
        error_log("Profile update failed: " . implode(' - ', $errorInfo));
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to update profile. Please try again.',
            'error_code' => $errorInfo[1] ?? 'unknown'
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Profile update error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
} catch (Exception $e) {
    error_log("Profile update error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred']);
}

// Function to handle profile picture upload
function handleProfilePictureUpload($file, $user_id) {
    // Define upload directory
    $upload_dir = __DIR__ . '/../userprofile/';
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) {
            return ['success' => false, 'message' => 'Failed to create upload directory'];
        }
    }
    
    // Validate file size (5MB limit)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'File size must be less than 5MB'];
    }
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = mime_content_type($file['tmp_name']);
    if (!in_array($file_type, $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed'];
    }
    
    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'user_' . $user_id . '_profile_' . time() . '.' . strtolower($file_extension);
    $file_path = $upload_dir . $filename;
    
    // Remove old profile picture if exists
    try {
        $pdo = pdo_init();
        $stmt = $pdo->prepare("SELECT user_profile_picture FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $current_user = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($current_user && !empty($current_user->user_profile_picture)) {
            $old_file_path = $upload_dir . $current_user->user_profile_picture;
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
        }
    } catch (Exception $e) {
        // Continue even if old file deletion fails
        error_log("Failed to delete old profile picture: " . $e->getMessage());
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => 'Failed to upload file'];
    }
}
?>
