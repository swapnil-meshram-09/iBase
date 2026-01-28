<?php
session_start();
include __DIR__ . "/db.php";                     
include __DIR__ . "/phpqrcode/qrlib.php";       

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

// Generate payment URL for QR code (points to pay.php)
$payment_url = "https://yourdomain.com/pay.php?id=$payment_id&course=" . urlencode($course_name) . "&amount=$course_amount";

// Generate QR code if it does not exist yet
$qr_file = $qr_folder . "/payment_" . $payment_id . ".png";
if(!file_exists($qr_file)){
    QRcode::png($payment_url, $qr_file, 'L', 6, 2);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>QR Code Payment</title>
<style>
body { font-family: Arial, sans-serif; text-align:center; background:#f5f5f5; margin-top:50px;}
.qr-container { display:inline-block; padding:20px; background:#fff; border-radius:10px; box-shadow:0 0 15px rgba(0,0,0,0.1);}
.qr-container img { width:200px; height:200px;}
</style>
</head>
<body>

<div class="qr-container">
    <h2>Scan QR to Pay for <?php echo htmlspecialchars($course_name); ?></h2>
    <img src="<?php echo 'qrcodes/payment_' . $payment_id . '.png'; ?>" alt="QR Code">
</div>

</body>
</html>
