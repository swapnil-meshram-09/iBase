<?php
session_start();
include "../db.php";

$error = "";

// Fetch courses
$courses = mysqli_query($conn, "SELECT * FROM courses");

// If student submits the course selection form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name   = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $course = $_POST['course_id'];

    if (empty($name) || empty($mobile) || empty($course)) {
        $error = "All fields are required!";
    }
    elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $error = "Mobile number must be 10 digits!";
    }
    else {
        // Save session variables
        $_SESSION['student_name']   = $name;
        $_SESSION['student_mobile'] = $mobile;
        $_SESSION['course_id']      = $course;

        // Check if student exists in student_enrollment
        $check = mysqli_query($conn,
            "SELECT id FROM student_enrollment WHERE contact='$mobile'"
        );

        if(mysqli_num_rows($check) > 0){
            $student = mysqli_fetch_assoc($check);
            $student_id = $student['id'];
        } else {
            $student_id = null; // will enroll later
        }

        // Insert or update course selection
        if($student_id){
            // Get course name
            $courseData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT title FROM courses WHERE id='$course'"));
            $course_name = $courseData['title'];

            // Check if selection already exists
            $selCheck = mysqli_query($conn,
                "SELECT id FROM course_selection WHERE student_id='$student_id'"
            );
            if(mysqli_num_rows($selCheck) > 0){
                // Update existing selection
                mysqli_query($conn,
                    "UPDATE course_selection 
                     SET course_id='$course', course_name='$course_name', student_name='$name', updated_at=NOW() 
                     WHERE student_id='$student_id'"
                );
            } else {
                // Insert new selection
                mysqli_query($conn,
                    "INSERT INTO course_selection (student_id, student_name, course_id, course_name, created_at) 
                     VALUES ('$student_id', '$name', '$course', '$course_name', NOW())"
                );
            }
        } else {
            // For new student, store only mobile/name in session
            // Enrollment will happen later in student_enrollment.php
        }

        // Redirect accordingly
        if ($student_id) {
            header("Location: payment.php");
            exit;
        } else {
            header("Location: student_enrollment.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Details</title>
<style>
body { background:#dde3ea; font-family:Arial; }
.box {
    width:400px;
    margin:10px auto;
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 0 10px #aaa;
}
h2{ text-align:center; margin-top:0px; }
a { display:inline-block; margin:12px 0; margin-left:90%; color:black; text-decoration:none; font-weight:bold; }
a:hover { text-decoration: underline; }
label { font-weight:bold; margin-top:10px; display:block; }
input, select { width: 95%; padding:10px; margin-top:5px; margin-bottom:10px; border:none; border-radius:6px; background:#f2f2f2; }
select{ width: 100%; }
button { margin-top:15px; width:100%; padding:12px; background:#16a34a; color:white; border:none; border-radius:8px; font-size:16px; cursor:pointer; }
button:hover { background:#12833b; }
.error { color:red; text-align:center; margin-bottom:10px; }
</style>

<script>
function onlyNumber(input) { input.value = input.value.replace(/[^0-9]/g, ''); }
function onlyChar(input) { input.value = input.value.replace(/[^A-Za-z ]/g, ''); }
</script>
</head>
<body>
<a href="../index.php">⬅ Back</a>

<div class="box">

<h2>Course Selection</h2>

<?php if($error){ ?><p class="error"><?= $error ?></p><?php } ?>

<form method="POST">

<label><b>Student Name</b></label>
<input type="text" name="name" oninput="onlyChar(this)" 
       pattern="[A-Za-z ]+" placeholder="Enter Student Name" 
       required value="<?= $_SESSION['student_name'] ?? '' ?>">

<label><b>Mobile Number</b></label>
<input type="text" name="mobile" maxlength="10" oninput="onlyNumber(this)" 
       inputmode="numeric" pattern="[0-9]{10}" placeholder="Enter 10-digit Mobile Number" 
       required value="<?= $_SESSION['student_mobile'] ?? '' ?>">

<label><b>Select Course</b></label>
<select name="course_id" required>
<option value="">Select Course</option>
<?php while($c=mysqli_fetch_assoc($courses)){ 
    $selected = (isset($_SESSION['course_id']) && $_SESSION['course_id']==$c['id']) ? 'selected' : '';
?>
<option value="<?= $c['id'] ?>" <?= $selected ?>><?= $c['title'] ?> (₹<?= $c['amount'] ?>)</option>
<?php } ?>
</select>

<button>Proceed</button>
</form>
</div>
</body>
</html>
