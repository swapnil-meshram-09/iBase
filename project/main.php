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
.tabs {
    margin: 30px 0;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}

.tab {
    padding: 10px 16px;
    border-radius: 10px;
    border: none;
    background: #f2f2f2;
    cursor: pointer;
    font-weight: bold;
}

.tab:hover {
    background: black;
    color: white;
}
</style>
</head>

<body>

<div class="tabs">
    <button class="tab" onclick="location.href='main.php'">Home</button>

    <!-- Student -->
    <button class="tab" onclick="location.href='studentLogin/login.php'">
        Student Login
    </button>

    <!-- User -->
    <button class="tab" onclick="location.href='user/user.php'">
        User Login
    </button>

    <!-- Admin -->
    <button class="tab" onclick="location.href='admin/add_user.php'">
        Add Student
    </button>

    <button class="tab" onclick="location.href='admin/.php'">
        Add User
    </button>

    <button class="tab" onclick="location.href='addfaculty/addFaculty.php'">
        Add Faculty
    </button>

    <button class="tab" onclick="location.href='admin/view_faculty.php'">
        View Student
    </button>

    <button class="tab" onclick="location.href='admin/view_student.php'">
        View Faculty
    </button>
</div>

</body>
</html>
