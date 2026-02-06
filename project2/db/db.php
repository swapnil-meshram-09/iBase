<?php
$conn = mysqli_connect("localhost","root","","ibase2");
if(!$conn){
    die("Database connection failed");
}
session_start();
?>
