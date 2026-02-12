<?php
session_start();
include "../db.php";

// Fetch student course enrollment data
$enrollmentResult = mysqli_query(
    $conn,
    "SELECT title, description, start_date, end_date, duration, amount, created_at
     FROM student_course_enrollment
     ORDER BY created_at DESC"
);

$currentTab = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>

<style>
body {
    background: #dde3ea;
    margin: 0px;
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
}

tr:nth-child(even) {
    background: #f2f2f2;
}


.tabs {
    margin: 30px 0;
    display: flex;
    gap: 10px;
    justify-content: center;
    font-size: 13.5px;
}

.tab {
    padding: 10px 18px;
    border-radius: 10px;
    background: #f2f2f2;
    font-weight: bold;
    text-decoration: none;
    color: black;
}

.tab:hover { background: black; color: white; }
.tab.active { background: black; color: white; }

</style>

</head>

<body>

<div class="tabs">
    <a class="tab <?= $currentTab=='enroll.php' ? 'active' : '' ?>" href="enroll.php">Enroll</a>
    <a class="tab <?= $currentTab=='dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
</div>

<div class="tableBox">
    <h2>Student Dashboard</h2>

    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Duration</th>
            <th>Amount</th>
        </tr>

        <?php if (mysqli_num_rows($enrollmentResult) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($enrollmentResult)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= date("d/m/Y", strtotime($row['start_date'])) ?></td>
                    <td><?= date("d/m/Y", strtotime($row['end_date'])) ?></td>
                    <td><?= htmlspecialchars($row['duration']) ?></td>
                    <td>₹ <?= number_format($row['amount'], 2) ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="7">No enrollment records found</td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
