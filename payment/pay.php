<?php 
   session_start();
   include "db.php";
   
   $course_name=$_POST['course_name']
   $course_amount=$_POST['course_amount']
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="pay.php" method='post'>
        <select name="course_name" id="" required>
            <option value="">Select Department</option>
            <option value="">Python</option>
            <option value="">Java</option>
            <option value="">JavaScript</option>
            <option value="">PHP</option>
        </select>

        <input type="text" 
               name='course_amount'       
               oninput="onlyNumber(this)"
               maxlength="2"
               inputmode="numeric"
               pattern="[0-9]{10}"
               placeholder="Enter Amount"
               required>

    </form>
    
</body>
</html>