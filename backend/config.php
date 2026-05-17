<?php

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'shop_db';

// Attempt to connect to database
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection and handle errors
if (!$conn) {
    error_log("Database connection failed: " . mysqli_connect_error());
    die('Database connection failed. Please try again later.');
}

// Set charset to UTF-8
if (!mysqli_set_charset($conn, "utf8mb4")) {
    error_log("Error setting charset: " . mysqli_error($conn));
}

?>