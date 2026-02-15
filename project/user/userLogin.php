<?php
session_start();
include "../db.php";

/* 🔥 Prevent browser caching */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

/* Load error from session (POST-Redirect-GET pattern) */
$error = $_SESSION['login_error'] ?? "";
unset($_SESSION['login_error']);

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "All fields are required!";
    }
    elseif (!preg_match("/^[0-9]{10}$/", $username)) {
        $error = "Enter a valid 10-digit phone number!";
    }
    elseif (preg_match("/^[0-9]/", $password)) {
        $error = "Password cannot start with a number!";
    }
    elseif (strlen($password) > 15) {
        $error = "Password cannot exceed 15 characters!";
    }

    if (!$error) {

        $stmt = $conn->prepare("
            SELECT sp.password, sr.id, sr.name, sr.contact
            FROM student_password sp
            JOIN student_registration sr 
                ON sr.contact = sp.contact
            WHERE sp.contact = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {

            $student = $result->fetch_assoc();

            if (password_verify($password, $student['password'])) {

                $_SESSION['student_id']     = $student['id'];
                $_SESSION['student_name']   = $student['name'];
                $_SESSION['student_mobile'] = $student['contact'];

                header("Location: enroll.php");
                exit;

            } else {
                $error = "Invalid phone or password!";
            }

        } else {
            $error = "Invalid phone or password!";
        }

        $stmt->close();
    }

    /* Save error and redirect (clears POST data) */
    $_SESSION['login_error'] = $error;
    header("Location: login.php");
    exit;
}

$currentTab = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html>
<head>

<style>
body { background:#dde3ea; margin:0; }
.tabs { margin:30px 0; display:flex; gap:10px; justify-content:center; font-size:13.5px; }
.tab { padding:10px 18px; border-radius:10px; background:#f2f2f2; font-weight:bold; text-decoration:none; color:black; }
.tab:hover { background:black; color:white; }
.tab.active { background:black; color:white; }

#formBox {
    width:420px;
    margin:auto;
    margin-top:40px;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 0 10px #aaa;
}

h2 { text-align:center; font-size:23px; margin-bottom:20px; }

label { font-weight:bold; display:block; margin-left:10px; margin-top:10px; }

input {
    padding:10px;
    border:none;
    border-radius:6px;
    background:#f2f2f2;
    width:90%;
    margin:8px 0 0 10px;
}

button {
    margin-top:15px;
    padding:12px;
    background:#16a34a;
    border:none;
    color:white;
    border-radius:10px;
    width:95%;
    font-size:16px;
    cursor:pointer;
    margin-left:10px;
}

button:hover { background:#12833b; }

.error {
    text-align:center;
    color:red;
    font-weight:bold;
}
</style>

<script>
function validateUsername(input) {
    let val = input.value.replace(/[^0-9]/g,'');
    if(val.length > 10) val = val.substring(0,10);
    input.value = val;
}

function validatePassword(input) {
    if (/^[0-9]/.test(input.value)) {
        input.value = input.value.substring(1);
    }
    if(input.value.length > 15) {
        input.value = input.value.substring(0,15);
    }
}
</script>
</head>

<body>

<!-- <div class="tabs">
    <a class="tab <?= $currentTab=='login.php' ? 'active' : '' ?>" href="login.php">Login</a>
    <a class="tab <?= $currentTab=='registration.php' ? 'active' : '' ?>" href="registration.php">Registration</a>
</div> -->

<form method="POST" id="formBox" autocomplete="off">

<h2>User Login</h2>

<?php if($error){ ?>
<p class="error"><?= $error ?></p>
<?php } ?>

<label>Phone Number</label>
<input type="text" name="username"
       oninput="validateUsername(this)"
       maxlength="10"
       required>

<label>Password</label>
<input type="password" name="password"
       oninput="validatePassword(this)"
       maxlength="15"
       autocomplete="new-password"
       required>

<button type="submit">Login</button>

</form>

</body>
</html>

