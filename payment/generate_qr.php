<?php
session_start();
include __DIR__ . "/db.php";                     // Database connection
include __DIR__ . "/phpqrcode/qrlib.php";       // Include QR code library

// Redirect if payment session not found
if(!isset($_SESSION['payment_id'], $_SESSION['course_name'], $_SESSION['course_amount'])){
    header("Location: index.php");
    exit;
}

// Get session data
$payment_id = $_SESSION['payment_id'];
$course_name = $_SESSION['course_name'];
$course_amount = $_SESSION['course_amount'];

// Create folder for QR codes if it doesn't exist
$qr_folder = __DIR__ . "/qrcodes";
if(!file_exists($qr_folder)){
    mkdir($qr_folder, 0777, true);
}

// Generate QR code file path
$qr_file = $qr_folder . "/payment_" . $payment_id . ".png";

// Payment URL (replace with your real payment gateway)
$payment_url = "https://yourpaymentgateway.com/pay?amount=$course_amount&course=$course_name&id=$payment_id";

// Generate QR code if file does not exist yet
if(!file_exists($qr_file)){
    QRcode::png($payment_url, $qr_file, 'L', 6, 2);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Code Payment</title>
<style>
body {
    font-family: Arial, sans-serif;
    text-align: center;
    margin-top: 50px;
    background-color: #f5f5f5;
}
.qr-container {
    display: inline-block;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}
.qr-container img {
    width: 200px;
    height: 200px;
}
button {
    padding: 10px 25px;
    font-size: 16px;
    margin-top: 20px;
    cursor: pointer;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
}
button:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>

<div class="qr-container">
    <h2>Scan QR to Pay for <?php echo htmlspecialchars($course_name); ?></h2>
    <img src="<?php echo 'qrcodes/payment_' . $payment_id . '.png'; ?>" alt="QR Code">
    <br>
    <a href="<?php echo $payment_url; ?>"><button>Pay Online</button></a>
</div>

</body>
</html>
