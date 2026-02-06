<!DOCTYPE html>
<html>
<head>
<title>Select Panel</title>

<link rel="stylesheet" href="assets/style.css">

<style>
body{
    font-family: Arial, sans-serif;
    background:#f4f6fb;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}

/* from second code */
.container{
    display:flex;
    gap:40px;
}

/* from first code, adapted */
.cards{
    display:flex;
    gap:40px;
}

.card{
    width:260px;
    height:180px;
    background:#fff;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.12);
    padding:20px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    transition:.3s;
}

.card:hover{
    transform:translateY(-8px);
}

/* colors */
.blue{ border-top:6px solid #2563eb; }
.purple{ border-top:6px solid #7c3aed; }

.card h2{
    margin:10px 0;
}

.card p{
    font-size:14px;
    color:#555;
    margin-bottom:15px;
    text-align:center;
}

/* button */
.btn{
    text-decoration:none;
    padding:8px 16px;
    border-radius:8px;
    color:#fff;
    font-weight:600;
}

.blue .btn{ background:#2563eb; }
.purple .btn{ background:#7c3aed; }

</style>
</head>

<body>

<div class="container">
    <div class="cards">

        <div class="card blue">
            <h2>Admin Module</h2>
            <p>Create courses & manage students</p>
            <a href="admin/admin.php" class="btn">Access Dashboard</a>
        </div>

        <div class="card purple">
            <h2>Student Registration</h2>
            <p>Register & pay online</p>
            <a href="student/student.php" class="btn">Register Now</a>
        </div>

    </div>
</div>

</body>
</html>
