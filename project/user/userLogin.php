<?php
session_start();
include "../db.php";
$currentTab = basename($_SERVER['PHP_SELF']);


/* Prevent browser caching */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$error = "";

/* Handle login */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $login    = trim($_POST['login']);
    $password = trim($_POST['password']);

    if (empty($login) || empty($password)) {
        $error = "All fields are required!";
    }

    if (!$error) {

        sleep(1); // basic brute force protection

        $stmt = $conn->prepare("
            SELECT id, user_name, contact, password
            FROM addUser
            WHERE user_name = ? OR contact = ?
            LIMIT 1
        ");

        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $error = "Invalid username/phone or password!";
        }
        else {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                session_regenerate_id(true);

                $_SESSION['user_id']      = $user['id'];
                $_SESSION['user_name']    = $user['user_name'];
                $_SESSION['user_contact'] = $user['contact'];

                header("Location: userCreateProgram.php");
                exit;

            } else {
                $error = "Invalid username/phone or password!";
            }
        }

        $stmt->close();
    }
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

.tabs { margin: 30px 0; display: flex; gap: 10px; justify-content: center; font-size: 13.5px; }
.tab { padding: 10px 18px; border-radius: 10px; background: #f2f2f2; font-weight: bold; text-decoration: none; color: black; }
.tab:hover { background: black; color: white; }
.tab.active { background: black; color: white; }

</style>

</head>

<body>

<div class="tabs">
    <a class="tab <?= $currentTab=='main.php' ? 'active' : '' ?>" href="main.php">Home</a>
    <a class="tab <?= $currentTab=='studentLogin/login.php' ? 'active' : '' ?>" href="studentLogin/login.php">Student Login</a>
    <a class="tab <?= $currentTab=='user/userLogin.php' ? 'active' : '' ?>" href="user/userLogin.php">User Login</a>
    <a class="tab <?= $currentTab=='addStudent/addStudent.php' ? 'active' : '' ?>" href="addStudent/addStudent.php"> Add Student</a>
    <a class="tab <?= $currentTab=='addUser/addUserRegistration.php' ? 'active' : '' ?>" href="addUser/addUserRegistration.php"> Add User</a>
    <a class="tab <?= $currentTab=='addfaculty/addFaculty.php' ? 'active' : '' ?>" href="addfaculty/addFaculty.php"> Add Faculty</a>
    <a class="tab <?= $currentTab=='viewStudent/viewStudent.php' ? 'active' : '' ?>" href="viewStudent/viewStudent.php"> View Student</a>
    <a class="tab <?= $currentTab=='viewFaculty/viewFaculty.php' ? 'active' : '' ?>" href="viewFaculty/viewFaculty.php"> View Faculty</a>
</div>

<form method="POST" id="formBox" autocomplete="off">

<h2>User Login</h2>

<?php if($error){ ?>
<p class="error"><?php echo $error; ?></p>
<?php } ?>

<label>Username or Phone</label>
<input type="text" name="login" required>

<label>Password</label>
<input type="password" name="password"
       maxlength="50"
       autocomplete="new-password"
       required>

<button type="submit">Login</button>

</form>

</body>
</html>