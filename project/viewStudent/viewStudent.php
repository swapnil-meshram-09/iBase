<?php
include "../db.php";
$currentTab = basename($_SERVER['PHP_SELF']);

/* Fetch students from useraddstudent table */
$students = mysqli_query($conn, "SELECT * FROM addstudent ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>View Students</title>


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

<!-- <div class="tabs">
    <a class="tab <?= $currentTab=='userCreateProgram.php' ? 'active' : '' ?>" href="userCreateProgram.php">Create Program</a>
    <a class="tab <?= $currentTab=='userViewProgram.php' ? 'active' : '' ?>" href="userViewProgram.php">View Program</a>
    <a class="tab <?= $currentTab=='userAddStudent.php' ? 'active' : '' ?>" href="userAddStudent.php">Add Student</a>
    <a class="tab <?= $currentTab=='userViewStudent.php' ? 'active' : '' ?>" href="userViewStudent.php">View Student</a>
    <a class="tab <?= $currentTab=='userAddFaculty.php' ? 'active' : '' ?>" href="userAddFaculty.php">Add Faculty</a>
    <a class="tab <?= $currentTab=='userViewFaculty.php' ? 'active' : '' ?>" href="userViewFaculty.php">View Faculty</a>
</div> -->

<div class="tableBox">

<h2>View Student Details</h2>

<table>
<tr>
    <th>Name</th>
    <th>Mobile</th>
    <th>College</th>
    <th>Department</th>
    <th>Year</th>
    <th>HOD Name</th>
    <th>HOD Contact</th>
    <th>Created At</th>
</tr>

<?php while($s = mysqli_fetch_assoc($students)): ?>
<tr>
    <td><?= htmlspecialchars($s['name']) ?></td>
    <td><?= htmlspecialchars($s['contact']) ?></td>
    <td><?= htmlspecialchars($s['college_name']) ?></td>
    <td><?= htmlspecialchars($s['department']) ?></td>
    <td><?= htmlspecialchars($s['year']) ?></td>
    <td><?= htmlspecialchars($s['hod_name']) ?></td>
    <td><?= htmlspecialchars($s['hod_contact']) ?></td>
    <td><?= $s['created_at'] ?></td>
</tr>
<?php endwhile; ?>

</table>

</div>

</body>
</html>
