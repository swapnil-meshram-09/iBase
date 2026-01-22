<?php
session_start();

if (!isset($_SESSION['user_data'])) {
    header("Location: form.html");
    exit();
}

$data = $_SESSION['user_data'];
?>
