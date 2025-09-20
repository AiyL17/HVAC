

<!-- Dashboard consistent styling with admin interface -->
<style>
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

    /* Container and Layout Styling */
    .container {
        margin-top: -20px;
    }  
    .container.mt-4 {
        padding: 1px;
        height: 820px;
        margin-bottom: 15px;
    }  
    
    .row.mb-3.g-2 {
        padding: 15px;
    }
    
    .card.p-2.p-md-3.round_md {
        margin-left: 10px;
        margin-right: 10px;
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

    /* Mobile Cards - Hidden on desktop */
    .mobile-cards {
        display: none;
    }

    /* Empty State Styling */
    .empty-state {
        padding: 40px 20px;
    }

    .empty-state-mobile {
        padding: 60px 20px;
        background: white;
        border-radius: 8px;
        margin: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #dee2e6;
    }

    /* Appointment card styling for mobile */
    .appointment-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #dee2e6;
        margin-bottom: 15px;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .appointment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
        color: #495057;
        font-size: 0.9rem;
    }

    .appointment-info-row {
        display: flex;
        align-items: center;
        padding: 8px 15px;
        gap: 10px;
        border-bottom: 1px solid #f8f9fa;
    }

    .appointment-info-row i {
        width: 16px;
        color: #6c757d;
        flex-shrink: 0;
    }

    .appointment-card-actions {
        padding: 12px 15px;
        background: #f8f9fa;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* Pagination styling */
    .pagination-container {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }

    /* Header and Filter Mobile Responsiveness */
    .filter-form .row {
        align-items: center;
    }

    .filter-form .input-group {
        max-width: 100% !important;
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
    
    /* Ensure modal content is properly positioned */
    .modal {
        z-index: 1050;
    }
    
    /* Responsive breakpoints */
    @media (max-width: 991.98px) {
        .desktop-table {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .dashboard-card-body {
            padding: 15px;
        }
        
        .filter-form .col-md-6 {
            margin-top: 10px;
        }
    }

    /* Mobile filter layout - Status and Payment Status in 2 columns, Search below in 1 column */
    @media (max-width: 767.98px) {
        .filter-form .row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 0;
        }
        
        .filter-form .row > div:nth-child(1),
        .filter-form .row > div:nth-child(2) {
            flex: 0 0 calc(50% - 5px) !important;
            max-width: calc(50% - 5px) !important;
            margin-bottom: 10px;
        }
        
        .filter-form .row > div:nth-child(3) {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            margin-top: 0px;
            margin-bottom: 0;
        }
        
        /* Override Bootstrap column classes on mobile */
        .filter-form .col-6 {
            flex: 0 0 calc(50% - 5px) !important;
            max-width: calc(50% - 5px) !important;
        }
        
        .filter-form .col-12 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
        
        /* Reduce label spacing on mobile */
        .filter-form .form-label {
            margin-bottom: 4px !important;
        }
        
        /* Reduce card body padding on mobile */
        .dashboard-card-body {
            padding: 15px 15px 10px 15px !important;
        }
    }

    @media (max-width: 575.98px) {
        .dashboard-card-header {
            padding: 12px 15px;
            font-size: 1rem;
        }
        
        .appointment-card-actions .btn {
            font-size: 0.8rem;
            padding: 4px 8px;
        }
    }
</style>

    <?php
    // Pagination settings
    $recordsPerPage = 10;
    $currentPage = isset($_GET['page_num']) ? max(1, intval($_GET['page_num'])) : 1;
    $offset = ($currentPage - 1) * $recordsPerPage;

    // Get filter parameters
    $statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
    $paymentFilter = isset($_GET['payment']) ? $_GET['payment'] : '';
    $searchFilter = isset($_GET['search']) ? $_GET['search'] : '';

    // Build WHERE clause for filters
    $whereConditions = [];
    $params = [];

    if (!empty($statusFilter)) {
        $whereConditions[] = "a_s.app_status_name = ?";
        $params[] = $statusFilter;
    }

    if (!empty($paymentFilter)) {
        $whereConditions[] = "a.payment_status = ?";
        $params[] = $paymentFilter;
    }

    if (!empty($searchFilter)) {
        $whereConditions[] = "(CONCAT(COALESCE(cust.user_name, ''), ' ', COALESCE(cust.user_midname, ''), ' ', COALESCE(cust.user_lastname, '')) LIKE ?)";
        $params[] = '%' . $searchFilter . '%';
    }

    $whereClause = '';
    if (!empty($whereConditions)) {
        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
    }

    // Count total records for pagination
    $countQuery = $pdo->prepare("SELECT COUNT(*) as total FROM appointment a
        LEFT JOIN appointment_status a_s ON a.app_status_id = a_s.app_status_id
        LEFT JOIN user cust ON a.user_id = cust.user_id
        $whereClause");
    $countQuery->execute($params);
    $totalRecords = $countQuery->fetch(PDO::FETCH_OBJ)->total;
    $totalPages = ceil($totalRecords / $recordsPerPage);

    // Get appointments for current page
    $sql = "SELECT
        a.app_id, a.app_schedule, a.app_desc, a.payment_status, a.decline_justification, a.service_type_id, a.app_created, a.app_price, a.app_justification, a.app_status_id, a.app_rating, a.app_comment, a.appliances_type_id, a.user_technician_2,
        COALESCE(a_s.app_status_name, 'Unknown Status') as app_status_name,
        COALESCE(s.service_type_name, 'Unknown Service') as service_type_name,
        s.service_type_price_min,
        s.service_type_price_max,
        COALESCE(at.appliances_type_name, 'Not Specified') as appliances_type_name,
        COALESCE(cust.user_name, 'Unknown') as customer_fname, 
        COALESCE(cust.user_midname, '') as customer_mname, 
        COALESCE(cust.user_lastname, 'Customer') as customer_lname,
        cust.house_building_street as customer_house_building_street,
        COALESCE(ata.barangay, cust.barangay) as customer_barangay,
        COALESCE(ata.municipality_city, cust.municipality_city) as customer_municipality_city,
        COALESCE(ata.province, cust.province) as customer_province,
        COALESCE(ata.zip_code, cust.zip_code) as customer_zip_code,
        COALESCE(tech.user_name, 'Unknown') as tech_fname, 
        COALESCE(tech.user_midname, '') as tech_mname, 
        COALESCE(tech.user_lastname, 'Technician') as tech_lname,
        COALESCE(tech2.user_name, '') as tech2_fname, 
        COALESCE(tech2.user_midname, '') as tech2_mname, 
        COALESCE(tech2.user_lastname, '') as tech2_lname
    FROM appointment a
        LEFT JOIN appointment_status a_s ON a.app_status_id = a_s.app_status_id
        LEFT JOIN user cust ON a.user_id = cust.user_id
        LEFT JOIN appointment_transaction_address ata ON a.app_id = ata.app_id
        LEFT JOIN user tech ON a.user_technician = tech.user_id
        LEFT JOIN user tech2 ON a.user_technician_2 = tech2.user_id
        LEFT JOIN service_type s ON a.service_type_id = s.service_type_id
        LEFT JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        $whereClause
    ORDER BY a.app_created DESC
    LIMIT $recordsPerPage OFFSET $offset";

    $query = $pdo->prepare($sql);
    $query->execute($params);
    $appointments = $query->fetchAll(PDO::FETCH_OBJ);

    $statusQuery = $pdo->query('SELECT * FROM appointment_status ORDER BY app_status_name');
    $statuses = $statusQuery->fetchAll(PDO::FETCH_OBJ);
    ?>

<h3 style="margin-top: 25px; text-align:left;"><?= ucfirst($_GET['page']) ?></h3>
    
    <!-- Filters and Search -->
    <div class="dashboard-card mb-4">
        <div class="dashboard-card-header">
            <i class="bi bi-funnel me-2"></i>Filter Appointments
        </div>
        <div class="dashboard-card-body py-3">
            <form method="GET" action="" class="filter-form">
                <input type="hidden" name="page" value="<?= htmlspecialchars($_GET['page']) ?>">
                <!-- First Row: Status and Payment Status (2 columns on mobile, 3 columns on desktop) -->
                <div class="row g-3">
                    <div class="col-md-4 col-6">
                        <label class="form-label small text-muted mb-1">Status</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <?php foreach ($statuses as $status) :
                                if (in_array($status->app_status_name, ['Pending Payment', 'To Rate'])) continue;
                            ?>
                                <option value="<?= htmlspecialchars($status->app_status_name) ?>" 
                                        <?= $statusFilter === $status->app_status_name ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($status->app_status_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 col-6">
                        <label class="form-label small text-muted mb-1">Payment Status</label>
                        <select name="payment" class="form-select" onchange="this.form.submit()">
                            <option value="">All Payment Statuses</option>
                            <option value="Paid" <?= $paymentFilter === 'Paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="Unpaid" <?= $paymentFilter === 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
                        </select>
                    </div>
                    <!-- Search field - full width on mobile, 1/3 width on desktop -->
                    <div class="col-md-4 col-12">
                        <label class="form-label small text-muted mb-1">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control search-input" id="searchInput" name="search" style="font-weight: 400; color: #505050;" placeholder="Search by customer name..." value="<?= htmlspecialchars($searchFilter) ?>">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
        
    <!-- Appointments Table -->
    <div class="dashboard-card">
        
        <!-- Desktop Table -->
        <div class="table-container">
            <div class="table-responsive desktop-table">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Service Type</th>
                        <th scope="col">Date & Time</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Payment Status</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted mt-3">No Appointments Found</h5>
                                    <p class="text-muted mb-0">There are no appointments matching your current filters.</p>
                                    <?php if (!empty($statusFilter) || !empty($paymentFilter) || !empty($searchFilter)): ?>
                                        <div class="mt-3">
                                            <a href="?page=<?= urlencode($_GET['page']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-arrow-clockwise me-1"></i>Clear Filters
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($appointments as $app): ?>
                            <?php
                                $userName = trim($app->customer_fname . ' ' . $app->customer_mname . ' ' . $app->customer_lname);
                                $technicianName = trim($app->tech_fname . ' ' . $app->tech_mname . ' ' . $app->tech_lname);
                                $technician2Name = trim($app->tech2_fname . ' ' . $app->tech2_mname . ' ' . $app->tech2_lname);
                                $appSchedule = new DateTime($app->app_schedule);

                                // Define classes for different statuses
                                $statusClass = '';
                                switch ($app->app_status_name) {
                                    case 'Approved':
                                        $statusClass = 'badge bg-primary';
                                        break;
                                    case 'Pending':
                                        $statusClass = 'badge bg-secondary';
                                        break;
                                    case 'Completed':
                                        $statusClass = 'badge bg-success';
                                        break;
                                    case 'Cancelled':
                                        $statusClass = 'badge bg-danger';
                                        break;
                                    case 'In Progress':
                                        $statusClass = 'badge bg-warning';
                                        break;
                                    default:
                                        $statusClass = 'badge bg-danger';
                                }

                                $paymentStatusClass = $app->payment_status === 'Paid' ? 'badge bg-success' : 'badge bg-danger';
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($userName) ?></td>
                                <td><?= htmlspecialchars($app->service_type_name) ?></td>
                                <td><?= $appSchedule->format("l, F j, Y, g:i A") ?></td>
                                <td class="text-center"><span style="width: 90px;" class="<?= $statusClass ?>"><?= htmlspecialchars($app->app_status_name) ?></span></td>
                                <td class="text-center"><span style="width: 80px;" class="<?= $paymentStatusClass ?>"><?= htmlspecialchars($app->payment_status) ?></span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary view-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewAppointmentModal"
                                        data-app-id="<?= $app->app_id ?>"
                                        data-customer-name="<?= htmlspecialchars($userName) ?>"
                                        data-date="<?= $appSchedule->format("l, F j, Y") ?>"
                                        data-time="<?= $appSchedule->format("g:i A") ?>"
                                        data-app-created="<?= (new DateTime($app->app_created))->format('l, F j, Y \a\t g:i A') ?>"
                                        data-service-type="<?= htmlspecialchars($app->service_type_name) ?>"
                                        data-service-type-id="<?= $app->service_type_id ?>"
                                        data-appliances-type="<?= htmlspecialchars($app->appliances_type_name ?: 'Not Specified') ?>"
                                        data-technician="<?= htmlspecialchars($technicianName) ?>"
                                        data-technician2="<?= htmlspecialchars($technician2Name) ?>"
                                        data-payment-status-html='<span class="<?= $paymentStatusClass ?>"><?= htmlspecialchars($app->payment_status) ?></span>'
                                        data-payment-status-name="<?= htmlspecialchars($app->payment_status) ?>"
                                        data-status-html='<span class="<?= $statusClass ?>"><?= htmlspecialchars($app->app_status_name) ?></span>'
                                        data-status-name="<?= htmlspecialchars($app->app_status_name) ?>"
                                        data-description="<?= htmlspecialchars($app->app_desc) ?>"
                                        data-decline-justification="<?= htmlspecialchars($app->decline_justification ?? '') ?>"
                                        data-app-price="<?= htmlspecialchars($app->app_price ?? '') ?>"
                                        data-app-justification="<?= htmlspecialchars($app->app_justification ?? '') ?>"
                                        data-app-status-id="<?= $app->app_status_id ?>"
                                        data-service-price-min="<?= htmlspecialchars($app->service_type_price_min ?? '') ?>"
                                        data-service-price-max="<?= htmlspecialchars($app->service_type_price_max ?? '') ?>"
                                        data-app-rating="<?= htmlspecialchars($app->app_rating ?? '0') ?>"
                                        data-app-comment="<?= htmlspecialchars($app->app_comment ?? '') ?>"
                                        data-customer-house-building-street="<?= htmlspecialchars($app->customer_house_building_street ?? '') ?>"
                                        data-customer-barangay="<?= htmlspecialchars($app->customer_barangay ?? '') ?>"
                                        data-customer-municipality-city="<?= htmlspecialchars($app->customer_municipality_city ?? '') ?>"
                                        data-customer-province="<?= htmlspecialchars($app->customer_province ?? '') ?>"
                                        data-customer-zip-code="<?= htmlspecialchars($app->customer_zip_code ?? '') ?>">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>

        <!-- Mobile Cards (Hidden on Desktop) -->
        <div class="mobile-cards">
            <?php if (empty($appointments)): ?>
                <div class="empty-state-mobile text-center py-5">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Appointments Found</h5>
                    <p class="text-muted mb-0 px-3">There are no appointments matching your current filters.</p>
                    <?php if (!empty($statusFilter) || !empty($paymentFilter) || !empty($searchFilter)): ?>
                        <div class="mt-4">
                            <a href="?page=<?= urlencode($_GET['page']) ?>" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($appointments as $app): ?>
                <?php
                    $userName = trim($app->customer_fname . ' ' . $app->customer_mname . ' ' . $app->customer_lname);
                    $technicianName = trim($app->tech_fname . ' ' . $app->tech_mname . ' ' . $app->tech_lname);
                    $technician2Name = trim($app->tech2_fname . ' ' . $app->tech2_mname . ' ' . $app->tech2_lname);
                    $appSchedule = new DateTime($app->app_schedule);

                    // Define classes for different statuses
                    $statusClass = '';
                    switch ($app->app_status_name) {
                        case 'Approved':
                            $statusClass = 'badge bg-primary';
                            break;
                        case 'Pending':
                            $statusClass = 'badge bg-secondary';
                            break;
                        case 'Completed':
                            $statusClass = 'badge bg-success';
                            break;
                        case 'Cancelled':
                            $statusClass = 'badge bg-danger';
                            break;
                        case 'In Progress':
                            $statusClass = 'badge bg-warning';
                            break;
                        default:
                            $statusClass = 'badge bg-danger';
                    }

                    $paymentStatusClass = $app->payment_status === 'Paid' ? 'badge bg-success' : 'badge bg-danger';
                ?>
                <div class="appointment-card">
                    <div class="appointment-card-header">
                        <div class="appointment-id" style="color: #007bff;"><span style="text-decoration: underline;">APP</span>-<?= $app->app_id ?></div>
                        <div class="appointment-status">
                            <span class="<?= $statusClass ?>"><?= htmlspecialchars($app->app_status_name) ?></span>
                        </div>
                    </div>
                    
                    <div class="appointment-info-row">
                        <i class="bi bi-person-fill"></i>
                        <div class="text-truncate"><?= htmlspecialchars($userName) ?></div>
                    </div>
                    
                    <div class="appointment-info-row">
                        <i class="bi bi-tools"></i>
                        <div class="text-truncate"><?= htmlspecialchars($app->service_type_name) ?></div>
                    </div>
                    
                    <div class="appointment-info-row">
                        <i class="bi bi-calendar-event"></i>
                        <div><?= $appSchedule->format("M j, Y g:i A") ?></div>
                    </div>
                    
                    <div class="appointment-info-row">
                        <i class="bi bi-credit-card"></i>
                        <div>
                            <span class="<?= $paymentStatusClass ?>"><?= htmlspecialchars($app->payment_status) ?></span>
                        </div>
                    </div>
                    
                    <?php if (!empty($technicianName)): ?>
                    <div class="appointment-info-row">
                        <i class="bi bi-person-gear"></i>
                        <div class="text-truncate"><?= htmlspecialchars($technicianName) ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($technician2Name)): ?>
                    <div class="appointment-info-row">
                        <i class="bi bi-person-gear"></i>
                        <div class="text-truncate"><?= htmlspecialchars($technician2Name) ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="appointment-card-actions">
                        <button type="button" class="btn btn-sm btn-outline-primary view-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#viewAppointmentModal"
                            data-app-id="<?= $app->app_id ?>"
                            data-customer-name="<?= htmlspecialchars($userName) ?>"
                            data-date="<?= $appSchedule->format('l, F j, Y') ?>"
                            data-time="<?= $appSchedule->format('g:i A') ?>"
                            data-app-created="<?= (new DateTime($app->app_created))->format('l, F j, Y \a\t g:i A') ?>"
                            data-service-type="<?= htmlspecialchars($app->service_type_name) ?>"
                            data-service-type-id="<?= $app->service_type_id ?>"
                            data-appliances-type="<?= htmlspecialchars($app->appliances_type_name ?: 'Not Specified') ?>"
                            data-technician="<?= htmlspecialchars($technicianName) ?>"
                            data-technician2="<?= htmlspecialchars($technician2Name) ?>"
                            data-payment-status-html='<span class="<?= $paymentStatusClass ?>"><?= htmlspecialchars($app->payment_status) ?></span>'
                            data-payment-status-name="<?= htmlspecialchars($app->payment_status) ?>"
                            data-status-html='<span class="<?= $statusClass ?>"><?= htmlspecialchars($app->app_status_name) ?></span>'
                            data-status-name="<?= htmlspecialchars($app->app_status_name) ?>"
                            data-description="<?= htmlspecialchars($app->app_desc) ?>"
                            data-decline-justification="<?= htmlspecialchars($app->decline_justification ?? '') ?>"
                            data-app-price="<?= htmlspecialchars($app->app_price ?? '') ?>"
                            data-app-justification="<?= htmlspecialchars($app->app_justification ?? '') ?>"
                            data-app-status-id="<?= $app->app_status_id ?>"
                            data-service-price-min="<?= htmlspecialchars($app->service_type_price_min ?? '') ?>"
                            data-service-price-max="<?= htmlspecialchars($app->service_type_price_max ?? '') ?>"
                            data-app-rating="<?= htmlspecialchars($app->app_rating ?? '0') ?>"
                            data-app-comment="<?= htmlspecialchars($app->app_comment ?? '') ?>"
                            data-customer-house-building-street="<?= htmlspecialchars($app->customer_house_building_street ?? '') ?>"
                            data-customer-barangay="<?= htmlspecialchars($app->customer_barangay ?? '') ?>"
                            data-customer-municipality-city="<?= htmlspecialchars($app->customer_municipality_city ?? '') ?>"
                            data-customer-province="<?= htmlspecialchars($app->customer_province ?? '') ?>"
                            data-customer-zip-code="<?= htmlspecialchars($app->customer_zip_code ?? '') ?>">
                            <i class="bi bi-eye"></i> View
                        </button>
                        
                        <?php if ($app->app_status_name === 'Pending'): ?>
                        <button type="button" class="btn btn-sm btn-success" onclick="updateAppointment(<?= $app->app_id ?>, 'accept')">
                            <i class="bi bi-check-circle"></i> Accept
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#declineModal" onclick="setDeclineAppointmentId(<?= $app->app_id ?>)">
                            <i class="bi bi-x-circle"></i> Decline
                        </button>
                        <?php elseif ($app->app_status_name === 'Completed' && $app->payment_status === 'Unpaid'): ?>
                        <button type="button" class="btn btn-sm btn-warning" onclick="updateAppointment(<?= $app->app_id ?>, 'mark_as_paid')">
                            <i class="bi bi-cash"></i> Mark Paid
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        </div>
    </div>
    
    <!-- Pagination Controls -->
    <div class="pagination-container">
        <?php if ($totalPages > 0): ?>
        <nav aria-label="Page navigation" class="d-flex justify-content-center">
            <ul class="pagination p-2 justify-content-center pagination-sm">
                <li class="page-item <?= $currentPage === 1 ? 'disabled' : '' ?>">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="<?= $currentPage > 1 ? '?page=' . urlencode($_GET['page']) . '&page_num=' . ($currentPage - 1) . (!empty($statusFilter) ? '&status=' . urlencode($statusFilter) : '') . (!empty($paymentFilter) ? '&payment=' . urlencode($paymentFilter) : '') . (!empty($searchFilter) ? '&search=' . urlencode($searchFilter) : '') : '#' ?>" aria-label="Previous">
                        &lt;
                    </a>
                </li>
                
                <?php
                // Flexible pagination logic that handles any number of pages
                if ($totalPages <= 7) {
                    // Show all pages if total pages is small
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $isActive = ($i == $currentPage);
                        echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                        echo '<a class="page-link ' . ($isActive ? 'text-light' : 'text-dark') . ' rounded-pill border-0 p-2 px-3" href="?page=' . urlencode($_GET['page']) . '&page_num=' . $i . (!empty($statusFilter) ? '&status=' . urlencode($statusFilter) : '') . (!empty($paymentFilter) ? '&payment=' . urlencode($paymentFilter) : '') . (!empty($searchFilter) ? '&search=' . urlencode($searchFilter) : '') . '">' . $i . '</a>';
                        echo '</li>';
                    }
                } else {
                    // Complex pagination for many pages
                    $startPage = 1;
                    $endPage = $totalPages;
                    
                    // Always show first page
                    $isActive = ($currentPage == 1);
                    echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                    echo '<a class="page-link ' . ($isActive ? 'text-light' : 'text-dark') . ' rounded-pill border-0 p-2 px-3" href="?page=' . urlencode($_GET['page']) . '&page_num=1' . (!empty($statusFilter) ? '&status=' . urlencode($statusFilter) : '') . (!empty($paymentFilter) ? '&payment=' . urlencode($paymentFilter) : '') . (!empty($searchFilter) ? '&search=' . urlencode($searchFilter) : '') . '">1</a>';
                    echo '</li>';
                    
                    // Calculate range around current page
                    $rangeStart = max(2, $currentPage - 2);
                    $rangeEnd = min($totalPages - 1, $currentPage + 2);
                    
                    // Add ellipsis after first page if needed
                    if ($rangeStart > 2) {
                        echo '<li class="page-item disabled">';
                        echo '<span class="page-link text-dark rounded-pill border-0 p-2 px-3">...</span>';
                        echo '</li>';
                    }
                    
                    // Show pages around current page
                    for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
                        $isActive = ($i == $currentPage);
                        echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                        echo '<a class="page-link ' . ($isActive ? 'text-light' : 'text-dark') . ' rounded-pill border-0 p-2 px-3" href="?page=' . urlencode($_GET['page']) . '&page_num=' . $i . (!empty($statusFilter) ? '&status=' . urlencode($statusFilter) : '') . (!empty($paymentFilter) ? '&payment=' . urlencode($paymentFilter) : '') . (!empty($searchFilter) ? '&search=' . urlencode($searchFilter) : '') . '">' . $i . '</a>';
                        echo '</li>';
                    }
                    
                    // Add ellipsis before last page if needed
                    if ($rangeEnd < $totalPages - 1) {
                        echo '<li class="page-item disabled">';
                        echo '<span class="page-link text-dark rounded-pill border-0 p-2 px-3">...</span>';
                        echo '</li>';
                    }
                    
                    // Always show last page (if it's not page 1)
                    if ($totalPages > 1) {
                        $isActive = ($currentPage == $totalPages);
                        echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                        echo '<a class="page-link ' . ($isActive ? 'text-light' : 'text-dark') . ' rounded-pill border-0 p-2 px-3" href="?page=' . urlencode($_GET['page']) . '&page_num=' . $totalPages . (!empty($statusFilter) ? '&status=' . urlencode($statusFilter) : '') . (!empty($paymentFilter) ? '&payment=' . urlencode($paymentFilter) : '') . (!empty($searchFilter) ? '&search=' . urlencode($searchFilter) : '') . '">' . $totalPages . '</a>';
                        echo '</li>';
                    }
                }
                ?>
                
                <li class="page-item <?= $currentPage === $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="<?= $currentPage < $totalPages ? '?page=' . urlencode($_GET['page']) . '&page_num=' . ($currentPage + 1) . (!empty($statusFilter) ? '&status=' . urlencode($statusFilter) : '') . (!empty($paymentFilter) ? '&payment=' . urlencode($paymentFilter) : '') . (!empty($searchFilter) ? '&search=' . urlencode($searchFilter) : '') : '#' ?>" aria-label="Next">
                        &gt;
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<!-- View Appointment Modal -->
<div class="modal fade" id="viewAppointmentModal" tabindex="-1" aria-labelledby="viewAppointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content round_lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="viewAppointmentModalLabel"><i class="bi bi-card-list me-2"></i>Appointment Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
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
        <div class="mt-3" id="declineJustificationSection" style="display: none;">
            <h6 class="text-primary"><i class="bi bi-exclamation-circle me-2"></i>Decline Justification</h6>
            <p id="modalDeclineJustification" class="p-3 bg-light rounded border"></p>
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
      </div>
      <div class="modal-footer">
        <div class="w-100 mb-2 text-center">
          <small class="text-muted">
            <i class="bi bi-calendar-plus me-1"></i>
            Appointment Created: <span id="modalCreatedDate"></span>
          </small>
        </div>
        <button type="button" class="btn btn-secondary" id="declineBtn" style="display: none;">Decline</button>
        <button type="button" class="btn btn-primary" id="acceptBtn" style="display: none;">Accept</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeBtn" style="display: none;">Close</button>
        <button type="button" class="btn btn-primary" id="markAsPaidBtn" style="display: none;">Mark as Paid</button>
    </div>
    </div>
  </div>
</div>

<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="declineModalLabel">Decline Justification</h5>
        <button type="button" class="btn-close bg-secondary" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="declineForm">
          <div class="mb-3">
            <label for="justification" class="form-label">Please provide a reason for declining this appointment:</label>
            <textarea class="form-control" id="justification" rows="3" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitDeclineBtn">Submit</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var viewAppointmentModal = document.getElementById('viewAppointmentModal');
    var declineModal = document.getElementById('declineModal');
    var declineForm = document.getElementById('declineForm');
    var submitDeclineBtn = document.getElementById('submitDeclineBtn');
    // Use global currentAppointmentId variable (no local declaration)

    viewAppointmentModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        
        currentAppointmentId = button.dataset.appId;
        var customerName = button.dataset.customerName;
        var date = button.dataset.date;
        var time = button.dataset.time;
        var serviceType = button.dataset.serviceType;
        var serviceTypeId = button.dataset.serviceTypeId;
        var technician = button.dataset.technician;
        var technician2 = button.dataset.technician2;
        var paymentStatusHtml = button.dataset.paymentStatusHtml;
        var paymentStatusName = button.dataset.paymentStatusName;
        var statusHtml = button.dataset.statusHtml;
        var statusName = button.dataset.statusName;
        var description = button.dataset.description;
        var appliancesType = button.dataset.appliancesType;
        var declineJustification = button.dataset.declineJustification;
        var appPrice = button.dataset.appPrice;
        var appJustification = button.dataset.appJustification;
        var appStatusId = button.dataset.appStatusId;
        var servicePriceMin = button.dataset.servicePriceMin;
        var servicePriceMax = button.dataset.servicePriceMax;

        var modal = this;
        modal.querySelector('#modalCustomerName').textContent = customerName;
        
        // Set appointment creation date
        var appCreated = button.dataset.appCreated;
        modal.querySelector('#modalCreatedDate').textContent = appCreated || 'N/A';
        
        // Combine customer address fields into a single address string
        var houseBuildingStreet = button.dataset.customerHouseBuildingStreet;
        var barangay = button.dataset.customerBarangay;
        var municipalityCity = button.dataset.customerMunicipalityCity;
        var province = button.dataset.customerProvince;
        var zipCode = button.dataset.customerZipCode;
        
        // Create a formatted address string
        var addressParts = [];
        if (houseBuildingStreet && houseBuildingStreet.trim() !== '') addressParts.push(houseBuildingStreet);
        if (barangay && barangay.trim() !== '') addressParts.push(barangay);
        if (municipalityCity && municipalityCity.trim() !== '') addressParts.push(municipalityCity);
        if (province && province.trim() !== '') addressParts.push(province);
        if (zipCode && zipCode.trim() !== '') addressParts.push(zipCode);
        
        var customerAddress = addressParts.join(', ');
        modal.querySelector('#modalCustomerAddress').textContent = customerAddress;
        
        modal.querySelector('#modalDate').textContent = date;
        modal.querySelector('#modalTime').textContent = time;
        modal.querySelector('#modalServiceType').textContent = serviceType;
        modal.querySelector('#modalTechnician').textContent = technician;
        
        // Handle second technician display
        var secondTechnicianSection = modal.querySelector('#secondTechnicianSection');
        var modalTechnician2 = modal.querySelector('#modalTechnician2');
        
        if (technician2 && technician2.trim() !== '') {
            modalTechnician2.textContent = technician2;
            secondTechnicianSection.style.display = 'block';
        } else {
            secondTechnicianSection.style.display = 'none';
        }
        
        modal.querySelector('#modalPaymentStatus').textContent = paymentStatusName;
        modal.querySelector('#modalStatus').textContent = statusName;
        modal.querySelector('#modalDescription').textContent = description;
        
        // Display the actual appliance type chosen during appointment creation
        console.log('Appliances Type Data:', appliancesType);
        console.log('Appliances Type Length:', appliancesType ? appliancesType.length : 'null/undefined');
        
        // Ensure appliances type is always displayed, even if empty
        var appliancesTypeDisplay = appliancesType && appliancesType.trim() !== '' ? appliancesType : 'Not Specified';
        modal.querySelector('#modalAppliancesType').textContent = appliancesTypeDisplay;
        
        // Handle decline justification display
        var declineJustificationSection = document.getElementById('declineJustificationSection');
        var modalDeclineJustification = document.getElementById('modalDeclineJustification');
        
        console.log('Status Name:', statusName);
        console.log('Decline Justification:', declineJustification);
        
        // Show decline justification for declined appointments only (not cancelled)
        if (statusName === 'Declined') {
            if (declineJustification && declineJustification.trim() !== '') {
                modalDeclineJustification.textContent = declineJustification;
                declineJustificationSection.style.display = 'block';
            } else {
                // Show a default message if no justification is found
                modalDeclineJustification.textContent = 'No justification provided.';
                declineJustificationSection.style.display = 'block';
            }
        } else {
            declineJustificationSection.style.display = 'none';
        }

        // Handle finalized price and cost justification display for completed appointments
        var completedPriceSection = document.getElementById('completedPriceSection');
        var costJustificationSection = document.getElementById('costJustificationSection');
        var modalFinalizedPrice = document.getElementById('modalFinalizedPrice');
        var modalCostJustification = document.getElementById('modalCostJustification');
        
        // Show price and justification for completed appointments (status ID 3)
        if (appStatusId == '3' && statusName === 'Completed') {
            // Display finalized price if available
            if (appPrice && appPrice.trim() !== '' && appPrice !== '0') {
                var formattedPrice = '₱' + new Intl.NumberFormat('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(parseFloat(appPrice));
                modalFinalizedPrice.textContent = formattedPrice;
                completedPriceSection.style.display = 'block';
            } else {
                completedPriceSection.style.display = 'none';
            }
            
            // Display cost justification if available
            if (appJustification && appJustification.trim() !== '') {
                modalCostJustification.textContent = appJustification;
                costJustificationSection.style.display = 'block';
            } else {
                costJustificationSection.style.display = 'none';
            }
            
            // Handle feedback display for completed appointments ONLY
            var feedbackSection = document.getElementById('feedbackSection');
            var appRating = button.dataset.appRating;
            var appComment = button.dataset.appComment;
            
            // Only show feedback for completed appointments (status ID 3)
            if (appStatusId == '3' && statusName === 'Completed' && appRating && appRating !== '0' && appComment && appComment.trim() !== '' && appComment !== 'No Comment') {
                // Display star rating
                var ratingStars = document.getElementById('ratingStars');
                var ratingValue = document.getElementById('ratingValue');
                var rating = parseInt(appRating);
                
                var starsHtml = '';
                for (var i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml += '★';
                    } else {
                        starsHtml += '☆';
                    }
                }
                ratingStars.innerHTML = starsHtml;
                ratingValue.textContent = '(' + rating + '/5)';
                
                // Display comment
                document.getElementById('modalComment').textContent = appComment;
                
                feedbackSection.style.display = 'block';
            } else {
                feedbackSection.style.display = 'none';
            }
        } else {
            // Hide completed appointment sections for non-completed appointments
            completedPriceSection.style.display = 'none';
            costJustificationSection.style.display = 'none';
            
            // Show price range for non-completed appointments
            var priceRangeSection = document.getElementById('priceRangeSection');
            var modalPriceRange = document.getElementById('modalPriceRange');
            
            if (servicePriceMin && servicePriceMax && servicePriceMin.trim() !== '' && servicePriceMax.trim() !== '') {
                var minPrice = parseFloat(servicePriceMin);
                var maxPrice = parseFloat(servicePriceMax);
                
                if (minPrice > 0 && maxPrice > 0) {
                    var priceRangeText;
                    if (minPrice === maxPrice) {
                        // Fixed price
                        priceRangeText = '₱' + new Intl.NumberFormat('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(minPrice);
                    } else {
                        // Price range
                        priceRangeText = '₱' + new Intl.NumberFormat('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(minPrice) + ' - ₱' + new Intl.NumberFormat('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(maxPrice);
                    }
                    modalPriceRange.textContent = priceRangeText;
                    priceRangeSection.style.display = 'block';
                } else {
                    priceRangeSection.style.display = 'none';
                }
            } else {
                priceRangeSection.style.display = 'none';
            }
        }

        var acceptBtn = document.getElementById('acceptBtn');
        var declineBtn = document.getElementById('declineBtn');
        var closeBtn = document.getElementById('closeBtn');
        var markAsPaidBtn = document.getElementById('markAsPaidBtn');

        if (statusName === 'Pending') {
            acceptBtn.style.display = 'block';
            declineBtn.style.display = 'block';
            closeBtn.style.display = 'none';
            markAsPaidBtn.style.display = 'none';
        } else if (statusName === 'Completed' && paymentStatusName === 'Unpaid') {
            acceptBtn.style.display = 'none';
            declineBtn.style.display = 'none';
            closeBtn.style.display = 'block';
            markAsPaidBtn.style.display = 'block';
        } else {
            acceptBtn.style.display = 'none';
            declineBtn.style.display = 'none';
            closeBtn.style.display = 'block';
            markAsPaidBtn.style.display = 'none';
        }
    });

    document.getElementById('acceptBtn').addEventListener('click', function() {
        if(currentAppointmentId) updateAppointment(currentAppointmentId, 'accept');
    });

    document.getElementById('declineBtn').addEventListener('click', function() {
        var declineModal = new bootstrap.Modal(document.getElementById('declineModal'));
        declineModal.show();
    });

    document.getElementById('markAsPaidBtn').addEventListener('click', function() {
        if(currentAppointmentId) updateAppointment(currentAppointmentId, 'mark_as_paid');
    });

    submitDeclineBtn.addEventListener('click', function() {
        var justification = document.getElementById('justification').value;
        console.log('Decline submit clicked. Appointment ID:', currentAppointmentId, 'Justification:', justification);
        if (justification.trim() !== '') {
            if(currentAppointmentId) {
                console.log('Calling updateAppointment with action: decline');
                updateAppointment(currentAppointmentId, 'decline', justification);
            }
            var declineModalEl = document.getElementById('declineModal');
            var declineModal = bootstrap.Modal.getInstance(declineModalEl);
            if (declineModal) {
                declineModal.hide();
            }
            document.getElementById('justification').value = '';
        } else {
            if (typeof warningToast === 'function') {
                warningToast('Justification is required to decline an appointment.');
            } else {
                alert('Justification is required to decline an appointment.');
            }
        }
    });
});

