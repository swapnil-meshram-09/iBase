<?php
session_start();
include "../db.php";
$currentTab = basename($_SERVER['PHP_SELF']); // 'registration.php'


// Fetch faculty data
$facultyResult = mysqli_query(
    $conn,
    "SELECT faculty_name, designation, department, contact, state, city, region, college_name, college_address 
     FROM userAddFaculty 
     ORDER BY id DESC"
);



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




</style>

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
<div class="tableBox">
    <h2>View Faculty Details</h2>

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


