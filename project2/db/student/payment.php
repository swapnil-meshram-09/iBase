<?php
include "../db/db.php";
$course = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM courses WHERE id=$_GET[course]")
);
?>

<div class="payment-card">
<h2>Registration Successful!</h2>
<p>Scan to Pay</p>

<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=demo@upi&am=<?= $course['amount'] ?>">

<h3>₹ <?= $course['amount'] ?></h3>

<a class="whatsapp" href="success.php">Send Confirmation via WhatsApp</a>
</div>
