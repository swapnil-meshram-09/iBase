<?php
include "../db.php";

$success = false;

$courses = mysqli_query($conn,"SELECT * FROM courses");

if(isset($_POST['register'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $mobile=$_POST['mobile'];
    $address=$_POST['address'];
    $course_id=$_POST['course_id'];

    $c = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM courses WHERE id=$course_id"));

    mysqli_query($conn,"INSERT INTO registrations 
        (student_name,email,mobile,address,course_id,course_title,amount_paid)
        VALUES ('$name','$email','$mobile','$address','$course_id','".$c['title']."','".$c['amount']."')");

    $success = true;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Registration</title>
<style>
body{font-family:Arial;background:#f4f6f8;padding:20px}
.box{background:white;padding:20px;border-radius:10px;max-width:500px;margin:auto}
input,select{width:100%;padding:10px;margin:8px 0}
button{padding:12px;width:100%;background:#16a34a;color:white;border:none;border-radius:6px}
</style>
</head>
<body>

<a href="../index.php">⬅ Back</a>

<div class="box">
<h2>Student Registration</h2>

<?php if($success): ?>
<p style="color:green">Registration successful!</p>
<?php endif; ?>

<form method="post">
<input name="name" placeholder="Name" required>
<input name="email" type="email" placeholder="Email" required>
<input name="mobile" placeholder="Mobile" required>
<input name="address" placeholder="Address" required>

<select name="course_id" required>
<option value="">Select Course</option>
<?php while($c=mysqli_fetch_assoc($courses)){ ?>
<option value="<?= $c['id'] ?>"><?= $c['title'] ?> (₹<?= $c['amount'] ?>)</option>
<?php } ?>
</select>

<button name="register">Register</button>
</form>
</div>

</body>
</html>
