<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$host = "webdev.iyaserver.com";
$userid = "twilcher_hudson_endless";
$userpw = "VanGogh12!";
$db = "twilcher_endless";

$pdo = new mysqli($host, $userid, $userpw, $db);

if ($pdo->connect_errno) {
    echo json_encode(['success' => false, 'error' => 'Database connection error']);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT favorites FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$favorites = json_decode($user['favorites'] ?? '[]', true) ?: [];

echo json_encode(['success' => true, 'favorites' => $favorites]);
?>