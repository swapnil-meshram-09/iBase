<?php
session_start();
include "../db.php";

/* Prevent browser caching */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

/* Load error */
$error = $_SESSION['login_error'] ?? "";
unset($_SESSION['login_error']);

/* Handle login */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $login    = trim($_POST['login']); // username OR phone
    $password = trim($_POST['password']);

    if (empty($login) || empty($password)) {
        $error = "All fields are required!";
    }
    elseif (strlen($password) < 5 || strlen($password) > 15) {
        $error = "Password must be 5–15 characters!";
    }

    if (!$error) {

        /* Check username OR contact */
        $stmt = $conn->prepare("
            SELECT id, user_name, contact, password
            FROM addUser
            WHERE user_name = ? OR contact = ?
            LIMIT 1
        ");

        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                /* Create session */
                $_SESSION['user_id']      = $user['id'];
                $_SESSION['user_name']    = $user['user_name'];
                $_SESSION['user_contact'] = $user['contact'];

                /* ✅ Redirect after login */
                header("Location: userCreateProgram.php"); // 👈 change page here if needed
                exit;

            } else {
                $error = "Invalid username/phone or password!";
            }

        } else {
            $error = "Invalid username/phone or password!";
        }

        $stmt->close();
    }

    $_SESSION['login_error'] = $error;
    header("Location: userCreateProgram.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>

<style>
body { background:#dde3ea; margin:0; }

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

</head>

<body>

<form method="POST" id="formBox" autocomplete="off">

<h2>User Login</h2>

<?php if($error){ ?>
<p class="error"><?= $error ?></p>
<?php } ?>

<label>Username or Phone</label>
<input type="text" name="login" required>

<label>Password</label>
<input type="password" name="password"
       maxlength="15"
       autocomplete="new-password"
       required>

<button type="submit">Login</button>

</form>

</body>
</html>