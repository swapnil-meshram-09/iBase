<?php 
session_start();
include "db.php"; // Make sure this file contains your DB connection

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $course_name = $_POST['course_name'] ?? '';
    $course_amount = $_POST['course_amount'] ?? '';

    if(empty($course_name) || empty($course_amount)){
        $error = "All fields are required!";
    } else if(!is_numeric($course_amount)){
        $error = "Amount must be a number!";
    } else {
        // Escape input to prevent SQL injection
        $course_name = mysqli_real_escape_string($conn, $course_name);
        $course_amount = mysqli_real_escape_string($conn, $course_amount);

        // Insert into database
        $sql = "INSERT INTO payments (course_name, course_amount) VALUES ('$course_name', '$course_amount')";
        if(mysqli_query($conn, $sql)){
            $last_id = mysqli_insert_id($conn);
            $_SESSION['payment_id'] = $last_id; // Store ID for QR code
            $_SESSION['course_name'] = $course_name;
            $_SESSION['course_amount'] = $course_amount;
            header("Location: generate_qr.php"); // Redirect to QR code page
            exit;
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Course Payment</title>
<script>
function onlyNumber(input){
    input.value = input.value.replace(/[^0-9]/g,'');
}
</script>
</head>
<body>

<h2>Pay for Course</h2>

<?php if($error): ?>
<p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="post">
    <select name="course_name" required>
        <option value="">Select Course</option>
        <option value="Python">Python</option>
        <option value="Java">Java</option>
        <option value="JavaScript">JavaScript</option>
        <option value="PHP">PHP</option>
    </select>
    <br><br>

    <input type="text" 
           name="course_amount" 
           oninput="onlyNumber(this)" 
           maxlength="6"
           placeholder="Enter Amount"
           required>
    <br><br>

    <button type="submit">Pay Now</button>
</form>

</body>
</html>
