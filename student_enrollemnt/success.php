<?php
session_start();
include "db.php";

if (!isset($_SESSION['last_id'])) {
    header("Location: enroll.php");
    exit();
}

$id = $_SESSION['last_id'];

$query = "SELECT * FROM student_enrollment WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Fetch Error : " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>Enrollment Success</title>

<style>

body {
    background:#dde3ea;
    font-family: Arial;
}

.box {
    width:450px;
    margin:60px auto;
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0px 0px 10px #aaa;
}

h2 {
    text-align:center;
}

p {
    font-size:15px;
}

</style>

</head>

<body>

<div class="box">

<h2>Enrollment Successful ✅</h2>

<p><b>Name:</b> <?php echo $data['name']; ?></p>
<p><b>Contact:</b> <?php echo $data['contact']; ?></p>
<p><b>College:</b> <?php echo $data['college_name']; ?></p>
<p><b>Department:</b> <?php echo $data['department']; ?></p>
<p><b>Year:</b> <?php echo $data['year']; ?></p>
<p><b>HOD Name:</b> <?php echo $data['hod_name']; ?></p>
<p><b>HOD Contact:</b> <?php echo $data['hod_contact']; ?></p>

</div>

</body>
</html>
