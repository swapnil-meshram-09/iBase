<?php
session_start();
include "../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_msg'])) {

    $student_mobile = trim($_POST['phone'] ?? '');
    $course_id      = trim($_POST['course_id'] ?? '');

    if (empty($student_mobile) || empty($course_id)) {
        die("Missing student mobile or course ID!");
    }

    // Fetch student
    $student = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT name FROM student_registration WHERE contact='$student_mobile'")
    );
    if (!$student) die("Student not found");

    $student_name = $student['name'];

    // Fetch course
    $course = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT * FROM courses WHERE id='$course_id'")
    );
    if (!$course) die("Course not found");

    $course_name  = $course['course_name'];  // adjust if needed
    $course_price = $course['course_price']; // adjust if needed

    // Insert into student_course_enrollment
    $sql = "INSERT INTO student_course_enrollment
            (name, contact, course_name, course_price, created_at)
            VALUES
            ('$student_name', '$student_mobile', '$course_name', '$course_price', NOW())";

    if (mysqli_query($conn,$sql)) {

        // WhatsApp message
        $message = "Hello $student_name! Your enrollment for $course_name course of ₹$course_price is successful. Thank you!";
        $whatsappLink = "https://wa.me/91$student_mobile?text=" . urlencode($message);

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
