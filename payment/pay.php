<?php
require __DIR__ . '/Instamojo.php';
session_start();

$payment_id = $_GET['id'] ?? '';
$course_name = $_GET['course'] ?? '';
$course_amount = $_GET['amount'] ?? '';

if(!$payment_id || !$course_name || !$course_amount){
    die("Invalid payment details.");
}

// Use your **LIVE** Instamojo API credentials
$api = new Instamojo\Instamojo(
    'YOUR_PRIVATE_API_KEY',
    'YOUR_PRIVATE_AUTH_TOKEN',
    'https://www.instamojo.com/api/1.1/'
);

// Optional: You can pass email/phone from session if available
$student_email = $_SESSION['email'] ?? '';
$student_phone = $_SESSION['mobile'] ?? '';

try {
    $response = $api->paymentRequestCreate(array(
        "purpose" => "Payment for $course_name",
        "amount" => $course_amount,
        "buyer_name" => $_SESSION['student_name'] ?? 'Student',
        "email" => $student_email,
        "phone" => $student_phone,
        "redirect_url" => "https://yourdomain.com/payment_success.php?payment_id=$payment_id"
    ));

    // Redirect to Instamojo checkout page
    header("Location: " . $response['longurl']);
    exit;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
