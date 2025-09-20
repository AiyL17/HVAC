<?php
    include 'config/ini.php';
    
    // Set user to inactive before clearing session
    if (isset($_SESSION['uid']) && !empty($_SESSION['uid'])) {
        $pdo = pdo_init();
        $updateStmt = $pdo->prepare("UPDATE user SET is_active = 0 WHERE user_id = :user_id");
        $updateStmt->bindParam(":user_id", $_SESSION['uid'], PDO::PARAM_INT);
        $updateStmt->execute();
        $pdo = null; // Close connection
    }
    
    // Clear session variables
    $session_uid = '';
    $session_googleCode = '';
    $_SESSION['uid'] = '';
    
    // Destroy the session completely
    session_destroy();
    
    // Redirect to login page
    $url = 'login.php';
    header("Location: $url");
    exit();
    ?>