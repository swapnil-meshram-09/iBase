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

                $_SESSION['success'] = "Registration completed successfully! 🎉";
                header("Location: index.php");
                exit();

            } else {

                $error = "Database Error: " . mysqli_error($conn);
            }
        }
    }
}

// Determine if form should be shown
$showForm = true;
if (isset($_SESSION['success']) && !isset($_GET['action'])) {
    $showForm = false;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Registration</title>

<style>

/* NAVBAR */
.navbar {
    margin-top: 30px;
    background: #92a3b3;
    padding: 10px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
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
    margin: 40px auto;
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
    margin-top: 30px;
    color: red;
    text-align: center;
    font-weight: bold;
}

.success-box {
    width: 500px;
    margin: 150px auto;
    background: #e6f4ea;
    border-left: 6px solid #28a745;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0px 0px 10px #aaa;
    color: #155724;
}

.success-box h2 {
    font-size: 24px;
    margin-bottom: 15px;
}

.success-box p {
    font-size: 18px;
    margin-bottom: 20px;
}

.success-box a {
    display: inline-block;
    padding: 10px 20px;
    background: #0682ff;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
}

.success-box a:hover {
    background: #0056b3;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-links">
        <a href="index.php">Registration</a>
        <a href="welcome.php">View Records</a>
    </div>
</div>

<?php 
// SHOW SUCCESS MESSAGE IF FORM SUBMITTED
if (!$showForm && isset($_SESSION['success'])) { 
?>
<div class="success-box">
    <h2>🎉 <?php echo $_SESSION['success']; ?></h2>
    <p>Your data has been saved successfully.</p>
    <a href="index.php?action=retry">Register Another Entry</a>
</div>
<?php 
} 
?>

<!-- ERROR MESSAGE -->
<?php 
if ($error != "") { 
    echo '<p class="error">'.$error.'</p>'; 
} 
?>

<!-- FORM -->
<?php 
if ($showForm) { 
?>
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

<?php 
// Clear success message when showing form
unset($_SESSION['success']);
} 
?>

</body>
</html>
