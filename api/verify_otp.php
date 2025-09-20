<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include '../config/ini.php';
include '../class/otpClass.php';

$otpClass = new OTPClass();
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['phone_number']) && isset($input['otp_code']) && 
        !empty(trim($input['phone_number'])) && !empty(trim($input['otp_code']))) {
        
        $phoneNumber = trim($input['phone_number']);
        $otpCode = trim($input['otp_code']);
        
        // Validate phone number format
        if (!preg_match('/^[0-9]{11}$/', $phoneNumber)) {
            $response['success'] = false;
            $response['message'] = 'Invalid phone number format.';
            echo json_encode($response);
            exit;
        }
        
        // Validate OTP format (6 digits)
        if (!preg_match('/^[0-9]{6}$/', $otpCode)) {
            $response['success'] = false;
            $response['message'] = 'OTP must be 6 digits.';
            echo json_encode($response);
            exit;
        }
        
        try {
            // Verify OTP
            $verificationResult = $otpClass->verifyOTP($phoneNumber, $otpCode);
            
            if ($verificationResult['success']) {
                $response['success'] = true;
                $response['message'] = $verificationResult['message'];
                $response['verified'] = true;
            } else {
                $response['success'] = false;
                $response['message'] = $verificationResult['message'];
                $response['verified'] = false;
            }
            
        } catch (Exception $e) {
            error_log("OTP Verification Error: " . $e->getMessage());
            $response['success'] = false;
            $response['message'] = 'Verification failed. Please try again.';
            $response['verified'] = false;
        }
        
    } else {
        $response['success'] = false;
        $response['message'] = 'Phone number and OTP code are required.';
        $response['verified'] = false;
    }
    
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
    $response['verified'] = false;
}

echo json_encode($response);
?>
