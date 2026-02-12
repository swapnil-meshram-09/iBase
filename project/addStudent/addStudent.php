<?php
session_start();
include "../db.php";

$currentTab = basename($_SERVER['PHP_SELF']);

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name        = trim($_POST['name']);
    $contact     = trim($_POST['contact']);
    $college     = trim($_POST['college']);
    $department  = $_POST['department'];
    $year        = $_POST['year'];
    $hod_name    = trim($_POST['hod_name']);
    $hod_contact = trim($_POST['hod_contact']);

    // Validation
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

        // Check duplicate contact
        $check = mysqli_query($conn, "SELECT id FROM addstudent WHERE contact='$contact'");

        if (mysqli_num_rows($check) > 0) {

            $error = "This contact number is already registered!";

        } else {

            $insert = mysqli_query($conn, "
                INSERT INTO addstudent
                (name, contact, college_name, department, year, hod_name, hod_contact)
                VALUES
                ('$name', '$contact', '$college', '$department', '$year', '$hod_name', '$hod_contact')
            ");

            if ($insert) {

                $success = "Student added successfully!";

                // Clear form after success
                $_POST = [];

            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Student</title>

<style>
body {
    background: #dde3ea;
    margin: 0;
}

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

.tab:hover { background: black; color: white; }
.tab.active { background: black; color: white; }

.card {
    width: 450px;
    margin: auto;
    margin-top: 40px;
    background: white;
    padding: 25px;
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

select { width: 95%; }

button.save {
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

button.save:hover { background: #12833b; }

.error {
    text-align: center;
    color: red;
    font-weight: bold;
}

.success {
    text-align: center;
    color: green;
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
<!-- 
<div class="tabs">
    <a class="tab <?= $currentTab=='userCreateProgram.php' ? 'active' : '' ?>" href="userCreateProgram.php">Create Program</a>
    <a class="tab <?= $currentTab=='userViewProgram.php' ? 'active' : '' ?>" href="userViewProgram.php">View Program</a>
    <a class="tab <?= $currentTab=='userAddStudent.php' ? 'active' : '' ?>" href="userAddStudent.php">Add Student</a>
    <a class="tab <?= $currentTab=='userViewStudent.php' ? 'active' : '' ?>" href="userViewStudent.php">View Student</a>
    <a class="tab <?= $currentTab=='userAddFaculty.php' ? 'active' : '' ?>" href="userAddFaculty.php">Add Faculty</a>
    <a class="tab <?= $currentTab=='userViewFaculty.php' ? 'active' : '' ?>" href="userViewFaculty.php">View Faculty</a>
</div> -->

<div class="card">

<h2>Add Student Details</h2>

<?php if ($error) echo "<p class='error'>$error</p>"; ?>
<?php if ($success) echo "<p class='success'>$success</p>"; ?>

<form method="POST">

<label>Student Name</label>
<input type="text" name="name"
value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
oninput="onlyChar(this)" required>

<label>Student Contact Number</label>
<input type="text" name="contact"
value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>"
oninput="onlyNumber(this)" maxlength="10" required>

<label>College Name</label>
<input type="text" name="college"
value="<?= htmlspecialchars($_POST['college'] ?? '') ?>"
oninput="onlyChar(this)" required>

<label>Department</label>
<select name="department" required>
<option value="">Select Department</option>
<?php
$departments = ["Computer Science","AI & ML","AI & DS","ETC","Mechanical","Civil","Electrical"];
foreach ($departments as $d) {
    $selected = (($_POST['department'] ?? '') == $d) ? "selected" : "";
    echo "<option $selected>$d</option>";
}
?>
</select>

<label>Year</label>
<select name="year" required>
<option value="">Select Year</option>
<?php
$years = ["First Year","Second Year","Third Year","Final Year"];
foreach ($years as $y) {
    $selected = (($_POST['year'] ?? '') == $y) ? "selected" : "";
    echo "<option $selected>$y</option>";
}
?>
</select>

<label>HOD Name</label>
<input type="text" name="hod_name"
value="<?= htmlspecialchars($_POST['hod_name'] ?? '') ?>"
oninput="onlyChar(this)" required>

<label>HOD Contact Number</label>
<input type="text" name="hod_contact"
value="<?= htmlspecialchars($_POST['hod_contact'] ?? '') ?>"
oninput="onlyNumber(this)" maxlength="10" required>

<button class="save" type="submit">Add Student</button>

</form>

</div>

</body>
</html>
