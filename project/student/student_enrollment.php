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
    }
    elseif (!preg_match("/^[A-Za-z ]+$/", $name)) {
        $error = "Student name must contain only letters!";
    }
    elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $error = "Student contact must be exactly 10 digits!";
    }
    elseif (!preg_match("/^[A-Za-z ]+$/", $hod_name)) {
        $error = "HOD name must contain only letters!";
    }
    elseif (!preg_match("/^[0-9]{10}$/", $hod_contact)) {
        $error = "HOD contact must be exactly 10 digits!";
    } else {
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
body { background:#dde3ea; font-family: Arial; margin:0px;}
#formBox {
    width: 450px;
    margin-top:0px;
    margin: auto;
    background:white;
    padding: 30px;
    border-radius:15px;
    box-shadow:0px 0px 10px #aaa;
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
    padding: 8px;
    padding-left: 12px;
    border:none;
    border-radius:6px;
    background:#f2f2f2;
    width:90%;
    margin-top:10px;
}

select,textarea  {
    width: 95%;
}
button {
    margin-top:15px;
    padding:12px;
    background:#007bff;
    border:none;
    color:white;
    border-radius:10px;
    width:100%;
    font-size:16px;
    cursor:pointer;
}
button:hover { background:#0056cc; }
.error { text-align:center; color:red; font-weight:bold; margin-bottom:10px; }
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

<form method="POST" id="formBox">

<h2 style="text-align:center">Student Enrollment Form</h2>

<?php if($error){ ?><p class="error"><?= $error ?></p><?php } ?>

<label><b>Student Name</b></label>
<input type="text"
       name="name"
       value="<?= htmlspecialchars($name) ?>"
       oninput="onlyChar(this)"
       pattern="[A-Za-z ]+"
       placeholder="Enter Student Name"
       required>

<label><b>Student Contact Number</b></label>
<input type="text"
       name="contact"
       value="<?= htmlspecialchars($contact) ?>"
       oninput="onlyNumber(this)"
       maxlength="10"
       inputmode="numeric"
       pattern="[0-9]{10}"
       placeholder="Enter 10-digit Contact Number"
       required>

<label><b>College Name</b></label>
<input type="text"
       name="college"
       oninput="onlyChar(this)"
       pattern="[A-Za-z ]+"
       placeholder="Enter College Name"
       required>

<label><b>Department</b></label>
<select name="department" required>
    <option value="">Select Department</option>
    <option value="Computer Science">Computer Science</option>
    <option value="AI & ML">AI & ML</option>
    <option value="AI & DS">AI & DS</option>
    <option value="ETC">ETC</option>
    <option value="Mechanical">Mechanical</option>
    <option value="Civil">Civil</option>
    <option value="Electrical">Electrical</option>
</select>

<label><b>Year</b></label>
<select name="year" required>
    <option value="">Select Year</option>
    <option value="First Year">First Year</option>
    <option value="Second Year">Second Year</option>
    <option value="Third Year">Third Year</option>
    <option value="Final Year">Final Year</option>
</select>

<label><b>HOD Name</b></label>
<input type="text"
       name="hod_name"
       oninput="onlyChar(this)"
       pattern="[A-Za-z ]+"
       placeholder="Enter HOD Name"
       required>

<label><b>HOD Contact Number</b></label>
<input type="text"
       name="hod_contact"
       oninput="onlyNumber(this)"
       maxlength="10"
       inputmode="numeric"
       pattern="[0-9]{10}"
       placeholder="Enter 10-digit HOD Contact"
       required>

<button type="submit">Submit & Continue</button>

</form>

</body>
</html>
