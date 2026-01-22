<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $description = trim($_POST['desciption']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validation
    if (empty($title) || empty($description) || empty($start_date) || empty($end_date)) {
        die("All fields are required!");
    }

    // Date validation
    if (strtotime($start_date) > strtotime($end_date)) {
        die("Start Date must be less than End Date!");
    }

    // Save data into session
    $_SESSION['user_data'] = [
        "title" => $title,
        "description" => $description,
        "start_date" => $start_date,
        "end_date" => $end_date
    ];

    // Redirect to welcome page
    header("Location: welcome.php");
    exit();

} else {
    echo "Invalid Request";
}
?>
