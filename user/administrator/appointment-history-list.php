

<?php
$tech_id = intval($_GET['tech-history'] ?? 0);
$tech = $pdo->prepare("SELECT * FROM user WHERE user_id = ?");
$tech->execute([$tech_id]);
$tech = ($row = $tech->fetch(PDO::FETCH_OBJ)) ? $row->user_name . " " . $row->user_lastname : 'Unknown';
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

    .round_md {
        border-radius: 8px !important;
    }
    
    /* Mobile Filter Improvements */
    @media (max-width: 768px) {
        .dashboard-card-body {
            padding: 16px;
        }
        
        .dashboard-card-header {
            padding: 10px 16px;
            font-size: 1rem;
        }
        
        /* Mobile Filter Layout */
        .mobile-filter-row {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .mobile-filter-dropdowns {
            display: flex;
            gap: 8px;
        }
        
        .mobile-filter-dropdowns .form-select {
            flex: 1;
            min-width: 0;
            font-size: 0.9rem;
        }
        
        .mobile-search-container {
            width: 100%;
        }
        
        .mobile-search-container .input-group {
            max-width: 100% !important;
        }
        
        .mobile-search-container .form-control {
            font-size: 0.9rem;
            font-weight: normal;
        }
        
        .mobile-search-container .form-control::placeholder {
            font-weight: normal;
        }
        
        /* Hide desktop layout on mobile */
        .desktop-filter-row {
            display: none;
        }
    }
    
    /* Desktop Filter Layout */
    @media (min-width: 769px) {
        .mobile-filter-row {
            display: none;
        }
    }
</style>

<script>
// Back button functionality for mobile history view
function goBackToUserManagement() {
    // Navigate back to user management page
    window.location.href = '?page=user';
}
</script>

<!-- Mobile Back Button -->
<div class="d-lg-none mb-3">
    <button type="button" class="btn btn-outline-secondary" onclick="goBackToUserManagement()">
        <i class="bi bi-arrow-left me-1"></i>Back to Users
    </button>
</div>

<nav class=" " style="--bs-breadcrumb-divider: '•';" aria-label="breadcrumb">
    <ol class="breadcrumb  p-0 m-0">
        <li class="breadcrumb-item h3 m-0 active text-dark" aria-current="page"><?=$tech?>'s History</li>
    </ol>
</nav>

<!-- Filter Section -->
<div class="dashboard-card mb-4" style="border-radius: 12px; margin-top: 15px;">
    <div class="dashboard-card-header">
        <i class="bi bi-funnel me-2"></i>Filter Appointments
    </div>
    <div class="dashboard-card-body py-3">

    <?php
    $pdo = pdo_init();
    $sql = "
        SELECT
            a.*,
            a_s.app_status_name,
            u.user_name, u.user_midname, u.user_lastname, 
            COALESCE(ata.full_address, u.house_building_street) as house_building_street,
            COALESCE(ata.barangay, u.barangay) as barangay,
            COALESCE(ata.municipality_city, u.municipality_city) as municipality_city,
            COALESCE(ata.province, u.province) as province,
            COALESCE(ata.zip_code, u.zip_code) as zip_code,
            u.user_contact,
            ut.user_name AS tech_name,
            ut.user_midname AS tech_midname,
            ut.user_lastname AS tech_lastname,
            ut2.user_name AS tech2_name,
            ut2.user_midname AS tech2_midname,
            ut2.user_lastname AS tech2_lastname,
            s.service_type_name,
            COALESCE(at.appliances_type_name, 'Not Specified') AS appliances_type_name,
            CASE 
                WHEN a.user_technician = :tech_id THEN 'Primary'
                WHEN a.user_technician_2 = :tech_id2 THEN 'Secondary'
                ELSE 'Unknown'
            END AS technician_role
        FROM
            appointment a
        JOIN appointment_status a_s ON a.app_status_id = a_s.app_status_id
        JOIN user u ON a.user_id = u.user_id
        LEFT JOIN appointment_transaction_address ata ON a.app_id = ata.app_id
        JOIN user ut ON a.user_technician = ut.user_id
        LEFT JOIN user ut2 ON a.user_technician_2 = ut2.user_id
        JOIN service_type s ON a.service_type_id = s.service_type_id
        LEFT JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        WHERE a.app_status_id = 3 AND (a.user_technician = :tech_id OR a.user_technician_2 = :tech_id2)
        ORDER BY a.app_id DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tech_id', $tech_id, PDO::PARAM_INT);
    $stmt->bindParam(':tech_id2', $tech_id, PDO::PARAM_INT);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Service type colors for badges
    $serviceTypeColors = [
        'Repair' => 'alert alert-danger ',
        'Maintenance' => 'alert alert-warning ',
        'Installation' => 'alert alert-success ',
    ];
    $defaultClass = 'bg-secondary text-white';
    
    // Fetch unique service types from the appointments for this technician
    $serviceTypesQuery = $pdo->prepare("
        SELECT DISTINCT s.service_type_name
        FROM appointment a
        JOIN service_type s ON a.service_type_id = s.service_type_id
        WHERE a.app_status_id = 3 AND (a.user_technician = :tech_id OR a.user_technician_2 = :tech_id2)
        ORDER BY s.service_type_name ASC
    ");
    $serviceTypesQuery->bindParam(':tech_id', $tech_id, PDO::PARAM_INT);
    $serviceTypesQuery->bindParam(':tech_id2', $tech_id, PDO::PARAM_INT);
    $serviceTypesQuery->execute();
    $serviceTypes = $serviceTypesQuery->fetchAll(PDO::FETCH_COLUMN);
    ?>

        <!-- Desktop Filter Layout -->
        <div class="row desktop-filter-row">
            <div class="col-md-6 d-flex gap-3">
                <select class="form-select bg-light" id="serviceTypeFilter" style="width: auto; min-width: 150px;">
                    <option value="All">All Service Types</option>
                    <?php foreach ($serviceTypes as $serviceType): ?>
                        <option value="<?= htmlspecialchars($serviceType) ?>"><?= htmlspecialchars($serviceType) ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-select bg-light" id="roleFilter" style="width: auto; min-width: 120px;">
                    <option value="All">All Roles</option>
                    <option value="Primary">Primary</option>
                    <option value="Secondary">Secondary</option>
                </select>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="customerNameSearch" class="form-control" placeholder="Search customer name..." style="font-weight: normal !important;">
                </div>
            </div>
        </div>
        
        <!-- Mobile Filter Layout -->
        <div class="mobile-filter-row">
            <div class="mobile-filter-dropdowns">
                <select class="form-select bg-light" id="serviceTypeFilterMobile">
                    <option value="All">All Service Types</option>
                    <?php foreach ($serviceTypes as $serviceType): ?>
                        <option value="<?= htmlspecialchars($serviceType) ?>"><?= htmlspecialchars($serviceType) ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-select bg-light" id="roleFilterMobile">
                    <option value="All">All Roles</option>
                    <option value="Primary">Primary</option>
                    <option value="Secondary">Secondary</option>
                </select>
            </div>
            <div class="mobile-search-container">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="customerNameSearchMobile" class="form-control" placeholder="Search customer name..." style="font-weight: normal !important;">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Container -->
<div class="dashboard-card">
    <div class="dashboard-card-body">
    <style>
        .table-responsive-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Mobile appointment card styling */
        .mobile-appointment-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .mobile-appointment-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            transform: translateY(-1px);
        }
        
        .mobile-appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        
        .mobile-appointment-customer {
            font-weight: 600;
            font-size: 1.1rem;
            color: #2c3e50;
            margin: 0;
            line-height: 1.3;
        }
        
        .mobile-appointment-role {
            flex-shrink: 0;
            margin-left: 12px;
        }
        
        .mobile-appointment-details {
            margin-bottom: 12px;
        }
        
        .mobile-appointment-contact {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }
        
        .mobile-appointment-service {
            color: #495057;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 4px;
        }
        
        .mobile-appointment-date {
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        .mobile-appointment-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        
        .mobile-appointment-actions .btn {
            flex: none;
            min-width: 80px;
            font-size: 0.85rem;
            padding: 6px 12px;
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            /* Mobile Filter Layout */
            .row.mb-3 .col-md-6 {
                width: 100% !important;
                margin-bottom: 15px;
                display: block !important;
            }
            
            .row.mb-3 .col-md-6:last-child {
                margin-bottom: 0;
                justify-content: flex-start !important;
            }
            
            /* Mobile Filter Controls */
            .row.mb-3 .col-md-6 .form-select {
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
                margin-bottom: 10px;
            }
            
            .row.mb-3 .col-md-6 .input-group {
                max-width: 100% !important;
                width: 100% !important;
            }
            
            /* Stack filters vertically */
            .row.mb-3 .col-md-6.d-flex {
                flex-direction: column !important;
                gap: 10px !important;
            }
        }
        .fixed-table {
            table-layout: fixed;
            width: 100%;
            min-width: 800px; /* Minimum width for mobile horizontal scroll */
        }
        .fixed-table th,
        .fixed-table td {
            padding: 12px 8px;
            text-align: left;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Ensure ellipsis works for nested elements */
        .fixed-table td div,
        .fixed-table td span {
            white-space: nowrap;
        }
        .fixed-table th:nth-child(1),
        .fixed-table td:nth-child(1) {
            width: 25%; /* Customer - increased */
        }
        .fixed-table th:nth-child(2),
        .fixed-table td:nth-child(2) {
            width: 18%; /* Contact - increased */
        }
        .fixed-table th:nth-child(3),
        .fixed-table td:nth-child(3) {
            width: 20%; /* Service Type - increased */
        }
        .fixed-table th:nth-child(4),
        .fixed-table td:nth-child(4) {
            width: 12%; /* Role - kept same */
            text-align: center; /* Center align Role column */
        }
        .fixed-table th:nth-child(5),
        .fixed-table td:nth-child(5) {
            width: 12%; /* Date - reduced to minimum needed */
            white-space: nowrap;
            overflow: visible;
            text-overflow: unset;
            text-align: center; /* Center align Date column */
        }
        .fixed-table th:nth-child(6),
        .fixed-table td:nth-child(6) {
            width: 13%; /* Actions - slightly reduced */
            text-align: center; /* Center align Actions column */
        }
        
        /* Enhanced table styling to match dashboard design */
        .table thead th {
            background: #f8f9fa;
            color: #495057;
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
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Special handling for customer name div */
        .fixed-table td div {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }
        
        /* Ensure service type spans don't break layout */
        .fixed-table td .text-dark {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }
        
        /* Role badges should not truncate but fit within column */
        .fixed-table td .badge {
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            .fixed-table {
                min-width: 750px;
                font-size: 14px;
            }
            .fixed-table th,
            .fixed-table td {
                padding: 8px 4px;
            }
            .btn {
                padding: 6px 10px;
                font-size: 12px;
                margin: 0 2px;
                min-width: 35px;
            }
            .badge {
                font-size: 10px;
                padding: 4px 6px;
            }
        }
        
        @media (max-width: 480px) {
            .fixed-table {
                min-width: 650px;
                font-size: 12px;
            }
            .fixed-table th,
            .fixed-table td {
                padding: 6px 3px;
            }
            .btn {
                padding: 5px 8px;
                font-size: 11px;
                margin: 0 1px;
                min-width: 32px;
            }
            .badge {
                font-size: 9px;
                padding: 3px 5px;
            }
        }
    </style>
    <!-- Desktop Table View -->
    <div class="d-none d-lg-block">
        <div class="table-responsive-container">
            <table class="table table-hover fixed-table">
                <thead>
                    <tr>
                        <th scope="col">Customer</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Service Type</th>
                        <th scope="col">Role</th>
                        <th scope="col">Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                No completed appointments found for this technician.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr class="appointment-row" data-service-type="<?= htmlspecialchars($appointment['service_type_name']) ?>" data-role="<?= htmlspecialchars($appointment['technician_role']) ?>" data-appointment-id="<?= $appointment['app_id'] ?>">
                                <td title="<?= htmlspecialchars($appointment['user_name'] . ' ' . $appointment['user_midname'] . ' ' . $appointment['user_lastname']) ?>">
                                    <div title="<?= htmlspecialchars($appointment['user_name'] . ' ' . $appointment['user_midname'] . ' ' . $appointment['user_lastname']) ?>"><?= htmlspecialchars($appointment['user_name'] . ' ' . $appointment['user_midname'] . ' ' . $appointment['user_lastname']) ?></div>
                                </td>
                                <td title="<?= htmlspecialchars($appointment['user_contact']) ?>">
                                    <span title="<?= htmlspecialchars($appointment['user_contact']) ?>"><?= htmlspecialchars($appointment['user_contact']) ?></span>
                                </td>
                                <td title="<?= htmlspecialchars($appointment['service_type_name']) ?>">
                                    <span class="text-dark" title="<?= htmlspecialchars($appointment['service_type_name']) ?>">
                                        <?= htmlspecialchars($appointment['service_type_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    $roleClass = '';
                                    $roleText = htmlspecialchars($appointment['technician_role']);
                                    if ($roleText === 'Primary') {
                                        $roleClass = 'badge bg-primary text-white fw-bold d-inline-block';
                                    } elseif ($roleText === 'Secondary') {
                                        $roleClass = 'badge bg-success text-white fw-bold d-inline-block';
                                    } else {
                                        $roleClass = 'text-dark';
                                    }
                                    ?>
                                    <span class="<?= $roleClass ?>" style="min-width: 80px; padding: 4px 8px; text-align: center;">
                                        <?= $roleText ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $appointmentDate = new DateTime($appointment['app_schedule']);
                                    echo $appointmentDate->format('M j, Y');
                                    ?>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-primary btn-sm text-white" onclick="viewDetails({
                                        id: <?= $appointment['app_id'] ?>,
                                        customer: '<?= addslashes(htmlspecialchars($appointment['user_name'] . ' ' . $appointment['user_midname'] . ' ' . $appointment['user_lastname'])) ?>',
                                        houseBuildingStreet: '<?= addslashes(htmlspecialchars($appointment['house_building_street'])) ?>',
                                        barangay: '<?= addslashes(htmlspecialchars($appointment['barangay'])) ?>',
                                        municipalityCity: '<?= addslashes(htmlspecialchars($appointment['municipality_city'])) ?>',
                                        province: '<?= addslashes(htmlspecialchars($appointment['province'])) ?>',
                                        zipCode: '<?= addslashes(htmlspecialchars($appointment['zip_code'])) ?>',
                                        date: '<?= date('F j, Y', strtotime($appointment['app_schedule'])) ?>',
                                        time: '<?= date('g:i A', strtotime($appointment['app_schedule'])) ?>',
                                        serviceType: '<?= addslashes(htmlspecialchars($appointment['service_type_name'])) ?>',
                                        appliancesType: '<?= addslashes(htmlspecialchars($appointment['appliances_type_name'])) ?>',
                                        primaryTechnician: '<?= addslashes(htmlspecialchars($appointment['tech_name'] . ' ' . $appointment['tech_midname'] . ' ' . $appointment['tech_lastname'])) ?>',
                                        secondaryTechnician: '<?= $appointment['tech2_name'] ? addslashes(htmlspecialchars($appointment['tech2_name'] . ' ' . $appointment['tech2_midname'] . ' ' . $appointment['tech2_lastname'])) : '' ?>',
                                        description: '<?= addslashes(htmlspecialchars($appointment['app_desc'])) ?>',
                                        totalAmount: '<?= number_format($appointment['app_price'], 2) ?>',
                                        costJustification: '<?= addslashes(htmlspecialchars($appointment['app_justification'] ?? '')) ?>',
                                        comment: '<?= addslashes(htmlspecialchars($appointment['app_comment'] ?? '')) ?>',
                                        rating: <?= floatval($appointment['app_rating'] ?? 0) ?>,
                                        created: '<?= htmlspecialchars($appointment['app_created']) ?>'
                                    })">
                                        <i class="bi bi-eye me-1"></i>View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="d-lg-none" id="mobileAppointmentCardsContainer">
        <?php if (empty($appointments)): ?>
            <div class="text-center text-muted py-4">
                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                No completed appointments found for this technician.
            </div>
        <?php else: ?>
            <?php foreach ($appointments as $appointment): ?>
                <div class="mobile-appointment-card appointment-row" 
                     data-service-type="<?= htmlspecialchars($appointment['service_type_name']) ?>" 
                     data-role="<?= htmlspecialchars($appointment['technician_role']) ?>"
                     data-appointment-id="<?= $appointment['app_id'] ?>">
                    <div class="mobile-appointment-header">
                        <h6 class="mobile-appointment-customer">
                            <?= htmlspecialchars($appointment['user_name'] . ' ' . $appointment['user_midname'] . ' ' . $appointment['user_lastname']) ?>
                        </h6>
                        <div class="mobile-appointment-role">
                            <?php 
                            $roleClass = '';
                            $roleText = htmlspecialchars($appointment['technician_role']);
                            if ($roleText === 'Primary') {
                                $roleClass = 'badge bg-primary text-white';
                            } elseif ($roleText === 'Secondary') {
                                $roleClass = 'badge bg-success text-white';
                            } else {
                                $roleClass = 'badge bg-secondary text-white';
                            }
                            ?>
                            <span class="<?= $roleClass ?>"><?= $roleText ?></span>
                        </div>
                    </div>
                    <div class="mobile-appointment-details">
                        <div class="mobile-appointment-contact">
                            <i class="bi bi-telephone me-1"></i><?= htmlspecialchars($appointment['user_contact']) ?>
                        </div>
                        <div class="mobile-appointment-service">
                            <i class="bi bi-tools me-1"></i><?= htmlspecialchars($appointment['service_type_name']) ?>
                        </div>
                        <div class="mobile-appointment-date">
                            <i class="bi bi-calendar me-1"></i>
                            <?php
                            $appointmentDate = new DateTime($appointment['app_schedule']);
                            echo $appointmentDate->format('M j, Y');
                            ?>
                        </div>
                    </div>
                    <div class="mobile-appointment-actions">
                        <button class="btn btn-primary btn-sm" onclick="viewDetails({
                            id: <?= $appointment['app_id'] ?>,
                            customer: '<?= addslashes(htmlspecialchars($appointment['user_name'] . ' ' . $appointment['user_midname'] . ' ' . $appointment['user_lastname'])) ?>',
                            houseBuildingStreet: '<?= addslashes(htmlspecialchars($appointment['house_building_street'])) ?>',
                            barangay: '<?= addslashes(htmlspecialchars($appointment['barangay'])) ?>',
                            municipalityCity: '<?= addslashes(htmlspecialchars($appointment['municipality_city'])) ?>',
                            province: '<?= addslashes(htmlspecialchars($appointment['province'])) ?>',
                            zipCode: '<?= addslashes(htmlspecialchars($appointment['zip_code'])) ?>',
                            date: '<?= date('F j, Y', strtotime($appointment['app_schedule'])) ?>',
                            time: '<?= date('g:i A', strtotime($appointment['app_schedule'])) ?>',
                            serviceType: '<?= addslashes(htmlspecialchars($appointment['service_type_name'])) ?>',
                            appliancesType: '<?= addslashes(htmlspecialchars($appointment['appliances_type_name'])) ?>',
                            primaryTechnician: '<?= addslashes(htmlspecialchars($appointment['tech_name'] . ' ' . $appointment['tech_midname'] . ' ' . $appointment['tech_lastname'])) ?>',
                            secondaryTechnician: '<?= $appointment['tech2_name'] ? addslashes(htmlspecialchars($appointment['tech2_name'] . ' ' . $appointment['tech2_midname'] . ' ' . $appointment['tech2_lastname'])) : '' ?>',
                            description: '<?= addslashes(htmlspecialchars($appointment['app_desc'])) ?>',
                            totalAmount: '<?= number_format($appointment['app_price'], 2) ?>',
                            costJustification: '<?= addslashes(htmlspecialchars($appointment['app_justification'] ?? '')) ?>',
                            comment: '<?= addslashes(htmlspecialchars($appointment['app_comment'] ?? '')) ?>',
                            rating: <?= floatval($appointment['app_rating'] ?? 0) ?>,
                            created: '<?= htmlspecialchars($appointment['app_created']) ?>'
                        })">
                            <i class="bi bi-eye"></i> View Details
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Pagination Container -->
    <div class="d-flex justify-content-center mt-4">
        <div id="paginationContainer" class="pagination-container">
            <!-- Pagination controls will be inserted here -->
        </div>
    </div>
    
    </div> <!-- Close dashboard-card-body -->
</div> <!-- Close dashboard-card -->

    <!-- Appointment Details Modal -->
    <div class="modal fade" id="appointmentDetailsModal" tabindex="-1" aria-labelledby="appointmentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title d-flex align-items-center" id="appointmentDetailsModalLabel">
                        <i class="bi bi-calendar-check me-2"></i>
                        Appointment Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Main Information Grid -->
                    <div class="row g-4 mb-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-hash text-muted me-2"></i>
                                    <small class="text-muted fw-medium">Appointment ID</small>
                                </div>
                                <div class="fw-medium" id="modalAppointmentId">APP-000</div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person text-muted me-2"></i>
                                    <small class="text-muted fw-medium">Customer Name</small>
                                </div>
                                <div class="fw-medium" id="modalCustomer"></div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-geo-alt text-muted me-2"></i>
                                    <small class="text-muted fw-medium">Customer Address</small>
                                </div>
                                <div id="modalAddress"></div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-calendar text-muted me-2"></i>
                                    <small class="text-muted fw-medium">Date & Time</small>
                                </div>
                                <div class="fw-medium">
                                    <span id="modalDate"></span> <span id="modalTime"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-tools text-muted me-2"></i>
                                    <small class="text-muted fw-medium">Service Type</small>
                                </div>
                                <div class="fw-medium" id="modalServiceType"></div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-gear text-muted me-2"></i>
                                    <small class="text-muted fw-medium">Appliances Type</small>
                                </div>
                                <div class="fw-medium" id="modalAppliancesType"></div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle text-muted me-2"></i>
                                    <small class="text-muted fw-medium">Status</small>
                                </div>
                                <div class="fw-medium">
                                    <span>Completed</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-currency-dollar text-muted me-2"></i>
                                    <small class="text-muted fw-medium">Total Amount</small>
                                </div>
                                <div class="fw-medium" id="modalTotalAmount">₱0.00</div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Technician Information -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-person-badge text-muted me-2"></i>
                                <small class="text-muted fw-medium">Primary Technician</small>
                            </div>
                            <div class="fw-medium" id="modalPrimaryTechnician"></div>
                        </div>
                        
                        <div class="col-md-6" id="secondaryTechnicianSection" style="display: none;">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-people text-muted me-2"></i>
                                <small class="text-muted fw-medium">Secondary Technician</small>
                            </div>
                            <div class="fw-medium" id="modalSecondaryTechnician"></div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Description Section -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-card-text text-primary me-2"></i>
                            <h6 class="text-primary mb-0">Description</h6>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <p id="modalDescription" class="fw-medium"></p>
                        </div>
                    </div>
                    
                    <!-- Cost Justification Section -->
                    <div class="mb-4" id="costJustificationSection">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-receipt text-primary me-2"></i>
                            <h6 class="text-primary mb-0">Cost Justification</h6>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <p id="modalCostJustification"class="fw-medium""></p>
                        </div>
                    </div>
                    
                    <!-- Customer Feedback Section -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-star-fill text-primary me-2"></i>
                            <h6 class="text-primary mb-0">Customer Feedback</h6>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-star text-muted me-2"></i>
                                    <small class="text-muted fw-medium">Rating</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span id="modalRatingStars" class="text-warning me-2">★★★★★</span>
                                    <span id="modalRatingValue" class="fw-medium">(0/5)</span>
                                </div>
                            </div>
                            <div id="modalCommentSection">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-chat text-muted me-2"></i>
                                    <small class="text-muted fw-medium">Comment</small>
                                </div>
                                <div id="modalComment" class="fw-medium"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Info -->
                    <div class="text-center mt-4">
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>Created: <span id="modalCreated"></span>
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn bg-secondary text-white px-4" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Pagination variables
        let currentPage = 1;
        const itemsPerPage = 10;
        let serviceTypeFilter, customerNameSearch, roleFilter;
        
        // Filter and pagination functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Desktop filter elements
            serviceTypeFilter = document.getElementById('serviceTypeFilter');
            customerNameSearch = document.getElementById('customerNameSearch');
            roleFilter = document.getElementById('roleFilter');
            
            // Mobile filter elements
            const serviceTypeFilterMobile = document.getElementById('serviceTypeFilterMobile');
            const customerNameSearchMobile = document.getElementById('customerNameSearchMobile');
            const roleFilterMobile = document.getElementById('roleFilterMobile');
            
            console.log('Initializing filters:', {
                serviceTypeFilter: !!serviceTypeFilter,
                customerNameSearch: !!customerNameSearch,
                roleFilter: !!roleFilter,
                serviceTypeFilterMobile: !!serviceTypeFilterMobile,
                customerNameSearchMobile: !!customerNameSearchMobile,
                roleFilterMobile: !!roleFilterMobile
            });
            
            // Function to sync filter values between desktop and mobile
            function syncFilters(sourceType, sourceRole, sourceSearch) {
                if (serviceTypeFilter && serviceTypeFilterMobile) {
                    serviceTypeFilter.value = sourceType;
                    serviceTypeFilterMobile.value = sourceType;
                }
                if (roleFilter && roleFilterMobile) {
                    roleFilter.value = sourceRole;
                    roleFilterMobile.value = sourceRole;
                }
                if (customerNameSearch && customerNameSearchMobile) {
                    customerNameSearch.value = sourceSearch;
                    customerNameSearchMobile.value = sourceSearch;
                }
            }
            
            // Desktop Service Type filter
            if (serviceTypeFilter) {
                serviceTypeFilter.addEventListener('change', function() {
                    console.log('Desktop service type filter changed:', this.value);
                    syncFilters(this.value, roleFilter?.value || 'All', customerNameSearch?.value || '');
                    applyFiltersAndPagination();
                });
            }
            
            // Mobile Service Type filter
            if (serviceTypeFilterMobile) {
                serviceTypeFilterMobile.addEventListener('change', function() {
                    console.log('Mobile service type filter changed:', this.value);
                    syncFilters(this.value, roleFilterMobile?.value || 'All', customerNameSearchMobile?.value || '');
                    applyFiltersAndPagination();
                });
            }
            
            // Desktop Role filter
            if (roleFilter) {
                roleFilter.addEventListener('change', function() {
                    console.log('Desktop role filter changed:', this.value);
                    syncFilters(serviceTypeFilter?.value || 'All', this.value, customerNameSearch?.value || '');
                    applyFiltersAndPagination();
                });
            }
            
            // Mobile Role filter
            if (roleFilterMobile) {
                roleFilterMobile.addEventListener('change', function() {
                    console.log('Mobile role filter changed:', this.value);
                    syncFilters(serviceTypeFilterMobile?.value || 'All', this.value, customerNameSearchMobile?.value || '');
                    applyFiltersAndPagination();
                });
            }
            
            // Desktop Live search filter with debounce
            if (customerNameSearch) {
                let searchTimeout;
                customerNameSearch.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const searchValue = this.value;
                    searchTimeout = setTimeout(function() {
                        console.log('Desktop search filter applied:', searchValue);
                        syncFilters(serviceTypeFilter?.value || 'All', roleFilter?.value || 'All', searchValue);
                        applyFiltersAndPagination();
                    }, 300); // 300ms debounce
                });
            }
            
            // Mobile Live search filter with debounce
            if (customerNameSearchMobile) {
                let searchTimeoutMobile;
                customerNameSearchMobile.addEventListener('input', function() {
                    clearTimeout(searchTimeoutMobile);
                    const searchValue = this.value;
                    searchTimeoutMobile = setTimeout(function() {
                        console.log('Mobile search filter applied:', searchValue);
                        syncFilters(serviceTypeFilterMobile?.value || 'All', roleFilterMobile?.value || 'All', searchValue);
                        applyFiltersAndPagination();
                    }, 300); // 300ms debounce
                });
            }
            
            // Initialize pagination
            console.log('Initializing pagination...');
            
            // Check if there are any appointments to work with
            const hasAppointments = document.querySelectorAll('.appointment-row').length > 0;
            console.log('Has appointments:', hasAppointments);
            
            if (hasAppointments) {
                applyFiltersAndPagination();
            } else {
                // No appointments available, but still initialize empty state
                console.log('No appointments found, showing empty state');
                showEmptyState();
                updatePaginationControls(0);
            }
        });
        
        function applyFiltersAndPagination() {
            console.log('Starting applyFiltersAndPagination...');
            
            // Apply filters first
            const filteredRows = applyFilters();
            
            console.log('Filtered rows count:', filteredRows.length);
            
            // Reset to first page when filters change
            currentPage = 1;
            
            // Then apply pagination
            applyPagination(filteredRows);
            
            // Update pagination controls
            updatePaginationControls(filteredRows.length);
            
            console.log('Applied filters and pagination:', {
                totalFiltered: filteredRows.length,
                currentPage: currentPage,
                totalPages: Math.ceil(filteredRows.length / itemsPerPage)
            });
        }
        
        function applyFilters() {
            // Get filter values from whichever elements are available (desktop or mobile)
            const serviceTypeFilter = document.getElementById('serviceTypeFilter') || document.getElementById('serviceTypeFilterMobile');
            const roleFilter = document.getElementById('roleFilter') || document.getElementById('roleFilterMobile');
            const customerNameSearch = document.getElementById('customerNameSearch') || document.getElementById('customerNameSearchMobile');
            
            const selectedServiceType = serviceTypeFilter?.value || 'All';
            const selectedRole = roleFilter?.value || 'All';
            const searchTerm = (customerNameSearch?.value || '').toLowerCase().trim();
            
            console.log('Filter values:', { selectedServiceType, selectedRole, searchTerm });
            
            // Get ALL rows from both desktop and mobile views for filtering
            const allDesktopRows = document.querySelectorAll('.d-none.d-lg-block .appointment-row');
            const allMobileRows = document.querySelectorAll('.d-lg-none .appointment-row');
            
            console.log('Total rows found - Desktop:', allDesktopRows.length, 'Mobile:', allMobileRows.length);
            
            // Use desktop rows as the source of truth for filtering
            const filteredRows = [];
            
            allDesktopRows.forEach(function(row) {
                const serviceType = row.getAttribute('data-service-type');
                const role = row.getAttribute('data-role');
                
                // Get customer name from desktop table row
                let customerName = '';
                const customerCell = row.querySelector('td:first-child div, td:first-child');
                if (customerCell) {
                    customerName = customerCell.textContent.toLowerCase().trim();
                }
                
                console.log('Row data:', { serviceType, role, customerName });
                
                // Check service type filter
                const serviceTypeMatch = selectedServiceType === 'All' || serviceType === selectedServiceType;
                
                // Check role filter  
                const roleMatch = selectedRole === 'All' || role === selectedRole;
                
                // Check name search filter
                const nameMatch = searchTerm === '' || customerName.includes(searchTerm);
                
                console.log('Filter matches:', { serviceTypeMatch, roleMatch, nameMatch });
                
                // Add to filtered rows if all filters match
                if (serviceTypeMatch && roleMatch && nameMatch) {
                    filteredRows.push(row);
                }
            });
            
            console.log('Filtered rows count:', filteredRows.length);
            return filteredRows;
        }
        
        function applyPagination(filteredRows) {
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            
            // Get all rows from both desktop and mobile views
            const allDesktopRows = document.querySelectorAll('.d-none.d-lg-block .appointment-row');
            const allMobileRows = document.querySelectorAll('.d-lg-none .appointment-row');
            
            // Always hide all rows and empty states first
            allDesktopRows.forEach(row => row.style.display = 'none');
            allMobileRows.forEach(row => row.style.display = 'none');
            hideEmptyState();
            
            console.log('applyPagination - filteredRows.length:', filteredRows.length);
            
            // Check if there are no filtered results
            if (filteredRows.length === 0) {
                console.log('No filtered results, showing empty state');
                showEmptyState();
                return;
            }
            
            // Show only the rows for current page from filtered results
            const pageRows = filteredRows.slice(startIndex, endIndex);
            console.log('Showing page rows:', pageRows.length);
            
            // For each filtered row, show both its desktop and mobile counterpart
            pageRows.forEach((row) => {
                const appointmentId = row.getAttribute('data-appointment-id');
                
                // Show corresponding desktop row
                const desktopRow = Array.from(allDesktopRows).find(r => 
                    r.getAttribute('data-appointment-id') === appointmentId
                );
                if (desktopRow) desktopRow.style.display = '';
                
                // Show corresponding mobile row
                const mobileRow = Array.from(allMobileRows).find(r => 
                    r.getAttribute('data-appointment-id') === appointmentId
                );
                if (mobileRow) mobileRow.style.display = '';
            });
        }
        
        function showEmptyState() {
            // Desktop empty state - find the correct table body
            const desktopTableBody = document.querySelector('.d-none.d-lg-block tbody');
            if (desktopTableBody) {
                let desktopEmptyRow = desktopTableBody.querySelector('.empty-state-row');
                if (!desktopEmptyRow) {
                    desktopEmptyRow = document.createElement('tr');
                    desktopEmptyRow.className = 'empty-state-row';
                    desktopEmptyRow.innerHTML = `
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-search fs-1 d-block mb-2"></i>
                            No appointments match the selected filters.
                        </td>
                    `;
                    desktopTableBody.appendChild(desktopEmptyRow);
                }
                desktopEmptyRow.style.display = '';
            }
            
            // Mobile empty state - find the correct container
            const mobileContainer = document.querySelector('.d-lg-none #mobileAppointmentCardsContainer');
            if (mobileContainer) {
                let mobileEmptyMessage = mobileContainer.querySelector('.filter-empty-state');
                if (!mobileEmptyMessage) {
                    mobileEmptyMessage = document.createElement('div');
                    mobileEmptyMessage.className = 'filter-empty-state text-center text-muted py-4';
                    mobileEmptyMessage.innerHTML = `
                        <i class="bi bi-search fs-1 d-block mb-2"></i>
                        No appointments match the selected filters.
                    `;
                    mobileContainer.appendChild(mobileEmptyMessage);
                }
                mobileEmptyMessage.style.display = '';
            }
        }
        
        function hideEmptyState() {
            // Hide desktop empty state
            const desktopEmptyRow = document.querySelector('.d-none.d-lg-block tbody .empty-state-row');
            if (desktopEmptyRow) {
                desktopEmptyRow.style.display = 'none';
            }
            
            // Hide mobile empty state
            const mobileEmptyMessage = document.querySelector('.d-lg-none #mobileAppointmentCardsContainer .filter-empty-state');
            if (mobileEmptyMessage) {
                mobileEmptyMessage.style.display = 'none';
            }
        }
        
        function updatePaginationControls(totalFilteredItems) {
            const paginationContainer = document.getElementById('paginationContainer');
            
            // If no items, show no pagination at all
            if (totalFilteredItems === 0) {
                paginationContainer.innerHTML = '';
                return;
            }
            
            const totalPages = Math.ceil(totalFilteredItems / itemsPerPage);
            
            // Remove results info text as requested
            let resultsInfo = '';
            
            // Always show pagination with prev/next buttons, even for single page
            if (totalPages <= 1) {
                const singlePageHTML = resultsInfo + `
                    <nav aria-label="Page navigation" class="text-center">
                        <ul class="pagination p-2 justify-content-center pagination-sm">
                            <li class="page-item disabled">
                                <span class="page-link text-dark rounded-pill border-0 p-2 px-3" aria-label="Previous">
                                    &lt;
                                </span>
                            </li>
                            <li class="page-item active">
                                <span class="page-link text-light rounded-pill border-0 p-2 px-3">1</span>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link text-dark rounded-pill border-0 p-2 px-3" aria-label="Next">
                                    &gt;
                                </span>
                            </li>
                        </ul>
                    </nav>
                `;
                paginationContainer.innerHTML = singlePageHTML;
                return;
            }
            
            let paginationHTML = resultsInfo + `
                <nav aria-label="Page navigation" class="text-center">
                    <ul class="pagination p-2 justify-content-center pagination-sm">
                        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                            <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" aria-label="Previous" onclick="changePage(${currentPage - 1}); return false;">
                                &lt;
                            </a>
                        </li>
            `;
            
            // Smart pagination - show max 5 pages with ellipsis
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
            
            // Adjust start page if we're near the end
            if (endPage - startPage < maxVisiblePages - 1) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }
            
            // Add first page and ellipsis if needed
            if (startPage > 1) {
                paginationHTML += `
                    <li class="page-item">
                        <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="changePage(1); return false;">1</a>
                    </li>
                `;
                if (startPage > 2) {
                    paginationHTML += `<li class="page-item disabled"><span class="page-link border-0">...</span></li>`;
                }
            }
            
            // Add page numbers
            for (let i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    paginationHTML += `
                        <li class="page-item active">
                            <a class="page-link text-light rounded-pill border-0 p-2 px-3" href="#" onclick="changePage(${i}); return false;">${i}</a>
                        </li>
                    `;
                } else {
                    paginationHTML += `
                        <li class="page-item">
                            <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="changePage(${i}); return false;">${i}</a>
                        </li>
                    `;
                }
            }
            
            // Add last page and ellipsis if needed
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    paginationHTML += `<li class="page-item disabled"><span class="page-link border-0">...</span></li>`;
                }
                paginationHTML += `
                    <li class="page-item">
                        <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="changePage(${totalPages}); return false;">${totalPages}</a>
                    </li>
                `;
            }
            
            paginationHTML += `
                        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                            <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" aria-label="Next" onclick="changePage(${currentPage + 1}); return false;">
                                &gt;
                            </a>
                        </li>
                    </ul>
                </nav>
            `;
            
            paginationContainer.innerHTML = paginationHTML;
        }
        
        function changePage(page) {
            const filteredRows = applyFilters();
            const totalPages = Math.ceil(filteredRows.length / itemsPerPage);
            
            // Prevent navigation to invalid pages
            if (page < 1 || (totalPages > 0 && page > totalPages) || filteredRows.length === 0) {
                return;
            }
         
            currentPage = page;
            applyPagination(filteredRows);
            updatePaginationControls(filteredRows.length);
        }

        // Function to view appointment details
        function viewDetails(appointment) {
            // Populate modal with appointment data
            document.getElementById('modalAppointmentId').textContent = 'APP-' + appointment.id;
            document.getElementById('modalCustomer').textContent = appointment.customer;
            document.getElementById('modalServiceType').textContent = appointment.serviceType;
            document.getElementById('modalAppliancesType').textContent = appointment.appliancesType;
            document.getElementById('modalPrimaryTechnician').textContent = appointment.primaryTechnician;
            // Combine address fields into a single formatted address
            const addressParts = [
                appointment.houseBuildingStreet,
                appointment.barangay,
                appointment.municipalityCity,
                appointment.province,
                appointment.zipCode
            ].filter(part => part && part.trim() !== '');
            
            const formattedAddress = addressParts.join(', ');
            document.getElementById('modalAddress').textContent = formattedAddress || 'No address provided';
            
            // Handle secondary technician
            const secondaryTechnicianSection = document.getElementById('secondaryTechnicianSection');
            const modalSecondaryTechnician = document.getElementById('modalSecondaryTechnician');
            
            if (appointment.secondaryTechnician && appointment.secondaryTechnician.trim() !== '') {
                modalSecondaryTechnician.textContent = appointment.secondaryTechnician;
                secondaryTechnicianSection.style.display = 'block';
            } else {
                secondaryTechnicianSection.style.display = 'none';
            }
            
            document.getElementById('modalDate').textContent = appointment.date;
            document.getElementById('modalTime').textContent = appointment.time;
            document.getElementById('modalDescription').textContent = appointment.description;
            document.getElementById('modalCreated').textContent = appointment.created;
            
            // Handle pricing information
            document.getElementById('modalTotalAmount').textContent = '₱' + appointment.totalAmount;
            
            // Handle cost justification section
            const costJustificationSection = document.getElementById('costJustificationSection');
            const modalCostJustification = document.getElementById('modalCostJustification');
            
            if (appointment.costJustification && appointment.costJustification.trim() !== '') {
                modalCostJustification.textContent = appointment.costJustification;
                costJustificationSection.style.display = 'block';
            } else {
                costJustificationSection.style.display = 'none';
            }
            
            // Handle rating - display as stars
            const modalRatingStars = document.getElementById('modalRatingStars');
            const modalRatingValue = document.getElementById('modalRatingValue');
            const rating = parseFloat(appointment.rating);
            
            // Generate star display
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    starsHtml += '★'; // Filled star
                } else {
                    starsHtml += '☆'; // Empty star
                }
            }
            modalRatingStars.innerHTML = starsHtml;
            modalRatingValue.textContent = '(' + Math.round(rating) + '/5)';
            
            // Handle comment section - always show the feedback section but hide comments if empty
            const modalComment = document.getElementById('modalComment');
            const modalCommentSection = document.getElementById('modalCommentSection');
            
            if (appointment.comment && appointment.comment !== 'No Comment' && appointment.comment.trim() !== '') {
                modalComment.textContent = appointment.comment;
                modalCommentSection.style.display = 'block';
            } else {
                modalComment.textContent = 'No additional comments provided.';
                modalCommentSection.style.display = 'block';
            }
            
            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
            
            // Add custom CSS to hide background scrollbar when modal shows
            document.getElementById('appointmentDetailsModal').addEventListener('show.bs.modal', function () {
                const style = document.createElement('style');
                style.id = 'modal-overflow-fix';
                style.innerHTML = `
                    html, body {
                        overflow: hidden !important;
                        padding-right: 0px !important;
                    }
                    .modal-open {
                        padding-right: 0px !important;
                    }
                `;
                document.head.appendChild(style);
            });
            
            // Remove custom CSS when modal is hidden
            document.getElementById('appointmentDetailsModal').addEventListener('hidden.bs.modal', function () {
                const style = document.getElementById('modal-overflow-fix');
                if (style) {
                    style.remove();
                }
            });
            
            modal.show();
        }
    </script>