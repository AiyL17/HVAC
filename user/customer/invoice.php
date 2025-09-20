<?php
$customer_id = $_SESSION['uid'];
include_once __DIR__ . '/../../config/ini.php';
$pdo = pdo_init();

// Handle status filter and search from GET
$statusFilter = $_GET['status'] ?? 'All';
$searchQuery = $_GET['search'] ?? '';

// Pagination variables
$page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

// First, get total count for pagination (before status filtering)
$countQuery = "SELECT COUNT(*) as total
FROM
    appointment a
WHERE
    a.user_id = :customer_id
    AND a.app_status_id = 3
    AND a.app_price IS NOT NULL 
    AND a.app_price != ''";

$countStmt = $pdo->prepare($countQuery);
$countStmt->bindParam(':customer_id', $customer_id);
$countStmt->execute();
$totalInvoices = $countStmt->fetch(PDO::FETCH_OBJ)->total;

// Fetch completed invoices (appointments with prices) for the logged-in customer
$query = "SELECT
    a.app_id,
    a.app_schedule,
    a.app_created,
    a.app_completed_at,
    a.app_status_id,
    a.app_price,
    a.payment_status,
    a.app_desc,
    a.app_justification,
    a.service_type_id,
    COALESCE(s.service_type_name, 'Unknown Service') as service_type_name,
    COALESCE(tech.user_name, 'Unknown') as technician_fname,
    COALESCE(tech.user_midname, '') as technician_mname,
    COALESCE(tech.user_lastname, 'Technician') as technician_lname,
    COALESCE(tech2.user_name, '') as technician2_fname,
    COALESCE(tech2.user_midname, '') as technician2_mname,
    COALESCE(tech2.user_lastname, '') as technician2_lname,
    COALESCE(cust.user_name, '') as customer_fname,
    COALESCE(cust.user_midname, '') as customer_mname,
    COALESCE(cust.user_lastname, '') as customer_lname,
    COALESCE(cust.house_building_street, '') as house_building_street,
    COALESCE(ata.barangay, cust.barangay, '') as barangay,
    COALESCE(ata.municipality_city, cust.municipality_city, '') as municipality_city,
    COALESCE(ata.province, cust.province, '') as province,
    COALESCE(ata.zip_code, cust.zip_code, '') as zip_code
FROM
    appointment a
LEFT JOIN
    service_type s ON a.service_type_id = s.service_type_id
LEFT JOIN
    user tech ON a.user_technician = tech.user_id
LEFT JOIN
    user tech2 ON a.user_technician_2 = tech2.user_id
LEFT JOIN
    user cust ON a.user_id = cust.user_id
LEFT JOIN
    appointment_transaction_address ata ON a.app_id = ata.app_id
WHERE
    a.user_id = :customer_id
    AND a.app_status_id = 3
    AND a.app_price IS NOT NULL 
    AND a.app_price != ''
ORDER BY
    a.app_created DESC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':customer_id', $customer_id);
$stmt->execute();
$allInvoices = $stmt->fetchAll(PDO::FETCH_OBJ);

// Apply status filtering and search, then pagination
$filteredInvoices = [];
$now = new DateTime();
foreach ($allInvoices as $inv) {
    $completionDate = new DateTime($inv->app_completed_at);
    $interval = $completionDate->diff($now);
    $isOverdue = ($inv->payment_status !== 'Paid' && $interval->days > 1 && $now > $completionDate);
    
    if ($inv->payment_status === 'Paid') {
        $status = 'Paid';
    } elseif ($isOverdue) {
        $status = 'Overdue';
    } else {
        $status = 'Pending';
    }
    
    // Check status filter
    $statusMatch = ($statusFilter === 'All' || $status === $statusFilter);
    
    // Check search query (search in service type name)
    $searchMatch = true;
    if (!empty($searchQuery)) {
        $searchMatch = stripos($inv->service_type_name, $searchQuery) !== false;
    }
    
    // Apply both filters
    if ($statusMatch && $searchMatch) {
        $inv->computed_status = $status;
        $filteredInvoices[] = $inv;
    }
}

// Calculate pagination for filtered results
$totalFiltered = count($filteredInvoices);
$totalPages = ceil($totalFiltered / $itemsPerPage);
$invoices = array_slice($filteredInvoices, $offset, $itemsPerPage);

