<?php
session_start();
include "../db.php";

$error = "";

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']); // Email / Phone
    $password = trim($_POST['password']); // Password

    // Validation
    if (empty($username) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $isEmail = false;
        $isPhone = false;

        // Determine if email or phone
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $isEmail = true;
        } elseif (preg_match("/^[0-9]{10}$/", $username)) {
            $isPhone = true;
        } else {
            $error = "Enter a valid email or 10-digit phone number!";
        }

        // Password cannot start with number
        if (!$error && preg_match("/^[0-9]/", $password)) {
            $error = "Password cannot start with a number!";
        }

        // Password max length 15
        if (!$error && strlen($password) > 15) {
            $error = "Password cannot exceed 15 characters!";
        }

        /* ================= LOGIN USING student_password ================= */
        if (!$error) {

            if ($isEmail) {
                $stmt = $conn->prepare("
                    SELECT 
                        sp.password,
                        sr.id,
                        sr.name,
                        sr.contact,
                        sr.email
                    FROM student_password sp
                    JOIN student_registration sr ON sr.contact = sp.contact
                    WHERE sp.email = ?
                    LIMIT 1
                ");
                $stmt->bind_param("s", $username);
            } else {
                $stmt = $conn->prepare("
                    SELECT 
                        sp.password,
                        sr.id,
                        sr.name,
                        sr.contact,
                        sr.email
                    FROM student_password sp
                    JOIN student_registration sr ON sr.contact = sp.contact
                    WHERE sp.contact = ?
                    LIMIT 1
                ");
                $stmt->bind_param("s", $username);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {

                $student = $result->fetch_assoc();

                // Verify hashed password
                if (password_verify($password, $student['password'])) {

                    $_SESSION['student_id']     = $student['id'];
                    $_SESSION['student_name']   = $student['name'];
                    $_SESSION['student_mobile'] = $student['contact'];
                    $_SESSION['student_email']  = $student['email'];

                    // Redirect after successful login
                    header("Location: enroll.php"); // change if needed
                    exit;

                } else {
                    $error = "Invalid username or password!";
                }

            } else {
                $error = "Invalid username or password!";
            }

            $stmt->close();
        }
    }
}

// Current tab for highlighting
$currentTab = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Login</title>
<style>
body { 
    /* font-family: Arial, sans-serif; */
     background: #dde3ea; margin: 0; }
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
.tab.active { 
    background: black; 
    color: white; 
}
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
h2 { 
    text-align: center; 
    font-size: 23px; 
    margin-bottom: 20px; 
}
label { 
    font-weight: bold; 
    margin-top: 10px; 
    display: block; 
    margin-left: 10px; 
}
input {
     padding: 10px;
      border: none; 
      border-radius: 6px; 
      background: #f2f2f2; 
      width: 90%; 
      margin-top: 8px; 
      margin-left: 10px; 
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
     background: #12833b; 
    }
.error { 
    text-align: center; 
    color: red; 
    font-weight: bold; 
    }
</style>

<script>
// Validate username input
function validateUsername(input) {
    let val = input.value;
    if (/^[0-9]/.test(val)) {
        // Phone number: only digits, max 10
        val = val.replace(/[^0-9]/g,'');
        if(val.length > 10) val = val.substring(0,10);
    } else {
        // Email: letters, numbers, @, ., _
        val = val.replace(/[^A-Za-z0-9@._]/g,'');
    }
    input.value = val;
}

// Validate password input
function validatePassword(input) {
    // Cannot start with number
    if (/^[0-9]/.test(input.value)) {
        input.value = input.value.substring(1);
    }
    // Max length 15
    if(input.value.length > 15) input.value = input.value.substring(0,15);
}
</script>
</head>
<body>

<div class="tabs">
    <a class="tab <?= $currentTab=='login.php' ? 'active' : '' ?>" href="login.php">Login</a>
    <a class="tab <?= $currentTab=='registration.php' ? 'active' : '' ?>" href="registration.php">Registration</a>
    <!-- <a class="tab <?= $currentTab=='enroll.php' ? 'active' : '' ?>" href="enroll.php">Enroll</a>
    <a class="tab <?= $currentTab=='dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a> -->
</div>

<form method="POST" id="formBox">
<h2>Student Login</h2>

<?php if($error){ ?>
<p class="error"><?= $error ?></p>
<?php } ?>

<label>Email / Phone Number</label>
<input type="text"
       name="username"
       oninput="validateUsername(this)"
       maxlength="50"
       required
       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

<label>Password</label>
<input type="password"
       name="password"
       oninput="validatePassword(this)"
       maxlength="15"
       required
       value="">

<button type="submit">Login</button>
</form>
</body>
</html>
