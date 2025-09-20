<!-- Dashboard consistent styling with admin interface -->
<style>
    /* Empty State Styling */
    .empty-state {
        padding: 2rem 1rem;
        text-align: center;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin: 1rem 0;
    }

    .empty-state-mobile {
        padding: 2rem 1rem;
        text-align: center;
        background-color: #f8f9fa;
        border-radius: 12px;
        margin: 1rem 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
    }

    /* Dashboard section cards matching admin interface */
    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #dee2e6;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .dashboard-card-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 12px 20px;
        border-radius: 12px 12px 0 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .dashboard-card-body {
        padding: 20px;
    }

    /* Filter container styling */
    .filter-container {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
        align-items: center;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-label {
        font-weight: 500;
        margin-bottom: 0;
    }

    /* Mobile filter layout - force side by side */
    @media (max-width: 767.98px) {
        .filter-form .row .col-6 {
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        
        .filter-form .row .col-12 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            margin-top: 1rem;
        }
    }

    /* Modal Scrollbar Management */
    /* Hide body scrollbar when modal is open */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }
    
    /* Ensure modal backdrop doesn't interfere */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
    }

    /* Enhanced pagination styling */
    .pagination-container {
        margin-top: 20px;
        margin-bottom: 30px;
    }
    
    .pagination {
        justify-content: center;
        flex-wrap: wrap;
        
    }
    
    .page-link {
        padding: 8px 12px;
        margin: 0 2px;
        background: #f8f9fa;
        border: 0px solid #dee2e6;
        border-radius: 20px;
        color: #495057;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 14px;
        min-width: 40px;
        text-align: center;
    }
    
    .page-link:hover {
        background: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
        transform: translateY(-1px);
        text-decoration: none;
    }
    
    .page-link.active {
        background: #007bff;
        border-color: #007bff;
        color: white;
        font-weight: bold;
    }
    
    .page-link.active:hover {
        background: #0056b3;
        border-color: #0056b3;
        transform: translateY(-1px);
        text-decoration: none;
    }
    
    .page-link.disabled {
        background: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .page-link.disabled:hover {
        background: #f8f9fa;
        transform: none;
        box-shadow: none;
        text-decoration: none;
    }
    
    .page-link.previous,
    .page-link.next {
        font-weight: bold;
        padding: 8px 12px;
        border-radius: 50%;
        min-width: 40px;
        height: 40px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Mobile Cards - Hidden on desktop */
    .mobile-cards {
        display: none;
    }

    /* Appointment card styling for mobile */
    .appointment-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
        overflow: hidden;
    }

    .appointment-card-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 12px 15px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .appointment-id {
        font-weight: 600;
        color: #007bff;
        font-size: 0.9rem;
    }
    
    .appointment-id .id-label {
        text-decoration: underline;
    }

    .appointment-status {
        font-size: 0.75rem;
    }
    
    .appointment-status .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        font-weight: 600;
    }

    .appointment-info-row {
        display: flex;
        align-items: center;
        padding: 8px 15px;
        border-bottom: 1px solid #f8f9fa;
        font-size: 0.9rem;
    }

    .appointment-info-row:last-child {
        border-bottom: none;
    }

    .appointment-info-row i {
        width: 20px;
        margin-right: 10px;
        color: #6c757d;
        flex-shrink: 0;
    }

    .appointment-actions {
        padding: 12px 15px;
        background: #f8f9fa;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .appointment-actions .btn {
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
        width: 100%;
    }

    .payment-status-mobile {
        font-weight: 500;
        color: #495057;
    }

    .mobile-empty-state {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
    }

    /* Responsive breakpoints */
    @media (max-width: 991.98px) {
        .desktop-table {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .dashboard-card-header {
            font-size: 1rem;
        }
    }

    @media (max-width: 767.98px) {
        .filter-form .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 15px;
        }
        
        .dashboard-card-header {
            padding: 12px 15px;
            font-size: 1rem;
        }
        
        .pagination .page-link {
            padding: 6px 10px;
            font-size: 0.8rem;
            min-width: 32px;
        }
    }

    @media (max-width: 575.98px) {
        .appointment-card {
            margin-bottom: 12px;
        }
        
        .appointment-card-header {
            padding: 10px 12px;
        }
        
        .appointment-info-row {
            padding: 6px 12px;
        }
        
        .appointment-actions {
            padding: 10px 12px;
        }
        
        .pagination .page-link {
            padding: 4px 8px;
            font-size: 0.75rem;
            min-width: 28px;
        }
    }

    /* Table Styling */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #4a4a4a;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e9ecef;
        padding: 12px 16px;
    }
    
    .table tbody td {
        padding: 12px 16px;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.03);
    }

    /* Table styling enhancements */
    .table-container {
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><?= ucfirst($_GET['page']) ?></h4>
</div>

<!-- Filter Section -->
<div class="dashboard-card mb-4">
    <div class="dashboard-card-header">
        <i class="bi bi-funnel me-2"></i>Filter Tasks
    </div>
    <div class="dashboard-card-body py-3">
        <div class="filter-form">
            <div class="row g-3 align-items-end">
                <div class="col-md-4 col-6">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select class="form-select" id="statusFilter" onchange="filterByStatus()">
                        <option value="all" <?= !isset($_GET['status']) || $_GET['status'] == 'all' ? 'selected' : ''; ?>>All Statuses</option>
                        <option value="1" <?= $_GET['status'] == '1' ? 'selected' : ''; ?>>Assigned</option>
                        <option value="5" <?= $_GET['status'] == '5' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="3,9" <?= $_GET['status'] == '3,9' ? 'selected' : ''; ?>>Completed</option>
                        <option value="4" <?= $_GET['status'] == '4' ? 'selected' : ''; ?>>Declined</option>
                        <option value="10" <?= $_GET['status'] == '10' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-4 col-6">
                    <label class="form-label small text-muted mb-1">Payment Status</label>
                    <select class="form-select" id="paymentFilter" onchange="filterByStatus()">
                        <option value="all" <?= !isset($_GET['payment']) || $_GET['payment'] == 'all' ? 'selected' : ''; ?>>All Payments</option>
                        <option value="Paid" <?= isset($_GET['payment']) && $_GET['payment'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                        <option value="Unpaid" <?= isset($_GET['payment']) && $_GET['payment'] == 'Unpaid' ? 'selected' : ''; ?>>Unpaid</option>
                    </select>
                </div>
                <div class="col-md-4 col-12">
                    <label class="form-label small text-muted mb-1 fw-bold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control" id="customerSearch" placeholder="Search customer name..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="font-weight: normal; color: #505050;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class=" mt-3 ">

    <?php
    // Pagination variables
    $page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $recordsPerPage = 10;
    $startFrom = ($page - 1) * $recordsPerPage;
    
    $query = $pdo->prepare('SELECT
        a.*,
        a_s.app_status_name,
        u.user_name, u.user_midname, u.user_lastname,
        s.service_type_name,
        COALESCE(at.appliances_type_name, "Not Specified") as appliances_type_name,
        ut.user_name AS tech_name,
        ut.user_midname AS tech_midname,
        ut.user_lastname AS tech_lastname,
        ut.user_contact AS tech_contact,
        ut.house_building_street AS tech_house_building_street,
        ut.barangay AS tech_barangay,
        ut.municipality_city AS tech_municipality_city,
        ut.province AS tech_province,
        ut.zip_code AS tech_zip_code,
        ut2.user_name AS tech2_name,
        ut2.user_midname AS tech2_midname,
        ut2.user_lastname AS tech2_lastname,
        ut2.user_contact AS tech2_contact,
        ut2.house_building_street AS tech2_house_building_street,
        ut2.barangay AS tech2_barangay,
        ut2.municipality_city AS tech2_municipality_city,
        ut2.province AS tech2_province,
        ut2.zip_code AS tech2_zip_code
    FROM
        appointment a
    JOIN
        appointment_status a_s ON a.app_status_id = a_s.app_status_id
    JOIN
        user u ON a.user_id = u.user_id
    JOIN
        user ut ON a.user_technician = ut.user_id
    LEFT JOIN
        user ut2 ON a.user_technician_2 = ut2.user_id
    JOIN
        service_type s ON a.service_type_id = s.service_type_id
    LEFT JOIN
        appliances_type at ON a.appliances_type_id = at.appliances_type_id
    WHERE
        (a.user_technician = "' . $_SESSION['uid'] . '" OR a.user_technician_2 = "' . $_SESSION['uid'] . '") 
    ' . (isset($_GET['search']) && !empty($_GET['search']) ? 'AND (CONCAT(u.user_name, " ", COALESCE(u.user_midname, ""), " ", u.user_lastname) LIKE "%' . addslashes($_GET['search']) . '%" OR CONCAT(u.user_name, " ", u.user_lastname) LIKE "%' . addslashes($_GET['search']) . '%")' : '') . '
    ' . (isset($_GET['status']) && $_GET['status'] != 'all' ? 'AND a.app_status_id IN(' . $_GET['status'] . ')' : '') . '
    ' . (isset($_GET['payment']) && $_GET['payment'] != 'all' ? 'AND a.payment_status = "' . addslashes($_GET['payment']) . '"' : '') . '
    ORDER BY a.app_created DESC
    ');
    $query->execute(array());
    $allAppointments = $query->fetchAll(PDO::FETCH_OBJ);
    
    // Get total number of appointments for pagination
    $totalRecords = count($allAppointments);
    $totalPages = ceil($totalRecords / $recordsPerPage);
    
    // Slice appointments for current page
    $appointments = array_slice($allAppointments, $startFrom, $recordsPerPage);

    $serviceTypeColors = [
        'Repair' => 'alert alert-danger ',
        'Maintenance' => 'alert alert-warning ',
        'Installation' => 'alert alert-success ',
    ];
    $defaultClass = 'alert alert-secondary';
    
    // Process each appointment individually
    $processedAppointments = [];
    foreach ($appointments as $app) {
        $schedule = new DateTime($app->app_created);
        $now = new DateTime();
        $interval = $now->diff($schedule);
        if ($interval->y > 0 || $interval->m > 0 || $interval->d > 1) {
            // If the appointment was created yesterday or earlier
            $appCreated = $schedule->format("F j, Y, g:i A"); // e.g., April 21, 2025, 9:23 AM
        } else {
            // If the appointment was created today
            if ($interval->h > 0) {
                $appCreated = $interval->h . 'hr' . ($interval->h > 1 ? 's' : '') . ' ago';
            } elseif ($interval->i > 0) {
                $appCreated = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
            } else {
                $appCreated = 'just now';
            }
        }

        $userName = $app->user_name . " " . $app->user_midname . " " . $app->user_lastname;

        // Store each appointment individually by app_id
        $processedAppointments[] = [
            'app_id' => $app->app_id,
            'app_desc' => $app->app_desc,
            'app_created' => $appCreated,
            'user_id' => $app->user_id,
            'user_name' => $userName,
            'service_type_name' => $app->service_type_name,
            'app_status_name' => $app->app_status_name,
            'app_schedule' => $app->app_schedule,
            'payment_status' => $app->payment_status
        ];
    }
    ?>
    
<!-- Desktop Table -->
<div class="dashboard-card desktop-table">
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Service Type</th>
                        <th scope="col">Date & Time</th>
                        <th scope="col">Status</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (empty($processedAppointments)) {
                    // Display an empty row if there are no results
                    ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-calendar-check text-muted" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mt-3">No Tasks Found</h5>
                                <p class="text-muted mb-0">You don't have any appointments assigned to you.</p>
                            </div>
                        </td>
                    </tr>
                    <?php
                } else {
                    // Display each appointment individually
                    foreach ($processedAppointments as $appointment) {
                        // Format the scheduled date
                        $scheduleDate = new DateTime($appointment['app_schedule']);
                        $formattedSchedule = $scheduleDate->format("M j, Y g:i A");
                        
                        // Get status badge class
                        $statusBadgeClass = 'bg-secondary';
                        $statusName = strtolower($appointment['app_status_name']);
                        if (strpos($statusName, 'assigned') !== false || strpos($statusName, 'approved') !== false) $statusBadgeClass = 'bg-primary';
                        elseif (strpos($statusName, 'progress') !== false) $statusBadgeClass = 'bg-warning';
                        elseif (strpos($statusName, 'completed') !== false) $statusBadgeClass = 'bg-success';
                        elseif (strpos($statusName, 'declined') !== false || strpos($statusName, 'cancelled') !== false) $statusBadgeClass = 'bg-danger';
                        
                        // Get service type badge class
                        $serviceTypeBadgeClass = $serviceTypeColors[$appointment['service_type_name']] ?? $defaultClass;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['user_name']) ?></td>
                            <td><?= htmlspecialchars($appointment['service_type_name']) ?></td>
                            <td><?= $scheduleDate->format("l, F j, Y, g:i A") ?></td>
                            <td>
                                <span class="badge <?= $statusBadgeClass ?> text-white px-2 py-1">
                                    <?= $appointment['app_status_name'] ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                $paymentBadgeClass = ($appointment['payment_status'] === 'Paid') ? 'bg-success' : 'bg-danger';
                                ?>
                                <span class="badge <?= $paymentBadgeClass ?> text-white px-2 py-1">
                                    <?= $appointment['payment_status'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary" onclick="openAppointmentModal(<?= $appointment['app_id'] ?>, <?= $appointment['user_id'] ?>)">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<!-- Mobile Cards (Hidden on Desktop) -->
<div class="mobile-cards">
    <?php if (empty($processedAppointments)): ?>
        <div class="mobile-empty-state">
            <div class="empty-state-mobile text-center py-5">
                <i class="bi bi-calendar-check text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">No Tasks Found</h5>
                <p class="text-muted mb-0 px-3">You don't have any appointments assigned to you.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($processedAppointments as $appointment): ?>
            <?php
            $scheduleDate = new DateTime($appointment['app_schedule']);
            
            // Get status badge class
            $statusBadgeClass = 'bg-secondary';
            $statusName = strtolower($appointment['app_status_name']);
            if (strpos($statusName, 'assigned') !== false || strpos($statusName, 'approved') !== false) $statusBadgeClass = 'bg-primary';
            elseif (strpos($statusName, 'progress') !== false) $statusBadgeClass = 'bg-warning';
            elseif (strpos($statusName, 'completed') !== false) $statusBadgeClass = 'bg-success';
            elseif (strpos($statusName, 'declined') !== false || strpos($statusName, 'cancelled') !== false) $statusBadgeClass = 'bg-danger';
            
            $paymentBadgeClass = ($appointment['payment_status'] === 'Paid') ? 'bg-success' : 'bg-danger';
            ?>
            <div class="appointment-card">
                <div class="appointment-card-header">
                    <div class="appointment-id"><span class="id-label">ID</span> - <?= $appointment['app_id'] ?></div>
                    <div class="appointment-status">
                        <span class="badge <?= $statusBadgeClass ?> text-white"><?= htmlspecialchars($appointment['app_status_name']) ?></span>
                    </div>
                </div>
                
                <div class="appointment-info-row">
                    <i class="bi bi-person-fill"></i>
                    <div class="text-truncate"><?= htmlspecialchars($appointment['user_name']) ?></div>
                </div>
                
                <div class="appointment-info-row">
                    <i class="bi bi-tools"></i>
                    <div class="text-truncate"><?= htmlspecialchars($appointment['service_type_name']) ?></div>
                </div>
                
                <div class="appointment-info-row">
                    <i class="bi bi-calendar-event"></i>
                    <div><?= $scheduleDate->format("l, F j, Y, g:i A") ?></div>
                </div>
                
                <div class="appointment-info-row">
                    <i class="bi bi-credit-card"></i>
                    <div class="payment-status-mobile">
                        <span class="badge <?= $paymentBadgeClass ?> text-white px-2 py-1">
                            <?= htmlspecialchars($appointment['payment_status']) ?>
                        </span>
                    </div>
                </div>
                
                <div class="appointment-actions">
                    <button type="button" class="btn btn-primary btn-sm" onclick="openAppointmentModal(<?= $appointment['app_id'] ?>, <?= $appointment['user_id'] ?>)">
                        <i class="bi bi-eye"></i> View
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<div class="d-flex justify-content-center mt-4" style="margin-bottom: 10px;">
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item <?= $page === 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $page > 1 ? '?page=task&status=' . urlencode($_GET['status'] ?? 'all') . '&payment=' . urlencode($_GET['payment'] ?? 'all') . '&search=' . urlencode($_GET['search'] ?? '') . '&page_num=' . ($page - 1) : '#' ?>" aria-label="Previous">
                    &lt;
                </a>
            </li>
            
            <?php
            // Pagination logic similar to administrator invoice.php
            $maxVisiblePages = 5;
            
            if ($totalPages <= $maxVisiblePages) {
                // Show all pages if total is small
                for ($i = 1; $i <= $totalPages; $i++) {
                    $isActive = ($i == $page);
                    echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                    echo '<a class="page-link" href="?page=task&status=' . urlencode($_GET['status'] ?? 'all') . '&payment=' . urlencode($_GET['payment'] ?? 'all') . '&search=' . urlencode($_GET['search'] ?? '') . '&page_num=' . $i . '">' . $i . '</a>';
                    echo '</li>';
                }
            } else {
                // Show limited pages with ellipsis
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $page + 2);
                
                // Show first page if not in range
                if ($startPage > 1) {
                    echo '<li class="page-item">';
                    echo '<a class="page-link" href="?page=task&status=' . urlencode($_GET['status'] ?? 'all') . '&payment=' . urlencode($_GET['payment'] ?? 'all') . '&search=' . urlencode($_GET['search'] ?? '') . '&page_num=1">1</a>';
                    echo '</li>';
                    if ($startPage > 2) {
                        echo '<li class="page-item disabled">';
                        echo '<span class="page-link">...</span>';
                        echo '</li>';
                    }
                }
                
                // Show current range
                for ($i = $startPage; $i <= $endPage; $i++) {
                    $isActive = ($i == $page);
                    echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                    echo '<a class="page-link" href="?page=task&status=' . urlencode($_GET['status'] ?? 'all') . '&payment=' . urlencode($_GET['payment'] ?? 'all') . '&search=' . urlencode($_GET['search'] ?? '') . '&page_num=' . $i . '">' . $i . '</a>';
                    echo '</li>';
                }
                
                // Show last page if not in range
                if ($endPage < $totalPages) {
                    if ($endPage < $totalPages - 1) {
                        echo '<li class="page-item disabled">';
                        echo '<span class="page-link">...</span>';
                        echo '</li>';
                    }
                    echo '<li class="page-item">';
                    echo '<a class="page-link" href="?page=task&status=' . urlencode($_GET['status'] ?? 'all') . '&payment=' . urlencode($_GET['payment'] ?? 'all') . '&search=' . urlencode($_GET['search'] ?? '') . '&page_num=' . $totalPages . '">' . $totalPages . '</a>';
                    echo '</li>';
                }
            }
            ?>
            
            <li class="page-item <?= $page === $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $page < $totalPages ? '?page=task&status=' . urlencode($_GET['status'] ?? 'all') . '&payment=' . urlencode($_GET['payment'] ?? 'all') . '&search=' . urlencode($_GET['search'] ?? '') . '&page_num=' . ($page + 1) : '#' ?>" aria-label="Next">
                    &gt;
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<!-- Appointment Detail Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content round_lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="appointmentModalLabel"><i class="bi bi-card-list me-2"></i>Appointment Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="appointmentModalBody">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small"><i class="bi bi-person me-2"></i>Customer Name</label>
                            <div id="modalCustomerName" class="fs-6"></div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small"><i class="bi bi-geo-alt me-2"></i>Customer Address</label>
                            <div id="modalCustomerAddress" class="fs-6"></div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small"><i class="bi bi-calendar-event me-2"></i>Date</label>
                            <div id="modalDate" class="fs-6"></div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small"><i class="bi bi-tools me-2"></i>Service Type</label>
                            <div id="modalServiceType" class="fs-6"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small"><i class="bi bi-credit-card me-2"></i>Payment Status</label>
                            <div id="modalPaymentStatus" class="fs-6"></div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small"><i class="bi bi-clock me-2"></i>Time</label>
                            <div id="modalTime" class="fs-6"></div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small"><i class="bi bi-box-seam me-2"></i>Appliance Type</label>
                            <div id="modalAppliancesType" class="fs-6"></div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small"><i class="bi bi-check2-circle me-2"></i>Status</label>
                            <div id="modalStatus" class="fs-6"></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small"><i class="bi bi-person-badge me-2"></i>Primary Technician</label>
                            <div id="modalTechnician" class="fs-6"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3" id="secondTechnicianSection" style="display: none;">
                            <label class="text-muted small"><i class="bi bi-person-plus me-2"></i>Secondary Technician</label>
                            <div id="modalTechnician2" class="fs-6"></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mt-3">
                    <h6 class="text-primary"><i class="bi bi-card-text me-2"></i>Description</h6>
                    <p id="modalDescription" class="p-3 bg-light rounded border"></p>
                </div>
                <div class="mt-3" id="completedPriceSection" style="display: none;">
                    <h6 class="text-primary"><i class="bi bi-currency-exchange me-2"></i>Finalized Price</h6>
                    <p id="modalFinalizedPrice" class="p-3 bg-light rounded border fs-5"></p>
                </div>
                <div class="mt-3" id="costJustificationSection" style="display: none;">
                    <h6 class="text-primary"><i class="bi bi-journal-text me-2"></i>Cost Justification</h6>
                    <p id="modalCostJustification" class="p-3 bg-light rounded border"></p>
                </div>
                <div class="mt-3" id="priceRangeSection" style="display: none;">
                    <h6 class="text-primary"><i class="bi bi-tag me-2"></i>Expected Price Range</h6>
                    <p id="modalPriceRange" class="p-3 bg-light rounded border fs-5"></p>
                </div>
                <div class="mt-3" id="feedbackSection" style="display: none;">
                    <h6 class="text-primary"><i class="bi bi-star-fill me-2"></i>Customer Feedback</h6>
                    <div class="p-3 bg-light rounded border">
                        <div class="mb-2">
                            <label class="text-muted small"><i class="bi bi-star me-2"></i>Rating</label>
                            <div id="modalRating" class="fs-6">
                                <span id="ratingStars" class="text-warning"></span>
                                <span id="ratingValue" class="ms-2 text-muted"></span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="text-muted small"><i class="bi bi-chat-text me-2"></i>Comment</label>
                            <div id="modalComment" class="fs-6 mt-1"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-3" id="technicianActionsSection">
                    <div class="d-flex justify-content-end gap-2" id="modalActionButtons">
                        <!-- Action buttons will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal for Actions -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content round_lg">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
            </div>
            <div class="modal-body border-0 text-center" id="confirmationModalBody">
                Mark this task as <span class="fw-bold" id="confirmationAction"></span> ?
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light border-0 px-3 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary border-0 text-light px-3 rounded-pill" id="confirmActionButton">Confirm</button>
            </div>
        </div>
    </div>
</div>

    <style>
        /* Scrollable table styles */
        .scrollable-table-container {
            max-height: 500px; /* Height for approximately 7-8 rows */
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }
        
        .scrollable-table-container .table {
            margin-bottom: 0;
        }
        
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: var(--bs-primary);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .sticky-header th {
            border-bottom: 2px solid rgba(255,255,255,0.2);
        }
        
        /* Custom scrollbar styling */
        .scrollable-table-container::-webkit-scrollbar {
            width: 8px;
        }
        
        .scrollable-table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .scrollable-table-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .scrollable-table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Ensure table rows maintain proper spacing in scrollable container */
        .scrollable-table-container .table tbody tr:hover {
            background-color: rgba(0,0,0,0.075);
        }
        
        /* Add subtle border to scrollable container */
        .scrollable-table-container {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize status filter if it exists
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                // Set the selected option based on the current URL status parameter
                const urlParams = new URLSearchParams(window.location.search);
                const currentStatus = urlParams.get('status') || 'all';
                
                // Set the correct option as selected
                statusFilter.value = currentStatus;
            }
            
            // Initialize payment filter
            const paymentFilter = document.getElementById('paymentFilter');
            if (paymentFilter) {
                // Set the selected option based on the current URL payment parameter
                const urlParams = new URLSearchParams(window.location.search);
                const currentPayment = urlParams.get('payment') || 'all';
                
                // Set the correct option as selected
                paymentFilter.value = currentPayment;
            }
            
            // Initialize customer search input
            const customerSearch = document.getElementById('customerSearch');
            if (customerSearch) {
                // Set initial value from URL parameter
                const urlParams = new URLSearchParams(window.location.search);
                const searchValue = urlParams.get('search') || '';
                customerSearch.value = searchValue;
            }
        });

        // Function to handle dropdown status filtering
        function filterByStatus() {
            const statusFilter = document.getElementById('statusFilter');
            const paymentFilter = document.getElementById('paymentFilter');
            const selectedStatus = statusFilter.value;
            const selectedPayment = paymentFilter.value;
            const searchValue = document.getElementById('customerSearch').value;
            
            // Build URL with status, payment, and search parameters
            let url = 'index.php?page=task&status=' + selectedStatus + '&payment=' + selectedPayment;
            if (searchValue.trim() !== '') {
                url += '&search=' + encodeURIComponent(searchValue.trim());
            }
            
            window.location.href = url;
        }
        
        // Live search functionality
        let searchTimeout;
        
        // Function to handle customer name search
        function searchCustomer() {
            const searchValue = document.getElementById('customerSearch').value;
            const statusFilter = document.getElementById('statusFilter');
            const selectedStatus = statusFilter.value;
            
            // Build URL with both search and status parameters
            let url = 'index.php?page=task&status=' + selectedStatus;
            if (searchValue.trim() !== '') {
                url += '&search=' + encodeURIComponent(searchValue.trim());
            }
            
            window.location.href = url;
        }
        
        // Function to clear search
        function clearSearch() {
            const statusFilter = document.getElementById('statusFilter');
            const selectedStatus = statusFilter.value;
            
            // Redirect with only status parameter, no search
            window.location.href = 'index.php?page=task&status=' + selectedStatus;
        }
        
        // Live search as user types
        document.getElementById('customerSearch').addEventListener('input', function(e) {
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Set a new timeout to search after user stops typing for 500ms
            searchTimeout = setTimeout(function() {
                const searchValue = e.target.value;
                const statusFilter = document.getElementById('statusFilter');
                const paymentFilter = document.getElementById('paymentFilter');
                const selectedStatus = statusFilter.value;
                const selectedPayment = paymentFilter.value;
                
                // Build URL with search, status, and payment parameters
                let url = 'index.php?page=task&status=' + selectedStatus + '&payment=' + selectedPayment;
                if (searchValue.trim() !== '') {
                    url += '&search=' + encodeURIComponent(searchValue.trim());
                }
                
                // Only redirect if the search value has actually changed
                const currentSearch = new URLSearchParams(window.location.search).get('search') || '';
                if (searchValue.trim() !== currentSearch) {
                    window.location.href = url;
                }
            }, 500); // Wait 500ms after user stops typing
        });
        
        // Allow Enter key to trigger immediate search
        document.getElementById('customerSearch').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                clearTimeout(searchTimeout); // Cancel the delayed search
                searchCustomer(); // Search immediately
            }
        });

        // Global variables for modal functionality
        const serviceTypeColors = {
            'Repair': 'alert alert-danger',
            'Maintenance': 'alert alert-warning',
            'Installation': 'alert alert-success',
        };
        const defaultClass = 'alert alert-secondary';
        let technicianId = <?php echo json_encode($_SESSION['uid']); ?>;
        let statusId = <?php echo json_encode($_GET['status'] ?? 'all'); ?>;
        let currentAppointmentId;
        let currentAction;

        // Function to open appointment modal
        async function openAppointmentModal(appId, customerId) {
            try {
                const modalElement = document.getElementById('appointmentModal');
                if (!modalElement) {
                    console.error('Appointment modal element not found');
                    return;
                }
                
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                
                // Store appointment ID in modal data attribute for safe retrieval
                modalElement.setAttribute('data-appointment-id', appId);
                
                // Reset modal body to loading state
                const modalBody = document.getElementById('appointmentModalBody');
                if (modalBody) {
                    modalBody.innerHTML = `
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading appointment details...</p>
                        </div>
                    `;
                }
                
                // Validate required parameters
                if (!appId || !customerId || !technicianId) {
                    throw new Error('Missing required parameters for appointment fetch');
                }
                
                // Fetch appointment details with proper error handling
                const response = await fetch(`api/technician/get_app.php?customer=${encodeURIComponent(customerId)}&technician=${encodeURIComponent(technicianId)}&status=${encodeURIComponent(statusId || 'all')}&app_id=${encodeURIComponent(appId)}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const appointments = await response.json();
                
                if (appointments && appointments.length > 0) {
                    renderAppointmentModal(appointments[0]);
                } else {
                    if (modalBody) {
                        modalBody.innerHTML = `
                            <div class="text-center p-4">
                                <i class="bi bi-exclamation-triangle text-warning fs-1"></i>
                                <p class="mt-2 text-muted">Appointment not found or no longer available.</p>
                            </div>
                        `;
                    }
                }
            } catch (error) {
                console.error('Error fetching appointment:', error);
                const modalBody = document.getElementById('appointmentModalBody');
                if (modalBody) {
                    modalBody.innerHTML = `
                        <div class="text-center p-4">
                            <i class="bi bi-exclamation-triangle text-danger fs-1"></i>
                            <p class="mt-2 text-muted">Error loading appointment details. Please try again.</p>
                            <button class="btn btn-outline-primary btn-sm mt-2" onclick="openAppointmentModal(${appId}, ${customerId})">
                                <i class="bi bi-arrow-clockwise me-1"></i>Retry
                            </button>
                        </div>
                    `;
                }
            }
        }

        // Global variables for price validation
        let currentServiceMinPrice = 0;
        let currentServiceMaxPrice = 0;

        // Function to render appointment details in modal
        function renderAppointmentModal(app) {
            // First, restore the modal structure since it was replaced with loading spinner
            const modalBody = document.getElementById('appointmentModalBody');
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small"><i class="bi bi-person me-2"></i>Customer Name</label>
                                <div id="modalCustomerName" class="fs-6"></div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small"><i class="bi bi-geo-alt me-2"></i>Customer Address</label>
                                <div id="modalCustomerAddress" class="fs-6"></div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small"><i class="bi bi-calendar-event me-2"></i>Date</label>
                                <div id="modalDate" class="fs-6"></div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small"><i class="bi bi-tools me-2"></i>Service Type</label>
                                <div id="modalServiceType" class="fs-6"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small"><i class="bi bi-credit-card me-2"></i>Payment Status</label>
                                <div id="modalPaymentStatus" class="fs-6"></div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small"><i class="bi bi-clock me-2"></i>Time</label>
                                <div id="modalTime" class="fs-6"></div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small"><i class="bi bi-box-seam me-2"></i>Appliance Type</label>
                                <div id="modalAppliancesType" class="fs-6"></div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small"><i class="bi bi-check2-circle me-2"></i>Status</label>
                                <div id="modalStatus" class="fs-6"></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small"><i class="bi bi-person-badge me-2"></i>Primary Technician</label>
                                <div id="modalTechnician" class="fs-6"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3" id="secondTechnicianSection" style="display: none;">
                                <label class="text-muted small"><i class="bi bi-person-plus me-2"></i>Secondary Technician</label>
                                <div id="modalTechnician2" class="fs-6"></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-3">
                        <h6 class="text-primary"><i class="bi bi-card-text me-2"></i>Description</h6>
                        <p id="modalDescription" class="p-3 bg-light rounded border"></p>
                    </div>
                    <div class="mt-3" id="completedPriceSection" style="display: none;">
                        <h6 class="text-primary"><i class="bi bi-currency-exchange me-2"></i>Finalized Price</h6>
                        <p id="modalFinalizedPrice" class="p-3 bg-light rounded border fs-5"></p>
                    </div>
                    <div class="mt-3" id="costJustificationSection" style="display: none;">
                        <h6 class="text-primary"><i class="bi bi-journal-text me-2"></i>Cost Justification</h6>
                        <p id="modalCostJustification" class="p-3 bg-light rounded border"></p>
                    </div>
                    <div class="mt-3" id="priceRangeSection" style="display: none;">
                        <h6 class="text-primary"><i class="bi bi-tag me-2"></i>Expected Price Range</h6>
                        <p id="modalPriceRange" class="p-3 bg-light rounded border fs-5"></p>
                    </div>
                    <div class="mt-3" id="feedbackSection" style="display: none;">
                        <h6 class="text-primary"><i class="bi bi-star-fill me-2"></i>Customer Feedback</h6>
                        <div class="p-3 bg-light rounded border">
                            <div class="mb-2">
                                <label class="text-muted small"><i class="bi bi-star me-2"></i>Rating</label>
                                <div id="modalRating" class="fs-6">
                                    <span id="ratingStars" class="text-warning"></span>
                                    <span id="ratingValue" class="ms-2 text-muted"></span>
                                </div>
                            </div>
                            <div class="mb-0">
                                <label class="text-muted small"><i class="bi bi-chat-text me-2"></i>Comment</label>
                                <div id="modalComment" class="fs-6 mt-1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 mb-3 text-center">
                        <small class="text-muted">
                            <i class="bi bi-calendar-plus me-1"></i>
                            Appointment Created: <span id="modalCreatedDate">Loading...</span>
                        </small>
                    </div>
                    <div class="mt-3" id="technicianActionsSection">
                        <div class="d-flex justify-content-end gap-2" id="modalActionButtons">
                            <!-- Action buttons will be populated by JavaScript -->
                        </div>
                    </div>
                `;
            }

            const schedule = new Date(app.app_schedule);
            const date = schedule.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            const time = schedule.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });

            // Get price display
            let priceDisplay;
            if (app.app_status_id == 3 && app.app_price && app.app_price > 0) {
                // Show actual price for completed appointments
                priceDisplay = new Intl.NumberFormat('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(app.app_price);
            } else {
                // Show price range from service type for assigned/in-progress appointments
                const minPrice = parseFloat(app.service_type_price_min) || 0;
                const maxPrice = parseFloat(app.service_type_price_max) || 0;
                
                if (minPrice > 0 && maxPrice > 0) {
                    if (minPrice === maxPrice) {
                        // Fixed price
                        priceDisplay = new Intl.NumberFormat('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(minPrice);
                    } else {
                        // Price range
                        priceDisplay = new Intl.NumberFormat('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(minPrice) + ' - ₱' + new Intl.NumberFormat('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(maxPrice);
                    }
                } else {
                    priceDisplay = '<small class="text-muted">Price not set</small>';
                }
            }

            // Get status badge class
            const statusName = app.app_status_name.toLowerCase();
            let statusBadgeClass;
            if (statusName.includes('pending')) statusBadgeClass = 'bg-warning text-dark';
            else if (statusName.includes('approved') || statusName.includes('assigned')) statusBadgeClass = 'bg-primary';
            else if (statusName.includes('declined')) statusBadgeClass = 'bg-danger';
            else if (statusName.includes('progress')) statusBadgeClass = 'bg-warning';
            else if (statusName.includes('completed')) statusBadgeClass = 'bg-success';
            else statusBadgeClass = 'bg-secondary';

            // Build customer address from API fields
            const customerAddress = [
                app.house_building_street,
                app.barangay ? `Brgy. ${app.barangay}` : null,
                app.municipality_city,
                app.province,
                app.zip_code
            ].filter(Boolean).join(', ') || 'Address not provided';

            // Populate modal fields with admin-style structure
            document.getElementById('modalCustomerName').textContent = `${app.user_name} ${app.user_midname || ''} ${app.user_lastname}`.trim();
            document.getElementById('modalCustomerAddress').textContent = customerAddress;
            document.getElementById('modalDate').textContent = date;
            document.getElementById('modalTime').textContent = time;
            document.getElementById('modalServiceType').textContent = app.service_type_name;
            document.getElementById('modalAppliancesType').textContent = app.appliances_type_name || 'Not specified';
            document.getElementById('modalStatus').textContent = app.app_status_name;
            document.getElementById('modalPaymentStatus').textContent = app.payment_status || 'Unpaid';
            
            // Technician information - build full name from API fields
            const primaryTechName = `${app.tech_name || ''} ${app.tech_midname || ''} ${app.tech_lastname || ''}`.trim() || 'Not assigned';
            document.getElementById('modalTechnician').textContent = primaryTechName;
            
            // Secondary technician (show/hide section)
            const secondTechSection = document.getElementById('secondTechnicianSection');
            if (app.tech2_name) {
                const secondaryTechName = `${app.tech2_name || ''} ${app.tech2_midname || ''} ${app.tech2_lastname || ''}`.trim();
                document.getElementById('modalTechnician2').textContent = secondaryTechName;
                secondTechSection.style.display = 'block';
            } else {
                secondTechSection.style.display = 'none';
            }
            
            // Description
            document.getElementById('modalDescription').textContent = app.app_desc || 'No description provided';
            
            // Set appointment creation date
            const createdDate = new Date(app.app_created);
            const formattedCreatedDate = createdDate.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }) + ' at ' + createdDate.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            const modalCreatedDateElement = document.getElementById('modalCreatedDate');
            if (modalCreatedDateElement) {
                modalCreatedDateElement.textContent = formattedCreatedDate;
            }
            
            // Price sections
            const completedPriceSection = document.getElementById('completedPriceSection');
            const costJustificationSection = document.getElementById('costJustificationSection');
            const priceRangeSection = document.getElementById('priceRangeSection');
            
            if (app.app_status_id == 3 && app.app_price && app.app_price > 0) {
                // Show finalized price for completed appointments
                document.getElementById('modalFinalizedPrice').innerHTML = `₱${priceDisplay}`;
                completedPriceSection.style.display = 'block';
                
                // Show cost justification if available
                if (app.app_justification) {
                    document.getElementById('modalCostJustification').textContent = app.app_justification;
                    costJustificationSection.style.display = 'block';
                } else {
                    costJustificationSection.style.display = 'none';
                }
                
                priceRangeSection.style.display = 'none';
            } else {
                // Show price range for non-completed appointments
                document.getElementById('modalPriceRange').innerHTML = `₱${priceDisplay}`;
                priceRangeSection.style.display = 'block';
                completedPriceSection.style.display = 'none';
                costJustificationSection.style.display = 'none';
            }
            
            // Customer feedback section
            const feedbackSection = document.getElementById('feedbackSection');
            if (app.app_rating && app.app_rating > 0 && app.app_comment && app.app_comment.trim() !== '') {
                // Generate star rating
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    stars += i <= app.app_rating ? '★' : '☆';
                }
                
                document.getElementById('ratingStars').textContent = stars;
                document.getElementById('ratingValue').textContent = `(${app.app_rating}/5)`;
                document.getElementById('modalComment').textContent = app.app_comment;
                feedbackSection.style.display = 'block';
            } else {
                feedbackSection.style.display = 'none';
            }
            
            // Store price range for validation
            currentServiceMinPrice = parseFloat(app.service_type_price_min) || 0;
            currentServiceMaxPrice = parseFloat(app.service_type_price_max) || 0;
            
            // Add action buttons for technician
            const actionButtonsHtml = generateActionButtons(app);
            document.getElementById('modalActionButtons').innerHTML = actionButtonsHtml;
        }

        // Function to generate action buttons based on appointment status
        function generateActionButtons(app) {
            let buttonsHtml = '';
            
            if (app.app_status_id == 1) {
                // Assigned status - show Mark as In Progress button
                buttonsHtml = `
                    <button class="btn btn-primary" onclick="showConfirmationModal('${app.service_type_name}', ${app.app_id}, 'inprogress')">
                        Mark as In Progress
                    </button>
                `;
            } else if (app.app_status_id == 5) {
                // In Progress status - show Mark as Completed button
                buttonsHtml = `
                    <button class="btn btn-primary" onclick="showConfirmationModal('${app.service_type_name}', ${app.app_id}, 'complete')">
                        Mark as Completed
                    </button>
                `;
            } else if (app.app_status_id == 3 && app.payment_status === 'Unpaid') {
                // Completed but unpaid - show Mark as Paid button with Close button (matching admin modal footer style)
                buttonsHtml = `
                    <button class="btn btn-secondary" onclick="document.getElementById('appointmentModal').querySelector('.btn-close').click()">
                        Close
                    </button>
                    <button class="btn btn-primary" onclick="showConfirmationModal('${app.service_type_name}', ${app.app_id}, 'paid')">
                        Mark as Paid
                    </button>
                `;
            }
            
            return buttonsHtml;
        }

        // Function to show confirmation modal for actions
        function showConfirmationModal(appointmentType, appointmentId, action) {
            // Get appointment ID from modal data attribute for safety
            const modalElement = document.getElementById('appointmentModal');
            const modalAppointmentId = modalElement ? modalElement.getAttribute('data-appointment-id') : appointmentId;
            
            if (!modalAppointmentId) {
                console.error('No appointment ID available for confirmation');
                alert('Error: Unable to identify appointment. Please try again.');
                return;
            }
            
            let actionText = "";
            let messageText = "";
            
            if (action.toLowerCase() === "inprogress") {
                actionText = "In Progress";
                messageText = `Mark this task as <span class="fw-bold">${actionText}</span>?`;
            } else if (action.toLowerCase() === "paid") {
                actionText = "Paid";
                messageText = `Mark this task as <span class="fw-bold">${actionText}</span>?`;
            } else {
                actionText = action.charAt(0).toUpperCase() + action.slice(1);
                
                // Format price range for display
                const minPrice = new Intl.NumberFormat('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(currentServiceMinPrice);
                const maxPrice = new Intl.NumberFormat('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(currentServiceMaxPrice);
                
                messageText = `
                    <div class="text-center mb-2">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle mb-2 border border-primary border-2" style="width: 48px; height: 48px;">
                            <i class="bi bi-check text-primary fs-4 fw-bold"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Complete Task</h5>
                        <p class="text-muted mb-0 small">Provide final service details</p>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-12">
                            <label for="price" class="form-label fw-semibold text-dark mb-2 d-flex align-items-center">
                                <i class="bi bi-currency-exchange text-success me-2"></i>
                                Final Service Price
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white border-0 fw-bold">₱</span>
                                <input type="number" class="form-control border-0 shadow-sm" id="price" placeholder="Enter amount" step="0.01" min="${currentServiceMinPrice}" max="${currentServiceMaxPrice}" onblur="validatePriceRange()">
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="bi bi-info-circle me-1"></i>Price range: ₱${minPrice} - ₱${maxPrice}
                            </small>
                            <div id="priceValidationMessage" class="text-danger small mt-1" style="display: none;"></div>
                        </div>
                        
                        <div class="col-12">
                            <label for="justification" class="form-label fw-semibold text-dark mb-2 d-flex align-items-center">
                                <i class="bi bi-journal-text text-info me-2"></i>
                                Cost Justification
                            </label>
                            <textarea class="form-control border-0 shadow-sm" id="justification" rows="3" placeholder="Explain labor, parts, and additional charges..." style="resize: none;"></textarea>
                        </div>
                    </div>
                `;
            }
            
            const confirmationActionElement = document.getElementById('confirmationAction');
            const confirmationModalBodyElement = document.getElementById('confirmationModalBody');
            
            if (confirmationActionElement) {
                confirmationActionElement.textContent = actionText;
            }
            if (confirmationModalBodyElement) {
                confirmationModalBodyElement.innerHTML = messageText;
            }
            
            // Function to validate price range
            window.validatePriceRange = function() {
                const priceInput = document.getElementById('price');
                const validationMessage = document.getElementById('priceValidationMessage');
                
                if (!priceInput || !validationMessage) return;
                
                const enteredPrice = parseFloat(priceInput.value);
                
                if (isNaN(enteredPrice) || enteredPrice <= 0) {
                    validationMessage.style.display = 'none';
                    return;
                }
                
                if (enteredPrice < currentServiceMinPrice || enteredPrice > currentServiceMaxPrice) {
                    const minFormatted = new Intl.NumberFormat('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(currentServiceMinPrice);
                    const maxFormatted = new Intl.NumberFormat('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(currentServiceMaxPrice);
                    
                    validationMessage.innerHTML = `<i class="bi bi-exclamation-triangle me-1"></i>Price must be between ₱${minFormatted} and ₱${maxFormatted}`;
                    validationMessage.style.display = 'block';
                    priceInput.classList.add('is-invalid');
                } else {
                    validationMessage.style.display = 'none';
                    priceInput.classList.remove('is-invalid');
                }
            };
            
            const confirmModalElement = document.getElementById('confirmationModal');
            if (!confirmModalElement) {
                console.error('Confirmation modal element not found');
                return;
            }
            
            const confirmModal = new bootstrap.Modal(confirmModalElement);
            confirmModal.show();
            
            // Handle confirm button click with scope-safe variables
            const confirmActionButton = document.getElementById('confirmActionButton');
            if (confirmActionButton) {
                confirmActionButton.onclick = async function() {
                    let price = null;
                    let justification = '';
                    
                    if (action.toLowerCase() === 'complete') {
                        const priceInput = document.getElementById('price');
                        const justificationInput = document.getElementById('justification');
                        
                        if (priceInput) price = priceInput.value;
                        if (justificationInput) justification = justificationInput.value;
                        
                        if (!price || price <= 0) {
                            alert('Price is required for completed tasks');
                            return;
                        }
                        
                        if (!justification || justification.trim().length < 10) {
                            alert('Cost justification is required (minimum 10 characters)');
                            return;
                        }
                    }
                    
                    // Update appointment with comprehensive error handling
                    try {
                        const result = await updateAppointment(modalAppointmentId, action, price, justification);
                        
                        // Show success message
                        console.log('Update successful:', result.message);
                        
                        // Close confirmation modal
                        confirmModal.hide();
                        
                        // Close the appointment detail modal
                        const appointmentModal = bootstrap.Modal.getInstance(document.getElementById('appointmentModal'));
                        if (appointmentModal) {
                            appointmentModal.hide();
                        }
                        
                        // Clean up modal artifacts
                        setTimeout(() => {
                            document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                                backdrop.remove();
                            });
                            document.body.classList.remove('modal-open');
                            document.body.style.overflow = '';
                            document.body.style.paddingRight = '';
                        }, 100);
                        
                        // Show success message briefly before refresh
                        const successMessage = document.createElement('div');
                        successMessage.className = 'alert alert-success position-fixed';
                        successMessage.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                        successMessage.innerHTML = `
                            <i class="bi bi-check-circle-fill me-2"></i>
                            ${result.message || 'Appointment updated successfully'}
                        `;
                        document.body.appendChild(successMessage);
                        
                        // Refresh the page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                        
                    } catch (error) {
                        console.error('Update error:', error);
                        
                        // Show specific error message
                        let errorMessage = 'An error occurred while updating the appointment.';
                        if (error.message) {
                            errorMessage = error.message;
                        }
                        
                        // Create error alert
                        const errorAlert = document.createElement('div');
                        errorAlert.className = 'alert alert-danger position-fixed';
                        errorAlert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                        errorAlert.innerHTML = `
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Error:</strong> ${errorMessage}
                            <button type="button" class="btn-close float-end" onclick="this.parentElement.remove()"></button>
                        `;
                        document.body.appendChild(errorAlert);
                        
                        // Auto-remove error after 5 seconds
                        setTimeout(() => {
                            if (errorAlert.parentElement) {
                                errorAlert.remove();
                            }
                        }, 5000);
                    }
                };
            }
        }

        // Function to update appointment status
        async function updateAppointment(appointmentId, action, price, justification = '') {
            try {
                const formData = new FormData();
                formData.append('appointment_id', appointmentId);
                formData.append('action', action);
                
                if (price) {
                    formData.append('price', price);
                }
                
                if (justification) {
                    formData.append('justification', justification);
                }
                
                console.log('Sending request with data:', {
                    appointment_id: appointmentId,
                    action: action,
                    price: price || 'not provided',
                    justification: justification || 'not provided'
                });
                
                const response = await fetch('api/technician/update_app.php', {
                    method: 'POST',
                    body: formData
                });
                
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));
                
                // Check if the response is ok (status 200-299)
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('HTTP error response:', errorText);
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Get response text first to debug JSON parsing issues
                const responseText = await response.text();
                console.log('Raw response text:', responseText);
                
                // Try to parse JSON
                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (jsonError) {
                    console.error('JSON parsing error:', jsonError);
                    console.error('Response that failed to parse:', responseText);
                    throw new Error('Invalid JSON response from server. Check console for details.');
                }
                
                console.log('Parsed backend response:', result);
                
                // Check if the backend returned an error
                if (result.status === 'error') {
                    throw new Error(result.message || 'Unknown error from backend');
                }
                
                return result;
                
            } catch (error) {
                console.error('Error in updateAppointment:', error);
                throw error; // Re-throw to be handled by the calling function
            }
        }
        
        // ...existing code...
    </script>