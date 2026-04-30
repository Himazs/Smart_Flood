<?php
include 'includes/config.php';
include 'includes/auth.php';

if (isLoggedIn()) {
    if ($_SESSION['user_type'] == 'admin') {
        header('Location: admin/dashboard.php');
    } else if ($_SESSION['user_type'] == 'government') {
        header('Location: government/dashboard.php');
    } else {
        header('Location: login.php');
    }
    exit();
} else {
    header('Location: login.php');
    exit();
}
?>