// Global functions for mobile card buttons
var currentAppointmentId = null;

function updateAppointment(appointmentId, action, justification = '') {
    console.log('updateAppointment called with:', {
        appointmentId: appointmentId,
        action: action,
        justification: justification
    });
    
    // Show loading state
    showLoadingState(action);
    
    var formData = new FormData();
    formData.append('appointment_id', appointmentId);
    formData.append('action', action);
    if (action === 'decline') {
        formData.append('justification', justification);
    }

    // Log what's being sent to the server
    console.log('Sending to server:', {
        appointment_id: appointmentId,
        action: action,
        justification: action === 'decline' ? justification : 'N/A'
    });

    fetch('api/administrator/update_app.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Enhanced JSON response:', data);
        
        if (data.status === 'success') {
            // Show success message with appointment details
            showSuccessMessage(data);
            
            // Update the UI in real-time instead of page reload
            if (data.data && data.data.appointment) {
                updateAppointmentUI(data.data.appointment, action);
            }
            
            // Close any open modals
            closeAllModals();
            
            // Optional: Still reload for now to ensure consistency
            // You can remove this line once you're confident in the real-time updates
            setTimeout(() => location.reload(), 1500);
            
        } else {
            hideLoadingState();
            showErrorMessage(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideLoadingState();
        showErrorMessage('Network error occurred. Please try again.');
    });
}

