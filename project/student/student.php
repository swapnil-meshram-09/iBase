<?php
session_start();
include "../db.php";

$error = "";

// Fetch courses
$courses = mysqli_query($conn, "SELECT * FROM courses");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name   = trim($_POST['name']);
    $email  = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $course = $_POST['course_id'];

    if (empty($name) || empty($email) || empty($mobile) || empty($course)) {
        $error = "All fields are required!";
    }
    elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $error = "Mobile number must be 10 digits!";
    }
    else {
        // Save session
        $_SESSION['student_name']   = $name;
        $_SESSION['student_email']  = $email;
        $_SESSION['student_mobile'] = $mobile;
        $_SESSION['course_id']      = $course;

        // Check if student exists
        $check = mysqli_query($conn,
            "SELECT id FROM student_enrollment WHERE contact='$mobile'"
        );

        if (mysqli_num_rows($check) > 0) {
            header("Location: payment.php");
            exit;
        } else {
            header("Location: student_enrollment.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Details</title>
<style>
body{background:#dde3ea;font-family:Arial}
.box{width:400px;margin:60px auto;background:#fff;padding:25px;border-radius:12px}
input,select{width:100%;padding:10px;margin:8px 0;background:#f2f2f2;border:none;border-radius:6px}
button{width:100%;padding:12px;background:#16a34a;color:white;border:none;border-radius:8px}
.error{color:red;text-align:center}
</style>
</head>
<body>

<div class="box">
<h2>Proceed to Payment</h2>

<?php if($error){ ?><p class="error"><?= $error ?></p><?php } ?>

<form method="POST">
<input type="text" name="name" placeholder="Student Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="text" name="mobile" maxlength="10" placeholder="Mobile Number" required>

<select name="course_id" required>
<option value="">Select Course</option>
<?php while($c=mysqli_fetch_assoc($courses)){ ?>
<option value="<?= $c['id'] ?>"><?= $c['title'] ?> (₹<?= $c['amount'] ?>)</option>
<?php } ?>
</select>

<button>Proceed</button>
</form>
</div>

</body>
</html>
