<?php
session_start();
include "../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_msg'])) {

    $student_mobile = trim($_POST['phone'] ?? '');
    $course_id      = trim($_POST['course_id'] ?? '');

    if (empty($student_mobile) || empty($course_id)) {
        die("Missing student mobile or course ID!");
    }

    // Fetch student ID
    $student = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT id FROM student_registration WHERE contact='$student_mobile'")
    );
    if (!$student) die("Student not found");

    $student_id = $student['id'];

    // Fetch course details
    $course = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT * FROM courses WHERE id='$course_id'")
    );
    if (!$course) die("Course not found");

    $amount = $course['amount'];

    // Insert payment record
    $sql = "INSERT INTO payments (student_id, course_id, amount, payment_status, payment_method, transaction_id)
            VALUES ('$student_id', '$course_id', '$amount', 'success', 'demo', 'TXN".time()."')";

    if (mysqli_query($conn,$sql)) {

        // WhatsApp message
        $message = "Hello! Your payment for ".$course['title']." course of ₹$amount is received successfully. Thank you!";
        $whatsappLink = "https://wa.me/91$student_mobile?text=" . urlencode($message);

        // Redirect to WhatsApp
        header("Location: $whatsappLink");
        exit;
    }
    else {
        die("Database Error: " . mysqli_error($conn));
    }
}
else {
    header("Location: payment.php");
    exit;
}
?>
