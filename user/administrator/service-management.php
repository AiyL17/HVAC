<?php
include 'config/ini.php';
$pdo = pdo_init();

// Appliance types functionality temporarily disabled
$applianceTypes = [];

// Handle add, update, delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add_service'])) {
            $service_type = trim($_POST['service_type']);
            $service_type_price_min = (int)$_POST['service_type_price_min'];
            $service_type_price_max = (int)$_POST['service_type_price_max'];
            
            // Validate input
            if (empty($service_type)) {
                throw new Exception('Service type name is required');
            }
            if ($service_type_price_min < 0 || $service_type_price_max < 0) {
                throw new Exception('Prices must be positive numbers');
            }
            if ($service_type_price_min > $service_type_price_max) {
                throw new Exception('Minimum price cannot be greater than maximum price');
            }
            
            // Insert service type
            $stmt = $pdo->prepare("INSERT INTO service_type (service_type_name, service_type_price_min, service_type_price_max) VALUES (?, ?, ?)");
            $stmt->execute([$service_type, $service_type_price_min, $service_type_price_max]);
            $service_type_id = $pdo->lastInsertId();
            
            // Handle appliance type associations
            if (!empty($_POST['appliances_type_ids'])) {
                $appliance_ids = explode(',', $_POST['appliances_type_ids']);
                $appliance_ids = array_filter(array_map('intval', $appliance_ids));
                
                foreach ($appliance_ids as $appliance_id) {
                    $stmt = $pdo->prepare("INSERT IGNORE INTO service_type_appliances (service_type_id, appliances_type_id) VALUES (?, ?)");
                    $stmt->execute([$service_type_id, $appliance_id]);
                }
            }
            
        } elseif (isset($_POST['edit_service'])) {
            $id = (int)$_POST['service_id'];
            $service_type = trim($_POST['service_type']);
            $service_type_price_min = (int)$_POST['service_type_price_min'];
            $service_type_price_max = (int)$_POST['service_type_price_max'];
            
            // Debug: Log what we received
            error_log("Edit service - ID: $id, Appliances: " . ($_POST['appliances_type_ids'] ?? 'EMPTY'));
            
            // Validate input
            if (empty($service_type)) {
                throw new Exception('Service type name is required');
            }
            if ($service_type_price_min < 0 || $service_type_price_max < 0) {
                throw new Exception('Prices must be positive numbers');
            }
            if ($service_type_price_min > $service_type_price_max) {
                throw new Exception('Minimum price cannot be greater than maximum price');
            }
            
            // Update service type
            $stmt = $pdo->prepare("UPDATE service_type SET service_type_name=?, service_type_price_min=?, service_type_price_max=? WHERE service_type_id=?");
            $stmt->execute([$service_type, $service_type_price_min, $service_type_price_max, $id]);
            
            // Update appliance type associations
            // First, remove existing associations
            $stmt = $pdo->prepare("DELETE FROM service_type_appliances WHERE service_type_id=?");
            $stmt->execute([$id]);
            
            // Then add new associations
            if (!empty($_POST['appliances_type_ids'])) {
                $appliance_ids = explode(',', $_POST['appliances_type_ids']);
                $appliance_ids = array_filter(array_map('intval', $appliance_ids));
                
                foreach ($appliance_ids as $appliance_id) {
                    $stmt = $pdo->prepare("INSERT IGNORE INTO service_type_appliances (service_type_id, appliances_type_id) VALUES (?, ?)");
                    $stmt->execute([$id, $appliance_id]);
                }
            }
            
        } elseif (isset($_POST['delete_service'])) {
            $id = (int)$_POST['service_id'];
            
            // Delete service type (CASCADE will handle service_type_appliances)
            $stmt = $pdo->prepare("DELETE FROM service_type WHERE service_type_id=?");
            $stmt->execute([$id]);
        }
        
        header('Location: index.php?page=service-management');
        exit();
        
    } catch (Exception $e) {
        // Store error message in session for display
        session_start();
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: index.php?page=service-management');
        exit();
    }
}