// Helper function to show loading state
function showLoadingState(action) {
    const actionText = action === 'mark_as_paid' ? 'marking as paid' : action + 'ing';
    const loadingHtml = `
        <div class="d-flex justify-content-center align-items-center p-3">
            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
            <span>Processing ${actionText}...</span>
        </div>
    `;
    
    // Show loading in modal footer
    const modalFooter = document.querySelector('#viewAppointmentModal .modal-footer');
    if (modalFooter) {
        modalFooter.innerHTML = loadingHtml;
    }
}

// Helper function to hide loading state
function hideLoadingState() {
    // This will be called if there's an error - restore original buttons
    location.reload(); // Fallback to reload on error
}

// Helper function to show success message with professional toast
function showSuccessMessage(data) {
    // Use professional toast notification like user.php
    if (typeof successToast === 'function') {
        successToast(data.message);
    } else {
        // Fallback to alert if successToast is not available
        alert(data.message);
    }
    
    // Log appointment details for debugging
    if (data.data && data.data.appointment) {
        const app = data.data.appointment;
        console.log('Appointment updated:', {
            customer: app.customer_name,
            service: app.service_type,
            newStatus: app.status_name,
            paymentStatus: app.payment_status
        });
    }
}

// Helper function to show error message with professional toast
function showErrorMessage(message) {
    if (typeof dangerToast === 'function') {
        dangerToast(message);
    } else {
        // Fallback to alert if dangerToast is not available
        alert('Error: ' + message);
    }
}

