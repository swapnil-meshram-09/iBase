<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "db.php";

$query = "SELECT * FROM registrations ORDER BY id ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Fetch Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Welcome</title>

<style>

/* NAVBAR */
.navbar {
    background: #0682ff;
    padding: 12px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar h2 {
    color: white;
    margin: 0;
}

.nav-links a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: bold;
    padding: 6px 12px;
    border-radius: 6px;
}

.nav-links a:hover {
    background: rgba(255,255,255,0.2);
}

/* TABLE STYLE */

body {
    background: #dde3ea;
    font-family: Arial, sans-serif;
    margin: 0;
}

.box {
    width: 70%;
    margin: 100px auto;
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0px 0px 10px #aaa;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background: #0682ff;
    color: white;
}

.left-align {
    text-align: left;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-links">
        <a href="index.php">Registration</a>
        <a href="welcome.php">View Records</a>
    </div>
</div>

<div class="box">

<h2 align="center">All Records</h2>

<table>

<tr>
<th>ID</th>
<th>Title</th>
<th>Description</th>
<th>Start Date</th>
<th>End Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td class="left-align"><?php echo htmlspecialchars($row['title']); ?></td>
<td class="left-align"><?php echo nl2br(htmlspecialchars($row['description'])); ?></td>
<td><?php echo date("d-m-Y", strtotime($row['start_date'])); ?></td>
<td><?php echo date("d-m-Y", strtotime($row['end_date'])); ?></td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>
