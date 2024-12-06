<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = "webdev.iyaserver.com";
$userid = "twilcher_hudson_endless";
$userpw = "VanGogh12!";
$db = "twilcher_endless";

$pdo = new mysqli($host, $userid, $userpw, $db);
if ($pdo->connect_errno) {
    echo "Database connection error: " . htmlspecialchars($pdo->connect_error);
    exit();
}

$userId = $_SESSION['user_id'];

$firstName = isset($_POST['firstname']) ? trim($_POST['firstname']) : null;
$lastName = isset($_POST['lastname']) ? trim($_POST['lastname']) : null;
$bio = isset($_POST['bio']) ? trim($_POST['bio']) : null;


$stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, bio = ? WHERE id = ?");
$stmt->bind_param("sssi", $firstName, $lastName, $bio, $userId);

if ($stmt->execute()) {
    // Redirect back to the dashboard page with a success message
    header("Location: dashboard.php?update=success");
    exit();
} else {
    // Redirect back to dashboard page with an error message
    header("Location: dashboard.php?update=error");
    exit();
}


$stmt->close();
$pdo->close();
?>
