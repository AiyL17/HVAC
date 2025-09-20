<?php
/**
 * Production-ready OTP Class for PhilSMS Integration
 * Handles OTP generation, sending, verification with robust error handling
 * Follows PhilSMS API specifications: E.164 format, sender_id 'PhilSMS'
 */
class OTPClass {
    private $philSmsApiKey = '961|sIB6LCBjTeY6XrwZF6spZzlGYcgKQDpXTC4vc1lQ';
    private $philSmsApiUrl = 'https://app.philsms.com/api/v3/sms/send';
    private $senderId = 'PhilSMS';
    private $otpLength = 6;
    private $otpExpiry = 300; // 5 minutes
    private $maxAttempts = 3;
    private $resendCooldown = 60; // 60 seconds
    
    // 🎯 UNIVERSAL DELIVERY - ALL PHILIPPINE NUMBERS TREATED EQUALLY
    private $universalMessageTemplate = 'Your verification code is: {otp}. Valid for 5 minutes. Do not share this code.';
    private $maxDeliveryAttempts = 3;
    private $retryDelaySeconds = 2;
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Generate secure OTP code
     */
    public function generateOTP() {
        return sprintf("%0{$this->otpLength}d", random_int(100000, 999999));
    }
    
    /**
     * 🎯 UNIVERSAL OTP DELIVERY - ALL PHILIPPINE NUMBERS TREATED EQUALLY
     * No carrier discrimination - ensures maximum delivery success
     */
    public function sendOTP($phoneNumber, $otpCode) {
        $timestamp = date('Y-m-d H:i:s');
        $deliveryId = uniqid('UNIV_', true);
        
        try {
            // 🔍 STEP 1: Basic validation
            if (!$this->validatePhoneNumber($phoneNumber)) {
                return [
                    'success' => false,
                    'message' => 'Invalid phone number format. Please use 11-digit Philippine mobile number (09XXXXXXXXX).',
                    'error_code' => 'INVALID_PHONE_FORMAT',
                    'delivery_id' => $deliveryId,
                    'timestamp' => $timestamp
                ];
            }
            
            // Rate limiting check
            if (!$this->canResendOTP()) {
                $remainingTime = $this->resendCooldown - (time() - ($_SESSION['otp_data']['created_at'] ?? 0));
                return [
                    'success' => false,
                    'message' => "Please wait {$remainingTime} seconds before requesting another OTP.",
                    'error_code' => 'RATE_LIMITED',
                    'remaining_time' => $remainingTime,
                    'delivery_id' => $deliveryId,
                    'timestamp' => $timestamp
                ];
            }
            
            // 📱 STEP 2: Universal phone formatting (no carrier detection)
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            $message = str_replace('{otp}', $otpCode, $this->universalMessageTemplate);
            
            error_log("[UNIVERSAL-OTP] [{$timestamp}] Starting universal delivery for {$phoneNumber} (ID: {$deliveryId})");
            
            // 🚀 STEP 3: Universal delivery with retry mechanism
            $deliveryResult = $this->executeUniversalDelivery($formattedPhone, $message, $deliveryId, $timestamp);
            
            // 💾 STEP 4: Store OTP if delivery successful
            if ($deliveryResult['success']) {
                $this->storeOTPInSession($formattedPhone, $otpCode, $deliveryId);
                error_log("[UNIVERSAL-OTP] [{$timestamp}] OTP stored successfully for delivery: {$deliveryId}");
            }
            
            return $deliveryResult;
            
        } catch (Exception $e) {
            error_log("[UNIVERSAL-OTP] [{$timestamp}] CRITICAL_ERROR: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'SMS service temporarily unavailable. Our technical team has been notified.',
                'error_code' => 'SYSTEM_ERROR',
                'delivery_id' => $deliveryId,
                'timestamp' => $timestamp,
                'error_details' => $e->getMessage()
            ];
        }
    }
    
    /**
     * 🚀 UNIVERSAL DELIVERY METHOD - NO CARRIER DISCRIMINATION
     */
    private function executeUniversalDelivery($formattedPhone, $message, $deliveryId, $timestamp) {
        for ($attempt = 1; $attempt <= $this->maxDeliveryAttempts; $attempt++) {
            error_log("[UNIVERSAL-OTP] Attempt {$attempt}/{$this->maxDeliveryAttempts} for {$formattedPhone}");
            
            $result = $this->sendSMSUniversal($formattedPhone, $message, $attempt);
            
            if ($result['success']) {
                return [
                    'success' => true,
                    'message' => 'OTP sent successfully! Please check your phone.',
                    'delivery_status' => 'DELIVERED',
                    'message_id' => $result['message_id'],
                    'delivery_id' => $deliveryId,
                    'attempts_made' => $attempt,
                    'timestamp' => $timestamp
                ];
            }
            
            // Wait before retry (except on last attempt)
            if ($attempt < $this->maxDeliveryAttempts) {
                sleep($this->retryDelaySeconds);
            }
        }
        
        // All attempts failed
        return [
            'success' => false,
            'message' => 'Failed to send OTP after multiple attempts. Please try again.',
            'delivery_status' => 'FAILED',
            'delivery_id' => $deliveryId,
            'attempts_made' => $this->maxDeliveryAttempts,
            'timestamp' => $timestamp
        ];
    }
    
