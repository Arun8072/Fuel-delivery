<?php
header('Content-Type: application/json');

// Disable error reporting for production
error_reporting(0);
ini_set('display_errors', 0);

// Function to send JSON response
function send_json_response($success, $message = '', $data = null) {
    $response = [
        'success' => $success,
        'message' => $message,
    ];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
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
    send_json_response(false, 'All fields are required');
}

// Load existing data
$jsonFile = 'orders.json';
$orders = [];
if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    if ($jsonContent !== false) {
        $orders = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            send_json_response(false, 'Error reading existing orders');
        }
    } else {
        send_json_response(false, 'Error reading orders file');
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
if (file_put_contents($jsonFile, json_encode($orders, JSON_PRETTY_PRINT)) !== false) {
    send_json_response(true, 'Order submitted successfully', $newOrder);
} else {
    send_json_response(false, 'Failed to save order');
}
