<?php
session_start();
if (isset($_SESSION['provider_id'])) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}
exit();
?>