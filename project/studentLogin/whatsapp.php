<?php
session_start();
include "../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_msg'])) {

    $student_mobile = trim($_POST['phone'] ?? '');
    $course_id      = trim($_POST['course_id'] ?? '');

    if (empty($student_mobile) || empty($course_id)) {
        die("Missing student mobile or course ID!");
    }

    /* Fetch Student */
    $student_query = mysqli_query($conn,
        "SELECT name FROM student_registration WHERE contact='$student_mobile'"
    );

    if (!$student_query || mysqli_num_rows($student_query) == 0) {
        die("Student not found");
    }

    $student = mysqli_fetch_assoc($student_query);
    $student_name = $student['name'];

    /* Fetch Course */
    $course_query = mysqli_query($conn,
        "SELECT title, description, start_date, end_date, duration, amount 
         FROM courses 
         WHERE id='$course_id'"
    );

    if (!$course_query || mysqli_num_rows($course_query) == 0) {
        die("Course not found");
    }

    $course = mysqli_fetch_assoc($course_query);

    $title       = $course['title'];
    $description = $course['description'];
    $start_date  = $course['start_date'];
    $end_date    = $course['end_date'];
    $duration    = $course['duration'];
    $amount      = $course['amount'];

    if ($amount <= 0) {
        die("Invalid course amount");
    }

    /* Insert Enrollment */
    $sql = "INSERT INTO student_course_enrollment
            (name, contact, title, description, start_date, end_date, duration, amount, created_at)
            VALUES
            ('$student_name', '$student_mobile', '$title', '$description',
             '$start_date', '$end_date', '$duration', '$amount', NOW())";

    if (mysqli_query($conn, $sql)) {

        $message = "Hello $student_name! Your enrollment for $title course of ₹$amount is successful. Thank you!";
        $whatsappLink = "https://wa.me/91$student_mobile?text=" . urlencode($message);

        echo "
        <script>
            window.open('$whatsappLink', '_blank');
            alert('Enrollment Successful!');
        </script>
        ";

    } else {
        die("Database Error: " . mysqli_error($conn));
    }

} else {
    echo "Invalid Request";
}
?>
