<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "db.php";

$error = "";

// FORM SUBMISSION
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $description = trim($_POST['desciption']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Escape data
    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);

    // Validation
    if (empty($title) || empty($description) || empty($start_date) || empty($end_date)) {

        $error = "All fields are required!";

    } elseif (!preg_match("/^[A-Za-z ]+$/", $title)) {

        $error = "Title must contain only letters and spaces!";

    } elseif (strtotime($start_date) > strtotime($end_date)) {

        $error = "Start Date must be less than End Date!";

    } else {

        // CHECK DUPLICATE TITLE
        $check = "SELECT id FROM registrations WHERE title='$title'";
        $checkResult = mysqli_query($conn, $check);

        if (mysqli_num_rows($checkResult) > 0) {

            $error = "This Title Already Exists! Duplicate Entry Not Allowed.";

        } else {

            // INSERT
            $sql = "INSERT INTO registrations 
                    (title, description, start_date, end_date)
                    VALUES 
                    ('$title','$description','$start_date','$end_date')";

            if (mysqli_query($conn, $sql)) {

                // SUCCESS MESSAGE USING SESSION
                $_SESSION['success'] = "Registration Successful!";
                header("Location: index.php");
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
<title>Registration</title>

<style>

/* NAVBAR */
.navbar {
    background: #0682ff;
    padding: 12px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar h2 {
    color: white;
    margin: 0;
}

.nav-links a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: bold;
    padding: 6px 12px;
    border-radius: 6px;
}

.nav-links a:hover {
    background: rgba(255,255,255,0.2);
}

/* FORM STYLE */

body {
    background: #dde3ea;
    font-family: Arial, sans-serif;
    margin: 0;
}

#formPage {
    width: 520px;
    margin: 90px auto;
    background: white;
    padding: 10px;
    border-radius: 15px;
    box-shadow: 0px 0px 10px #aaa;
    color:black;
}

table {
    margin-left: 50px;
    width: 70%;
    border-spacing: 10px 12px;
}

input, textarea {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: none;
    background: whitesmoke;
}

td{
    font-size: 15px;
    font-weight: 700;
}

textarea {
    height: 80px;
}

button {
    margin: 20px auto;
    display: block;
    padding: 12px 30px;
    background: #0682ff;
    border: none;
    border-radius: 15px;
    color: white;
    cursor: pointer;
}

.error {
    color: red;
    text-align: center;
    font-weight: bold;
}

.success {
    color: green;
    text-align: center;
    font-weight: bold;
    font-size: 18px;
    margin-top: 20px;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <h2>Registration System</h2>
    <div class="nav-links">
        <a href="index.php">Registration</a>
        <a href="welcome.php">View Records</a>
    </div>
</div>

<!-- SUCCESS MESSAGE -->
<?php if(isset($_SESSION['success'])) { ?>
    <p class="success">
        <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']); 
        ?>
    </p>
<?php } ?>

<!-- ERROR MESSAGE -->
<?php if($error != "") { ?>
<p class="error"><?php echo $error; ?></p>
<?php } ?>

<!-- FORM (HIDE AFTER SUCCESS) -->
<?php if(!isset($_SESSION['success'])) { ?>
<form method="POST" id="formPage">

<h2 align="center">Registration Form</h2>

<table>

<tr>
<td>Title</td>
<td>
<input type="text"
       name="title"
       pattern="[A-Za-z ]+"
       oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')"
       required>
</td>
</tr>

<tr>
<td>Description</td>
<td>
<textarea name="desciption" required></textarea>
</td>
</tr>

<tr>
<td>Start Date</td>
<td><input type="date" name="start_date" required></td>
</tr>

<tr>
<td>End Date</td>
<td><input type="date" name="end_date" required></td>
</tr>

</table>

<button type="submit">Submit</button>

</form>
<?php } ?>

</body>
</html>
