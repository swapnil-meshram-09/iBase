<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['desciption']) ? trim($_POST['desciption']) : '';
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

    // Validation
    if (empty($title) || empty($description) || empty($start_date) || empty($end_date)) {
        die("All fields are required!");
    } else if (strtotime($start_date) > strtotime($end_date)) {
        die("Start date must be less than End date!");
    } else {

        // Insert Data into Database
        $sql = "INSERT INTO registrations(title, description, start_date, end_date)
                VALUES ('$title','$description','$start_date','$end_date')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['last_id'] = mysqli_insert_id($conn);
            header("Location: welcome.php");
            exit();
        } else {
            die("Database Error: " . mysqli_error($conn));
        }
    }

} else {
    die("Invalid Request Method");
}
?>
