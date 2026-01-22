<?php
session_start();

// Check data exists
if (!isset($_SESSION['user_data'])) {
    header("Location: form.html");
    exit();
}

// Get backend data
$data = $_SESSION['user_data'];

// Load HTML template
$html = file_get_contents("welcome.html");

// Replace placeholders with backend values
$html = str_replace("{{title}}", $data['title'], $html);
$html = str_replace("{{description}}", $data['description'], $html);
$html = str_replace("{{start_date}}", $data['start_date'], $html);
$html = str_replace("{{end_date}}", $data['end_date'], $html);

// Display final page
echo $html;
?>