// Helper function to update appointment UI in real-time
function updateAppointmentUI(appointment, action) {
    // Find the appointment row in the table
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const viewBtn = row.querySelector('.view-btn');
        if (viewBtn && viewBtn.dataset.appId == appointment.id) {
            // Update status badge
            const statusCell = row.cells[3]; // Status column
            const statusClass = getStatusBadgeClass(appointment.status_name);
            statusCell.innerHTML = `<span style="width: 90px;" class="${statusClass}">${appointment.status_name}</span>`;
            
            // Update payment status badge if it's a payment action
            if (action === 'mark_as_paid') {
                const paymentCell = row.cells[4]; // Payment Status column
                paymentCell.innerHTML = `<span style="width: 80px;" class="badge bg-success">${appointment.payment_status}</span>`;
            }
            
            // Update the view button data attributes for future modal opens
            viewBtn.dataset.statusName = appointment.status_name;
            viewBtn.dataset.paymentStatusName = appointment.payment_status;
            viewBtn.dataset.statusHtml = `<span class="${statusClass}">${appointment.status_name}</span>`;
            
            if (action === 'mark_as_paid') {
                viewBtn.dataset.paymentStatusHtml = `<span class="badge bg-success">${appointment.payment_status}</span>`;
            }
        }
    });
}

