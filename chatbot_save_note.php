<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$note = $data['note'] ?? '';
$user_id = $data['user_id'] ?? 0;

if (empty($note) || empty($user_id)) {
    echo json_encode(['success' => false]);
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'tripzone_crud_db');
$stmt = $conn->prepare("INSERT INTO user_notes (user_id, content) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $note);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
$conn->close();
?>