// Pagination and filtering setup
$items_per_page = 4;
$current_page = isset($_GET['page_num']) ? max(1, intval($_GET['page_num'])) : 1;
$offset = ($current_page - 1) * $items_per_page;
$filter_service_type = isset($_GET['filter']) ? trim($_GET['filter']) : '';

// Get total count for pagination with filtering
try {
    $count_query = "
        SELECT COUNT(DISTINCT st.service_type_id) as total
        FROM service_type st
        LEFT JOIN service_type_appliances sta ON st.service_type_id = sta.service_type_id
        LEFT JOIN appliances_type at ON sta.appliances_type_id = at.appliances_type_id
    ";
    
    if (!empty($filter_service_type)) {
        $count_query .= " WHERE st.service_type_name LIKE :filter";
    }
    
    $count_stmt = $pdo->prepare($count_query);
    
    if (!empty($filter_service_type)) {
        $count_stmt->bindValue(':filter', '%' . $filter_service_type . '%', PDO::PARAM_STR);
    }
    
    $count_stmt->execute();
    $total_items = $count_stmt->fetch(PDO::FETCH_OBJ)->total;
} catch (PDOException $e) {
    $count_query = "SELECT COUNT(*) as total FROM service_type";
    
    if (!empty($filter_service_type)) {
        $count_query .= " WHERE service_type_name LIKE :filter";
    }
    
    $count_stmt = $pdo->prepare($count_query);
    
    if (!empty($filter_service_type)) {
        $count_stmt->bindValue(':filter', '%' . $filter_service_type . '%', PDO::PARAM_STR);
    }
    
    $count_stmt->execute();
    $total_items = $count_stmt->fetch(PDO::FETCH_OBJ)->total;
}

$total_pages = ceil($total_items / $items_per_page);

// Fetch services from database with pagination and filtering
try {
    $main_query = "
        SELECT 
            st.service_type_id,
            st.service_type_name,
            st.service_type_price_min,
            st.service_type_price_max,
            GROUP_CONCAT(at.appliances_type_name SEPARATOR ', ') as appliances_types,
            GROUP_CONCAT(at.appliances_type_id SEPARATOR ', ') as appliances_type_ids
        FROM service_type st
        LEFT JOIN service_type_appliances sta ON st.service_type_id = sta.service_type_id
        LEFT JOIN appliances_type at ON sta.appliances_type_id = at.appliances_type_id
    ";
    
    if (!empty($filter_service_type)) {
        $main_query .= " WHERE st.service_type_name LIKE :filter";
    }
    
    $main_query .= "
        GROUP BY st.service_type_id, st.service_type_name, st.service_type_price_min, st.service_type_price_max
        ORDER BY st.service_type_id DESC
        LIMIT :limit OFFSET :offset
    ";
    
    $stmt = $pdo->prepare($main_query);
    
    if (!empty($filter_service_type)) {
        $stmt->bindValue(':filter', '%' . $filter_service_type . '%', PDO::PARAM_STR);
    }
    
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    // If the junction table doesn't exist yet, fall back to basic service_type query
    $fallback_query = "SELECT * FROM service_type";
    
    if (!empty($filter_service_type)) {
        $fallback_query .= " WHERE service_type_name LIKE :filter";
    }
    
    $fallback_query .= " ORDER BY service_type_id DESC LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($fallback_query);
    
    if (!empty($filter_service_type)) {
        $stmt->bindValue(':filter', '%' . $filter_service_type . '%', PDO::PARAM_STR);
    }
    
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_OBJ);
    // Add empty appliances_types property for compatibility
    foreach ($services as $service) {
        $service->appliances_types = '';
        $service->appliances_type_ids = '';
    }
}

