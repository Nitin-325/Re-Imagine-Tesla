<?php

include 'config.php';

session_start();

// Check if session exists before destroying
if(session_status() === PHP_SESSION_ACTIVE) {
    session_unset();
    session_destroy();
}

// Add exit after redirect to prevent further execution
header('location:login.php');
exit();

?>