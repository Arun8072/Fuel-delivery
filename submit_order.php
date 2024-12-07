<?php
header('Content-Type: application/json');

// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

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
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Load existing data
$jsonFile = 'orders.json';
$orders = [];
if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    if ($jsonContent === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to read orders file']);
        exit;
    }
    $orders = json_decode($jsonContent, true);
    if ($orders === null && json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to parse orders JSON']);
        exit;
    }
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
if (file_put_contents($jsonFile, json_encode($orders, JSON_PRETTY_PRINT)) === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save order']);
    exit;
}

echo json_encode(['success' => true]);