// Fetch all services for filter dropdown (without pagination)
try {
    $filter_stmt = $pdo->prepare("
        SELECT DISTINCT st.service_type_name
        FROM service_type st
        ORDER BY st.service_type_name
    ");
    $filter_stmt->execute();
    $all_services = $filter_stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $all_services = [];
}

// Fetch appliance types for forms
try {
    $stmt = $pdo->prepare("SELECT * FROM appliances_type ORDER BY appliances_type_name");
    $stmt->execute();
    $applianceTypes = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $applianceTypes = [];
}

// Check for error messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;
if ($error_message) {
    unset($_SESSION['error_message']);
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
    
    /* Hide body scrollbar when modal is open to prevent double scrollbars */
    body.modal-open {
        overflow: hidden !important;
    }
    
    /* Card Header Styling with Gradient Background for Modals */
    .modal .card-header {
        background: linear-gradient(135deg, #007bff, #0056b3) !important;
        color: white !important;
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none !important;
        padding: 16px 24px !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
    }
    
    .modal .card-header .card-title {
        color: white !important;
        font-weight: 600 !important;
        font-size: 1.1rem !important;
        margin-bottom: 0 !important;
    }
    
    .modal .card-header .btn-close {
        filter: brightness(0) invert(1) !important;
        opacity: 0.8 !important;
    }
    
    .modal .card-header .btn-close:hover {
        opacity: 1 !important;
    }
    
    /* Ensure modal content and card have proper border radius */
    .modal-content {
        border-radius: 12px !important;
        border: none !important;
        overflow: hidden !important;
    }
    
    .modal .card {
        border-radius: 12px !important;
        border: none !important;
        overflow: hidden !important;
    }
    
    .modal .card-body {
        padding: 24px !important;
    }
    
    .modal .card-footer {
        background-color: #f8f9fa !important;
        border-top: 1px solid #dee2e6 !important;
        padding: 16px 24px !important;
    }
    
    /* Consistent Input Field Styling for Service Modals */
    .modal .card-body .form-control {
        height: 42px !important;
        padding: 8px 12px !important;
        font-size: 14px !important;
        border-radius: 6px !important;
        border: 1px solid #ced4da !important;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
    }
    
    .modal .card-body .form-control:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        outline: 0 !important;
    }
    
    .modal .card-body .form-label {
        font-weight: 500 !important;
        margin-bottom: 6px !important;
        color: #495057 !important;
        font-size: 14px !important;
    }
    
    /* Ensure consistent column spacing */
    .modal .card-body .row.g-3 .col-md-6 {
        padding-right: 12px !important;
        padding-left: 12px !important;
    }
    
    /* Table Styling */
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
        font-size: 16px;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.03);
    }

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
    .container {
        margin-top: -20px;
    }
