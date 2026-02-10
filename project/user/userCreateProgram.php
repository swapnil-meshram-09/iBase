<?php
include "../db.php";
$currentTab = basename($_SERVER['PHP_SELF']); // 'login.php'

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
    width: 450px;
    margin: auto;
    margin-top: 30px;
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

<!-- Tabs -->
<!-- Tabs -->
<div class="tabs">
    <a class="tab <?= $currentTab=='create' ? 'active' : '' ?>" href="?tab=create">
        Create Program
    </a>

    <a class="tab <?= $currentTab=='view' ? 'active' : '' ?>" href="?tab=view">
        View Program
    </a>
</div>


<div class="card">

<?php if($tab=='create'): ?>
<h2>Create Program</h2>
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
    <select name="duration" required>
        <option value="">Select</option>
        <option value="1 month">1 month</option>
        <option value="3 months">3 months</option>
        <option value="5 months">5 months</option>
    </select>

    <label>Amount</label>
    <input type="text" name="amount" required>

    <button class="save" name="create_course">Create Course</button>
</form>
<?php endif; ?>


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
