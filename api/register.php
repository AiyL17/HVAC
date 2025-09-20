<?php
header('Content-Type: application/json');

include '../config/ini.php';
include '../class/userClass.php';
include '../class/otpClass.php';

$userClass = new userClass();
$otpClass = new OTPClass();
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['first_name']) && isset($input['middle_name']) && isset($input['last_name']) && isset($input['email']) && isset($input['contact']) && isset($input['house_building_street']) && isset($input['barangay']) && isset($input['municipality_city']) && isset($input['province']) && isset($input['zip_code']) && isset($input['password']) && isset($input['confirm_password'])) {
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
        $password = $input['password'];
        $confirm_password = $input['confirm_password'];

        if (strlen(trim($first_name)) > 0 && strlen(trim($middle_name)) > 0 && strlen(trim($last_name)) > 0 && strlen(trim($email)) > 0 && strlen(trim($contact)) > 0 && strlen(trim($house_building_street)) > 0 && strlen(trim($barangay)) > 0 && strlen(trim($municipality_city)) > 0 && strlen(trim($province)) > 0 && strlen(trim($zip_code)) > 0 && strlen(trim($password)) > 0 && strlen(trim($confirm_password)) > 0) {
           
            if ($password === $confirm_password) {
                // Check if OTP is verified before allowing registration
                if (!$otpClass->isOTPVerified($contact)) {
                    $response['success'] = false;
                    $response['message'] = 'Please verify your phone number with OTP before completing registration.';
                    $response['otp_required'] = true;
                } else {
                    // Proceed with registration
                    $uid = $userClass->userRegister($first_name, $middle_name, $last_name, $email, $contact, $house_building_street, $barangay, $municipality_city, $province, $zip_code, $password);
                    if ($uid) {
                        // Clear OTP session after successful registration
                        $otpClass->clearOTPSession();
                        
                        $response['success'] = true;
                        $response['message'] = 'Registration successful! Your account has been created.';
                        $response['redirect'] = 'login.php';
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'Registration failed. Please try again.';
                    }
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'Passwords do not match.';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'All fields are required.';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid input.';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
