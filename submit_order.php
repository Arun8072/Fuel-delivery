<?php
// Set the response content type to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $inputData = file_get_contents('php://input');

    // Decode the JSON data into a PHP associative array
    $data = json_decode($inputData, true);

    // Check if the JSON data was successfully decoded
    if ($data === null) {
        // If decoding fails, return an error response
        echo json_encode(['success' => false, 'message' => 'Invalid JSON format']);
        exit;
    }

    // Validate required fields
    if (isset($data['username'], $data['mobile'], $data['location'], $data['payment'], $data['petrolQuantity'], $data['dieselQuantity'], $data['timestamp'])) {
        // If validation passes, process the order
        $orderData = [
            'username' => $data['username'],
            'mobile' => $data['mobile'],
            'location' => $data['location'],
            'payment' => $data['payment'],
            'petrolQuantity' => $data['petrolQuantity'],
            'dieselQuantity' => $data['dieselQuantity'],
            'timestamp' => $data['timestamp'],
        ];

        // Define the path to your JSON file
        $filePath = 'orders.json';

        // Read the existing data from the JSON file
        $existingData = [];
        if (file_exists($filePath)) {
            $jsonContent = file_get_contents($filePath);
            $existingData = json_decode($jsonContent, true) ?? [];
        }

        // Append the new order to the existing data
        $existingData[] = $orderData;

        // Write the updated data back to the JSON file
        if (file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT))) {
            // Return a success response
            echo json_encode(['success' => true, 'message' => 'Order saved successfully']);
        } else {
            // If writing to the file fails, return an error response
            echo json_encode(['success' => false, 'message' => 'Failed to save order']);
        }
    } else {
        // If required fields are missing, return an error response
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    }
} else {
    // If the request method is not POST, return an error
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
