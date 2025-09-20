<!-- <style>
    /* Assigned Background */
.bg-complete {
  background: linear-gradient(45deg, rgb(49, 198, 209) 0%,rgb(140, 219, 248) 100%);
}

/* In Progress Background */
.bg-inprogress {
  background: linear-gradient(45deg, #fe8a96 0%, #ffba96 100%);
}

/* Complete Background */
.bg-assigned {
    background: linear-gradient(45deg, #3096e7 0%, #8cc8f8 100%);

}

</style> -->

<?php
if (isset($_GET['customer'])) {

   include 'task-view.php';
} else {
    include 'task-list.php';

}
exit();

?>


