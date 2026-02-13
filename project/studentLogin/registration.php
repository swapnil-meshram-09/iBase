<?php
session_start();
include "../db.php";

$error   = "";
$success = "";

/* Load old form data if exists */
$form = $_SESSION['form_data'] ?? [];

$name        = $form['name'] ?? ($_SESSION['student_name'] ?? "");
$contact     = $form['contact'] ?? "";
$college     = $form['college'] ?? "";
$department  = $form['department'] ?? "";
$year        = $form['year'] ?? "";
$hod_name    = $form['hod_name'] ?? "";
$hod_contact = $form['hod_contact'] ?? "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $_SESSION['form_data'] = $_POST;

    $name        = trim($_POST['name']);
    $contact     = trim($_POST['contact']);
    $college     = trim($_POST['college']);
    $department  = $_POST['department'];
    $year        = $_POST['year'];
    $hod_name    = trim($_POST['hod_name']);
    $hod_contact = trim($_POST['hod_contact']);

    /* Validation */
    if (
        empty($name) || empty($contact) || empty($college) ||
        empty($department) || empty($year) ||
        empty($hod_name) || empty($hod_contact)
    ) {
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
    }
    else {

        /* Check if student already exists */
        $check = mysqli_query(
            $conn,
            "SELECT id FROM student_registration WHERE contact='$contact'"
        );

        if (mysqli_num_rows($check) > 0) {

            $error = "Student already exists with this contact number!";

        } else {

            mysqli_query(
                $conn,
                "INSERT INTO student_registration
                (name, contact, college_name, department, year, hod_name, hod_contact)
                VALUES
                ('$name','$contact','$college','$department','$year','$hod_name','$hod_contact')"
            );

            $_SESSION['student_name']   = $name;
            $_SESSION['student_mobile'] = $contact;

            /* Clear temporary form data */
           

            unset($_SESSION['form_data']);
            /* Redirect to password setup */
            header("Location: studentPassword.php");
            // unset($_SESSION['form_data']);
            // unset($_SESSION['student_mobile']);
            exit;
        }
    }
}

/* Active tab */
$currentTab = basename($_SERVER['PHP_SELF']);
?>


<!DOCTYPE html>
<html>
<head>
<title>Student Registration</title>

<style>
body {
    /* font-family: Arial, sans-serif; */
    background: #dde3ea;
    margin: 0px;
}

/* Tabs */
.tabs {
    margin: 30px 0;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 13.5px;
    justify-content: center;
}

.tab {
    padding: 10px 18px;
    border-radius: 10px;
    background: #f2f2f2;
    font-weight: bold;
    text-decoration: none;
    color: black;
}

.tab:hover {
    background: black;
    color: white;
}

/* Active tab */
.tab.active {
    background: black;
    color: white;
}

/* Disabled tab if not logged in */
.tab.disabled {
    pointer-events: none;
    opacity: 0.5;
}

/* Form */
#formBox {
    width: 450px;
    margin: auto;
    margin-top: 40px;
    background: white;
    padding: 25px;
    padding-top: 0.5px;
    border-radius: 15px;
    box-shadow: 0px 0px 10px #aaa;
    font-size: 15px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
}

label {
    font-weight: bold;
    margin-top: 10px;
    display: block;
    margin-left: 10px;
}

input, select {
    padding: 8px 12px;
    border: none;
    border-radius: 6px;
    background: #f2f2f2;
    width: 90%;
    margin-top: 10px;
    margin-left: 10px;
}

select {
    width: 95%;
}

button {
    margin-top: 15px;
    padding: 12px;
    background: #16a34a;
    border: none;
    color: white;
    border-radius: 10px;
    width: 95%;
    font-size: 16px;
    cursor: pointer;
    margin-left: 10px;
}

button:hover {
    background: green;
}

.error {
    text-align: center;
    color: red;
    font-weight: bold;
}
</style>

<script>
function onlyNumber(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}
function onlyChar(input) {
    input.value = input.value.replace(/[^A-Za-z ]/g, '');
}
</script>
</head>

<body>

<!-- Tabs -->
<div class="tabs">
    <a class="tab <?= $currentTab=='login.php' ? 'active' : '' ?>" href="login.php">Login</a>
    <a class="tab <?= $currentTab=='registration.php' ? 'active' : '' ?>" href="registration.php">Registration</a>
    <!-- <a class="tab <?= $currentTab=='enroll.php' ? 'active' : '' ?>" href="enroll.php">Enroll</a>
    <a class="tab <?= $currentTab=='dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a> -->
</div>

<!-- Registration Form -->
<form method="POST" id="formBox">

<h2>Student Registration</h2>

<?php if ($error) { ?>
    <p class="error"><?= $error ?></p>
<?php } ?>

<label>Student Name</label>
<input type="text" name="name" value="<?= htmlspecialchars($name) ?>" oninput="onlyChar(this)" required>

<label>Student Contact Number</label>
<input type="text" name="contact" value="<?= htmlspecialchars($contact) ?>" oninput="onlyNumber(this)" maxlength="10" required>

<label>College Name</label>
<input type="text" name="college" oninput="onlyChar(this)" required>

<label>Department</label>
<select name="department" required>
    <option value="">Select Department</option>
    <option>Computer Science</option>
    <option>AI & ML</option>
    <option>AI & DS</option>
    <option>ETC</option>
    <option>Mechanical</option>
    <option>Civil</option>
    <option>Electrical</option>
</select>

<label>Year</label>
<select name="year" required>
    <option value="">Select Year</option>
    <option>First Year</option>
    <option>Second Year</option>
    <option>Third Year</option>
    <option>Final Year</option>
</select>

<label>HOD Name</label>
<input type="text" name="hod_name" oninput="onlyChar(this)" required>

<label>HOD Contact Number</label>
<input type="text" name="hod_contact" oninput="onlyNumber(this)" maxlength="10" required>

<button type="submit">Register</button>

</form>

</body>
</html>
