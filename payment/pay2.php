<?php
session_start();
include 'db.php'; // Database connection

use Endroid\QrCode\QrCode;
require 'vendor/autoload.php';

$successData = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = trim($_POST['student_name']);
    $mobile = trim($_POST['mobile']);
    $course_name = $_POST['course_name'];
    $course_amount = $_POST['course_amount'];

    // Save registration
    $stmt = $conn->prepare("INSERT INTO registrations (student_name, mobile, course_name, course_amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $student_name, $mobile, $course_name, $course_amount);
    $stmt->execute();

    $successData = [
        'student_name' => $student_name,
        'mobile' => $mobile,
        'course_name' => $course_name,
        'course_amount' => $course_amount
    ];

    // Generate UPI QR code
    $upiString = "upi://pay?pa=demo@upi&pn=Institute&am={$course_amount}&cu=INR";
    $qrCode = new QrCode($upiString);
    $qrCode->setSize(200);
    $qrCodeDataUri = $qrCode->writeDataUri();

    // WhatsApp URL
    $message = "Hello, I have registered for the course: {$course_name}.\nName: {$student_name}\nAmount: ₹{$course_amount}\nPlease confirm my registration.";
    $whatsappUrl = "https://wa.me/?text=" . urlencode($message);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Registration</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    margin: 0;
    padding: 20px;
}
.container {
    max-width: 400px;
    margin: 0 auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: 1px solid #e0e0e0;
}
h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}
input, select, button {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 16px;
    box-sizing: border-box;
}
input:focus, select:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 2px rgba(79,70,229,0.2);
}
button {
    background-color: #4f46e5;
    color: white;
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}
button:hover {
    background-color: #4338ca;
    transform: translateY(-2px);
}
.success {
    text-align: center;
}
.success h2 {
    color: #16a34a;
}
.qr-section {
    background: #f9fafb;
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 15px;
}
.qr-section img {
    display: block;
    margin: 0 auto 10px;
}
a.button-link {
    display: block;
    background: #16a34a;
    color: white;
    padding: 12px;
    text-decoration: none;
    font-weight: bold;
    border-radius: 8px;
    margin-bottom: 10px;
    text-align: center;
}
a.button-link:hover {
    background: #15803d;
}
a.secondary-link {
    display: block;
    background: #e5e7eb;
    color: #333;
    padding: 12px;
    text-decoration: none;
    border-radius: 8px;
    text-align: center;
}
a.secondary-link:hover {
    background: #d1d5db;
}
</style>
<script>
function onlyNumber(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}
</script>
</head>
<body>
<div class="container">
<?php if ($successData): ?>
    <div class="success">
        <h2>Registration Successful!</h2>
        <p>Please complete the payment to confirm your seat.</p>
    </div>

    <div class="qr-section">
        <img src="<?= $qrCodeDataUri ?>" alt="UPI QR Code" width="200">
        <p style="text-align:center;font-weight:bold;">Amount: ₹<?= $successData['course_amount'] ?></p>
        <p style="text-align:center;font-size:14px;color:#555;">Scan to Pay via UPI App</p>
    </div>

    <a href="<?= $whatsappUrl ?>" target="_blank" class="button-link">Send Confirmation via WhatsApp</a>
    <a href="index.php" class="secondary-link">Register Another Student</a>

<?php else: ?>
    <h2>Student Registration</h2>
    <form method="POST">
        <input type="text" name="student_name" placeholder="Student Name" required>
        <input type="text" name="mobile" placeholder="Mobile Number" oninput="onlyNumber(this)" maxlength="10" required>
        <select name="course_name" required>
            <option value="">Select Department</option>
            <option value="Python">Python</option>
            <option value="Java">Java</option>
            <option value="JavaScript">JavaScript</option>
            <option value="PHP">PHP</option>
        </select>
        <input type="text" name="course_amount" placeholder="Enter Amount" oninput="onlyNumber(this)" required>
        <button type="submit">Register</button>
    </form>
<?php endif; ?>
</div>
</body>
</html>