// Helper function to get status badge class
function getStatusBadgeClass(statusName) {
    switch (statusName) {
        case 'Approved': return 'badge bg-primary';
        case 'Pending': return 'badge bg-secondary';
        case 'Completed': return 'badge bg-success';
        case 'Cancelled': return 'badge bg-danger';
        case 'In Progress': return 'badge bg-warning';
        case 'Declined': return 'badge bg-danger';
        default: return 'badge bg-secondary';
    }
}

// Helper function to close all modals
function closeAllModals() {
    // Close main appointment modal
    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewAppointmentModal'));
    if (viewModal) {
        viewModal.hide();
    }
    
    // Close decline modal
    const declineModal = bootstrap.Modal.getInstance(document.getElementById('declineModal'));
    if (declineModal) {
        declineModal.hide();
    }
}

function setDeclineAppointmentId(appointmentId) {
    currentAppointmentId = appointmentId;
    console.log('Setting decline appointment ID:', appointmentId);
}

document.addEventListener('DOMContentLoaded', function () {
    // Continue with other DOMContentLoaded code...

    // Debounced search functionality
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const form = searchInput.closest('form');

    function debounceSearch() {
        // Clear existing timeout
        clearTimeout(searchTimeout);
        
        // Set new timeout
        searchTimeout = setTimeout(() => {
            form.submit();
        }, 800); // 800ms delay
    }

    // Add event listener for search input
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // Only trigger search if input has content or was cleared
            if (this.value.length >= 2 || this.value.length === 0) {
                debounceSearch();
            } else {
                clearTimeout(searchTimeout);
            }
        });

        // Handle Enter key press
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                form.submit();
            }
        });
    }

    // Add smooth scrolling to pagination links (matching invoice.php behavior)
    document.addEventListener('click', function(e) {
        // Check if clicked element is a pagination link
        if (e.target.closest('.pagination a.page-link') && !e.target.closest('.page-item.disabled')) {
            e.preventDefault();
            
            const link = e.target.closest('a');
            const href = link.getAttribute('href');
            
            // Only proceed if it's a valid pagination link (not # or disabled)
            if (href && href !== '#') {
                // Smooth scroll to top of the page
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                
                // Navigate to the new page after a short delay to allow smooth scroll
                setTimeout(function() {
                    window.location.href = href;
                }, 300);
            }
        }
    });

    // Check if there's a 'view' parameter in the URL to automatically open the modal
    const urlParams = new URLSearchParams(window.location.search);
    const viewAppointmentId = urlParams.get('view');
    
    if (viewAppointmentId) {
        // Find the view button for this appointment and trigger it
        const viewButton = document.querySelector(`[data-app-id="${viewAppointmentId}"]`);
        if (viewButton) {
            // Trigger the modal opening
            const modal = new bootstrap.Modal(document.getElementById('viewAppointmentModal'));
            
            // Manually set the appointment data (simulate button click)
            currentAppointmentId = viewButton.dataset.appId;
            
            // Populate modal with appointment data
            document.getElementById('modalCustomerName').textContent = viewButton.dataset.customerName || 'N/A';
            document.getElementById('modalDate').textContent = viewButton.dataset.date || 'N/A';
            document.getElementById('modalTime').textContent = viewButton.dataset.time || 'N/A';
            document.getElementById('modalServiceType').textContent = viewButton.dataset.serviceType || 'N/A';
            document.getElementById('modalTechnician').innerHTML = viewButton.dataset.technician || 'Not Assigned';
            document.getElementById('modalTechnician2').innerHTML = viewButton.dataset.technician2 || '';
            document.getElementById('modalPaymentStatus').innerHTML = viewButton.dataset.paymentStatusHtml || 'N/A';
            document.getElementById('modalStatus').innerHTML = viewButton.dataset.statusHtml || 'N/A';
            document.getElementById('modalDescription').textContent = viewButton.dataset.description || 'No description provided';
            document.getElementById('modalAppliancesType').textContent = viewButton.dataset.appliancesType || 'Not Specified';
            document.getElementById('modalPrice').textContent = viewButton.dataset.appPrice || 'Not Set';
            
            // Handle decline justification
            const declineJustification = viewButton.dataset.declineJustification;
            const declineSection = document.getElementById('declineJustificationSection');
            if (declineJustification && declineJustification.trim() !== '') {
                document.getElementById('modalDeclineJustification').textContent = declineJustification;
                declineSection.style.display = 'block';
            } else {
                declineSection.style.display = 'none';
            }
            
            // Show the modal
            modal.show();
            
            // Clean up the URL by removing the 'view' parameter
            const newUrl = new URL(window.location);
            newUrl.searchParams.delete('view');
            window.history.replaceState({}, '', newUrl);
        }
    }

});
</script>

