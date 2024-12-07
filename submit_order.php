<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set headers
    header('Content-Type: application/json');

    // Get POST data
    $petrolQuantity = isset($_POST['petrolQuantity']) ? (float)$_POST['petrolQuantity'] : null;
    $dieselQuantity = isset($_POST['dieselQuantity']) ? (float)$_POST['dieselQuantity'] : null;
    $timestamp = isset($_POST['timestamp']) ? $_POST['timestamp'] : null;

    // Validate data
    if ($petrolQuantity === null || $dieselQuantity === null || $timestamp === null) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }

    // Prepare data to write to JSON file
    $orderData = [
        'petrolQuantity' => $petrolQuantity,
        'dieselQuantity' => $dieselQuantity,
        'timestamp' => $timestamp
    ];

    // File path
    $filePath = 'orders.json';

    // Read existing data from JSON file
    $existingData = [];
    if (file_exists($filePath)) {
        $jsonContent = file_get_contents($filePath);
        $existingData = json_decode($jsonContent, true) ?? [];
    }

    // Append new data
    $existingData[] = $orderData;

    // Write data back to JSON file
    if (file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true, 'message' => 'Order saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save order']);
    }
} else {
    // Handle invalid request method
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
