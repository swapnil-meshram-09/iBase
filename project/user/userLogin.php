<?php
session_start();
include "../db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $login    = trim($_POST['login']);
    $password = trim($_POST['password']);

    if (empty($login) || empty($password)) {
        $error = "All fields are required!";
    }
    else {

        // Delay to prevent brute force
        sleep(1);

        $stmt = $conn->prepare("
            SELECT id, user_name, contact, password
            FROM addUser
            WHERE user_name=? OR contact=?
            LIMIT 1
        ");

        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $error = "Invalid login credentials!";
        }
        else {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                // Secure session
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_contact'] = $user['contact'];

                header("Location: userDashboard.php");
                exit;
            }
            else {
                $error = "Invalid login credentials!";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>User Login</title>

<style>
body { background:#dde3ea; font-family:Arial; }
#box { width:420px; margin:60px auto; background:white; padding:25px; border-radius:15px; box-shadow:0 0 10px #aaa; }
input,button { width:100%; padding:10px; margin-top:8px; border-radius:6px; border:none; background:#f2f2f2; }
button { background:#16a34a; color:white; cursor:pointer; }
.error { color:red; text-align:center; font-weight:bold; }
</style>

</head>

<body>

<form method="POST" id="box">

<h2 align="center">User Login</h2>

<?php if ($error) echo "<p class='error'>$error</p>"; ?>

<input type="text" name="login" placeholder="Username or Phone" required>
<input type="password" name="password" placeholder="Password" required>

<button type="submit">Login</button>

</form>

</body>
</html>