<?php
include "db.php";

$query = "SELECT * FROM page1_data ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn,$query);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>Two Page Layout</title>

<style>

body {
    background:#ddd;
    font-family:Arial;
}

/* MAIN */
.container {
    width:850px;
    background:white;
    margin:40px auto;
    border:3px solid black;
    display:flex;
}

/* LEFT SIDE */
.left {
    width:50%;
    padding:20px;
    border-right:3px solid black;
}

.left-box {
    border:3px solid black;
    padding:15px;
    height:300px;
}

/* RIGHT SIDE */
.right {
    width:50%;
    padding:20px;
}

.right h3 {
    margin-bottom:10px;
}

/* INPUT */
input {
    width:100%;
    padding:8px;
    margin-bottom:12px;
    border:2px solid black;
}

/* BUTTON */
button {
    padding:10px 15px;
    background:black;
    color:white;
    border:none;
    cursor:pointer;
}

</style>
</head>

<body>

<div class="container">

<!-- LEFT SIDE (BACKEND DATA) -->
<div class="left">

    <div class="left-box">

        <p><b>Aim:</b> <?php echo $data['aim']; ?></p>
        <p><b>Topic:</b> <?php echo $data['topic']; ?></p>
        <p><b>Duration:</b> <?php echo $data['duration']; ?></p>

    </div>

</div>


<!-- RIGHT SIDE (FRONTEND STORE) -->
<div class="right">

<h3>Python</h3>

<input type="text" id="f1" placeholder="Enter Field 1">
<input type="text" id="f2" placeholder="Enter Field 2">
<input type="text" id="f3" placeholder="Enter Field 3">
<input type="text" id="f4" placeholder="Enter Field 4">
<input type="text" id="f5" placeholder="Enter Field 5">

<button onclick="saveData()">Save</button>

</div>

</div>


<script>

// SAVE TO FRONTEND (LocalStorage)
function saveData() {

    let data = {
        field1: document.getElementById("f1").value,
        field2: document.getElementById("f2").value,
        field3: document.getElementById("f3").value,
        field4: document.getElementById("f4").value,
        field5: document.getElementById("f5").value
    };

    localStorage.setItem("page2Data", JSON.stringify(data));

    alert("Data Saved In Browser Successfully!");
}


// LOAD SAVED DATA
window.onload = function(){

    let saved = localStorage.getItem("page2Data");

    if(saved){
        let obj = JSON.parse(saved);

        document.getElementById("f1").value = obj.field1;
        document.getElementById("f2").value = obj.field2;
        document.getElementById("f3").value = obj.field3;
        document.getElementById("f4").value = obj.field4;
        document.getElementById("f5").value = obj.field5;
    }

}

</script>

</body>
</html>
