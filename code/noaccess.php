<?php
session_start();
if (!isset($_SESSION['zpuid'])) {
    die("<h1>Unauthorised request!</h1> Request not accessible outside!");
}
?>