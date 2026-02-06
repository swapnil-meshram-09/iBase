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
body { background:#dde3ea; font-family:Arial; }
.box {
    width:400px;
    margin:10px auto;
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 0 10px #aaa;
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

label { font-weight:bold; margin-top:10px; display:block; }
input, select {
    width: 95%;
    padding:10px;
    margin-top:5px;
    margin-bottom:10px;
    border:none;
    border-radius:6px;
    background:#f2f2f2;
}

select{
    width: 100%;
}
button {
    margin-top:15px;
    width:100%;
    padding:12px;
    background:#16a34a;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}
button:hover { background:#12833b; }
.error { color:red; text-align:center; margin-bottom:10px; }
</style>

<script>
// Allow only numbers
function onlyNumber(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}

// Allow only letters
function onlyChar(input) {
    input.value = input.value.replace(/[^A-Za-z ]/g, '');
}
</script>
</head>
<body>
<a href="../index.php">⬅ Back</a>

<div class="box">

<h2 style="text-align:center">Course Selection</h2>

<?php if($error){ ?><p class="error"><?= $error ?></p><?php } ?>

<form method="POST">

<label><b>Student Name</b></label>
<input type="text" name="name" oninput="onlyChar(this)" pattern="[A-Za-z ]+" placeholder="Enter Student Name" required>

<label><b>Email</b></label>
<input type="email" name="email" placeholder="Enter Email" required>

<label><b>Mobile Number</b></label>
<input type="text" name="mobile" maxlength="10" oninput="onlyNumber(this)" inputmode="numeric" pattern="[0-9]{10}" placeholder="Enter 10-digit Mobile Number" required>

<label><b>Select Course</b></label>
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
