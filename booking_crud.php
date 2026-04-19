<?php
require_once 'connection.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$package_name = $data['package_name'] ?? '';
$customer_name = $data['customer_name'] ?? '';
$customer_email = $data['customer_email'] ?? '';
$customer_phone = $data['customer_phone'] ?? '';
$travel_date = $data['travel_date'] ?? '';
$number_of_people = $data['number_of_people'] ?? 1;
$special_requests = $data['special_requests'] ?? '';
$user_id = $_SESSION['user_id'];

if (empty($package_name) || empty($customer_name) || empty($customer_email) || empty($travel_date)) {
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO bookings (package_name, customer_name, customer_email, customer_phone, travel_date, number_of_people, special_requests, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssisi", $package_name, $customer_name, $customer_email, $customer_phone, $travel_date, $number_of_people, $special_requests, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Booking confirmed!', 'booking_id' => $conn->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Booking failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>