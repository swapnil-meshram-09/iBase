<?php
session_start();
include "db.php";
include "phpqrcode/qrlib.php"; // Include QR code library

if(!isset($_SESSION['payment_id'])){
    header("Location: index.php");
    exit;
}

// Get data
$payment_id = $_SESSION['payment_id'];
$course_name = $_SESSION['course_name'];
$course_amount = $_SESSION['course_amount'];

// Generate QR code for payment link (you can integrate with any payment gateway link)
$payment_url = "https://yourpaymentgateway.com/pay?amount=$course_amount&course=$course_name&id=$payment_id";
$qr_file = 'qrcodes/payment_'.$payment_id.'.png';

// Create folder if not exists
if(!file_exists('qrcodes')) mkdir('qrcodes', 0777, true);

// Generate QR code image
QRcode::png($payment_url, $qr_file, 'L', 6, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Code Payment</title>
<style>
.qr-container {
    text-align:center;
    margin-top:50px;
}
.qr-container img {
    width:200px;
    height:200px;
}
button {
    padding:10px 20px;
    font-size:16px;
    margin-top:20px;
    cursor:pointer;
}
</style>
</head>
<body>

<div class="qr-container">
    <h2>Scan QR to Pay for <?php echo htmlspecialchars($course_name); ?></h2>
    <img src="<?php echo $qr_file; ?>" alt="QR Code">
    <br>
    <a href="<?php echo $payment_url; ?>"><button>Pay Online</button></a>
</div>

</body>
</html>
