<?php
require __DIR__ . '/Instamojo.php'; // Your SDK

session_start();

// Get payment details from QR code URL
$payment_id = $_GET['id'] ?? '';
$course_name = $_GET['course'] ?? '';
$course_amount = $_GET['amount'] ?? '';

if(!$payment_id || !$course_name || !$course_amount){
    die("Invalid payment details.");
}

// Initialize Instamojo API
$api = new Instamojo\Instamojo(
    'YOUR_API_KEY',           // Replace with your API Key
    'YOUR_AUTH_TOKEN',        // Replace with your Auth Token
    'https://test.instamojo.com/api/1.1/' // Sandbox for testing
);

try {
    // Create a payment request
    $response = $api->paymentRequestCreate(array(
        "purpose" => "Payment for $course_name",
        "amount" => $course_amount,
        "buyer_name" => "Student",           // optional
        "email" => "student@example.com",    // optional
        "phone" => "9999999999",             // optional
        "redirect_url" => "https://yourdomain.com/payment-success.php?payment_id=$payment_id"
    ));

    // Redirect user to Instamojo checkout
    header("Location: " . $response['longurl']);
    exit;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