</style>
<div class="container mt-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Services Lists</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
            <i class="bi bi-plus-lg me-2"></i>Add Service
        </button>
    </div>

    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($error_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Filters and Search -->
    <div class="dashboard-card mb-4">
        <div class="dashboard-card-header">
            <i class="bi bi-funnel me-2"></i>Filter Services
        </div>
        <div class="dashboard-card-body py-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Service Type</label>
                    <select class="form-select" id="serviceTypeFilter">
                        <option value="">All Service Types</option>
                        <?php foreach ($all_services as $service): ?>
                            <option value="<?= htmlspecialchars($service->service_type_name) ?>"><?= htmlspecialchars($service->service_type_name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-card d-none d-lg-block">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>SERVICE TYPE</th>
                        <th>AVAILABLE APPLIANCES</th>
                        <th style="text-align: center;">PRICE RANGE</th>
                        <th class="text-end" style="text-align: center !important;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($services)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-gear text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted mt-3">No Services Found</h5>
                                    <p class="text-muted mb-0">There are no service types matching your current filters.</p>
                                    <?php if (!empty($filter_service_type)): ?>
                                        <div class="mt-3">
                                            <a href="?page=service-management" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-arrow-clockwise me-1"></i>Clear Filters
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($services as $service): ?>
                        <tr>
                            <td class="fw-medium">
                                <?= htmlspecialchars($service->service_type_name) ?>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php 
                                    $appliances = explode(',', $service->appliances_types ?? '');
                                    $hasAppliances = false;
                                    foreach ($appliances as $appliance): 
                                        if (trim($appliance) !== ''): 
                                            $hasAppliances = true;
                                    ?>
                                            <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-2 py-1" style="font-size:0.75em;">
                                                <?= htmlspecialchars(trim($appliance)) ?>
                                            </span>
                                    <?php 
                                        endif; 
                                    endforeach; 
                                    if (!$hasAppliances):
                                    ?>
                                        <span class="text-muted small">No appliances specified</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="fw-medium text-dark">
                                    ₱<?= number_format($service->service_type_price_min) ?> - ₱<?= number_format($service->service_type_price_max) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" style="padding: 0.5rem 0.78rem; border-radius: 1.04rem" class="btn btn-sm btn-primary text-white" data-bs-toggle="modal" data-bs-target="#editServiceModal<?= $service->service_type_id ?>" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Delete this service?');">
                                        <input type="hidden" name="service_id" value="<?= $service->service_type_id ?>">
                                        <button type="submit" style="padding: 0.5rem 0.78rem; border-radius: 1.04rem" name="delete_service" class="btn btn-sm btn-danger text-white" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                </table>
            </div>
        </div>
        
        <!-- Mobile Card View -->
        <div class="d-lg-none">
            <?php if (empty($services)): ?>
                <div class="empty-state-mobile text-center py-5">
                    <i class="bi bi-gear text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Services Found</h5>
                    <p class="text-muted mb-0 px-3">There are no service types matching your current filters.</p>
                    <?php if (!empty($filter_service_type)): ?>
                        <div class="mt-4">
                            <a href="?page=service-management" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                <div class="card mb-3 mx-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="card-title mb-1 fw-bold text-primary">
                                    <i class="bi bi-gear me-2"></i>
                                    <?= htmlspecialchars($service->service_type_name) ?>
                                </h6>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block small">Price Range</small>
                                <small class="fw-bold text-success">₱<?= number_format($service->service_type_price_min) ?> - ₱<?= number_format($service->service_type_price_max) ?></small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1 small"><i class="bi bi-tools me-2"></i>Available Appliances</small>
                            <div class="d-flex flex-wrap gap-1">
                                <?php 
                                $appliances = explode(',', $service->appliances_types ?? '');
                                $hasAppliances = false;
                                foreach ($appliances as $appliance): 
                                    if (trim($appliance) !== ''): 
                                        $hasAppliances = true;
                                ?>
                                        <span class="badge bg-primary-subtle text-primary px-2 py-1">
                                            <?= htmlspecialchars(trim($appliance)) ?>
                                        </span>
                                <?php 
                                    endif; 
                                endforeach; 
                                if (!$hasAppliances):
                                ?>
                                    <span class="text-muted small">No appliances specified</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" style="color: white; background-color:#0d6efd" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editServiceModal<?= $service->service_type_id ?>">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </button>
                            <form method="post" onsubmit="return confirm('Delete this service?');" class="d-inline">
                                <input type="hidden" name="service_id" value="<?= $service->service_type_id ?>">
                                <button type="submit" style="color: white; background-color:#dc3545;" name="delete_service" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Shared Edit Modals for Both Desktop and Mobile -->
    <?php foreach ($services as $service): ?>
    <div class="modal fade" id="editServiceModal<?= $service->service_type_id ?>" tabindex="-1" aria-labelledby="editServiceModalLabel<?= $service->service_type_id ?>" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Service</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
              <input type="hidden" name="service_id" value="<?= $service->service_type_id ?>">
              <div class="card-body">
                  <div class="row g-3 align-items-center">
                      <div class="col-md-6">
                          <label class="form-label">Service Type</label>
                          <input type="text" name="service_type" class="form-control" value="<?= htmlspecialchars($service->service_type_name) ?>" required>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label">Min Price</label>
                          <input type="number" name="service_type_price_min" class="form-control" min="0" step="1" value="<?= htmlspecialchars($service->service_type_price_min) ?>" required>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label">Max Price</label>
                          <input type="number" name="service_type_price_max" class="form-control" min="0" step="1" value="<?= htmlspecialchars($service->service_type_price_max) ?>" required>
                      </div>
                  </div>
                  <div class="mt-4">
                      <label class="form-label">Available Appliances</label>
                      <div class="appliance-tags-container mb-2" id="editTagsContainer<?= $service->service_type_id ?>"></div>
                      <div class="input-group mb-2">
                        <input type="text" class="form-control edit-appliance-input" id="editApplianceInput<?= $service->service_type_id ?>" placeholder="Enter new appliance type">
                        <button type="button" class="btn btn-outline-primary edit-add-appliance-btn" data-service-id="<?= $service->service_type_id ?>">+ Add</button>
                      </div>
                      <input type="hidden" name="appliances_type_ids" id="editApplianceHidden<?= $service->service_type_id ?>">
                  </div>
              </div>
              <div class="card-footer d-flex justify-content-end gap-2">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" name="edit_service" class="btn btn-primary px-4">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    
    <!-- Pagination Controls -->
    <div class="pagination-container">
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="text-center">
            <ul class="pagination p-2 justify-content-center pagination-sm">
                <li class="page-item <?= $current_page === 1 ? 'disabled' : '' ?>">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="<?= $current_page > 1 ? '?page=service-management' . (!empty($filter_service_type) ? '&filter=' . urlencode($filter_service_type) : '') . '&page_num=' . ($current_page - 1) : '#' ?>" aria-label="Previous">
                        &lt;
                    </a>
                </li>
                    
                <?php
                // Flexible pagination logic that handles any number of pages
                $maxVisiblePages = 7; // Maximum number of page links to show
                
                if ($total_pages <= $maxVisiblePages) {
                    // Show all pages if total pages is small
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $isActive = ($i == $current_page);
                        echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                        echo '<a class="page-link ' . ($isActive ? 'text-light' : 'text-dark') . ' rounded-pill border-0 p-2 px-3" href="?page=service-management' . (!empty($filter_service_type) ? '&filter=' . urlencode($filter_service_type) : '') . '&page_num=' . $i . '">' . $i . '</a>';
                        echo '</li>';
                    }
                } else {
                    // Complex pagination for many pages
                    $startPage = 1;
                    $endPage = $total_pages;
                    
                    // Always show first page
                    $isActive = ($current_page == 1);
                    echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                    echo '<a class="page-link ' . ($isActive ? 'text-light' : 'text-dark') . ' rounded-pill border-0 p-2 px-3" href="?page=service-management' . (!empty($filter_service_type) ? '&filter=' . urlencode($filter_service_type) : '') . '&page_num=1">1</a>';
                    echo '</li>';
                    
                    // Calculate range around current page
                    $rangeStart = max(2, $current_page - 2);
                    $rangeEnd = min($total_pages - 1, $current_page + 2);
                    
                    // Add ellipsis after first page if needed
                    if ($rangeStart > 2) {
                        echo '<li class="page-item disabled">';
                        echo '<span class="page-link text-dark rounded-pill border-0 p-2 px-3">...</span>';
                        echo '</li>';
                    }
                    
                    // Show pages around current page
                    for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
                        $isActive = ($i == $current_page);
                        echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                        echo '<a class="page-link ' . ($isActive ? 'text-light' : 'text-dark') . ' rounded-pill border-0 p-2 px-3" href="?page=service-management' . (!empty($filter_service_type) ? '&filter=' . urlencode($filter_service_type) : '') . '&page_num=' . $i . '">' . $i . '</a>';
                        echo '</li>';
                    }
                    
                    // Add ellipsis before last page if needed
                    if ($rangeEnd < $total_pages - 1) {
                        echo '<li class="page-item disabled">';
                        echo '<span class="page-link text-dark rounded-pill border-0 p-2 px-3">...</span>';
                        echo '</li>';
                    }
                    
                    // Always show last page (if it's not page 1)
                    if ($total_pages > 1) {
                        $isActive = ($current_page == $total_pages);
                        echo '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
                        echo '<a class="page-link ' . ($isActive ? 'text-light' : 'text-dark') . ' rounded-pill border-0 p-2 px-3" href="?page=service-management' . (!empty($filter_service_type) ? '&filter=' . urlencode($filter_service_type) : '') . '&page_num=' . $total_pages . '">' . $total_pages . '</a>';
                        echo '</li>';
                    }
                }
                ?>
                    
                <li class="page-item <?= $current_page === $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="<?= $current_page < $total_pages ? '?page=service-management' . (!empty($filter_service_type) ? '&filter=' . urlencode($filter_service_type) : '') . '&page_num=' . ($current_page + 1) : '#' ?>" aria-label="Next">
                        &gt;
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>
<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post">
          <div class="card-body">
            <div class="row g-3 align-items-center">
              <div class="col-md-6">
                <label class="form-label">Service Type</label>
                <input type="text" name="service_type" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Minimum Price (₱)</label>
                <input type="number" name="service_type_price_min" class="form-control" min="0" step="1" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Maximum Price (₱)</label>
                <input type="number" name="service_type_price_max" class="form-control" min="0" step="1" required>
              </div>
            </div>
            <div class="mt-4">
              <label class="form-label">Available Appliances</label>
              <div class="appliance-tags-container mb-2" id="addTagsContainer"></div>
              <div class="input-group mb-2">
                <input type="text" class="form-control" id="addApplianceInput" placeholder="Enter new appliance type">
                <button type="button" class="btn btn-outline-primary" id="addAddApplianceBtn">+ Add</button>
              </div>
              <input type="hidden" name="appliances_type_ids" id="addApplianceHidden">
            </div>
          </div>
          <div class="card-footer d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="add_service" class="btn btn-primary px-4">Save Service</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
