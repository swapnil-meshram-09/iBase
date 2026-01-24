<?php
session_start();
include "db.php";

$error = "";

// SAFE POST FETCH
$name        = $_POST['name'] ?? "";
$contact     = $_POST['contact'] ?? "";
$college     = $_POST['college'] ?? "";
$department  = $_POST['department'] ?? "";
$year        = $_POST['year'] ?? "";
$hod_name    = $_POST['hod_name'] ?? "";
$hod_contact = $_POST['hod_contact'] ?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Trim data
    $name = trim($name);
    $contact = trim($contact);
    $college = trim($college);
    $hod_name = trim($hod_name);
    $hod_contact = trim($hod_contact);

    // VALIDATION
    if (empty($name) || empty($contact) || empty($college) || empty($department) || empty($year) || empty($hod_name) || empty($hod_contact)) {
        $error = "All fields are required!";
    }
    elseif (!preg_match("/^[A-Za-z ]+$/", $name)) {
        $error = "Student name must contain only letters!";
    }
    elseif (!preg_match("/^[A-Za-z ]+$/", $hod_name)) {
        $error = "HOD name must contain only letters!";
    }
    elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $error = "Student contact must be exactly 10 digits!";
    }
    elseif (!preg_match("/^[0-9]{10}$/", $hod_contact)) {
        $error = "HOD contact must be exactly 10 digits!";
    }
    else {

        // 🔍 DUPLICATE CHECK
        $checkQuery = "SELECT id FROM student_enrollment 
                       WHERE name='$name' AND contact='$contact'";

        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {

            $error = "Student already registered!";

        } else {

            // ✅ INSERT DATA
            $sql = "INSERT INTO student_enrollment 
            (name, contact, college_name, department, year, hod_name, hod_contact) 
            VALUES 
            ('$name','$contact','$college','$department','$year','$hod_name','$hod_contact')";

            if (mysqli_query($conn, $sql)) {

                $_SESSION['last_id'] = mysqli_insert_id($conn);
                header("Location: success.php");
                exit();

            } else {
                $error = "Database Error: " . mysqli_error($conn);
            }
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Student Enrollment</title>

<style>

body {
    background:#dde3ea;
    font-family: Arial;
}

#formBox {
    width:500px;
    margin:40px auto;
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0px 0px 10px #aaa;
}

.container {
    display:flex;
    flex-direction:column;
    gap:12px;
}

input, select {
    padding:10px;
    border:none;
    border-radius:6px;
    background:#f2f2f2;
}

button {
    margin-top:15px;
    padding:12px;
    background:#007bff;
    border:none;
    color:white;
    border-radius:10px;
    cursor:pointer;
}

button:hover {
    background:#0056cc;
}

.error {
    color:red;
    text-align:center;
    font-weight:bold;
}

h2 {
    text-align:center;
}

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

<form method="POST" id="formBox">

<h2>Student Enrollment Form</h2>

<?php if($error != "") { ?>
<p class="error"><?php echo $error; ?></p>
<?php } ?>

<div class="container">

<label><b>Student Name</b></label>
<input type="text"
       name="name"
       oninput="onlyChar(this)"
       pattern="[A-Za-z ]+"
       placeholder="Enter Student Name"
       required>

<label><b>Student Contact Number</b></label>
<input type="text"
       name="contact"
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

</div>

<button type="submit">Submit Enrollment</button>

</form>

</body>
</html>