    /**
     * 📡 SEND SMS WITH UNIVERSAL APPROACH
     */
    private function sendSMSUniversal($phone, $message, $attemptNumber) {
        $postData = [
            'recipient' => $phone,
            'sender_id' => $this->senderId,
            'message' => $message
        ];
        
        $headers = [
            'Authorization: Bearer ' . $this->philSmsApiKey,
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->philSmsApiUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        // Parse response
        $responseData = json_decode($response, true);
        
        $result = [
            'success' => false,
            'message_id' => null,
            'error' => null
        ];
        
        if ($httpCode === 200 && !$curlError) {
            if (is_array($responseData) && isset($responseData['status']) && strtolower($responseData['status']) === 'success') {
                $result['success'] = true;
                $result['message_id'] = $responseData['data']['uid'] ?? uniqid('MSG_');
                error_log("[UNIVERSAL-OTP] Attempt {$attemptNumber}: SUCCESS - Message ID: {$result['message_id']}");
            } else {
                $result['error'] = $responseData['message'] ?? 'Unknown API error';
                error_log("[UNIVERSAL-OTP] Attempt {$attemptNumber}: API ERROR - {$result['error']}");
            }
        } else {
            $result['error'] = $curlError ?: "HTTP {$httpCode}";
            error_log("[UNIVERSAL-OTP] Attempt {$attemptNumber}: HTTP ERROR - {$result['error']}");
        }
        
        return $result;
    }
    
    /**
     * 📱 VALIDATE PHONE NUMBER FORMAT
     */
    private function validatePhoneNumber($phone) {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return strlen($phone) === 11 && preg_match('/^09\d{9}$/', $phone);
    }
    
    /**
     * 🔄 FORMAT PHONE NUMBER TO E.164
     */
    private function formatPhoneNumber($phone) {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return '63' . substr($phone, 1); // Convert 09XXXXXXXXX to 639XXXXXXXXX
    }
    
    /**
     * ⏰ CHECK IF CAN RESEND OTP (RATE LIMITING)
     */
    private function canResendOTP() {
        if (!isset($_SESSION['otp_data'])) {
            return true;
        }
        
        $timeSinceLastOTP = time() - $_SESSION['otp_data']['created_at'];
        return $timeSinceLastOTP >= $this->resendCooldown;
    }
    
    /**
     * 💾 STORE OTP IN SESSION
     */
    private function storeOTPInSession($phone, $otp, $deliveryId) {
        $_SESSION['otp_data'] = [
            'phone_number' => $phone,
            'otp_code' => $otp,
            'delivery_id' => $deliveryId,
            'created_at' => time(),
            'attempts' => 0,
            'verified' => false
        ];
    }
    
    /**
     * ✅ VERIFY OTP CODE
     */
    public function verifyOTP($phoneNumber, $enteredOTP) {
        if (!isset($_SESSION['otp_data'])) {
            return [
                'success' => false,
                'message' => 'No OTP found. Please request a new OTP.',
                'error_code' => 'NO_OTP_SESSION'
            ];
        }
        
        $otpData = $_SESSION['otp_data'];
        
        // Check if OTP has expired
        if ((time() - $otpData['created_at']) > $this->otpExpiry) {
            unset($_SESSION['otp_data']);
            return [
                'success' => false,
                'message' => 'OTP has expired. Please request a new OTP.',
                'error_code' => 'OTP_EXPIRED'
            ];
        }
        
        // Check attempt limit
        if ($otpData['attempts'] >= 3) {
            unset($_SESSION['otp_data']);
            return [
                'success' => false,
                'message' => 'Too many failed attempts. Please request a new OTP.',
                'error_code' => 'MAX_ATTEMPTS_EXCEEDED'
            ];
        }
        
        // Increment attempt counter
        $_SESSION['otp_data']['attempts']++;
        
        // Verify phone number and OTP
        $formattedPhone = $this->formatPhoneNumber($phoneNumber);
        if ($formattedPhone === $otpData['phone_number'] && $enteredOTP === $otpData['otp_code']) {
            $_SESSION['otp_data']['verified'] = true;
            return [
                'success' => true,
                'message' => 'OTP verified successfully!',
                'delivery_id' => $otpData['delivery_id']
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Invalid OTP. Please try again.',
            'error_code' => 'INVALID_OTP',
            'attempts_remaining' => 3 - $_SESSION['otp_data']['attempts']
        ];
    }
    
    /**
     * 🔍 CHECK IF OTP IS VERIFIED
     */
    public function isOTPVerified($phoneNumber) {
        if (!isset($_SESSION['otp_data'])) {
            return false;
        }
        
        $otpData = $_SESSION['otp_data'];
        
        // Check if OTP session exists and is verified
        if (!isset($otpData['verified']) || !$otpData['verified']) {
            return false;
        }
        
        // Check if phone number matches
        $formattedPhone = $this->formatPhoneNumber($phoneNumber);
        if ($formattedPhone !== $otpData['phone_number']) {
            return false;
        }
        
        // Check if OTP hasn't expired
        if ((time() - $otpData['created_at']) > $this->otpExpiry) {
            unset($_SESSION['otp_data']);
            return false;
        }
        
        return true;
    }
    
    /**
     * 🗑️ CLEAR OTP SESSION
     */
    public function clearOTPSession() {
        unset($_SESSION['otp_data']);
        return true;
    }
}
?>
