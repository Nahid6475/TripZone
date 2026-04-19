<?php
require_once 'connection.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'bookings' => []]);
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM bookings WHERE user_id = $user_id ORDER BY id DESC");

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode(['success' => true, 'bookings' => $bookings]);
$conn->close();
?>