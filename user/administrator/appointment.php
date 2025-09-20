<?php
if (isset($_GET['customer'])) {

    include 'appointment-view.php';
} else if (isset($_GET['tech-history'])  ) {

    include 'appointment-history-list.php';
} else if (isset($_GET['history'])|| isset($_GET['user-history'])) {

    include 'appointment-history-view.php';
} else {
    include 'appointment-list.php';

}
exit();

?>