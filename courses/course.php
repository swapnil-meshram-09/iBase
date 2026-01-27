<?php
include "db.php";

/* FETCH LEFT SIDE DATA */
$query1 = "SELECT * FROM page1_data ORDER BY id DESC LIMIT 1";
$res1 = mysqli_query($conn,$query1);
$page1 = mysqli_fetch_assoc($res1);

/* INSERT RIGHT SIDE DATA */
$msg = "";

if(isset($_POST['save'])){

$field1 = $_POST['field1'];
$field2 = $_POST['field2'];
$field3 = $_POST['field3'];
$field4 = $_POST['field4'];
$field5 = $_POST['field5'];

if(empty($field1) || empty($field2)){

    $msg = "Please Fill Required Fields";

}else{

$insert = "INSERT INTO page2_data (field1,field2,field3,field4,field5) 
VALUES ('$field1','$field2','$field3','$field4','$field5')";

mysqli_query($conn,$insert);

$msg = "Data Saved Successfully";

}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Learning Dashboard</title>

<style>

/* BODY */
body{
    background:#eef1f5;
    font-family:Segoe UI;
    margin:0;
}

/* CONTAINER */
.container{
    width:90%;
    max-width:1300px;
    height:600px;
    background:white;
    margin:40px auto;
    display:flex;
    border-radius:10px;
    box-shadow:0 0 15px rgba(0,0,0,0.2);
}

/* LEFT PANEL */
.left{
    width:50%;
    padding:30px;
    background:#f9f9f9;
    border-right:2px solid #ddd;
}

.left h2{
    margin-bottom:15px;
}

.left-box{
    border:2px solid black;
    padding:20px;
    height:450px;
    font-size:18px;
}

/* RIGHT PANEL */
.right{
    width:50%;
    padding:30px;
}

.right h2{
    margin-bottom:20px;
}

/* INPUT */
input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    font-size:16px;
    border:2px solid #333;
    border-radius:6px;
}

/* BUTTON */
button{
    padding:12px 30px;
    font-size:16px;
    background:#007bff;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#0056b3;
}

/* MESSAGE */
.msg{
    margin-bottom:10px;
    color:green;
    font-weight:bold;
}

/* RESPONSIVE */
@media(max-width:900px){

.container{
    flex-direction:column;
    height:auto;
}

.left,.right{
    width:100%;
}

}

</style>
</head>

<body>

<div class="container">

<!-- LEFT SIDE -->
<div class="left">

<h2>Course Details</h2>

<div class="left-box">

<p><b>Aim:</b> <?php echo $page1['aim']; ?></p><br>

<p><b>Topic:</b> <?php echo $page1['topic']; ?></p><br>

<p><b>Duration:</b> <?php echo $page1['duration']; ?></p>

</div>

</div>


<!-- RIGHT SIDE -->
<div class="right">

<h2>Python Module Entry</h2>

<div class="msg"><?php echo $msg; ?></div>

<form method="POST">

<input type="text" name="field1" placeholder="Module Name" required>
<input type="text" name="field2" placeholder="Trainer Name" required>
<input type="text" name="field3" placeholder="Batch Time">
<input type="text" name="field4" placeholder="Lab Room">
<input type="text" name="field5" placeholder="Notes Link">

<button type="submit" name="save">Save Data</button>

</form>

</div>

</div>

</body>
</html>
