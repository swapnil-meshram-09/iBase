<?php
session_start();
include "../db.php";

$error = "";

$name    = $_SESSION['student_name'] ?? "";
$contact = $_SESSION['student_mobile'] ?? "";
$course_id = $_SESSION['course_id'] ?? "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $college = trim($_POST['college']);
    $department = $_POST['department'];
    $year = $_POST['year'];
    $hod_name = trim($_POST['hod_name']);
    $hod_contact = trim($_POST['hod_contact']);

    if (empty($name) || empty($contact) || empty($college) || empty($department) || empty($year) || empty($hod_name) || empty($hod_contact)) {
        $error = "All fields are required!";
    } else {
        // Insert student if not exists
        $check = mysqli_query($conn,"SELECT id FROM student_enrollment WHERE contact='$contact'");
        if (mysqli_num_rows($check) == 0) {
            mysqli_query($conn,"INSERT INTO student_enrollment
                (name, contact, college_name, department, year, hod_name, hod_contact)
                VALUES
                ('$name','$contact','$college','$department','$year','$hod_name','$hod_contact')");
        }
        header("Location: payment.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Enrollment</title>
<style>
body{background:#dde3ea;font-family:Arial}
#formBox{width:500px;margin:30px auto;background:white;padding:30px;border-radius:15px}
input,select{padding:8px;border:none;border-radius:6px;background:#f2f2f2;width:100%;margin:8px 0}
button{margin-top:15px;padding:12px;background:#007bff;border:none;color:white;border-radius:10px;width:100%}
.error{text-align:center;color:red;font-weight:bold}
</style>
</head>
<body>

<form method="POST" id="formBox">
<h2>Student Enrollment</h2>

<?php if($error){ ?><p class="error"><?= $error ?></p><?php } ?>

<input name="name" value="<?= $name ?>" placeholder="Student Name" required>
<input name="contact" value="<?= $contact ?>" placeholder="Mobile" required>
<input name="college" placeholder="College Name" required>

<select name="department" required>
<option value="">Department</option>
<option>Computer Science</option>
<option>AI & ML</option>
<option>Mechanical</option>
<option>Civil</option>
<option>Electrical</option>
</select>

<select name="year" required>
<option value="">Year</option>
<option>First Year</option>
<option>Second Year</option>
<option>Third Year</option>
<option>Final Year</option>
</select>

<input name="hod_name" placeholder="HOD Name" required>
<input name="hod_contact" placeholder="HOD Contact" required>

<button>Submit & Continue</button>
</form>

</body>
</html>
