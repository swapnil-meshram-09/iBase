<?php
session_start();
include "../db.php";

$error = "";
$success = "";

// Get contact from session (from registration.php)
$contact = $_SESSION['student_mobile'] ?? '';
// $email   = $_SESSION['student_email'] ?? ''; // optional email for future use

if (!$contact) {
    // If no contact found in session, redirect to registration
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    if (empty($password) || empty($confirm)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match!";
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if contact already exists in credentials table
        $check = mysqli_query($conn, "SELECT id FROM credentials WHERE contact='$contact'");

        if (mysqli_num_rows($check) == 0) {
            // Insert new credential
            mysqli_query(
                $conn,
                "INSERT INTO credentials (contact, password /* , email */)
                 VALUES ('$contact', '$hashedPassword')"
                //  VALUES ('$contact', '$hashedPassword' /* , '$email' */)"
            );
        } else {
            // Update existing credential
            mysqli_query(
                $conn,
                "UPDATE credentials 
                 SET password='$hashedPassword'
                 WHERE contact='$contact'"
            );
                            // SET password='$hashedPassword' /* , email='$email' */

        }

        $success = "Password set successfully! You can now login.";
        // Clear session variables after password set
        unset($_SESSION['student_mobile']);
        // unset($_SESSION['student_email']); // optional
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

</body>
</html>