// Tag-style appliances for Add New Service modal
function renderAddTags(appliances) {
  const container = document.getElementById('addTagsContainer');
  const hidden = document.getElementById('addApplianceHidden');
  container.innerHTML = '';
  let ids = [];
  appliances.forEach(appl => {
    const tag = document.createElement('span');
    tag.className = 'badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 me-1 mb-1 d-inline-flex align-items-center';
    tag.textContent = appl.name + ' ';
    const remove = document.createElement('button');
    remove.type = 'button';
    remove.className = 'btn-close btn-close-sm ms-1';
    remove.style.fontSize = '0.8em';
    remove.onclick = function() {
      appliances = appliances.filter(a => a.id !== appl.id);
      renderAddTags(appliances);
    };
    tag.appendChild(remove);
    container.appendChild(tag);
    ids.push(appl.id);
  });
  hidden.value = ids.join(',');
}

document.addEventListener('DOMContentLoaded', function() {
  let addAppliances = [];
  renderAddTags(addAppliances);
  document.getElementById('addAddApplianceBtn').onclick = function() {
    const input = document.getElementById('addApplianceInput');
    const val = input.value.trim();
    if (val) {
      // Prevent duplicate
      if (!addAppliances.some(a => a.name.toLowerCase() === val.toLowerCase())) {
        // AJAX to insert and fetch real ID
        fetch('api/administrator/add_appliance_type.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'appliance_type_name=' + encodeURIComponent(val)
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            addAppliances.push({id: data.id, name: data.name});
            renderAddTags(addAppliances);
          } else {
            alert('Error: ' + (data.error || 'Could not add appliance.'));
          }
        })
        .catch(() => alert('Network error.'));
      }
      input.value = '';
    }
  };
  // On submit, update hidden input
  document.querySelector('#addServiceModal form').onsubmit = function() {
    document.getElementById('addApplianceHidden').value = addAppliances.map(a => a.id).join(',');
  };
});

