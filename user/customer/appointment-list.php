<script src="../../js/ToastUtils.js"></script>
<style>
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
        padding: 20px;
    }

    /* Filter container styling */
    .filter-container {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
        align-items: center;
    }

    /* Mobile Cards - Hidden on desktop */
    .mobile-cards {
        display: none;
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

    /* Mobile Card Styles */
    .appointment-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 16px;
        padding: 16px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .appointment-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
    }

    .appointment-id {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .appointment-status .badge {
        font-size: 0.75rem;
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
    }

    .appointment-card-actions {
        margin-top: 12px;
        padding-top: 8px;
        border-top: 1px solid #eee;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .appointment-card-actions .btn {
        font-size: 0.8rem;
        padding: 6px 12px;
        flex: 1;
        min-width: 80px;
    }

    @media (max-width: 480px) {
        .appointment-card {
            padding: 12px;
            margin-bottom: 12px;
        }
        
        .appointment-card-actions .btn {
            font-size: 0.75rem;
            padding: 5px 10px;
        }
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

    /* Filter form responsive adjustments */
    .filter-form .row {
        align-items: center;
    }

    .filter-form .input-group {
        max-width: 100% !important;
    }

    .rating {
        --size: 20px;
        --star-color: #FFD700;
        /* Yellow color for stars */
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
        /* Remove outline */
        -webkit-appearance: none;
        appearance: none;
        cursor: pointer;
        margin: 20px 0;
        background: transparent;
        /* Ensure no background on the slider itself */
    }

    /* Chrome and Safari */
    .rating::-webkit-slider-runnable-track {
        height: 100%;
        mask: var(--mask);
        -webkit-mask-composite: source-in;
        /* Fix for gaps between stars */
        mask-composite: intersect;
        background: var(--bg);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        box-shadow: none;
        border: none;
    }

    .rating::-webkit-slider-thumb {
        opacity: 0;
    }

    /* Firefox */
    .rating::-moz-range-track {
        height: 100%;
        mask: var(--mask);
        mask-composite: add;
        /* Fix for Firefox */
        background: var(--bg);
        print-color-adjust: exact;
        box-shadow: none;
        border: none;
    }

    .rating::-moz-range-thumb {
        opacity: 0;
    }

    /* Container for demo */
    .rating-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
</style>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Appointments</h4>
    <a href="#" id="new-appointment-btn" class="btn btn-primary px-3">
        <i class="bi bi-plus-lg me-2"></i>New Appointment
    </a>
</div>

<!-- Filter Section -->
<div class="dashboard-card mb-4">
    <div class="dashboard-card-header">
        <i class="bi bi-funnel me-2"></i>Filter Appointments
    </div>
    <div class="dashboard-card-body py-3">
        <div class="filter-form">
            <div class="row g-3 align-items-end">
                <div class="col-6 col-md-4">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="all" selected>All Statuses</option>
                        <option value="1">Approved</option>
                        <option value="10">Cancelled</option>
                        <option value="3">Completed</option>
                        <option value="4">Declined</option>
                        <option value="5">In Progress</option>
                        <option value="2">Pending</option>
                    </select>
                </div>
                <div class="col-6 col-md-4">
                    <label class="form-label small text-muted mb-1">Payment Status</label>
                    <select class="form-select" id="paymentStatusFilter">
                        <option value="all" selected>All Payments</option>
                        <option value="Paid">Paid</option>
                        <option value="Unpaid">Unpaid</option>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small text-muted mb-1 fw-bold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="serviceSearchFilter" placeholder="Search by service type..." style="font-weight: normal;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <style>
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
        
        .custom-scroll::-webkit-scrollbar {
            height: 15px;
            padding: 10px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background-color: rgba(0, 0, 0, .05);
            border-radius: 10px;
            height: 5px;
            margin: 50px;
            border: 5px solid transparent;
            background-clip: padding-box;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            height: 5px;
            background-clip: padding-box;
            border-radius: 10px;
            background-color: rgba(var(--bg-primary-rgb), .5);
            border: 5px solid transparent;
            background-clip: padding-box;
            transition: border 0.3s ease, background-color 0.3s ease;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background-color: rgba(var(--bg-primary-rgb), 1);
            border: 0;
        }

        .custom-scroll::-webkit-scrollbar-thumb:active {
            background-color: rgba(var(--bg-primary-rgb), 1);
            border: 0;
        }
        
        /* Scrollable table styles for appointments */
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
        
        /* Custom scrollbar styling for appointment table */
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




<!-- Appointments Table -->
<div class="dashboard-card">
    <div class="table-container">
        <div class="table-responsive desktop-table">
            <table class="table table-hover" id="appointments-table">
                <thead>
                    <tr>
                        <th>Service Type</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="appointments-tbody">
                    <!-- Appointments will be loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (Hidden on Desktop) -->
        <div class="mobile-cards">
            <div id="mobile-appointments-container">
                <!-- Mobile appointment cards will be loaded here -->
            </div>
        </div>
    </div>
</div>        
            <!-- Loading State -->
            <div id="loading-state" class="text-center p-5" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Loading appointments...</p>
            </div>
            
            <div id="no-appointments" class="text-center p-5" style="display: none;">
                <div class="empty-state">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">No Appointments Found</h5>
                    <p class="text-muted mb-0">You don't have any appointments matching the selected status.</p>
                </div>
            </div>
            
        <!-- Pagination Controls Bottom -->
        <div id="pagination-controls" class="d-flex justify-content-center align-items-center mt-3" style="display: none !important; margin-bottom: 20px;">
            <nav aria-label="Appointments pagination">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item" id="prev-page" style="margin-right: 8px;">
                        <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" aria-label="Previous">
                            <span aria-hidden="true">&lt;</span>
                        </a>
                    </li>
                    <div id="page-numbers" class="d-flex">
                        <!-- Page numbers will be inserted here -->
                    </div>
                    <li class="page-item" id="next-page" style="margin-left: 8px;">
                        <a class="page-link text-dark rounded-pill border-0 p-2 px-3" href="#" aria-label="Next">
                            <span aria-hidden="true">&gt;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
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
                    <label class="text-muted small"><i class="bi bi-hash me-2"></i>Appointment ID</label>
                    <div id="modalAppointmentId" class="fs-6"></div>
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
                    <div id="modalApplianceType" class="fs-6"></div>
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
        <div class="mt-3" id="giveFeedbackSection" style="display: none;">
            <h6 class="text-primary"><i class="bi bi-star-fill me-2"></i>Give Your Feedback</h6>
            <div class="p-3 bg-light rounded border">
                <form id="modalFeedbackForm">
                    <input type="hidden" id="modalFeedbackAppId" name="app_id">
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-star me-2"></i>Rating</label>
                        <div class="rating-stars">
                            <span class="star" data-rating="1">★</span>
                            <span class="star" data-rating="2">★</span>
                            <span class="star" data-rating="3">★</span>
                            <span class="star" data-rating="4">★</span>
                            <span class="star" data-rating="5">★</span>
                        </div>
                        <input type="hidden" id="modalRatingInput" name="rating" required>
                    </div>
                    <div class="mb-3">
                        <label for="modalCommentInput" class="form-label"><i class="bi bi-chat-text me-2"></i>Comment</label>
                        <textarea class="form-control" id="modalCommentInput" name="comment" rows="3" placeholder="Share your experience..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-2"></i>Submit Feedback
                    </button>
                </form>
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content round_lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="feedbackModalLabel"><i class="bi bi-star-fill me-2"></i>Give Feedback</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="feedbackForm">
          <input type="hidden" id="feedbackAppId" name="app_id">
          <div class="mb-3">
            <label class="form-label"><i class="bi bi-star me-2"></i>Rating</label>
            <div class="rating-stars">
              <span class="star" data-rating="1">★</span>
              <span class="star" data-rating="2">★</span>
              <span class="star" data-rating="3">★</span>
              <span class="star" data-rating="4">★</span>
              <span class="star" data-rating="5">★</span>
            </div>
            <input type="hidden" id="selectedRating" name="rating" required>
          </div>
          <div class="mb-3">
            <label for="feedbackComment" class="form-label"><i class="bi bi-chat-text me-2"></i>Comment</label>
            <textarea class="form-control" id="feedbackComment" name="comment" rows="4" placeholder="Share your experience with our service..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitFeedback">Submit Feedback</button>
      </div>
    </div>
  </div>
</div>

<style>
.rating-stars {
  font-size: 2rem;
  color: #ddd;
  cursor: pointer;
}
.rating-stars .star {
  transition: color 0.2s;
}
.rating-stars .star:hover,
.rating-stars .star.active {
  color: #ffc107;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to get URL parameters
        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            const results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        // Set page title
        const page = getUrlParameter('page') || 'appointment';
        // Page title removed since we now have a fixed header

        // Set default type if not present
        const currentType = getUrlParameter('type') || 'all';
        
        // Pagination state
        let currentPage = 1;
        let perPage = 10;
        let totalPages = 1;
        let totalRecords = 0;

        // Set active status filter
        const statusFilter = document.getElementById('statusFilter');
        statusFilter.value = currentType;

        // Add change event listener for status filter
        statusFilter.addEventListener('change', function() {
            currentPage = 1; // Reset to first page when changing filter
            const paymentStatus = document.getElementById('paymentStatusFilter').value;
            const serviceSearch = document.getElementById('serviceSearchFilter').value;
            loadAppointmentsByType(this.value, currentPage, perPage, serviceSearch, paymentStatus);

            // Update URL without page reload
            const url = new URL(window.location);
            url.searchParams.set('type', this.value);
            window.history.pushState({}, '', url);
        });

        // Add change event listener for payment status filter
        const paymentStatusFilter = document.getElementById('paymentStatusFilter');
        paymentStatusFilter.addEventListener('change', function() {
            currentPage = 1; // Reset to first page when changing filter
            const serviceSearch = document.getElementById('serviceSearchFilter').value;
            loadAppointmentsByType(statusFilter.value, currentPage, perPage, serviceSearch, this.value);
        });

        // Add service search functionality
        const serviceSearchFilter = document.getElementById('serviceSearchFilter');
        let searchTimeout;
        
        serviceSearchFilter.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1; // Reset to first page when searching
                const paymentStatus = document.getElementById('paymentStatusFilter').value;
                loadAppointmentsByType(statusFilter.value, currentPage, perPage, this.value, paymentStatus);
            }, 500); // Debounce search by 500ms
        });

        // New appointment button
        document.getElementById('new-appointment-btn').addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = `?page=appointment&action=new`;
        });

        // Load appointments for the current type
        loadAppointmentsByType(currentType, 1, perPage);
        

        // Helper functions for pagination
        function showLoadingState() {
            // Removed loading state - content displays instantly
        }
        
        function showError(message) {
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('appointments-table').style.display = 'none';
            document.getElementById('no-appointments').style.display = 'block';
            document.getElementById('no-appointments').innerHTML = `<p class="text-danger">${message}</p>`;
            document.getElementById('pagination-controls').style.display = 'none';
        }
        
        function updatePaginationInfo(pagination) {
            // Function kept for compatibility but no longer displays info
        }
        
        function renderPaginationControls() {
            const paginationControls = document.getElementById('pagination-controls');
            const pageNumbers = document.getElementById('page-numbers');
            const prevPage = document.getElementById('prev-page');
            const nextPage = document.getElementById('next-page');
            
            // Clear existing page numbers
            pageNumbers.innerHTML = '';
            
            // Hide pagination if only one page or no records
            if (totalPages <= 1) {
                paginationControls.style.display = 'none';
                return;
            }
            
            paginationControls.style.display = 'flex';
            
            // Update prev/next button states
            if (currentPage <= 1) {
                prevPage.classList.add('disabled');
            } else {
                prevPage.classList.remove('disabled');
            }
            
            if (currentPage >= totalPages) {
                nextPage.classList.add('disabled');
            } else {
                nextPage.classList.remove('disabled');
            }
            
            // Generate page numbers (show max 5 pages)
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            // Adjust start if we're near the end
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }
            
            // Add first page and ellipsis if needed
            if (startPage > 1) {
                addPageNumber(1);
                if (startPage > 2) {
                    addEllipsis();
                }
            }
            
            // Add page numbers
            for (let i = startPage; i <= endPage; i++) {
                addPageNumber(i);
            }
            
            // Add ellipsis and last page if needed
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    addEllipsis();
                }
                addPageNumber(totalPages);
            }
            
            // Add event listeners for prev/next
            prevPage.querySelector('a').onclick = function(e) {
                e.preventDefault();
                if (currentPage > 1) {
                    const statusFilter = document.getElementById('statusFilter').value;
                    const serviceSearch = document.getElementById('serviceSearchFilter').value;
                    const paymentStatus = document.getElementById('paymentStatusFilter').value;
                    loadAppointmentsByType(statusFilter, currentPage - 1, perPage, serviceSearch, paymentStatus);
                }
            };
            
            nextPage.querySelector('a').onclick = function(e) {
                e.preventDefault();
                if (currentPage < totalPages) {
                    const statusFilter = document.getElementById('statusFilter').value;
                    const serviceSearch = document.getElementById('serviceSearchFilter').value;
                    const paymentStatus = document.getElementById('paymentStatusFilter').value;
                    loadAppointmentsByType(statusFilter, currentPage + 1, perPage, serviceSearch, paymentStatus);
                }
            };
        }
        
        function addPageNumber(pageNum) {
            const pageNumbers = document.getElementById('page-numbers');
            const li = document.createElement('li');
            li.className = `page-item ${pageNum === currentPage ? 'active' : ''}`;
            li.style.marginRight = '8px'; // Add spacing between pages
            
            // Apply rounded pill styling with proper colors
            const linkClass = pageNum === currentPage ? 
                'page-link text-light rounded-pill border-0 p-2 px-3' : 
                'page-link text-dark rounded-pill border-0 p-2 px-3';
            
            li.innerHTML = `<a class="${linkClass}" href="#">${pageNum}</a>`;
            
            li.querySelector('a').onclick = function(e) {
                e.preventDefault();
                if (pageNum !== currentPage) {
                    const statusFilter = document.getElementById('statusFilter').value;
                    const serviceSearch = document.getElementById('serviceSearchFilter').value;
                    const paymentStatus = document.getElementById('paymentStatusFilter').value;
                    loadAppointmentsByType(statusFilter, pageNum, perPage, serviceSearch, paymentStatus);
                }
            };
            
            pageNumbers.appendChild(li);
        }
        
        function addEllipsis() {
            const pageNumbers = document.getElementById('page-numbers');
            const li = document.createElement('li');
            li.className = 'page-item disabled';
            li.style.marginRight = '8px'; // Add spacing consistent with page numbers
            li.innerHTML = '<span class="page-link border-0 bg-transparent p-2 px-3">...</span>';
            pageNumbers.appendChild(li);
        }

        // Function to load appointments by type with pagination
        function loadAppointmentsByType(type, page = 1, limit = 10, serviceSearch = '', paymentStatus = '') {
            // If type is 6 (To Rate), redirect to type 3 (Completed)
            if (type === '6') {
                type = '3';

                // Update URL without page reload
                const url = new URL(window.location);
                url.searchParams.set('type', type);
                window.history.pushState({}, '', url);

                // Update active tab
                document.querySelectorAll('.appointment-tab').forEach(tab => {
                    tab.classList.remove('bg-primary', 'text-light', 'fw-semibold', 'active');
                    tab.classList.add('text-dark');
                    if (tab.dataset.type === type) {
                        tab.classList.remove('text-dark');
                        tab.classList.add('bg-primary', 'text-light', 'fw-semibold', 'active');
                    }
                });
            }
            
            // Build URL with optional service search and payment status parameters
            let url = `api/customer/get_app.php?type=${type}&page=${page}&limit=${limit}`;
            if (serviceSearch && serviceSearch.trim() !== '') {
                url += `&service_search=${encodeURIComponent(serviceSearch.trim())}`;
            }
            if (paymentStatus && paymentStatus !== 'all') {
                url += `&payment_status=${encodeURIComponent(paymentStatus)}`;
            }
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentPage = data.pagination.current_page;
                        perPage = data.pagination.per_page;
                        totalPages = data.pagination.total_pages;
                        totalRecords = data.pagination.total_records;
                        
                        renderAppointments(data.appointments);
                        updatePaginationInfo(data.pagination);
                        renderPaginationControls();
                    } else {
                        showError(data.message || 'Failed to load appointments');
                    }
                })
                .catch(error => {
                    console.error('Error fetching appointments:', error);
                    showError('Failed to load appointments. Please try again later.');
                });
        }

        // Function to render appointments in table format
        function renderAppointments(appointments) {
            const tbody = document.getElementById('appointments-tbody');
            const table = document.getElementById('appointments-table');
            const noAppointments = document.getElementById('no-appointments');
            
            tbody.innerHTML = '';
            document.getElementById('mobile-appointments-container').innerHTML = '';
            
            if (!appointments || appointments.length === 0) {
                table.style.display = 'none';
                noAppointments.style.display = 'block';
                noAppointments.innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mt-3">No Appointments Found</h5>
                        <p class="text-muted mb-0">You don't have any appointments matching the selected status.</p>
                    </div>
                `;
                return;
            }
            
            table.style.display = 'table';
            noAppointments.style.display = 'none';
            
            // Define status classes based on status name (matching admin format)
            function getStatusClass(statusName) {
                switch (statusName) {
                    case 'Approved':
                        return 'badge bg-primary';
                    case 'Pending':
                        return 'badge bg-secondary';
                    case 'Completed':
                        return 'badge bg-success';
                    case 'Cancelled':
                        return 'badge bg-danger';
                    case 'In Progress':
                        return 'badge bg-warning';
                    case 'Declined':
                        return 'badge bg-danger';
                    default:
                        return 'badge bg-danger';
                }
            }
            
            function getPaymentStatusClass(paymentStatus) {
                return paymentStatus === 'Paid' ? 
                    'badge bg-success' : 
                    'badge bg-danger';
            }
            
            function generateActionButtons(app) {
                if (app.app_status_name === "Pending") {
                    return `
                        <a href="?page=appointment&action=edit&id=${app.app_id}" class="btn btn-sm btn-primary me-1">
                            <i class="bi bi-pencil-fill"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-danger cancel-btn" data-app-id="${app.app_id}">
                            <i class="bi bi-x-circle-fill"></i> Cancel
                        </button>
                    `;
                } else if (app.app_status_name === "Completed") {
                    return `
                        <button type="button" class="btn btn-sm btn-primary view-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#viewAppointmentModal"
                                data-app-id="${app.app_id}"
                                data-date="${app.app_schedule ? new Date(app.app_schedule).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : ''}"
                                data-time="${app.app_schedule ? new Date(app.app_schedule).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : ''}"
                                data-service-type="${app.service_type_name || ''}"
                                data-appliance-type="${app.appliances_type_name || 'Not Specified'}"
                                data-technician="${app.tech_name ? app.tech_name + ' ' + (app.tech_lastname || '') : 'Not assigned'}"
                                data-technician2="${app.tech2_name ? app.tech2_name + ' ' + (app.tech2_lastname || '') : ''}"
                                data-status-name="${app.app_status_name || ''}"
                                data-payment-status="${app.payment_status || 'Unpaid'}"
                                data-description="${app.app_desc || ''}"
                                data-decline-justification="${app.decline_justification || ''}"
                                data-app-price="${app.app_price || ''}"
                                data-app-justification="${app.app_justification || ''}"
                                data-app-rating="${app.app_rating || '0'}"
                                data-app-comment="${app.app_comment || ''}"
                                data-app-created="${app.app_created || ''}"
                                data-customer-house-building-street="${app.customer_house_building_street || ''}"
                                data-customer-barangay="${app.customer_barangay || ''}"
                                data-customer-municipality-city="${app.customer_municipality_city || ''}"
                                data-customer-province="${app.customer_province || ''}"
                                data-customer-zip-code="${app.customer_zip_code || ''}">
                            <i class="bi bi-eye"></i> View
                        </button>
                    `;
                } else {
                    return `
                        <button type="button" class="btn btn-sm btn-primary view-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#viewAppointmentModal"
                            data-app-id="${app.app_id}"
                            data-date="${app.app_schedule ? new Date(app.app_schedule).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : ''}"
                            data-time="${app.app_schedule ? new Date(app.app_schedule).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : ''}"
                            data-service-type="${app.service_type_name || ''}"
                            data-appliance-type="${app.appliances_type_name || 'Not Specified'}"
                            data-technician="${app.tech_name ? app.tech_name + ' ' + (app.tech_lastname || '') : 'Not assigned'}"
                            data-technician2="${app.tech2_name ? app.tech2_name + ' ' + (app.tech2_lastname || '') : ''}"
                            data-status-name="${app.app_status_name || ''}"
                            data-payment-status="${app.payment_status || 'Unpaid'}"
                            data-description="${app.app_desc || ''}"
                            data-decline-justification="${app.decline_justification || ''}"
                            data-app-price="${app.app_price || ''}"
                            data-app-justification="${app.app_justification || ''}"
                            data-app-created="${app.app_created || ''}"
                            data-customer-house-building-street="${app.customer_house_building_street || ''}"
                            data-customer-barangay="${app.customer_barangay || ''}"
                            data-customer-municipality-city="${app.customer_municipality_city || ''}"
                            data-customer-province="${app.customer_province || ''}"
                            data-customer-zip-code="${app.customer_zip_code || ''}">
                        <i class="bi bi-eye"></i> View
                    </button>
                    `;
                }
            }

            appointments.forEach(app => {
                // Format date and time (matching admin format)
                const schedule = new Date(app.app_schedule);
                const formattedDateTime = schedule.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) + ' at ' + schedule.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
                
                const statusClass = getStatusClass(app.app_status_name);
                const paymentStatusClass = getPaymentStatusClass(app.payment_status);
                const actionButtons = generateActionButtons(app);
                
                // Create table row for desktop
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${app.service_type_name}</td>
                    <td>${formattedDateTime}</td>
                    <td><span class="${statusClass}">${app.app_status_name}</span></td>
                    <td><span class="${paymentStatusClass}">${app.payment_status || 'Unpaid'}</span></td>
                    <td class="text-center">${actionButtons}</td>
                `;
                tbody.appendChild(row);

                // Create mobile card
                const mobileCard = document.createElement('div');
                mobileCard.className = 'appointment-card';
                mobileCard.innerHTML = `
                    <div class="appointment-card-header">
                        <div class="appointment-id text-primary"><span class="text-decoration-underline">ID</span> - ${app.app_id}</div>
                        <div class="appointment-status">
                            <span class="${statusClass}">${app.app_status_name}</span>
                        </div>
                    </div>
                    
                    <div class="appointment-info-row">
                        <i class="bi bi-tools"></i>
                        <div class="text-truncate">${app.service_type_name}</div>
                    </div>
                    
                    <div class="appointment-info-row">
                        <i class="bi bi-calendar-event"></i>
                        <div class="text-truncate">${formattedDateTime}</div>
                    </div>
                    
                    <div class="appointment-info-row">
                        <i class="bi bi-credit-card"></i>
                        <div class="text-truncate">
                            <span class="${paymentStatusClass}">${app.payment_status || 'Unpaid'}</span>
                        </div>
                    </div>
                    
                    <div class="appointment-info-row">
                        <i class="bi bi-person-badge"></i>
                        <div class="text-truncate">
                            ${app.tech_name ? (app.tech_name + ' ' + (app.tech_lastname || '')).trim() : 'Not assigned'}
                        </div>
                    </div>
                    
                    ${app.tech2_name ? `
                    <div class="appointment-info-row">
                        <i class="bi bi-person-plus"></i>
                        <div class="text-truncate">
                            ${(app.tech2_name + ' ' + (app.tech2_lastname || '')).trim()}
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="appointment-card-actions">
                        ${actionButtons}
                    </div>
                `;
                
                document.getElementById('mobile-appointments-container').appendChild(mobileCard);
            });
            
            // Add event listeners after rendering
            addEventListeners();
        }

        function renderAppointmentActions(app) {
            if (app.app_status_name === "Approval") {
                return `
                <div class="d-flex justify-content-end gap-2 pt-3">
                    <a href="?page=appointment&action=edit&id=${app.app_id}" class="btn px-3 rounded-pill btn-primary fw-semibold border-0">
                        <small><i class="bi bi-pencil-fill me-2 small"></i>Edit</small>
                    </a>
                </div>
            `;
            } else if (app.app_status_name === "Completed") {
                // Check if app_comment exists in the database
                if (app.app_comment && app.app_comment.trim() !== '') {
                    // Display existing feedback and rating
                    return `
                    <div class="mt-2 p-0 bg-light border round_md" style="margin:0px">
                        ${app.app_comment != "No Comment" ? ` <span name="comment" placeholder="Comment" class="form-control px-3 pt-2 pb-0 round_md border-0 bg-light" readonly>${app.app_comment || 'No comment provided'}</span>` : ``}
                        <div class="d-flex align-items-center justify-content-end gap-2 p-2">
                            <div class="w-100 d-flex align-items-center">
                                <input type="range" name="rating" min="0.0" max="5.0" step="0.5" value="${app.app_rating || 0}" class="rating p-0 m-0"
                                    style="--val:${app.app_rating || 0}"
                                    oninput="this.style.setProperty('--val', this.value); this.parentNode.querySelector('.rating-value').textContent = parseFloat(this.value).toFixed(1);" disabled>
                                <span class="rating-value px-2 small">${parseFloat(app.app_rating || 0).toFixed(1)}</span>
                            </div>
                        </div>
                    </div>
                `;
                } else {
                    // Show feedback form if app_comment is empty
                    return `
                    <form class="feedback-form" data-app-id="${app.app_id}">
                        <input type="hidden" name="app_id" value="${app.app_id}">
                        <div class="mt-2 p-0 bg-light border round_md" style="margin:0px">
                            <textarea name="comment" placeholder="Comment" class="form-control p-3 round_md border-0 bg-light"></textarea>
                            <div class="d-flex align-items-center justify-content-end gap-2 p-2">
                                <div class="w-100 d-flex align-items-center">
                                    <input type="range" name="rating" min="0.0" max="5.0" step="0.5" value="2.5" class="rating p-0 m-0"
                                        style="--val:2.5"
                                        oninput="this.style.setProperty('--val', this.value); this.parentNode.querySelector('.rating-value').textContent = parseFloat(this.value).toFixed(1);">
                                    <span class="rating-value px-2 small">2.5</span>
                                    <div class="d-flex w-100 justify-content-end">
                                        <button type="submit" class="btn px-3 mt-2 rounded-pill btn-primary fw-semibold border-0 submit-feedback-btn">
                                            <small>Send</small>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                `;
                }
            }
            return ''; // No actions for other statuses
        }

        // Function to add event listeners to dynamic elements
        function addEventListeners() {
            // Add event listeners for feedback forms
            document.querySelectorAll('.feedback-form').forEach(function (form) {
                const appId = form.dataset.appId;

                // Submit feedback event
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const commentTextarea = this.querySelector('textarea[name="comment"]');
                    const ratingInput = this.querySelector('input[name="rating"]');
                    const comment = commentTextarea.value;
                    const rating = ratingInput.value;

                    submitFeedback(appId, comment, rating, form);
                });
            });

            // Add event listeners for cancel buttons
            document.querySelectorAll('.cancel-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const appId = this.dataset.appId;
                    cancelAppointment(appId, this.closest('.col-sm-6'));
                });
            });
        }

        // Function to submit feedback
        function submitFeedback(appId, comment, rating, form) {
            showDialog({
                title: 'Confirmation',
                message: 'Are you sure you want to <span class="fw-bold">Send</span> this feedback and rating?.',
                confirmText: 'Send',
                cancelText: 'Cancel',
                onConfirm: function () {
                    fetch('api/customer/app_feedback.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ app_id: appId, comment: (comment == "") ? "No Comment" : comment, rating: rating })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                successToast('Feedback submitted successfully');
                                const statusFilter = document.getElementById('statusFilter').value;
                                const serviceSearch = document.getElementById('serviceSearchFilter').value;
                                const paymentStatus = document.getElementById('paymentStatusFilter').value;
                                loadAppointmentsByType(statusFilter, currentPage, perPage, serviceSearch, paymentStatus);
                            } else {
                                dangerToast('Failed to submit feedback: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting feedback:', error);
                            dangerToast('An error occurred while submitting feedback');
                        });
                }
            });
        }

        // Function to cancel appointment
        function cancelAppointment(appId, appointmentElement) {
            showDialog({
                title: 'Confirmation',
                message: 'Are you sure you want to <span class="fw-bold">Cancel</span> this appointment?.',
                confirmText: 'Yes',
                cancelText: 'No',
                onConfirm: function () {
                    fetch('api/customer/app_cancel.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ app_id: appId })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                successToast('Appointment cancelled successfully');
                                const statusFilter = document.getElementById('statusFilter').value;
                                const serviceSearch = document.getElementById('serviceSearchFilter').value;
                                const paymentStatus = document.getElementById('paymentStatusFilter').value;
                                loadAppointmentsByType(statusFilter, currentPage, perPage, serviceSearch, paymentStatus);
                            } else {
                                dangerToast('Failed to cancel appointment: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Error cancelling appointment:', error);
                            dangerToast('An error occurred while cancelling the appointment');
                        });
                }
            });
        }
    });

    // Event listener for View button to populate modal
    document.addEventListener('click', function(e) {
        if (e.target.closest('.view-btn')) {
            const button = e.target.closest('.view-btn');
            
            // Populate modal with appointment details
            document.getElementById('modalAppointmentId').textContent = button.dataset.appId || 'N/A';
            document.getElementById('modalDate').textContent = button.dataset.date || 'Not set';
            document.getElementById('modalTime').textContent = button.dataset.time || 'Not set';
            document.getElementById('modalServiceType').textContent = button.dataset.serviceType || 'Not specified';
            document.getElementById('modalApplianceType').textContent = button.dataset.applianceType || 'Not Specified';
            document.getElementById('modalTechnician').textContent = button.dataset.technician || 'Not assigned';
            
            // Handle second technician display
            const secondTechnicianSection = document.getElementById('secondTechnicianSection');
            const technician2 = button.dataset.technician2;
            
            if (technician2 && technician2.trim() !== '' && technician2.trim() !== 'undefined') {
                document.getElementById('modalTechnician2').textContent = technician2;
                secondTechnicianSection.style.display = 'block';
            } else {
                secondTechnicianSection.style.display = 'none';
            }
            
            // Combine and display customer address
            const houseBuildingStreet = button.dataset.customerHouseBuildingStreet || '';
            const barangay = button.dataset.customerBarangay || '';
            const municipalityCity = button.dataset.customerMunicipalityCity || '';
            const province = button.dataset.customerProvince || '';
            const zipCode = button.dataset.customerZipCode || '';
            
            // Create address array and filter out empty values
            const addressParts = [houseBuildingStreet, barangay, municipalityCity, province, zipCode].filter(part => part.trim() !== '');
            
            // Combine address parts with commas
            const fullAddress = addressParts.join(', ');
            
            // Display the combined address or a message if no address is available
            document.getElementById('modalCustomerAddress').textContent = fullAddress || 'No address provided';
            
            document.getElementById('modalStatus').textContent = button.dataset.statusName || 'Unknown';
            document.getElementById('modalPaymentStatus').textContent = button.dataset.paymentStatus || 'Unpaid';
            document.getElementById('modalDescription').textContent = button.dataset.description || 'No description provided';
            
            // Set appointment creation date
            const createdDate = button.dataset.appCreated;
            if (createdDate) {
                const createdDateTime = new Date(createdDate);
                const formattedCreatedDate = createdDateTime.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) + ' at ' + createdDateTime.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
                document.getElementById('modalCreatedDate').textContent = formattedCreatedDate;
            } else {
                document.getElementById('modalCreatedDate').textContent = 'Not available';
            }
            
            // Handle decline justification
            const declineSection = document.getElementById('declineJustificationSection');
            const declineJustification = button.dataset.declineJustification;
            if (declineJustification && declineJustification.trim() !== '') {
                document.getElementById('modalDeclineJustification').textContent = declineJustification;
                declineSection.style.display = 'block';
            } else {
                declineSection.style.display = 'none';
            }
            
            // Handle final price and cost justification for completed appointments
            const finalPriceSection = document.getElementById('completedPriceSection');
            const costJustificationSection = document.getElementById('costJustificationSection');
            const appPrice = button.dataset.appPrice;
            const appJustification = button.dataset.appJustification;
            
            if (appPrice && parseFloat(appPrice) > 0) {
                document.getElementById('modalFinalizedPrice').textContent = '₱' + parseFloat(appPrice).toFixed(2);
                finalPriceSection.style.display = 'block';
            } else {
                finalPriceSection.style.display = 'none';
            }
            
            if (appJustification && appJustification.trim() !== '') {
                document.getElementById('modalCostJustification').textContent = appJustification;
                costJustificationSection.style.display = 'block';
            } else {
                costJustificationSection.style.display = 'none';
            }
            
            // Handle feedback display for completed appointments
            const feedbackSection = document.getElementById('feedbackSection');
            const giveFeedbackSection = document.getElementById('giveFeedbackSection');
            const appRating = button.dataset.appRating;
            const appComment = button.dataset.appComment;
            const statusName = button.dataset.statusName;
            
            if (statusName === 'Completed') {
                if (appRating && appRating !== '0' && appComment && appComment.trim() !== '' && appComment !== 'No Comment') {
                    // Display existing feedback
                    const ratingStars = document.getElementById('ratingStars');
                    const ratingValue = document.getElementById('ratingValue');
                    const rating = parseInt(appRating);
                    
                    let starsHtml = '';
                    for (let i = 1; i <= 5; i++) {
                        if (i <= rating) {
                            starsHtml += '★';
                        } else {
                            starsHtml += '☆';
                        }
                    }
                    ratingStars.innerHTML = starsHtml;
                    ratingValue.textContent = `(${rating}/5)`;
                    
                    // Display comment
                    document.getElementById('modalComment').textContent = appComment;
                    
                    feedbackSection.style.display = 'block';
                    giveFeedbackSection.style.display = 'none';
                } else {
                    // Show feedback form for completed appointments without feedback
                    document.getElementById('modalFeedbackAppId').value = button.dataset.appId;
                    feedbackSection.style.display = 'none';
                    giveFeedbackSection.style.display = 'block';
                }
            } else {
                feedbackSection.style.display = 'none';
                giveFeedbackSection.style.display = 'none';
            }
        }
    });

    // Feedback modal - capture app_id when modal is shown
    const feedbackModal = document.getElementById('feedbackModal');
    feedbackModal.addEventListener('show.bs.modal', function(event) {
        // Get the button that triggered the modal
        const button = event.relatedTarget;
        const appId = button.getAttribute('data-app-id');
        
        console.log('Feedback modal opening for app_id:', appId);
        
        // Set the app_id in the modal
        document.getElementById('feedbackAppId').value = appId;
        
        // Reset form
        document.getElementById('selectedRating').value = '';
        document.getElementById('feedbackComment').value = '';
        document.querySelectorAll('.star').forEach(star => star.classList.remove('active'));
    });
    
    // Clean up modal state when hidden
    feedbackModal.addEventListener('hidden.bs.modal', function(event) {
        // Ensure backdrop is completely removed
        document.body.classList.remove('modal-open');
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        
        // Reset form completely
        document.getElementById('feedbackAppId').value = '';
        document.getElementById('selectedRating').value = '';
        document.getElementById('feedbackComment').value = '';
        document.querySelectorAll('.star').forEach(star => star.classList.remove('active'));
    });

    // Star rating interaction for both modals
    function initializeStarRating(containerSelector, ratingInputId) {
        const container = document.querySelector(containerSelector);
        if (!container) return;
        
        const stars = container.querySelectorAll('.star');
        const ratingInput = document.getElementById(ratingInputId);
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                ratingInput.value = rating;
                
                // Update visual feedback
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
            
            star.addEventListener('mouseover', function() {
                const rating = this.dataset.rating;
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.style.color = '#ffc107';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
        });
        
        container.addEventListener('mouseleave', function() {
            const currentRating = ratingInput.value;
            stars.forEach((s, index) => {
                if (index < currentRating) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    }
    
    // Initialize star ratings for both feedback forms
    initializeStarRating('.rating-stars', 'selectedRating');
    initializeStarRating('#giveFeedbackSection .rating-stars', 'modalRatingInput');
    
    // Handle modal feedback form submission
    document.getElementById('modalFeedbackForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const appId = document.getElementById('modalFeedbackAppId').value;
        const rating = document.getElementById('modalRatingInput').value;
        const comment = document.getElementById('modalCommentInput').value;
        
        if (!rating) {
            warningToast('Please select a rating');
            return;
        }
        
        // Submit feedback via AJAX
        fetch('/HVAC/api/customer/app_feedback.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                app_id: appId,
                rating: rating,
                comment: comment
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.text();
        })
        .then(text => {
            console.log('Raw response:', text);
            try {
                const data = JSON.parse(text);
                console.log('Parsed data:', data);
                
                if (data.success) {
                    successToast('Feedback submitted successfully!');
                    // Close modal and refresh the page
                    const modal = bootstrap.Modal.getInstance(document.getElementById('viewAppointmentModal'));
                    modal.hide();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    console.error('API Error:', data);
                    dangerToast('Error submitting feedback: ' + (data.message || 'Unknown error'));
                    if (data.debug) {
                        console.log('Debug info:', data.debug);
                    }
                }
            } catch (parseError) {
                console.error('JSON Parse Error:', parseError);
                console.error('Response text:', text);
                dangerToast('Error: Invalid response from server');
            }
        })
        .catch(error => {
            console.error('Network Error:', error);
            dangerToast('Network error occurred while submitting feedback');
        });
    });

    // Submit feedback with comprehensive validation
    document.getElementById('submitFeedback').addEventListener('click', function() {
        const appId = document.getElementById('feedbackAppId').value;
        const rating = document.getElementById('selectedRating').value;
        const comment = document.getElementById('feedbackComment').value;
        
        console.log('Submitting feedback:', {
            appId: appId,
            rating: rating,
            comment: comment
        });
        
        // Validate required fields
        if (!appId) {
            alert('Error: No appointment selected. Please close and try again.');
            return;
        }
        
        if (!rating) {
            alert('Please select a rating (1-5 stars)');
            return;
        }
        
        // Prepare data payload
        const payload = {
            app_id: parseInt(appId),
            rating: parseInt(rating),
            comment: comment && comment.trim() !== '' ? comment.trim() : 'No Comment'
        };
        
        console.log('Sending payload to API:', payload);
        
        // Disable submit button to prevent double submission
        const submitBtn = document.getElementById('submitFeedback');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        
        // Make actual API call to submit feedback
        fetch('api/customer/app_feedback.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API Response:', data);
            
            // Validate response structure
            if (typeof data !== 'object' || data === null) {
                throw new Error('Invalid response format');
            }
            
            if (data.success) {
                // Hide modal and show success message
                const feedbackModal = document.getElementById('feedbackModal');
                const modalInstance = bootstrap.Modal.getInstance(feedbackModal);
                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    // Fallback: use data-bs-dismiss
                    feedbackModal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                }
                
                successToast('Feedback submitted successfully!');
                
                // Reload page to reflect changes (avoids scope issues)
                setTimeout(() => {
                    window.location.reload();
                }, 1000); // Small delay to ensure user sees success message
                
                // Reset form
                document.getElementById('feedbackComment').value = '';
                document.getElementById('selectedRating').value = '';
                document.querySelectorAll('.star').forEach(s => s.classList.remove('active'));
            } else {
                alert('Error: ' + (data.message || 'Failed to submit feedback'));
            }
        })
        .catch(error => {
            console.error('Feedback submission error:', error);
            alert('Error: Failed to submit feedback. Please try again.');
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Feedback';
        });
    });

    // Event listener for Feedback button to populate modal
    document.addEventListener('click', function(e) {
        if (e.target.closest('.feedback-btn')) {
            const button = e.target.closest('.feedback-btn');
            const appId = button.getAttribute('data-app-id');
            
            console.log('Opening feedback modal for appointment:', appId);
            
            // Set the app_id in the hidden input field
            document.getElementById('feedbackAppId').value = appId;
            
            // Reset the form
            document.getElementById('feedbackComment').value = '';
            document.getElementById('selectedRating').value = '';
            
            // Reset star rating display
            document.querySelectorAll('.star').forEach(star => {
                star.classList.remove('active');
            });
        }
    });

    // Star rating functionality (consolidated - removed duplicate)

</script>
