<?php
if (isset($_GET['action'])) {

   include 'appointment-action.php';
} else {
    include 'appointment-list.php';

}
exit();

?>


