<?php
session_start();
$currentTab = basename($_SERVER['PHP_SELF']); // 'login.php'

?>

<!DOCTYPE html>
<html>
<head>
<style>
body {
    /* font-family: Arial, sans-serif; */
    background: #dde3ea;
    margin: 0;
}

/* Navbar buttons */
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

</body>
</html>
