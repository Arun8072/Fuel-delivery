<?php
header('Content-Type: application/json');

$jsonFile = 'orders.json';
if (file_exists($jsonFile)) {
    $orders = json_decode(file_get_contents($jsonFile), true);
    $latestOrder = end($orders);
    
    echo json_encode([
        'accepted' => $latestOrder['accepted'],
        'outForDelivery' => $latestOrder['outForDelivery'],
        'delivered' => $latestOrder['delivered']
    ]);
} else {
    echo json_encode(['error' => 'No orders found']);
}