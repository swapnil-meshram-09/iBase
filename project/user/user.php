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
    padding: 10px 18px;
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

    <!-- User -->
    <button class="tab" onclick="location.href='createProgram.php'">
        Create Program
    </button>

    <button class="tab" onclick="location.href='.php'">
        Add Student
    </button>

    <button class="tab" onclick="location.href='viewStudent.php'">
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
