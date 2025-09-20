<?php
// fetch_task_counts.php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

// Get the technician ID from the session
$technician_id = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;

// Validate the technician ID
if (!$technician_id) {
    // Return an error if the technician ID is not found in the session
    echo json_encode([
        'success' => false,
        'message' => 'Technician ID not found in session.'
    ]);
    exit;
}

try {
    // Get total task count (all tasks assigned to the technician - primary or secondary)
    $stmt_total = $pdo->prepare('SELECT COUNT(*) AS task_count
        FROM appointment
        WHERE (user_technician = :technician_id OR user_technician_2 = :technician_id)');
    $stmt_total->bindParam(':technician_id', $technician_id, PDO::PARAM_INT);
    $stmt_total->execute();
    $total_result = $stmt_total->fetch(PDO::FETCH_ASSOC);
    $task_count = $total_result['task_count'];

    // Get approved appointments (approved by admin, ready for technician to start)
    $stmt_pending = $pdo->prepare('SELECT COUNT(*) AS pending_count
        FROM appointment
        WHERE app_status_id = 1 AND (user_technician = :technician_id OR user_technician_2 = :technician_id)');
    $stmt_pending->bindParam(':technician_id', $technician_id, PDO::PARAM_INT);
    $stmt_pending->execute();
    $pending_result = $stmt_pending->fetch(PDO::FETCH_ASSOC);
    $pending_count = $pending_result['pending_count'];

    // Get in progress appointments
    $stmt_in_progress = $pdo->prepare('SELECT COUNT(*) AS in_progress_count
        FROM appointment
        WHERE  app_status_id = 5 AND (user_technician = :technician_id OR user_technician_2 = :technician_id)');
    $stmt_in_progress->bindParam(':technician_id', $technician_id, PDO::PARAM_INT);
    $stmt_in_progress->execute();
    $in_progress_result = $stmt_in_progress->fetch(PDO::FETCH_ASSOC);
    $in_progress_count = $in_progress_result['in_progress_count'];

    // Get completed appointments
    $stmt_completed = $pdo->prepare('SELECT COUNT(*) AS completed_count
        FROM appointment
        WHERE  app_status_id = 3 AND (user_technician = :technician_id OR user_technician_2 = :technician_id)');
    $stmt_completed->bindParam(':technician_id', $technician_id, PDO::PARAM_INT);
    $stmt_completed->execute();
    $completed_result = $stmt_completed->fetch(PDO::FETCH_ASSOC);
    $completed_count = $completed_result['completed_count'];

    // Return the data as JSON
    echo json_encode([
        'success' => true,
        'task_count' => $task_count,
        'pending_count' => $pending_count,
        'in_progress_count' => $in_progress_count,
        'completed_count' => $completed_count
    ]);
} catch (PDOException $e) {
    // Return an error message if the database query fails
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>