// Note: Invoices are now pre-filtered and paginated
?>

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

    /* Dashboard consistent styling with admin interface */
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

    /* Enhanced pagination styling */
    .pagination-container {
        margin-top: 20px;
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

    /* Mobile responsive fixes for filter section */
    @media (max-width: 767.98px) {
        .filter-search-column {
            padding-left: 0 !important;
            margin-top: 1rem;
            align-items: stretch !important;
        }
        
        .filter-search-input {
            width: 100% !important;
            max-width: none !important;
        }
        
        .dashboard-card-body .row.g-3 {
            flex-direction: column;
        }
        
        .dashboard-card-body .col-md-6 {
            margin-bottom: 0.5rem;
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        
        /* Ensure both status and search have same width on mobile */
        .dashboard-card-body .col-md-6 .form-select,
        .dashboard-card-body .col-md-6 .input-group {
            width: 100% !important;
            max-width: none !important;
        }
    }
</style>

<div class="container mt-4">
    <h3 style="margin-top: -20px; color: #333; font-weight: 600; font-size: 27.22px; padding: 15px 20px;">My Invoices</h3>
    <!-- Filters and Search -->
    <div class="dashboard-card mb-4">
        <div class="dashboard-card-header">
            <i class="bi bi-funnel me-2"></i>Filter Invoices
        </div>
        <div class="dashboard-card-body py-3" style="border-radius: 12px;">
            <div class="row g-3" style="border-radius: 12px;">
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <form method="get" action="" class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                        <input type="hidden" name="page" value="invoice">
                        <input type="hidden" name="p" value="1">
                        <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                            <option value="All"<?= $statusFilter === 'All' ? ' selected' : '' ?>>All Status</option>
                            <option value="Pending"<?= $statusFilter === 'Pending' ? ' selected' : '' ?>>Pending</option>
                            <option value="Paid"<?= $statusFilter === 'Paid' ? ' selected' : '' ?>>Paid</option>
                            <option value="Overdue"<?= $statusFilter === 'Overdue' ? ' selected' : '' ?>>Overdue</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-6 filter-search-column" style="padding-left: 190px;">
                    <label class="form-label small text-muted mb-1">Search</label>
                    <div class="input-group filter-search-input" style="width: 300px;">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="searchInput" class="form-control" style="font-weight: 400; color: #505050;" placeholder="Search by service type..." 
                               value="<?= htmlspecialchars($searchQuery) ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Invoices Table -->
    <div class="dashboard-card">
        <!-- Desktop Table View -->
        <div class="d-none d-lg-block">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Invoice No</th>
                        <th scope="col">Date</th>
                        <th scope="col">Service</th>
                        <th scope="col" class="text-center">Amount</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="invoiceTableBody">
                    <?php
                    $displayedCount = 0;
                    foreach ($invoices as $inv):
                        $completionDate = new DateTime($inv->app_completed_at);
                        $status = $inv->computed_status;
                        $displayedCount++;
                        
                        $invoiceId = 'INV-' . str_pad($inv->app_id, 3, '0', STR_PAD_LEFT);
                        $technicianName = trim($inv->technician_fname . ' ' . $inv->technician_mname . ' ' . $inv->technician_lname);
                        $technician2Name = trim($inv->technician2_fname . ' ' . $inv->technician2_mname . ' ' . $inv->technician2_lname);
                        $customerName = trim($inv->customer_fname . ' ' . $inv->customer_mname . ' ' . $inv->customer_lname);
                        // Combine individual address fields into a single address string
                        $addressParts = [
                            $inv->house_building_street,
                            $inv->barangay,
                            $inv->municipality_city,
                            $inv->province,
                            $inv->zip_code
                        ];
                        $customerAddress = implode(', ', array_filter($addressParts, function($part) {
                            return !empty(trim($part));
                        }));
                    ?>
                        <tr>
                            <td><?= $invoiceId ?></td>
                            <td><?= $completionDate->format('Y-m-d g:i A') ?></td>
                            <td><?= htmlspecialchars($inv->service_type_name) ?></td>
                            <td class="text-center">₱<?= number_format($inv->app_price, 2) ?></td>
                            <td class="text-center">
                                <?php if ($status === 'Paid'): ?>
                                    <span class="badge bg-success" style="width: 68px;">Paid</span>
                                <?php elseif ($status === 'Overdue'): ?>
                                    <span class="badge bg-danger">Overdue</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-white">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary view-invoice-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#invoiceModal"
                                    data-invoice="<?= $invoiceId ?>"
                                    data-date="<?= $completionDate->format('l, F j, Y : g:i A') ?>"
                                    data-servicetype="<?= htmlspecialchars($inv->service_type_name) ?>"
                                    data-servicetypeid="<?= $inv->service_type_id ?>"
                                    data-status="<?= $status ?>"
                                    data-desc="<?= htmlspecialchars($inv->app_desc ?: 'No description provided') ?>"
                                    data-amount="₱<?= number_format($inv->app_price, 2) ?>"
                                    data-justification="<?= htmlspecialchars($inv->app_justification ?: 'No justification provided') ?>"
                                    data-technician="<?= htmlspecialchars($technicianName) ?>"
                                    data-technician2="<?= htmlspecialchars($technician2Name) ?>"
                                    data-customer="<?= htmlspecialchars($customerName) ?>"
                                    data-address="<?= htmlspecialchars($customerAddress) ?>"
                                    data-completed-at="<?= $inv->app_completed_at ?>"
                                >View</button>
                                <button type="button" class="btn btn-sm btn-success ms-1 download-invoice-btn" title="Download Invoice"
                                    data-invoice="<?= $invoiceId ?>"
                                    data-date="<?= $completionDate->format('l, F j, Y : g:i A') ?>"
                                    data-servicetype="<?= htmlspecialchars($inv->service_type_name) ?>"
                                    data-servicetypeid="<?= $inv->service_type_id ?>"
                                    data-status="<?= $status ?>"
                                    data-desc="<?= htmlspecialchars($inv->app_desc ?: 'No description provided') ?>"
                                    data-amount="₱<?= number_format($inv->app_price, 2) ?>"
                                    data-justification="<?= htmlspecialchars($inv->app_justification ?: 'No justification provided') ?>"
                                    data-technician="<?= htmlspecialchars($technicianName) ?>"
                                    data-technician2="<?= htmlspecialchars($technician2Name) ?>"
                                    data-customer="<?= htmlspecialchars($customerName) ?>"
                                    data-address="<?= htmlspecialchars($customerAddress) ?>"
                                >
                                    <i class="bi bi-download"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($displayedCount === 0): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted mt-3">No Invoices Found</h5>
                                    <p class="text-muted mb-0">You don't have any invoices matching the selected filters.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Card View -->
        <div class="d-lg-none" id="mobileInvoiceCardsContainer">
            <?php
            $displayedCount = 0;
            foreach ($invoices as $inv):
                $completionDate = new DateTime($inv->app_completed_at);
                $status = $inv->computed_status;
                if ($status === 'Paid') {
                    $statusClass = 'success';
                } elseif ($status === 'Overdue') {
                    $statusClass = 'danger';
                } else {
                    $statusClass = 'warning';
                }
                $displayedCount++;
                
                $invoiceId = 'INV-' . str_pad($inv->app_id, 3, '0', STR_PAD_LEFT);
                $technicianName = trim($inv->technician_fname . ' ' . $inv->technician_mname . ' ' . $inv->technician_lname);
                $technician2Name = trim($inv->technician2_fname . ' ' . $inv->technician2_mname . ' ' . $inv->technician2_lname);
                $customerName = trim($inv->customer_fname . ' ' . $inv->customer_mname . ' ' . $inv->customer_lname);
                $addressParts = [
                    $inv->house_building_street,
                    $inv->barangay,
                    $inv->municipality_city,
                    $inv->province,
                    $inv->zip_code
                ];
                $customerAddress = implode(', ', array_filter($addressParts, function($part) {
                    return !empty(trim($part));
                }));
            ?>
            <div class="card mb-3 shadow">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="card-title mb-1 fw-bold text-primary"><?= $invoiceId ?></h6>
                            <p class="card-text mb-1 text-muted small">
                                <i class="bi bi-tools me-1"></i>
                                <?= htmlspecialchars($inv->service_type_name) ?>
                            </p>
                        </div>
                        <span class="badge bg-<?= $statusClass ?> rounded-pill"><?= $status ?></span>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block"><i class="bi bi-calendar3 me-1"></i>Date:</small>
                            <small class="fw-medium"><?= $completionDate->format('M j, Y g:i A') ?></small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted d-block"><i class="bi bi-currency-peso me-1"></i>Amount:</small>
                            <small class="fw-bold text-success">₱<?= number_format($inv->app_price, 2) ?></small>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-primary text-white btn-sm view-invoice-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#invoiceModal"
                            data-invoice="<?= $invoiceId ?>"
                            data-date="<?= $completionDate->format('Y-m-d g:i A') ?>"
                            data-servicetype="<?= htmlspecialchars($inv->service_type_name) ?>"
                            data-servicetypeid="<?= $inv->service_type_id ?>"
                            data-status="<?= $status ?>"
                            data-desc="<?= htmlspecialchars($inv->app_desc ?: 'No description provided') ?>"
                            data-amount="₱<?= number_format($inv->app_price, 2) ?>"
                            data-justification="<?= htmlspecialchars($inv->app_justification ?: 'No justification provided') ?>"
                            data-technician="<?= htmlspecialchars($technicianName) ?>"
                            data-technician2="<?= htmlspecialchars($technician2Name) ?>"
                            data-customer="<?= htmlspecialchars($customerName) ?>"
                            data-address="<?= htmlspecialchars($customerAddress) ?>">
                            <i class="bi bi-eye me-1"></i>View Details
                        </button>
                        <button type="button" class="btn btn-success btn-sm download-invoice-btn"
                            data-invoice="<?= $invoiceId ?>"
                            data-date="<?= $completionDate->format('Y-m-d g:i A') ?>"
                            data-servicetype="<?= htmlspecialchars($inv->service_type_name) ?>"
                            data-servicetypeid="<?= $inv->service_type_id ?>"
                            data-status="<?= $status ?>"
                            data-desc="<?= htmlspecialchars($inv->app_desc ?: 'No description provided') ?>"
                            data-amount="₱<?= number_format($inv->app_price, 2) ?>"
                            data-justification="<?= htmlspecialchars($inv->app_justification ?: 'No justification provided') ?>"
                            data-technician="<?= htmlspecialchars($technicianName) ?>"
                            data-technician2="<?= htmlspecialchars($technician2Name) ?>"
                            data-customer="<?= htmlspecialchars($customerName) ?>"
                            data-address="<?= htmlspecialchars($customerAddress) ?>">
                            <i class="bi bi-download me-1"></i>Download
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if ($displayedCount === 0): ?>
                <div class="empty-state-mobile text-center py-5">
                    <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Invoices Found</h5>
                    <p class="text-muted mb-0 px-3">You don't have any invoices matching the selected filters.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
        
    
    <!-- Desktop Pagination -->
    <div class="d-none d-lg-block" id="paginationContainer">
        <?php if ($totalPages > 0): ?>
        <nav aria-label="Page navigation" class="text-center">
            <ul class="pagination p-2 justify-content-center pagination-sm">
                <li class="page-item <?= $page === 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $page > 1 ? '?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=' . ($page - 1) : '#' ?>" aria-label="Previous">
                        &lt;
                    </a>
                </li>
                
                <?php
                // Flexible pagination logic that handles any number of pages
                $maxVisiblePages = 7; // Maximum number of page links to show
                
                if ($totalPages <= $maxVisiblePages) {
                    // Show all pages if total pages is small
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $isActive = ($i == $page);
                        echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                        echo '<a class="page-link" href="?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=' . $i . '">' . $i . '</a>';
                        echo '</li>';
                    }
                } else {
                    // Complex pagination for many pages
                    $startPage = 1;
                    $endPage = $totalPages;
                    
                    // Always show first page
                    $isActive = ($page == 1);
                    echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                    echo '<a class="page-link" href="?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=1">1</a>';
                    echo '</li>';
                    
                    // Calculate range around current page
                    $rangeStart = max(2, $page - 2);
                    $rangeEnd = min($totalPages - 1, $page + 2);
                    
                    // Add ellipsis after first page if needed
                    if ($rangeStart > 2) {
                        echo '<li class="page-item disabled">';
                        echo '<span class="page-link">...</span>';
                        echo '</li>';
                    }
                    
                    // Show pages around current page
                    for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
                        $isActive = ($i == $page);
                        echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                        echo '<a class="page-link" href="?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=' . $i . '">' . $i . '</a>';
                        echo '</li>';
                    }
                    
                    // Add ellipsis before last page if needed
                    if ($rangeEnd < $totalPages - 1) {
                        echo '<li class="page-item disabled">';
                        echo '<span class="page-link">...</span>';
                        echo '</li>';
                    }
                    
                    // Always show last page (if it's not page 1)
                    if ($totalPages > 1) {
                        $isActive = ($page == $totalPages);
                        echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                        echo '<a class="page-link" href="?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=' . $totalPages . '">' . $totalPages . '</a>';
                        echo '</li>';
                    }
                }
                ?>
                
                <li class="page-item <?= $page === $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $page < $totalPages ? '?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=' . ($page + 1) : '#' ?>" aria-label="Next">
                        &gt;
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
    
    <!-- Mobile Pagination -->
    <div class="d-lg-none mt-3" id="mobilePaginationContainer">
        <?php if ($totalPages > 0): ?>
        <nav aria-label="Page navigation" class="text-center">
            <ul class="pagination p-2 justify-content-center pagination-sm">
                <li class="page-item <?= $page === 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $page > 1 ? '?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=' . ($page - 1) : '#' ?>" aria-label="Previous">
                        &lt;
                    </a>
                </li>
                
                <?php
                // Simplified mobile pagination - show fewer pages for mobile
                $maxVisiblePages = 3; // Show fewer pages on mobile
                
                if ($totalPages <= $maxVisiblePages) {
                    // Show all pages if total pages is small
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $isActive = ($i == $page);
                        echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                        echo '<a class="page-link" href="?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=' . $i . '">' . $i . '</a>';
                        echo '</li>';
                    }
                } else {
                    // Show current page and adjacent pages
                    $startPage = max(1, $page - 1);
                    $endPage = min($totalPages, $page + 1);
                    
                    // Show first page if not in range
                    if ($startPage > 1) {
                        echo '<li class="page-item">';
                        echo '<a class="page-link" href="?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=1">1</a>';
                        echo '</li>';
                        if ($startPage > 2) {
                            echo '<li class="page-item disabled">';
                            echo '<span class="page-link">...</span>';
                            echo '</li>';
                        }
                    }
                    
                    // Show pages around current page
                    for ($i = $startPage; $i <= $endPage; $i++) {
                        $isActive = ($i == $page);
                        echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                        echo '<a class="page-link" href="?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=' . $i . '">' . $i . '</a>';
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
                        echo '<a class="page-link" href="?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=' . $totalPages . '">' . $totalPages . '</a>';
                        echo '</li>';
                    }
                }
                ?>
                
                <li class="page-item <?= $page === $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $page < $totalPages ? '?page=invoice&status=' . urlencode($statusFilter) . '&search=' . urlencode($searchQuery) . '&p=' . ($page + 1) : '#' ?>" aria-label="Next">
                        &gt;
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
        
        <div id="noInvoicesMessage" class="text-center py-5" style="display: none;">
            <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
            <h5 class="text-muted mt-3">No invoices found</h5>
            <p class="text-muted">You don't have any invoices yet.</p>
        </div>
    </div>
