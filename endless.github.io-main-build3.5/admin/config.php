<?php
// Database configuration
$host = 'webdev.iyaserver.com';
$username = 'twilcher_endless';
$password = 'VanGogh12!';
$database = 'twilcher_endless';

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");
?>