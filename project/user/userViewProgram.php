<?php
include "../db.php";
$currentTab = basename($_SERVER['PHP_SELF']); // 'login.php'

$tab = $_GET['tab'] ?? 'view';

/* CREATE COURSE */
if(isset($_POST['create_course'])){
    mysqli_query($conn,"INSERT INTO courses 
    (title,description,start_date,end_date,duration,amount)
    VALUES (
        '".$_POST['title']."',
        '".$_POST['description']."',
        '".$_POST['start_date']."',
        '".$_POST['end_date']."',
        '".$_POST['duration']."',
        '".$_POST['amount']."'
    )");
}

$courses  = mysqli_query($conn,"SELECT * FROM courses ORDER BY id DESC");


?>


<!DOCTYPE html>
<html>
<head>
<title>Admin</title>

<style>
body {
    /* font-family: Arial, sans-serif; */
    background: #dde3ea;
    padding: 0;
    margin: 0;
}

/* Container */
.card {
    width: 450px;
    margin: auto;
    margin-top: 40px;
    background: white;
    padding: 25px;
    padding-top: 0.5px;
    border-radius: 15px;
    box-shadow: 0px 0px 10px #aaa;
    font-size: 15px;
}

/* Headings */
h2 {
    text-align: center;
    margin-top: 20px;
    margin-bottom: 10px;
    font-size: 24px;
    padding:0px;
}

/* Form */
label {
   font-weight: bold;
    margin-top: 10px;
    display: block;
    margin-left: 10px;
    
}
input, textarea, select {
     padding: 8px 12px;
    border: none;
    border-radius: 6px;
    background: #f2f2f2;
    width: 90%;
    margin-top: 10px;
    margin-left: 10px;
}

select {
    width: 95%;

}
textarea {
    height: 80px;
    resize: none;
}

button.save {
    margin-top: 15px;
    padding: 12px;
    background: #16a34a;
    border: none;
    color: white;
    border-radius: 10px;
    width: 95%;
    font-size: 16px;
    cursor: pointer;
    margin-left: 10px;
}
button.save:hover {
    background: #12833b;
}

/* Table */
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

/* ===== ONLY TAB CSS (AS REQUESTED) ===== */
.tabs { margin: 30px 0; display: flex; gap: 10px; justify-content: center; font-size: 13.5px; }
.tab { padding: 10px 18px; border-radius: 10px; background: #f2f2f2; font-weight: bold; text-decoration: none; color: black; }
.tab:hover { background: black; color: white; }

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

    <a class="tab <?= $currentTab=='userCreateProgram.php' ? 'active' : '' ?>" href="userCreateProgram.php">Create Program</a>
    <a class="tab <?= $currentTab=='userViewProgram.php' ? 'active' : '' ?>" href="userViewProgram.php">View Program</a>
    <a class="tab <?= $currentTab=='userAddStudent.php' ? 'active' : '' ?>" href="userAddStudent.php">Add Student</a>
    <a class="tab <?= $currentTab=='userViewStudent.php' ? 'active' : '' ?>" href="userViewStudent.php">View Student</a>
    <a class="tab <?= $currentTab=='userAddFaculty.php' ? 'active' : '' ?>" href="userAddFaculty.php">Add Faculty</a>
    <a class="tab <?= $currentTab=='userViewFaculty.php' ? 'active' : '' ?>" href="userViewFaculty.php"> View Faculty</a>

</div>

<div class="card">

<?php if($tab=='view'): ?>
<h2>View Program</h2>
<table>
<tr><th>Title</th><th>Duration</th><th>Amount</th></tr>
<?php while($c=mysqli_fetch_assoc($courses)): ?>
<tr>
    <td><?= $c['title'] ?></td>
    <td><?= $c['duration'] ?></td>
    <td>₹<?= $c['amount'] ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php endif; ?>


</div>

</body>
</html>
