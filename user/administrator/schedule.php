<?php
include_once __DIR__ . '/../../config/ini.php';
$pdo = pdo_init();

// Handle month/year filter from GET
$currentMonth = $_GET['month'] ?? date('n');
$currentYear = $_GET['year'] ?? date('Y');
$technicianFilter = $_GET['technician'] ?? 'All';
$statusFilter = $_GET['status'] ?? 'All';

// Calculate first and last day of the month
$firstDay = date('Y-m-01', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
$lastDay = date('Y-m-t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));

// Fetch appointments for the entire month
$query = "SELECT 
    a.app_id,
    a.app_schedule,
    a.app_price,
    a.app_status_id,
    a.app_desc,
    a.app_justification,
    a.decline_justification,
    COALESCE(s.service_type_name, 'Unknown Service') as service_type_name,
    COALESCE(cust.user_name, 'Unknown') as customer_fname,
    COALESCE(cust.user_midname, '') as customer_mname,
    COALESCE(cust.user_lastname, 'Customer') as customer_lname,
    cust.house_building_street as customer_house_building_street,
    COALESCE(ata.barangay, cust.barangay) as customer_barangay,
    COALESCE(ata.municipality_city, cust.municipality_city) as customer_municipality_city,
    COALESCE(ata.province, cust.province) as customer_province,
    COALESCE(ata.zip_code, cust.zip_code) as customer_zip_code,
    COALESCE(tech.user_name, 'Unknown') as technician_fname,
    COALESCE(tech.user_midname, '') as technician_mname,
    COALESCE(tech.user_lastname, 'Technician') as technician_lname,
    COALESCE(tech2.user_name, '') as technician2_fname,
    COALESCE(tech2.user_midname, '') as technician2_mname,
    COALESCE(tech2.user_lastname, '') as technician2_lname,
    COALESCE(ast.app_status_name, 'Unknown Status') as app_status_name,
    COALESCE(s.service_type_price_min, 0) as service_type_min_price,
    COALESCE(s.service_type_price_max, 0) as service_type_max_price
FROM 
    appointment a
LEFT JOIN 
    user cust ON a.user_id = cust.user_id
LEFT JOIN 
    appointment_transaction_address ata ON a.app_id = ata.app_id
LEFT JOIN 
    service_type s ON a.service_type_id = s.service_type_id
LEFT JOIN 
    user tech ON a.user_technician = tech.user_id
LEFT JOIN 
    user tech2 ON a.user_technician_2 = tech2.user_id
LEFT JOIN 
    appointment_status ast ON a.app_status_id = ast.app_status_id
WHERE 
    DATE(a.app_schedule) BETWEEN :firstDay AND :lastDay";

if ($technicianFilter !== 'All') {
    $query .= " AND (tech.user_id = :technicianFilter OR tech2.user_id = :technicianFilter)";
}

$query .= " GROUP BY a.app_id ORDER BY a.app_schedule ASC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':firstDay', $firstDay);
$stmt->bindParam(':lastDay', $lastDay);

if ($technicianFilter !== 'All') {
    $stmt->bindParam(':technicianFilter', $technicianFilter);
}

$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_OBJ);

// Organize appointments by date
$appointmentsByDate = [];
foreach ($appointments as $appointment) {
    $date = date('Y-m-d', strtotime($appointment->app_schedule));
    $appointmentsByDate[$date][] = $appointment;
}

// Get technician options for filter
$technicianQuery = $pdo->query("SELECT DISTINCT u.user_id, u.user_name FROM user u JOIN appointment a ON u.user_id = a.user_technician ORDER BY u.user_name");
$technicianOptions = $technicianQuery->fetchAll(PDO::FETCH_OBJ);

// Calendar helper functions
function getMonthName($month) {
    return date('F', mktime(0, 0, 0, $month, 1));
}

function getDaysInMonth($month, $year) {
    return date('t', mktime(0, 0, 0, $month, 1, $year));
}

function getFirstDayOfWeek($month, $year) {
    return date('w', mktime(0, 0, 0, $month, 1, $year));
}
?>

<style>
    /* Dashboard Consistent Styling */
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
        padding: 24px;
    }
    
    .dashboard-card-body .row {
        margin-left: -12px;
        margin-right: -12px;
    }
    
    .dashboard-card-body .row > [class*='col-'] {
        padding-left: 12px;
        padding-right: 12px;
    }

    /* Monthly Summary Styling */
    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }
    
    .summary-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .summary-icon i {
        font-size: 1.2rem;
        color: white;
    }
    
    .summary-content {
        flex: 1;
        min-width: 0;
    }
    
    .summary-number {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1.2;
        margin-bottom: 4px;
    }
    
    .summary-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
        line-height: 1.3;
    }

    .calendar-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .calendar-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 20px;
    }
    .calendar-nav {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
    }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: #dee2e6;
        border-radius: 0 0 12px 12px;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    .calendar-day-header {
        background: #495057;
        color: white;
        padding: 12px 8px;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .calendar-day {
        background: white;
        min-height: 120px;
        padding: 8px;
        position: relative;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }
    .calendar-day:hover {
        background: #f8f9fa;
    }
    .calendar-day.other-month {
        background: #f8f9fa;
        color: #6c757d;
    }
    .calendar-day.today {
        background: #e3f2fd;
        border: 2px solid #2196f3;
    }
    .day-number {
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }
    .appointment-item {
        background: #007bff;
        color: white;
        padding: 2px 6px;
        margin: 1px 0;
        border-radius: 3px;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s ease;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .appointment-item:hover {
        background: #0056b3;
        transform: scale(1.02);
    }
    /* Status-based color coding (ID fallback) */
    .appointment-item.status-1 { background: #ffc107; color: #000; } /* Pending - Yellow */
    .appointment-item.status-2 { background: #007bff; } /* Approved - Blue */
    .appointment-item.status-3 { background: #28a745; } /* Completed - Green */
    .appointment-item.status-4 { background: #dc3545; } /* Declined/Cancelled - Red */
    .appointment-item.status-5 { background: #fd7e14; } /* In Progress - Orange */
    
    /* Semantic status color coding (name-based) */
    .appointment-item.status-pending { background: #ffc107; color: #000; } /* Pending - Yellow */
    .appointment-item.status-approved { background: #007bff; } /* Approved - Blue */
    .appointment-item.status-completed { background: #28a745; } /* Completed - Green */
    .appointment-item.status-declined { background: #dc3545; } /* Declined/Cancelled - Red */
    .appointment-item.status-progress { background: #fd7e14; } /* In Progress - Orange */
    .appointment-count {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #007bff;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .filter-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .month-nav-btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .month-nav-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }
    .appointment-modal .modal-body {
        max-height: 600px;
        overflow-y: auto;
    }

    .appointment-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background: white;
        margin-bottom: 15px;
    }
    .appointment-header {
        background: #f8f9fa;
        padding: 12px 16px;
        border-bottom: 1px solid #e9ecef;
        border-radius: 8px 8px 0 0;
    }
    .appointment-body {
        padding: 16px;
    }
    .info-item {
        margin-bottom: 8px;
        font-size: 14px;
    }
    .info-item:last-child {
        margin-bottom: 0;
    }
    .additional-info {
        border-top: 1px solid #e9ecef;
        padding-top: 12px;
        margin-top: 12px;
    }
    .bg-orange {
        background-color: #fd7e14 !important;
        color: white !important;
    }

    /* ===== MOBILE RESPONSIVENESS ===== */
    
    /* Mobile-first approach - Base styles for mobile */
    @media (max-width: 991.98px) {
        /* Container adjustments - prevent horizontal overflow */
        .container-fluid {
            padding-left: 5px;
            padding-right: 5px;
            overflow-x: hidden;
            max-width: 100vw;
        }
        
        /* Ensure body doesn't overflow */
        body {
            overflow-x: hidden;
        }
        
        /* Header title */
        h3 {
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 1rem !important;
        }
        
        /* Filter section mobile optimization - 2x2 grid like Monthly Summary */
        .filter-section {
            padding: 10px;
            margin-bottom: 15px;
        }
        
        .filter-section .row {
            gap: 8px;
        }
        
        /* Force 2x2 grid layout on mobile - same as Monthly Summary */
        .filter-section .col-md-6 {
            flex: 0 0 calc(50% - 4px) !important;
            max-width: calc(50% - 4px) !important;
            margin-bottom: 8px !important;
        }
        
        /* Adjust form controls for mobile 2x2 grid layout */
        .filter-section .form-select {
            font-size: 0.85rem !important;
            padding: 6px 8px !important;
        }
        
        .filter-section .btn {
            font-size: 0.8rem !important;
            padding: 8px 6px !important;
            white-space: nowrap;
        }
        
        .filter-section .form-label {
            font-size: 0.8rem !important;
            margin-bottom: 3px !important;
        }
        
        /* Calendar header mobile optimization */
        .calendar-header {
            padding: 15px 10px;
            border-radius: 8px 8px 0 0;
        }
        
        .calendar-header h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }
        
        .calendar-header p {
            font-size: 0.9rem;
        }
        
        /* Navigation buttons mobile */
        .month-nav-btn {
            padding: 6px 10px;
            font-size: 0.85rem;
        }
        
        /* Calendar grid mobile optimization - Force proper sizing */
        .calendar-grid {
            border-radius: 0 0 8px 8px;
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        
        .calendar-day-header {
            padding: 4px 1px;
            font-size: 0.65rem;
            min-width: 0;
            overflow: hidden;
        }
        
        .calendar-day {
            min-height: 60px;
            padding: 1px;
            font-size: 0.7rem;
            min-width: 0;
            overflow: hidden;
        }
        
        /* Prevent horizontal scroll on mobile - Critical fixes */
        .calendar-container {
            overflow-x: hidden;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
            margin: 0;
        }
        
        /* Force calendar header to stay within bounds */
        .calendar-header {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow: hidden;
        }
        
        .day-number {
            font-size: 0.8rem;
            margin-bottom: 3px;
        }
        
        .appointment-item {
            font-size: 0.6rem;
            padding: 1px 4px;
            margin: 0.5px 0;
        }
        
        .appointment-count {
            width: 16px;
            height: 16px;
            font-size: 0.6rem;
            top: 2px;
            right: 2px;
        }
        
        /* Modal adjustments for mobile */
        .modal-dialog {
            margin: 10px;
            max-width: calc(100% - 20px);
        }
        
        .appointment-modal .modal-body {
            max-height: 70vh;
            padding: 15px;
        }
        
        .appointment-card {
            margin-bottom: 10px;
        }
        
        .appointment-header {
            padding: 10px 12px;
        }
        
        .appointment-body {
            padding: 12px;
        }
        
        .appointment-body .row {
            margin: 0;
        }
        
        .appointment-body .col-md-6 {
            padding: 0;
            margin-bottom: 10px;
        }
        
        .info-item {
            font-size: 13px;
            margin-bottom: 6px;
        }
    }
    
    /* Tablet adjustments */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .calendar-day {
            min-height: 100px;
            padding: 6px;
        }
        
        .appointment-item {
            font-size: 0.65rem;
        }
        
        .calendar-header {
            padding: 18px 15px;
        }
    }
    
    /* Small mobile devices */
    @media (max-width: 575.98px) {
        .container-fluid {
            padding-left: 5px;
            padding-right: 5px;
        }
        
        h3 {
            font-size: 1.3rem;
        }
        
        .filter-section {
            padding: 8px;
        }
        
        .calendar-header {
            padding: 10px 8px;
        }
        
        .calendar-header h3 {
            font-size: 1.1rem;
        }
        
        .calendar-header p {
            font-size: 0.8rem;
        }
        
        .month-nav-btn {
            padding: 4px 8px;
            font-size: 0.8rem;
        }
        
        .calendar-day {
            min-height: 60px;
            padding: 2px;
        }
        
        .calendar-day-header {
            padding: 6px 2px;
            font-size: 0.7rem;
        }
        
        .day-number {
            font-size: 0.75rem;
        }
        
        .appointment-item {
            font-size: 0.55rem;
            padding: 1px 3px;
        }
        
        .appointment-count {
            width: 14px;
            height: 14px;
            font-size: 0.55rem;
        }
        
        /* Mobile navigation buttons - completely override Bootstrap */
        .calendar-header .row {
            display: flex !important;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap;
            width: 100%;
            margin: 0 !important;
            padding: 0 8px;
        }
        
        .calendar-header .col-md-4 {
            padding: 0 !important;
            margin: 0 !important;
            flex-basis: auto !important;
            max-width: none !important;
            width: auto !important;
        }
        
        .calendar-header .col-md-4:first-child {
            flex: 0 0 auto;
            text-align: left;
        }
        
        .calendar-header .col-md-4:nth-child(2) {
            flex: 1;
            text-align: center;
            padding: 0 8px !important;
            min-width: 0;
        }
        
        .calendar-header .col-md-4:last-child {
            flex: 0 0 auto;
            text-align: right;
        }
        
        /* Style navigation buttons to match the image - smaller for mobile */
        .calendar-header .month-nav-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .calendar-header .month-nav-btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        /* Adjust title styling for mobile */
        .calendar-header h3 {
            font-size: 1.2rem !important;
            margin-bottom: 0.25rem;
            font-weight: 600;
            display: block !important;
            visibility: visible !important;
        }
        
        .calendar-header p {
            font-size: 0.8rem !important;
            margin-bottom: 0;
            opacity: 0.9;
            display: block !important;
            visibility: visible !important;
        }
        
        /* Force all navigation elements to be visible and functional */
        .calendar-header .col-md-4 {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }
        
        .calendar-header .month-nav-btn {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
            cursor: pointer !important;
            text-decoration: none !important;
            position: relative;
            z-index: 10;
        }
        
        /* Ensure buttons are touch-friendly and functional */
        .calendar-header .month-nav-btn:hover,
        .calendar-header .month-nav-btn:focus,
        .calendar-header .month-nav-btn:active {
            background: rgba(255,255,255,0.3) !important;
            color: white !important;
            text-decoration: none !important;
        }
        
        /* Mobile touch improvements for navigation buttons */
        .calendar-header .month-nav-btn {
            min-height: 44px;
            min-width: 44px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            touch-action: manipulation;
            position: relative;
            z-index: 100;
            overflow: visible;
        }
        
        /* Ensure navigation buttons are clickable on mobile */
        .calendar-header .col-md-4 a {
            pointer-events: auto !important;
            position: relative;
            z-index: 100;
        }
    }
    
    /* Hide body scrollbar when modal is open to prevent double scrollbars */
    body.modal-open {
        overflow: hidden !important;
    }
    
    /* Extra small screens - Stack filter controls */
    @media (max-width: 480px) {
        .filter-section .col-md-3 {
            width: 100%;
            margin-bottom: 8px;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 5px;
        }
        
        /* Further optimize calendar for very small screens */
        .calendar-day {
            min-height: 50px;
            padding: 1px;
        }
        
        .calendar-day-header {
            padding: 4px 1px;
            font-size: 0.65rem;
        }
        
        .day-number {
            font-size: 0.7rem;
        }
        
        .appointment-item {
            font-size: 0.5rem;
            padding: 0px 2px;
        }
    }
    
    /* Landscape mobile optimization */
    @media (max-width: 991.98px) and (orientation: landscape) {
        .calendar-day {
            min-height: 70px;
        }
        
        .modal-dialog {
            max-width: 90%;
        }
        
        .appointment-modal .modal-body {
            max-height: 60vh;
        }
    }
    
    /* Touch-friendly improvements */
    @media (max-width: 991.98px) {
        .appointment-item {
            min-height: 20px;
            display: flex;
            align-items: center;
        }
        
        .month-nav-btn,
        .btn {
            min-height: 44px;
            touch-action: manipulation;
        }
        
        .form-select {
            flex: 1;
            margin-bottom: 10px;
        }
        
        .filter-section .row > div:last-child {
            flex: 0 0 auto;
            max-width: 200px;
        }
    }
    
    /* Mobile Summary Section - Responsive Layout */
    @media (max-width: 991.98px) {
        .summary-card {
            padding: 15px;
            gap: 12px;
        }
        
        .summary-icon {
            width: 50px;
            height: 50px;
        }
        
        .summary-icon i {
            font-size: 1.2rem;
        }
        
        .summary-number {
            font-size: 1.5rem;
        }
        
        .summary-label {
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 767.98px) {
        .summary-card {
            padding: 12px;
            gap: 8px;
            flex-direction: column;
            text-align: center;
        }
        
        .summary-icon {
            width: 40px;
            height: 40px;
        }
        
        .summary-icon i {
            font-size: 1rem;
        }
        
        .summary-number {
            font-size: 1.2rem;
        }
        
        .summary-label {
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 575.98px) {
        .summary-card {
            padding: 10px;
            gap: 6px;
        }
        
        .summary-icon {
            width: 35px;
            height: 35px;
        }
        
        .summary-icon i {
            font-size: 0.9rem;
        }
        
        .summary-number {
            font-size: 1.1rem;
        }
        
        .summary-label {
            font-size: 0.7rem;
        }
    }
    
    /* Desktop Filter Section - Horizontal Layout */
    @media (min-width: 768px) {
        .filter-section .row {
            display: flex;
            flex-direction: row;
            align-items: end;
        }
        
        .filter-section .row > div {
            flex: 1;
            margin-bottom: 1rem;
        }
        
        .filter-section .row > div:last-child {
            flex: 0 0 auto;
            max-width: 200px;
        }
    }
</style>

<div class="container-fluid mt-3 mb-3">
<h3 style=" text-align:left;"><?= ucfirst($_GET['page']) ?></h3>
    <!-- Filters and Search -->
    <div class="dashboard-card mb-4">
        <div class="dashboard-card-header">
            <i class="bi bi-funnel me-2"></i>Filter Schedule
        </div>
        <div class="dashboard-card-body py-3">
            <form method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="page" value="schedule">
                
                <!-- Desktop: Horizontal Layout, Mobile: 2x2 Grid -->
                <div class="col-lg-3 col-md-6 col-6">
                    <label class="form-label small text-muted mb-1">Month</label>
                    <select name="month" class="form-select" onchange="this.form.submit()">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $currentMonth == $m ? 'selected' : '' ?>>
                                <?= getMonthName($m) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="col-lg-3 col-md-6 col-6">
                    <label class="form-label small text-muted mb-1">Year</label>
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        <?php for ($y = date('Y') - 1; $y <= date('Y') + 2; $y++): ?>
                            <option value="<?= $y ?>" <?= $currentYear == $y ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="col-lg-4 col-md-6 col-6">
                    <label class="form-label small text-muted mb-1">Filter by Technician</label>
                    <select name="technician" class="form-select" onchange="this.form.submit()">
                        <option value="All" <?= $technicianFilter === 'All' ? 'selected' : '' ?>>All Technicians</option>
                        <?php foreach ($technicianOptions as $technician): ?>
                            <option value="<?= $technician->user_id ?>" <?= $technicianFilter == $technician->user_id ? 'selected' : '' ?>>
                                <?= $technician->user_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-lg-2 col-md-6 col-6">
                    <label class="form-label small text-muted mb-1">&nbsp;</label>
                    <button type="button" class="btn btn-primary w-100" onclick="location.href='index.php?page=schedule&month=<?= date('n') ?>&year=<?= date('Y') ?>&status=All'">
                        <i class="bi bi-arrow-clockwise me-1"></i>This Month
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="calendar-container">
        <!-- Calendar Header -->
        <div class="calendar-header">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <?php
                    $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
                    $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
                    $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
                    $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;
                    ?>
                    <a href="index.php?page=schedule&month=<?= $prevMonth ?>&year=<?= $prevYear ?>&status=<?= $statusFilter ?>" 
                       class="btn month-nav-btn me-2">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                </div>
                <div class="col-md-4 text-center">
                    <h3 class="mb-1"><?= getMonthName($currentMonth) ?> <?= $currentYear ?></h3>
                    <p class="mb-0">Monthly Schedule View</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="index.php?page=schedule&month=<?= $nextMonth ?>&year=<?= $nextYear ?>&status=<?= $statusFilter ?>" 
                       class="btn month-nav-btn">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="calendar-grid">
            <!-- Day Headers -->
            <div class="calendar-day-header">Sun</div>
            <div class="calendar-day-header">Mon</div>
            <div class="calendar-day-header">Tue</div>
            <div class="calendar-day-header">Wed</div>
            <div class="calendar-day-header">Thu</div>
            <div class="calendar-day-header">Fri</div>
            <div class="calendar-day-header">Sat</div>
            
            <?php
            $daysInMonth = getDaysInMonth($currentMonth, $currentYear);
            $firstDayOfWeek = getFirstDayOfWeek($currentMonth, $currentYear);
            $today = date('Y-m-d');
            
            // Previous month's trailing days
            $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
            $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
            $daysInPrevMonth = getDaysInMonth($prevMonth, $prevYear);
            
            for ($i = $firstDayOfWeek - 1; $i >= 0; $i--) {
                $day = $daysInPrevMonth - $i;
                $date = sprintf('%04d-%02d-%02d', $prevYear, $prevMonth, $day);
                echo '<div class="calendar-day other-month">';
                echo '<div class="day-number">' . $day . '</div>';
                echo '</div>';
            }
            
            // Current month days
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                $isToday = $date === $today;
                $dayAppointments = $appointmentsByDate[$date] ?? [];
                
                echo '<div class="calendar-day' . ($isToday ? ' today' : '') . '" data-date="' . $date . '">';
                echo '<div class="day-number">' . $day . '</div>';
                
                if (!empty($dayAppointments)) {
                    $count = count($dayAppointments);
                    if ($count > 3) {
                        echo '<div class="appointment-count">' . $count . '</div>';
                    }
                    
                    $displayCount = min(3, $count);
                    for ($i = 0; $i < $displayCount; $i++) {
                        $appointment = $dayAppointments[$i];
                        $time = date('g:i A', strtotime($appointment->app_schedule));
                        
                        // Determine status class using the same logic as modal
                        $statusClass = 'appointment-item';
                        $statusName = strtolower($appointment->app_status_name ?? '');
                        
                        if (strpos($statusName, 'pending') !== false) {
                            $statusClass .= ' status-pending'; // Pending - Yellow
                        } elseif (strpos($statusName, 'approved') !== false || strpos($statusName, 'approval') !== false) {
                            $statusClass .= ' status-approved'; // Approved - Blue
                        } elseif (strpos($statusName, 'completed') !== false || strpos($statusName, 'complete') !== false) {
                            $statusClass .= ' status-completed'; // Completed - Green
                        } elseif (strpos($statusName, 'declined') !== false || strpos($statusName, 'cancelled') !== false || strpos($statusName, 'cancel') !== false) {
                            $statusClass .= ' status-declined'; // Declined/Cancelled - Red
                        } elseif (strpos($statusName, 'progress') !== false || strpos($statusName, 'ongoing') !== false) {
                            $statusClass .= ' status-progress'; // In Progress - Orange
                        } else {
                            // Fallback to ID-based mapping
                            $statusClass .= ' status-' . $appointment->app_status_id;
                        }
                        
                        echo '<div class="' . $statusClass . '" 
                                   data-bs-toggle="modal" data-bs-target="#appointmentModal" 
                                   data-date="' . $date . '">';
                        echo $time . ' - ' . $appointment->customer_fname;
                        echo '</div>';
                    }
                    
                    if ($count > 3) {
                        echo '<div class="appointment-item" style="background: #6c757d;" 
                                   data-bs-toggle="modal" data-bs-target="#appointmentModal" 
                                   data-date="' . $date . '">';
                        echo '+' . ($count - 3) . ' more';
                        echo '</div>';
                    }
                }
                
                echo '</div>';
            }
            
            // Next month's leading days
            $totalCells = $firstDayOfWeek + $daysInMonth;
            $remainingCells = 42 - $totalCells; // 6 rows × 7 days = 42 cells
            $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
            $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;
            
            for ($day = 1; $day <= $remainingCells; $day++) {
                $date = sprintf('%04d-%02d-%02d', $nextYear, $nextMonth, $day);
                echo '<div class="calendar-day other-month">';
                echo '<div class="day-number">' . $day . '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    
    <!-- Monthly Summary Section -->
    <div class="dashboard-card mt-4">
        <div class="dashboard-card-header">
            <i class="bi bi-bar-chart me-2"></i>Monthly Summary - <?= getMonthName($currentMonth) ?> <?= $currentYear ?>
        </div>
        <div class="dashboard-card-body">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="summary-card">
                        <div class="summary-icon bg-primary">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-number"><?= count($appointments) ?></div>
                            <div class="summary-label">Total Appointments</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="summary-card">
                        <div class="summary-icon bg-success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-number"><?= count(array_filter($appointments, fn($a) => $a->app_status_id == 3)) ?></div>
                            <div class="summary-label">Completed</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="summary-card">
                        <div class="summary-icon bg-warning">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-number"><?= count(array_filter($appointments, fn($a) => $a->app_status_id == 1)) ?></div>
                            <div class="summary-label">Pending</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="summary-card">
                        <div class="summary-icon bg-info">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-number">₱<?= number_format(array_sum(array_map(fn($a) => $a->app_price ?? 0, $appointments)), 2) ?></div>
                            <div class="summary-label">Expected Revenue</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Appointment Details Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0056b3; color: white">
                <h5 class="modal-title" id="appointmentModalLabel">
                    <i class="bi bi-calendar3 me-2 text-white"></i>Appointments for <span id="modalDate"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Appointment details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Store appointments data for JavaScript access
const appointmentsData = <?= json_encode($appointmentsByDate) ?>;

// Handle appointment modal
document.addEventListener('DOMContentLoaded', function() {
    const appointmentModal = document.getElementById('appointmentModal');
    const modalDate = document.getElementById('modalDate');
    const modalBody = document.getElementById('modalBody');
    let modalInstance = null;
    
    // Initialize modal instance
    modalInstance = new bootstrap.Modal(appointmentModal, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    
    // Handle modal events to prevent backdrop issues
    appointmentModal.addEventListener('hidden.bs.modal', function() {
        // Remove any remaining backdrops
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // Restore body scroll
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    });
    
    // Handle appointment item clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('appointment-item') || e.target.closest('.appointment-item')) {
            e.preventDefault();
            e.stopPropagation();
            
            const appointmentItem = e.target.classList.contains('appointment-item') ? e.target : e.target.closest('.appointment-item');
            const date = appointmentItem.getAttribute('data-date');
            
            if (date && appointmentsData[date]) {
                showAppointmentsForDate(date);
                modalInstance.show();
            }
        }
    });
    
    // Handle calendar day clicks (for days with appointments)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('calendar-day') || e.target.closest('.calendar-day')) {
            // Only show modal if clicking on the day itself, not on appointment items
            if (!e.target.classList.contains('appointment-item') && !e.target.closest('.appointment-item')) {
                const calendarDay = e.target.classList.contains('calendar-day') ? e.target : e.target.closest('.calendar-day');
                const date = calendarDay.getAttribute('data-date');
                
                if (date && appointmentsData[date] && appointmentsData[date].length > 0) {
                    showAppointmentsForDate(date);
                    modalInstance.show();
                }
            }
        }
    });
    
    function showAppointmentsForDate(date) {
        const appointments = appointmentsData[date] || [];
        const formattedDate = new Date(date + 'T00:00:00').toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        modalDate.textContent = formattedDate;
        
        if (appointments.length === 0) {
            modalBody.innerHTML = '<p class="text-muted text-center">No appointments scheduled for this date.</p>';
            return;
        }
        
        // Sort appointments by status priority: Pending, In Progress, Approved, Completed, Cancelled
        const statusOrder = {
            1: 1, // Pending
            5: 2, // In Progress
            2: 3, // Approved
            3: 4, // Completed
            4: 5  // Cancelled/Declined
        };
        
        const sortedAppointments = appointments.sort((a, b) => {
            const orderA = statusOrder[parseInt(a.app_status_id)] || 999;
            const orderB = statusOrder[parseInt(b.app_status_id)] || 999;
            
            // If same status, sort by time
            if (orderA === orderB) {
                return a.app_schedule.localeCompare(b.app_schedule);
            }
            
            return orderA - orderB;
        });
        
        let html = '';
        sortedAppointments.forEach(function(appointment, index) {
            const time = new Date('2000-01-01T' + appointment.app_schedule.split(' ')[1]).toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            
            let statusClass = 'bg-secondary';
            const statusName = appointment.app_status_name ? appointment.app_status_name.toLowerCase() : '';
            
            // Map by status name to ensure consistency
            if (statusName.includes('pending')) {
                statusClass = 'bg-warning text-dark'; // Pending - Yellow
            } else if (statusName.includes('approved') || statusName.includes('approval')) {
                statusClass = 'bg-primary'; // Approved - Blue
            } else if (statusName.includes('completed') || statusName.includes('complete')) {
                statusClass = 'bg-success'; // Completed - Green
            } else if (statusName.includes('declined') || statusName.includes('cancelled') || statusName.includes('cancel')) {
                statusClass = 'bg-danger'; // Declined/Cancelled - Red
            } else if (statusName.includes('progress') || statusName.includes('ongoing')) {
                statusClass = 'bg-orange'; // In Progress - Orange
            } else {
                // Fallback to ID-based mapping if name matching fails
                switch (parseInt(appointment.app_status_id)) {
                    case 1: statusClass = 'bg-warning text-dark'; break; // Pending - Yellow
                    case 2: statusClass = 'bg-primary'; break; // Approved - Blue
                    case 3: statusClass = 'bg-success'; break; // Completed - Green
                    case 4: statusClass = 'bg-danger'; break; // Declined/Cancelled - Red
                    case 5: statusClass = 'bg-orange'; break; // In Progress - Orange
                }
            }
            
            html += `
                <div class="appointment-card">
                    <div class="appointment-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock me-2 text-primary"></i>
                                <strong>${time}</strong>
                                <span class="badge ${statusClass} ms-2">${appointment.app_status_name}</span>
                            </div>
                            <small class="text-muted">ID: ${appointment.app_id}</small>
                        </div>
                    </div>
                    <div class="appointment-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-person me-2 text-primary"></i>
                                    <strong>${appointment.customer_fname} ${appointment.customer_mname || ''} ${appointment.customer_lname}</strong>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                                    <span>${(() => {
                                        const addressParts = [
                                            appointment.customer_house_building_street,
                                            appointment.customer_barangay,
                                            appointment.customer_municipality_city,
                                            appointment.customer_province,
                                            appointment.customer_zip_code
                                        ].filter(part => part && part.trim() !== '');
                                        return addressParts.length > 0 ? addressParts.join(', ') : 'No address provided';
                                    })()}</span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-tools me-2 text-primary"></i>
                                    <span>${appointment.service_type_name}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                ${appointment.technician_fname ? 
                                    `<div class="info-item">
                                        <i class="bi bi-person-gear me-2 text-primary"></i>
                                        <span>Primary: ${appointment.technician_fname} ${appointment.technician_lname || ''}</span>
                                    </div>` : 
                                    `<div class="info-item text-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <span>No primary technician assigned</span>
                                    </div>`
                                }
                                ${appointment.technician2_fname && appointment.technician2_fname.trim() !== '' ? 
                                    `<div class="info-item">
                                        <i class="bi bi-person-plus me-2 text-primary"></i>
                                        <span>Secondary: ${appointment.technician2_fname} ${appointment.technician2_lname || ''}</span>
                                    </div>` : ''
                                }
                                ${(() => {
                                    // Show actual price for completed appointments, expected range for others
                                    if (appointment.app_status_id == 3 && appointment.app_price && appointment.app_price > 0) {
                                        return `<div class="info-item">
                                            <i class="bi bi-currency-exchange me-2 text-primary"></i>
                                            <span>₱${parseFloat(appointment.app_price).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                                        </div>`;
                                    } else if (appointment.service_type_min_price && appointment.service_type_max_price) {
                                        const minPrice = parseFloat(appointment.service_type_min_price);
                                        const maxPrice = parseFloat(appointment.service_type_max_price);
                                        if (minPrice > 0 && maxPrice > 0) {
                                            return `<div class="info-item">
                                                <i class="bi bi-currency-exchange me-2 text-primary"></i>
                                                <span>₱${minPrice.toLocaleString('en-US', {minimumFractionDigits: 2})} - ₱${maxPrice.toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                                            </div>`;
                                        }
                                    }
                                    return '';
                                })()}
                            </div>
                        </div>
                        ${appointment.app_desc || appointment.decline_justification || appointment.app_justification ? 
                            `<div class="additional-info">
                                ${appointment.app_desc ? 
                                    `<div class="info-item">
                                        <i class="bi bi-chat-text me-2 text-primary"></i>
                                        <span><strong>Description:</strong> ${appointment.app_desc}</span>
                                    </div>` : ''
                                }
                                ${appointment.decline_justification && (appointment.app_status_name.toLowerCase().includes('declined') || appointment.app_status_name.toLowerCase().includes('cancelled')) ? 
                                    `<div class="info-item">
                                        <i class="bi bi-x-circle me-2 text-danger"></i>
                                        <span><strong>Decline Reason:</strong> ${appointment.decline_justification}</span>
                                    </div>` : ''
                                }
                                ${appointment.app_justification && appointment.app_status_id == 3 ? 
                                    `<div class="info-item">
                                        <i class="bi bi-journal-text me-2 text-primary"></i>
                                        <span><strong>Cost Justification:</strong> ${appointment.app_justification}</span>
                                    </div>` : ''
                                }
                            </div>` : ''
                        }
                    </div>
                </div>
            `;
        });
        
        modalBody.innerHTML = html;
    }
});

// Auto-refresh every 10 minutes
setTimeout(function() {
    location.reload();
}, 600000);

// Print functionality
function printCalendar() {
    window.print();
}
</script>

<style media="print">
    .filter-section, .btn, .no-print {
        display: none !important;
    }
    .calendar-container {
        break-inside: avoid;
    }
    .calendar-header {
        background: #007bff !important;
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
    .appointment-item {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
</style>
