<?php
session_start();
include "../db.php";

$error = "";
$success = "";

$name = "";
$contact = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name     = trim($_POST['name']);
    $contact  = trim($_POST['contact']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    /* Validation */
    if (empty($name) || empty($contact) || empty($password) || empty($confirm)) {
        $error = "All fields are required!";
    }
    elseif (!preg_match("/^[A-Za-z ]+$/", $name)) {
        $error = "Name must contain only letters!";
    }
    elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $error = "Contact must be exactly 10 digits!";
    }
    elseif ($password !== $confirm) {
        $error = "Passwords do not match!";
    }
    else {

        /* Check existing user */
        $check = mysqli_query(
            $conn,
            "SELECT id FROM addUser WHERE contact='$contact'"
        );

        if (mysqli_num_rows($check) > 0) {
            $error = "User already exists with this contact!";
        }
        else {

            /* Hash password */
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            /* Insert user into single table */
            mysqli_query(
                $conn,
                "INSERT INTO addUser (user_name, contact, password)
                 VALUES ('$name', '$contact', '$hashedPassword')"
            );

            $success = "User added successfully!";
            $name = $contact = "";
        }
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
</style>

<script>
function onlyNumber(input){
    input.value = input.value.replace(/[^0-9]/g,'');
}

function onlyChar(input){
    input.value = input.value.replace(/[^A-Za-z ]/g,'');
}

function validatePassword(input){
    let val = input.value;

    if(val.length>15) val=val.slice(0,15);
    if(val.length>0 && !/^[A-Za-z]/.test(val[0])) val=val.slice(1);
    if(val.length>1)
        val = val[0] + val.slice(1).replace(/[^A-Za-z0-9@#$%&!]/g,'');

    input.value = val;
}
</script>

</head>

<body>

<form method="POST" id="formBox">

<h2>Add User</h2>

<?php if($error) echo "<p class='error'>$error</p>"; ?>
<?php if($success) echo "<p class='success'>$success</p>"; ?>

<label>Name</label>
<input type="text" name="name" value="<?= htmlspecialchars($name) ?>" oninput="onlyChar(this)" required>

<label>Contact Number</label>
<input type="text" name="contact" value="<?= htmlspecialchars($contact) ?>" oninput="onlyNumber(this)" maxlength="10" required>

<label>Password</label>
<input type="password" name="password" oninput="validatePassword(this)" maxlength="15" required>

<label>Confirm Password</label>
<input type="password" name="confirm_password" oninput="validatePassword(this)" maxlength="15" required>

<button type="submit">Add User</button>

</form>

</body>
</html>