<?php
// Get the raw POST data
$rawData = file_get_contents("php://input");

// Decode the JSON data into an associative array
$data = json_decode($rawData, true);

// Check if data was received
if ($data) {
    // Extract individual fields from the data
    $username = $data['username'];
    $mobile = $data['mobile'];
    $location = $data['location'];
    $payment = $data['payment'];
    $petrolQuantity = $data['petrolQuantity'];
    $dieselQuantity = $data['dieselQuantity'];
    $timestamp = $data['timestamp'];

    // Example: process the order, save to database or perform any other action
    // For demonstration purposes, let's assume the order is successfully processed.

    // Send a response back to the front-end
    $response = [
        'success' => true,
        'message' => 'Order successfully submitted.',
        'orderData' => $data
    ];
} else {
    // If no data was received or decoding failed
    $response = [
        'success' => false,
        'message' => 'Failed to receive order data.'
    ];
}

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
