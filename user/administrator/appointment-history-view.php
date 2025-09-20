<?php
$tech_id = intval($_GET['tech'] ?? 0);
$tech = $pdo->prepare("SELECT * FROM user WHERE user_id = ?");
$tech->execute([$tech_id]);
$tech = ($row = $tech->fetch(PDO::FETCH_OBJ)) ? $row->user_name. " " . $row->user_midname . " " . $row->user_lastname : 'Unknown';

$customer_id = intval($_GET['history'] ?? 0);
$customer = $pdo->prepare("SELECT * FROM user WHERE user_id = ?");
$customer->execute([$customer_id]);
$customer = ($row = $customer->fetch(PDO::FETCH_OBJ)) ? $row->user_name. " " . $row->user_midname . " " .$row->user_lastname : 'Unknown';


// SQL query
if (isset($_GET['tech-history'])) {
$sql = "
        SELECT
            a.*,
            a_s.app_status_name,
            u.user_name, u.user_midname, u.user_lastname, u.house_building_street, 
            COALESCE(ata.barangay, u.barangay) as barangay,
            COALESCE(ata.municipality_city, u.municipality_city) as municipality_city,
            COALESCE(ata.province, u.province) as province,
            COALESCE(ata.zip_code, u.zip_code) as zip_code,
            ut.user_name AS tech_name,
            ut.user_midname AS tech_midname,
            ut.user_lastname AS tech_lastname,
            ut2.user_name AS tech2_name,
            ut2.user_midname AS tech2_midname,
            ut2.user_lastname AS tech2_lastname,
            s.service_type_name,
            COALESCE(at.appliances_type_name, 'Not Specified') as appliances_type_name
        FROM
            appointment a
        JOIN appointment_status a_s ON a.app_status_id = a_s.app_status_id
        JOIN user u ON a.user_id = u.user_id
        LEFT JOIN appointment_transaction_address ata ON a.app_id = ata.app_id
        JOIN user ut ON a.user_technician = ut.user_id
        LEFT JOIN user ut2 ON a.user_technician_2 = ut2.user_id
        JOIN service_type s ON a.service_type_id = s.service_type_id
        LEFT JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        WHERE a.app_status_id = 3 AND a.user_technician = ".$_GET['tech']." AND a.user_id = ".$_GET['history']."
        ORDER BY a.app_id DESC
    ";
} else {
    $sql = "
        SELECT
            a.*,
            a_s.app_status_name,
            u.user_name, u.user_midname, u.user_lastname, u.house_building_street, 
            COALESCE(ata.barangay, u.barangay) as barangay,
            COALESCE(ata.municipality_city, u.municipality_city) as municipality_city,
            COALESCE(ata.province, u.province) as province,
            COALESCE(ata.zip_code, u.zip_code) as zip_code,
            ut.user_name AS tech_name,
            ut.user_midname AS tech_midname,
            ut.user_lastname AS tech_lastname,
            ut2.user_name AS tech2_name,
            ut2.user_midname AS tech2_midname,
            ut2.user_lastname AS tech2_lastname,
            s.service_type_name,
            COALESCE(at.appliances_type_name, 'Not Specified') as appliances_type_name
        FROM
            appointment a
        JOIN appointment_status a_s ON a.app_status_id = a_s.app_status_id
        JOIN user u ON a.user_id = u.user_id
        LEFT JOIN appointment_transaction_address ata ON a.app_id = ata.app_id
        JOIN user ut ON a.user_technician = ut.user_id
        LEFT JOIN user ut2 ON a.user_technician_2 = ut2.user_id
        JOIN service_type s ON a.service_type_id = s.service_type_id
        LEFT JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        WHERE a.app_status_id = 3 AND  a.user_id = ".$_GET['history']."
        ORDER BY a.app_id DESC
    ";
}
$stmt = $pdo->query($sql);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Optional: define badge color classes for each service type
$serviceTypeColors = [
    'Repair' => 'alert alert-danger ',
    'Maintenance' => 'alert alert-warning ',
    'Installation' => 'alert alert-success ',
];
$defaultClass = 'bg-secondary text-white';
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

    .rating {
        --size: 20px;
        --star-color: #FFD700;
        --mask: conic-gradient(from -18deg at 61% 34.5%, #0000 108deg, #000 0) 0 / var(--size),
            conic-gradient(from 270deg at 39% 34.5%, #0000 108deg, #000 0) 0 / var(--size),
            conic-gradient(from 54deg at 68% 56%, #0000 108deg, #000 0) 0 / var(--size),
            conic-gradient(from 198deg at 32% 56%, #0000 108deg, #000 0) 0 / var(--size),
            conic-gradient(from 126deg at 50% 69%, #0000 108deg, #000 0) 0 / var(--size);
        --bg: linear-gradient(90deg, var(--star-color) calc(var(--size) * var(--val) + 1px), #ddd 0);
        height: var(--size);
        width: calc(var(--size) * 5);
        border: 0;
        outline: none;
        background: transparent;
        -webkit-appearance: none;
        appearance: none;
        cursor: pointer;
        margin: 20px 0;
    }

    .rating::-webkit-slider-runnable-track {
        height: 100%;
        mask: var(--mask);
        -webkit-mask-composite: source-in;
        mask-composite: intersect;
        background: var(--bg);
        box-shadow: none;
        border: none;
    }

    .rating::-webkit-slider-thumb {
        opacity: 0;
    }

    .rating::-moz-range-track {
        height: 100%;
        mask: var(--mask);
        mask-composite: add;
        background: var(--bg);
        box-shadow: none;
        border: none;
    }

    .rating::-moz-range-thumb {
        opacity: 0;
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
        <li class="breadcrumb-item h3 m-0 active text-dark" aria-current="page"><?= isset($_GET['tech-history'])? $tech: $customer  ?>'s History View</li>
    </ol>
</nav>

<!-- Filter Section -->
<div class="dashboard-card mb-4" style="border-radius: 12px; margin-top: 15px;">
    <div class="dashboard-card-header">
        <i class="bi bi-funnel me-2"></i>Filter Appointments
    </div>
    <div class="dashboard-card-body py-3">
        <div class="row">
            <div class="col-md-6">
                <label class="form-label small text-muted mb-1">Service Type</label>
                <select class="form-select bg-light border-0 round_md" id="serviceTypeFilter" style="width: auto; min-width: 150px;">
                    <option value="All">All Service Types</option>
                    <!-- Service types will be populated dynamically -->
                </select>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
            <label class="form-label small text-muted mb-1" style="padding-right: 175px; position:absolute;">Technician Name</label>
                <div class="input-group" style="max-width: 300px; height: 25px; padding-top: 25px">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="technicianNameSearch" class="form-control bg-light border-0" placeholder="Search technician name..." style="font-weight: normal;">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Container -->
<div class="dashboard-card">
    <style>
        .table-responsive-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Mobile appointment history card styling */
        .mobile-history-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .mobile-history-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            transform: translateY(-1px);
        }
        
        .mobile-history-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        
        .mobile-history-technician {
            font-weight: 600;
            font-size: 1.1rem;
            color: #2c3e50;
            margin: 0;
            line-height: 1.3;
        }
        
        .mobile-history-date {
            flex-shrink: 0;
            margin-left: 12px;
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        .mobile-history-details {
            margin-bottom: 12px;
        }
        
        .mobile-history-service {
            color: #495057;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 4px;
        }
        
        .mobile-history-appliance {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .mobile-history-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        
        .mobile-history-actions .btn {
            flex: none;
            min-width: 80px;
            font-size: 0.85rem;
            padding: 6px 12px;
        }
        .fixed-table {
            table-layout: fixed;
            width: 100%;
            min-width: 800px;
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
        .fixed-table td div,
        .fixed-table td span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            display: block;
        }
        /* Enhanced Table Styling */
        .table thead th {
            background-color: #f8f9fa !important;
            color: #4a4a4a !important;
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

        .fixed-table th:nth-child(1),
        .fixed-table td:nth-child(1) {
            width: 25%; /* Technician Name */
        }
        .fixed-table th:nth-child(2),
        .fixed-table td:nth-child(2) {
            width: 20%; /* Service Type */
            white-space: normal;
            overflow: visible;
            text-overflow: unset;
        }
        .fixed-table th:nth-child(3),
        .fixed-table td:nth-child(3) {
            width: 20%; /* Appliances Type */
        }
        .fixed-table th:nth-child(4),
        .fixed-table td:nth-child(4) {
            width: 20%; /* Date */
        }
        .fixed-table th:nth-child(5),
        .fixed-table td:nth-child(5) {
            width: 15%; /* Action */
            text-align: right;
        }
        @media (max-width: 768px) {
            /* Mobile Filter Layout - Side by Side */
            .dashboard-card-body .row {
                display: flex !important;
                flex-wrap: nowrap !important;
                gap: 8px !important;
                margin: 0 !important;
            }
            
            .dashboard-card-body .row .col-md-6 {
                width: 50% !important;
                flex: 1 !important;
                margin-bottom: 0 !important;
                padding: 0 4px !important;
            }
            
            .dashboard-card-body .row .col-md-6:first-child {
                padding-left: 0 !important;
            }
            
            .dashboard-card-body .row .col-md-6:last-child {
                padding-right: 0 !important;
                justify-content: flex-start !important;
            }
            
            /* Mobile Filter Controls */
            .dashboard-card-body .row .col-md-6 .form-select {
                width: 100% !important;
                min-width: 0 !important;
                font-size: 13px !important;
                padding: 8px 12px !important;
            }
            
            .dashboard-card-body .row .col-md-6 .input-group {
                width: 100% !important;
                max-width: none !important;
                min-width: 0 !important;
            }
            
            .dashboard-card-body .row .col-md-6 .input-group .form-control {
                font-size: 13px !important;
                padding: 8px 12px !important;
            }
            
            .dashboard-card-body .row .col-md-6 .input-group .input-group-text {
                font-size: 13px !important;
                padding: 8px 12px !important;
            }
            
            /* Mobile Table Styles */
            .fixed-table {
                min-width: 700px;
                font-size: 13px;
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
    </style>
    
    <!-- Desktop Table View -->
    <div class="d-none d-lg-block">
        <div class="dashboard-card-body p-0">
            <div class="table-responsive-container">
                <table class="table table-hover align-middle mb-0 fixed-table">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Technician Name</th>
                        <th scope="col">Service Type</th>
                        <th scope="col">Appliances Type</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody">
<?php if (empty($appointments)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                            No appointment history found.
                        </td>
                    </tr>
<?php else: ?>
<?php foreach ($appointments as $app):

    $scheduled = $app['app_schedule']; // Example: '2025-05-25 14:30:00'
    $date = date('F j, Y', strtotime($scheduled)); // e.g., "May 25, 2025"
    $time = date('g:i A', strtotime($scheduled));  // e.g., "2:30 PM"
    $customer_name = htmlspecialchars($app['user_name'] . " " . $app['user_midname'] . " " . $app['user_lastname']);
    $desc = htmlspecialchars($app['app_desc']);
    $totalAmount = isset($app['app_price']) ? '₱' . number_format($app['app_price'], 2) : '₱0.00';
    $comment = htmlspecialchars($app['app_comment']);
    $rating = floatval($app['app_rating']);
    $created = htmlspecialchars($app['app_schedule']);
    $serviceType = htmlspecialchars($app['service_type_name']);
    $badgeClass = $serviceTypeColors[$serviceType] ?? $defaultClass;
    $techFullname = htmlspecialchars("{$app['tech_name']} {$app['tech_midname']} {$app['tech_lastname']}");
    ?>
    <tr class="appointment-row" data-service-type="<?= $serviceType ?>" data-technician="<?= $techFullname ?>">
        <td title="<?= $techFullname ?>">
            <div><?= $techFullname ?></div>
        </td>
        <td>
            <span class="text-dark">
                <?= $serviceType ?>
            </span>
        </td>
        <td>
            <span class="text-dark">
                <?= $app['appliances_type_name'] ?? 'Not Specified' ?>
            </span>
        </td>
        <td>
            <?php
            $appointmentDate = new DateTime($app['app_schedule']);
            echo $appointmentDate->format('M j, Y');
            ?>
        </td>
        <td>
            <?php 
            // Process secondary technician name properly
            $secondaryTechName = '';
            if (!empty($app['tech2_name'])) {
                $tech2_full = trim($app['tech2_name'] . ' ' . ($app['tech2_midname'] ?? '') . ' ' . ($app['tech2_lastname'] ?? ''));
                $secondaryTechName = addslashes($tech2_full);
            }
            $totalAmount = isset($app['app_price']) ? '₱' . number_format($app['app_price'], 2) : '₱0.00';
            $costJustification = addslashes(htmlspecialchars($app['app_justification'] ?? 'No cost justification provided.'));
            $appliancesType = addslashes($app['appliances_type_name'] ?? 'Not Specified');
            ?>
            <button class="btn btn-primary btn-sm text-white" onclick="viewDetails({
                id: <?= $app['app_id'] ?>,
                customer: '<?= addslashes($customer_name) ?>',
                houseBuildingStreet: '<?= addslashes(htmlspecialchars($app['house_building_street'])) ?>',
                barangay: '<?= addslashes(htmlspecialchars($app['barangay'])) ?>',
                municipalityCity: '<?= addslashes(htmlspecialchars($app['municipality_city'])) ?>',
                province: '<?= addslashes(htmlspecialchars($app['province'])) ?>',
                zipCode: '<?= addslashes(htmlspecialchars($app['zip_code'])) ?>',
                date: '<?= $date ?>',
                time: '<?= $time ?>',
                serviceType: '<?= addslashes($serviceType) ?>',
                appliancesType: '<?= $appliancesType ?>',
                totalAmount: '<?= $totalAmount ?>',
                technician: '<?= addslashes($techFullname) ?>',
                secondaryTechnician: '<?= $secondaryTechName ?>',
                description: '<?= addslashes($desc) ?>',
                costJustification: '<?= $costJustification ?>',
                comment: '<?= addslashes($comment) ?>',
                rating: <?= $rating ?>,
                created: '<?= $created ?>'
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
        <!-- Desktop Pagination -->
        <div id="desktopPaginationContainer" class="mt-3 text-center">
            <!-- Pagination controls will be inserted here -->
        </div>
    </div>
    
    <!-- Mobile Card View -->
    <div class="d-lg-none">
      
        <div class="dashboard-card-body" id="mobileHistoryCardsContainer">
            <?php if (empty($appointments)): ?>
                <div class="text-center text-muted py-4">
                    <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                    No appointment history found.
                </div>
            <?php else: ?>
            <?php foreach ($appointments as $app): ?>
                <?php
                $scheduled = $app['app_schedule'];
                $date = date('F j, Y', strtotime($scheduled));
                $time = date('g:i A', strtotime($scheduled));
                $customer_name = htmlspecialchars($app['user_name'] . " " . $app['user_midname'] . " " . $app['user_lastname']);
                $desc = htmlspecialchars($app['app_desc']);
                $totalAmount = isset($app['app_price']) ? '₱' . number_format($app['app_price'], 2) : '₱0.00';
                $comment = htmlspecialchars($app['app_comment']);
                $rating = floatval($app['app_rating']);
                $created = htmlspecialchars($app['app_schedule']);
                $serviceType = htmlspecialchars($app['service_type_name']);
                $badgeClass = $serviceTypeColors[$serviceType] ?? $defaultClass;
                $techFullname = htmlspecialchars("{$app['tech_name']} {$app['tech_midname']} {$app['tech_lastname']}");
                ?>
                <div class="mobile-history-card appointment-row" 
                     data-service-type="<?= $serviceType ?>" 
                     data-technician="<?= $techFullname ?>">
                    <div class="mobile-history-header">
                        <h6 class="mobile-history-technician"><?= $techFullname ?></h6>
                        <div class="mobile-history-date">
                            <i class="bi bi-calendar me-1"></i>
                            <?php
                            $appointmentDate = new DateTime($app['app_schedule']);
                            echo $appointmentDate->format('M j, Y');
                            ?>
                        </div>
                    </div>
                    <div class="mobile-history-details">
                        <div class="mobile-history-service">
                            <i class="bi bi-tools me-1"></i><?= $serviceType ?>
                        </div>
                        <div class="mobile-history-appliance">
                            <i class="bi bi-gear me-1"></i><?= $app['appliances_type_name'] ?? 'Not Specified' ?>
                        </div>
                    </div>
                    <div class="mobile-history-actions">
                        <?php 
                        // Process secondary technician name properly
                        $secondaryTechName = '';
                        if (!empty($app['tech2_name'])) {
                            $tech2_full = trim($app['tech2_name'] . ' ' . ($app['tech2_midname'] ?? '') . ' ' . ($app['tech2_lastname'] ?? ''));
                            $secondaryTechName = addslashes($tech2_full);
                        }
                        $costJustification = addslashes(htmlspecialchars($app['app_justification'] ?? 'No cost justification provided.'));
                        $appliancesType = addslashes($app['appliances_type_name'] ?? 'Not Specified');
                        ?>
                        <button class="btn btn-primary btn-sm" onclick="viewDetails({
                            id: <?= $app['app_id'] ?>,
                            customer: '<?= addslashes($customer_name) ?>',
                            houseBuildingStreet: '<?= addslashes(htmlspecialchars($app['house_building_street'])) ?>',
                            barangay: '<?= addslashes(htmlspecialchars($app['barangay'])) ?>',
                            municipalityCity: '<?= addslashes(htmlspecialchars($app['municipality_city'])) ?>',
                            province: '<?= addslashes(htmlspecialchars($app['province'])) ?>',
                            zipCode: '<?= addslashes(htmlspecialchars($app['zip_code'])) ?>',
                            date: '<?= $date ?>',
                            time: '<?= $time ?>',
                            serviceType: '<?= addslashes($serviceType) ?>',
                            appliancesType: '<?= $appliancesType ?>',
                            totalAmount: '<?= $totalAmount ?>',
                            technician: '<?= addslashes($techFullname) ?>',
                            secondaryTechnician: '<?= $secondaryTechName ?>',
                            description: '<?= addslashes($desc) ?>',
                            costJustification: '<?= $costJustification ?>',
                            comment: '<?= addslashes($comment) ?>',
                            rating: <?= $rating ?>,
                            created: '<?= $created ?>'
                        })">
                            <i class="bi bi-eye"></i> View Details
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
            <!-- Mobile Pagination -->
            <div id="mobilePaginationContainer" class="mt-3 text-center">
                <!-- Pagination controls will be inserted here -->
            </div>
        </div>
    </div>
</div>

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
                            <div class="fw-medium" id="modalAppliancesType">Not Specified</div>
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
                        <div class="fw-medium" id="modalTechnician"></div>
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
                        <p id="modalCostJustification" class="fw-medium"></p>
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
        let serviceTypeFilter, technicianNameSearch;
        
        // Filter and pagination functionality
        document.addEventListener('DOMContentLoaded', function() {
            serviceTypeFilter = document.getElementById('serviceTypeFilter');
            technicianNameSearch = document.getElementById('technicianNameSearch');
            
            // Populate service type filter
            populateServiceTypeFilter();
            
            // Service Type filter
            if (serviceTypeFilter) {
                serviceTypeFilter.addEventListener('change', function() {
                    applyFiltersAndPagination();
                });
            }
            
            // Live search filter
            if (technicianNameSearch) {
                technicianNameSearch.addEventListener('input', function() {
                    applyFiltersAndPagination();
                });
            }
            
            // Initialize pagination
            applyFiltersAndPagination();
            
            // Handle window resize to reapply pagination when switching between desktop/mobile
            window.addEventListener('resize', function() {
                // Reset to first page when view changes
                currentPage = 1;
                applyFiltersAndPagination();
            });
        });
        
        function populateServiceTypeFilter() {
            const serviceTypes = new Set();
            // Get service types from desktop table rows (they contain all the data)
            const rows = document.querySelectorAll('#historyTableBody .appointment-row');
            
            rows.forEach(row => {
                const serviceType = row.getAttribute('data-service-type');
                if (serviceType) {
                    serviceTypes.add(serviceType);
                }
            });
            
            serviceTypes.forEach(serviceType => {
                const option = document.createElement('option');
                option.value = serviceType;
                option.textContent = serviceType;
                serviceTypeFilter.appendChild(option);
            });
        }
        
        function applyFiltersAndPagination() {
            // Reset to first page when filters change
            currentPage = 1;
            
            // Apply filters first
            const filteredRows = applyFilters();
            
            // Then apply pagination
            applyPagination(filteredRows);
            
            // Update pagination controls
            updatePaginationControls(filteredRows.length);
        }
        
        function applyFilters() {
            const selectedServiceType = serviceTypeFilter ? serviceTypeFilter.value : 'All';
            const searchTerm = technicianNameSearch ? technicianNameSearch.value.toLowerCase().trim() : '';
            
            // Only get rows from the currently visible view (desktop or mobile)
            const isDesktop = window.innerWidth >= 992; // Bootstrap lg breakpoint
            const viewSelector = isDesktop ? '#historyTableBody .appointment-row' : '#mobileHistoryCardsContainer .appointment-row';
            const allRows = document.querySelectorAll(viewSelector);
            const filteredRows = [];
            
            console.log('Filter Debug - View:', isDesktop ? 'Desktop' : 'Mobile');
            console.log('Filter Debug - Total rows found:', allRows.length);
            
            allRows.forEach(function(row) {
                const serviceType = row.getAttribute('data-service-type');
                const technicianName = row.getAttribute('data-technician').toLowerCase().trim();
                
                // Check service type filter
                const serviceTypeMatch = selectedServiceType === 'All' || serviceType === selectedServiceType;
                
                // Check technician name search filter
                const nameMatch = searchTerm === '' || technicianName.includes(searchTerm);
                
                // Add to filtered rows if all filters match
                if (serviceTypeMatch && nameMatch) {
                    filteredRows.push(row);
                }
            });
            
            console.log('Filter Debug - Filtered rows:', filteredRows.length);
            return filteredRows;
        }
        
        function applyPagination(filteredRows) {
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            
            // Only apply pagination to the currently visible view
            const isDesktop = window.innerWidth >= 992;
            const viewSelector = isDesktop ? '#historyTableBody .appointment-row' : '#mobileHistoryCardsContainer .appointment-row';
            const allRows = document.querySelectorAll(viewSelector);
            
            // Hide all rows first
            allRows.forEach(row => {
                row.style.display = 'none';
            });
            
            // Check if there are no filtered results
            if (filteredRows.length === 0) {
                // Show empty state message
                showEmptyState(isDesktop);
            } else {
                // Hide empty state and show filtered results
                hideEmptyState(isDesktop);
                filteredRows.slice(startIndex, endIndex).forEach(row => {
                    row.style.display = '';
                });
            }
        }
        
        function showEmptyState(isDesktop) {
            if (isDesktop) {
                // Check if empty row already exists
                let emptyRow = document.querySelector('#historyTableBody .empty-state-row');
                if (!emptyRow) {
                    emptyRow = document.createElement('tr');
                    emptyRow.className = 'empty-state-row';
                    emptyRow.innerHTML = `
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-search fs-1 d-block mb-2"></i>
                            No appointments match the selected filters.
                        </td>
                    `;
                    document.getElementById('historyTableBody').appendChild(emptyRow);
                }
                emptyRow.style.display = '';
            } else {
                // Check if empty message already exists
                let emptyMessage = document.querySelector('#mobileHistoryCardsContainer .filter-empty-state');
                if (!emptyMessage) {
                    emptyMessage = document.createElement('div');
                    emptyMessage.className = 'filter-empty-state text-center text-muted py-4';
                    emptyMessage.innerHTML = `
                        <i class="bi bi-search fs-1 d-block mb-2"></i>
                        No appointments match the selected filters.
                    `;
                    document.getElementById('mobileHistoryCardsContainer').appendChild(emptyMessage);
                }
                emptyMessage.style.display = '';
            }
        }
        
        function hideEmptyState(isDesktop) {
            if (isDesktop) {
                const emptyRow = document.querySelector('#historyTableBody .empty-state-row');
                if (emptyRow) {
                    emptyRow.style.display = 'none';
                }
            } else {
                const emptyMessage = document.querySelector('#mobileHistoryCardsContainer .filter-empty-state');
                if (emptyMessage) {
                    emptyMessage.style.display = 'none';
                }
            }
        }
        
        function updatePaginationControls(totalFilteredItems) {
            const totalPages = Math.ceil(totalFilteredItems / itemsPerPage);
            const isDesktop = window.innerWidth >= 992;
            const paginationContainer = document.getElementById(isDesktop ? 'desktopPaginationContainer' : 'mobilePaginationContainer');
            
            // Clear both containers first
            const desktopContainer = document.getElementById('desktopPaginationContainer');
            const mobileContainer = document.getElementById('mobilePaginationContainer');
            if (desktopContainer) desktopContainer.innerHTML = '';
            if (mobileContainer) mobileContainer.innerHTML = '';
            
            // Debug: Log pagination calculation
            console.log('Pagination Debug:', {
                totalFilteredItems: totalFilteredItems,
                itemsPerPage: itemsPerPage,
                calculatedTotalPages: totalPages,
                currentPage: currentPage,
                view: isDesktop ? 'Desktop' : 'Mobile'
            });
            
            if (totalPages <= 1) {
                return;
            }
            
            let paginationHTML = `
                <nav aria-label="Page navigation" class="text-center">
                    <ul class="pagination p-2 justify-content-center pagination-sm">
                        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                            <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" aria-label="Previous" onclick="changePage(${currentPage - 1}); return false;">
                                &lt;
                            </a>
                        </li>
            `;
            
            // Add page numbers
            for (let i = 1; i <= totalPages; i++) {
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
            
            if (page < 1 || page > totalPages) return;
            
            currentPage = page;
            applyPagination(filteredRows);
            updatePaginationControls(filteredRows.length);
        }

        // Function to view appointment details
        function viewDetails(appointment) {
            // Populate modal with appointment data
            document.getElementById('modalAppointmentId').textContent = 'APP-' + (appointment.id || '000');
            document.getElementById('modalCustomer').textContent = appointment.customer;
            
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
            
            document.getElementById('modalDate').textContent = appointment.date;
            document.getElementById('modalTime').textContent = appointment.time;
            document.getElementById('modalServiceType').textContent = appointment.serviceType;
            document.getElementById('modalAppliancesType').textContent = appointment.appliancesType || 'Not Specified';
            document.getElementById('modalTotalAmount').textContent = appointment.totalAmount || '₱0.00';
            document.getElementById('modalTechnician').textContent = appointment.technician;
            document.getElementById('modalDescription').textContent = appointment.description;
            document.getElementById('modalCreated').textContent = appointment.created;
            
            // Handle secondary technician
            const secondaryTechnicianSection = document.getElementById('secondaryTechnicianSection');
            const modalSecondaryTechnician = document.getElementById('modalSecondaryTechnician');
            
            if (appointment.secondaryTechnician && appointment.secondaryTechnician.trim() !== '') {
                modalSecondaryTechnician.textContent = appointment.secondaryTechnician;
                secondaryTechnicianSection.style.display = 'block';
            } else {
                secondaryTechnicianSection.style.display = 'none';
            }
            
            // Handle cost justification
            const modalCostJustification = document.getElementById('modalCostJustification');
            
            if (appointment.costJustification && appointment.costJustification.trim() !== '' && appointment.costJustification !== 'No cost justification provided.') {
                modalCostJustification.textContent = appointment.costJustification;
            } else {
                modalCostJustification.textContent = 'No cost justification provided.';
            }
            
            // Handle rating stars and value
            const modalRatingStars = document.getElementById('modalRatingStars');
            const modalRatingValue = document.getElementById('modalRatingValue');
            const rating = parseFloat(appointment.rating) || 0;
            
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
            modalRatingValue.textContent = `(${rating.toFixed(1)}/5)`;
            
            // Handle comment section
            const modalComment = document.getElementById('modalComment');
            
            if (appointment.comment && appointment.comment !== 'No Comment' && appointment.comment.trim() !== '') {
                modalComment.textContent = appointment.comment;
            } else {
                modalComment.textContent = 'No additional comments provided.';
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