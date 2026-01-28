<?php
require __DIR__ . '/Instamojo.php'; // Include your SDK

// Get data sent by Instamojo after payment
$payment_request_id = $_GET['payment_request_id'] ?? '';
$payment_id = $_GET['payment_id'] ?? '';

if(!$payment_request_id || !$payment_id){
    die("Invalid payment response.");
}

// Initialize Instamojo
$api = new Instamojo\Instamojo(
    'YOUR_API_KEY',           // Replace with your API Key
    'YOUR_AUTH_TOKEN',        // Replace with your Auth Token
    'https://test.instamojo.com/api/1.1/' // Sandbox URL for testing
);

try {
    // Check payment status
    $response = $api->paymentRequestPaymentStatus($payment_request_id);

    if($response['status'] == 'Completed'){
        echo "<h2>Payment Successful!</h2>";
        echo "<p>Payment ID: " . htmlspecialchars($payment_id) . "</p>";
        echo "<p>Amount Paid: ₹" . htmlspecialchars($response['amount']) . "</p>";
        
        // TODO: Update your database
        // Example:
        // $conn->query("UPDATE payments SET status='paid' WHERE id='$payment_id'");
        
    } else {
        echo "<h2>Payment Pending or Failed</h2>";
        echo "<p>Status: " . htmlspecialchars($response['status']) . "</p>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
