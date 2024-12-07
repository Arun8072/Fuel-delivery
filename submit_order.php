<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw POST data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate data
    if (isset($data['username'], $data['mobile'], $data['location'], $data['payment'], $data['petrolQuantity'], $data['dieselQuantity'], $data['timestamp'])) {
        // Prepare data to write to JSON file
        $orderData = [
            'username' => $data['username'],
            'mobile' => $data['mobile'],
            'location' => $data['location'],
            'payment' => $data['payment'],
            'petrolQuantity' => $data['petrolQuantity'],
            'dieselQuantity' => $data['dieselQuantity'],
            'timestamp' => $data['timestamp'],
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
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    }
} else {
    // Handle invalid request method
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
