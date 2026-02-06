<?php
session_start();
include "db.php";

/* ================= LEFT SIDE DATA ================= */
$query1 = "SELECT * FROM page1_data ORDER BY id DESC LIMIT 1";
$res1 = mysqli_query($conn,$query1);
$page1 = mysqli_fetch_assoc($res1);

/* ================= MESSAGE HANDLING ================= */
$error        = $_SESSION['error'] ?? "";
$success      = $_SESSION['success'] ?? "";
$whatsappLink = $_SESSION['whatsapp'] ?? "";

unset($_SESSION['error'], $_SESSION['success'], $_SESSION['whatsapp']);

/* ================= FORM SUBMISSION ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $course_name   = $_POST['course_name'] ?? '';
    $course_amount = $_POST['course_amount'] ?? '';
    $phone         = trim($_POST['phone'] ?? '');

    if (empty($course_name) || empty($course_amount) || empty($phone)) {
        $_SESSION['error'] = "All fields are required!";
    }
    else if (!is_numeric($course_amount)) {
        $_SESSION['error'] = "Amount must be numeric!";
    }
    else if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $_SESSION['error'] = "Phone number must be 10 digits!";
    }
    else {

        $course_name_safe   = mysqli_real_escape_string($conn,$course_name);
        $course_amount_safe = mysqli_real_escape_string($conn,$course_amount);
        $phone_safe         = mysqli_real_escape_string($conn,$phone);

        $sql = "INSERT INTO payments (course_name, course_amount, phone)
                VALUES ('$course_name_safe','$course_amount_safe','$phone_safe')";

        if (mysqli_query($conn,$sql)) {

            $message = "Hello! Your payment for $course_name course of ₹$course_amount is received successfully. Thank you!";
            $_SESSION['whatsapp'] = "https://wa.me/91$phone?text=" . urlencode($message);
            $_SESSION['success']  = "Redirecting to WhatsApp...";
        }
        else {
            $_SESSION['error'] = "Database Error: " . mysqli_error($conn);
        }
    }

    /* 🔑 POST → REDIRECT → GET */
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Learning Dashboard</title>

<style>
body{
    background:#eef1f5;
    font-family:Segoe UI;
    margin:0;
}
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
.left{
    width:50%;
    padding:30px;
    background:#f9f9f9;
    border-right:2px solid #ddd;
}
.left-box{
    border:2px solid #000;
    padding:20px;
    height:420px;
    font-size:18px;
}
.right{
    width:50%;
    padding:30px;
}
input,select{
    margin-top:10px;
    width:90%;
    padding:12px;
    margin-bottom:15px;
    margin-left:15px;
    font-size:16px;
    border:2px solid #333;
    border-radius:6px;
}
select{
    width:95%;
}
button{
    margin-top:15px;
    padding:12px;
    width:95%;
    font-size:16px;
    background:#25D366;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    margin-left:15px;
}
button:hover{
    background:#128C7E;
}
.error{
    background:#ffe1e1;
    color:#c40000;
    padding:10px;
    border-radius:5px;
    margin-bottom:10px;
}
.success{
    background:#e1ffe9;
    color:#0a7d2b;
    padding:10px;
    border-radius:5px;
    margin-bottom:10px;
}
@media(max-width:900px){
    .container{flex-direction:column;height:auto;}
    .left,.right{width:100%;}
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

<!-- LEFT SIDE -->
<div class="left">
    <h2>Course Details</h2>
    <div class="left-box">
        <p><b>Aim:</b> <?= htmlspecialchars($page1['aim'] ?? '') ?></p><br>
        <p><b>Topic:</b> <?= htmlspecialchars($page1['topic'] ?? '') ?></p><br>
        <p><b>Duration:</b> <?= htmlspecialchars($page1['duration'] ?? '') ?></p>
    </div>
</div>

<!-- RIGHT SIDE -->
<div class="right">
<h2>Course Payment</h2>

<?php if($error){ ?>
    <div class="error"><?= $error ?></div>
<?php } ?>

<?php if($success){ ?>
    <div class="success"><?= $success ?></div>
    <script>
        setTimeout(function(){
            window.location.href = "<?= $whatsappLink ?>";
        },1500);
    </script>
<?php } ?>

<form method="POST" autocomplete="off">

    <select name="course_name" required>
        <option value="">Select Course</option>
        <option value="Python">Python</option>
        <option value="Java">Java</option>
        <option value="PHP">PHP</option>
        <option value="JavaScript">JavaScript</option>
    </select>

    <input type="text" name="phone" placeholder="Enter Mobile Number"
           maxlength="10" oninput="onlyNumber(this)" required>

    <input type="text" name="course_amount" placeholder="Enter Amount"
           maxlength="6" oninput="onlyNumber(this)" required>

    <button type="submit">Send WhatsApp</button>

</form>
</div>
</div>

</body>
</html>
