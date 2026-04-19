<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$note_id = $data['note_id'] ?? 0;
$content = $data['content'] ?? '';
$user_id = $data['user_id'] ?? 0;

$conn = new mysqli('localhost', 'root', '', 'tripzone_crud_db');
$stmt = $conn->prepare("UPDATE user_notes SET content = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param("sii", $content, $note_id, $user_id);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
$conn->close();
?>