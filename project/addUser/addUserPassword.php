<?php
session_start();
include "../db.php";

$error = "";
$success = "";

// Get contact from session
$contact = $_SESSION['student_mobile'] ?? '';

if (!$contact) {
    header("Location: login.php");
    exit;
}

/* Check if student exists */
$getStudent = mysqli_query(
    $conn,
    "SELECT id FROM student_registration WHERE contact='$contact'"
);

if (mysqli_num_rows($getStudent) == 0) {
    $error = "Student record not found!";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$error) {

    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    if (empty($password) || empty($confirm)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm) {
        $error = "Passwords does not match!";
    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check existing password record
        $check = mysqli_query(
            $conn,
            "SELECT id FROM student_password WHERE contact='$contact'"
        );

        if (mysqli_num_rows($check) == 0) {

            // Insert password (NO EMAIL)
            mysqli_query(
                $conn,
                "INSERT INTO student_password (contact, password)
                 VALUES ('$contact', '$hashedPassword')"
            );

        } else {

            // Update password
            mysqli_query(
                $conn,
                "UPDATE student_password
                 SET password='$hashedPassword'
                 WHERE contact='$contact'"
            );
        }

        $success = "Password set successfully! You can now login.";

        unset($_SESSION['student_mobile']);
        unset($_SESSION['form_data']);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Set Password</title>
    <style>
        body {
            /* font-family: Arial, sans-serif; */
            background: #dde3ea;
            margin: 0px;
        }

        #formBox {
            width: 400px;
            margin: auto;
            margin-top: 40px;
            background: white;
            padding: 25px;
            margin-top: 60px;
            border-radius: 15px;
            box-shadow: 0px 0px 10px #aaa;
            font-size: 15px;
        }

    h2 {
    text-align: center;
    margin-top: 0px;
    margin-bottom: 25px;
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
    margin-left:10px;
    border: none;
    border-radius: 6px;
    background: #f2f2f2;
    width: 90%;
    margin-top: 20px;
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
.success {
    color: green;
    text-align: center;
    font-weight: bold;
}
    </style>
<script>
        function validatePassword(input) {
            let val = input.value;

            // Limit length to 15
            if (val.length > 15) {
                val = val.slice(0, 15);
            }

            // First character must be a letter
            if (val.length > 0 && !/^[A-Za-z]/.test(val[0])) {
                val = val.slice(1);
            }

            // After first char: allow letters, numbers, and @ # $ % & !
            if (val.length > 1) {
                val = val[0] + val.slice(1).replace(/[^A-Za-z0-9@#$%&!]/g, '');
            }

            input.value = val;
        }
    </script>

</head>
<body>

<form method="POST" id="formBox">
    <h2>Set Password</h2>

    <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
    <?php if ($success) { echo "<p class='success'>$success</p>"; } ?>

    <label>Password</label>
    <input type="password" name="password" oninput="validatePassword(this)" maxlength="15" required>

    <label>Confirm Password</label>
    <input type="password" name="confirm_password" oninput="validatePassword(this)" maxlength="15" required>


    <!-- Optional email input for future use -->
    <!-- <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"> -->

    <button type="submit">Set Password</button>
</form>

<?php if (!empty($success)) : ?>
<script>
    // Page is already rendered, now wait and redirect
    setTimeout(() => {
        window.location.href = "login.php"; // change to your target page
    }, 1500); // 3 seconds
</script>
<?php endif; ?>
</body>
</html>
