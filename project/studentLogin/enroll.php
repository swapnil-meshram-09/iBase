<?php
session_start();
include "../db.php";

/* ---------- CHECK LOGIN ---------- */
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

/* ---------- GET STUDENT DATA FROM SESSION ---------- */
$student_id     = $_SESSION['student_id'];
$student_name   = $_SESSION['student_name'];
$student_mobile = $_SESSION['student_mobile'];

/* ---------- AJAX COURSE FETCH ---------- */
if(isset($_POST['ajax_course_id'])){

    $course_id = intval($_POST['ajax_course_id']);

    $stmt = $conn->prepare("
        SELECT title, description, start_date, end_date, duration, amount 
        FROM courses 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();

    if($course){
        $course['start_date'] = date("d/m/Y", strtotime($course['start_date']));
        $course['end_date']   = date("d/m/Y", strtotime($course['end_date']));
    }

    echo json_encode($course);
    exit;
}

$error = "";

/* ---------- FETCH COURSES ---------- */
$courses = $conn->query("SELECT id, title, amount FROM courses");

/* ---------- HANDLE FORM SUBMIT ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['ajax_course_id'])) {

    $course = $_POST['course_id'] ?? '';

    if (empty($course)) {
        $error = "Please select a course!";
    } else {

        $_SESSION['course_id'] = $course;

        header("Location: payment.php");
        exit;
    }
}

$currentTab = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Enrollment</title>

<style>
body { background: #dde3ea; margin: 0; }

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

.tab:hover { background: black; color: white; }
.tab.active { background: black; color: white; }

#formBox {
    width: 420px;
    margin: auto;
    margin-top: 40px;
    background: white;
    padding: 25px;
    padding-top: 5px;
    border-radius: 15px;
    box-shadow: 0px 0px 10px #aaa;
}

h2 { text-align: center; font-size: 23px; margin-bottom: 30px; }

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

select { width: 95%; }

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

button:hover { background-color: green; }

.error {
    text-align: center;
    color: red;
    font-weight: bold;
}

.courseBox{
    padding:10px;
    margin-top: 20px;
    margin-right:10px;
    margin-left:5px;
    background:#f9f9f9;
    border-radius:10px;
    display:none;
}
</style>

<script>
function loadCourseDetails(course_id){

    if(course_id === ""){
        document.getElementById("courseDetails").style.display = "none";
        return;
    }

    let xhr = new XMLHttpRequest();

    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function(){

        if(this.status === 200){

            let data = JSON.parse(this.responseText);

            document.getElementById("courseDetails").style.display = "block";

            document.getElementById("d_title").innerText = data.title ?? '';
            document.getElementById("d_description").innerText = data.description ?? '';
            document.getElementById("d_start").innerText = data.start_date ?? '';
            document.getElementById("d_end").innerText = data.end_date ?? '';
            document.getElementById("d_duration").innerText = data.duration ?? '';
            document.getElementById("d_amount").innerText = data.amount ?? '';
        }
    };

    xhr.send("ajax_course_id=" + course_id);
}
</script>

</head>

<body>

<div class="tabs">
    <a class="tab <?= $currentTab=='enroll.php' ? 'active' : '' ?>" href="enroll.php">Enroll</a>
    <a class="tab <?= $currentTab=='dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
</div>

<form method="POST" id="formBox">

<h2>Student Enrollment</h2>

<?php if($error){ ?>
<p class="error"><?= $error ?></p>
<?php } ?>

<label>Student Name</label>
<input type="text"
       value="<?= htmlspecialchars($student_name) ?>"
       disabled>

<label>Mobile Number</label>
<input type="text"
       value="<?= htmlspecialchars($student_mobile) ?>"
       disabled>

<label>Select Course</label>
<select name="course_id" required onchange="loadCourseDetails(this.value)">
    <option value="">Select Course</option>

    <?php while($c = $courses->fetch_assoc()) { ?>
        <option value="<?= $c['id'] ?>">
            <?= htmlspecialchars($c['title']) ?> (₹<?= $c['amount'] ?>)
        </option>
    <?php } ?>
</select>

<div class="courseBox" id="courseDetails">
    <h3>Course Details</h3>
    <p><b>Title:</b> <span id="d_title"></span></p>
    <p><b>Description:</b> <span id="d_description"></span></p>
    <p><b>Start Date:</b> <span id="d_start"></span></p>
    <p><b>End Date:</b> <span id="d_end"></span></p>
    <p><b>Duration:</b> <span id="d_duration"></span></p>
    <p><b>Amount:</b> ₹<span id="d_amount"></span></p>
</div>

<button type="submit">Proceed</button>

</form>

</body>
</html>