// Tag-style appliances for Edit Service modal
function renderEditTags(serviceId, appliances) {
  const container = document.getElementById('editTagsContainer' + serviceId);
  const hidden = document.getElementById('editApplianceHidden' + serviceId);
  container.innerHTML = '';
  let ids = [];
  appliances.forEach(appl => {
    const tag = document.createElement('span');
    tag.className = 'badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 me-1 mb-1 d-inline-flex align-items-center';
    tag.textContent = appl.name + ' ';
    const remove = document.createElement('button');
    remove.type = 'button';
    remove.className = 'btn-close btn-close-sm ms-1';
    remove.style.fontSize = '0.8em';
    remove.onclick = function() {
      // Update the global array for this service
      window['editAppliances' + serviceId] = window['editAppliances' + serviceId].filter(a => a.id !== appl.id);
      // Re-render with updated array
      renderEditTags(serviceId, window['editAppliances' + serviceId]);
      console.log('Removed appliance:', appl.name, 'Remaining:', window['editAppliances' + serviceId]);
    };
    tag.appendChild(remove);
    container.appendChild(tag);
    ids.push(appl.id);
  });
  hidden.value = ids.join(',');
  console.log('Updated hidden field for service', serviceId, 'with IDs:', ids.join(','));
}

document.addEventListener('DOMContentLoaded', function() {
  <?php foreach ($services as $service): ?>
    // Make the appliances array globally accessible
    window.editAppliances<?= $service->service_type_id ?> = [
      <?php
        if (!empty($service->appliances_types) && !empty($service->appliances_type_ids)) {
          $names = explode(',', $service->appliances_types);
          $ids = explode(',', $service->appliances_type_ids);
          for ($i = 0; $i < count($ids); $i++) {
            $id = trim($ids[$i]);
            $name = isset($names[$i]) ? trim($names[$i]) : '';
            if ($id && $name) {
              echo "{id: '" . addslashes($id) . "', name: '" . addslashes($name) . "'},";
            }
          }
        }
      ?>
    ];
    
    // Initialize edit form when modal is shown
    document.getElementById('editServiceModal<?= $service->service_type_id ?>').addEventListener('shown.bs.modal', function() {
      renderEditTags(<?= $service->service_type_id ?>, window.editAppliances<?= $service->service_type_id ?>);
    });
    
    // Handle add appliance button for edit form
    document.querySelector('#editServiceModal<?= $service->service_type_id ?> .edit-add-appliance-btn').onclick = function() {
      const input = document.getElementById('editApplianceInput<?= $service->service_type_id ?>');
      const val = input.value.trim();
      if (val) {
        if (!window.editAppliances<?= $service->service_type_id ?>.some(a => a.name.toLowerCase() === val.toLowerCase())) {
          // AJAX to insert and fetch real ID
          fetch('api/administrator/add_appliance_type.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'appliance_type_name=' + encodeURIComponent(val)
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              window.editAppliances<?= $service->service_type_id ?>.push({id: data.id, name: data.name});
              renderEditTags(<?= $service->service_type_id ?>, window.editAppliances<?= $service->service_type_id ?>);
            } else {
              alert('Error: ' + (data.error || 'Could not add appliance.'));
            }
          })
          .catch(() => alert('Network error.'));
        }
        input.value = '';
      }
    };
    // Allow Enter key to add appliance in Edit modal
    document.getElementById('editApplianceInput<?= $service->service_type_id ?>').addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        document.querySelector('#editServiceModal<?= $service->service_type_id ?> .edit-add-appliance-btn').click();
      }
    });
    // On submit, update hidden input
    document.querySelector('#editServiceModal<?= $service->service_type_id ?> form').onsubmit = function(e) {
      const hiddenField = document.getElementById('editApplianceHidden<?= $service->service_type_id ?>');
      const applianceIds = window.editAppliances<?= $service->service_type_id ?>.map(a => a.id).join(',');
      hiddenField.value = applianceIds;
      console.log('Form submission for service <?= $service->service_type_id ?>, appliance IDs:', applianceIds);
      console.log('Final appliances being saved:', window.editAppliances<?= $service->service_type_id ?>);
      return true;
    };
  <?php endforeach; ?>
});

function updateTableRow(serviceId, appliances) {
  const row = document.querySelector(`[data-bs-target='#editServiceModal${serviceId}']`).closest('tr');
  if (row) {
    const container = row.querySelector('.d-flex.flex-wrap.gap-2');
    if (container) {
      container.innerHTML = '';
      appliances.forEach(appl => {
        const tag = document.createElement('span');
        tag.className = 'badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2';
        tag.style.fontSize = '1em';
        tag.textContent = appl.name;
        container.appendChild(tag);
      });
    }
  }
}

// Service Type Filter Functionality with Pagination
const serviceTypeFilter = document.getElementById('serviceTypeFilter');

function filterServiceTable() {
    const filterValue = serviceTypeFilter.value;
    
    // Build URL with proper parameters
    let url = '?page=service-management';
    
    if (filterValue) {
        url += '&filter=' + encodeURIComponent(filterValue);
    }
    
    // Reset to page 1 when filtering
    url += '&page_num=1';
    
    window.location.href = url;
}

// Initialize filter from URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const filterParam = urlParams.get('filter');
    if (filterParam && serviceTypeFilter) {
        serviceTypeFilter.value = filterParam;
    }
});

serviceTypeFilter.addEventListener('change', filterServiceTable);
</script> 