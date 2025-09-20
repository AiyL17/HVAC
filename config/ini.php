<?php



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Manila');

$DB_HOST = "localhost";
$DB_NAME = "hvac";
$DB_USER = "root";
$DB_PASS = "";
// define("BASE_URL", "http://localhost//"); // Eg. http://yourwebsite.com

if (!function_exists('pdo_init')) {
function pdo_init() {
    global $DB_HOST,
        $DB_NAME,
        $DB_USER,
        $DB_PASS;
    try {
        $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
        $pdo->exec("set names utf8");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "ERROR: " . $e->getMessage();
    }
}

// Function to update user activity timestamp
if (!function_exists('update_user_activity')) {
function update_user_activity() {
    if (isset($_SESSION['uid']) && !empty($_SESSION['uid'])) {
        try {
            $pdo = pdo_init();
            $stmt = $pdo->prepare("UPDATE user SET last_activity = NOW() WHERE user_id = :user_id");
            $stmt->bindParam(":user_id", $_SESSION['uid'], PDO::PARAM_INT);
            $stmt->execute();
            $pdo = null;
        } catch (PDOException $e) {
            // Silently handle errors to avoid breaking page loads
            error_log("Failed to update user activity: " . $e->getMessage());
        }
    }
}
}

// Automatically update user activity on page load (for logged-in users)
if (isset($_SESSION['uid']) && !empty($_SESSION['uid'])) {
    update_user_activity();
}
}


