<?php
require_once 'connection.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$booking_id = $data['booking_id'] ?? 0;
$travel_date = $data['travel_date'] ?? '';
$number_of_people = $data['number_of_people'] ?? 1;
$special_requests = $data['special_requests'] ?? '';
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE bookings SET travel_date = ?, number_of_people = ?, special_requests = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param("sisii", $travel_date, $number_of_people, $special_requests, $booking_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Booking updated!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed']);
}

$stmt->close();
$conn->close();
?>