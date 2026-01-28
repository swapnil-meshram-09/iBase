<!-- <?php 
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
</html> -->


<?php
// ---------------- DATABASE CONNECTION ----------------
$host = "localhost";
$user = "root";
$password = ""; // MySQL root password
$dbname = "payment_db";

$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) die("Connection failed: " . mysqli_connect_error());

// ---------------- CREATE TABLE ----------------
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    course_name VARCHAR(50) NOT NULL,
    course_amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// ---------------- HANDLE FORM SUBMISSION ----------------
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $course = mysqli_real_escape_string($conn, $_POST['course_name']);
    $amount = mysqli_real_escape_string($conn, $_POST['course_amount']);

    if (!empty($name) && !empty($mobile) && !empty($course) && !empty($amount)) {
        $insert = mysqli_query($conn, "INSERT INTO registrations (student_name, mobile, course_name, course_amount) VALUES ('$name','$mobile','$course',$amount)");
        if ($insert) {
            $message = "Registration successful!";
        } else {
            $message = "Database error: " . mysqli_error($conn);
        }
    } else {
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Registration</title>
<style>
body { font-family: Arial, sans-serif; background: #f0f0f0; }
.container { max-width: 400px; margin:50px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 10px rgba(0,0,0,0.1);}
input, select, button { width:100%; padding:10px; margin:8px 0; border-radius:5px; border:1px solid #ccc; box-sizing:border-box;}
button { background:#007bff; color:#fff; border:none; cursor:pointer;}
button:hover { background:#0056b3; }
.message { padding:10px; margin-bottom:10px; border-radius:5px; }
.success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
</style>
<script>
function onlyNumber(input) {
    input.value = input.value.replace(/[^0-9]/g,'');
}
</script>
</head>
<body>
<div class="container">
    <h2>Student Registration</h2>

    <?php if($message): ?>
        <div class="message <?php echo ($message=='Registration successful!') ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="student_name" placeholder="Student Name" required>
        <input type="tel" name="mobile" placeholder="Mobile Number" oninput="onlyNumber(this)" maxlength="10" required>

        <select name="course_name" required>
            <option value="">Select Department</option>
            <option value="Python">Python</option>
            <option value="Java">Java</option>
            <option value="JavaScript">JavaScript</option>
            <option value="PHP">PHP</option>
        </select>

        <input type="text" name="course_amount" placeholder="Enter Amount" oninput="onlyNumber(this)" required>

        <button type="submit">Register</button>
    </form>
</div>
</body>
</html>
