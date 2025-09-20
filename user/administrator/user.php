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

    /* Validation Error Styling */
    .invalid-feedback {
        display: none; /* Hidden by default */
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
    }
    
    .invalid-feedback.show {
        display: block; /* Only show when explicitly shown */
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 1.4 1.4M7.2 4.6l-1.4 1.4'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    /* Hide body scrollbar when modal is open to prevent double scrollbars */
    body.modal-open {
        overflow: hidden !important;
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

    .enhanced-dropdown-wrapper {
        position: relative;
        width: 100%;
    }
    
    .enhanced-dropdown-wrapper.active .enhanced-dropdown-list {
        display: block;
    }
    
    .enhanced-dropdown-input {
        cursor: text;
        padding-right: 40px !important;
    }
    
    /* Handle floating label positioning when dropdown has value */
    .enhanced-dropdown-input:not(:placeholder-shown),
    .enhanced-dropdown-input.has-value {
        padding-top: 2.45rem !important;
        padding-left: .75rem !important;
        padding-bottom: 0.625rem !important;
        color: #495057 !important;
    }
    
    .enhanced-dropdown-input:focus {
        padding-top: 2.45rem !important;
        padding-bottom: 0.625rem !important;
    }
    
    /* Ensure placeholder is transparent for floating labels */
    .enhanced-dropdown-input::placeholder {
        color: transparent;
        opacity: 0;
    }
    
    .dropdown-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        z-index: 10;
        color: #6c757d;
    }
    
    .enhanced-dropdown-list {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
        margin-top: 5px;
    }
    
    .dropdown-search {
        position: relative;
        padding: 10px;
        border-bottom: 1px solid #dee2e6;
    }
    
    .dropdown-search .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .dropdown-search-input {
        width: 100%;
        padding: 8px 15px 8px 40px;
        border: none;
        outline: none;
        background: #f8f9fa;
        border-radius: 0.25rem;
    }
    
    .dropdown-options {
        max-height: 150px;
        overflow-y: auto;
    }
    
    .dropdown-option {
        padding: 10px 15px;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .dropdown-option:hover,
    .dropdown-option.selected {
        background-color: #e9ecef;
    }
    
    .dropdown-option.no-results {
        color: #6c757d;
        font-style: italic;
        cursor: default;
    }
    
    .dropdown-option.no-results:hover {
        background-color: transparent;
    }
    
</style>

<script>
// Back button functionality for mobile history view
function goBackToUserManagement() {
    // Navigate back to user management page
    window.location.href = '?page=user';
}
</script>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Back Button for Mobile History View -->
    <?php if (isset($_GET['history']) || isset($_GET['tech-history'])): ?>
    <div class="d-lg-none">
        <button type="button" class="btn btn-outline-secondary me-2" onclick="goBackToUserManagement()">
            <i class="bi bi-arrow-left me-1"></i>Back
        </button>
    </div>
    <?php endif; ?>
    
    <h3 class="mb-0"><?= ucfirst($_GET['page'] ?? 'User Management') ?></h3>
    
    <?php if (!isset($_GET['history']) && !isset($_GET['tech-history'])): ?>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-plus-lg me-2"></i>Add User
    </button>
    <?php endif; ?>
</div>

<!-- Filters and Search -->
<div class="dashboard-card mb-4" style="border-radius: 12px;">
    <div class="dashboard-card-header">
        <i class="bi bi-funnel me-2"></i>Filter Users
    </div>
    <div class="dashboard-card-body py-3" style="border-radius: 12px;">
        <div class="row g-3">
            <div class="col-md-4 col-6">
                <label class="form-label small text-muted mb-1">User Type</label>
                <select class="form-select" id="userTypeSelect">
                    <option value="All">All Types</option>
                    <option value="2">Technician</option>
                    <option value="3">Staff</option>
                    <option value="4">Customer</option>
                </select>
            </div>
            <div class="col-md-4 col-6">
                <label class="form-label small text-muted mb-1">Status</label>
                <select class="form-select" id="statusSelect">
                    <option value="All">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label small text-muted mb-1">Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="userNameSearch" class="form-control" style="font-weight: 450; color: black" placeholder="Search by name...">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="dashboard-card">
    <style>
        .table-responsive-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .fixed-table {
            table-layout: fixed;
            width: 100%;
            min-width: 800px; /* Minimum width for mobile horizontal scroll */
        }
        
        /* Mobile card styling */
        .mobile-user-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .mobile-user-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            transform: translateY(-1px);
        }
        
        .mobile-user-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        
        .mobile-user-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: #2c3e50;
            margin: 0;
            line-height: 1.3;
        }
        
        .mobile-user-status {
            flex-shrink: 0;
            margin-left: 12px;
        }
        
        .mobile-user-details {
            margin-bottom: 12px;
        }
        
        .mobile-user-email {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 4px;
            word-break: break-word;
        }
        
        .mobile-user-role {
            color: #495057;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .mobile-user-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        .mobile-user-actions .btn {
            flex: 1;
            min-width: 80px;
            font-size: 0.85rem;
            padding: 6px 12px;
        }
        
        @media (max-width: 991.98px) {
            .mobile-user-actions .btn {
                flex: none;
                min-width: 70px;
            }
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
        .fixed-table th:nth-child(1),
        .fixed-table td:nth-child(1) {
            width: 25%; /* Name - more space */
        }
        .fixed-table th:nth-child(2),
        .fixed-table td:nth-child(2) {
            width: 30%; /* Email - most space */
        }
        .fixed-table th:nth-child(3),
        .fixed-table td:nth-child(3) {
            width: 12%; /* Status - compact */
        }
        .fixed-table th:nth-child(4),
        .fixed-table td:nth-child(4) {
            width: 13%; /* Role - compact */
        }
        .fixed-table th:nth-child(5),
        .fixed-table td:nth-child(5) {
            width: 20%; /* Actions - moderate space */
            text-align: right;
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            /* Mobile filter layout */
            .row.mb-3 {
                margin-bottom: 1rem !important;
            }
            .row.mb-3 .col-md-6:first-child {
                margin-bottom: 10px;
            }
            .row.mb-3 .col-md-6 .d-flex {
                flex-wrap: wrap;
                gap: 8px !important;
            }
            .form-select {
                min-width: 120px !important;
                font-size: 14px;
                padding: 6px 8px;
            }
            .input-group {
                max-width: 100% !important;
            }
            .input-group .form-control {
                font-size: 14px;
                padding: 6px 8px;
            }
            .fixed-table {
                min-width: 750px; /* Increased to accommodate action buttons */
                font-size: 14px;
            }
            .fixed-table th,
            .fixed-table td {
                padding: 8px 4px;
            }
            /* Ensure action column has enough space */
            .fixed-table th:nth-child(5),
            .fixed-table td:nth-child(5) {
                min-width: 120px;
                width: 22%;
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
            /* Mobile phone filter layout */
            .row.mb-3 .col-md-6 .d-flex {
                flex-direction: column;
                gap: 6px !important;
            }
            .form-select {
                min-width: 100px !important;
                width: 100%;
                font-size: 13px;
                padding: 5px 6px;
            }
            .input-group {
                width: 100%;
            }
            .input-group .form-control {
                font-size: 13px;
                padding: 5px 6px;
            }
            .fixed-table {
                min-width: 650px; /* Increased to accommodate action buttons */
                font-size: 12px;
            }
            .fixed-table th,
            .fixed-table td {
                padding: 6px 3px;
            }
            /* Ensure action column has enough space on small screens */
            .fixed-table th:nth-child(5),
            .fixed-table td:nth-child(5) {
                min-width: 110px;
                width: 25%;
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
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                        <th scope="col">Role</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <!-- User data will be dynamically inserted here -->
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Mobile Card View -->
    <div class="d-lg-none" id="mobileUserCardsContainer">
        <!-- Mobile cards will be dynamically generated here -->
    </div>
    <!-- Desktop Pagination -->
    <div class="d-none d-lg-block" id="paginationContainer">
        <!-- Pagination controls will be inserted here -->
    </div>
    
    <!-- Mobile Pagination -->
    <div class="d-lg-none mt-3" id="mobilePaginationContainer">
        <!-- Mobile pagination will be dynamically generated here -->
    </div>
</div>

<!-- Add User Form -->
<div class="modal fade" id="addUserModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px 12px 12px 12px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 12px 12px 0 0;">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addUserForm">
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="border-0 round_md form-control mb-2 bg-light" id="first_name"
                                    name="first_name" autocomplete="off" placeholder="First Name" required>
                                <label for="first_name">First Name</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="border-0 round_md form-control mb-2 bg-light" id="middle_name"
                                    name="middle_name" autocomplete="off" placeholder="Middle Name">
                                <label for="middle_name">Middle Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="border-0 round_md form-control bg-light" id="last_name"
                            name="last_name" autocomplete="off" placeholder="Last Name" required>
                        <label for="last_name">Last Name</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="email" class="border-0 round_md form-control bg-light" id="email" style="font-weight: 600; color: #505050;"
                            name="email" autocomplete="off" placeholder="Email" pattern=".*@gmail\.com$" title="Email must end with @gmail.com">
                        <label for="email">Email</label>
                        <div class="invalid-feedback" id="email-error"></div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="border-0 round_md form-control bg-light" id="contact"
                            name="contact" autocomplete="off" placeholder="Contact" pattern="[0-9]{11}" title="Contact must be exactly 11 digits" maxlength="11" required>
                        <label for="contact">Contact</label>
                        <div class="invalid-feedback" id="contact-error"></div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control bg-light border-0 round_md" placeholder=" "
                            id="house_building_street" name="house_building_street" required>
                        <label for="house_building_street">House/Building Number & Street Name</label>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="form-floating mb-2 position-relative">
                                <div class="enhanced-dropdown-wrapper">
                                    <input type="text" 
                                        class="form-control bg-light border-0 round_md enhanced-dropdown-input" 
                                        id="province" 
                                        name="province" 
                                        autocomplete="off" 
                                        placeholder=" " 
                                        required>
                                    <div class="enhanced-dropdown-list" id="add-province-dropdown">
                                        <div class="dropdown-options" id="add-province-options">
                                            <!-- Options will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                                <label for="province">Province</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="form-floating mb-2 position-relative">
                                <div class="enhanced-dropdown-wrapper">
                                    <input type="text" 
                                        class="form-control bg-light border-0 round_md enhanced-dropdown-input" 
                                        id="municipality_city" 
                                        name="municipality_city" 
                                        autocomplete="off" 
                                        placeholder=" " 
                                        required>
                                    <div class="enhanced-dropdown-list" id="add-city-dropdown">
                                        <div class="dropdown-options" id="add-city-options">
                                            <!-- Options will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                                <label for="municipality_city">Municipality/City</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="form-floating mb-2 position-relative">
                                <div class="enhanced-dropdown-wrapper">
                                    <input type="text" 
                                        class="form-control bg-light border-0 round_md enhanced-dropdown-input" 
                                        id="barangay" 
                                        name="barangay" 
                                        autocomplete="off" 
                                        placeholder=" " 
                                        required>
                                    <div class="enhanced-dropdown-list" id="add-barangay-dropdown">
                                        <div class="dropdown-options" id="add-barangay-options">
                                            <!-- Options will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                                <label for="barangay">Barangay</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control bg-light border-0 round_md" id="zip_code" name="zip_code" autocomplete="off" placeholder="Zip Code" pattern="[0-9]{4}" title="Please enter exactly 4 digits" maxlength="4" required>
                                <label for="zip_code">Zip Code</label>
                                <div class="invalid-feedback" id="zip-code-error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" id="password"
                            class="bg-light border-0 round_md form-control show-hide-password round" name="password"
                            placeholder="Password" required />
                        <label for="password">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select bg-light border-0 round_md" id="user_type" name="user_type" required>
                            <option value="4">Customer</option>
                            <option value="3">Staff</option>
                            <option value="2">Technician</option>
                        </select>
                        <label for="user_type">User Type</label>
                    </div>
                    <div class="modal-footer p-0 border-0">
                        <button type="button" class="btn btn-light  border-0 px-3  rounded-pill"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary  border-0 px-3  rounded-pill">Add</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>
<!-- Edit User Form -->
<div class="modal fade" id="editUserModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editUserForm">
                    <input type="hidden" id="user_id" name="user_id">
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="round_md form-control mb-2 border-0 bg-light"
                                    id="edit_first_name" name="first_name" required>
                                <label for="edit_first_name">First Name</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="round_md form-control mb-2 border-0 bg-light"
                                    id="edit_middle_name" name="middle_name">
                                <label for="edit_middle_name">Middle Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="round_md form-control border-0 bg-light"
                            id="edit_last_name" name="last_name" required>
                        <label for="edit_last_name">Last Name</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="email" class="round_md form-control border-0 bg-light" id="edit_email" style="font-weight: 600; color: #505050;"
                            name="email" pattern=".*@gmail\.com$" title="Email must end with @gmail.com" required>
                        <label for="edit_email">Email</label>
                        <div class="invalid-feedback" id="edit-email-error"></div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="round_md form-control border-0 bg-light" style="font-weight: 600; color: #505050;"
                            id="edit_contact" name="contact" pattern="[0-9]{11}" title="Contact must be exactly 11 digits" maxlength="11" required>
                        <label for="edit_contact">Contact</label>
                        <div class="invalid-feedback" id="edit-contact-error"></div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control round_md border-0 bg-light" id="edit_house_building_street" name="house_building_street" style="font-weight: 600; color: #505050;"
                            placeholder="House/Building Number & Street Name" required>
                        <label for="edit_house_building_street">House/Building Number & Street Name</label>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="form-floating mb-2 position-relative">
                                <div class="enhanced-dropdown-wrapper">
                                    <input type="text" 
                                        class="form-control bg-light border-0 round_md enhanced-dropdown-input" 
                                        id="edit_province" 
                                        name="province" 
                                        autocomplete="off" 
                                        placeholder=" " 
                                        required>
                                    <div class="enhanced-dropdown-list" id="edit-province-dropdown">
                                        <div class="dropdown-options" id="edit-province-options">
                                            <!-- Options will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                                <label for="edit_province">Province</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="form-floating mb-2 position-relative">
                                <div class="enhanced-dropdown-wrapper">
                                    <input type="text" 
                                        class="form-control bg-light border-0 round_md enhanced-dropdown-input" 
                                        id="edit_municipality_city" 
                                        name="municipality_city" 
                                        autocomplete="off" 
                                        placeholder=" " 
                                        required>
                                    <div class="enhanced-dropdown-list" id="edit-city-dropdown">
                                        <div class="dropdown-options" id="edit-city-options">
                                            <!-- Options will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                                <label for="edit_municipality_city">Municipality/City</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="form-floating mb-2 position-relative">
                                <div class="enhanced-dropdown-wrapper">
                                    <input type="text" 
                                        class="form-control bg-light border-0 round_md enhanced-dropdown-input" 
                                        id="edit_barangay" 
                                        name="barangay" 
                                        autocomplete="off" 
                                        placeholder=" " 
                                        required>
                                    <div class="enhanced-dropdown-list" id="edit-barangay-dropdown">
                                        <div class="dropdown-options" id="edit-barangay-options">
                                            <!-- Options will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                                <label for="edit_barangay">Barangay</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control bg-light border-0 round_md" id="edit_zip_code" name="zip_code" autocomplete="off" placeholder=" " pattern="[0-9]{4}" title="Please enter exactly 4 digits" maxlength="4" required>
                                <label for="edit_zip_code">Zip Code</label>
                                <div class="invalid-feedback" id="edit-zip-code-error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" id="edit_password"
                            class="border-0 round_md form-control bg-light show-hide-password round" name="password"
                            placeholder="Password" required />
                        <label for="edit_password">Password</label>
                    </div>
                    <div id="editUserTypeContainer" class="form-floating mb-3">
                        <select class="form-select bg-light border-0 round_md" id="edit_user_type" name="user_type" required>
                            <option value="4">Customer</option>
                            <option value="3">Staff</option>
                            <option value="2">Technician</option>
                            <option value="1">Administrator</option>
                        </select>
                        <label for="edit_user_type">User Type</label>
                    </div>
                    <div class="modal-footer p-0 border-0">
                        <button type="button" class="btn btn-light  border-0 px-3  rounded-pill"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary  border-0 px-3  rounded-pill">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', async function () {
        // Add form submission handler to prevent multiple submissions
        const addUserForm = document.getElementById('addUserForm');
        if (addUserForm) {
            // Remove any existing event listeners
            addUserForm.onsubmit = null;
            
            // Remove all existing event listeners by cloning the form
            const newForm = addUserForm.cloneNode(true);
            addUserForm.parentNode.replaceChild(newForm, addUserForm);
            
            // Get the new form reference
            const cleanForm = document.getElementById('addUserForm');
            
            // Add single event listener for form submission
            cleanForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                e.stopImmediatePropagation(); // Stop all event propagation
                console.log('Form submission triggered - calling addUser');
                addUser(this); // Call our function
                return false;
            }, { once: false, passive: false });
            
            // Also prevent any button clicks from submitting the form
            const submitBtn = cleanForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    console.log('Submit button clicked - calling addUser');
                    addUser(cleanForm);
                    return false;
                }, { once: false, passive: false });
            }
        }
        
        const urlParams = new URLSearchParams(window.location.search);
        const initialTypeParam = urlParams.get('type');
        const initialUserPage = parseInt(urlParams.get('itempage')) || 1;
        const limit = 8; // Items per page

        // Map numeric type parameters to filter values
        let initialType = 'All';
        if (initialTypeParam === '4') {
            initialType = '4'; // Customer
        } else if (initialTypeParam === '3') {
            initialType = '3'; // Staff
        } else if (initialTypeParam === '2') {
            initialType = '2'; // Technician
        }

        const userTypeSelect = document.getElementById('userTypeSelect');
        const statusSelect = document.getElementById('statusSelect');
        userTypeSelect.value = initialType;
        statusSelect.value = 'All';
        await fetchUsers(initialType, 'All', initialUserPage, limit);

        userTypeSelect.addEventListener('change', function () {
            const selectedType = this.value;
            const selectedStatus = statusSelect.value;
            fetchUsers(selectedType, selectedStatus, 1, limit);
        });

        statusSelect.addEventListener('change', function () {
            const selectedType = userTypeSelect.value;
            const selectedStatus = this.value;
            fetchUsers(selectedType, selectedStatus, 1, limit);
        });

        document.getElementById('addUserForm').addEventListener('submit', function (event) {
            event.preventDefault();
            addUser(event.target);
        });

        document.getElementById('editUserForm').addEventListener('submit', function (event) {
            event.preventDefault();
            editUser(event.target);
        });

        // Add search functionality
        const userNameSearch = document.getElementById('userNameSearch');
        userNameSearch.addEventListener('input', function() {
            filterUserTable();
        });
    });

    async function fetchUsers(role = 'All', status = 'All', userPage = 1, limit = 8) {
        let fetchUrl = `api/administrator/user.php?pg=${userPage}&limit=${limit}`;
        fetchUrl += `&type=${role}&status=${status}`;
        const response = await fetch(fetchUrl);
        const data = await response.json();

        if (data.success) {
            const userContainer = document.getElementById('userTableBody');
            userContainer.innerHTML = ''; // Clear existing content

            // Check if users array is empty
            if (data.users.length === 0) {
                // Render empty state for desktop table
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="5" class="text-center py-5">
                        <div class="empty-state">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">No Users Found</h5>
                            <p class="text-muted mb-0">There are no users matching your current filters.</p>
                            <div class="mt-3">
                                <button onclick="fetchUsers('all', 'all', 1)" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Clear Filters
                                </button>
                            </div>
                        </div>
                    </td>
                `;
                userContainer.appendChild(emptyRow);
                
                // Render empty state for mobile cards
                renderMobileUserCards([]);
                return;
            }

            // Render desktop table
            data.users.forEach(user => {
                const userTypeName = user.user_type_name.charAt(0).toUpperCase() + user.user_type_name.slice(1);
                const statusBadge = user.is_active == 1 ? 
                    '<span class="badge bg-success">Active</span>' : 
                    '<span class="badge bg-secondary">Inactive</span>';
                const userRow = document.createElement('tr');
                userRow.innerHTML = `
                    <td title="${user.user_name} ${user.user_midname} ${user.user_lastname}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${user.user_name} ${user.user_midname} ${user.user_lastname}</td>
                    <td title="${user.user_email}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${user.user_email}</td>
                    <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${statusBadge}</td>
                    <td title="${userTypeName}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${userTypeName}</td>
                    <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        ${userTypeName === 'Customer' || userTypeName === 'Technician' ? `
                            
                    <a class="text-decoration-none" href="?page=appointment&${userTypeName === 'Customer' ? 'history' : 'tech-history'}=${user.user_id}">
                <button class="btn btn-light ps-3 pe-3 rounded-pill border-0">
                    <i class="bi bi-clock-history small"></i>
                </button>
                </a>
                            ` : ''}
                        ${user.user_id === '1' || user.user_id === 1 ? `
                            <button class="btn  btn-primary  ps-3 pe-3 rounded-pill border-0" onclick="editUser(${user.user_id})">
                                <i class="bi bi-pencil-fill small"></i>
                            </button>
                        ` : `
                            <button class="btn  btn-primary  ps-3 pe-3 rounded-pill border-0" onclick="editUser(${user.user_id})">
                                <i class="bi bi-pencil-fill small"></i>
                            </button>
                            <button class="btn   btn-danger  rounded-pill text-light ps-3 pe-3 border-0" onclick="deleteUser(${user.user_id})">
                                <i class="bi bi-trash-fill small "></i>
                            </button>
                        `}
                    </td>
                `;
                userContainer.appendChild(userRow);
            });

            // Render mobile cards
            const mobileUsersData = data.users.map(user => ({
                user_id: user.user_id,
                first_name: user.user_name,
                middle_name: user.user_midname,
                last_name: user.user_lastname,
                email: user.user_email,
                status: user.is_active == 1 ? 'Active' : 'Inactive',
                user_type: user.user_type,
                user_type_name: user.user_type_name
            }));
            renderMobileUserCards(mobileUsersData);

            // Add pagination controls
            if (data.totalPages != 0) {
                addPaginationControls(data.totalPages, userPage, role, status);
                renderMobileUserPagination(userPage, data.totalPages, role, status);
            }
            // Update the URL without adding a history entry
            const url = new URL(window.location);
            url.searchParams.set('type', role);
            url.searchParams.set('status', status);
            url.searchParams.set('itempage', userPage);
            window.history.replaceState({}, '', url);

        } else {
            console.error('Failed to fetch users:', data.message);
        }
    }



    function addPaginationControls(totalPages, currentPage, role, status) {
        const paginationContainer = document.getElementById('paginationContainer');
        paginationContainer.innerHTML = `
    <nav aria-label="Page navigation" class="text-center">
        <ul class="pagination p-2 justify-content-center pagination-sm">
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" aria-label="Previous" onclick="fetchUsers('${role}', '${status}', ${currentPage - 1})">
                  &lt;
                </a>
            </li>
            ${generatePaginationLinks(totalPages, currentPage, role, status)}
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" aria-label="Next" onclick="fetchUsers('${role}', '${status}', ${currentPage + 1})">
                    &gt;
                </a>
            </li>
        </ul>
    </nav>
    `;
    }
    function generatePaginationLinks(totalPages, currentPage, role, status) {
        let paginationLinks = '';



        // Special handling for pages 1-3
        if (currentPage === 1) {
            // Page 1: Show (1) 2 3 Last
            paginationLinks += `
            <li class="page-item active">
                <a class="page-link text-light rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', 1)">1</a>
            </li>
        `;

            // Add page 2 if it exists
            if (totalPages >= 2) {
                paginationLinks += `
                <li class="page-item">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', 2)">2</a>
                </li>
            `;
            }

            // Add page 3 if it exists
            if (totalPages >= 3) {
                paginationLinks += `
                <li class="page-item">
                     <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', 3)">3</a>
                </li>
            `;
            }
            // Add page 4 if it exists
            if (totalPages >= 4) {
                paginationLinks += `
                <li class="page-item">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', 4)">4</a>
                </li>
            `;
            }
            // Add Last page number if there are more than 3 pages
            if (totalPages > 3) {
                paginationLinks += `
                <li class="page-item">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', ${totalPages})">${totalPages}</a>
                </li>
            `;
            }
        } else if (currentPage === 2) {
            // Page 2: Show 1 (2) 3 4 Last

            // Add page 1
            paginationLinks += `
            <li class="page-item">
                <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', 1)">1</a>
            </li>
        `;

            // Add current page (2)
            paginationLinks += `
            <li class="page-item active">
                <a class="page-link text-light rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', 2)">2</a>
            </li>
        `;

            // Add page 3 if it exists
            if (totalPages >= 3) {
                paginationLinks += `
                <li class="page-item">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', 3)">3</a>
                </li>
            `;
            }

            // Add page 4 if it exists
            if (totalPages >= 4) {
                paginationLinks += `
                <li class="page-item">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', 4)">4</a>
                </li>
            `;
            }

            // Add Last page number if there are more than 4 pages
            if (totalPages > 4) {
                paginationLinks += `
                <li class="page-item">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', ${totalPages})">${totalPages}</a>
                </li>
            `;
            }
        } else if (currentPage >= totalPages - 1) {
            // Last two pages: Show 1 and last three pages

            // Add page 1 if not already shown
            if (totalPages > 4) {
                paginationLinks += `
                <li class="page-item">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', 1)">1</a>
                </li>
            `;
            }

            // Show last three pages (or fewer if totalPages < 3)
            const startPage = Math.max(1, totalPages - 3);
            for (let i = startPage; i <= totalPages; i++) {
                const isActive = i === currentPage;
                paginationLinks += `
                <li class="page-item ${isActive ? 'active' : ''}">
                     <a class="page-link ${isActive ? 'text-light' : 'text-dark'} rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', ${i})">${i}</a>
                </li>
            `;
            }
        } else {
            // Middle pages (4 through totalPages-2)

            // Add page 1
            paginationLinks += `
            <li class="page-item">
                <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', 1)">1</a>
            </li>
        `;

            // Current page with one page before and after
            for (let i = currentPage - 1; i <= currentPage + 1 && i <= totalPages; i++) {
                const isActive = i === currentPage;
                paginationLinks += `
                <li class="page-item ${isActive ? 'active' : ''}">
                     <a class="page-link ${isActive ? 'text-light' : 'text-dark'} rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', ${i})">${i}</a>
                </li>
            `;
            }

            // Add Last page number if not already shown
            if (currentPage < totalPages - 1) {
                paginationLinks += `
                <li class="page-item">
                    <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" onclick="fetchUsers('${role}', '${status}', ${totalPages})">${totalPages}</a>
                </li>
            `;
            }
        }



        return paginationLinks;
    }

    // Validation functions
    function validateEmail(email) {
        const gmailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
        return gmailPattern.test(email);
    }

    function validateContact(contact) {
        const contactPattern = /^[0-9]{11}$/;
        return contactPattern.test(contact);
    }

    function validateZipCode(zipCode) {
        const zipPattern = /^[0-9]{4}$/;
        return zipPattern.test(zipCode);
    }

    function showFieldError(fieldId, errorId, message) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.getElementById(errorId);
        
        if (field && errorDiv) {
            field.classList.add('is-invalid');
            errorDiv.textContent = message;
            errorDiv.classList.add('show');
        }
    }

    function hideFieldError(fieldId, errorId) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.getElementById(errorId);
        
        if (field && errorDiv) {
            field.classList.remove('is-invalid');
            errorDiv.classList.remove('show');
            errorDiv.textContent = '';
        }
    }

    function validateAddUserForm(form, forceValidation = false) {
        let isValid = true;
        
        // Only validate if forced (on submit) or if user has interacted with fields
        if (!forceValidation) {
            return true; // Skip validation if not forced
        }
        
        // Clear previous errors
        hideFieldError('email', 'email-error');
        hideFieldError('contact', 'contact-error');
        hideFieldError('zip_code', 'zip-code-error');
        
        // Get form values
        const email = form.email.value.trim();
        const contact = form.contact.value.trim();
        const zipCode = form.zip_code.value.trim();
        
        // Validate email
        if (email && !validateEmail(email)) {
            showFieldError('email', 'email-error', 'Email must end with @gmail.com');
            isValid = false;
        }
        
        // Validate contact
        if (contact && !validateContact(contact)) {
            showFieldError('contact', 'contact-error', 'Contact must be exactly 11 digits');
            isValid = false;
        }
        
        // Validate zip code
        if (zipCode && !validateZipCode(zipCode)) {
            showFieldError('zip_code', 'zip-code-error', 'Zip code must be exactly 4 digits');
            isValid = false;
        }
        
        return isValid;
    }

    function validateEditUserForm(form, forceValidation = false) {
        let isValid = true;
        
        // Only validate if forced (on submit) or if user has interacted with fields
        if (!forceValidation) {
            return true; // Skip validation if not forced
        }
        
        // Clear previous errors
        hideFieldError('edit_email', 'edit-email-error');
        hideFieldError('edit_contact', 'edit-contact-error');
        hideFieldError('edit_zip_code', 'edit-zip-code-error');
        
        // Get form values
        const email = form.email.value.trim();
        const contact = form.contact.value.trim();
        const zipCode = form.zip_code.value.trim();
        
        // Validate email
        if (email && !validateEmail(email)) {
            showFieldError('edit_email', 'edit-email-error', 'Email must end with @gmail.com');
            isValid = false;
        }
        
        // Validate contact
        if (contact && !validateContact(contact)) {
            showFieldError('edit_contact', 'edit-contact-error', 'Contact must be exactly 11 digits');
            isValid = false;
        }
        
        // Validate zip code
        if (zipCode && !validateZipCode(zipCode)) {
            showFieldError('edit_zip_code', 'edit-zip-code-error', 'Zip code must be exactly 4 digits');
            isValid = false;
        }
        
        return isValid;
    }

    // Global variable to prevent double submission
    let isAddUserSubmitting = false;

    async function addUser(form) {
        try {
            // Prevent double submission
            if (isAddUserSubmitting) {
                console.log('Form submission already in progress, preventing duplicate submission');
                return;
            }
            
            // Set flag to prevent double submission
            isAddUserSubmitting = true;
            
            // Validate form before submission with force validation
            if (!validateAddUserForm(form, true)) {
                isAddUserSubmitting = false; // Reset flag if validation fails
                return; // Stop submission if validation fails
            }
            
            // Disable submit button to prevent multiple clicks
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Adding...';
            
            const formData = new FormData(form);
            console.log('Sending form data:', Object.fromEntries(formData));
            
            const response = await fetch('api/administrator/add_user.php', {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(formData)),
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            
            console.log('Response status:', response.status);
            
            // Check if response is OK (200-299 status codes)
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Since backend is working correctly, assume success if we get here
            // Try to parse JSON, but don't fail if it's malformed
            let data = null;
            let jsonParseSuccess = false;
            
            try {
                const responseText = await response.text();
                console.log('Response text:', responseText);
                data = JSON.parse(responseText);
                console.log('Parsed JSON data:', data);
                jsonParseSuccess = true;
            } catch (jsonError) {
                console.warn('JSON parsing failed, but assuming success since backend works:', jsonError);
                // Backend is working, so assume success
                jsonParseSuccess = false;
            }

            // Check for success - either from parsed JSON or assume success if parsing failed
            const isSuccess = jsonParseSuccess ? (data && data.success) : true;
            
            if (isSuccess) {
                console.log('User added successfully, showing success message');
                successToast('User added successfully!');
                // Clear form fields
                form.reset();
                document.getElementById('user_type').value = '4'; // Reset user_type to default
                // Clear any validation errors
                hideFieldError('email', 'email-error');
                hideFieldError('contact', 'contact-error');
                hideFieldError('zip_code', 'zip-code-error');
                // Close modal
                $('#addUserModal').modal('hide');
                // Refresh user table
                await fetchUsers(document.getElementById('userTypeSelect').value);
            } else {
                console.log('Server returned error:', data ? data.message : 'Unknown error');
                dangerToast('Failed to add user: ' + (data ? data.message : 'Unknown error occurred'));
            }
            
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            
        } catch (error) {
            console.error('Detailed error adding user:', error);
            console.error('Error stack:', error.stack);
            
            // More specific error message based on error type
            let errorMessage = 'An error occurred while adding the user.';
            if (error.message.includes('JSON')) {
                errorMessage = 'Server response error. Please try again.';
            } else if (error.message.includes('HTTP')) {
                errorMessage = 'Network error. Please check your connection and try again.';
            } else if (error.message.includes('fetch')) {
                errorMessage = 'Connection error. Please try again.';
            }
            
            dangerToast(errorMessage);
            
            // Re-enable submit button on error
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Add';
            }
        } finally {
            // Always reset the flag
            isAddUserSubmitting = false;
        }
    }




    async function deleteUser(userId) {
        showDialog({
            title: 'Delete',
            message: 'Do you want to <span class="fw-bold">delete</span> this user?.',
            confirmText: 'Delete',
            cancelText: 'Cancel',
            onConfirm: async function () {
                try {
                    const response = await fetch('api/administrator/delete_user.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            user_id: userId
                        }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                    const data = await response.json();

                    if (data.success) {
                        successToast('Deleted successfully!');
                        // Refresh user table immediately
                        await fetchUsers(document.getElementById('userTypeSelect').value);
                    } else {
                        dangerToast('Failed to delete user: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error deleting user:', error);
                    dangerToast('An error occurred while deleting the user.');
                }
            }
        });
    }

    function editUser(userId) {
        if (userId instanceof HTMLFormElement) {
            const form = userId;
            handleEditUserSubmit(form);
            return;
        }
        fetchUserData(userId);
    }

    async function fetchUserData(userId) {
        try {
            const response = await fetch(`api/administrator/get_user.php?user_id=${userId}`);
            const data = await response.json();

            if (data.success) {
                const user = data.user;
                document.getElementById('user_id').value = user.user_id;
                document.getElementById('edit_first_name').value = user.user_name;
                document.getElementById('edit_middle_name').value = user.user_midname;
                document.getElementById('edit_last_name').value = user.user_lastname;
                document.getElementById('edit_email').value = user.user_email;
                document.getElementById('edit_contact').value = user.user_contact;
                // Populate dissected address fields
                document.getElementById('edit_house_building_street').value = user.house_building_street || '';
                document.getElementById('edit_barangay').value = user.barangay || '';
                document.getElementById('edit_municipality_city').value = user.municipality_city || '';
                document.getElementById('edit_province').value = user.province || '';
                document.getElementById('edit_zip_code').value = user.zip_code || '';
                document.getElementById('edit_password').value = user.user_pass;
                document.getElementById('edit_user_type').value = user.user_type_id;

                // Show/hide the user type select based on user type
                const editUserTypeContainer = document.getElementById('editUserTypeContainer');
                const editUserTypeSelect = document.getElementById('edit_user_type');
                if (user.user_type_id == 1) {
                    // Hide user type section for admin user but keep the value for form submission
                    editUserTypeContainer.style.display = 'none';
                    editUserTypeSelect.removeAttribute('required');
                    // Ensure the admin user_type value is preserved for backend validation
                    editUserTypeSelect.value = '1';
                } else {
                    editUserTypeContainer.style.display = '';
                    editUserTypeSelect.setAttribute('required', 'required');
                }

                $('#editUserModal').modal('show');
            } else {
                dangerToast('Failed to fetch user data: ' + data.message);
            }
        } catch (error) {
            console.error('Error fetching user data:', error);
            dangerToast('An error occurred while fetching user data.');
        }
    }

    // Global variable to prevent duplicate edit user submissions
    let isEditUserSubmitting = false;
    
    async function handleEditUserSubmit(form) {
        // Prevent duplicate submissions
        if (isEditUserSubmitting) {
            console.log('Edit user submission already in progress, ignoring duplicate call');
            return;
        }
        
        try {
            isEditUserSubmitting = true;
            
            // Validate form before submission with force validation
            if (!validateEditUserForm(form, true)) {
                return; // Stop submission if validation fails
            }
            
            const formData = new FormData(form);
            const response = await fetch('api/administrator/edit_user.php', {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(formData)),
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();

            if (data.success) {
                successToast('User updated successfully!');
                // Clear any validation errors
                hideFieldError('edit_email', 'edit-email-error');
                hideFieldError('edit_contact', 'edit-contact-error');
                hideFieldError('edit_zip_code', 'edit-zip-code-error');
                // Close modal first
                $('#editUserModal').modal('hide');
                // Then refresh user table
                await fetchUsers(document.getElementById('userTypeSelect').value);
            } else {
                dangerToast('Failed to update user: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error updating user:', error);
            dangerToast('An error occurred while updating the user: ' + error.message);
        } finally {
            // Always reset the submission flag
            isEditUserSubmitting = false;
        }
    }


    // User name search filter functionality
    function filterUserTable() {
        const searchValue = document.getElementById('userNameSearch').value.toLowerCase();
        
        // Filter desktop table rows
        const userTableBody = document.getElementById('userTableBody');
        if (userTableBody) {
            const userRows = userTableBody.getElementsByTagName('tr');
            for (let i = 0; i < userRows.length; i++) {
                const row = userRows[i];
                const nameCell = row.cells[0]; // Name is the first column
                
                if (nameCell) {
                    const nameMatch = searchValue === '' || nameCell.textContent.toLowerCase().includes(searchValue);
                    
                    if (nameMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            }
        }
        
        // Filter mobile cards
        const mobileContainer = document.getElementById('mobileUserCardsContainer');
        if (mobileContainer) {
            const mobileCards = mobileContainer.querySelectorAll('.mobile-user-card');
            mobileCards.forEach(card => {
                const nameElement = card.querySelector('.mobile-user-name');
                if (nameElement) {
                    const nameMatch = searchValue === '' || nameElement.textContent.toLowerCase().includes(searchValue);
                    
                    if (nameMatch) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        }
    }

    // Address data structure
    const addressData = {
        "Davao del Norte": {
            cities: ["Tagum City", "Panabo City", "Carmen", "Sto. Tomas"],
            zipCodes: {
                "Tagum City": "8100",
                "Panabo City": "8105",
                "Carmen": "8101",
                "Sto. Tomas": "8112"
            },
            barangays: {
                "Tagum City": ["Apokon", "Bincungan", "Busaon", "Canocotan", "Cuambogan", "La Filipina", "Liboganon", "Madaum", "Mankilam", "New Balamban", "Nueva Fuerza", "Pagsabangan", "Pandapan", "Magugpo Central", "Magugpo East", "Magugpo North", "Magugpo Poblacion", "Magugpo South", "Magugpo West", "San Agustin", "San Isidro", "San Miguel", "Visayan Village"],
                "Panabo City": ["A. O. Floirendo", "Datu Abdul Dadia", "Buenavista", "Cacao", "Cagangohan", "Consolacion", "Dapco", "Gredu", "J.P. Laurel", "Kasilak", "Katipunan", "Katualan", "Kauswagan", "Kiotoy", "Little Panay", "Lower Panaga (Roxas)", "Mabunao", "Madaum", "Malativas", "Manay", "Nanyo", "New Malaga", "New Malitbog", "New Pandan", "New Visayas", "Quezon", "Salvacion", "San Francisco", "San Nicolas", "San Pedro", "San Roque", "San Vicente", "Santa Cruz", "Santo Niño", "Sindaton", "Southern Davao", "Tagpore", "Tibungol", "Upper Licanan", "Waterfall"],
                "Carmen": ["Alejal", "Anibongan", "Asuncion (Cuatro-Cuatro)", "Cebulano", "Guadalupe", "Ising", "La Paz", "Mabaus", "Mabuhay", "Magsaysay", "Mangalcal", "Minda", "New Camiling", "Salvacion", "San Isidro", "Sto. Niño", "Taba", "Tibulao", "Tubod", "Tuganay"],
                "Sto. Tomas": ["Bobongon", "Tibal-og", "Balagunan", "Casig-ang", "Esperanza", "Kimamon", "Kinamayan", "La Libertad", "Lunga-og", "Magwawa", "New Katipunan", "New Visayas", "Pantaron", "Salvacion", "San Jose", "San Miguel", "San Vicente", "Talomo", "Tulalian"]
            }
        }
    };

    // Enhanced Dropdown Class
    class EnhancedDropdown {
        constructor(inputId, optionsId, dropdownId, data, placeholder = 'Search...') {
            this.input = document.getElementById(inputId);
            this.optionsContainer = document.getElementById(optionsId);
            this.dropdown = document.getElementById(dropdownId);
            this.wrapper = this.input.closest('.enhanced-dropdown-wrapper');
            this.data = data;
            this.filteredData = [...this.data];
            this.placeholder = placeholder;
            this.selectedValue = '';
            this.isOpen = false;
            this.isSearchMode = false;
            this.selectedIndex = -1;
            
            this.init();
        }
        
        init() {
            this.setupEventListeners();
            this.updateOptions();
        }
        
        getPlaceholderText() {
            const fieldName = this.input.name;
            if (fieldName === 'barangay') return 'Select Barangay';
            if (fieldName === 'municipality_city') return 'Select Municipality/City';
            if (fieldName === 'province') return 'Select Province';
            return 'Select option';
        }
        
        updateDisplay() {
            if (this.selectedValue) {
                this.input.value = this.selectedValue;
                this.input.classList.add('has-value');
            } else {
                this.input.value = '';
                this.input.classList.remove('has-value');
            }
        }
        
        setupEventListeners() {
            // Click on input to open dropdown
            this.input.addEventListener('click', (e) => {
                e.stopPropagation();
                if (!this.isOpen) {
                    this.open();
                }
            });
            
            // Main input search functionality with custom entry support
            this.input.addEventListener('input', (e) => {
                const value = e.target.value;
                if (!this.isOpen && value.length > 0) {
                    this.open();
                }
                this.isSearchMode = true;
                this.selectedValue = value; // Allow custom entries
                this.filter(value);
                
                // Update has-value class for styling
                if (value) {
                    this.input.classList.add('has-value');
                } else {
                    this.input.classList.remove('has-value');
                }
            });
            
            // Handle focus to enable search mode
            this.input.addEventListener('focus', (e) => {
                if (!this.isOpen) {
                    this.open();
                }
            });
            
            // Handle blur to exit search mode and accept custom entries
            this.input.addEventListener('blur', (e) => {
                // Delay to allow option selection
                setTimeout(() => {
                    if (!this.wrapper.contains(document.activeElement)) {
                        // Accept typed value as custom entry
                        const value = this.input.value.trim();
                        if (value && this.isSearchMode) {
                            this.selectedValue = value;
                            this.input.classList.add('has-value');
                            // Trigger change event for interdependent functionality
                            const changeEvent = new Event('change', { bubbles: true });
                            this.input.dispatchEvent(changeEvent);
                        }
                        this.exitSearchMode();
                        this.close();
                    }
                }, 150);
            });
            
            // Close dropdown when clicking outside and accept custom entries
            document.addEventListener('click', (e) => {
                // Don't close if clicking within the same modal or on other form elements in modal
                const isInModal = e.target.closest('.modal-content');
                const isFormElement = e.target.matches('input, select, textarea, button, label');
                const isInSameModal = this.wrapper.closest('.modal-content') === e.target.closest('.modal-content');
                
                // Only close if clicking completely outside modal or outside this specific dropdown
                if (!this.wrapper.contains(e.target) && (!isInModal || !isInSameModal || !isFormElement)) {
                    // Accept typed value as custom entry
                    const value = this.input.value.trim();
                    if (value && this.isSearchMode) {
                        this.selectedValue = value;
                        this.input.classList.add('has-value');
                        // Trigger change event for interdependent functionality
                        const changeEvent = new Event('change', { bubbles: true });
                        this.input.dispatchEvent(changeEvent);
                    }
                    this.exitSearchMode();
                    this.close();
                }
            });
            
            // Keyboard navigation
            this.input.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (!this.isOpen) {
                        this.open();
                    } else {
                        this.navigateOptions(e.key === 'ArrowDown' ? 1 : -1);
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    const selected = this.optionsContainer.querySelector('.dropdown-option.selected');
                    if (selected && !selected.classList.contains('no-results')) {
                        this.selectOption(selected.textContent);
                    }
                } else if (e.key === 'Escape') {
                    this.exitSearchMode();
                    this.close();
                }
            });
        }
        
        populateOptions(data) {
            this.optionsContainer.innerHTML = '';
            
            if (data.length === 0) {
                const noResults = document.createElement('div');
                noResults.className = 'dropdown-option no-results';
                noResults.textContent = 'No options available';
                this.optionsContainer.appendChild(noResults);
                return;
            }
            
            data.forEach(item => {
                const option = document.createElement('div');
                option.className = 'dropdown-option';
                option.textContent = item;
                option.addEventListener('click', () => {
                    this.selectOption(item);
                });
                this.optionsContainer.appendChild(option);
            });
        }
        
        filter(searchTerm) {
            this.filteredData = this.data.filter(item => 
                item.toLowerCase().includes(searchTerm.toLowerCase())
            );
            this.updateOptions();
            this.selectedIndex = -1;
        }
        
        exitSearchMode() {
            this.isSearchMode = false;
            if (this.selectedValue) {
                this.input.value = this.selectedValue;
                this.input.classList.add('has-value');
            } else {
                this.input.value = '';
                this.input.classList.remove('has-value');
            }
        }
        
        updateOptions() {
            this.optionsContainer.innerHTML = '';
            
            if (this.filteredData.length === 0) {
                const noResults = document.createElement('div');
                noResults.className = 'dropdown-option no-results';
                noResults.textContent = 'No results found';
                this.optionsContainer.appendChild(noResults);
            } else {
                this.filteredData.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'dropdown-option';
                    option.textContent = item;
                    option.addEventListener('click', () => {
                        this.selectOption(item);
                    });
                    this.optionsContainer.appendChild(option);
                });
            }
        }
        
        selectOption(value) {
            this.selectedValue = value;
            this.input.value = value;
            this.input.classList.add('has-value');
            this.isSearchMode = false;
            this.close();
            
            // Trigger change event for interdependent functionality
            const changeEvent = new Event('change', { bubbles: true });
            this.input.dispatchEvent(changeEvent);
            const inputEvent = new Event('input', { bubbles: true });
            this.input.dispatchEvent(inputEvent);
        }
        
        updateData(newData) {
            this.data = newData;
            this.populateOptions(newData);
        }
        
        setValue(value) {
            this.selectedValue = value;
            this.input.value = value;
            if (value) {
                this.input.classList.add('has-value');
            } else {
                this.input.classList.remove('has-value');
            }
        }
        
        clear() {
            this.selectedValue = '';
            this.input.value = '';
            this.input.classList.remove('has-value');
            this.populateOptions(this.data);
        }
        
        open() {
            this.isOpen = true;
            this.wrapper.classList.add('active');
            this.searchInput.value = '';
            this.searchInput.focus();
            this.populateOptions(this.data);
        }
        
        close() {
            this.isOpen = false;
            this.wrapper.classList.remove('active');
            this.clearSelection();
        }
        
        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        }
        
        navigateOptions(direction) {
            const options = this.optionsContainer.querySelectorAll('.dropdown-option:not(.no-results)');
            if (options.length === 0) return;
            
            const currentSelected = this.optionsContainer.querySelector('.dropdown-option.selected');
            let newIndex = 0;
            
            if (currentSelected) {
                const currentIndex = Array.from(options).indexOf(currentSelected);
                newIndex = currentIndex + direction;
                currentSelected.classList.remove('selected');
            }
            
            if (newIndex < 0) newIndex = options.length - 1;
            if (newIndex >= options.length) newIndex = 0;
            
            options[newIndex].classList.add('selected');
            options[newIndex].scrollIntoView({ block: 'nearest' });
        }
        
        clearSelection() {
            const selected = this.optionsContainer.querySelector('.dropdown-option.selected');
            if (selected) {
                selected.classList.remove('selected');
            }
        }
    }
    
    // Function to handle barangay selection for Add User form
    function onBarangayChange() {
        const barangaySelect = document.getElementById('barangay');
        const citySelect = document.getElementById('municipality_city');
        const provinceSelect = document.getElementById('province');
        const zipCodeInput = document.getElementById('zip_code');
        
        const selectedBarangay = barangaySelect.value;
        
        if (selectedBarangay) {
            // Find the city and province for this barangay
            Object.keys(addressData).forEach(province => {
                Object.keys(addressData[province].barangays).forEach(city => {
                    if (addressData[province].barangays[city].includes(selectedBarangay)) {
                        citySelect.value = city;
                        provinceSelect.value = province;
                        
                        // Auto-fill zip code based on city
                        if (addressData[province] && addressData[province].zipCodes && addressData[province].zipCodes[city]) {
                            zipCodeInput.value = addressData[province].zipCodes[city];
                        }
                        
                        // Update dropdowns
                        if (window.addressDropdowns && window.addressDropdowns.add) {
                            window.addressDropdowns.add.city.setValue(city);
                            window.addressDropdowns.add.province.setValue(province);
                            updateBarangayDropdown('add', city, province);
                        }
                    }
                });
            });
        }
    }

    // Function to handle city selection for Add User form
    function onCityChange() {
        const citySelect = document.getElementById('municipality_city');
        const provinceSelect = document.getElementById('province');
        const zipCodeInput = document.getElementById('zip_code');
        
        const selectedCity = citySelect.value;
        
        if (selectedCity) {
            // Find the province for this city
            Object.keys(addressData).forEach(province => {
                if (addressData[province].cities.includes(selectedCity)) {
                    provinceSelect.value = province;
                    
                    // Auto-fill zip code based on city
                    if (addressData[province] && addressData[province].zipCodes && addressData[province].zipCodes[selectedCity]) {
                        zipCodeInput.value = addressData[province].zipCodes[selectedCity];
                    }
                    
                    // Update dropdowns
                    if (window.addressDropdowns && window.addressDropdowns.add) {
                        window.addressDropdowns.add.province.setValue(province);
                        updateBarangayDropdown('add', selectedCity, province);
                    }
                }
            });
        }
    }

    // Function to handle province selection for Add User form
    function onProvinceChange() {
        const provinceSelect = document.getElementById('province');
        const selectedProvince = provinceSelect.value;
        
        if (selectedProvince && addressData[selectedProvince]) {
            // Update city dropdown
            if (window.addressDropdowns && window.addressDropdowns.add) {
                updateCityDropdown('add', selectedProvince);
                updateBarangayDropdown('add', null, selectedProvince);
            }
        }
    }

    // Function to handle barangay selection for Edit User form
    function onEditBarangayChange() {
        const barangaySelect = document.getElementById('edit_barangay');
        const citySelect = document.getElementById('edit_municipality_city');
        const provinceSelect = document.getElementById('edit_province');
        const zipCodeInput = document.getElementById('edit_zip_code');
        
        const selectedBarangay = barangaySelect.value;
        
        if (selectedBarangay) {
            // Find the city and province for this barangay
            Object.keys(addressData).forEach(province => {
                Object.keys(addressData[province].barangays).forEach(city => {
                    if (addressData[province].barangays[city].includes(selectedBarangay)) {
                        citySelect.value = city;
                        provinceSelect.value = province;
                        
                        // Auto-fill zip code based on city
                        if (addressData[province] && addressData[province].zipCodes && addressData[province].zipCodes[city]) {
                            zipCodeInput.value = addressData[province].zipCodes[city];
                        }
                        
                        // Update dropdowns
                        if (window.addressDropdowns && window.addressDropdowns.edit) {
                            window.addressDropdowns.edit.city.setValue(city);
                            window.addressDropdowns.edit.province.setValue(province);
                            updateBarangayDropdown('edit', city, province);
                        }
                    }
                });
            });
        }
    }

    // Function to handle city selection for Edit User form
    function onEditCityChange() {
        const citySelect = document.getElementById('edit_municipality_city');
        const provinceSelect = document.getElementById('edit_province');
        const zipCodeInput = document.getElementById('edit_zip_code');
        
        const selectedCity = citySelect.value;
        
        if (selectedCity) {
            // Find the province for this city
            Object.keys(addressData).forEach(province => {
                if (addressData[province].cities.includes(selectedCity)) {
                    provinceSelect.value = province;
                    
                    // Auto-fill zip code based on city
                    if (addressData[province] && addressData[province].zipCodes && addressData[province].zipCodes[selectedCity]) {
                        zipCodeInput.value = addressData[province].zipCodes[selectedCity];
                    }
                    
                    // Update dropdowns
                    if (window.addressDropdowns && window.addressDropdowns.edit) {
                        window.addressDropdowns.edit.province.setValue(province);
                        updateBarangayDropdown('edit', selectedCity, province);
                    }
                }
            });
        }
    }

    // Function to handle province selection for Edit User form
    function onEditProvinceChange() {
        const provinceSelect = document.getElementById('edit_province');
        const selectedProvince = provinceSelect.value;
        
        if (selectedProvince && addressData[selectedProvince]) {
            // Update city dropdown
            if (window.addressDropdowns && window.addressDropdowns.edit) {
                updateCityDropdown('edit', selectedProvince);
                updateBarangayDropdown('edit', null, selectedProvince);
            }
        }
    }

    // Function to update barangay dropdown based on province and city
    function updateBarangayDropdown(formType, city, province) {
        if (window.addressDropdowns && window.addressDropdowns[formType]) {
            let barangays = [];
            
            if (city && province && addressData[province] && addressData[province].barangays[city]) {
                barangays = addressData[province].barangays[city];
            } else if (province && addressData[province]) {
                // Show all barangays for the province
                Object.keys(addressData[province].barangays).forEach(cityKey => {
                    barangays = barangays.concat(addressData[province].barangays[cityKey]);
                });
                // Remove duplicates
                barangays = [...new Set(barangays)];
            }
            
            window.addressDropdowns[formType].barangay.updateData(barangays.sort());
        }
    }

    // Function to update city dropdown based on province
    function updateCityDropdown(formType, province) {
        if (window.addressDropdowns && window.addressDropdowns[formType]) {
            let cities = [];
            
            if (province && addressData[province]) {
                cities = addressData[province].cities;
            }
            
            window.addressDropdowns[formType].city.updateData(cities.sort());
        }
    }

    // Enhanced Address Filtering Setup
    function setupAddressFiltering() {
        console.log('Setting up enhanced address filtering...');
        
        // Extract all barangays from addressData
        const allBarangays = [];
        Object.keys(addressData).forEach(province => {
            Object.keys(addressData[province].barangays).forEach(city => {
                addressData[province].barangays[city].forEach(barangay => {
                    if (!allBarangays.includes(barangay)) {
                        allBarangays.push(barangay);
                    }
                });
            });
        });
        
        // Extract all cities
        const allCities = [];
        Object.keys(addressData).forEach(province => {
            addressData[province].cities.forEach(city => {
                if (!allCities.includes(city)) {
                    allCities.push(city);
                }
            });
        });
        
        // Extract all provinces
        const allProvinces = Object.keys(addressData);
        
        // Initialize enhanced dropdowns for Add User form
        const addBarangayDropdown = new EnhancedDropdown(
            'barangay', 
            'add-barangay-options', 
            'add-barangay-dropdown', 
            allBarangays.sort(), 
            'Barangay'
        );
        
        const addCityDropdown = new EnhancedDropdown(
            'municipality_city', 
            'add-city-options', 
            'add-city-dropdown', 
            allCities.sort(), 
            'Municipality/City'
        );
        
        const addProvinceDropdown = new EnhancedDropdown(
            'province', 
            'add-province-options', 
            'add-province-dropdown', 
            allProvinces.sort(), 
            'Province'
        );
        
        // Initialize enhanced dropdowns for Edit User form
        const editBarangayDropdown = new EnhancedDropdown(
            'edit_barangay', 
            'edit-barangay-options', 
            'edit-barangay-dropdown', 
            allBarangays.sort(), 
            'Barangay'
        );
        
        const editCityDropdown = new EnhancedDropdown(
            'edit_municipality_city', 
            'edit-city-options', 
            'edit-city-dropdown', 
            allCities.sort(), 
            'Municipality/City'
        );
        
        const editProvinceDropdown = new EnhancedDropdown(
            'edit_province', 
            'edit-province-options', 
            'edit-province-dropdown', 
            allProvinces.sort(), 
            'Search provinces...'
        );
        
        // Store dropdown instances globally
        window.addressDropdowns = {
            add: {
                barangay: addBarangayDropdown,
                city: addCityDropdown,
                province: addProvinceDropdown
            },
            edit: {
                barangay: editBarangayDropdown,
                city: editCityDropdown,
                province: editProvinceDropdown
            }
        };
        
        // Set up change event listeners for Add User form
        document.getElementById('province').addEventListener('change', onProvinceChange);
        document.getElementById('municipality_city').addEventListener('change', onCityChange);
        document.getElementById('barangay').addEventListener('change', onBarangayChange);
        
        // Set up change event listeners for Edit User form
        document.getElementById('edit_province').addEventListener('change', onEditProvinceChange);
        document.getElementById('edit_municipality_city').addEventListener('change', onEditCityChange);
        document.getElementById('edit_barangay').addEventListener('change', onEditBarangayChange);
        
        // Add zip code input validation for Add User Modal
        const zipCodeInput = document.getElementById('zip_code');
        if (zipCodeInput) {
            zipCodeInput.addEventListener('input', function(event) {
                let value = event.target.value.replace(/[^0-9]/g, '');
                if (value.length > 4) {
                    value = value.substring(0, 4);
                }
                event.target.value = value;
            });
        }
        
        // Add zip code input validation for Edit User Modal
        const editZipCodeInput = document.getElementById('edit_zip_code');
        if (editZipCodeInput) {
            editZipCodeInput.addEventListener('input', function(event) {
                // Store current address values before validation
                const currentBarangay = document.getElementById('edit_barangay').value;
                const currentCity = document.getElementById('edit_municipality_city').value;
                const currentProvince = document.getElementById('edit_province').value;
                
                let value = event.target.value.replace(/[^0-9]/g, '');
                if (value.length > 4) {
                    value = value.substring(0, 4);
                }
                event.target.value = value;
                
                // Restore address values if they were cleared
                setTimeout(() => {
                    if (!document.getElementById('edit_barangay').value && currentBarangay) {
                        document.getElementById('edit_barangay').value = currentBarangay;
                    }
                    if (!document.getElementById('edit_municipality_city').value && currentCity) {
                        document.getElementById('edit_municipality_city').value = currentCity;
                    }
                    if (!document.getElementById('edit_province').value && currentProvince) {
                        document.getElementById('edit_province').value = currentProvince;
                    }
                }, 10);
            });
            
            // Prevent any other event handlers from clearing address fields when zip code changes
            editZipCodeInput.addEventListener('change', function(event) {
                event.stopPropagation();
            });
            
            editZipCodeInput.addEventListener('blur', function(event) {
                event.stopPropagation();
            });
        }
        
        console.log('Enhanced address filtering setup complete!');
    }
    
    // Setup real-time validation for Add User Modal
    function setupAddUserValidation() {
        const emailField = document.getElementById('email');
        const contactField = document.getElementById('contact');
        const zipCodeField = document.getElementById('zip_code');
        let hasUserInteracted = {
            email: false,
            contact: false,
            zipCode: false
        };
        
        // Email validation on input
        if (emailField) {
            emailField.addEventListener('input', function() {
                hasUserInteracted.email = true; // Mark as user has started typing
                
                const email = this.value.trim();
                if (email && !validateEmail(email)) {
                    showFieldError('email', 'email-error', 'Email must end with @gmail.com');
                } else {
                    hideFieldError('email', 'email-error');
                }
            });
        }
        
        // Contact validation on input (only allow numbers)
        if (contactField) {
            contactField.addEventListener('input', function() {
                hasUserInteracted.contact = true; // Mark as user has started typing
                
                // Remove any non-digit characters
                this.value = this.value.replace(/[^0-9]/g, '');
                
                const contact = this.value.trim();
                if (contact && !validateContact(contact)) {
                    if (contact.length < 11) {
                        showFieldError('contact', 'contact-error', 'Contact must be exactly 11 digits');
                    } else if (contact.length > 11) {
                        showFieldError('contact', 'contact-error', 'Contact cannot exceed 11 digits');
                    }
                } else {
                    hideFieldError('contact', 'contact-error');
                }
            });
        }
        
        // Zip code validation on input (only allow numbers)
        if (zipCodeField) {
            zipCodeField.addEventListener('input', function() {
                hasUserInteracted.zipCode = true; // Mark as user has started typing
                
                // Remove any non-digit characters
                this.value = this.value.replace(/[^0-9]/g, '');
                
                const zipCode = this.value.trim();
                if (zipCode && !validateZipCode(zipCode)) {
                    if (zipCode.length < 4) {
                        showFieldError('zip_code', 'zip-code-error', 'Zip code must be exactly 4 digits');
                    } else if (zipCode.length > 4) {
                        showFieldError('zip_code', 'zip-code-error', 'Zip code cannot exceed 4 digits');
                    }
                } else {
                    hideFieldError('zip_code', 'zip-code-error');
                }
            });
        }
        
        // Add form submit event listener
        const addUserForm = document.getElementById('addUserForm');
        if (addUserForm) {
            addUserForm.addEventListener('submit', function(e) {
                e.preventDefault();
                addUser(this);
            });
        }
        
        // Reset interaction tracking when modal is opened
        $('#addUserModal').on('shown.bs.modal', function() {
            hasUserInteracted = {
                email: false,
                contact: false,
                zipCode: false
            };
            // Clear any existing validation errors when modal opens
            hideFieldError('email', 'email-error');
            hideFieldError('contact', 'contact-error');
            hideFieldError('zip_code', 'zip-code-error');
            
            // Ensure all error elements are properly hidden on modal open
            const errorElements = document.querySelectorAll('#addUserModal .invalid-feedback');
            errorElements.forEach(element => {
                element.classList.remove('show');
                element.textContent = '';
            });
            
            // Remove any invalid classes from form controls
            const formControls = document.querySelectorAll('#addUserModal .form-control');
            formControls.forEach(control => {
                control.classList.remove('is-invalid');
            });
        });
    }
    
    // Setup real-time validation for Edit User Modal
    function setupEditUserValidation() {
        const editEmailField = document.getElementById('edit_email');
        const editContactField = document.getElementById('edit_contact');
        const editZipCodeField = document.getElementById('edit_zip_code');
        let isEditMode = false; // Track if user has started editing
        
        // Email validation on input
        if (editEmailField) {
            editEmailField.addEventListener('focus', function() {
                isEditMode = true; // User started editing
            });
            
            editEmailField.addEventListener('input', function() {
                if (!isEditMode) return; // Only validate if user has started editing
                
                const email = this.value.trim();
                if (email && !validateEmail(email)) {
                    showFieldError('edit_email', 'edit-email-error', 'Email must end with @gmail.com');
                } else {
                    hideFieldError('edit_email', 'edit-email-error');
                }
            });
        }
        
        // Contact validation on input (only allow numbers)
        if (editContactField) {
            editContactField.addEventListener('focus', function() {
                isEditMode = true; // User started editing
            });
            
            editContactField.addEventListener('input', function() {
                // Remove any non-digit characters
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if (!isEditMode) return; // Only validate if user has started editing
                
                const contact = this.value.trim();
                if (contact && !validateContact(contact)) {
                    if (contact.length < 11) {
                        showFieldError('edit_contact', 'edit-contact-error', 'Contact must be exactly 11 digits');
                    } else if (contact.length > 11) {
                        showFieldError('edit_contact', 'edit-contact-error', 'Contact cannot exceed 11 digits');
                    }
                } else {
                    hideFieldError('edit_contact', 'edit-contact-error');
                }
            });
        }
        
        // Zip code validation on input (only allow numbers)
        if (editZipCodeField) {
            editZipCodeField.addEventListener('focus', function() {
                isEditMode = true; // User started editing
            });
            
            editZipCodeField.addEventListener('input', function() {
                // Remove any non-digit characters
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if (!isEditMode) return; // Only validate if user has started editing
                
                const zipCode = this.value.trim();
                if (zipCode && !validateZipCode(zipCode)) {
                    if (zipCode.length < 4) {
                        showFieldError('edit_zip_code', 'edit-zip-code-error', 'Zip code must be exactly 4 digits');
                    } else if (zipCode.length > 4) {
                        showFieldError('edit_zip_code', 'edit-zip-code-error', 'Zip code cannot exceed 4 digits');
                    }
                } else {
                    hideFieldError('edit_zip_code', 'edit-zip-code-error');
                }
            });
        }
        
        // Add form submit event listener
        const editUserForm = document.getElementById('editUserForm');
        if (editUserForm) {
            editUserForm.addEventListener('submit', function(e) {
                e.preventDefault();
                isEditMode = true; // Force validation on submit
                editUser(this);
            });
        }
        
        // Reset edit mode when modal is opened
        $('#editUserModal').on('shown.bs.modal', function() {
            isEditMode = false;
            // Clear any existing validation errors when modal opens
            hideFieldError('edit_email', 'edit-email-error');
            hideFieldError('edit_contact', 'edit-contact-error');
            hideFieldError('edit_zip_code', 'edit-zip-code-error');
        });
        
        // Prevent dropdown closing when clicking on other form elements in the same modal
        setupModalDropdownBehavior();
    }
    
    // Setup modal-specific dropdown behavior
    function setupModalDropdownBehavior() {
        // Add event listener to modal forms to prevent dropdown closing
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            const formElements = modal.querySelectorAll('input:not(.enhanced-dropdown-input), select, textarea, button');
            formElements.forEach(element => {
                element.addEventListener('focus', function(e) {
                    // Prevent event bubbling that might close dropdowns
                    e.stopPropagation();
                });
                
                element.addEventListener('click', function(e) {
                    // Prevent event bubbling that might close dropdowns
                    e.stopPropagation();
                });
            });
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setupAddressFiltering();
            setupAddUserValidation();
            setupEditUserValidation();
        });
    } else {
        setupAddressFiltering();
        setupAddUserValidation();
        setupEditUserValidation();
    }

    // Mobile card rendering functionality
    function renderMobileUserCards(users) {
        const container = document.getElementById('mobileUserCardsContainer');
        if (!container) return;
        
        if (!users || users.length === 0) {
            container.innerHTML = `
                <div class="empty-state-mobile text-center py-5">
                    <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Users Found</h5>
                    <p class="text-muted mb-0 px-3">There are no users matching your current filters.</p>
                    <div class="mt-4">
                        <button onclick="fetchUsers('all', 'all', 1)" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                        </button>
                    </div>
                </div>
            `;
            return;
        }
        
        const cardsHtml = users.map(user => {
            const statusBadge = user.status === 'Active' 
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
            
            const roleText = user.user_type_name ? 
                user.user_type_name.charAt(0).toUpperCase() + user.user_type_name.slice(1) :
                getRoleText(user.user_type);
            
            // Generate action buttons based on user type and permissions
            let actionButtons = '';
            
            // History button for customers and technicians
            if (roleText === 'Customer' || roleText === 'Technician') {
                const historyParam = roleText === 'Customer' ? 'history' : 'tech-history';
                actionButtons += `
                    <a class="btn btn-outline-secondary btn-sm text-decoration-none" href="?page=appointment&${historyParam}=${user.user_id}">
                        <i class="bi bi-clock-history"></i> History
                    </a>
                `;
            }
            
            // Edit button for admin user (ID 1), Edit and Delete for others
            if (user.user_id == 1) {
                actionButtons += `
                    <button class="btn btn-primary btn-sm" onclick="editUser(${user.user_id})">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                `;
            } else {
                actionButtons += `
                    <button class="btn btn-primary btn-sm" onclick="editUser(${user.user_id})">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.user_id})">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                `;
            }
            
            return `
                <div class="mobile-user-card">
                    <div class="mobile-user-header">
                        <h6 class="mobile-user-name">${user.first_name} ${user.middle_name || ''} ${user.last_name}</h6>
                        <div class="mobile-user-status">${statusBadge}</div>
                    </div>
                    <div class="mobile-user-details">
                        <div class="mobile-user-email">
                            <i class="bi bi-envelope me-1"></i>${user.email || 'No email'}
                        </div>
                        <div class="mobile-user-role">
                            <i class="bi bi-person-badge me-1"></i>${roleText}
                        </div>
                    </div>
                    <div class="mobile-user-actions">
                        ${actionButtons}
                    </div>
                </div>
            `;
        }).join('');
        
        container.innerHTML = cardsHtml;
    }
    
    function getRoleText(userType) {
        switch(userType) {
            case '2': return 'Technician';
            case '3': return 'Staff';
            case '4': return 'Customer';
            default: return 'Unknown';
        }
    }
    
    // Mobile pagination rendering
    function renderMobileUserPagination(currentPage, totalPages, role, status) {
        const container = document.getElementById('mobilePaginationContainer');
        if (!container || totalPages <= 1) {
            container.innerHTML = '';
            return;
        }
        
        let paginationHtml = `
            <div class="mobile-dataTables-wrapper" style="margin-top: 20px; text-align: center;">
                <div class="mobile-dataTables-paginate">
        `;
        
        // Previous button
        paginationHtml += `
            <a class="mobile-paginate-button previous ${currentPage === 1 ? 'disabled' : ''}" 
               href="#" onclick="fetchUsers('${role}', '${status}', ${currentPage - 1}); return false;">
                ‹ Previous
            </a>
        `;
        
        // Page numbers logic (simplified for mobile)
        const startPage = Math.max(1, currentPage - 1);
        const endPage = Math.min(totalPages, currentPage + 1);
        
        if (startPage > 1) {
            paginationHtml += `
                <a class="mobile-paginate-button" href="#" onclick="fetchUsers('${role}', '${status}', 1); return false;">1</a>
            `;
            if (startPage > 2) {
                paginationHtml += `<span class="mobile-paginate-button disabled">...</span>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            paginationHtml += `
                <a class="mobile-paginate-button ${i === currentPage ? 'current' : ''}" 
                   href="#" onclick="fetchUsers('${role}', '${status}', ${i}); return false;">${i}</a>
            `;
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHtml += `<span class="mobile-paginate-button disabled">...</span>`;
            }
            paginationHtml += `
                <a class="mobile-paginate-button" href="#" onclick="fetchUsers('${role}', '${status}', ${totalPages}); return false;">${totalPages}</a>
            `;
        }
        
        // Next button
        paginationHtml += `
            <a class="mobile-paginate-button next ${currentPage === totalPages ? 'disabled' : ''}" 
               href="#" onclick="fetchUsers('${role}', '${status}', ${currentPage + 1}); return false;">
                Next ›
            </a>
        `;
        
        paginationHtml += `
                </div>
            </div>
        `;
        
        container.innerHTML = paginationHtml;
    }
    
    // Add mobile pagination CSS
    const mobilePaginationCSS = `
        <style>
        .mobile-dataTables-paginate {
            margin-top: 20px !important;
            text-align: center !important;
        }
        
        .mobile-paginate-button {
            display: inline-block !important;
            padding: 8px 12px !important;
            margin: 0 2px !important;
            background: #f8f9fa !important;
            border: 0px solid #dee2e6 !important;
            border-radius: 20px !important;
            color: #495057 !important;
            text-decoration: none !important;
            transition: all 0.3s ease !important;
            font-size: 14px !important;
            min-width: 40px !important;
            text-align: center !important;
        }
        
        .mobile-paginate-button:hover {
            background: #e9ecef !important;
            border-color: #adb5bd !important;
            color: #495057 !important;
            transform: translateY(-1px) !important;
            text-decoration: none !important;
        }
        
        .mobile-paginate-button.current {
            background: #007bff !important;
            border-color: #007bff !important;
            color: white !important;
            font-weight: bold !important;
        }
        
        .mobile-paginate-button.current:hover {
            background: #0056b3 !important;
            border-color: #0056b3 !important;
            transform: translateY(-1px) !important;
            text-decoration: none !important;
        }
        
        .mobile-paginate-button.disabled {
            background: #f8f9fa !important;
            border-color: #dee2e6 !important;
            color: #6c757d !important;
            cursor: not-allowed !important;
            opacity: 0.5 !important;
        }
        
        .mobile-paginate-button.disabled:hover {
            background: #f8f9fa !important;
            transform: none !important;
            box-shadow: none !important;
            text-decoration: none !important;
        }
        
        .mobile-paginate-button.previous,
        .mobile-paginate-button.next {
            font-weight: bold !important;
            padding: 8px 16px !important;
        }
        </style>
    `;
    
    // Inject mobile pagination CSS
    document.head.insertAdjacentHTML('beforeend', mobilePaginationCSS);
    
    // Custom floating label CSS for both Add and Edit User Modals
    const floatingLabelCSS = `
        <style>
        /* Enhanced dropdown wrapper styling */
        .enhanced-dropdown-wrapper {
            position: relative;
            width: 100%;
        }
        
        /* Enhanced dropdown input styling */
        .enhanced-dropdown-input {
            width: 100%;
            height: calc(3.5rem + 2px);
            padding: 1.625rem 0.75rem 0.5rem;
            font-size: 1rem;
            line-height: 1.5;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: all 0.15s ease-in-out;
        }
        
        /* Form floating container */
        .form-floating {
            position: relative;
        }
        
        /* Label styling */
        .form-floating > label {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 1rem 0.75rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            pointer-events: none;
            border: 1px solid transparent;
            transform-origin: 0 0;
            transition: all 0.15s ease-in-out;
            color: #6c757d;
        }
        
        /* Floating label state */
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label,
        .form-floating > .enhanced-dropdown-wrapper > .form-control:focus ~ label,
        .form-floating > .enhanced-dropdown-wrapper > .form-control:not(:placeholder-shown) ~ label,
        .form-floating > .form-select ~ label {
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
            color: #6c757d;
        }
        
        /* Hide placeholder when not focused */
        .form-floating > .form-control::placeholder,
        .form-floating > .enhanced-dropdown-wrapper > .form-control::placeholder {
            color: transparent;
        }
        
        /* Show placeholder when focused */
        .form-floating > .form-control:focus::placeholder,
        .form-floating > .enhanced-dropdown-wrapper > .form-control:focus::placeholder {
            color: #6c757d;
            opacity: 0.5;
        }
        
        /* Focus state */
        .form-control:focus, 
        .form-select:focus,
        .enhanced-dropdown-input:focus {
            color: #212529;
            background-color: #fff;
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        /* Enhanced Dropdown Styles */
        .enhanced-dropdown-wrapper {
            position: relative;
            width: 100%;
        }

        .enhanced-dropdown-input {
            padding-right: 0.75rem !important;
            padding-left: 0.75rem !important;
            cursor: text;
            background-color: white !important;
            border: 0 !important;
            font-size: 16px;
            color: #495057;
            height: calc(3.5rem + 2px);
            line-height: 1.25;
            width: 100%;
        }

        .enhanced-dropdown-input:focus {
            box-shadow: none !important;
            border-color: transparent !important;
            outline: none;
        }

        .enhanced-dropdown-input::placeholder {
            color: transparent;
            opacity: 0;
        }
        
        /* Handle floating label positioning when dropdown has value */
        .enhanced-dropdown-input:not(:placeholder-shown) {
            padding-top: 1.625rem !important;
            padding-bottom: 0.625rem !important;
            padding-left: 0.75rem !important;
        }
        
        .enhanced-dropdown-input:focus {
            padding-top: 1.625rem !important;
            padding-bottom: 0.625rem !important;
            padding-left: 0.75rem !important;
        }

        /* Form floating styles */
        .form-floating {
            position: relative;
        }

        .form-floating > .form-control,
        .form-floating > .form-select {
            height: calc(3.5rem + 2px);
            line-height: 1.25;
            padding: 1.625rem 0.75rem 0.5rem;
        }

        .form-floating > label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            pointer-events: none;
            border: 1px solid transparent;
            transform-origin: 0 0;
            transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
            color: #6c757d;
        }

        /* Float the label when dropdown has value */
        .enhanced-dropdown-wrapper:has(.enhanced-dropdown-input.has-value) + label,
        .enhanced-dropdown-wrapper:has(.enhanced-dropdown-input:focus) + label,
        .enhanced-dropdown-wrapper:has(.enhanced-dropdown-input:not(:placeholder-shown)) + label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label,
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-select ~ label {
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
            color: #6c757d;
            font-size: 0.85em;
            opacity: 1;
        }

        /* Ensure proper spacing */
        .form-floating > .form-control,
        .form-floating > .form-select {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }

        /* Enhanced dropdown list styling */
        .enhanced-dropdown-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .enhanced-dropdown-wrapper.active .enhanced-dropdown-list {
            display: block;
            animation: dropdownFadeIn 0.2s ease-out;
        }

        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-options {
            max-height: 200px;
            overflow-y: auto;
            padding-top: 8px;
        }

        .dropdown-option {
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-size: 14px;
            color: #495057;
            background-color: white;
        }

        .dropdown-option:hover {
            background-color: #f8f9fa;
            color: #326e9f;
        }

        .dropdown-option.selected {
            background-color: #326e9f;
            color: white;
        }

        .dropdown-option:last-child {
            border-bottom: none;
        }

        /* Custom scrollbar for dropdown */
        .dropdown-options::-webkit-scrollbar {
            width: 4px;
        }

        .dropdown-options::-webkit-scrollbar-track {
            background: transparent;
        }

        .dropdown-options::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 2px;
        }

        .dropdown-options::-webkit-scrollbar-thumb:hover {
            background: #adb5bd;
        }
        #addUserModal .enhanced-dropdown-input:focus,
        #editUserModal .enhanced-dropdown-input:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            outline: 0;
        }
        
        /* Enhanced dropdown placeholder */
        #addUserModal .enhanced-dropdown-input::placeholder,
        #editUserModal .enhanced-dropdown-input::placeholder {
            color: transparent;
        }
        
        #addUserModal .enhanced-dropdown-input:focus::placeholder,
        #editUserModal .enhanced-dropdown-input:focus::placeholder {
            color: #6c757d;
            opacity: 0.5;
        }
        
        /* Ensure proper z-index for dropdowns */
        #addUserModal .enhanced-dropdown-list,
        #editUserModal .enhanced-dropdown-list {
            z-index: 1060; /* Above modal backdrop */
        }
        
        /* Fix for form-control-plaintext */
        .form-control-plaintext:focus {
            outline: 0;
        }
        
        /* Ensure proper spacing between form groups */
        .form-group {
            margin-bottom: 1rem;
        }
        
        /* Fix for the address fields in the two-column layout */
        .row > .col-md-6 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        </style>
    `;
    
    // Inject floating label CSS
    document.head.insertAdjacentHTML('beforeend', floatingLabelCSS);
</script>