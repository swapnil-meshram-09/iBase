<?php
session_start();
include "db.php";

if (!isset($_SESSION['last_id'])) {
    header("Location: index.html");
    exit();
}

$id = $_SESSION['last_id'];

$query = "SELECT * FROM registrations WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Fetch Error: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);

// Convert Date Format
$start_date = date("d - m - Y", strtotime($data['start_date']));
$end_date = date("d - m - Y", strtotime($data['end_date']));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>

    <style>
        body {
            background: #dde3ea;
            font-family: Arial, sans-serif;
        }

        .box {
            width: 400px;
            background: white;
            margin: 70px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #aaa;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            font-size: 15px;
        }
    </style>
</head>

<body>

<div class="box">
    <h1>Welcome</h1>

    <p><b>Title:</b> <?php echo $data['title']; ?></p>
    <p><b>Description:</b> <?php echo $data['description']; ?></p>
    <p><b>Start Date:</b> <?php echo $start_date; ?></p>
    <p><b>End Date:</b> <?php echo $end_date; ?></p>
</div>

</body>
</html>
