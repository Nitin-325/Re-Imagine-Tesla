<?php
include 'config.php';

// Create message table
$create_message_table = "CREATE TABLE IF NOT EXISTS `message` (
    `id` int(100) NOT NULL AUTO_INCREMENT,
    `user_id` int(100) NOT NULL,
    `name` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL, 
    `number` varchar(12) NOT NULL,
    `message` text NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Execute query and handle errors
if (!mysqli_query($conn, $create_message_table)) {
    error_log("Error creating message table: " . mysqli_error($conn));
    die('Error creating message table. Please try again later.');
}

// Create orders table
$create_orders_table = "CREATE TABLE IF NOT EXISTS `orders` (
    `id` int(100) NOT NULL AUTO_INCREMENT,
    `user_id` int(100) NOT NULL,
    `name` varchar(100) NOT NULL,
    `number` varchar(12) NOT NULL,
    `email` varchar(100) NOT NULL,
    `method` varchar(50) NOT NULL,
    `address` varchar(500) NOT NULL,
    `total_products` varchar(1000) NOT NULL,
    `total_price` decimal(10,2) NOT NULL,
    `placed_on` varchar(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Execute query and handle errors
if (!mysqli_query($conn, $create_orders_table)) {
    error_log("Error creating orders table: " . mysqli_error($conn));
    die('Error creating orders table. Please try again later.');
}

// Create cart table
$create_cart_table = "CREATE TABLE IF NOT EXISTS `cart` (
    `id` int(100) NOT NULL AUTO_INCREMENT,
    `user_id` int(100) NOT NULL,
    `name` varchar(100) NOT NULL,
    `price` decimal(10,2) NOT NULL,
    `quantity` int(100) NOT NULL,
    `image` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Execute query and handle errors
if (!mysqli_query($conn, $create_cart_table)) {
    error_log("Error creating cart table: " . mysqli_error($conn));
    die('Error creating cart table. Please try again later.');
}
?>