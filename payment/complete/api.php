<?php 
session_start();
include "db.php";

$error = "";
$success = "";


// META WHATSAPP SETTINGS


$ACCESS_TOKEN = "EAArr6hAkNxMBQuqbDXYrzloIv31sW5Jrfm1bJYfrI2XYpeDEfed83Bqr0MsZB7uveMbBV1EwZAXzbu8g2JBtm3wFgv4Ukzu7NRYikaqvOHiCnreeFukd1kJgikFlHc9RfKusaMMBV7TJrYM1WJ8kIj8XQZCfwVRMbgAAwRSnUmWVUwq869hYdHrm77HI5Ligoru2qKoTPLlDsZB2omeu14u3VPA5vS9MnZCHYzs6nFsNl0HW3UiZBNiy0IJjIvKY2cf6i3Hox8wGQqnGozISwbU0lvLsjvao9VDEKfZCRkZD";
$PHONE_NUMBER_ID = "1019412521247202";

// ======================

function sendWhatsAppMessage($to, $message, $token, $phoneId){

    $url = "https://graph.facebook.com/v18.0/$phoneId/messages";

    $data = [
        "messaging_product" => "whatsapp",
        "to" => $to,
        "type" => "text",
        "text" => [
            "body" => $message
        ]
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

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
}

// ======================
// FORM SUBMIT
// ======================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $course_name = $_POST['course_name'] ?? '';
    $course_amount = $_POST['course_amount'] ?? '';
    $phone = trim($_POST['phone'] ?? '');

    // VALIDATION
    if(empty($course_name) || empty($course_amount) || empty($phone)){
        $error = "All fields are required!";
    } 
    else if(!is_numeric($course_amount)){
        $error = "Amount must be numeric!";
    } 
    else if(!preg_match('/^[0-9]{10}$/', $phone)){
        $error = "Phone number must be 10 digits!";
    } 
    else {

        // SANITIZE
        $course_name_safe = mysqli_real_escape_string($conn, $course_name);
        $course_amount_safe = mysqli_real_escape_string($conn, $course_amount);
        $phone_safe = mysqli_real_escape_string($conn, $phone);

        // SAVE PAYMENT
        $sql = "INSERT INTO payments (course_name, course_amount, phone) 
                VALUES ('$course_name_safe', '$course_amount_safe', '$phone_safe')";

        if(mysqli_query($conn, $sql)){
            
            // SEND WHATSAPP MESSAGE

            $countryCode = "91";
            $fullNumber = $countryCode.$phone;

            $message = "Hello! Your payment for $course_name course of ₹$course_amount is received successfully. Thank you!";

            sendWhatsAppMessage($fullNumber, $message, $ACCESS_TOKEN, $PHONE_NUMBER_ID);

            $success = "Payment saved & WhatsApp message sent successfully!";

        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Course Payment</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

body {
    background: linear-gradient(120deg,#25D366,#128C7E);
    font-family: Arial;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.container {
    background:white;
    padding:30px;
    border-radius:12px;
    width:360px;
    box-shadow:0px 10px 25px rgba(0,0,0,0.2);
}

h2 {
    text-align:center;
    margin-bottom:15px;
}

input, select, button {
    width:100%;
    padding:12px;
    margin-top:10px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:15px;
}

button {
    background:#25D366;
    border:none;
    color:white;
    cursor:pointer;
    font-weight:bold;
}

button:hover {
    background:#128C7E;
}

.error {
    background:#ffe1e1;
    color:#c40000;
    padding:10px;
    border-radius:5px;
    margin-bottom:10px;
}

.success {
    background:#e1ffe9;
    color:#0a7d2b;
    padding:10px;
    border-radius:5px;
    margin-bottom:10px;
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

<h2>Course Payment</h2>

<?php if($error){ ?>
    <div class="error"><?= $error ?></div>
<?php } ?>

<?php if($success){ ?>
    <div class="success"><?= $success ?></div>
<?php } ?>

<form method="POST">

<select name="course_name" required>
    <option value="">Select Course</option>
    <option value="Python">Python</option>
    <option value="Java">Java</option>
    <option value="PHP">PHP</option>
    <option value="JavaScript">JavaScript</option>
</select>

<input type="text" name="course_amount" placeholder="Enter Amount" maxlength="6" oninput="onlyNumber(this)" required>

<input type="text" name="phone" placeholder="Enter WhatsApp Number" maxlength="10" oninput="onlyNumber(this)" required>

<button type="submit">Submit Payment</button>

</form>

</div>

</body>
</html>
