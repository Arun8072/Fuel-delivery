<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Fuel Delivery Service</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-6">Admin Panel - Fuel Delivery Service</h1>
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">Username</th>
                    <th class="px-4 py-2">Mobile</th>
                    <th class="px-4 py-2">Location</th>
                    <th class="px-4 py-2">Payment</th>
                    <th class="px-4 py-2">Fuel Type</th>
                    <th class="px-4 py-2">Petrol Quantity</th>
                    <th class="px-4 py-2">Diesel Quantity</th>
                    <th class="px-4 py-2">Timestamp</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $jsonFile = 'orders.json';
                if (file_exists($jsonFile)) {
                    $orders = json_decode(file_get_contents($jsonFile), true);
                    usort($orders, function($a, $b) {
                        return strtotime($a['timestamp']) - strtotime($b['timestamp']);
                    });

                    foreach ($orders as $index => $order) {
                        echo "<tr>";
                        echo "<td class='border px-4 py-2'>{$order['username']}</td>";
                        echo "<td class='border px-4 py-2'>{$order['mobile']}</td>";
                        echo "<td class='border px-4 py-2'>{$order['location']}</td>";
                        echo "<td class='border px-4 py-2'>{$order['payment']}</td>";
                        echo "<td class='border px-4 py-2'>" .$order['fuelType']. "</td>";
                        echo "<td class='border px-4 py-2'>{$order['petrolQuantity']} L</td>";
                        echo "<td class='border px-4 py-2'>{$order['dieselQuantity']} L</td>";
                        echo "<td class='border px-4 py-2'>{$order['timestamp']}</td>";
                        echo "<td class='border px-4 py-2'>";
                        if (!$order['hasAccepted']) {
                            echo "<button onclick='updateStatus($index, \"hasAccepted\")' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-2'>Accept</button>";
                        }
                        if ($order['hasAccepted'] && !$order['hasOutForDelivery']) {
                            echo "<button onclick='updateStatus($index, \"hasOutForDelivery\")' class='bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded'>Out for Delivery</button>";
                        }
                        if ($order['hasAccepted'] && $order['hasOutForDelivery'] && !$order['hasDelivered']) {
                            echo "<button onclick='updateStatus($index, \"hasDelivered\")' class='bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded'>Delivered</button>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
    async function updateStatus(index, status) {
        try {
            const response = await fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `index=${index}&status=${status}`
            });

            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    alert('Status updated successfully');
                    location.reload();
                } else {
                    alert('Failed to update status: ' + result.message);
                }
            } else {
                throw new Error('Network response was not ok.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while updating the status. Please try again.');
        }
    }
    </script>
</body>
</html>