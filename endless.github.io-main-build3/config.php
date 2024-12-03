<?php
$servername = "webdev.iyaserver.com";
$username = "twilcher";
$password = "AcadDev_Wilcher_6801994716";
$dbname = "twilcher_endless";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");