<?php
session_start();
include "../db.php";

if (!isset($_SESSION['student_mobile']) || !isset($_SESSION['course_id'])) {
    header("Location: student.php");
    exit;
}

$student_mobile = $_SESSION['student_mobile'];
$student_name   = $_SESSION['student_name'];
$course_id      = $_SESSION['course_id'];

// Fetch student ID
$student = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT id FROM student_enrollment WHERE contact='$student_mobile'")
);
$student_id = $student['id'];

// Fetch course
$course = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM courses WHERE id='$course_id'")
);

// Handle payment demo
if (isset($_POST['pay_now'])) {
    mysqli_query($conn, "INSERT INTO payments
        (student_id, course_id, amount, payment_status, payment_method, transaction_id)
        VALUES ('$student_id','$course_id','".$course['amount']."','success','demo','TXN".time()."')");
    header("Location: welcome.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment</title>
<style>
body{background:#dde3ea;font-family:Arial}
.box{width:420px;margin:60px auto;background:#fff;padding:25px;border-radius:15px;box-shadow:0 0 10px #aaa}
p{margin:8px 0}
button{width:100%;padding:12px;background:#16a34a;border:none;color:white;border-radius:10px;font-size:16px;cursor:pointer}
button:hover{background:#12833b}
</style>
</head>
<body>

<div class="box">
<h2>Payment Details</h2>
<p><b>Name:</b> <?= htmlspecialchars($student_name) ?></p>
<p><b>Mobile:</b> <?= htmlspecialchars($student_mobile) ?></p>
<p><b>Course:</b> <?= htmlspecialchars($course['title']) ?></p>
<p><b>Amount:</b> ₹<?= $course['amount'] ?></p>

<form method="POST">
<button name="pay_now">Pay Now</button>
</form>
</div>

</body>
</html>
