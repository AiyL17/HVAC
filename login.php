<?php

include 'config/ini.php';
include 'class/userClass.php';

$userClass = new userClass();
$pdo = pdo_init();

if (!empty($_SESSION['uid'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log In | HVAC </title>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0">
    <link rel=" icon" href="img/icon.png">
    <?php
    include 'includes/cdn.php';
    ?>

    <link rel="stylesheet" href="css/style.css">
    <script>
        $(document).ready(function () {
            $('#togglePassword').click(function () {
                const eyeIcon = $(this);
                const pwdInput = $('.show-hide-password');

                pwdInput.off('blur');

                if (eyeIcon.hasClass('fa-eye')) {
                    eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                    pwdInput.attr('type', 'password').focus();


                } else {
                    eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                    pwdInput.attr('type', 'text').focus();



                }
            });
        })
    </script>
</head>

<body class="">
    <div class="text-dark vh-100  d-flex align-items-center">
        <div class="container col-md-8   round_md ">

            <div class="row justify-content-center">
                <div class="col-md-6 round_md m left align-items-center d-flex">
                    <div class="">

                        <div class="ms-sm-5 ms-2  ">

 <div class="d-flex gap-1">
                            <div class="m-0 p-0 bg-dark-subtle col-5 p-1 round_lg"></div>

                            <div class="m-0 p-0 bg-dark-subtle col-1 p-1 round_lg"></div>
                            </div>

                            <div class="d-flex align-items-end">
                                <h1 class="  p-0">

                                    <b>
                                        <span style="font-size: calc(3.0rem + 3.2vw);">H<span class="text-primary"
                                               >VAC</span></span>
                                    </b>
                                </h1>
                            </div>
                            <!-- <h5 class="m-0 fst-italic  " >"Home of Happy People"</h5> -->
                            <h6 class=" " style="color:#212529;"><span class="fw-bold">
                                    <i class="bi bi-gear"></i></span> &nbsp;|&nbsp; Air Conditioning and Refrigeration Services</h6>

                            <br>



                        </div>



                    </div>
                </div>
                <div class="col-md-5  d-flex align-items-center">


                    <div class="w-100 " id="loginContainer">
                        <div class="  w-100 pt-5 pb-5">
                            <div id="login" class="p-sm-4 p-2  ">


                                <div class="d-flex align-items-center pb-2 mb-2 ">
                                    <h3 class=" p-0 m-0"><b>Log In</b></h3>
                                    <div>
                                        <div id="loader" class="ms-3 loader collapse small"></div>
                                    </div>
                                </div>
                                <div id="errorMsgLogin"
                                    class="collapse  text-center   bg-opacity-25 round_md "></div>

                                <form id="loginForm" class="auth-form collapse show" name="login">

                                    <div class="form-floating border-0 mt-2">
                                        <input type="text" class="round_md  border-0 form-control mb-2 " id="username"
                                            name="username" autocomplete="off" placeholder="Username" required>
                                        <label for="floatingInput">Username</label>
                                    </div>
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="w-100 form-floating">
                                            <input type="password" id="password"
                                                class="round_md border-0 form-control  show-hide-password round"
                                                name="password" autocomplete="off" placeholder="Password" required />
                                            <label for="floatingInput">Password</label>
                                        </div>
                                        <span class="fas fa-eye-slash" id="togglePassword"
                                            style="margin-left:-40px;z-index:1"></span>
                                    </div>

                                    <!-- Remember Password Checkbox -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="rememberPassword" name="rememberPassword">
                                        <label class="form-check-label small text-muted" for="rememberPassword">
                                            Remember my password
                                        </label>
                                    </div>

                                    <input type="submit" id="submit-btn"
                                        class="w-100 btn border-0 p-2 round_lg btn-primary" name="loginSubmit"
                                        value="Login">
                                </form>

                            </div>

                            <div class="text-center small pt-2">
                                <p>Don't have an Account? <a class="text-purple " href="register.php">Click Here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- <p class="  small text-dark  text-body-secondary pe-sm-4 text-sm-center text-center" style="font-size:9pt;margin-top:-35px"><b><a class="text-dark " href="https://web.facebook.com/ian.divinagracia.56">HVAC</a></b> | Alrights Reserved </p> -->

</body>
<script>
    document.getElementById('loginForm').addEventListener('submit', async function (event) {
        event.preventDefault();

        // Validate form fields
        const requiredInputs = document.querySelectorAll('#loginForm input[required]');
        let isValid = true;

        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            return;
        }

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const rememberPassword = document.getElementById('rememberPassword').checked;
        const errorMsgLogin = document.getElementById('errorMsgLogin');

        const errorMsgCollapse = new bootstrap.Collapse(errorMsgLogin, {
            toggle: false
        });
        const loader = new bootstrap.Collapse(document.getElementById('loader'), {
            toggle: false
        });
        const formCollapse = new bootstrap.Collapse(document.getElementById('loginForm'), {
            toggle: false
        });

        loader.show();

        errorMsgCollapse.hide();
        formCollapse.hide();
        const response = await fetch('api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username,
                password: password,
                rememberPassword: rememberPassword
            })
        });

        const data = await response.json();

        if (data.success) {
            // Save credentials if remember password is checked
            if (typeof window.saveCredentials === 'function') {
                window.saveCredentials(username, password);
            }

            errorMsgLogin.classList.remove("bg-danger");

            errorMsgLogin.classList.add("bg-success");

            errorMsgLogin.innerHTML = `<p class="small m-0 p-2"> Success</p>`;
            setTimeout(() => {
                errorMsgCollapse.show();
                loader.hide();

            }, 500);
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
        } else {
            errorMsgLogin.classList.remove("bg-success");

            errorMsgLogin.classList.add("bg-danger");
            errorMsgLogin.innerHTML = `<p class="small m-0 p-2"> ${data.message}</p>`;
            setTimeout(() => {
                errorMsgCollapse.show();
                formCollapse.show();
                loader.hide();

            }, 1000);
        }

    });

    // Remember Password Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const rememberCheckbox = document.getElementById('rememberPassword');

        // Load saved credentials on page load
        loadSavedCredentials();

        function loadSavedCredentials() {
            const savedUsername = localStorage.getItem('hvac_remembered_username');
            const savedPassword = localStorage.getItem('hvac_remembered_password');
            
            if (savedUsername && savedPassword) {
                usernameInput.value = savedUsername;
                passwordInput.value = savedPassword;
                rememberCheckbox.checked = true;
            }
        }

        // Save credentials when remember is checked and login is successful
        function saveCredentials(username, password) {
            if (rememberCheckbox.checked) {
                localStorage.setItem('hvac_remembered_username', username);
                localStorage.setItem('hvac_remembered_password', password);
            } else {
                // Clear saved credentials if remember is unchecked
                localStorage.removeItem('hvac_remembered_username');
                localStorage.removeItem('hvac_remembered_password');
            }
        }

        // Clear credentials when checkbox is unchecked
        rememberCheckbox.addEventListener('change', function() {
            if (!this.checked) {
                localStorage.removeItem('hvac_remembered_username');
                localStorage.removeItem('hvac_remembered_password');
            }
        });

        // Expose saveCredentials function globally so it can be called from the login handler
        window.saveCredentials = saveCredentials;
    });
</script>

</html>
