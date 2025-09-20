<div id="mySidebar" class=" sidebar mt-0 ">
    <div class="inner-sidebar " id="innderSidebar">
        <div id="sidebarlogo" class=" d-flex  align-items-center ps-1 pt-2 pb-1  ">
            <button class="btn  me-2  text-dark btn-light rounded-pill border-0" style="display: none;" id="closebtn"
                type="button" onclick="toggleNav()"> <i class="bi  bi-list-nested fw-bold fs-3 "></i>

            </button>
        </div>

        <div class="mt-4 pt-5 mobile-spacing-fix">
            <?php

            //sidebar items
            
            $userDetails = $userClass->userDetails($_SESSION['uid']);
            $user_type = $userDetails->user_type;

            if ($user_type == 'administrator') {
                $pages = [
                    'dashboard' => 'bi bi-grid-1x2-fill',
                    'appointment' => 'bi bi-calendar2-check-fill',
                    'schedule' => 'bi bi-calendar3',
                    'invoice' => 'bi bi-receipt-cutoff',
                    'service-management' => 'bi bi-gear-fill', // Service Management link
                    'user' => 'bi bi-people-fill',
                    'sales' => 'bi bi-bar-chart-line-fill',
                ];
            } else if ($user_type == 'staff') {
                $pages = [
                    'dashboard' => 'bi bi-grid-1x2-fill',
                    'appointment' => 'bi bi-calendar2-check-fill',
                    'schedule' => 'bi bi-calendar3',
                    'invoice' => 'bi bi-receipt-cutoff',
                    'user' => 'bi bi-people-fill',
                ];
            } else if ($user_type == 'technician') {
                $pages = [
                    'dashboard' => 'bi bi-grid-1x2-fill',
                    'task' => 'bi bi-wrench-adjustable-circle-fill',
                    'schedule' => 'bi bi-calendar3',
                    'statistics' => 'bi bi-graph-up-arrow',
                ];
            } else if ($user_type == 'customer') {
                $pages = [
                    'dashboard' => 'bi bi-house-fill',
                    'appointment' => 'bi bi-calendar2-check-fill',
                    'schedule' => 'bi bi-calendar3',
                    'invoice' => 'bi bi-receipt-cutoff',
                    'analytics' => 'bi bi-graph-up',
                ];
            }
             else {
                $pages = [
                    'dashboard' => 'bi bi-house-fill',
                    'planholder' => 'bi bi-person-circle',
                    'receipts' => 'bi-file-earmark-text-fill',

                ];
            }

            // Use $current_page from index.php if set, otherwise fallback
            $current_page = $current_page ?? ($_GET['page'] ?? 'dashboard');
            ?>

            <?php foreach (
                $pages as $page => $icon): ?>
                <?php $selected = ($current_page == $page) ? 'selected' : ''; ?>
                <a href="index.php?page=<?php echo $page; ?><?php
                   echo ($page == 'task') ? '&status=all' : '';
                   echo ($user_type == 'customer' && $page == 'appointment') ? '&type=all' : '';
                   ?>" class="sidebar-item round_lg px-3 <?php echo $selected; ?>">
                    <i class="me-3 <?php echo $icon; ?>"></i>
                    <span class="sidebar-item-text collapse show"><?php echo ($page === 'sales' ? 'Report and Analytics' : ucfirst($page === 'service-management' ? 'services' : $page)); ?></span>
                </a>

            <?php endforeach; ?>


        </div>


    </div>
</div>