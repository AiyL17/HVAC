<?php
header('Content-Type: application/json');

include '../config/ini.php';
include '../class/userClass.php';

$userClass = new userClass();
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['username']) && isset($input['password'])) {
        $username = $input['username'];
        $password = $input['password'];
        $rememberPassword = isset($input['rememberPassword']) ? $input['rememberPassword'] : false;

        if (strlen(trim($username)) >=1 && strlen(trim($password)) >= 1) {
            $uid = $userClass->userLogin($username, $password);
            if ($uid) {
                $response['success'] = true;
                $response['message'] = 'Login successful';
                $response['redirect'] = 'index.php';
                $response['rememberPassword'] = $rememberPassword;
            } else {
                $response['success'] = false;
                $response['message'] = 'Please check login details.';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Username and password are required.';
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
