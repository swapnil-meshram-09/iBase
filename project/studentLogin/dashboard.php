<?php
session_start();
include "../db.php";
// Fetch enrolled student data using session mobile
$studentData = null;
$courseData  = null;

if (isset($_SESSION['student_mobile'])) {

    $mobile = $_SESSION['student_mobile'];

    // Get student_id using mobile
    $stuQ = mysqli_query(
        $conn,
        "SELECT id FROM student_enrollment WHERE contact='$mobile' LIMIT 1"
    );

    if (mysqli_num_rows($stuQ) > 0) {

        $stu = mysqli_fetch_assoc($stuQ);
        $student_id = $stu['id'];

        // Get course selection data
        $selQ = mysqli_query(
            $conn,
            "SELECT * FROM course_selection WHERE student_id='$student_id' LIMIT 1"
        );

        if (mysqli_num_rows($selQ) > 0) {

            $studentData = mysqli_fetch_assoc($selQ);

            // Get course fee
            $feeQ = mysqli_query(
                $conn,
                "SELECT amount FROM courses WHERE id='".$studentData['course_id']."' LIMIT 1"
            );

            if (mysqli_num_rows($feeQ) > 0) {
                $fee = mysqli_fetch_assoc($feeQ);
                $courseAmount = $fee['amount'];
            }
        }
    }
}





$currentTab = basename($_SERVER['PHP_SELF']); // 'registration.php'

?>

<!DOCTYPE html>
<html>
<head>

<style>
body {
    /* font-family: Arial, sans-serif; */
    background: #dde3ea;
    margin: 0px;
}

/* Tabs */
.tabs {
    margin: 30px 0;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 13.5px;
    justify-content: center;
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
    width: 450px;
    margin: auto;
    background: white;
    padding: 25px;
    padding-top: 0.5px;
    border-radius: 15px;
    box-shadow: 0px 0px 10px #aaa;
    font-size: 15px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
}

label {
    font-weight: bold;
    margin-top: 10px;
    display: block;
    margin-left: 10px;
}

input, select {
    padding: 8px 12px;
    border: none;
    border-radius: 6px;
    background: #f2f2f2;
    width: 90%;
    margin-top: 10px;
    margin-left: 10px;
}

select {
    width: 95%;
}

button {
    margin-top: 15px;
    padding: 12px;
    background: #16a34a;
    border: none;
    color: white;
    border-radius: 10px;
    width: 95%;
    font-size: 16px;
    cursor: pointer;
    margin-left: 10px;
}

button:hover {
    background: green;
}

.error {
    text-align: center;
    color: red;
    font-weight: bold;
}
</style>

</head>

<body>

<!-- Tabs -->
<div class="tabs">
    <a class="tab <?= $currentTab=='login.php' ? 'active' : '' ?>" href="login.php">Login</a>
    <a class="tab <?= $currentTab=='registration.php' ? 'active' : '' ?>" href="registration.php">Registration</a>
    <a class="tab <?= $currentTab=='enroll.php' ? 'active' : '' ?>" href="enroll.php">Enroll</a>
    <a class="tab <?= $currentTab=='dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
</div>

<!-- Dashboard Box -->
<div id="formBox">

<h2>Student Dashboard</h2>

<?php if ($studentData && $courseData) { ?>

    <!-- <label>Student Name</label>
    <input type="text" value="<?= htmlspecialchars($studentData['name']) ?>" disabled> -->

    <!-- <label>Mobile Number</label>
    <input type="text" value="<?= htmlspecialchars($studentData['contact']) ?>" disabled> -->

    <!-- <label>Course Name</label>
    <input type="text" value="<?= htmlspecialchars($courseData['title']) ?>" disabled> -->

    <!-- <label>Course Fee</label>
    <input type="text" value="₹<?= htmlspecialchars($courseData['amount']) ?>" disabled> -->

    
<?php } else { ?>

    <p class="error">No enrollment data found. Please enroll first.</p>

<?php } ?>

</div>

</body>
</html>


