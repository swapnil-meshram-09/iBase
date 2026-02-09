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
    font-size: 14px;
    margin-top: 10px;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: left;
}
th {
    background: #f2f2f2;
}

/* ===== ONLY TAB CSS (AS REQUESTED) ===== */
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

<!-- Tabs -->
<!-- <div class="tabs">
    <a href="?tab=create"><button class="tab">Create Program</button></a>
    <a href="?tab=view"><button class="tab">Courses</button></a>
</div> -->

<div class="card">
<h2>Students</h2>

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
