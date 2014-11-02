
<?php
session_start();
if (!isset($_SESSION['zpuid'])) {
    die("<h1>Unauthorized request!</h1> Request not accessible outside!");
}
?>
