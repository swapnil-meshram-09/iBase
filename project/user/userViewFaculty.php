<?php
session_start();
include "../db.php";
// Fetch faculty data
$facultyResult = mysqli_query(
    $conn,
    "SELECT faculty_name, designation, department, contact, state, city, region, college_name, college_address 
     FROM userAddFaculty 
     ORDER BY id DESC"
);

// $error = "";

// $name       = $_SESSION['student_name'] ?? "";
// $contact    = $_SESSION['student_mobile'] ?? "";
// $course_id  = $_SESSION['course_id'] ?? "";

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {

//     $name        = trim($_POST['name']);
//     $contact     = trim($_POST['contact']);
//     $college     = trim($_POST['college']);
//     $department  = $_POST['department'];
//     $year        = $_POST['year'];
//     $hod_name    = trim($_POST['hod_name']);
//     $hod_contact = trim($_POST['hod_contact']);

//     if (
//         empty($name) || empty($contact) || empty($college) ||
//         empty($department) || empty($year) ||
//         empty($hod_name) || empty($hod_contact)
//     ) {
//         $error = "All fields are required!";
//     }
//     elseif (!preg_match("/^[A-Za-z ]+$/", $name)) {
//         $error = "Student name must contain only letters!";
//     }
//     elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
//         $error = "Student contact must be exactly 10 digits!";
//     }
//     elseif (!preg_match("/^[A-Za-z ]+$/", $hod_name)) {
//         $error = "HOD name must contain only letters!";
//     }
//     elseif (!preg_match("/^[0-9]{10}$/", $hod_contact)) {
//         $error = "HOD contact must be exactly 10 digits!";
//     }
//     else {

//         $check = mysqli_query(
//             $conn,
//             "SELECT id FROM student_enrollment WHERE contact='$contact'"
//         );

//         if (mysqli_num_rows($check) == 0) {
//             mysqli_query(
//                 $conn,
//                 "INSERT INTO student_enrollment
//                 (name, contact, college_name, department, year, hod_name, hod_contact)
//                 VALUES
//                 ('$name','$contact','$college','$department','$year','$hod_name','$hod_contact')"
//             );
//         }

//         $_SESSION['student_name']   = $name;
//         $_SESSION['student_mobile'] = $contact;
//         $_SESSION['course_id']      = $course_id;

//         // Redirect to enroll page after registration
//         // header("Location: enroll.php");
//         exit;
    // }
// }

// Determine current page for active tab
$currentTab = basename($_SERVER['PHP_SELF']); // 'registration.php'

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
/* 
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
/* .tab.active {
    background: black;
    color: white;
} */

/* Disabled tab if not logged in */
/* .tab.disabled {
    pointer-events: none;
    opacity: 0.5;
}  */

/* Form */
/* #formBox {
    width:420px;
    margin:auto;
    margin-top: 50px;
    padding:25px;
    background:#fff;
    border-radius:15px;
    box-shadow:0 0 10px #aaa;
} */

.tableBox {
    width: 80%;
    margin: auto;
    margin-top: 40px;
    background: #fff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 0 10px #aaa;
    overflow-x: auto;
}

h2{
    text-align:center;
    margin-top: 0px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 14px;
}

th, td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
}

th {
    background: #000;
    color: #fff;
    margin-top:0px;
}

tr:nth-child(even) {
    background: #f2f2f2;
}




.error {
    text-align: center;
    color: red;
    font-weight: bold;
}
</style>

</head>

<body>

<!-- Tabs -->
<!-- <div class="tabs">
    <a class="tab <?= $currentTab=='login.php' ? 'active' : '' ?>" href="login.php">Login</a>
    <a class="tab <?= $currentTab=='registration.php' ? 'active' : '' ?>" href="registration.php">Registration</a>
    <a class="tab <?= $currentTab=='enroll.php' ? 'active' : '' ?>" href="enroll.php">Enroll</a>
    <a class="tab <?= $currentTab=='dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
</div> -->

<!-- Registration Form -->
<div class="tableBox">
    <h2>Faculty List</h2>

    <table>
        <tr>
            <th>Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Contact</th>
            <th>State</th>
            <th>City</th>
            <th>Region</th>
            <th>College</th>
            <th>Address</th>
        </tr>

        <?php if (mysqli_num_rows($facultyResult) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($facultyResult)) { ?>
                <tr>
                    <td><?= $row['faculty_name'] ?></td>
                    <td><?= $row['designation'] ?></td>
                    <td><?= $row['department'] ?></td>
                    <td><?= $row['contact'] ?></td>
                    <td><?= $row['state'] ?></td>
                    <td><?= $row['city'] ?></td>
                    <td><?= $row['region'] ?></td>
                    <td><?= $row['college_name'] ?></td>
                    <td><?= $row['college_address'] ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="9">No faculty records found</td>
            </tr>
        <?php } ?>
    </table>
</div>





</body>
</html>