</div>

<!-- Invoice Detail Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content round_lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="invoiceModalLabel"><i class="bi bi-receipt-cutoff me-2"></i>Invoice Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="text-muted small"><i class="bi bi-hash me-2"></i>Invoice Number</label>
              <div id="modalInvoiceNo" class="fs-6"></div>
            </div>
            <div class="mb-3">
              <label class="text-muted small"><i class="bi bi-calendar-event me-2"></i>Date & Time</label>
              <div id="modalDate" class="fs-6"></div>
            </div>
            <div class="mb-3">
              <label class="text-muted small"><i class="bi bi-tools me-2"></i>Service Type</label>
              <div id="modalServiceType" class="fs-6"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="text-muted small"><i class="bi bi-box-seam me-2"></i>Appliances Type</label>
              <div id="modalApplianceType" class="fs-6"></div>
            </div>
            <div class="mb-3">
              <label class="text-muted small"><i class="bi bi-check2-circle me-2"></i>Status</label>
              <div id="modalStatus" class="fs-6"></div>
            </div>
            <div class="mb-3">
              <label class="text-muted small"><i class="bi bi-cash-coin me-2"></i>Total Amount</label>
              <div id="modalAmount" class="fs-6"></div>
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
          <p id="modalDesc" class="p-3 bg-light rounded border"></p>
        </div>
        <div class="mt-3">
          <h6 class="text-primary"><i class="bi bi-justify-left me-2"></i>Cost Justification</h6>
          <p id="modalJustification" class="p-3 bg-light rounded border"></p>
        </div>
      </div>
      <div class="modal-footer">
        <div class="w-100 mb-2 text-center">
          <small class="text-muted">
            <i class="bi bi-calendar-plus me-1"></i>
            Invoice Generated: <span id="modalInvoiceDate"></span>
          </small>
        </div>
        <button type="button" class="btn bg-secondary text-white" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal event handlers
    var modalInvoiceNo = document.getElementById('modalInvoiceNo');
    var modalDate = document.getElementById('modalDate');
    var modalServiceType = document.getElementById('modalServiceType');
    var modalApplianceType = document.getElementById('modalApplianceType');
    var modalTechnician = document.getElementById('modalTechnician');
    var modalTechnician2 = document.getElementById('modalTechnician2');
    var secondTechnicianSection = document.getElementById('secondTechnicianSection');
    var modalStatus = document.getElementById('modalStatus');
    var modalDesc = document.getElementById('modalDesc');
    var modalAmount = document.getElementById('modalAmount');
    var modalJustification = document.getElementById('modalJustification');

    // Function to fetch and display first appliance type for a service type
    async function loadApplianceType(serviceTypeId) {
        try {
            const response = await fetch(`api/customer/get_appliance_types.php?service_type_id=${serviceTypeId}`);
            const data = await response.json();
            
            if (data.success && data.appliances && data.appliances.length > 0) {
                // Display the first appliance type
                modalApplianceType.textContent = data.appliances[0].appliances_type_name;
            } else {
                modalApplianceType.textContent = 'Not specified';
            }
        } catch (error) {
            console.error('Error loading appliance type:', error);
            modalApplianceType.textContent = 'Not specified';
        }
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('view-invoice-btn')) {
            modalInvoiceNo.textContent = e.target.getAttribute('data-invoice');
            modalDate.textContent = e.target.getAttribute('data-date');
            modalServiceType.textContent = e.target.getAttribute('data-servicetype');
            modalTechnician.textContent = e.target.getAttribute('data-technician');
            
            // Handle secondary technician display
            const technician2 = e.target.getAttribute('data-technician2');
            if (technician2 && technician2.trim() !== '') {
                modalTechnician2.textContent = technician2;
                secondTechnicianSection.style.display = 'block';
            } else {
                secondTechnicianSection.style.display = 'none';
            }
            
            modalStatus.innerHTML = getStatusBadge(e.target.getAttribute('data-status'));
            modalDesc.textContent = e.target.getAttribute('data-desc');
            modalAmount.textContent = e.target.getAttribute('data-amount');
            modalJustification.textContent = e.target.getAttribute('data-justification');
            
            // Load appliance type dynamically
            const serviceTypeId = e.target.getAttribute('data-servicetypeid');
            if (serviceTypeId) {
                loadApplianceType(serviceTypeId);
            } else {
                modalApplianceType.textContent = 'Not specified';
            }
            
            // Set invoice generation date (using completion date as invoice generation date)
            var invoiceDate = e.target.getAttribute('data-date');
            document.getElementById('modalInvoiceDate').textContent = invoiceDate;
        }
        
        if (e.target.classList.contains('download-invoice-btn') || e.target.closest('.download-invoice-btn')) {
            // Get the button element (handles both direct button click and icon click)
            var button = e.target.classList.contains('download-invoice-btn') ? e.target : e.target.closest('.download-invoice-btn');
            
            // Get payment status to determine format
            var paymentStatus = button.getAttribute('data-status');
            var isPaid = paymentStatus === 'Paid';
            
            // Build printable content
            var technician = button.getAttribute('data-technician') || 'Not assigned';
            var technician2 = button.getAttribute('data-technician2');
            var technicianRows = `<tr><td><b>Primary Technician:</b></td><td>${technician}</td></tr>`;
            if (technician2 && technician2.trim() !== '') {
                technicianRows += `<tr><td><b>Secondary Technician:</b></td><td>${technician2}</td></tr>`;
            }
            
            // Different content based on payment status
            var documentTitle = isPaid ? 'Receipt' : 'Invoice';
            var documentHeader = isPaid ? 'OFFICIAL RECEIPT' : 'Invoice';
            var windowTitle = isPaid ? 'Receipt' : 'Invoice';
            
            // Helper functions for Official Receipt
            function numberToWords(num) {
                if (num === 0) return 'Zero';
                
                const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
                const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
                const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
                const thousands = ['', 'Thousand', 'Million', 'Billion'];
                
                function convertHundreds(n) {
                    let result = '';
                    if (n >= 100) {
                        result += ones[Math.floor(n / 100)] + ' Hundred ';
                        n %= 100;
                    }
                    if (n >= 20) {
                        result += tens[Math.floor(n / 10)];
                        if (n % 10 !== 0) {
                            result += '-' + ones[n % 10];
                        }
                    } else if (n >= 10) {
                        result += teens[n - 10];
                    } else if (n > 0) {
                        result += ones[n];
                    }
                    return result.trim();
                }
                
                let result = '';
                let thousandIndex = 0;
                
                while (num > 0) {
                    if (num % 1000 !== 0) {
                        let chunk = convertHundreds(num % 1000);
                        if (thousands[thousandIndex]) {
                            chunk += ' ' + thousands[thousandIndex];
                        }
                        result = chunk + ' ' + result;
                    }
                    num = Math.floor(num / 1000);
                    thousandIndex++;
                }
                
                return result.trim();
            }
            
            function convertAmountToWords(amount) {
                // Remove peso sign and parse as float
                let numAmount = parseFloat(amount.toString().replace(/[₱,]/g, ''));
                let pesos = Math.floor(numAmount);
                let centavos = Math.round((numAmount - pesos) * 100);
                
                let result = numberToWords(pesos) + ' Pesos';
                if (centavos > 0) {
                    result += ' and ' + numberToWords(centavos) + ' Centavos';
                }
                return result;
            }
            
            if (isPaid) {
                // Use the official receipt format for paid invoices
                var currentDate = new Date().toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric' 
                });
                
                var technicianName = button.getAttribute('data-technician') || 'Not assigned';
                var technician2Name = button.getAttribute('data-technician2');
                var technicianInfo = technicianName;
                if (technician2Name && technician2Name.trim() !== '') {
                    technicianInfo += ' & ' + technician2Name;
                }
                
                html = `
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                      <meta charset="UTF-8">
                      <title>Official Receipt</title>
                      <style>
                        body {
                          font-family: Arial, sans-serif;
                          font-size: 12px;
                          margin: 40px;
                        }
                        .container {
                          display: flex;
                          justify-content: space-between;
                          padding: 10px;
                        }
                        .left {
                          width: 35%;
                          padding: 5px;
                          border-left: 2px dashed #000;
                          padding-left: 15px;
                        }
                        .left-bordered {
                          border: 1px solid #000;
                          padding: 8px;
                          margin-bottom: 15px;
                        }
                        .right {
                          width: 63%;
                        }
                        .title {
                          font-weight: bold;
                          text-align: center;
                          font-size: 16px;
                        }
                        .section-title {
                          background: #000;
                          color: #fff;
                          padding: 2px 5px;
                          display: inline-block;
                          margin: 10px 0;
                          font-weight: bold;
                        }
                        .line-input {
                          display: block;
                          width: 100%;
                          border-bottom: 1px solid #000;
                          margin: 0;
                          height: 20px;
                          padding: 2px 4px;
                          font-size: 11px;
                        }
                        .settlement-table {
                          width: 100%;
                          border-collapse: collapse;
                          margin: 5px 0;
                        }
                        .settlement-table td {
                          border: 1px solid #000;
                          height: 18px;
                          padding: 2px 4px;
                          font-size: 11px;
                          vertical-align: middle;
                        }
                        table {
                          width: 100%;
                          border-collapse: collapse;
                          margin-top: 10px;
                        }
                        td {
                          vertical-align: top;
                        }
                        .small-text {
                          font-size: 10px;
                        }
                        .signature-line {
                          border-bottom: 1px solid #000;
                          width: 250px;
                          display: inline-block;
                        }
                        .form-line {
                          border-bottom: 1px solid #000;
                          display: inline-block;
                          width: 100%;
                          height: 16px;
                        }
                        .flex-space {
                          display: flex;
                          justify-content: space-between;
                        }
                        .filled-line {
                          border-bottom: 1px solid #000;
                          display: inline-block;
                          width: 200px;
                          padding: 0 5px;
                        }
                        .filled-line-long {
                          border-bottom: 1px solid #000;
                          display: inline-block;
                          width: 600px;
                          padding: 0 5px;
                        }
                        .filled-line-lo {
                          border-bottom: 1px solid #000;
                          display: inline-block;
                          width: 780px;
                          padding: 0 5px;
                        }  
                        .filled-line-medium {
                          border-bottom: 1px solid #000;
                          display: inline-block;
                          width: 698px;
                          padding: 0 5px;
                        }
                        .filled-line-med {
                          border-bottom: 1px solid #000;
                          display: inline-block;
                          width: 150px;
                          padding: 0 5px;
                        }  
                        .date-line {
                          border-bottom: 1px solid #000;
                          display: inline-block;
                          width: 80px;
                          padding: 0 5px;
                        }
                        .customer-name-line {
                          border-bottom: 1px solid #000;
                          display: inline-block;
                          width: 695px;
                          padding: 0 5px;
                        }
                        .address-line {
                          border-bottom: 1px solid #000;
                          display: inline-block;
                          width: 703px;
                          padding: 0 5px;
                        }
                        .tin-line {
                          border-bottom: 1px solid #000;
                          display: inline-block;
                          width: 550px;
                          padding: 0 5px;
                        }
                        .business-style-line {
                          border-bottom: 1px solid #000;
                          display: inline-block;
                          width: 785px;
                          padding: 0 5px;
                        }
                      </style>
                    </head>
                    <body>
                      <div class="container">
                        <!-- Left Section -->
                        <div class="left">
                          <div class="left-bordered">
                            <div class="section-title">IN SETTLEMENT OF THE FF:</div>
                            <table class="settlement-table">
                              <tr>
                                <td>Tangible Property Tax:</td>
                                <td style="border-left: 1px solid #000;">0.00</td>
                              </tr>
                              <tr>
                                <td>Service Type:</td>
                                <td style="border-left: 1px solid #000;"><strong>${button.getAttribute('data-servicetype')}</strong></td>
                              </tr>
                              <tr>
                                <td>Customer Address:</td>
                                <td style="border-left: 1px solid #000;"><strong>${button.getAttribute('data-address')}</strong></td>
                              </tr>
                              <tr>
                                <td>Appliances Type:</td>
                                <td style="border-left: 1px solid #000;"><strong>Not specified</strong></td>
                              </tr>
                              <tr>
                                <td>Service by:</td>
                                <td style="border-left: 1px solid #000;"><strong>${technicianInfo}</strong></td>
                              </tr>
                              <tr>
                                <td>Date & Time:</td>
                                <td style="border-left: 1px solid #000;"><strong>${button.getAttribute('data-date')}</strong></td>
                              </tr>
                              <tr>
                                <td></td>
                                <td style="border-left: 1px solid #000;"></td>
                              </tr>
                              <tr>
                                <td></td>
                                <td style="border-left: 1px solid #000;"></td>
                              </tr>
                              <tr>
                                <td></td>
                                <td style="border-left: 1px solid #000;"></td>
                              </tr>
                              <tr>
                                <td><strong>TOTAL SALES</strong></td>
                                <td style="border-left: 1px solid #000;"><strong>${button.getAttribute('data-amount')}</strong></td>
                              </tr>
                              <tr>
                                <td><strong>Less: Withholding Tax</strong></td>
                                <td style="border-left: 1px solid #000;"></td>
                              </tr>
                              <tr>
                                <td><strong>PAYMENT DUE</strong></td>
                                <td style="border-left: 1px solid #000;"></td>
                              </tr>
                            </table>
                            
                            <table class="settlement-table" style="margin-top: 8px;">
                              <tr><td><strong>MODE OF PAYMENT:</strong></td></tr>
                              <tr><td>[✓] Cash</td></tr>
                              <tr><td>[ ] E-Wallet/Bank Transfer</td></tr>
                              <tr><td>[ ] Check #</td></tr>
                              <tr><td>Bank/Dated:</td></tr>
                            </table>
                          </div>
                          
                          <p style="font-size: 10px; margin: 8px 0;">12 Bkts. (2s) SN 000001 - 000600<br>
                          BIR Auth. to Print OCN: 11ZAU20210000000826</p>
                          <p style="font-size: 10px; margin: 4px 0;"><strong>Date of ATP: 08.25.2021</strong><br>
                          <strong>Expiry Date: 08.24.2026</strong></p>
                          <p style="font-size: 10px; margin: 4px 0;">Ptd. by: ARS PRINTING PRESS; TIN: 192-169-197-000<br>
                          P-Lemosnito, Mankilam, Tagum City - #655-7150</p>
                        </div>
                    
                        <!-- Right Section -->
                        <div class="right">
                          <div class="title">HVAC AIRCON & REFRIGERATION SERVICES</div>
                          <p style="text-align:center">Purok 16-A, Ising, Carmen, Davao Del Norte<br>
                          <strong>AILE A. DERLA - PROP.</strong><br>
                          Non-Vat Reg. TIN: 414-662-661-00000</p>
                          <div class="flex-space">
                            <div class="section-title">OFFICIAL RECEIPT</div>
                            <span>Date <span class="date-line"><strong>${currentDate}</strong></span> 20<span class="date-line"><strong>${new Date().getFullYear().toString().slice(-2)}</strong></span></span>
                          </div>
                          <p style="margin-bottom: 8px;">RECEIVED from <span class="customer-name-line"><strong>${button.getAttribute('data-customer')}</strong></span></p>
                          <p style="margin-bottom: 8px;">with address at <span class="address-line"><strong>${button.getAttribute('data-address')}</strong></span></p>
                          <p style="margin-bottom: 8px;">TIN <span class="tin-line"><strong>192-169-197-000</strong></span> and engaged in the business style of</p>
                          <p style="margin-bottom: 8px;"><span class="business-style-line"></span> the amount of <span class="filled-line-medium"><strong>${convertAmountToWords(button.getAttribute('data-amount'))}</strong></span></p>
                          <p style="margin-bottom: 8px;"><span class="filled-line-long"></span> (<span class="filled-line-med"><strong>${button.getAttribute('data-amount')}</strong></span>)</p>
                          <p style="margin-bottom: 8px;">as (✓) full / ( ) partial payment for <span class="filled-line-long"><strong>${button.getAttribute('data-servicetype')} - Not specified</strong></span></p>
                          <p style="margin-bottom: 15px;"><span class="filled-line-lo"><strong>${button.getAttribute('data-desc')}</strong></span></p>
                          <div class="flex-space">
                            <span style ="margin-top: 15px; margin-left: 400px;">Received Payment by:</span>
                            <span class="signature-line"></span>
                          </div>
                          <p style="text-align:right">Cashier/Authorized Representative</p>
                    
                          <p><strong>No.</strong> <u><strong>${button.getAttribute('data-invoice')}</strong></u></p>
                          <p class="small-text">* THIS DOCUMENT IS NOT VALID FOR CLAIMING INPUT TAXES *<br>
                          <strong>"THIS OFFICIAL RECEIPT SHALL BE VALID FOR FIVE (5) YEARS FROM THE DATE OF THE ATP"</strong></p>
                    
                          <p class="small-text">Printer's Association No.<br>
                          11ZAP20180000000001;<br>
                          Date Issued: 11.23.2018</p>
                        </div>
                      </div>
                    </body>
                    </html>
                `;
            } else {
                // Professional Invoice Format for Unpaid Invoices (Pending/Overdue) - matching admin design
                var invoiceStatus = button.getAttribute('data-status');
                var statusColor = invoiceStatus === 'Overdue' ? '#dc3545' : '#ffc107';
                var statusText = invoiceStatus === 'Overdue' ? 'OVERDUE' : 'PENDING PAYMENT';
                
                html = `
                    <html>
                    <head>
                        <title>Invoice</title>
                        <style>
                            @media print {
                                body { margin: 0; padding: 0; }
                                .no-print { display: none; }
                            }
                            body {
                                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                font-size: 14px;
                                line-height: 1.5;
                                margin: 0;
                                padding: 30px;
                                background: #f5f5f5;
                                color: #333;
                            }
                            .invoice-container {
                                max-width: 800px;
                                margin: 0 auto;
                                background: white;
                                box-shadow: 0 0 20px rgba(0,0,0,0.1);
                                border-radius: 8px;
                                overflow: hidden;
                            }
                            .invoice-header {
                                padding: 40px 40px 30px 40px;
                                background: white;
                            }
                            .invoice-title {
                                font-size: 48px;
                                font-weight: 700;
                                color: #007bff;
                                margin: 0 0 30px 0;
                                letter-spacing: 2px;
                            }
                            .header-info {
                                display: grid;
                                grid-template-columns: repeat(4, 1fr);
                                gap: 30px;
                                margin-bottom: 40px;
                            }
                            .info-item {
                                text-align: left;
                            }
                            .info-label {
                                font-size: 12px;
                                font-weight: 600;
                                color: #666;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                                margin-bottom: 5px;
                            }
                            .info-value {
                                font-size: 16px;
                                font-weight: 600;
                                color: #333;
                                margin: 0;
                            }
                            .billing-section {
                                display: grid;
                                grid-template-columns: 1fr 1fr;
                                gap: 60px;
                                padding: 0 40px;
                                margin-bottom: 40px;
                            }
                            .billing-block {
                                
                            }
                            .billing-title {
                                font-size: 14px;
                                font-weight: 600;
                                color: #333;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                                margin-bottom: 15px;
                            }
                            .company-info {
                                font-size: 16px;
                                font-weight: 600;
                                color: #333;
                                margin-bottom: 8px;
                            }
                            .address-info {
                                font-size: 14px;
                                color: #666;
                                line-height: 1.4;
                            }
                            .customer-name {
                                font-size: 16px;
                                font-weight: 600;
                                color: #333;
                                margin-bottom: 8px;
                            }
                            .customer-address {
                                font-size: 14px;
                                color: #666;
                                line-height: 1.4;
                            }
                            .items-section {
                                padding: 0 40px;
                                margin-bottom: 30px;
                            }
                            .items-table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-bottom: 30px;
                            }
                            .items-table th {
                                background: #f8f9fa;
                                padding: 15px 20px;
                                text-align: left;
                                font-size: 12px;
                                font-weight: 600;
                                color: #666;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                                border-bottom: 2px solid #e9ecef;
                            }
                            .items-table th:last-child {
                                text-align: right;
                            }
                            .items-table td {
                                padding: 15px 20px;
                                border-bottom: 1px solid #f1f3f4;
                                font-size: 14px;
                                color: #333;
                            }
                            .items-table td:last-child {
                                text-align: right;
                                font-weight: 500;
                            }
                            .service-description {
                                font-size: 13px;
                                color: #666;
                                margin-top: 5px;
                            }
                            .totals-section {
                                padding: 0 40px;
                                margin-bottom: 30px;
                            }
                            .totals-table {
                                width: 100%;
                                margin-left: auto;
                                max-width: 300px;
                                float: right;
                            }
                            .totals-table td {
                                padding: 8px 20px;
                                font-size: 14px;
                                border-bottom: 1px solid #f1f3f4;
                            }
                            .totals-table td:first-child {
                                text-align: right;
                                font-weight: 500;
                                color: #666;
                                text-transform: uppercase;
                                font-size: 12px;
                                letter-spacing: 0.5px;
                            }
                            .totals-table td:last-child {
                                text-align: right;
                                font-weight: 600;
                                color: #333;
                            }
                            .total-row {
                                border-top: 2px solid #333 !important;
                                border-bottom: 4px double #333 !important;
                            }
                            .total-row td {
                                font-size: 16px;
                                font-weight: 700;
                                color: #333;
                                padding-top: 15px;
                                padding-bottom: 15px;
                            }
                            .status-section {
                                padding: 20px 40px;
                                background: ${invoiceStatus === 'Overdue' ? '#ffebee' : '#fff3e0'};
                                border-top: 1px solid #e9ecef;
                                text-align: center;
                            }
                            .status-message {
                                font-size: 16px;
                                font-weight: 600;
                                color: ${statusColor};
                                margin: 0;
                            }
                            .footer-section {
                                padding: 40px;
                                background: #007bff;
                                color: white;
                                text-align: center;
                            }
                            .thank-you {
                                font-size: 32px;
                                font-weight: 700;
                                margin: 0 0 15px 0;
                                letter-spacing: 1px;
                            }
                            .footer-text {
                                font-size: 14px;
                                opacity: 0.9;
                                line-height: 1.6;
                                margin: 0;
                            }
                            .clearfix::after {
                                content: "";
                                display: table;
                                clear: both;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="invoice-container">
                            <!-- Invoice Header -->
                            <div class="invoice-header">
                                <h1 class="invoice-title">INVOICE</h1>
                                
                                <div class="header-info">
                                    <div class="info-item">
                                        <div class="info-label">N. INVOICE</div>
                                        <div class="info-value">${button.getAttribute('data-invoice')}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">DATE</div>
                                        <div class="info-value">${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }).toUpperCase()}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">STATUS</div>
                                        <div class="info-value" style="color: ${statusColor};">${invoiceStatus.toUpperCase()}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">AMOUNT DUE</div>
                                        <div class="info-value">${button.getAttribute('data-amount')}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Billing Information -->
                            <div class="billing-section">
                                <div class="billing-block">
                                    <div class="billing-title">BILL TO:</div>
                                    <div class="customer-name">${button.getAttribute('data-customer')}</div>
                                    <div class="customer-address">${button.getAttribute('data-address') || 'Address not specified'}</div>
                                </div>
                                <div class="billing-block">
                                    <div class="billing-title">BILL FROM:</div>
                                    <div class="company-info">HVAC AIRCON & REFRIGERATION SERVICES</div>
                                    <div class="address-info">
                                        Purok 16-A, Ising, Carmen, Davao Del Norte<br>
                                        AILE A. DERLA - PROP.<br>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Items Section -->
                            <div class="items-section">
                                <table class="items-table">
                                    <thead>
                                        <tr>
                                            <th>Service Details</th>
                                            <th>Date & Time</th>
                                            <th>Technician(s)</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>${button.getAttribute('data-servicetype')}</strong><br>
                                                <span style="color: #666; font-size: 13px;">Equipment: Not specified</span>
                                                <div class="service-description">${button.getAttribute('data-desc')}</div>
                                            </td>
                                            <td>${button.getAttribute('data-date')}</td>
                                            <td>
                                                ${button.getAttribute('data-technician') || 'Not assigned'}
                                                ${button.getAttribute('data-technician2') && button.getAttribute('data-technician2').trim() !== '' ? '<br>' + button.getAttribute('data-technician2') : ''}
                                            </td>
                                            <td>${button.getAttribute('data-amount')}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Totals Section -->
                            <div class="totals-section clearfix">
                                <table class="totals-table">
                                    <tr class="total-row">
                                        <td>TOTAL</td>
                                        <td>${button.getAttribute('data-amount')}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Status Section -->
                            <div class="status-section">
                                <p class="status-message">
                                    ${invoiceStatus === 'Overdue' ? 'PAYMENT OVERDUE - Please remit payment immediately to avoid service interruption' : 'PAYMENT PENDING - Payment due within 30 days of service completion'}
                                </p>
                            </div>
                            
                            <!-- Footer -->
                            <div class="footer-section">
                                <h2 class="thank-you">THANK YOU!</h2>
                                <p class="footer-text">
                                    We appreciate your business and trust in our HVAC services.<br>
                                    For any questions regarding this invoice, please contact us at your convenience.
                                </p>
                            </div>
                        </div>
                    </body>
                    </html>
                `;
            }
            var printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write(`<html><head><title>${windowTitle}</title></head><body>` + html + '</body></html>');
            printWindow.document.close();
            setTimeout(function () { printWindow.print(); }, 300);
        }
    });
});

// loadInvoices function removed - now using server-side PHP rendering

function getStatusBadge(status) {
    switch(status) {
        case 'Paid': return '<span>Paid</span>';
        case 'Pending': return '<span>Pending</span>';
        case 'Overdue': return '<span>Overdue</span>';
        default: return '<span>Unknown</span>';
    }
}

// Debounced search functionality
let searchTimeout;
const searchInput = document.getElementById('searchInput');

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const searchValue = searchInput.value.trim();
        const currentStatus = '<?= urlencode($statusFilter) ?>';
        window.location.href = `?page=invoice&status=${currentStatus}&search=${encodeURIComponent(searchValue)}&p=1`;
    }, 500); // 500ms delay
}

// Add event listener for debounced search
if (searchInput) {
    searchInput.addEventListener('input', debounceSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            debounceSearch();
        }
    });
}
</script>

<style>
.badge {
    font-size: 0.75em;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.invoice-details {
    padding: 1rem;
}

/* Hide background scrollbar when modal is open */
body.modal-open {
    overflow: hidden !important;
}
</style>