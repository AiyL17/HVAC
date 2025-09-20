<style>
/* Fix double scrollbar issue in Edit Profile Modal */
body.modal-open-custom {
    overflow: hidden !important;
    padding-right: 15px !important;
}

html.modal-open-custom {
    overflow: hidden !important;
}

.modal.show {
    padding-right: 0 !important;
}

/* Enhanced Profile Modal Styling */
.profile-avatar img {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.profile-avatar img:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3) !important;
}

/* Enhanced table styling for profile information */
#profileModal .table td {
    padding: 0.75rem 0.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    vertical-align: middle;
}

#profileModal .table td:first-child {
    font-weight: 600;
    color: #6c757d;
    width: 35%;
}

#profileModal .table td:last-child {
    color: #495057;
    font-weight: 500;
}

/* Enhanced Edit Profile Modal Styling */
.modal-header.bg-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    border: none;
    box-shadow: 0 2px 10px rgba(0, 123, 255, 0.2);
}

.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

/* Enhanced form controls */
.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    transform: translateY(-1px);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

/* Enhanced dropdown styling */
.enhanced-dropdown-wrapper {
    position: relative;
}

.dropdown-options {
    border: 1px solid #dee2e6 !important;
    border-radius: 0.5rem !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    background: #ffffff !important;
    z-index: 1050 !important;
    /* Hide scrollbar while keeping scroll functionality */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* Internet Explorer 10+ */
    margin-top: 2px;
}

.dropdown-options::-webkit-scrollbar {
    display: none;
}

.dropdown-option {
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-bottom: none;
    transition: all 0.2s ease;
    font-size: 0.95rem;
}

.dropdown-option:hover {
    background: #f8f9fa;
    color: #495057;
    transform: translateX(4px);
    border-bottom: none !important;
    border: none !important;
}

.dropdown-option:last-child {
    border-bottom: none;
}

/* Enhanced buttons - maintaining original sizes */
.modal .btn {
    transition: all 0.3s ease;
}

.modal .btn-primary {
    background: var(--bs-primary) !important;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
    border: none;
}

.modal .btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.modal .btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
    color: white;
    border: none;
}

.modal .btn-secondary:hover {
    background: linear-gradient(135deg, #545b62 0%, #3d4449 100%);
    transform: translateY(-1px);
}

/* Profile picture preview enhancements */
#profilePicturePreview {
    transition: all 0.3s ease;
    border: 3px solid #007bff !important;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
    display: block !important;
    margin: 0 auto !important;
}

#profilePicturePreview:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
}

/* Ensure profile picture preview container stays centered */
.profile-picture-preview {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}

/* Enhanced modal animations */
.modal.fade .modal-dialog {
    transition: transform 0.4s ease-out, opacity 0.4s ease-out;
    transform: translate(0, -50px) scale(0.95);
}

.modal.show .modal-dialog {
    transform: translate(0, 0) scale(1);
}

/* Enhanced text styling */
.text-primary {
    color: #007bff !important;
    font-weight: 600;
}

.text-muted {
    color: #6c757d !important;
}

/* Enhanced spacing and typography */
h6.text-primary {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    position: relative;
    padding-bottom: 0.5rem;
}

h6.text-primary::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 30px;
    height: 2px;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-radius: 1px;
}

/* Enhanced file input styling */
.form-control[type="file"] {
    padding: 0.5rem;
    border: 2px dashed #007bff;
    background: rgba(0, 123, 255, 0.05);
    border-radius: 8px;
}

.form-control[type="file"]:hover {
    border-color: #0056b3;
    background: rgba(0, 123, 255, 0.1);
}

/* Enhanced form text */
.form-text {
    color: #6c757d;
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

/* Responsive enhancements */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 1rem;
    }
    
    .profile-avatar img {
        width: 150px !important;
        height: 200px !important;
    }
    
    #profilePicturePreview {
        width: 80px !important;
        height: 80px !important;
    }
}

/* Loading state enhancements */
.btn:disabled {
    opacity: 0.7;
    transform: none !important;
    cursor: not-allowed;
}

/* Hide scrollbar completely for dropdown options */
.dropdown-options::-webkit-scrollbar {
    display: none;
}
</style>

<nav class="navbar navbar-expand-md navbar-light bg-nav sticky-top " id="nav">
    <div class="d-flex w-100 align-items-center">
        <div id="opnbtn">
            <button class="btn fw-bold rounded-pill border-0 ms-1 btn-light" id="opnbtn" type="button"
                onclick="toggleNav()">
                <i class="bi  bi-list-nested fw-bold fs-3"></i>
            </button>
        </div>
        <a href="<?php 
            if (isset($userDetails) && !empty($userDetails->user_type)) {
                switch($userDetails->user_type) {
                    case 'administrator':
                        echo 'index.php?page=dashboard';
                        break;
                    case 'customer':
                        echo 'index.php?page=dashboard';
                        break;
                    case 'technician':
                        echo 'index.php?page=dashboard';
                        break;
                    case 'staff':
                        echo 'index.php?page=dashboard';
                        break;
                    default:
                        echo 'index.php';
                }
            } else {
                echo 'index.php';
            }
        ?>" class="navbar-brand me-2">
            <div class="d-flex align-items-center pe-3 rounded-pill">
                <h3 class="fw-bold ms-sm-3 ms-1 m-0 p-0 text-primary">HVAC</h3>

            </div>
        </a>



        <?php
        if (!empty($userDetails->user_name)) { ?>

            <div class="d-flex align-items-center justify-content-end w-100 p-2 ps-3 me-2">
                
                <!-- Notification Icon (Admin, Customer, Technician, and Staff) -->
                <?php if ($userDetails->user_type === 'administrator' || $userDetails->user_type === 'customer' || $userDetails->user_type === 'technician' || $userDetails->user_type === 'staff'): ?>
                <div class="dropdown me-3">
                    <button class="btn btn-light position-relative" type="button" id="notificationDropdown" 
                            data-bs-toggle="dropdown" aria-expanded="false" onclick="markNotificationsAsRead()">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" 
                              id="notification-count" style="display: none;">0</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="notificationDropdown" 
                         style="width: 350px; max-height: 400px; overflow-y: auto; overflow-x: hidden; word-wrap: break-word;">
                        <style>
                        /* Mobile-responsive notification dropdown positioning */
                        @media (max-width: 768px) {
                            .dropdown-menu[aria-labelledby="notificationDropdown"] {
                                position: fixed !important;
                                top: 60px !important;
                                right: 10px !important;
                                left: auto !important;
                                transform: none !important;
                                width: calc(100vw - 20px) !important;
                                max-width: 350px !important;
                                max-height: 70vh !important;
                                margin: 0 !important;
                                z-index: 1050 !important;
                            }
                        }
                        </style>
                        <style>
                        .notification-item {
                            white-space: normal !important;
                            word-wrap: break-word !important;
                            overflow-wrap: break-word !important;
                            height: auto !important;
                            min-height: auto !important;
                            padding: 12px 16px !important;
                        }
                        .notification-item .flex-grow-1 {
                            min-width: 0 !important;
                            width: 100% !important;
                        }
                        .notification-item h6 {
                            word-wrap: break-word !important;
                            overflow-wrap: break-word !important;
                            white-space: normal !important;
                            line-height: 1.3 !important;
                            flex: 1 !important;
                            margin-right: 8px !important;
                        }
                        .notification-item p {
                            word-wrap: break-word !important;
                            overflow-wrap: break-word !important;
                            white-space: normal !important;
                            line-height: 1.4 !important;
                            width: 100% !important;
                            display: block !important;
                        }
                        </style>
                        <div class="dropdown-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Notifications</h6>
                            <button class="btn btn-sm btn-outline-primary" onclick="markAllNotificationsAsRead()">
                                <i class="bi bi-check-all"></i> Mark as Read
                            </button>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div id="notifications-container">
                            <div class="text-center p-3 text-muted">
                                <i class="bi bi-bell-slash fs-4"></i>
                                <p class="mb-0 mt-2">No notifications</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <button
                        class="btn text-dark round_sm list-item rounded-pill border-0 align-items-center d-flex toggle"
                        type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <?php if (!empty($userDetails->user_profile_picture)): ?>
                            <img src="userprofile/<?= $userDetails->user_profile_picture ?>" 
                                 alt="Profile Picture" 
                                 class="rounded-circle me-2" 
                                 style="width: 24px; height: 24px; object-fit: cover;">
                        <?php else: ?>
                            <i class="me-2 bi bi-person-fill"></i>
                        <?php endif; ?>
                        <small>
                            <?= ucfirst($userDetails->user_name); ?>
                        </small>
                    </button>
                    <ul class="dropdown-menu shadow border dropdown-menu-end me-2 round_md"
                        aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item small list-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="bi bi-person-circle me-2"></i>Profile
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item small list-item text-dark" href="logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a></li>
                    </ul>
                </div>

            </div>

        <?php }
        ?>

    </div>

