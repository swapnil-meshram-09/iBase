<?php
session_start();
include "../db.php";
$currentTab = basename($_SERVER['PHP_SELF']);

// Fetch faculty data
$facultyResult = mysqli_query(
    $conn,
    "SELECT faculty_name, designation, department, contact, state, city, region, college_name, college_address 
     FROM addFaculty 
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

.tabs { margin: 30px 0; display: flex; gap: 10px; justify-content: center; font-size: 13.5px; }
.tab { padding: 10px 18px; border-radius: 10px; background: #f2f2f2; font-weight: bold; text-decoration: none; color: black; }
.tab:hover { background: black; color: white; }
.tab.active { background: black; color: white; }

</style>

</head>

<body>

<div class="tabs">
    <a class="tab <?= $currentTab=='main.php' ? 'active' : '' ?>" href="main.php">Home</a>
    <a class="tab <?= $currentTab=='studentLogin/login.php' ? 'active' : '' ?>" href="studentLogin/login.php">Student Login</a>
    <a class="tab <?= $currentTab=='user/userLogin.php' ? 'active' : '' ?>" href="user/userLogin.php">User Login</a>
    <a class="tab <?= $currentTab=='addStudent/addStudent.php' ? 'active' : '' ?>" href="addStudent/addStudent.php"> Add Student</a>
    <a class="tab <?= $currentTab=='addUser/addUserRegistration.php' ? 'active' : '' ?>" href="addUser/addUserRegistration.php"> Add User</a>
    <a class="tab <?= $currentTab=='addfaculty/addFaculty.php' ? 'active' : '' ?>" href="addfaculty/addFaculty.php"> Add Faculty</a>
    <a class="tab <?= $currentTab=='viewStudent/viewStudent.php' ? 'active' : '' ?>" href="viewStudent/viewStudent.php"> View Student</a>
    <a class="tab <?= $currentTab=='viewFaculty/viewFaculty.php' ? 'active' : '' ?>" href="viewFaculty/viewFaculty.php"> View Faculty</a>
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


