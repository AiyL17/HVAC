<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register | HVAC</title>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0">
    <link rel="icon" href="img/icon.png">

    <?php
    include 'includes/cdn.php';
    ?>
    
    <style>
        /* .round_md {
            border-radius: 8px;
        }
        
        .text-purple {
            color: #6f42c1;
        }
        
        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #326e9f;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
         /* Loading spinner */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Multi-page form styling */
        .form-page {
            transition: all 0.5s ease;
            position: relative;
            width: 100%;
        }

        .hidden {
            opacity: 0;
            transform: translateX(30px);
            pointer-events: none;
            position: absolute;
            top: 0;
        }

        .visible {
            opacity: 1;
            transform: translateX(0);
            pointer-events: auto;
        }

        #form-container {
            position: relative;
            min-height: 300px;
        }

        /* Progress steps styling */
        .progress-container {
            margin-bottom: 20px;
        }

        .progress-step {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 30px;
        }

        .step {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            /* font-weight: bold; */
            z-index: 2;
            transition: all 0.5s ease;
        }

        .step.active {
            background-color: #326e9f;
            color: white;
            transform: scale(1.1);
            /* box-shadow: 0 0 15px rgba(50, 110, 159, 0.5); */
        }

        .step.completed {
            background-color: #198754;
            color: white;
            transform: scale(1);
            /* box-shadow: 0 0 10px rgba(25, 135, 84, 0.3); */
        }

        .step-connector {
            position: absolute;
            top: 17px;
            left: 50px;
            right: 50px;
            height: 3px;
            background-color: #e9ecef;
            z-index: 1;
            transition: all 0.8s ease;
        }

        .step-connector.complete {
            background-color: #198754;
            /* box-shadow: 0 0 8px rgba(25, 135, 84, 0.5); */
        }

        .step-label {
            position: absolute;
            top: 40px;
            width: 100px;
            text-align: center;
            font-size: 12px;
            transition: all 0.5s ease;
        }

        #page1-label {
            left: -30px;
        }

        #page2-label {
            right: -30px;
        }

        .step.completed+.step-label {
            color: #198754;
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
        }

        .enhanced-dropdown-input:focus {
            box-shadow: none !important;
            border-color: transparent !important;
        }

        .enhanced-dropdown-input::placeholder {
            color: transparent;
            opacity: 0;
        }
        
        /* Handle floating label positioning when dropdown has value */
        .enhanced-dropdown-input:not(:placeholder-shown) {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
            padding-left: 0.75rem !important;
        }
        
        .enhanced-dropdown-input:focus {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
            padding-left: 0.75rem !important;
        }
        
        /* Ensure proper label positioning for dropdowns with values */
        .enhanced-dropdown-input.has-value {
            padding-top: 1.625rem !important;
            padding-bottom: 0.625rem !important;
            padding-left: 0.75rem !important;
        }
        
        /* Float the label when dropdown has value */
        .enhanced-dropdown-wrapper:has(.enhanced-dropdown-input.has-value) + label,
        .enhanced-dropdown-wrapper:has(.enhanced-dropdown-input:focus) + label,
        .enhanced-dropdown-wrapper:has(.enhanced-dropdown-input:not(:placeholder-shown)) + label {
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
            color: #6c757d;
        }



        .enhanced-dropdown-list {
            position: absolute;
            top: calc(100% + 2px);
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            max-height: 300px;
            overflow: hidden;
            display: none;
        }

        .enhanced-dropdown-wrapper.active .enhanced-dropdown-list {
            display: block;
            animation: dropdownFadeIn 0.2s ease-out;
        }

        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .dropdown-option.no-results {
            color: #6c757d;
            font-style: italic;
            cursor: default;
            text-align: center;
            padding: 20px 16px;
        }

        .dropdown-option.no-results:hover {
            background-color: transparent;
            color: #6c757d;
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

        /* Form floating label adjustments */
        .form-floating > .enhanced-dropdown-input ~ label {
            opacity: 0.65;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }
        
        .form-floating > .enhanced-dropdown-input:focus ~ label,
        .form-floating > .enhanced-dropdown-input:not(:placeholder-shown) ~ label {
            opacity: 0.65;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }
        
        /* Ensure dropdown shows selected value */
        .enhanced-dropdown-input.has-value {
            color: #495057 !important;
        }
        
        .enhanced-dropdown-input.has-value::after {
            content: attr(data-selected);
            color: #495057;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .enhanced-dropdown-list {
                max-height: 250px;
            }
            
            .dropdown-options {
                max-height: 150px;
            }
            
            .dropdown-option {
                padding: 10px 12px;
                font-size: 13px;
            }
        }
    </style>
</head>

