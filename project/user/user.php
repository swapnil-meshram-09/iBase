<?php
session_start();
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
.tabs { margin: 30px 0; display: flex; gap: 10px; justify-content: center; font-size: 13.5px; }
.tab { padding: 10px 18px; border-radius: 10px; background: #f2f2f2; font-weight: bold; text-decoration: none; color: black; }
.tab:hover { background: black; color: white; }
.tab.active { background: black; color: white; }
</style>
</head>

<body>

<div class="tabs">

    <a class="tab <?= $currentTab=='userCreateProgram.php' ? 'active' : '' ?>" href="userCreateProgram.php">Create Program</a>
    <a class="tab <?= $currentTab=='userViewProgram.php' ? 'active' : '' ?>" href="userViewProgram.php">View Program</a>
    <a class="tab <?= $currentTab=='userViewProgram.php' ? 'active' : '' ?>" href="userViewProgram.php">View Program</a>
    <!-- User -->

    <button class="tab" onclick="location.href='userViewProgram.php'">
        
    </button>

    <button class="tab" onclick="location.href='userAddStudent.php'">
        Add Student
    </button>

    <button class="tab" onclick="location.href='userViewStudent.php'">
        View Student
    </button>

    <button class="tab" onclick="location.href='userAddFaculty.php'">
        Add Faculty
    </button>

    <button class="tab" onclick="location.href='userViewFaculty.php'">
        View Faculty
    </button>

</div>

</body>
</html>
