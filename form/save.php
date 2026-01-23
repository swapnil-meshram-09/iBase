<?php
session_start();
include "db.php";

$error = "";

// FORM SUBMIT HANDLING
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $description = trim($_POST['desciption']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validation
    if (empty($title) || empty($description) || empty($start_date) || empty($end_date)) {
        $error = "All fields are required!";
    } 
    else if (strtotime($start_date) > strtotime($end_date)) {
        $error = "Start date must be less than End date!";
    } 
    else {

        // Insert Data
        $sql = "INSERT INTO registrations(title, description, start_date, end_date) 
                VALUES ('$title','$description','$start_date','$end_date')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['last_id'] = mysqli_insert_id($conn);
            header("Location: welcome.php");
            exit();
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>

    <style>

        body {
            background: #dde3ea;
            font-family: Arial, sans-serif;
        }

        #formPage {
            border-radius: 20px;
            height: 500px;
            width: 500px;
            margin: 40px auto;
            padding: 10px;
            box-shadow: 0px 0px 10px #aaa;
            background-color: white;
        }

        .title {
            text-align: center;
        }

        .container {
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .inputs {
            border: none;
            border-radius: 5px;
            background-color: whitesmoke;
            padding: 10px;
            font-size: 14px;
        }

        .button {
            display: block;
            margin: auto;
            margin-top: 20px;
            background-color: rgb(6, 130, 255);
            padding: 15px 30px;
            border: none;
            border-radius: 15px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .button:hover {
            background-color: rgb(0, 110, 220);
        }

        .labels {
            font-size: 16px;
            font-weight: 600;
        }

        .error {
            text-align: center;
            color: red;
            font-weight: bold;
        }

    </style>
</head>

<body>

<form method="POST" id="formPage">

    <h1 class="title">Registration Form</h1>

    <?php if($error != "") { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <div class="container">

        <label class="labels">Title</label>
        <input class="inputs" type="text" name="title" placeholder="Enter title" required>

        <label class="labels">Description</label>
        <input class="inputs" type="text" name="desciption" placeholder="Enter description" required>

        <label class="labels">Start Date</label>
        <input class="inputs" type="date" name="start_date" required>

        <label class="labels">End Date</label>
        <input class="inputs" type="date" name="end_date" required>

    </div>

    <button class="button" type="submit">Submit</button>

</form>

</body>
</html>