<body class="bg-light" style="overflow-x: hidden;">

    <div class="text-dark   my-5 my-lg-0 row align-items-center justify-content-center vh-100 vw-100">
        <div class="container col-lg-8 round_md ">
            <div class="row justify-content-center ">
                <div class="col-lg-6  round_md align-items-center d-flex">
                    <div>
                        <div class="ms-sm-5 ms-2 text-start">
                            <div class="d-flex gap-1">
                                <div class="m-0 p-0 bg-dark-subtle col-1 p-1 round_lg"></div>
                                <div class="m-0 p-0 bg-dark-subtle col-5 p-1 round_lg"></div>
                            </div>
                            <div class="d-flex align-items-end">
                                <h1 class="p-0">
                                    <b><span style="font-size: calc(3.0rem + 3.2vw);">H<span
                                                style="color:#326e9f;">VAC</span></span></b>
                                </h1>
                            </div>
                            <h6 style="color:#212529;"><span class="fw-bold"><i class="bi bi-gear"></i></span>
                                &nbsp;|&nbsp; Air Conditioning and Refrigeration Services</h6>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5   d-flex align-items-center">
                    <div class="w-100" id="registerContainer">
                        <div id="login" class="p-sm-4 p-2 ">
                            <div class="d-flex align-items-center pb-2  ">
                                <h3 class="p-0 m-0"><b>Register</b></h3>
                                <div>
                                    <div id="loader" class="ms-3 loader collapse small"></div>
                                </div>
                            </div>

                            <!-- Progress Indicator -->
                            <div class="progress-container mx-lg-5 mx-5   ">
                                <div class="progress-step">
                                    <div class="step active" id="step1">1</div>
                                    <div class="step-connector" id="connector"></div>
                                    <div class="step" id="step2">2</div>

                                    <div class="step-label" id="page1-label">Personal Info</div>
                                    <div class="step-label" id="page2-label">Account Setup</div>
                                </div>
                            </div>
                            <div id="errorMsgRegister" role="alert"
                                class="collapse  text-center alert m-0 p-0 round_md"></div>

                            <form id="registerForm" class="" name="register">
                                <div id="form-container">
                                    <!-- Page 1: Personal Information -->
                                    <div id="page1" class="form-page visible">
                                        <div class="row mt-2">
                                            <div class="col-6 col-lg-6 pe-lg-1">
                                                <div class=" form-floating mb-2">
                                                    <input type="text" class="round_md form-control border-0  "
                                                        id="first_name" name="first_name" autocomplete="off"
                                                        placeholder="First Name" required>
                                                    <label for="first_name">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-lg-6 ps-lg-1 ">
                                                <div class=" form-floating mb-2 ">
                                                    <input type="text" class="round_md  form-control border-0  "
                                                        id="middle_name" name="middle_name" autocomplete="off"
                                                        placeholder="Middle Name" required>
                                                    <label for="middle_name" class="text-truncate ">Middle Name</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-12">
                                                <div class=" form-floating mb-2">
                                                    <input type="text" class="round_md form-control border-0  "
                                                        id="last_name" name="last_name" autocomplete="off"
                                                        placeholder="Last Name" required>
                                                    <label for="last_name">Last Name</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class=" form-floating mb-2">
                                                    <input type="text" class="round_md form-control border-0  "
                                                         id="contact" name="contact" autocomplete="off"
                                                         placeholder="Contact" pattern="[0-9]{11}" title="Please enter exactly 11 digits" maxlength="11" required>
                                                    <label for="contact">Contact</label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="d-flex  ">
                                            <div class="w-100  form-floating mb-2">
                                                <input type="password" id="password" class="round_md form-control border-0 show-hide-password round" name="password" placeholder="Password" required="">
                                                <label for="password">Password</label>
                                            </div>
                                            <span class="fas fa-eye-slash" id="togglePassword" style="margin-top:21px;margin-left:-50px;z-index:1;cursor:pointer"></span>
                                        </div> -->

                                        <!-- Address Fields -->
                                        <div class="form-floating mb-3">
                                            <input type="text" class="round_md form-control border-0" id="house_building_street" name="house_building_street" autocomplete="off" placeholder="House/Building Number and Street Name" required>
                                            <label for="house_building_street">House/Building Number & Street Name</label>
                                        </div>
                                        
                                        <!-- Address Fields - Enhanced Dropdown UI -->
                                        <div class="row">
                                            <div class="col-6 col-lg-6 pe-lg-1">
                                                <div class="form-floating mb-3 position-relative">
                                                    <div class="enhanced-dropdown-wrapper">
                                                        <input type="text" 
                                                               class="form-control round_md border-0 enhanced-dropdown-input" 
                                                               id="province" 
                                                               name="province" 
                                                               placeholder="Search or select Province" 
                                                               autocomplete="off" 
                                                               required>
                                                        <div class="enhanced-dropdown-list" id="province-dropdown">
                                                            <div class="dropdown-options" id="province-options">
                                                                <!-- Options will be populated by JavaScript -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label for="province">Province</label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-lg-6 ps-lg-1">
                                                <div class="form-floating mb-3 position-relative">
                                                    <div class="enhanced-dropdown-wrapper">
                                                        <input type="text" 
                                                               class="form-control round_md border-0 enhanced-dropdown-input" 
                                                               id="municipality_city" 
                                                               name="municipality_city" 
                                                               placeholder="Search or select Municipality/City" 
                                                               autocomplete="off" 
                                                               required>
                                                        <div class="enhanced-dropdown-list" id="city-dropdown">
                                                            <div class="dropdown-options" id="city-options">
                                                                <!-- Options will be populated by JavaScript -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label for="municipality_city">Municipality</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-6 col-lg-6 pe-lg-1">
                                                <div class="form-floating mb-3 position-relative">
                                                    <div class="enhanced-dropdown-wrapper">
                                                        <input type="text" 
                                                               class="form-control round_md border-0 enhanced-dropdown-input" 
                                                               id="barangay" 
                                                               name="barangay" 
                                                               placeholder="Search or select Barangay" 
                                                               autocomplete="off" 
                                                               required>
                                                        <div class="enhanced-dropdown-list" id="barangay-dropdown">
                                                            <div class="dropdown-options" id="barangay-options">
                                                                <!-- Options will be populated by JavaScript -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label for="barangay">Barangay</label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-lg-6 ps-lg-1">
                                                <div class="form-floating mb-3">
                                                     <input type="text" class="round_md form-control border-0" id="zip_code" name="zip_code" autocomplete="off" placeholder="Zip Code" pattern="[0-9]{4}" title="Please enter exactly 4 digits" maxlength="4" required>
                                                    <label for="zip_code">Zip Code</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <button type="button" id="nextBtn"
                                                class=" w-100 btn border-0 round_lg btn-primary"
                                                style="transition: all 0.3s ease;">
                                                Proceed
                                                <i class=" ms-2 bi bi-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Page 2: Account Setup -->
                                    <div id="page2" class="form-page hidden">
                                        <div class="row mt-2">
                                            <div class="col-sm-12">
                                                <div class=" form-floating mb-2">
                                                    <input type="email" class="round_md form-control border-0  "
                                                        id="email" name="email" autocomplete="off" placeholder="Email"
                                                        required>
                                                    <label for="email">Email</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex  ">
                                            <div class="w-100  form-floating mb-2">
                                                <input type="password" id="password"
                                                    class="round_md form-control border-0 show-hide-password round"
                                                    name="password" placeholder="Password" required />
                                                <label for="password">Password</label>
                                            </div>
                                            <span class="fas fa-eye-slash" id="togglePassword"
                                                style="margin-top:21px;margin-left:-55px;z-index:1;cursor:pointer"></span>
                                        </div>

                                        <div class="d-flex  mb-4">
                                            <div class="w-100  form-floating mb-2">
                                                <input type="password" id="confirm_password"
                                                    class="round_md form-control border-0 show-hide-password round"
                                                    name="confirm_password" placeholder="Confirm Password" required />
                                                <label for="confirm_password">Confirm Password</label>
                                            </div>
                                            <span class="fas fa-eye-slash" id="toggleConfirmPassword"
                                                style="margin-top:21px;margin-left:-55px;z-index:1;cursor:pointer"></span>
                                        </div>

                                        <div class="d-flex justify-content-between  ">
                                            <button type="button" id="prevBtn"
                                                class="small btn border-0 round_lg btn-secondary"
                                                style="width: 48%; transition: all 0.3s ease;">
                                                <i class=" me-2 bi bi-arrow-left"></i>

                                                Back
                                            </button>
                                            <button type="submit" id="submit-btn"
                                                class="d-flex justify-content-center small btn border-0 round_lg btn-primary"
                                                style="width: 48%; transition: all 0.3s ease;">
                                                Register
                                                <i class=" ms-2 bi bi-check-lg"></i>

                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="text-center small pt-2">
                            <p>Already have an Account? <a class="text-purple" href="login.php">Click Here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- OTP Verification Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content round_md">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="otpModalLabel">
                        <i class="bi bi-shield-check text-primary me-2"></i>
                        Verify Your Phone Number
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <div class="text-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-phone text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <p class="mt-3 mb-2 text-muted">We've sent a 6-digit verification code to:</p>
                        <p class="fw-bold mb-0" id="otpPhoneDisplay">+63 XXX XXX XXXX</p>
                    </div>
                    
                    <div id="otpErrorMsg" class="alert alert-danger d-none" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <span id="otpErrorText">Error message here</span>
                    </div>
                    
                    <div id="otpSuccessMsg" class="alert alert-success d-none" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <span id="otpSuccessText">Success message here</span>
                    </div>
                    
                    <form id="otpForm">
                        <div class="mb-3">
                            <label for="otpCode" class="form-label">Enter 6-digit OTP Code</label>
                            <input type="text" class="form-control form-control-lg text-center round_md" 
                                   id="otpCode" name="otpCode" placeholder="000000" 
                                   maxlength="6" pattern="[0-9]{6}" autocomplete="off" required>
                            <div class="form-text text-center">
                                <span id="otpTimer" class="text-muted">Code expires in 5:00</span>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" id="verifyOtpBtn" class="btn btn-primary btn-lg round_lg">
                                <span id="verifyBtnText">Verify & Complete Registration</span>
                                <div id="verifySpinner" class="spinner d-none"></div>
                            </button>
                            
                            <button type="button" id="resendOtpBtn" class="btn btn-outline-secondary round_lg" disabled>
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                <span id="resendBtnText">Resend OTP (60s)</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    
   <script>
    
    document.addEventListener('DOMContentLoaded', function () {
        const page1 = document.getElementById('page1');
        const page2 = document.getElementById('page2');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.getElementById('submit-btn');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const connector = document.getElementById('connector');
        const form = document.getElementById('registerForm');

        // Form validation patterns
        const patterns = {
            email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
            contact: /^[0-9]{11}$/,
            // Password must contain at least 8 characters, one uppercase, one lowercase and one number
            password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/,
            name: /^[a-zA-Z\s]+$/  // Letters and spaces only
        };

        // Custom validation messages
        const messages = {
            email: 'Please enter a valid email address',
            contact: 'Please enter exactly 11 digits',
            password: 'Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter,',
            confirmPassword: 'Passwords do not match',
            name: 'Letters only',  // Updated message
            required: 'This field is required'
        };

        // Add validation on input fields
        function setupValidation() {
            // Email validation
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('input', function () {
                validateField(this, patterns.email, messages.email);
            });

            // Contact validation
            const contactInput = document.getElementById('contact');
            contactInput.addEventListener('input', function () {
                validateField(this, patterns.contact, messages.contact);
            });

            // Password validation
            const passwordInput = document.getElementById('password');
            passwordInput.addEventListener('input', function () {
                validateField(this, patterns.password, messages.password);
                // Also validate confirm password if it has a value
                if (document.getElementById('confirm_password').value) {
                    validatePasswordMatch();
                }
            });

            // Confirm password validation
            const confirmPasswordInput = document.getElementById('confirm_password');
            confirmPasswordInput.addEventListener('input', validatePasswordMatch);

            // Name validation for first name
            const firstNameInput = document.getElementById('first_name');
            firstNameInput.addEventListener('input', function () {
                validateField(this, patterns.name, messages.name);
            });

            // Name validation for middle name
            const middleNameInput = document.getElementById('middle_name');
            middleNameInput.addEventListener('input', function () {
                validateField(this, patterns.name, messages.name);
            });

            // Name validation for last name
            const lastNameInput = document.getElementById('last_name');
            lastNameInput.addEventListener('input', function () {
                validateField(this, patterns.name, messages.name);
            });

            // Clear validation errors when typing on other input fields
            const allInputs = document.querySelectorAll('input[required], textarea[required]');
            allInputs.forEach(input => {
                if (input.id !== 'email' && input.id !== 'contact' &&
                    input.id !== 'password' && input.id !== 'confirm_password' &&
                    input.id !== 'first_name' && input.id !== 'middle_name' && input.id !== 'last_name') {
                    input.addEventListener('input', function () {
                        this.classList.remove('is-invalid');
                        this.classList.add('border-0');

                        const feedbackElement = this.nextElementSibling?.classList.contains('invalid-feedback') ?
                            this.nextElementSibling : null;
                        if (feedbackElement) feedbackElement.remove();
                    });
                }
            });
        }

        // Validate a field against a pattern
        function validateField(field, pattern, message) {
            const value = field.value.trim();
            const isEmpty = value === '';
            const isValid = pattern ? pattern.test(value) : !isEmpty;

            if (isEmpty) {
                field.classList.remove('is-valid');
                field.classList.add('is-invalid');
                field.classList.remove('border-0');
                field.classList.add('border-2');

                let feedbackElement = field.nextElementSibling?.classList.contains('invalid-feedback') ?
                    field.nextElementSibling : null;

                if (!feedbackElement) {
                    feedbackElement = document.createElement('div');
                    feedbackElement.className = 'invalid-feedback';
                    field.parentNode.insertBefore(feedbackElement, field.nextSibling);
                }

                feedbackElement.textContent = messages.required;
                return false;
            } else if (!isValid) {
                field.classList.remove('is-valid');
                field.classList.add('is-invalid');
                field.classList.remove('border-0');
                field.classList.add('border-2');

                let feedbackElement = field.nextElementSibling?.classList.contains('invalid-feedback') ?
                    field.nextElementSibling : null;

                if (!feedbackElement) {
                    feedbackElement = document.createElement('div');
                    feedbackElement.className = 'invalid-feedback';
                    field.parentNode.insertBefore(feedbackElement, field.nextSibling);
                }

                feedbackElement.textContent = message;
                return false;
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('border-0');
                const feedbackElement = field.nextElementSibling?.classList.contains('invalid-feedback') ?
                    field.nextElementSibling : null;
                if (feedbackElement) feedbackElement.remove();
                return true;
            }
        }

        // Validate password confirmation
        function validatePasswordMatch() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');

            if (password.value === confirmPassword.value) {
                confirmPassword.classList.remove('is-invalid');
                confirmPassword.classList.add('border-0');

                const feedbackElement = confirmPassword.nextElementSibling?.classList.contains('invalid-feedback') ?
                    confirmPassword.nextElementSibling : null;
                if (feedbackElement) feedbackElement.remove();

                return true;
            } else {
                confirmPassword.classList.remove('is-valid');
                confirmPassword.classList.add('is-invalid');
                confirmPassword.classList.remove('border-0');

                let feedbackElement = confirmPassword.nextElementSibling?.classList.contains('invalid-feedback') ?
                    confirmPassword.nextElementSibling : null;

                if (!feedbackElement) {
                    feedbackElement = document.createElement('div');
                    feedbackElement.className = 'invalid-feedback';
                    confirmPassword.parentNode.insertBefore(feedbackElement, confirmPassword.nextSibling);
                }

                feedbackElement.textContent = messages.confirmPassword;

                return false;
            }
        }

        // Validate all required fields in a specific page
        function validatePage(pageNum) {
            const page = document.getElementById(`page${pageNum}`);
            const requiredInputs = page.querySelectorAll('input[required], textarea[required]');
            let isValid = true;

            requiredInputs.forEach(input => {
                if (pageNum === 2) {
                    if (input.id === 'email') {
                        isValid = validateField(input, patterns.email, messages.email) && isValid;
                    } else if (input.id === 'password') {
                        isValid = validateField(input, patterns.password, messages.password) && isValid;
                    } else if (input.id === 'confirm_password') {
                        isValid = validatePasswordMatch() && isValid;
                    } else {
                        isValid = validateField(input) && isValid;
                    }
                } else if (pageNum === 1) {
                    if (input.id === 'contact') {
                        isValid = validateField(input, patterns.contact, messages.contact) && isValid;
                    } else if (input.id === 'first_name' || input.id === 'middle_name' || input.id === 'last_name') {
                        isValid = validateField(input, patterns.name, messages.name) && isValid;
                    } else {
                        isValid = validateField(input) && isValid;
                    }
                }
            });

            return isValid;
        }

        // Next button functionality
        nextBtn.addEventListener('click', function () {
            if (validatePage(1)) {
                page1.classList.add('hidden');
                page2.classList.remove('hidden');

                setTimeout(() => {
                    page1.classList.remove('visible');
                    page2.classList.add('visible');
                }, 50);

                connector.style.transition = 'background 0.8s ease';

                setTimeout(() => {
                    step1.classList.remove('active');
                    step1.classList.add('completed');
                    connector.classList.add('complete');

                    setTimeout(() => {
                        step2.classList.add('active');
                    }, 200);
                }, 100);
            }
        });

        // Previous button functionality
        prevBtn.addEventListener('click', function () {
            page2.classList.add('hidden');
            page1.classList.remove('hidden');

            setTimeout(() => {
                page2.classList.remove('visible');
                page1.classList.add('visible');
            }, 50);

            step2.classList.remove('active');

            setTimeout(() => {
                connector.classList.remove('complete');

                setTimeout(() => {
                    step1.classList.remove('completed');
                    step1.classList.add('active');
                }, 200);
            }, 100);
        });

        // Store form data globally for OTP verification
        let registrationData = {};

        // Form submission handler - trigger OTP modal
        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (!validatePage(2)) {
                return;
            }

            // Store form data
            registrationData = {
                first_name: document.getElementById('first_name').value,
                middle_name: document.getElementById('middle_name').value,
                last_name: document.getElementById('last_name').value,
                email: document.getElementById('email').value,
                contact: document.getElementById('contact').value,
                house_building_street: document.getElementById('house_building_street').value,
                barangay: document.getElementById('barangay').value,
                municipality_city: document.getElementById('municipality_city').value,
                province: document.getElementById('province').value,
                zip_code: document.getElementById('zip_code').value,
                password: document.getElementById('password').value,
                confirm_password: document.getElementById('confirm_password').value
            };

            // Validate email and password for duplicates before sending OTP
            await validateRegistrationData(registrationData);
        });

        // Send OTP function
        async function sendOTP(phoneNumber) {
            const loader = new bootstrap.Collapse(document.getElementById('loader'), {
                toggle: false
            });
            const errorMsgRegister = document.getElementById('errorMsgRegister');
            const errorMsgCollapse = new bootstrap.Collapse(errorMsgRegister, {
                toggle: false
            });

            loader.show();
            submitBtn.innerHTML = 'Sending OTP <div class="spinner"></div>';
            submitBtn.disabled = true;
            prevBtn.disabled = true;

            try {
                const response = await fetch('api/send_otp.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        phone_number: phoneNumber
                    })
                });

                const data = await response.json();
                console.log('OTP Send Response:', data);

                if (data.success) {
                    // Show OTP modal
                    showOTPModal(phoneNumber);

                    // Reset form button
                    loader.hide();
                    submitBtn.innerHTML = 'Register <i class="bi bi-check-lg ms-2"></i>';
                    submitBtn.disabled = false;
                    prevBtn.disabled = false;
                } else {
                    // Show error message
                    errorMsgRegister.classList.remove("alert-success");
                    errorMsgRegister.classList.add("alert-danger");
                    errorMsgRegister.innerHTML = `<p class="small m-2 p-0">${data.message}</p>`;

                    setTimeout(() => {
                        errorMsgCollapse.show();
                        loader.hide();
                        submitBtn.innerHTML = 'Register <i class="bi bi-check-lg ms-2"></i>';
                        submitBtn.disabled = false;
                        prevBtn.disabled = false;
                    }, 500);
                }
            } catch (error) {
                console.error('OTP Send Error:', error);
                errorMsgRegister.classList.remove("alert-success");
                errorMsgRegister.classList.add("alert-danger");
                errorMsgRegister.innerHTML = `<p class="small m-2 p-0">Failed to send OTP. Please try again.</p>`;

                setTimeout(() => {
                    errorMsgCollapse.show();
                    loader.hide();
                    submitBtn.innerHTML = 'Register <i class="bi bi-check-lg ms-2"></i>';
                    submitBtn.disabled = false;
                    prevBtn.disabled = false;
                }, 500);
            }
        }

        // Validate registration data for duplicates before OTP
        async function validateRegistrationData(data) {
            const loader = new bootstrap.Collapse(document.getElementById('loader'), {
                toggle: false
            });
            const errorMsgRegister = document.getElementById('errorMsgRegister');
            const errorMsgCollapse = new bootstrap.Collapse(errorMsgRegister, {
                toggle: false
            });

            loader.show();
            submitBtn.innerHTML = 'Validating <div class="spinner"></div>';
            submitBtn.disabled = true;
            prevBtn.disabled = true;

            try {
                const response = await fetch('api/validate_registration.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email: data.email,
                        password: data.password
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Validation passed, proceed with OTP
                    await sendOTP(data.contact);
                } else {
                    // Show validation error
                    errorMsgRegister.className = 'alert alert-danger text-center m-0 p-2 round_md';
                    errorMsgRegister.textContent = result.message;
                    
                    setTimeout(() => {
                        errorMsgCollapse.show();
                        loader.hide();
                        submitBtn.innerHTML = 'Register <i class="bi bi-check-lg ms-2"></i>';
                        submitBtn.disabled = false;
                        prevBtn.disabled = false;
                    }, 500);
                }
            } catch (error) {
                console.error('Validation error:', error);
                errorMsgRegister.className = 'alert alert-danger text-center m-0 p-2 round_md';
                errorMsgRegister.textContent = 'Validation failed. Please try again.';
                
                setTimeout(() => {
                    errorMsgCollapse.show();
                    loader.hide();
                    submitBtn.innerHTML = 'Register <i class="bi bi-check-lg ms-2"></i>';
                    submitBtn.disabled = false;
                    prevBtn.disabled = false;
                }, 500);
            }
        }

        // Toggle password visibility with animation
        document.getElementById('togglePassword').addEventListener('click', function () {
            togglePasswordVisibility('password', 'togglePassword');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            togglePasswordVisibility('confirm_password', 'toggleConfirmPassword');
        });

        function togglePasswordVisibility(inputId, iconId) {
            const eyeIcon = document.getElementById(iconId);
            const pwdInput = document.getElementById(inputId);

            setTimeout(() => {
                if (eyeIcon.classList.contains('fa-eye')) {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                    pwdInput.setAttribute('type', 'password');
                } else {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                    pwdInput.setAttribute('type', 'text');
                }

                pwdInput.focus();
            }, 150);
        }

        // OTP Modal Variables
        let otpTimer;
        let otpTimeLeft = 300; // 5 minutes
        let resendTimer;
        let resendTimeLeft = 60; // 1 minute
        const otpModal = new bootstrap.Modal(document.getElementById('otpModal'));

        // Show OTP Modal
        function showOTPModal(phoneNumber) {
            // Format phone number for display
            const formattedPhone = formatPhoneForDisplay(phoneNumber);
            document.getElementById('otpPhoneDisplay').textContent = formattedPhone;
            
            // Reset modal state
            document.getElementById('otpCode').value = '';
            document.getElementById('otpErrorMsg').classList.add('d-none');
            document.getElementById('otpSuccessMsg').classList.add('d-none');
            
            // Start timers
            startOTPTimer();
            startResendTimer();
            
            // Show modal
            otpModal.show();
        }

        // Format phone number for display
        function formatPhoneForDisplay(phoneNumber) {
            if (phoneNumber.length === 11 && phoneNumber.startsWith('0')) {
                return `+63 ${phoneNumber.substring(1, 4)} ${phoneNumber.substring(4, 7)} ${phoneNumber.substring(7)}`;
            }
            return phoneNumber;
        }

        // Start OTP expiry timer
        function startOTPTimer() {
            otpTimeLeft = 300; // Reset to 5 minutes
            const timerElement = document.getElementById('otpTimer');
            
            otpTimer = setInterval(() => {
                const minutes = Math.floor(otpTimeLeft / 60);
                const seconds = otpTimeLeft % 60;
                timerElement.textContent = `Code expires in ${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                if (otpTimeLeft <= 0) {
                    clearInterval(otpTimer);
                    timerElement.textContent = 'Code expired';
                    timerElement.classList.add('text-danger');
                    
                    // Disable verify button
                    document.getElementById('verifyOtpBtn').disabled = true;
                    showOTPError('OTP code has expired. Please request a new one.');
                }
                
                otpTimeLeft--;
            }, 1000);
        }

        // Start resend timer
        function startResendTimer() {
            resendTimeLeft = 60; // Reset to 1 minute
            const resendBtn = document.getElementById('resendOtpBtn');
            const resendBtnText = document.getElementById('resendBtnText');
            
            resendBtn.disabled = true;
            
            resendTimer = setInterval(() => {
                resendBtnText.textContent = `Resend OTP (${resendTimeLeft}s)`;
                
                if (resendTimeLeft <= 0) {
                    clearInterval(resendTimer);
                    resendBtn.disabled = false;
                    resendBtnText.textContent = 'Resend OTP';
                }
                
                resendTimeLeft--;
            }, 1000);
        }

        // Show OTP error message
        function showOTPError(message) {
            const errorMsg = document.getElementById('otpErrorMsg');
            const errorText = document.getElementById('otpErrorText');
            const successMsg = document.getElementById('otpSuccessMsg');
            
            successMsg.classList.add('d-none');
            errorText.textContent = message;
            errorMsg.classList.remove('d-none');
        }

        // Show OTP success message
        function showOTPSuccess(message) {
            const errorMsg = document.getElementById('otpErrorMsg');
            const successMsg = document.getElementById('otpSuccessMsg');
            const successText = document.getElementById('otpSuccessText');
            
            errorMsg.classList.add('d-none');
            successText.textContent = message;
            successMsg.classList.remove('d-none');
        }

        // OTP Form submission
        document.getElementById('otpForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const otpCode = document.getElementById('otpCode').value.trim();
            const verifyBtn = document.getElementById('verifyOtpBtn');
            const verifyBtnText = document.getElementById('verifyBtnText');
            const verifySpinner = document.getElementById('verifySpinner');
            
            if (otpCode.length !== 6 || !/^[0-9]{6}$/.test(otpCode)) {
                showOTPError('Please enter a valid 6-digit OTP code.');
                return;
            }
            
            // Show loading state
            verifyBtn.disabled = true;
            verifyBtnText.textContent = 'Verifying...';
            verifySpinner.classList.remove('d-none');
            
            try {
                // Verify OTP
                const verifyResponse = await fetch('api/verify_otp.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        phone_number: registrationData.contact,
                        otp_code: otpCode
                    })
                });
                
                const verifyData = await verifyResponse.json();
                console.log('OTP Verify Response:', verifyData);
                
                if (verifyData.success && verifyData.verified) {
                    showOTPSuccess('OTP verified successfully! Completing registration...');
                    
                    // Complete registration
                    setTimeout(async () => {
                        await completeRegistration();
                    }, 1000);
                    
                } else {
                    showOTPError(verifyData.message || 'Invalid OTP code. Please try again.');
                    
                    // Reset button state
                    verifyBtn.disabled = false;
                    verifyBtnText.textContent = 'Verify & Complete Registration';
                    verifySpinner.classList.add('d-none');
                }
                
            } catch (error) {
                console.error('OTP Verification Error:', error);
                showOTPError('Verification failed. Please try again.');
                
                // Reset button state
                verifyBtn.disabled = false;
                verifyBtnText.textContent = 'Verify & Complete Registration';
                verifySpinner.classList.add('d-none');
            }
        });

        // Resend OTP button
        document.getElementById('resendOtpBtn').addEventListener('click', async function() {
            await sendOTP(registrationData.contact);
        });

        // Complete registration after OTP verification
        async function completeRegistration() {
            try {
                const response = await fetch('api/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(registrationData)
                });
                
                const data = await response.json();
                console.log('Registration Response:', data);
                
                if (data.success) {
                    showOTPSuccess('Registration completed successfully! Redirecting...');
                    
                    // Clear timers
                    clearInterval(otpTimer);
                    clearInterval(resendTimer);
                    
                    // Redirect after success
                    setTimeout(() => {
                        window.location.href = data.redirect || 'login.php';
                    }, 2000);
                    
                } else {
                    showOTPError(data.message || 'Registration failed. Please try again.');
                    
                    // Reset verify button
                    const verifyBtn = document.getElementById('verifyOtpBtn');
                    const verifyBtnText = document.getElementById('verifyBtnText');
                    const verifySpinner = document.getElementById('verifySpinner');
                    
                    verifyBtn.disabled = false;
                    verifyBtnText.textContent = 'Verify & Complete Registration';
                    verifySpinner.classList.add('d-none');
                }
                
            } catch (error) {
                console.error('Registration Error:', error);
                showOTPError('Registration failed. Please try again.');
                
                // Reset verify button
                const verifyBtn = document.getElementById('verifyOtpBtn');
                const verifyBtnText = document.getElementById('verifyBtnText');
                const verifySpinner = document.getElementById('verifySpinner');
                
                verifyBtn.disabled = false;
                verifyBtnText.textContent = 'Verify & Complete Registration';
                verifySpinner.classList.add('d-none');
            }
        }

        // OTP input formatting (only allow numbers)
        document.getElementById('otpCode').addEventListener('input', function(event) {
            let value = event.target.value.replace(/[^0-9]/g, '');
            if (value.length > 6) {
                value = value.substring(0, 6);
            }
            event.target.value = value;
            
            // Clear error messages when typing
            if (value.length > 0) {
                document.getElementById('otpErrorMsg').classList.add('d-none');
            }
        });
        
        // Contact input formatting (only allow numbers, max 11 digits)
        document.getElementById('contact').addEventListener('input', function(event) {
            let value = event.target.value.replace(/[^0-9]/g, '');
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            event.target.value = value;
        });
        
        // Zip Code input formatting (only allow numbers, max 4 digits)
        document.getElementById('zip_code').addEventListener('input', function(event) {
            let value = event.target.value.replace(/[^0-9]/g, '');
            if (value.length > 4) {
                value = value.substring(0, 4);
            }
            event.target.value = value;
        });

        // Clear timers when modal is closed
        document.getElementById('otpModal').addEventListener('hidden.bs.modal', function() {
            clearInterval(otpTimer);
            clearInterval(resendTimer);
        });

        // Address dropdown interdependence logic
        
        // Data structure for province-city-barangay relationships
        // For a more comprehensive system, this would ideally be loaded from a database or API
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

        // Function to handle barangay selection
        function onBarangayChange() {
            // Prevent infinite loops
            if (window.addressUpdating) return;
            window.addressUpdating = true;
            
            console.log('onBarangayChange function called');
            
            const barangayInput = document.getElementById('barangay');
            const selectedBarangay = barangayInput.value;
            console.log('Selected barangay:', selectedBarangay);
            
            // If a barangay is selected, auto-fill city and province
            if (selectedBarangay) {
                let found = false;
                // Find the city and province for this barangay
                Object.keys(addressData).forEach(province => {
                    Object.keys(addressData[province].barangays).forEach(city => {
                        if (addressData[province].barangays[city].includes(selectedBarangay)) {
                            console.log('Found barangay', selectedBarangay, 'in city:', city, 'province:', province);
                            
                            // Update the enhanced dropdowns without triggering events
                            if (window.addressDropdowns) {
                                // Temporarily disable events
                                const originalSetValue = window.addressDropdowns.city.setValue;
                                window.addressDropdowns.city.setValue = function(value) {
                                    this.selectedValue = value;
                                    this.input.value = value;
                                    if (value) {
                                        this.input.classList.add('has-value');
                                    } else {
                                        this.input.classList.remove('has-value');
                                    }
                                };
                                
                                window.addressDropdowns.city.setValue(city);
                                window.addressDropdowns.province.setValue(province);
                                
                                // Auto-fill zip code based on city
                                if (addressData[province] && addressData[province].zipCodes && addressData[province].zipCodes[city]) {
                                    const zipCodeInput = document.getElementById('zip_code');
                                    if (zipCodeInput) {
                                        zipCodeInput.value = addressData[province].zipCodes[city];
                                        console.log('Auto-filled zip code:', addressData[province].zipCodes[city], 'for city:', city);
                                    }
                                }
                                
                                // Restore original setValue
                                window.addressDropdowns.city.setValue = originalSetValue;
                                
                                // Update dropdowns
                                updateCityDropdown(province);
                                updateBarangayDropdown(city, province);
                            }
                            found = true;
                        }
                    });
                });
                
                if (!found) {
                    console.log('Barangay not found in addressData (custom entry):', selectedBarangay);
                    // For custom entries, we don't auto-fill other fields
                    // This allows users to enter addresses not in our predefined list
                }
            } else {
                console.log('No barangay selected');
            }
            
            window.addressUpdating = false;
        }

        // Function to handle city selection
        function onCityChange() {
            // Prevent infinite loops
            if (window.addressUpdating) return;
            window.addressUpdating = true;
            
            const cityInput = document.getElementById('municipality_city');
            const selectedCity = cityInput.value;
            
            console.log('onCityChange called, selected city:', selectedCity);
            
            // If a city is selected, auto-fill province and filter barangays
            if (selectedCity) {
                // Find the province for this city
                Object.keys(addressData).forEach(province => {
                    if (addressData[province].cities.includes(selectedCity)) {
                        console.log('Found city', selectedCity, 'in province:', province);
                        
                        // Update the enhanced dropdowns without triggering events
                        if (window.addressDropdowns) {
                            // Temporarily disable events for province
                            const originalSetValue = window.addressDropdowns.province.setValue;
                            window.addressDropdowns.province.setValue = function(value) {
                                this.selectedValue = value;
                                this.input.value = value;
                                if (value) {
                                    this.input.classList.add('has-value');
                                } else {
                                    this.input.classList.remove('has-value');
                                }
                            };
                            
                            window.addressDropdowns.province.setValue(province);
                            
                            // Restore original setValue
                            window.addressDropdowns.province.setValue = originalSetValue;
                            
                            // Auto-fill zip code based on city
                            if (addressData[province] && addressData[province].zipCodes && addressData[province].zipCodes[selectedCity]) {
                                const zipCodeInput = document.getElementById('zip_code');
                                if (zipCodeInput) {
                                    zipCodeInput.value = addressData[province].zipCodes[selectedCity];
                                    console.log('Auto-filled zip code:', addressData[province].zipCodes[selectedCity], 'for city:', selectedCity);
                                }
                            }
                            
                            // Update barangay dropdown to show only barangays in this city
                            updateBarangayDropdown(selectedCity, province);
                            // Clear barangay selection since we changed city
                            const originalBarangaySetValue = window.addressDropdowns.barangay.setValue;
                            window.addressDropdowns.barangay.setValue = function(value) {
                                this.selectedValue = value;
                                this.input.value = value;
                                if (value) {
                                    this.input.classList.add('has-value');
                                } else {
                                    this.input.classList.remove('has-value');
                                }
                            };
                            window.addressDropdowns.barangay.setValue('');
                            window.addressDropdowns.barangay.setValue = originalBarangaySetValue;
                        }
                    }
                });
            } else {
                // If no city selected, reset barangay dropdown
                if (window.addressDropdowns) {
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
                    window.addressDropdowns.barangay.updateData(allBarangays.sort());
                    
                    const originalBarangaySetValue = window.addressDropdowns.barangay.setValue;
                    window.addressDropdowns.barangay.setValue = function(value) {
                        this.selectedValue = value;
                        this.input.value = value;
                        if (value) {
                            this.input.classList.add('has-value');
                        } else {
                            this.input.classList.remove('has-value');
                        }
                    };
                    window.addressDropdowns.barangay.setValue('');
                    window.addressDropdowns.barangay.setValue = originalBarangaySetValue;
                }
            }
            
            window.addressUpdating = false;
        }

        // Function to handle province selection
        function onProvinceChange() {
            // Prevent infinite loops
            if (window.addressUpdating) return;
            window.addressUpdating = true;
            
            const provinceInput = document.getElementById('province');
            const selectedProvince = provinceInput.value;
            
            console.log('onProvinceChange called, selected province:', selectedProvince);
            
            // If a province is selected and it's in our predefined data, update city and barangay dropdowns
            if (selectedProvince && addressData[selectedProvince]) {
                if (window.addressDropdowns) {
                    // Update city dropdown with cities from this province
                    updateCityDropdown(selectedProvince);
                    
                    // Update barangay dropdown with all barangays from this province
                    const provinceBarangays = [];
                    Object.keys(addressData[selectedProvince].barangays).forEach(city => {
                        addressData[selectedProvince].barangays[city].forEach(barangay => {
                            if (!provinceBarangays.includes(barangay)) {
                                provinceBarangays.push(barangay);
                            }
                        });
                    });
                    window.addressDropdowns.barangay.updateData(provinceBarangays.sort());
                    
                    // Clear city and barangay selections without triggering events (only for predefined provinces)
                    const originalCitySetValue = window.addressDropdowns.city.setValue;
                    const originalBarangaySetValue = window.addressDropdowns.barangay.setValue;
                    
                    window.addressDropdowns.city.setValue = function(value) {
                        this.selectedValue = value;
                        this.input.value = value;
                        if (value) {
                            this.input.classList.add('has-value');
                        } else {
                            this.input.classList.remove('has-value');
                        }
                    };
                    
                    window.addressDropdowns.barangay.setValue = function(value) {
                        this.selectedValue = value;
                        this.input.value = value;
                        if (value) {
                            this.input.classList.add('has-value');
                        } else {
                            this.input.classList.remove('has-value');
                        }
                    };
                    
                    window.addressDropdowns.city.setValue('');
                    window.addressDropdowns.barangay.setValue('');
                    
                    // Clear zip code when province changes
                    const zipCodeInput = document.getElementById('zip_code');
                    if (zipCodeInput) {
                        zipCodeInput.value = '';
                        console.log('Cleared zip code due to province change');
                    }
                    
                    // Restore original setValue functions
                    window.addressDropdowns.city.setValue = originalCitySetValue;
                    window.addressDropdowns.barangay.setValue = originalBarangaySetValue;
                }
            } else if (selectedProvince) {
                // Custom province entered - don't clear city and barangay, just clear zip code
                console.log('Custom province entered:', selectedProvince, '- preserving city and barangay values');
                const zipCodeInput = document.getElementById('zip_code');
                if (zipCodeInput) {
                    zipCodeInput.value = '';
                    console.log('Cleared zip code due to custom province');
                }
            } else {
                // If no province selected, reset to all options
                if (window.addressDropdowns) {
                    // Reset to all cities
                    const allCities = [];
                    Object.keys(addressData).forEach(province => {
                        addressData[province].cities.forEach(city => {
                            if (!allCities.includes(city)) {
                                allCities.push(city);
                            }
                        });
                    });
                    window.addressDropdowns.city.updateData(allCities.sort());
                    
                    // Reset to all barangays
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
                    window.addressDropdowns.barangay.updateData(allBarangays.sort());
                    
                    // Clear selections without triggering events
                    const originalCitySetValue = window.addressDropdowns.city.setValue;
                    const originalBarangaySetValue = window.addressDropdowns.barangay.setValue;
                    
                    window.addressDropdowns.city.setValue = function(value) {
                        this.selectedValue = value;
                        this.input.value = value;
                        if (value) {
                            this.input.classList.add('has-value');
                        } else {
                            this.input.classList.remove('has-value');
                        }
                    };
                    
                    window.addressDropdowns.barangay.setValue = function(value) {
                        this.selectedValue = value;
                        this.input.value = value;
                        if (value) {
                            this.input.classList.add('has-value');
                        } else {
                            this.input.classList.remove('has-value');
                        }
                    };
                    
                    window.addressDropdowns.city.setValue('');
                    window.addressDropdowns.barangay.setValue('');
                    
                    // Clear zip code when no province is selected
                    const zipCodeInput = document.getElementById('zip_code');
                    if (zipCodeInput) {
                        zipCodeInput.value = '';
                        console.log('Cleared zip code due to no province selection');
                    }
                    
                    // Restore original setValue functions
                    window.addressDropdowns.city.setValue = originalCitySetValue;
                    window.addressDropdowns.barangay.setValue = originalBarangaySetValue;
                }
            }
            
            window.addressUpdating = false;
        }

        // Function to update barangay dropdown based on province and city
        function updateBarangayDropdown(city, province, selectedBarangay = null) {
            if (window.addressDropdowns && window.addressDropdowns.barangay) {
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
                
                window.addressDropdowns.barangay.updateData(barangays.sort());
                
                // Keep the previously selected barangay if it's still valid
                if (selectedBarangay && barangays.includes(selectedBarangay)) {
                    window.addressDropdowns.barangay.setValue(selectedBarangay);
                }
            }
        }

        // Enhanced Dropdown Class
        class EnhancedDropdown {
            constructor(inputId, optionsId, dropdownId, data, placeholder = 'Search...') {
                this.input = document.getElementById(inputId);
                this.optionsContainer = document.getElementById(optionsId);
                this.dropdown = document.getElementById(dropdownId);
                this.wrapper = this.input.closest('.enhanced-dropdown-wrapper');
                this.data = data;
                this.placeholder = placeholder;
                this.selectedValue = '';
                this.isOpen = false;
                this.isSearchMode = false;
                
                this.init();
            }
            
            init() {
                // Set initial placeholder for floating label compatibility
                this.input.placeholder = ' ';
                this.populateOptions(this.data);
                this.setupEventListeners();
                this.updateDisplay();
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
                    this.filterOptions(value);
                    
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
                            // Only accept typed value if no option was just selected
                            if (this.isSearchMode && !this.selectedValue) {
                                const value = this.input.value.trim();
                                if (value) {
                                    this.selectedValue = value;
                                    this.input.classList.add('has-value');
                                    // Trigger change event for interdependent functionality
                                    const changeEvent = new Event('change', { bubbles: true });
                                    this.input.dispatchEvent(changeEvent);
                                }
                            }
                            this.exitSearchMode();
                            this.close();
                        }
                    }, 150);
                });
                
                // Close dropdown when clicking outside and accept custom entries
                document.addEventListener('click', (e) => {
                    if (!this.wrapper.contains(e.target)) {
                        // Only accept typed value if no option was just selected
                        if (this.isSearchMode && !this.selectedValue) {
                            const value = this.input.value.trim();
                            if (value) {
                                this.selectedValue = value;
                                this.input.classList.add('has-value');
                                // Trigger change event for interdependent functionality
                                const changeEvent = new Event('change', { bubbles: true });
                                this.input.dispatchEvent(changeEvent);
                            }
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
            
            filterOptions(searchTerm) {
                const filteredData = this.data.filter(item => 
                    item.toLowerCase().includes(searchTerm.toLowerCase())
                );
                
                this.optionsContainer.innerHTML = '';
                
                if (filteredData.length === 0) {
                    const noResults = document.createElement('div');
                    noResults.className = 'dropdown-option no-results';
                    noResults.textContent = 'No results found';
                    this.optionsContainer.appendChild(noResults);
                } else {
                    filteredData.forEach(item => {
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
                
                // Trigger change event for existing functionality
                const changeEvent = new Event('change', { bubbles: true });
                this.input.dispatchEvent(changeEvent);
                
                // Also trigger input event to ensure all listeners are notified
                const inputEvent = new Event('input', { bubbles: true });
                this.input.dispatchEvent(inputEvent);
            }
            
            // Method to get current value (either selected from dropdown or custom typed)
            getValue() {
                return this.input.value.trim() || this.selectedValue;
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
                
                // Trigger change event when value is set programmatically
                const changeEvent = new Event('change', { bubbles: true });
                this.input.dispatchEvent(changeEvent);
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
                this.populateOptions(this.data);
            }
            
            close() {
                this.isOpen = false;
                this.wrapper.classList.remove('active');
                this.clearSelection();
            }
            
            exitSearchMode() {
                this.isSearchMode = false;
                if (this.selectedValue) {
                    this.input.value = this.selectedValue;
                } else {
                    this.input.value = '';
                }
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
            
            // Initialize enhanced dropdowns
            const barangayDropdown = new EnhancedDropdown(
                'barangay', 
                'barangay-options', 
                'barangay-dropdown', 
                allBarangays.sort(), 
                'Search barangays...'
            );
            
            const cityDropdown = new EnhancedDropdown(
                'municipality_city', 
                'city-options', 
                'city-dropdown', 
                allCities.sort(), 
                'Search cities...'
            );
            
            const provinceDropdown = new EnhancedDropdown(
                'province', 
                'province-options', 
                'province-dropdown', 
                allProvinces.sort(), 
                'Search provinces...'
            );
            
            // Store dropdown instances globally for the existing functions
            window.addressDropdowns = {
                barangay: barangayDropdown,
                city: cityDropdown,
                province: provinceDropdown
            };
            
            // Set up change event listeners for existing functionality
            document.getElementById('province').addEventListener('change', onProvinceChange);
            document.getElementById('municipality_city').addEventListener('change', onCityChange);
            document.getElementById('barangay').addEventListener('change', onBarangayChange);
            
            console.log('Enhanced address filtering setup complete!');
        }
        
        // Enhanced updateCityDropdown function
        function updateCityDropdown(province) {
            if (window.addressDropdowns && window.addressDropdowns.city) {
                let cities = [];
                
                if (province && addressData[province]) {
                    cities = addressData[province].cities;
                } else {
                    // Show all cities if no province selected
                    Object.keys(addressData).forEach(prov => {
                        addressData[prov].cities.forEach(city => {
                            if (!cities.includes(city)) {
                                cities.push(city);
                            }
                        });
                    });
                }
                
                window.addressDropdowns.city.updateData(cities.sort());
            }
        }
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupAddressFiltering);
        } else {
            setupAddressFiltering();
        }

        // Initialize validation
        
        setupValidation();
    });
</script>



</body>

</html>