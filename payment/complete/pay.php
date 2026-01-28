<?php 
session_start();
include "db.php"; // Make sure this file contains your DB connection

$error = "";
$success = "";
$whatsappLink = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Course payment fields
    $course_name = $_POST['course_name'] ?? '';
    $course_amount = $_POST['course_amount'] ?? '';

    // WhatsApp fields
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if(empty($course_name) || empty($course_amount) || empty($phone) || empty($message)){
        $error = "All fields are required!";
    } else if(!is_numeric($course_amount)){
        $error = "Amount must be a number!";
    } else if(!preg_match('/^[0-9]{10}$/', $phone)){
        $error = "Phone number must be 10 digits!";
    } else {
        // Escape input to prevent SQL injection
        $course_name = mysqli_real_escape_string($conn, $course_name);
        $course_amount = mysqli_real_escape_string($conn, $course_amount);

        // Insert into database
        $sql = "INSERT INTO payments (course_name, course_amount, phone, message) 
                VALUES ('$course_name', '$course_amount', '$phone', '" . mysqli_real_escape_string($conn, $message) . "')";
        if(mysqli_query($conn, $sql)){
            $last_id = mysqli_insert_id($conn);
            $_SESSION['payment_id'] = $last_id; // Optional: store ID for later use
            $_SESSION['course_name'] = $course_name;
            $_SESSION['course_amount'] = $course_amount;

            // Generate WhatsApp link
            $countryCode = "91"; // change if needed
            $fullNumber = $countryCode . $phone;
            $whatsappLink = "https://wa.me/$fullNumber?text=" . urlencode($message);

            $success = "Payment details saved! Click the link below to send WhatsApp message.";
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
<title>Course Payment + WhatsApp</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f2f2f2;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
    width: 350px;
}
input, select, button {
    padding: 10px;
    margin: 10px 0;
    width: 100%;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}
button {
    background: #25D366;
    color: white;
    border: none;
    cursor: pointer;
}
button:hover {
    background: #128C7E;
}
.error { color: red; font-size: 14px; }
.success { color: green; font-size: 14px; }
a.whatsapp-link {
    display: block;
    margin-top: 15px;
    text-align: center;
    background: #25D366;
    color: white;
    padding: 10px;
    text-decoration: none;
    border-radius: 5px;
}
a.whatsapp-link:hover {
    background: #128C7E;
}
</style>
<script>
function onlyNumber(input){
    input.value = input.value.replace(/[^0-9]/g,'');
}
</script>
</head>
<body>

<div class="container">
    <h2>Course Payment + WhatsApp</h2>

    <?php if($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if($success): ?>
        <p class="success"><?= $success ?></p>
        <a class="whatsapp-link" href="<?= $whatsappLink ?>" target="_blank">Send WhatsApp Message</a>
    <?php endif; ?>

    <form method="post">
        <!-- Course Payment -->
        <select name="course_name" required>
            <option value="">Select Course</option>
            <option value="Python">Python</option>
            <option value="Java">Java</option>
            <option value="JavaScript">JavaScript</option>
            <option value="PHP">PHP</option>
        </select>

        <input type="text" name="course_amount" oninput="onlyNumber(this)" maxlength="6" placeholder="Enter Amount" required>

        <!-- WhatsApp Fields -->
        <input type="text" name="phone" oninput="onlyNumber(this)" maxlength="10" placeholder="Enter 10-digit phone" required>
        <input type="text" name="message" placeholder="Enter WhatsApp message" required>

        <button type="submit">Submit</button>
    </form>
</div>

</body>
</html>
