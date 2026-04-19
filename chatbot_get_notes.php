<?php
header('Content-Type: application/json');

$user_id = $_GET['user_id'] ?? 0;

if (empty($user_id)) {
    echo json_encode(['success' => false, 'notes' => []]);
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'tripzone_crud_db');
$result = $conn->query("SELECT id, content, created_at FROM user_notes WHERE user_id = $user_id ORDER BY created_at DESC");

$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

echo json_encode(['success' => true, 'notes' => $notes]);
$conn->close();
?>