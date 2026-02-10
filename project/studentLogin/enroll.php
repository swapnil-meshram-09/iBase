<?php
session_start();
include "../db.php";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['student_name']);
    unset($_SESSION['student_mobile']);
    unset($_SESSION['course_id']);
}
$error = "";

// Fetch courses
$courses = mysqli_query($conn, "SELECT * FROM courses");

// Handle form submission
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
        $_SESSION['student_name']   = $name;
        $_SESSION['student_mobile'] = $mobile;
        $_SESSION['course_id']      = $course;

        $check = mysqli_query($conn,
            "SELECT id FROM student_enrollment WHERE contact='$mobile'"
        );

        if(mysqli_num_rows($check) > 0){
            $student = mysqli_fetch_assoc($check);
            $student_id = $student['id'];
        } else {
            $student_id = null;
        }

        if($student_id){
            $courseData = mysqli_fetch_assoc(
                mysqli_query($conn, "SELECT title FROM courses WHERE id='$course'")
            );
            $course_name = $courseData['title'];

            $selCheck = mysqli_query($conn,
                "SELECT id FROM course_selection WHERE student_id='$student_id'"
            );

            if(mysqli_num_rows($selCheck) > 0){
                mysqli_query($conn,
                    "UPDATE course_selection 
                     SET course_id='$course',
                         course_name='$course_name',
                         student_name='$name',
                         updated_at=NOW()
                     WHERE student_id='$student_id'"
                );
            } else {
                mysqli_query($conn,
                    "INSERT INTO course_selection
                    (student_id, student_name, course_id, course_name, created_at)
                    VALUES
                    ('$student_id','$name','$course','$course_name',NOW())"
                );
            }
        }

        // Redirect
        if ($student_id) {
            header("Location: payment.php");
            // header("Location: dashboard.php");
        } else {
            header("Location: enroll.php");
        }
        exit;
    }
}

// Determine current page for active tab
$currentTab = basename($_SERVER['PHP_SELF']); // 'login.php'

?>

<!DOCTYPE html>
<html>
<head>
<style>
body {
    /* font-family: Arial, sans-serif; */
    background: #dde3ea;
    margin: 0;
}

/* Tabs */
.tabs {
    margin: 30px 0;
    display: flex;
    gap: 10px;
    justify-content: center;
    font-size: 13.5px;
}

.tab {
    padding: 10px 18px;
    border-radius: 10px;
    background: #f2f2f2;
    font-weight: bold;
    text-decoration: none;
    color: black;
}

.tab:hover {
    background: black;
    color: white;
}

/* Active tab */
.tab.active {
    background: black;
    color: white;
}

/* Disabled tab if not logged in */
.tab.disabled {
    pointer-events: none;
    opacity: 0.5;
}

/* Form */
#formBox {
    width: 420px;
    margin: auto;
    background: white;
    padding: 25px;
    padding-top: 5px;
    border-radius: 15px;
    box-shadow: 0px 0px 10px #aaa;
}

h2 {
    text-align: center;
    font-size: 23px;
    margin-bottom: 30px;
}

label {
    font-weight: bold;
    margin-top: 10px;
    display: block;
    margin-left: 10px;
}

input, select {
    padding: 10px;
    border: none;
    border-radius: 6px;
    background: #f2f2f2;
    width: 90%;
    margin-top: 8px;
    margin-left: 10px;
}

select {
    width: 95%;
}

button {
    margin-top: 15px;
    padding: 12px;
    background-color: #16a34a;
    border: none;
    color: white;
    border-radius: 10px;
    width: 95%;
    font-size: 16px;
    cursor: pointer;
    margin-left: 10px;
}

button:hover {
    background-color: green;
}

.error {
    text-align: center;
    color: red;
    font-weight: bold;
}
</style>

<script>
function onlyNumber(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}
function onlyChar(input) {
    input.value = input.value.replace(/[^A-Za-z ]/g, '');
}
</script>
</head>

<body>

<!-- Tabs -->
<div class="tabs">
    <a class="tab <?= $currentTab=='login.php' ? 'active' : '' ?>" href="login.php">Login</a>
    <a class="tab <?= $currentTab=='registration.php' ? 'active' : '' ?>" href="registration.php">Registration</a>
    <a class="tab <?= $currentTab=='enroll.php' ? 'active' : '' ?>" href="enroll.php">Enroll</a>
    <a class="tab <?= $currentTab=='dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
</div>

<!-- Login Form -->
<form method="POST" id="formBox">

<h2>Student Enrollment</h2>

<?php if($error){ ?>
    <p class="error"><?= $error ?></p>
<?php } ?>

<label>Student Name</label>
<input type="text"
       name="name"
       oninput="onlyChar(this)"
       required
       value="<?= $_SESSION['student_name'] ?? '' ?>">

<label>Mobile Number</label>
<input type="text"
       name="mobile"
       maxlength="10"
       oninput="onlyNumber(this)"
       required
       value="<?= $_SESSION['student_mobile'] ?? '' ?>">

<label>Select Course</label>
<select name="course_id" required>
    <option value="">Select Course</option>
    <?php while($c = mysqli_fetch_assoc($courses)) {
        $selected = (isset($_SESSION['course_id']) && $_SESSION['course_id'] == $c['id']) ? 'selected' : '';
    ?>
        <option value="<?= $c['id'] ?>" <?= $selected ?>>
            <?= $c['title'] ?> (₹<?= $c['amount'] ?>)
        </option>
    <?php } ?>
</select>

<button type="submit">Proceed</button>

</form>

</body>
</html>
