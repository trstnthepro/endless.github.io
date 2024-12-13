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
$piece_id = $_POST['piece_id'] ?? '';
$action = $_POST['action'] ?? '';

if (!$piece_id || !$action) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

// Get current favorites
$stmt = $pdo->prepare("SELECT favorites FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Parse JSON favorites
$favorites = json_decode($user['favorites'] ?? '[]', true) ?: [];

if ($action === 'add') {
    if (!in_array($piece_id, $favorites)) {
        $favorites[] = $piece_id;
    }
} else if ($action === 'remove') {
    $favorites = array_values(array_diff($favorites, [$piece_id]));
}

// Convert back to JSON
$favorites_json = json_encode(array_values($favorites));

// Update database
$stmt = $pdo->prepare("UPDATE users SET favorites = ? WHERE id = ?");
$stmt->bind_param("si", $favorites_json, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Update failed']);
}
?>