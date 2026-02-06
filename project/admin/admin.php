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
body{
    font-family:Arial;
    background:#f1f5f9;
    padding:12px;
    font-size:14px;
    width: 70%;
    margin-left: 200px;
}

/* Tabs */
.tabs{
    display:flex;
    gap:8px;
    margin-bottom:14px;
}
.tab{
    flex:1;
    padding:8px;
    border-radius:8px;
    border:none;
    background:#e5e7eb;
    cursor:pointer;
    font-size:13px;
}
.tab.active{
    background:#2563eb;
    color:white;
    font-weight:600;
}

/* Card */
.card{
    background:#fff;
    border-radius:10px;
    padding:16px;
    box-shadow:0 6px 14px rgba(0,0,0,.08);
    width: 100%;
}

h2{
    margin:0 0 10px;
    font-size:16px;
}

/* Form */
label{
    font-size:12px;
}
input, textarea{
    width:100%;
    padding:8px;
    margin:4px 0 10px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:13px;
}
textarea{
    height:60px;
}
button.save{
    background:#2563eb;
    color:white;
    padding:8px;
    width:100%;
    border:none;
    border-radius:8px;
    font-size:14px;
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}
th,td{
    padding:8px;
    border-bottom:1px solid #eee;
}
th{
    background:#f9fafb;
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
<input type="number" name="amount" required>

<button class="save" name="create_course">Save</button>
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
