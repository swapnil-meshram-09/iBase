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

body{
    font-family: Arial, sans-serif;
    background:#f4f6fb;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}

/* from second code */
.container{
    display:flex;
    gap:40px;
}

/* from first code, adapted */
.cards{
    display:flex;
    gap:40px;
}

.card{
    width:260px;
    height:180px;
    background:#fff;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.12);
    padding:20px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    transition:.3s;
}

.card:hover{
    transform:translateY(-8px);
}

/* colors */
.blue{ border-top:6px solid #2563eb; }
.purple{ border-top:6px solid #7c3aed; }

.card h2{
    margin:10px 0;
}

.card p{
    font-size:14px;
    color:#555;
    margin-bottom:15px;
    text-align:center;
}

/* button */
.btn{
    text-decoration:none;
    padding:8px 16px;
    border-radius:8px;
    color:#fff;
    font-weight:600;
}

.blue .btn{ background:#2563eb; }
.purple .btn{ background:#7c3aed; }
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

<div class="container">
    <div class="cards">

        <div class="card blue">
            <h2>Admin Module</h2>
            <p>Create courses & manage students</p>
            <a href="admin/admin.php" class="btn">Access Dashboard</a>
        </div>

        <div class="card purple">
            <h2>Student Registration</h2>
            <p>Register & pay online</p>
            <a href="student/student.php" class="btn">Register Now</a>
        </div>

    </div>
</div>

</body>
</html>
