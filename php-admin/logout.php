<?php
include 'includes/config.php';
include 'includes/auth.php';
include 'includes/functions.php';

if (isLoggedIn()) {
    logAction('Logout', "User {$_SESSION['user_id']} logged out");
}

session_destroy();
header('Location: login.php');
exit();
?>