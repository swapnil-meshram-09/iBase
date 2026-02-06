<!DOCTYPE html>
<html>
<head>
<title>Select Panel</title>
<style>
body{
    font-family:Arial;
    background:#f4f6fb;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.container{
    display:flex;
    gap:40px;
}
.box{
    width:260px;
    height:180px;
    background:#fff;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.12);
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    cursor:pointer;
    transition:.3s;
    text-decoration:none;
    color:#000;
}
.box:hover{
    transform:translateY(-8px);
}
.admin{border-top:6px solid #2563eb}
.student{border-top:6px solid #16a34a}
h2{margin:10px 0}
</style>
</head>

<body>
<div class="container">

<a href="admin/admin.php" class="box admin">
    <h2>Admin Panel</h2>
    <p>Manage courses & students</p>
</a>

<a href="student/student.php" class="box student">
    <h2>Student Panel</h2>
    <p>Register & make payment</p>
</a>

</div>
</body>
</html>
