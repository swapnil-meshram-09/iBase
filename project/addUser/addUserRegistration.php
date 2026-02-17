<?php
session_start();
include "../db.php";
$currentTab = basename($_SERVER['PHP_SELF']);

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name     = trim($_POST['name']);
    $contact  = trim($_POST['contact']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    /* Validation */

    if (empty($name) || empty($contact) || empty($password) || empty($confirm)) {
        $error = "All fields are required!";
    }
    elseif (!preg_match("/^[a-z][a-z0-9@_-]{4,14}$/", $name)) {
        $error = "Username must be 5–15 characters and valid format!";
    }
    elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $error = "Contact must be exactly 10 digits!";
    }
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    }
    elseif ($password !== $confirm) {
        $error = "Passwords do not match!";
    }
    else {

        /* Check duplicate */

        $stmt = $conn->prepare(
            "SELECT id FROM addUser WHERE user_name=? OR contact=?"
        );
        $stmt->bind_param("ss", $name, $contact);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username or contact already exists!";
        }
        else {

            /* Insert user */

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO addUser (user_name, contact, password)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param("sss", $name, $contact, $hashed);

            if ($stmt->execute()) {
                $success = "User added successfully!";
                $_POST = []; // clear form
            } else {
                $error = "Failed to add user!";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add User</title>

<style>
body { background:#dde3ea; margin:0; }

#formBox {
    width:420px;
    margin:auto;
    margin-top:60px;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 0 10px #aaa;
}

h2 { text-align:center; }

label {
    font-weight:bold;
    display:block;
    margin-top:10px;
}

input {
    padding:8px 12px;
    border:none;
    border-radius:6px;
    background:#f2f2f2;
    width:95%;
    margin-top:5px;
}

button {
    margin-top:15px;
    padding:12px;
    background:#16a34a;
    border:none;
    color:white;
    border-radius:10px;
    width:100%;
    font-size:16px;
    cursor:pointer;
}

button:hover { background:green; }

.error { text-align:center; color:red; font-weight:bold; }
.success { text-align:center; color:green; font-weight:bold; }

.tabs { margin: 30px 0; display: flex; gap: 10px; justify-content: center; font-size: 13.5px; }
.tab { padding: 10px 18px; border-radius: 10px; background: #f2f2f2; font-weight: bold; text-decoration: none; color: black; }
.tab:hover { background: black; color: white; }
.tab.active { background: black; color: white; }

</style>

<script>
function validateUser(input) {
    let val = input.value;

    val = val.replace(/[^a-z0-9@_-]/g,'');
    if (val.length > 0 && !/^[a-z]/.test(val[0])) val = val.slice(1);
    if (val.length > 15) val = val.slice(0,15);

    input.value = val;
}

function onlyNumber(input) {
    input.value = input.value.replace(/[^0-9]/g,'').slice(0,10);
}
</script>

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

<form method="POST" id="formBox">

<h2>Add User</h2>

<?php if ($error) echo "<p class='error'>$error</p>"; ?>
<?php if ($success) echo "<p class='success'>$success</p>"; ?>

<label>Username</label>
<input type="text" name="name"
       minlength="5" maxlength="15"
       oninput="validateUser(this)"
       required>

<label>Contact</label>
<input type="text" name="contact"
       maxlength="10"
       oninput="onlyNumber(this)"
       required>

<label>Password</label>
<input type="password" name="password"
       minlength="6" maxlength="50"
       required>

<label>Confirm Password</label>
<input type="password" name="confirm_password"
       minlength="6" maxlength="50"
       required>

<button type="submit">Add User</button>

</form>

</body>
</html>