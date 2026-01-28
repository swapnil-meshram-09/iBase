<?php
require __DIR__ . '/Instamojo.php';

$payment_request_id = $_GET['payment_request_id'] ?? '';
$payment_id = $_GET['payment_id'] ?? '';

if(!$payment_request_id || !$payment_id){
    die("Invalid payment response.");
}

$api = new Instamojo\Instamojo('YOUR_API_KEY', 'YOUR_AUTH_TOKEN', 'https://test.instamojo.com/api/1.1/');

try {
    $response = $api->paymentRequestPaymentStatus($payment_request_id);

    if($response['status'] == 'Completed'){
        echo "Payment Successful!";
        // TODO: Update your DB to mark $payment_id as paid
    } else {
        echo "Payment Pending or Failed.";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
