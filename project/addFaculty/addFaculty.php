<?php
session_start();
include "../db.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $faculty_name     = trim($_POST['faculty_name']);
    $designation      = $_POST['designation'];
    $department       = $_POST['department'];
    $contact          = trim($_POST['contact']);
    $state            = $_POST['state'];
    $city             = $_POST['city'];
    $region           = $_POST['region'];
    $college_name     = trim($_POST['college_name']);
    $college_address  = trim($_POST['college_address']);

    if (
        empty($faculty_name) || empty($designation) || empty($department) ||
        empty($contact) || empty($state) || empty($city) ||
        empty($region) || empty($college_name) || empty($college_address)
    ) {
        $error = "All fields are required!";
    }
    elseif (!preg_match("/^[A-Za-z ]+$/", $faculty_name)) {
        $error = "Faculty name must contain only letters!";
    }
    elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $error = "Contact number must be exactly 10 digits!";
    }
    else {

        $check = mysqli_query(
            $conn,
            "SELECT id FROM addFaculty WHERE contact='$contact'"
        );

        if (mysqli_num_rows($check) > 0) {
            $error = "Faculty with this contact number already exists!";
        } else {

            mysqli_query(
                $conn,
                "INSERT INTO addFaculty
                (faculty_name, designation, department, contact, state, city, region, college_name, college_address)
                VALUES
                ('$faculty_name','$designation','$department','$contact','$state','$city','$region','$college_name','$college_address')"
            );

            $success = "Faculty added successfully!";
        }
    }
}

$currentTab = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>

<style>
body {
    /* font-family: Arial, sans-serif; */
    background: #dde3ea;
    margin: 0px;
}

/* Tabs */
/* .tabs {
    margin: 30px 0;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 13.5px;
    justify-content: center;
    } */
/* 

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

.tab.active {
    background: black;
    color: white;
} */

/* Form */
#formBox {
    /* width: 480px;
    margin: auto;
    margin-top: 20px;
    background: white;
    padding-left: 25px;
    padding-right: 25px;
    padding-top: 0px;
    border-radius: 15px;
    box-shadow: 0px 0px 10px #aaa;
    font-size: 15px; */

    width:420px;
    margin:auto;
    margin-top: 40px;
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 0 10px #aaa;
}

h2 {
    text-align: center;
    /* margin-bottom: 20px; */
    margin-top: 0px;
}

label {
    font-weight: bold;
    margin-top: 10px;
    display: block;
    margin-left: 10px;
}

input, select, textarea {
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

textarea {
    resize: none;
    height: 80px;
}

button {
    margin-top: 15px;
    padding: 12px;
    background-color: #16a34a;
    border: none;
    color: white;
    border-radius: 10px;
    width: 95%;
    font-size: 16px;
    cursor: pointer;
    margin-left: 10px;
}

button:hover {
    background-color: green;
}

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

<!-- Tabs -->
<!-- <div class="tabs">
    <a class="tab <?= $currentTab=='login.php' ? 'active' : '' ?>" href="login.php">Login</a>
    <a class="tab <?= $currentTab=='registration.php' ? 'active' : '' ?>" href="registration.php">Registration</a>
    <a class="tab <?= $currentTab=='addFaculty.php' ? 'active' : '' ?>" href="addFaculty.php">Add Faculty</a>
    <a class="tab <?= $currentTab=='dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
</div> -->

<form method="POST" id="formBox">

<h2>Add Faculty Details</h2>

<?php if ($error) { ?><p class="error"><?= $error ?></p><?php } ?>
<?php if ($success) { ?><p class="success"><?= $success ?></p><?php } ?>

<label>Faculty Name</label>
<input type="text" name="faculty_name" oninput="onlyChar(this)" required>

<label>Designation</label>
<select name="designation" required>
    <option value="">Select Designation</option>
    <option>Assistant Professor</option>
    <option>Associate Professor</option>
    <option>Professor</option>
    <option>HOD</option>
    <option>Lecturer</option>
</select>

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

<label>Contact Number</label>
<input type="text" name="contact" maxlength="10" oninput="onlyNumber(this)" required>

<label>State</label>
<select name="state" required>
    <option value="">Select State</option>
    <option>Maharashtra</option>
    <option>Karnataka</option>
    <option>Gujarat</option>
    <option>Tamil Nadu</option>
</select>

<label>City</label>
<select name="city" required>
    <option value="">Select City</option>
    <option>Pune</option>
    <option>Mumbai</option>
    <option>Bangalore</option>
    <option>Chennai</option>
</select>

<label>Region</label>
<select name="region" required>
    <option value="">Select Region</option>
    <option>North</option>
    <option>South</option>
    <option>East</option>
    <option>West</option>
</select>

<label>College Name</label>
<input type="text" name="college_name" required>

<label>College Address</label>
<textarea name="college_address" required></textarea>

<button type="submit">Add Faculty</button>

</form>

</body>
</html>