<style>
/* Custom rounded pagination styling */
.pagination-sm .page-link {
    border-radius: 50% !important;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    border: 1px solid #dee2e6;
    color: #6c757d;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
}

.pagination-sm .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.pagination-sm .page-item:not(.active) .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #495057;
}

.pagination-sm .page-item.disabled .page-link {
    color: #6c757d;
    background-color: transparent;
    border-color: #dee2e6;
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-sm .page-item {
    margin: 0;
}

/* Mobile Responsive Styles */
@media (max-width: 991.98px) {
    /* Hide desktop table on mobile */
    .desktop-table {
        display: none !important;
    }
    
    /* Show mobile cards */
    .mobile-cards {
        display: block !important;
    }
    
    /* Filter form responsive adjustments */
    .filter-form .row > div {
        margin-bottom: 10px;
    }
    
    /* Search input mobile optimization */
    .search-input {
        font-size: 16px; /* Prevent iOS zoom */
    }
    
    /* Button adjustments */
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
}

@media (max-width: 767.98px) {
    /* Side-by-side dropdown filters on mobile */
    .filter-form .col-md-3 {
        flex: 0 0 50%;
        max-width: 50%;
        padding-right: 5px;
        padding-left: 5px;
        margin-bottom: 10px;
    }
    
    .filter-form .col-md-3:first-child {
        padding-right: 7.5px;
        padding-left: 15px;
    }
    
    .filter-form .col-md-3:nth-child(2) {
        padding-left: 7.5px;
        padding-right: 15px;
    }
    
    /* Search input takes full width */
    .filter-form .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 10px;
        padding-left: 15px;
        padding-right: 15px;
    }
    
    /* Other column types stack normally */
    .filter-form .col-md-2,
    .filter-form .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 10px;
    }
    
    /* Mobile filter form improvements */
    .filter-form .row {
        margin-bottom: 0;
    }
    
    .filter-form .col-md-6 {
        justify-content: stretch !important;
    }
    
    .filter-form .input-group {
        max-width: 100% !important;
        width: 100%;
    }
    
    .filter-form select.form-select {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 4px;
    }
    
    .filter-form input.form-control {
        font-size: 14px;
        padding: 8px 12px;
    }
    
    .filter-form .input-group-text {
        padding: 8px 12px;
        border-radius: 4px 0 0 4px;
    }
    
    /* Page title responsive */
    h3 {
        font-size: 1.75rem;
        margin-bottom: 20px;
    }
    
    /* Reduce padding on small screens */
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    /* Compact pagination on mobile */
    .pagination-sm .page-link {
        width: 28px;
        height: 28px;
        font-size: 12px;
        margin: 0 1px;
    }
}

