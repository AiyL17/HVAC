<nav class="navbar navbar-expand-md navbar-light sticky-top bg-nav    " id="nav">

    <div class="d-flex w-100 align-items-center  ">
        <!-- <button class="btn  ms-2  " id="opnbtn" type="button" onclick="toggleNav()"><i
                class="bi h5 bi-list"></i></button> -->
        <div id="opnbtn"></div>

                <button class="btn  ms-2 " type="button" onclick="toggleNav()"><i class="bi fs-5 bi-list"></i>

</button>



        <a href="#" class="navbar-brand me-5 ">
            <div class="d-flex align-items-center">
                <img src="./img/ic_logo.png" class="mb-1
                 me-sm-3 me-lg-2  ms-sm-2" width="30" alt="">
                 <h4 class="mt-1 fw-bold"> <span class="text-purple"> IC</span></h4>


            </div>

        </a>
    
        <!-- <a href="#" class="navbar-brand me-5 ps-2">
            <div class="d-flex align-items-center">
                <img src="./img/logo.png" class="
                 me-3" width="40" alt="">
                 <h3 class="mt-1"> <span class="text-purple"> <b>IC </b></span></h3>


            </div>

        </a> -->
                <?php
                if (!empty($adminDetails->username)) { ?>

                    <div class="d-flex align-items-center w-100 p-2 ps-3 me-2">
                        
                        <!-- Notification Icon -->
                        <div class="dropdown me-3">
                            <button class="btn btn-light position-relative" type="button" id="notificationDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false" onclick="markNotificationsAsRead()">
                                <i class="bi bi-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" 
                                      id="notification-count" style="display: none;">0</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="notificationDropdown" 
                                 style="width: 350px; max-height: 400px; overflow-y: auto;">
                                <div class="dropdown-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Notifications</h6>
                                    <button class="btn btn-sm btn-outline-primary" onclick="clearAllNotifications()">
                                        <i class="bi bi-trash"></i> Clear All
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

                        <!-- User Dropdown -->
                        <div class="dropdown">
                            <button class="btn text-light ps-3 p-1 round_md align-items-center d-flex toggle" type="button"
                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <small class="text-dark" style="font-weight:600">
                                    <?= $adminDetails->username; ?>
                                </small>
                                <img class="ms-2 bg-light border p-1 rounded-circle" width="25" src="./img/user.png" alt="">
                            </button>
                            <ul class="dropdown-menu round_md dropdown-menu-end round me-2" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item small" href="admin_logout.php">Logout</a></li>
                            </ul>
                        </div>

                    </div>

                <?php }
                ?>

        



    </div>
</nav>