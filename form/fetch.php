<?php
session_start();
include "db.php";

if (isset($_SESSION['last_id'])) {
    $id = $_SESSION['last_id'];

    $query = "SELECT * FROM registrations WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
    } else {
        die("Error fetching data from database");
    }

} else {
    header("Location: form.html");
    exit();
}
?>