@media (max-width: 575.98px) {
    /* Extra small screens */
    .filter-form .btn {
        width: 100%;
        margin-top: 5px;
    }
    
    /* Enhanced mobile header */
    h3 {
        font-size: 1.5rem;
        text-align: center;
        margin-bottom: 15px;
    }
    
    /* Mobile-optimized filter controls */
    .filter-form select.form-select,
    .filter-form input.form-control {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid #ced4da;
        transition: border-color 0.2s ease;
    }
    
    .filter-form select.form-select:focus,
    .filter-form input.form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .filter-form .input-group-text {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-right: none;
        background-color: #f8f9fa;
    }
    
    /* Compact search input on very small screens */
    .search-input::placeholder {
        font-size: 14px;
    }
    
    .results-info {
        font-size: 0.875rem;
        text-align: center;
        margin-bottom: 15px;
    }
    
    /* Better spacing for mobile */
    .filter-form .col-md-3,
    .filter-form .col-md-6 {
        margin-bottom: 15px;
    }
    
    .filter-form .col-md-6:last-child {
        margin-bottom: 10px;
    }
}

/* Mobile Card Styles */
.mobile-cards {
    display: none; /* Hidden by default, shown on mobile via media query */
}

.appointment-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 15px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s ease;
}

.appointment-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.appointment-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    flex-wrap: wrap;
    gap: 8px;
}

