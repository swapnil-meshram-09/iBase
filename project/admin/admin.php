<?php
include "../db.php";
$tab = $_GET['tab'] ?? 'create';

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

$courses = mysqli_query($conn,"SELECT * FROM courses ORDER BY id DESC");
$students = mysqli_query($conn,"SELECT * FROM registrations ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #dde3ea;
    padding: 0;
    margin: 0;
}

a {
    display: inline-block;
    margin: 12px 0;
    margin-left: 90%;
    color: black;
    text-decoration: none;
    font-weight: bold;
}
a:hover { text-decoration: underline; }

/* Container */
.card {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    max-width: 500px;
    margin: 20px auto;
}

/* Tabs */
.tabs {
    display: flex;
    gap: 8px;
    margin-left: 31%;
    margin-bottom: 20px;
    justify-content: center;
    width: 38%;
}
.tab {
    flex: 1;
    padding: 10px;
    border-radius: 10px;
    border: none;
    background: #f2f2f2;
    cursor: pointer;
    font-size: 14px;
    text-align: center;
    transition: all 0.2s;
}
.tab.active {
    background: black;
    color: white;
    font-weight: 600;
}
.tab:hover:not(.active) {
    background: #e0e7ff;
}

/* Headings */
h2 {
    margin-top: 0;
    margin-bottom: 15px;
    text-align: center;
    font-size: 18px;
    color: #111827;
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
    font-size: 14px;
    box-sizing: border-box;
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
    transition: background 0.2s;
}
button.save:hover {
    background: #12833b;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    margin-top: 10px;
}
th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
th {
    background: #f2f2f2;
    font-weight: 600;
}
tr:hover {
    background: #f9fafb;
}
</style>

</head>
<body>

<a href="../index.php">⬅ Back</a>

<div class="tabs">
    <button class="tab <?= $tab=='create'?'active':'' ?>" onclick="location.href='?tab=create'">➕ Create</button>
    <button class="tab <?= $tab=='view'?'active':'' ?>" onclick="location.href='?tab=view'">📋 Courses</button>
    <button class="tab <?= $tab=='students'?'active':'' ?>" onclick="location.href='?tab=students'">👥 Students</button>
</div>

<div class="card">

<?php if($tab=='create'): ?>
<h2>New Course</h2>
<form method="post">
<label>Title</label>
<input name="title" required>

<label>Description</label>
<textarea name="description" required></textarea>

<label>Start</label>
<input type="date" name="start_date" required>

<label>End</label>
<input type="date" name="end_date" required>

<label>Duration</label>
<input name="duration" required>

<label>Amount</label>
<input type="text" name="amount" required>

<button class="save" name="create_course">Create Course</button>
</form>
<?php endif; ?>

<?php if($tab=='view'): ?>
<h2>Courses</h2>
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

<?php if($tab=='students'): ?>
<h2>Students</h2>
<table>
<tr><th>Name</th><th>Course</th><th>Mobile</th></tr>
<?php while($s=mysqli_fetch_assoc($students)): ?>
<tr>
<td><?= $s['student_name'] ?></td>
<td><?= $s['course_title'] ?></td>
<td><?= $s['mobile'] ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php endif; ?>

</div>
</body>
</html>
