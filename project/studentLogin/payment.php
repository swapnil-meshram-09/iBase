<?php
session_start();
include "../db.php";

/* ---------- GET SESSION DATA ---------- */
$student_mobile = $_SESSION['student_mobile'] ?? '';
$student_name   = $_SESSION['student_name'] ?? '';
$course_id      = $_SESSION['course_id'] ?? '';

/* ---------- SESSION CHECK ---------- */
if (empty($student_mobile) || empty($student_name) || empty($course_id)) {
    die("Session expired. Please login again.");
}

/* ---------- FETCH COURSE ---------- */
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    die("Course not found!");
}

$message = "";
$message_class = "";

/* ---------- HANDLE PAYMENT ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_msg'])) {

    $title       = $course['title'];
    $description = $course['description'];
    $start_date  = $course['start_date'];
    $end_date    = $course['end_date'];
    $duration    = $course['duration'];
    $amount      = $course['amount'];

    /* ---------- CHECK DUPLICATE ---------- */
    $check_stmt = $conn->prepare("
        SELECT id FROM student_course_enrollment 
        WHERE contact = ? AND title = ?
    ");
    $check_stmt->bind_param("ss", $student_mobile, $title);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {

        $message = "You are already enrolled in this course!";
        $message_class = "error";

    } else {

        /* ---------- INSERT ENROLLMENT ---------- */
        $insert_stmt = $conn->prepare("
            INSERT INTO student_course_enrollment
            (name, contact, title, description, start_date, end_date, duration, amount, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $insert_stmt->bind_param(
            "sssssssd",
            $student_name,
            $student_mobile,
            $title,
            $description,
            $start_date,
            $end_date,
            $duration,
            $amount
        );

        if ($insert_stmt->execute()) {

            /* ---------- WHATSAPP REDIRECT ---------- */
            $wa_message = "Hello $student_name! Your enrollment for $title course of ₹$amount is successful. Thank you!";
            $whatsappLink = "https://wa.me/91$student_mobile?text=" . urlencode($wa_message);

            // Optional: Clear course session after success
            unset($_SESSION['course_id']);

            header("Location: $whatsappLink");
            exit;

        } else {
            $message = "Database Error!";
            $message_class = "error";
        }

        $insert_stmt->close();
    }

    $check_stmt->close();
}

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment</title>
<style>
body{ background:#dde3ea; margin:0; }
.box{
    width:420px;
    margin:40px auto;
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 0 10px #aaa;
}
h2{ text-align:center; margin-bottom:15px; }
button{
    width:100%;
    padding:12px;
    background:#16a34a;
    border:none;
    color:white;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
}
button:hover{ background:#12833b; }
.error{
    text-align:center;
    color:red;
    font-weight:bold;
    margin-bottom:15px;
}
p{
    margin:8px 0;
    margin-top:25px;
    margin-bottom:25px;
}
</style>
</head>
<body>

<div class="box">
<h2>Proceed to Payment</h2>

<?php if (!empty($message)) { ?>
    <div class="<?= $message_class ?>">
        <?= htmlspecialchars($message) ?>
    </div>
<?php } ?>

<p><b>Name:</b> <?= htmlspecialchars($student_name) ?></p>
<p><b>Mobile:</b> <?= htmlspecialchars($student_mobile) ?></p>
<p><b>Course:</b> <?= htmlspecialchars($course['title']) ?></p>
<p><b>Amount:</b> ₹<?= htmlspecialchars($course['amount']) ?></p>

<form method="POST">
    <button type="submit" name="send_msg">
        Send Confirmation via WhatsApp
    </button>
</form>

</div>

</body>
</html>
