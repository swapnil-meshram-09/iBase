<?php
session_start();
include "db.php";

/* ================= LEFT SIDE DATA ================= */
$query1 = "SELECT * FROM page1_data ORDER BY id DESC LIMIT 1";
$res1   = mysqli_query($conn, $query1);
$page1  = mysqli_fetch_assoc($res1);

/* ================= RIGHT SIDE : PAYMENT + WHATSAPP ================= */

$error = "";
$success = "";

/* META WHATSAPP SETTINGS */
$ACCESS_TOKEN   = "YOUR_META_ACCESS_TOKEN";
$PHONE_NUMBER_ID = "YOUR_PHONE_NUMBER_ID";

function sendWhatsAppMessage($to, $message, $token, $phoneId){

    $url = "https://graph.facebook.com/v18.0/$phoneId/messages";

    $data = [
        "messaging_product" => "whatsapp",
        "to" => $to,
        "type" => "text",
        "text" => ["body" => $message]
    ];

    $headers = [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_exec($ch);
    curl_close($ch);
}

/* FORM SUBMIT */
if(isset($_POST['pay_now'])){

    $course_name   = $_POST['course_name'] ?? "";
    $course_amount = $_POST['course_amount'] ?? "";
    $phone         = trim($_POST['phone'] ?? "");

    if(empty($course_name) || empty($course_amount) || empty($phone)){
        $error = "All fields are required!";
    }
    elseif(!is_numeric($course_amount)){
        $error = "Amount must be numeric!";
    }
    elseif(!preg_match('/^[0-9]{10}$/', $phone)){
        $error = "Phone must be 10 digits!";
    }
    else{

        $course_name   = mysqli_real_escape_string($conn, $course_name);
        $course_amount = mysqli_real_escape_string($conn, $course_amount);
        $phone         = mysqli_real_escape_string($conn, $phone);

        $sql = "INSERT INTO payments (course_name, course_amount, phone)
                VALUES ('$course_name','$course_amount','$phone')";

        if(mysqli_query($conn, $sql)){

            $fullNumber = "91".$phone;
            $message = "Hello! Your payment for $course_name course of ₹$course_amount is received successfully. Thank you!";

            sendWhatsAppMessage($fullNumber, $message, $ACCESS_TOKEN, $PHONE_NUMBER_ID);

            $success = "Payment saved & WhatsApp message sent!";
        }
        else{
            $error = "DB Error: " . mysqli_error($conn);
        }
    }
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
    background:white;
    margin:40px auto;
    display:flex;
    border-radius:10px;
    box-shadow:0 0 15px rgba(0,0,0,0.2);
}

/* LEFT */
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

/* RIGHT */
.right{
    width:50%;
    padding:30px;
}

input, select{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    font-size:16px;
    border:2px solid #333;
    border-radius:6px;
}

button{
    padding:12px;
    width:100%;
    font-size:16px;
    background:#25D366;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
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
    .container{flex-direction:column;}
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
    <p><b>Aim:</b> <?= $page1['aim']; ?></p><br>
    <p><b>Topic:</b> <?= $page1['topic']; ?></p><br>
    <p><b>Duration:</b> <?= $page1['duration']; ?></p>
</div>
</div>

<!-- RIGHT SIDE -->
<div class="right">
<h2>Course Payment</h2>

<?php if($error){ ?><div class="error"><?= $error ?></div><?php } ?>
<?php if($success){ ?><div class="success"><?= $success ?></div><?php } ?>

<form method="POST">

<select name="course_name" required>
    <option value="">Select Course</option>
    <option value="Python">Python</option>
    <option value="Java">Java</option>
    <option value="PHP">PHP</option>
    <option value="JavaScript">JavaScript</option>
</select>

<input type="text" name="course_amount" placeholder="Enter Amount" oninput="onlyNumber(this)" required>

<input type="text" name="phone" placeholder="WhatsApp Number" maxlength="10" oninput="onlyNumber(this)" required>

<button type="submit" name="pay_now">Submit Payment</button>

</form>
</div>

</div>

</body>
</html>
