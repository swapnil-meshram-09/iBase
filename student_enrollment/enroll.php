<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $college = trim($_POST['college']);
    $department = $_POST['department'];
    $year = $_POST['year'];
    $hod_name = trim($_POST['hod_name']);
    $hod_contact = trim($_POST['hod_contact']);

    // Validation
    if (empty($name) || empty($contact) || empty($college) || empty($department) || empty($year) || empty($hod_name) || empty($hod_contact)) {
        $error = "All fields are required!";
    } 
    elseif (!preg_match('/^[0-9]{10}$/', $contact) || !preg_match('/^[0-9]{10}$/', $hod_contact)) {
        $error = "Contact numbers must be 10 digits!";
    }
    else {

        // Insert Data
        $sql = "INSERT INTO student_enrollment
        (name, contact, college_name, department, year, hod_name, hod_contact)
        VALUES 
        ('$name','$contact','$college','$department','$year','$hod_name','$hod_contact')";

        if (mysqli_query($conn, $sql)) {

            $_SESSION['last_id'] = mysqli_insert_id($conn);
            header("Location: success.php");
            exit();

        } else {
            $error = "Database Error : " . mysqli_error($conn);
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

</head>

<body>
<form method="POST" id="formBox">

<h2>Student Enrollment Form</h2>

<?php if($error != "") { ?>
<p class="error"><?php echo $error; ?></p>
<?php } ?>

<div class="container">

<label for="name"><b>Student Name</b></label>
<input type="text" name="name" id="name" placeholder="Enter Student Name" required>

<label for="contact"><b>Student Contact Number</b></label>
<input type="text" name="contact" id="contact" placeholder="Enter 10-digit Contact Number" required>

<label for="college"><b>College Name</b></label>
<input type="text" name="college" id="college" placeholder="Enter College Name" required>

<label for="department"><b>Department</b></label>
<select name="department" id="department" required>
    <option value="">Select Department</option>
    <option value="Computer Science">Computer Science</option>
    <option value="Mechanical">Mechanical</option>
    <option value="Civil">Civil</option>
    <option value="Electrical">Electrical</option>
</select>

<label for="year"><b>Year</b></label>
<select name="year" id="year" required>
    <option value="">Select Year</option>
    <option value="First Year">First Year</option>
    <option value="Second Year">Second Year</option>
    <option value="Third Year">Third Year</option>
    <option value="Final Year">Final Year</option>
</select>

<label for="hod_name"><b>HOD Name</b></label>
<input type="text" name="hod_name" id="hod_name" placeholder="Enter HOD Name" required>

<label for="hod_contact"><b>HOD Contact Number</b></label>
<input type="text" name="hod_contact" id="hod_contact" placeholder="Enter 10-digit HOD Contact" required>

</div>

<button type="submit">Submit Enrollment</button>

</form>



</body>
</html>
