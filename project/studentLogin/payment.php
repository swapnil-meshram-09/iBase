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

// Fetch course
$course = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM courses WHERE id='$course_id'")
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment</title>
<style>
body{
    background:#dde3ea;
    /* font-family:Arial; */
    margin:0px;
}
h2{
    text-align:center;
    margin-bottom: 45px;
    margin-top: 0px;
}
.box{
    width:420px;
    margin:auto;
    margin-top: 40px;    
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 0 10px #aaa;
    margin-bottom:10px;
}
p{
  margin-top: 25px;
  margin-bottom:20px;
}
button{
    width:100%;padding:12px;background:#16a34a;border:none;color:white;border-radius:10px;font-size:16px;cursor:pointer; margin-top:10px;
}
button:hover{
    background:#12833b
}
a {
    display: inline-block;
    margin: 12px 0;
    margin-left: 90%;
    color: black;
    text-decoration: none;
    font-weight: bold;
}
a:hover { text-decoration: underline; }
</style>
</head>
<body>
<a href="../index.php">
    <!-- ⬅ Back -->
</a>
<div class="box">
<h2>Proceed to payment</h2>
<p><b>Name:</b> <?= htmlspecialchars($student_name) ?></p>
<p><b>Mobile:</b> <?= htmlspecialchars($student_mobile) ?></p>
<p><b>Course:</b> <?= htmlspecialchars($course['title']) ?></p>
<p><b>Amount:</b> ₹<?= $course['amount'] ?></p>

<form method="POST" action="whatsapp.php">
    <input type="hidden" name="phone" value="<?= htmlspecialchars($student_mobile) ?>">
    <input type="hidden" name="course_id" value="<?= htmlspecialchars($course_id) ?>">
    <button type="submit" name="send_msg">Send Confirmation via WhatsApp</button>
</form>
</div>

</body>
</html>
