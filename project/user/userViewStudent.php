<?php
include "../db.php";
$currentTab = basename($_SERVER['PHP_SELF']);

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
$students = mysqli_query($conn,"SELECT * FROM registrations ORDER BY id DESC");
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
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    max-width: 500px;
    margin: 20px auto;
    margin-top: 40px;
}

/* Headings */
h2 {
    margin-top: 0;
    margin-bottom: 15px;
    text-align: center;
    font-size: 18px;
}

/* Form */
label {
    display: block;
    font-weight: bold;
    font-size: 13px;
}
input, textarea, select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: none;
    border-radius: 8px;
    background: #f2f2f2;
}
textarea {
    height: 80px;
    resize: none;
}

button.save {
    width: 100%;
    padding: 12px;
    background: #16a34a;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
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

    <a class="tab <?= $currentTab=='userCreateProgram.php' ? 'active' : '' ?>" href="userCreateProgram.php">Create Program</a>
    <a class="tab <?= $currentTab=='userViewProgram.php' ? 'active' : '' ?>" href="userViewProgram.php">View Program</a>
    <a class="tab <?= $currentTab=='userAddStudent.php' ? 'active' : '' ?>" href="userAddStudent.php">Add Student</a>
    <a class="tab <?= $currentTab=='userViewStudent.php' ? 'active' : '' ?>" href="userViewStudent.php">View Student</a>
    <a class="tab <?= $currentTab=='userAddFaculty.php' ? 'active' : '' ?>" href="userAddFaculty.php">Add Faculty</a>
    <a class="tab <?= $currentTab=='userViewFaculty.php' ? 'active' : '' ?>" href="userViewFaculty.php"> View Faculty</a>

</div>

<div class="card">
<h2>View Student Details</h2>

<table>
<tr>
    <th>Name</th>
    
    <th>Mobile</th>
    <th>Course</th>
</tr>

<?php while($s = mysqli_fetch_assoc($students)): ?>
<tr>
    <td><?= $s['student_name'] ?></td>
    <td><?= $s['mobile'] ?></td>
    <td><?= $s['course_title'] ?></td>
</tr>
<?php endwhile; ?>

</table>



</div>

</body>
</html>