.appointment-id {
    font-weight: bold;
    color: #495057;
    font-size: 0.9rem;
}

.appointment-status {
    flex-shrink: 0;
}



.appointment-info-row {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.appointment-info-row i {
    width: 20px;
    margin-right: 8px;
    color: #6c757d;
    flex-shrink: 0;
}

.appointment-info-row .text-truncate {
    flex: 1;
    min-width: 0;
}

.appointment-card-actions {
    margin-top: 15px;
    padding-top: 12px;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.appointment-card-actions .btn {
    flex: 1;
    min-width: 80px;
    font-size: 0.8rem;
    padding: 0.375rem 0.75rem;
}

/* Mobile View Button Styling */
.mobile-cards .btn-outline-primary {
    background-color: #007bff !important;
    color: white !important;
    border-color: #007bff !important;
}

.mobile-cards .btn-outline-primary:hover {
    background-color: #0056b3 !important;
    border-color: #0056b3 !important;
    color: white !important;
}

/* Remove background colors only from service type badges in mobile view */
.mobile-cards .appointment-info-row .badge.bg-info {
    background-color: transparent !important;
    color: #6c757d !important;
    border: 1px solid #dee2e6;
}

/* Keep payment status badge colors intact - remove the override */

/* Keep appointment status badges with their original colors - no override needed */

/* Mark as Paid button styling */
.mobile-cards .btn-warning {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
    color: white !important;
}

.mobile-cards .btn-warning:hover {
    background-color: #218838 !important;
    border-color: #1e7e34 !important;
    color: white !important;
}

@media (max-width: 480px) {
    .appointment-card {
        padding: 12px;
        margin-bottom: 12px;
    }
    
    .appointment-card-actions .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .appointment-info-row {
        font-size: 0.85rem;
    }
}
</style>