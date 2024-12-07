<?php
header('Content-Type: application/json');

// Validate and sanitize input
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$mobile = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_STRING);
$location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
$payment = filter_input(INPUT_POST, 'payment', FILTER_SANITIZE_STRING);
$fuel_type = filter_input(INPUT_POST, 'fuel_type', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_FLOAT);
$timestamp = filter_input(INPUT_POST, 'timestamp', FILTER_SANITIZE_STRING);

// Validate required fields
if (!$username || !$mobile || !$location || !$payment || !$fuel_type || !$quantity || !$timestamp) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Load existing data
$jsonFile = 'orders.json';
$orders = [];
if (file_exists($jsonFile)) {
    $orders = json_decode(file_get_contents($jsonFile), true);
}

// Add new order
$newOrder = [
    'username' => $username,
    'mobile' => $mobile,
    'location' => $location,
    'payment' => $payment,
    'fuel_type' => $fuel_type,
    'quantity' => $quantity,
    'timestamp' => $timestamp,
    'accepted' => false,
    'outForDelivery' => false,
    'delivered' => false
];

$orders[] = $newOrder;

// Save updated data
if (file_put_contents($jsonFile, json_encode($orders, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save order']);
}