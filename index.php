<?php
include 'config/ini.php';
include 'class/userClass.php';

$userClass = new userClass();
$pdo = pdo_init();
ob_start();
if (empty($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
} else {
    $userDetails = $userClass->userDetails($_SESSION['uid']);
    
    // Check if user details were found
    if (!$userDetails) {
        // User session exists but user not found in database
        // Clear session and redirect to login
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

if (basename($_SERVER['PHP_SELF']) === 'index.php' && empty($_GET['page'])) {
    // Check the user type and set the default page accordingly
    if ($userDetails->user_type === 'technician') {
        header('Location: index.php?page=dashboard');
    } else {
        header('Location: index.php?page=dashboard');
    }
    exit();
}

$page = $_GET['page'] ?? 'dashboard';
$user_type = $userDetails->user_type;
// Force sidebar to highlight 'user' for history pages
if (
    ($page === 'appointment' && isset($_GET['history'])) ||
    ($page === 'appointment' && isset($_GET['tech-history']))
) {
    $current_page = 'user';
} else {
    $current_page = $page;
}
$allowedPages = [
    'administrator' => ['dashboard', 'appointment', 'user', 'invoice', 'service-management', 'sales', 'schedule'],
    'staff' => ['dashboard', 'appointment', 'user', 'schedule', 'invoice'],
    'customer' => ['dashboard', 'appointment', 'service-history', 'schedule', 'analytics', 'invoice'],
    'technician' => ['dashboard', 'task', 'schedule', 'statistics']
];
// Map to define which user type's files to use
$fileSourceMap = [
    'administrator' => 'administrator',
    'staff' => 'administrator', 
    'customer' => 'customer',
    'technician' => 'technician'
];

// Determine if we should show 404 page
$show404 = false;

// Check if the user type is valid
if (array_key_exists($user_type, $allowedPages)) {
    // Check if the requested page is allowed for the user type
    if (in_array($page, $allowedPages[$user_type])) {
        // Get the correct directory to use for this user type
        $fileSource = $fileSourceMap[$user_type];

        // Check if the file exists
        $filePath = "user/{$fileSource}/{$page}.php";
        if (!file_exists($filePath)) {
            $show404 = true;
        }
    } else {
        $show404 = true;
    }
} else {
    $show404 = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes/cdn.php'; ?>
    
    <script src="js/javascript.js"></script>
    <script src="js/DialogUtils.js"></script>
    <script src="js/ToastUtils.js"></script>
    <?php if ($user_type === 'administrator' || $user_type === 'staff'): ?>
    <script src="js/admin_notifications.js?v=<?= time() ?>"></script>
    <?php elseif ($user_type === 'customer'): ?>
    <script src="js/customer_notifications.js?v=<?= time() ?>"></script>
    <?php elseif ($user_type === 'technician'): ?>
    <script src="js/technician_notifications.js?v=<?= time() ?>"></script>
    <?php endif; ?>
    <title><?= $show404 ? '404 Not Found' : ucfirst($_GET['page'] ?? 'Dashboard') ?> | HVAC</title>
</head>

<body class="overflow-auto" data-user-type="<?= $user_type ?>">
    <?php if (!$show404): ?>
        <?php include 'includes/sidebar.php'; ?>
        <?php include 'includes/navbar.php'; ?>
    <?php endif; ?>

    <div id="main" class="<?= !$show404 ? 'ps-md-4 pe-md-4' : 'm-0 ' ?>">
        <div class="container">
            <?php
            if ($show404) {
                include "404.php";
            } else {
                // Include the appropriate file based on the source directory and page
                include $filePath;
            }
            ob_end_flush();
            ?>
        </div>
    </div>
</body>

</html>