<?php
include "../db/db.php";
if(isset($_POST['register'])){
    mysqli_query($conn,"INSERT INTO students(name,email,phone,address)
    VALUES(
        '$_POST[name]',
        '$_POST[email]',
        '$_POST[phone]',
        '$_POST[address]'
    )");
    session_start();
    $_SESSION['email']=$_POST['email'];
    header("Location: select_course.php");
}
?>

<form method="POST" class="card-form">
<h2>Student Course Registration</h2>

<input name="name" placeholder="Student Name" required>
<input name="email" value="<?= $_GET['email'] ?? '' ?>" required>
<input name="phone" placeholder="Mobile (WhatsApp)" required>
<input name="address" placeholder="Address" required>

<button name="register">Register</button>
</form>
