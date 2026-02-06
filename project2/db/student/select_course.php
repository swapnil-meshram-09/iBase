<?php
include "../db/db.php";
session_start();

$email = $_SESSION['email'];
$student = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM students WHERE email='$email'")
);

if(isset($_POST['course'])){
    mysqli_query($conn,"INSERT INTO registrations(student_id,course_id)
    VALUES('{$student['id']}','$_POST[course]')");
    header("Location: payment.php?course=".$_POST['course']);
}
?>

<form method="POST">
<h3>Select Course</h3>

<select name="course" required>
<option value="">-- Choose Course --</option>
<?php
$c = mysqli_query($conn,"SELECT * FROM courses");
while($row=mysqli_fetch_assoc($c)){
    echo "<option value='{$row['id']}'>
        {$row['course_name']} - ₹{$row['amount']}
    </option>";
}
?>
</select>

<button>Proceed to Payment</button>
</form>
