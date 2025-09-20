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
    
    if (isset($input['phone_number']) && !empty(trim($input['phone_number']))) {
        $phoneNumber = trim($input['phone_number']);
        
        // Validate phone number format (11 digits for Philippines)
        if (!preg_match('/^[0-9]{11}$/', $phoneNumber)) {
            $response['success'] = false;
            $response['message'] = 'Please enter a valid 11-digit phone number.';
            echo json_encode($response);
            exit;
        }
        
        try {
            // Generate OTP
            $otpCode = $otpClass->generateOTP();
            
            // Send OTP via SMS using universal delivery system
            $smsResult = $otpClass->sendOTP($phoneNumber, $otpCode);
            
            // Return the result from universal OTP delivery
            $response = $smsResult;
            
            // Add additional info for successful delivery
            if ($smsResult['success']) {
                $response['expires_in'] = 300; // 5 minutes in seconds
                
                // For development/testing - remove in production
                if (isset($_GET['debug']) && $_GET['debug'] == '1') {
                    $response['debug_otp'] = $otpCode;
                }
            }
            
        } catch (Exception $e) {
            error_log("OTP Send Error: " . $e->getMessage());
            $response['success'] = false;
            $response['message'] = 'Failed to send OTP. Please try again.';
        }
        
    } else {
        $response['success'] = false;
        $response['message'] = 'Phone number is required.';
    }
    
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
