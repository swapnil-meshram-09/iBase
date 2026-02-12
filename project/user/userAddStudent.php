<?php
session_start();
include "../db.php"; // Database connection

$currentTab = basename($_SERVER['PHP_SELF']);

$error = "";
$success = "";

$name    = $_SESSION['student_name'] ?? "";
$contact = $_SESSION['student_mobile'] ?? "";

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
        // Check if contact already exists
        $check = mysqli_query($conn, "SELECT id FROM useraddstudent WHERE contact='$contact'");
        
        if (mysqli_num_rows($check) == 0) {
            // Insert into table
            $insert = mysqli_query($conn, "
                INSERT INTO useraddstudent
                (name, contact, college_name, department, year, hod_name, hod_contact)
                VALUES
                ('$name', '$contact', '$college', '$department', '$year', '$hod_name', '$hod_contact')
            ");

            if ($insert) {
                $_SESSION['student_name']   = $name;
                $_SESSION['student_mobile'] = $contact;
                $success = "Student added successfully!";
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        } else {
            $error = "This contact number is already registered!";
        }
    }
}
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

<div class="tabs">

    <a class="tab <?= $currentTab=='userCreateProgram.php' ? 'active' : '' ?>" href="userCreateProgram.php">Create Program</a>
    <a class="tab <?= $currentTab=='userViewProgram.php' ? 'active' : '' ?>" href="userViewProgram.php">View Program</a>
    <a class="tab <?= $currentTab=='userAddStudent.php' ? 'active' : '' ?>" href="userAddStudent.php">Add Student</a>
    <a class="tab <?= $currentTab=='userViewStudent.php' ? 'active' : '' ?>" href="userViewStudent.php">View Student</a>
    <a class="tab <?= $currentTab=='userAddFaculty.php' ? 'active' : '' ?>" href="userAddFaculty.php">Add Faculty</a>
    <a class="tab <?= $currentTab=='userViewFaculty.php' ? 'active' : '' ?>" href="userViewFaculty.php"> View Faculty</a>

</div>

<!-- Registration Form -->
<form method="POST" id="formBox">

<h2>Add Student Details</h2>

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

<button type="submit">Add Student</button>

</form>

</body>
</html>
