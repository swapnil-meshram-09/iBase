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
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin</title>

<!-- ❌ CSS NOT CHANGED -->
<style>
/* same CSS you already have */
</style>
</head>

<body>

<div class="tabs">
    <a class="tab <?= $currentTab=='program_create.php' ? 'active' : '' ?>" href="program_create.php">
        Create Program
    </a>

    <a class="tab <?= $currentTab=='program_view.php' ? 'active' : '' ?>" href="program_view.php">
        View Program
    </a>
</div>

<div class="card">

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
    <input name="amount" required>

    <button class="save" name="create_course">Create Course</button>
</form>

</div>
</body>
</html>
