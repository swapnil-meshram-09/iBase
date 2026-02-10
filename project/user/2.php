<?php
include "../db.php";
$currentTab = basename($_SERVER['PHP_SELF']);

$courses = mysqli_query($conn,"SELECT * FROM courses ORDER BY id DESC");
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

</div>
</body>
</html>
