<?php
header('Content-Type: application/json');

$index = filter_input(INPUT_POST, 'index', FILTER_VALIDATE_INT);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

if ($index === false || $index === null || !in_array($status, ['accepted', 'outForDelivery'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$jsonFile = 'orders.json';
if (file_exists($jsonFile)) {
    $orders = json_decode(file_get_contents($jsonFile), true);
    
    if (isset($orders[$index])) {
        if ($status === 'accepted') {
            $orders[$index]['accepted'] = true;
        } elseif ($status === 'outForDelivery') {
            $orders[$index]['outForDelivery'] = true;
        }
        
        if (file_put_contents($jsonFile, json_encode($orders, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update order status']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No orders found']);
}