</nav>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="profileModalLabel">
                    <i class="bi bi-person-circle me-2"></i>User Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($userDetails)): ?>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="profile-avatar mb-3">
                            <?php if (!empty($userDetails->user_profile_picture)): ?>
                                <img src="userprofile/<?= $userDetails->user_profile_picture ?>" 
                                     alt="Profile Picture" 
                                     class="box" 
                                     style="width: 200px; height: 330px; object-fit: cover; border: 2px solid #007bff; border-radius: 12px; margin-left: 23px;">
                            <?php else: ?>
                                <i class="bi bi-person-circle text-primary" style="font-size: 7.5rem;"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Personal Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold text-muted">Full Name:</td>
                                <td><?= ucfirst($userDetails->user_name) ?> <?= ucfirst($userDetails->user_midname ?? '') ?> <?= ucfirst($userDetails->user_lastname) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Email:</td>
                                <td><?= $userDetails->user_email ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Phone:</td>
                                <td><?= $userDetails->user_contact ?? 'Not provided' ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">User Type:</td>
                                <td><span ><?= ucfirst($userDetails->user_type) ?></span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Member Since:</td>
                                <td><?= date('F j, Y', strtotime($userDetails->user_created ?? 'now')) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Address:</td>
                                <td>
                                    <?= $userDetails->house_building_street ?? '' ?>
                                    <?= !empty($userDetails->house_building_street) && !empty($userDetails->barangay) ? ', ' : '' ?>
                                    <?= $userDetails->barangay ?? '' ?>
                                    <?= !empty($userDetails->barangay) && !empty($userDetails->municipality_city) ? ', ' : '' ?>
                                    <?= $userDetails->municipality_city ?? '' ?>
                                    <?= !empty($userDetails->municipality_city) && !empty($userDetails->province) ? ', ' : '' ?>
                                    <?= $userDetails->province ?? '' ?>
                                    <?= !empty($userDetails->province) && !empty($userDetails->zip_code) ? ', ' : '' ?>
                                    <?= $userDetails->zip_code ?? '' ?>
                                    <?= empty($userDetails->house_building_street) && empty($userDetails->barangay) && empty($userDetails->municipality_city) && empty($userDetails->province) && empty($userDetails->zip_code) ? 'Not provided' : '' ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Set Availability Section (Full Width for Technicians) -->
                <?php if ($userDetails->user_type === 'technician'): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3"><i class="bi bi-calendar-check me-2"></i>Set Availability</h6>
                        <div class="p-0">
                            <div class="row g-2 mb-3">
                                <div class="col-3">
                                    <label class="form-label fw-bold text-muted mb-1">Date</label>
                                    <input type="date" class="form-control fw-normal" id="availabilityDate">
                                    <div id="dateError" class="invalid-feedback d-none" style="font-size: 0.75rem; color: #dc3545;">
                                        Please select a weekday (Monday to Friday)
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label class="form-label fw-bold text-muted mb-1">Start Time</label>
                                    <select class="form-select" id="availabilityStartTime">
                                        <option value="08:00">8:00 AM</option>
                                        <option value="09:00">9:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="13:00">1:00 PM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="15:00">3:00 PM</option>
                                        <option value="16:00">4:00 PM</option>
                                        <option value="17:00">5:00 PM</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label fw-bold text-muted mb-1">End Time</label>
                                    <select class="form-select" id="availabilityEndTime">
                                        <option value="08:00">8:00 AM</option>
                                        <option value="09:00">9:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="13:00">1:00 PM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="15:00">3:00 PM</option>
                                        <option value="16:00">4:00 PM</option>
                                        <option value="17:00">5:00 PM</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label fw-bold text-muted mb-1">Status</label>
                                    <select class="form-select" id="availabilityStatus">
                                        <option value="available">Available</option>
                                        <option value="unavailable">Unavailable</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-primary" onclick="updateTechnicianAvailability()">
                                <i class="bi bi-save me-2"></i>Save Availability
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php else: ?>
                <div class="text-center">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Profile information not available</h5>
                    <p class="text-muted">Unable to load user profile data.</p>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editProfile()">
                    <i class="bi bi-pencil me-2"></i>Edit Profile
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editProfileModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Edit Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProfileForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Profile Picture Upload Section -->
                    <div class="mb-4 text-center">
                        <h6 class="text-primary mb-3"><i class="bi bi-camera me-2"></i>Profile Picture</h6>
                        <div class="profile-picture-preview mb-3">
                            <img id="profilePicturePreview" 
                                 src="<?= !empty($userDetails->user_profile_picture) ? 'userprofile/' . $userDetails->user_profile_picture : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iNTAiIGN5PSI1MCIgcj0iNTAiIGZpbGw9IiNlOWVjZWYiLz48cGF0aCBkPSJNNTAgMjVjNi45IDAgMTIuNSA1LjYgMTIuNSAxMi41UzU2LjkgNTAgNTAgNTBzLTEyLjUtNS42LTEyLjUtMTIuNVM0My4xIDI1IDUwIDI1em0wIDUwYy0xMy44IDAtMjUtMTEuMi0yNS0yNSAwLTEuNCAwLjEtMi43IDAuMy00SDc0LjdjMC4yIDEuMyAwLjMgMi42IDAuMyA0IDAgMTMuOC0xMS4yIDI1LTI1IDI1eiIgZmlsbD0iIzZjNzU3ZCIvPjwvc3ZnPg==' ?>" 
                                 alt="Profile Picture" 
                                 class="rounded-circle" 
                                 style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #007bff;">
                        </div>
                        <div class="mb-3">
                            <input type="file" class="form-control" id="profilePictureFile" name="profile_picture" 
                                   accept="image/*" onchange="previewProfilePicture(this)">
                            <div class="form-text">Choose a profile picture (JPG, PNG, GIF - Max 5MB)</div>
                        </div>
                    </div>
                    <hr class="mb-4">
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="editFirstName" class="form-label fw-bold">First Name</label>
                                <input type="text" class="form-control" id="editFirstName" name="user_name" value="<?= $userDetails->user_name ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="editMiddleName" class="form-label fw-bold">Middle Name</label>
                                <input type="text" class="form-control" id="editMiddleName" name="user_midname" value="<?= $userDetails->user_midname ?? '' ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label fw-bold">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="user_lastname" value="<?= $userDetails->user_lastname ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="user_email" 
                               value="<?= $userDetails->user_email ?? '' ?>" 
                               pattern=".*@gmail\.com$" 
                               title="Email must end with @gmail.com" 
                               required>
                        <div class="invalid-feedback" id="editEmail-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editPhone" class="form-label fw-bold">Phone Number</label>
                        <input type="tel" class="form-control" id="editPhone" name="user_phone" 
                               value="<?= $userDetails->user_contact ?? '' ?>" 
                               pattern="[0-9]{11}" 
                               title="Contact must be exactly 11 digits" 
                               maxlength="11">
                        <div class="invalid-feedback" id="editPhone-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editHouseBuildingStreet" class="form-label fw-bold">House/Building Number & Street Name</label>
                        <input type="text" class="form-control" id="editHouseBuildingStreet" name="house_building_street" value="<?= $userDetails->house_building_street ?? '' ?>">
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="editProvince" class="form-label fw-bold">Province</label>
                                <div class="enhanced-dropdown-wrapper position-relative">
                                    <input type="text" class="form-control" id="editProvince" name="province" 
                                           value="<?= $userDetails->province ?? '' ?>" 
                                           placeholder="Type or select province" 
                                           autocomplete="off">
                                    <div class="dropdown-options position-absolute w-100 bg-white border rounded shadow-sm" 
                                         id="editProvinceOptions" 
                                         style="max-height: 200px; overflow-y: auto; z-index: 1050; display: none;">
                                        <div class="dropdown-option p-2 border-bottom" data-value="Davao del Norte">Davao del Norte</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="editMunicipalityCity" class="form-label fw-bold">Municipality</label>
                                <div class="enhanced-dropdown-wrapper position-relative">
                                    <input type="text" class="form-control" id="editMunicipalityCity" name="municipality_city" 
                                           value="<?= $userDetails->municipality_city ?? '' ?>" 
                                           placeholder="Type or select municipality" 
                                           autocomplete="off">
                                    <div class="dropdown-options position-absolute w-100 bg-white border rounded shadow-sm" 
                                         id="editMunicipalityOptions" 
                                         style="max-height: 200px; overflow-y: auto; z-index: 1050; display: none;">
                                        <div class="dropdown-option p-2 border-bottom" data-value="Carmen">Carmen</div>
                                        <div class="dropdown-option p-2 border-bottom" data-value="Panabo City">Panabo City</div>
                                        <div class="dropdown-option p-2 border-bottom" data-value="Sto. Tomas">Sto. Tomas</div>
                                        <div class="dropdown-option p-2 border-bottom" data-value="Tagum City">Tagum City</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="mb-2">
                                <label for="editBarangay" class="form-label">Barangay</label>
                                <div class="enhanced-dropdown-wrapper position-relative">
                                    <input type="text" class="form-control" id="editBarangay" name="barangay" 
                                           value="<?= $userDetails->barangay ?? '' ?>" 
                                           placeholder="Type or select barangay" 
                                           autocomplete="off">
                                    <div class="dropdown-options position-absolute w-100 bg-white border rounded shadow-sm" 
                                         id="editBarangayOptions" 
                                         style="max-height: 200px; overflow-y: auto; z-index: 1050; display: none;">
                                        <div class="dropdown-option p-2" data-value="A. O. Floirendo">A. O. Floirendo</div>
                                        <div class="dropdown-option p-2" data-value="Alejal">Alejal</div>
                                        <div class="dropdown-option p-2" data-value="Anibongan">Anibongan</div>
                                        <div class="dropdown-option p-2" data-value="Apokon">Apokon</div>
                                        <div class="dropdown-option p-2" data-value="Asuncion (Cuatro-Cuatro)">Asuncion (Cuatro-Cuatro)</div>
                                        <div class="dropdown-option p-2" data-value="Balagunan">Balagunan</div>
                                        <div class="dropdown-option p-2" data-value="Bincungan">Bincungan</div>
                                        <div class="dropdown-option p-2" data-value="Bobongon">Bobongon</div>
                                        <div class="dropdown-option p-2" data-value="Buenavista">Buenavista</div>
                                        <div class="dropdown-option p-2" data-value="Busaon">Busaon</div>
                                        <div class="dropdown-option p-2" data-value="Cacao">Cacao</div>
                                        <div class="dropdown-option p-2" data-value="Cagangohan">Cagangohan</div>
                                        <div class="dropdown-option p-2" data-value="Canocotan">Canocotan</div>
                                        <div class="dropdown-option p-2" data-value="Casig-ang">Casig-ang</div>
                                        <div class="dropdown-option p-2" data-value="Cebulano">Cebulano</div>
                                        <div class="dropdown-option p-2" data-value="Consolacion">Consolacion</div>
                                        <div class="dropdown-option p-2" data-value="Cuambogan">Cuambogan</div>
                                        <div class="dropdown-option p-2" data-value="Dapco">Dapco</div>
                                        <div class="dropdown-option p-2" data-value="Datu Abdul Dadia">Datu Abdul Dadia</div>
                                        <div class="dropdown-option p-2" data-value="Esperanza">Esperanza</div>
                                        <div class="dropdown-option p-2" data-value="Gredu">Gredu</div>
                                        <div class="dropdown-option p-2" data-value="Guadalupe">Guadalupe</div>
                                        <div class="dropdown-option p-2" data-value="Ising">Ising</div>
                                        <div class="dropdown-option p-2" data-value="J.P. Laurel">J.P. Laurel</div>
                                        <div class="dropdown-option p-2" data-value="Kasilak">Kasilak</div>
                                        <div class="dropdown-option p-2" data-value="Katipunan">Katipunan</div>
                                        <div class="dropdown-option p-2" data-value="Katualan">Katualan</div>
                                        <div class="dropdown-option p-2" data-value="Kauswagan">Kauswagan</div>
                                        <div class="dropdown-option p-2" data-value="Kimamon">Kimamon</div>
                                        <div class="dropdown-option p-2" data-value="Kinamayan">Kinamayan</div>
                                        <div class="dropdown-option p-2" data-value="Kiotoy">Kiotoy</div>
                                        <div class="dropdown-option p-2" data-value="La Filipina">La Filipina</div>
                                        <div class="dropdown-option p-2" data-value="La Libertad">La Libertad</div>
                                        <div class="dropdown-option p-2" data-value="La Paz">La Paz</div>
                                        <div class="dropdown-option p-2" data-value="Liboganon">Liboganon</div>
                                        <div class="dropdown-option p-2" data-value="Little Panay">Little Panay</div>
                                        <div class="dropdown-option p-2" data-value="Lower Panaga (Roxas)">Lower Panaga (Roxas)</div>
                                        <div class="dropdown-option p-2" data-value="Lunga-og">Lunga-og</div>
                                        <div class="dropdown-option p-2" data-value="Mabaus">Mabaus</div>
                                        <div class="dropdown-option p-2" data-value="Mabuhay">Mabuhay</div>
                                        <div class="dropdown-option p-2" data-value="Mabunao">Mabunao</div>
                                        <div class="dropdown-option p-2" data-value="Madaum">Madaum</div>
                                        <div class="dropdown-option p-2" data-value="Magsaysay">Magsaysay</div>
                                        <div class="dropdown-option p-2" data-value="Magugpo Central">Magugpo Central</div>
                                        <div class="dropdown-option p-2" data-value="Magugpo East">Magugpo East</div>
                                        <div class="dropdown-option p-2" data-value="Magugpo North">Magugpo North</div>
                                        <div class="dropdown-option p-2" data-value="Magugpo Poblacion">Magugpo Poblacion</div>
                                        <div class="dropdown-option p-2" data-value="Magugpo South">Magugpo South</div>
                                        <div class="dropdown-option p-2" data-value="Magugpo West">Magugpo West</div>
                                        <div class="dropdown-option p-2" data-value="Magwawa">Magwawa</div>
                                        <div class="dropdown-option p-2" data-value="Malativas">Malativas</div>
                                        <div class="dropdown-option p-2" data-value="Manay">Manay</div>
                                        <div class="dropdown-option p-2" data-value="Mangalcal">Mangalcal</div>
                                        <div class="dropdown-option p-2" data-value="Mankilam">Mankilam</div>
                                        <div class="dropdown-option p-2" data-value="Minda">Minda</div>
                                        <div class="dropdown-option p-2" data-value="Nanyo">Nanyo</div>
                                        <div class="dropdown-option p-2" data-value="New Balamban">New Balamban</div>
                                        <div class="dropdown-option p-2" data-value="New Camiling">New Camiling</div>
                                        <div class="dropdown-option p-2" data-value="New Katipunan">New Katipunan</div>
                                        <div class="dropdown-option p-2" data-value="New Malaga">New Malaga</div>
                                        <div class="dropdown-option p-2" data-value="New Malitbog">New Malitbog</div>
                                        <div class="dropdown-option p-2" data-value="New Pandan">New Pandan</div>
                                        <div class="dropdown-option p-2" data-value="New Visayas">New Visayas</div>
                                        <div class="dropdown-option p-2" data-value="Nueva Fuerza">Nueva Fuerza</div>
                                        <div class="dropdown-option p-2" data-value="Pagsabangan">Pagsabangan</div>
                                        <div class="dropdown-option p-2" data-value="Pandapan">Pandapan</div>
                                        <div class="dropdown-option p-2" data-value="Pantaron">Pantaron</div>
                                        <div class="dropdown-option p-2" data-value="Quezon">Quezon</div>
                                        <div class="dropdown-option p-2" data-value="Salvacion">Salvacion</div>
                                        <div class="dropdown-option p-2" data-value="San Agustin">San Agustin</div>
                                        <div class="dropdown-option p-2" data-value="San Francisco">San Francisco</div>
                                        <div class="dropdown-option p-2" data-value="San Isidro">San Isidro</div>
                                        <div class="dropdown-option p-2" data-value="San Jose">San Jose</div>
                                        <div class="dropdown-option p-2" data-value="San Miguel">San Miguel</div>
                                        <div class="dropdown-option p-2" data-value="San Nicolas">San Nicolas</div>
                                        <div class="dropdown-option p-2" data-value="San Pedro">San Pedro</div>
                                        <div class="dropdown-option p-2" data-value="San Roque">San Roque</div>
                                        <div class="dropdown-option p-2" data-value="San Vicente">San Vicente</div>
                                        <div class="dropdown-option p-2" data-value="Santa Cruz">Santa Cruz</div>
                                        <div class="dropdown-option p-2" data-value="Santo Niño">Santo Niño</div>
                                        <div class="dropdown-option p-2" data-value="Sindaton">Sindaton</div>
                                        <div class="dropdown-option p-2" data-value="Southern Davao">Southern Davao</div>
                                        <div class="dropdown-option p-2" data-value="Sto. Niño">Sto. Niño</div>
                                        <div class="dropdown-option p-2" data-value="Taba">Taba</div>
                                        <div class="dropdown-option p-2" data-value="Tagpore">Tagpore</div>
                                        <div class="dropdown-option p-2" data-value="Talomo">Talomo</div>
                                        <div class="dropdown-option p-2" data-value="Tibal-og">Tibal-og</div>
                                        <div class="dropdown-option p-2" data-value="Tibulao">Tibulao</div>
                                        <div class="dropdown-option p-2" data-value="Tibungol">Tibungol</div>
                                        <div class="dropdown-option p-2" data-value="Tubod">Tubod</div>
                                        <div class="dropdown-option p-2" data-value="Tuganay">Tuganay</div>
                                        <div class="dropdown-option p-2" data-value="Tulalian">Tulalian</div>
                                        <div class="dropdown-option p-2" data-value="Upper Licanan">Upper Licanan</div>
                                        <div class="dropdown-option p-2" data-value="Visayan Village">Visayan Village</div>
                                        <div class="dropdown-option p-2" data-value="Waterfall">Waterfall</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="mb-2">
                                <label for="editZipCode" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" id="editZipCode" name="zip_code" 
                                       value="<?= $userDetails->zip_code ?? '' ?>" 
                                       pattern="[0-9]{4}" 
                                       title="Please enter exactly 4 digits" 
                                       maxlength="4">
                                <div class="invalid-feedback" id="editZipCode-error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="editPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="editPassword" name="new_password" placeholder="Leave blank to keep current password">
                                <div class="form-text">Leave blank if you don't want to change your password</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    function handleScroll() {
        var navbar = document.getElementById("nav");

        var sidebar = document.getElementById("mySidebar");

        if (window.scrollY > 0) {
            navbar.classList.add("border-bottom");


        } else {

            navbar.classList.remove("border-bottom");
        }
    }
    window.addEventListener("scroll", handleScroll);

    // Profile modal functions
    function editProfile() {
        // Close the profile modal
        var profileModal = bootstrap.Modal.getInstance(document.getElementById('profileModal'));
        if (profileModal) {
            profileModal.hide();
        }
        
        // Open the edit profile modal
        var editModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
        editModal.show();
    }

    // Handle edit profile form submission
    document.addEventListener('DOMContentLoaded', function() {
        const editForm = document.getElementById('editProfileForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate password confirmation
                const newPassword = document.getElementById('editPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                
                if (newPassword && newPassword !== confirmPassword) {
                    if (typeof showToast === 'function') {
                        showToast('Passwords do not match!', 'danger');
                    } else {
                        alert('Passwords do not match!');
                    }
                    return;
                }
                
                // Collect form data
                const formData = new FormData(editForm);
                
                // Show loading state
                const submitBtn = editForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Saving...';
                submitBtn.disabled = true;
                
                // Send AJAX request to update profile
                fetch('api/update_profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close edit modal
                        var editModal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                        if (editModal) {
                            editModal.hide();
                        }
                        
                        // Update the profile modal with new data
                        if (data.data) {
                            console.log('Profile update response data:', data.data);
                            updateProfileModalContent(data.data);
                        } else {
                            console.log('No data.data in response, refreshing page to get updated profile');
                            // If no updated data is returned, refresh to get current profile info
                            window.location.reload();
                        }
                        
                        // Show success message
                        if (typeof showToast === 'function') {
                            showToast('Profile updated successfully!', 'success');
                        } else {
                            alert('Profile updated successfully!');
                        }
                        
                        // No need to refresh page anymore since we update dynamically
                    } else {
                        if (typeof showToast === 'function') {
                            showToast(data.message || 'Failed to update profile', 'danger');
                        } else {
                            alert(data.message || 'Failed to update profile');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof showToast === 'function') {
                        showToast('An error occurred while updating profile', 'danger');
                    } else {
                        alert('An error occurred while updating profile');
                    }
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
    });

    // Function to update the User Profile modal content with new data
    function updateProfileModalContent(userData) {
        console.log('updateProfileModalContent called with:', userData);
        console.log('Profile picture in userData:', userData.user_profile_picture);
        
        // Update the user name in the profile modal
        const profileUserName = document.querySelector('#profileModal h5.text-primary');
        if (profileUserName) {
            profileUserName.textContent = userData.user_name.charAt(0).toUpperCase() + userData.user_name.slice(1);
        }
        
        // Update profile picture in both modals if uploaded
        if (userData.user_profile_picture && userData.user_profile_picture.trim() !== '') {
            console.log('Updating profile picture to:', userData.user_profile_picture);
            const profileAvatar = document.querySelector('#profileModal .profile-avatar i');
            if (profileAvatar) {
                // Replace icon with actual image
                profileAvatar.outerHTML = `<img src="userprofile/${userData.user_profile_picture}" alt="Profile Picture" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #007bff;">`;
            }
            
            // Update edit modal preview
            const editPreview = document.getElementById('profilePicturePreview');
            if (editPreview) {
                editPreview.src = `userprofile/${userData.user_profile_picture}`;
            }
            
            // **UPDATE NAVBAR PROFILE PICTURE** - This is the key addition
            updateNavbarProfilePicture(userData.user_profile_picture);
        } else {
            console.log('No profile picture found, keeping existing navbar image');
            // Don't update navbar if no profile picture data - preserve existing image
            // Only update if we're certain there should be no image
        }
        
        // Update the full name in the table
        const fullNameCell = document.querySelector('#profileModal table tr:nth-child(1) td:nth-child(2)');
        if (fullNameCell) {
            const fullName = [
                userData.user_name.charAt(0).toUpperCase() + userData.user_name.slice(1),
                userData.user_midname ? userData.user_midname.charAt(0).toUpperCase() + userData.user_midname.slice(1) : '',
                userData.user_lastname.charAt(0).toUpperCase() + userData.user_lastname.slice(1)
            ].filter(name => name.trim() !== '').join(' ');
            fullNameCell.textContent = fullName;
        }
        
        // Update the email in the table
        const emailCell = document.querySelector('#profileModal table tr:nth-child(2) td:nth-child(2)');
        if (emailCell) {
            emailCell.textContent = userData.user_email;
        }
        
        // Update the phone in the table
        const phoneCell = document.querySelector('#profileModal table tr:nth-child(3) td:nth-child(2)');
        if (phoneCell) {
            phoneCell.textContent = userData.user_contact || 'Not provided';
        }
        
        // Update the address in the table
        // Construct address from dissected fields
        const addressCell = document.querySelector('#profileModal table tr:nth-child(6) td:nth-child(2)');
        if (addressCell) {
            const addressParts = [];
            if (userData.house_building_street && userData.house_building_street.trim() !== '') 
                addressParts.push(userData.house_building_street);
            if (userData.barangay && userData.barangay.trim() !== '') 
                addressParts.push(userData.barangay);
            if (userData.municipality_city && userData.municipality_city.trim() !== '') 
                addressParts.push(userData.municipality_city);
            if (userData.province && userData.province.trim() !== '') 
                addressParts.push(userData.province);
            if (userData.zip_code && userData.zip_code.trim() !== '') 
                addressParts.push(userData.zip_code);
            
            const fullAddress = addressParts.join(', ');
            addressCell.textContent = fullAddress || 'Not provided';
        }
        
        // Update the dropdown button text (user name in navbar)
        const dropdownButton = document.querySelector('#dropdownMenuButton small');
        if (dropdownButton) {
            dropdownButton.textContent = userData.user_name.charAt(0).toUpperCase() + userData.user_name.slice(1);
        }
        
        // Update the dropdown selections in the edit modal with new values
        const editBarangaySelect = document.getElementById('editBarangay');
        if (editBarangaySelect && userData.barangay) {
            editBarangaySelect.value = userData.barangay;
        }
        
        const editMunicipalitySelect = document.getElementById('editMunicipalityCity');
        if (editMunicipalitySelect && userData.municipality_city) {
            editMunicipalitySelect.value = userData.municipality_city;
        }
        
        const editProvinceSelect = document.getElementById('editProvince');
        if (editProvinceSelect && userData.province) {
            editProvinceSelect.value = userData.province;
        }
    }

    // Function to dynamically update navbar profile picture
    function updateNavbarProfilePicture(profilePicturePath) {
        const dropdownButton = document.getElementById('dropdownMenuButton');
        if (!dropdownButton) return;
        
        // Find the current profile picture or icon in the navbar
        const currentImg = dropdownButton.querySelector('img');
        const currentIcon = dropdownButton.querySelector('i.bi-person-fill');
        
        if (profilePicturePath) {
            // User has uploaded a profile picture
            if (currentImg) {
                // Update existing image source with cache-busting timestamp
                currentImg.src = `userprofile/${profilePicturePath}?t=${Date.now()}`;
            } else if (currentIcon) {
                // Replace icon with new image
                currentIcon.outerHTML = `<img src="userprofile/${profilePicturePath}?t=${Date.now()}" alt="Profile Picture" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">`;
            }
        } else {
            // No profile picture - revert to default icon
            if (currentImg) {
                // Replace image with default icon
                currentImg.outerHTML = `<i class="me-2 bi bi-person-fill"></i>`;
            }
            // If currentIcon exists, it's already the default, no change needed
        }
    }

    // Function to preview profile picture before upload
    function previewProfilePicture(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validate file size (5MB limit)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                input.value = ''; // Clear the file input
                return;
            }
            
            // Validate file type (images only)
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Only JPG, PNG, and GIF files are allowed');
                input.value = ''; // Clear the file input
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Update preview in edit modal
                const preview = document.getElementById('profilePicturePreview');
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    
                    // Hide the default icon if shown
                    const defaultIcon = preview.nextElementSibling;
                    if (defaultIcon && defaultIcon.classList.contains('bi-person-circle')) {
                        defaultIcon.style.display = 'none';
                    }
                }
                
                // Also update the profile modal preview if open
                const profileAvatar = document.querySelector('#profileModal .profile-avatar img');
                if (profileAvatar) {
                    profileAvatar.src = e.target.result;
                }
            }
            
            reader.readAsDataURL(file);
        }
    }

    // Date and time validation for technician availability
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('availabilityDate');
        const startTimeInput = document.getElementById('availabilityStartTime');
        const endTimeInput = document.getElementById('availabilityEndTime');
        
        // Set minimum date to today
        if (dateInput) {
            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            const yyyy = today.getFullYear();
            const todayFormatted = `${yyyy}-${mm}-${dd}`;
            
            dateInput.setAttribute('min', todayFormatted);
            
            // Disable weekends and past dates
            dateInput.addEventListener('input', function() {
                const selectedDate = new Date(this.value);
                const day = selectedDate.getDay();
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                const dateError = document.getElementById('dateError');
                
                // Reset error state
                this.classList.remove('is-invalid');
                if (dateError) dateError.classList.add('d-none');
                
                // Check if it's a weekend (0 = Sunday, 6 = Saturday)
                if (day === 0 || day === 6) {
                    this.value = '';
                    this.classList.add('is-invalid');
                    if (dateError) {
                        dateError.textContent = 'Please select a weekday (Monday to Friday)';
                        dateError.classList.remove('d-none');
                    }
                    return;
                }
                
                // Check if date is in the past
                if (selectedDate < today) {
                    this.value = '';
                    this.classList.add('is-invalid');
                    if (dateError) {
                        dateError.textContent = 'Please select today or a future date';
                        dateError.classList.remove('d-none');
                    }
                    return;
                }
            });
        }
        
        // Function to validate time (8:00-12:00 or 13:00-17:00)
        function validateTime(input, isStartTime = true) {
            if (!input) return;
            
            const time = input.value;
            if (!time) return;
            
            const [hours, minutes] = time.split(':').map(Number);
            const totalMinutes = hours * 60 + minutes;
            
            // Check if time is within allowed slots
            const isInFirstSlot = (totalMinutes >= 8 * 60) && (totalMinutes <= 12 * 60);
            const isInSecondSlot = (totalMinutes >= 13 * 60) && (totalMinutes <= 17 * 60);
            
            if (!isInFirstSlot && !isInSecondSlot) {
                // If invalid time, reset to default based on input type
                input.value = isStartTime ? '08:00' : '17:00';
                alert('Please select a time between 8:00 AM - 12:00 PM or 1:00 PM - 5:00 PM');
            }
            
            // If this is the end time, make sure it's after start time
            if (!isStartTime && startTimeInput && startTimeInput.value) {
                const [startHours, startMins] = startTimeInput.value.split(':').map(Number);
                const startTotalMins = startHours * 60 + startMins;
                
                if (totalMinutes <= startTotalMins) {
                    input.value = '17:00';
                    alert('End time must be after start time');
                }
            }
        }
        
        // Add event listeners for time validation
        if (startTimeInput) {
            startTimeInput.addEventListener('change', function() {
                validateTime(this, true);
                // If end time is before new start time, update it
                if (endTimeInput && endTimeInput.value) {
                    const [startHours, startMins] = this.value.split(':').map(Number);
                    const [endHours, endMins] = endTimeInput.value.split(':').map(Number);
                    
                    const startTotal = startHours * 60 + startMins;
                    const endTotal = endHours * 60 + endMins;
                    
                    if (endTotal <= startTotal) {
                        // Set end time to 5:00 PM if it's before or same as start time
                        endTimeInput.value = '17:00';
                    }
                }
            });
        }
        
        if (endTimeInput) {
            endTimeInput.addEventListener('change', function() {
                validateTime(this, false);
            });
        }
        
        // Load existing availability data when modal opens
        loadTechnicianAvailability();
    });
    
    // Function to load existing technician availability data
    function loadTechnicianAvailability() {
        if (typeof fetch === 'undefined') {
            console.log('Fetch API not available');
            return;
        }
        
        fetch('api/technician/get_availability.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const availabilityData = data.data;
                    
                    // Populate form fields with existing data
                    if (availabilityData.date) {
                        const dateInput = document.getElementById('availabilityDate');
                        if (dateInput) dateInput.value = availabilityData.date;
                    }
                    
                    if (availabilityData.start_time) {
                        const startTimeInput = document.getElementById('availabilityStartTime');
                        if (startTimeInput) startTimeInput.value = availabilityData.start_time;
                    }
                    
                    if (availabilityData.end_time) {
                        const endTimeInput = document.getElementById('availabilityEndTime');
                        if (endTimeInput) endTimeInput.value = availabilityData.end_time;
                    }
                    
                    if (availabilityData.status) {
                        const statusInput = document.getElementById('availabilityStatus');
                        if (statusInput) statusInput.value = availabilityData.status;
                    }
                }
            })
            .catch(error => {
                console.log('Error loading availability data:', error);
            });
    }
    
    // Function to update technician availability
    function updateTechnicianAvailability() {
        // Get form values
        const dateInput = document.getElementById('availabilityDate');
        const startTimeInput = document.getElementById('availabilityStartTime');
        const endTimeInput = document.getElementById('availabilityEndTime');
        const statusInput = document.getElementById('availabilityStatus');
        
        if (!dateInput || !startTimeInput || !endTimeInput || !statusInput) {
            alert('Form elements not found');
            return;
        }
        
        const date = dateInput.value;
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        const status = statusInput.value;
        
        // Validate required fields
        if (!date) {
            alert('Please select a date');
            dateInput.focus();
            return;
        }
        
        if (!startTime) {
            alert('Please select a start time');
            startTimeInput.focus();
            return;
        }
        
        if (!endTime) {
            alert('Please select an end time');
            endTimeInput.focus();
            return;
        }
        
        // Validate that end time is after start time
        if (startTime >= endTime) {
            alert('End time must be after start time');
            endTimeInput.focus();
            return;
        }
        
        // Show loading state
        const saveButton = event.target;
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Saving...';
        saveButton.disabled = true;
        
        // Prepare data for API
        const availabilityData = {
            date: date,
            start_time: startTime,
            end_time: endTime,
            status: status
        };
        
        // Send data to API
        fetch('api/technician/save_availability.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(availabilityData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Availability saved successfully!');
                
                // Optionally close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('profileModal'));
                if (modal) {
                    modal.hide();
                }
            } else {
                alert('Error: ' + (data.message || 'Failed to save availability'));
            }
        })
        .catch(error => {
            console.error('Error saving availability:', error);
            alert('An error occurred while saving availability. Please try again.');
        })
        .finally(() => {
            // Restore button state
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        });
    }

    // Enhanced dropdown functionality for Edit Profile Modal
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize custom dropdowns
        initCustomDropdown('editBarangay', 'editBarangayOptions');
        initCustomDropdown('editMunicipalityCity', 'editMunicipalityOptions');
        initCustomDropdown('editProvince', 'editProvinceOptions');
        
        // Initialize address interdependence for Edit Profile Modal
        initEditProfileAddressLogic();
    });

    // Address data structure for Edit Profile Modal
    const editAddressData = {
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

    function initEditProfileAddressLogic() {
        // Add event listeners for interdependent address logic
        const editBarangay = document.getElementById('editBarangay');
        const editMunicipality = document.getElementById('editMunicipalityCity');
        const editProvince = document.getElementById('editProvince');
        const editZipCode = document.getElementById('editZipCode');

        if (editBarangay) {
            editBarangay.addEventListener('change', onEditBarangayChange);
            editBarangay.addEventListener('input', debounce(onEditBarangayChange, 300));
        }
        
        if (editMunicipality) {
            editMunicipality.addEventListener('change', onEditMunicipalityChange);
            editMunicipality.addEventListener('input', debounce(onEditMunicipalityChange, 300));
        }
        
        if (editProvince) {
            editProvince.addEventListener('change', onEditProvinceChange);
            editProvince.addEventListener('input', debounce(onEditProvinceChange, 300));
        }
    }

    // Debounce function for input events
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function initCustomDropdown(inputId, optionsId) {
        const input = document.getElementById(inputId);
        const optionsContainer = document.getElementById(optionsId);
        
        if (!input || !optionsContainer) return;
        
        // Show dropdown on focus
        input.addEventListener('focus', function() {
            showDropdownOptions(optionsContainer);
        });
        
        // Filter options as user types
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const options = optionsContainer.querySelectorAll('.dropdown-option');
            
            let hasVisibleOptions = false;
            options.forEach(option => {
                const text = option.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    option.style.display = 'block';
                    hasVisibleOptions = true;
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Show/hide dropdown based on whether there are visible options
            if (hasVisibleOptions && searchTerm.length > 0) {
                showDropdownOptions(optionsContainer);
            } else if (searchTerm.length === 0) {
                showDropdownOptions(optionsContainer);
            } else {
                hideDropdownOptions(optionsContainer);
            }
        });
        
        // Handle option selection
        optionsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('dropdown-option')) {
                const value = e.target.getAttribute('data-value');
                input.value = value;
                hideDropdownOptions(optionsContainer);
                input.focus();
                
                // Trigger change event to activate interdependent logic for all address fields
                const changeEvent = new Event('change', { bubbles: true });
                input.dispatchEvent(changeEvent);
                
                // Also trigger input event for immediate feedback
                const inputEvent = new Event('input', { bubbles: true });
                input.dispatchEvent(inputEvent);
            }
        });
        
        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !optionsContainer.contains(e.target)) {
                hideDropdownOptions(optionsContainer);
            }
        });
        
        // Hide dropdown on blur (with small delay to allow option clicks)
        input.addEventListener('blur', function() {
            setTimeout(() => {
                hideDropdownOptions(optionsContainer);
            }, 200);
        });
    }

    function showDropdownOptions(optionsContainer) {
        optionsContainer.style.display = 'block';
    }

    function hideDropdownOptions(optionsContainer) {
        optionsContainer.style.display = 'none';
    }

    // Fix double scrollbar issue and prevent duplicate field rendering
    document.addEventListener('DOMContentLoaded', function() {
        const editProfileModal = document.getElementById('editProfileModal');
        
        if (editProfileModal) {
            editProfileModal.addEventListener('show.bs.modal', function() {
                // Hide body scrollbar when modal opens
                document.body.classList.add('modal-open-custom');
                document.documentElement.style.overflow = 'hidden';
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = '15px'; // Prevent layout shift
                
                // Clear any potential duplicate elements
                clearDuplicateFields();
            });
            
            editProfileModal.addEventListener('hidden.bs.modal', function() {
                // Restore body scrollbar when modal closes
                document.body.classList.remove('modal-open-custom');
                document.documentElement.style.overflow = '';
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });
        }
    });

    // Function to clear any duplicate field elements
    function clearDuplicateFields() {
        const modalBody = document.querySelector('#editProfileModal .modal-body');
        if (modalBody) {
            // Remove any duplicate province/municipality/barangay sections
            const duplicateRows = modalBody.querySelectorAll('.row');
            const addressRows = [];
            
            duplicateRows.forEach(row => {
                const hasAddressFields = row.querySelector('#editProvince, #editMunicipalityCity, #editBarangay');
                if (hasAddressFields) {
                    addressRows.push(row);
                }
            });
            
            // If there are more than 2 address rows (Province/Municipality row + Barangay/Zip row), remove extras
            if (addressRows.length > 2) {
                for (let i = 2; i < addressRows.length; i++) {
                    addressRows[i].remove();
                }
            }
        }
    }

    // Edit Profile Modal Address Interdependence Functions
    function onEditBarangayChange() {
        if (window.editAddressUpdating) return;
        window.editAddressUpdating = true;
        
        const barangayInput = document.getElementById('editBarangay');
        const municipalityInput = document.getElementById('editMunicipalityCity');
        const provinceInput = document.getElementById('editProvince');
        const zipCodeInput = document.getElementById('editZipCode');
        const selectedBarangay = barangayInput.value;
        
        console.log('Edit Profile - Barangay changed to:', selectedBarangay);
        
        if (selectedBarangay) {
            let found = false;
            // Find the city and province for this barangay
            Object.keys(editAddressData).forEach(province => {
                Object.keys(editAddressData[province].barangays).forEach(city => {
                    if (editAddressData[province].barangays[city].includes(selectedBarangay)) {
                        console.log('Found barangay', selectedBarangay, 'in city:', city, 'province:', province);
                        
                        // Auto-fill municipality and province
                        municipalityInput.value = city;
                        provinceInput.value = province;
                        
                        // Auto-fill zip code
                        if (editAddressData[province].zipCodes[city]) {
                            zipCodeInput.value = editAddressData[province].zipCodes[city];
                            console.log('Auto-filled zip code:', editAddressData[province].zipCodes[city]);
                        }
                        
                        // Update dropdown options
                        updateEditMunicipalityOptions(province);
                        updateEditBarangayOptions(city, province);
                        
                        found = true;
                    }
                });
            });
            
            if (!found) {
                console.log('Barangay not found in predefined data (custom entry):', selectedBarangay);
            }
        }
        
        window.editAddressUpdating = false;
    }

    function onEditMunicipalityChange() {
        if (window.editAddressUpdating) return;
        window.editAddressUpdating = true;
        
        const municipalityInput = document.getElementById('editMunicipalityCity');
        const provinceInput = document.getElementById('editProvince');
        const zipCodeInput = document.getElementById('editZipCode');
        const selectedMunicipality = municipalityInput.value;
        
        if (selectedMunicipality) {
            // Find the province for this municipality
            Object.keys(editAddressData).forEach(province => {
                if (editAddressData[province].cities.includes(selectedMunicipality)) {
                    // Auto-fill province
                    provinceInput.value = province;
                    
                    // Auto-fill zip code
                    if (editAddressData[province].zipCodes[selectedMunicipality]) {
                        zipCodeInput.value = editAddressData[province].zipCodes[selectedMunicipality];
                    }
                    
                    // Update barangay options to show only barangays in this municipality
                    updateEditBarangayOptions(selectedMunicipality, province);
                }
            });
        }
        
        window.editAddressUpdating = false;
    }

    function onEditProvinceChange() {
        if (window.editAddressUpdating) return;
        window.editAddressUpdating = true;
        
        const provinceInput = document.getElementById('editProvince');
        const selectedProvince = provinceInput.value;
        
        if (selectedProvince && editAddressData[selectedProvince]) {
            // Update municipality options to show only municipalities in this province
            updateEditMunicipalityOptions(selectedProvince);
            
            // Update barangay options to show all barangays in this province
            const provinceBarangays = [];
            Object.keys(editAddressData[selectedProvince].barangays).forEach(city => {
                editAddressData[selectedProvince].barangays[city].forEach(barangay => {
                    if (!provinceBarangays.includes(barangay)) {
                        provinceBarangays.push(barangay);
                    }
                });
            });
            updateEditBarangayOptions(null, selectedProvince, provinceBarangays);
        }
        
        window.editAddressUpdating = false;
    }

    function updateEditMunicipalityOptions(province) {
        const optionsContainer = document.getElementById('editMunicipalityOptions');
        if (!optionsContainer || !editAddressData[province]) return;
        
        // Clear existing options
        optionsContainer.innerHTML = '';
        
        // Add municipalities for this province
        editAddressData[province].cities.forEach(city => {
            const option = document.createElement('div');
            option.className = 'dropdown-option p-2 border-bottom';
            option.setAttribute('data-value', city);
            option.textContent = city;
            optionsContainer.appendChild(option);
        });
    }

    function updateEditBarangayOptions(city, province, customBarangays = null) {
        const optionsContainer = document.getElementById('editBarangayOptions');
        if (!optionsContainer) return;
        
        // Clear existing options
        optionsContainer.innerHTML = '';
        
        let barangays = [];
        if (customBarangays) {
            barangays = customBarangays;
        } else if (city && province && editAddressData[province] && editAddressData[province].barangays[city]) {
            barangays = editAddressData[province].barangays[city];
        } else if (province && editAddressData[province]) {
            // Show all barangays for the province
            Object.keys(editAddressData[province].barangays).forEach(cityKey => {
                barangays = barangays.concat(editAddressData[province].barangays[cityKey]);
            });
            barangays = [...new Set(barangays)]; // Remove duplicates
        } else {
            // Show all barangays
            Object.keys(editAddressData).forEach(prov => {
                Object.keys(editAddressData[prov].barangays).forEach(cityKey => {
                    barangays = barangays.concat(editAddressData[prov].barangays[cityKey]);
                });
            });
            barangays = [...new Set(barangays)]; // Remove duplicates
        }
        
        // Add barangays to dropdown
        barangays.sort().forEach(barangay => {
            const option = document.createElement('div');
            option.className = 'dropdown-option p-2 border-bottom';
            option.setAttribute('data-value', barangay);
            option.textContent = barangay;
            optionsContainer.appendChild(option);
        });
    }

    // Validation functions (matching admin user.php)
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

    // Setup real-time validation for Edit Profile Modal
    function setupEditProfileValidation() {
        const emailField = document.getElementById('editEmail');
        const phoneField = document.getElementById('editPhone');
        const zipCodeField = document.getElementById('editZipCode');
        let hasUserInteracted = {
            email: false,
            phone: false,
            zipCode: false
        };
        
        // Email validation on input
        if (emailField) {
            emailField.addEventListener('input', function() {
                hasUserInteracted.email = true; // Mark as user has started typing
                
                const email = this.value.trim();
                if (email && !validateEmail(email)) {
                    showFieldError('editEmail', 'editEmail-error', 'Email must end with @gmail.com');
                } else {
                    hideFieldError('editEmail', 'editEmail-error');
                }
            });
        }
        
        // Phone validation on input (only allow numbers)
        if (phoneField) {
            phoneField.addEventListener('input', function() {
                hasUserInteracted.phone = true; // Mark as user has started typing
                
                // Remove any non-digit characters
                this.value = this.value.replace(/[^0-9]/g, '');
                
                const phone = this.value.trim();
                if (phone && !validateContact(phone)) {
                    if (phone.length < 11) {
                        showFieldError('editPhone', 'editPhone-error', 'Contact must be exactly 11 digits');
                    } else if (phone.length > 11) {
                        showFieldError('editPhone', 'editPhone-error', 'Contact cannot exceed 11 digits');
                    }
                } else {
                    hideFieldError('editPhone', 'editPhone-error');
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
                        showFieldError('editZipCode', 'editZipCode-error', 'Zip code must be exactly 4 digits');
                    } else if (zipCode.length > 4) {
                        showFieldError('editZipCode', 'editZipCode-error', 'Zip code cannot exceed 4 digits');
                    }
                } else {
                    hideFieldError('editZipCode', 'editZipCode-error');
                }
            });
        }
        
        // Reset interaction tracking when modal is opened
        $('#editProfileModal').on('shown.bs.modal', function() {
            hasUserInteracted = {
                email: false,
                phone: false,
                zipCode: false
            };
            // Clear any existing validation errors when modal opens
            hideFieldError('editEmail', 'editEmail-error');
            hideFieldError('editPhone', 'editPhone-error');
            hideFieldError('editZipCode', 'editZipCode-error');
            
            // Ensure all error elements are properly hidden on modal open
            const errorElements = document.querySelectorAll('#editProfileModal .invalid-feedback');
            errorElements.forEach(element => {
                element.classList.remove('show');
                element.textContent = '';
            });
            
            // Remove any invalid classes from form controls
            const formControls = document.querySelectorAll('#editProfileModal .form-control');
            formControls.forEach(control => {
                control.classList.remove('is-invalid');
            });
        });
    }

    // Initialize validation when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        setupEditProfileValidation();
    });
</script>