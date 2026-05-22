<?php

session_start();

$usersFile = "users.json";

/* CREATE FILE IF NOT EXISTS */

if(!file_exists($usersFile)){
    file_put_contents($usersFile, json_encode([]));
}

/* GET USERS */

$users = json_decode(file_get_contents($usersFile), true);

if(!$users){
    $users = [];
}

/* REGISTER */

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    /* VALIDATION */

    if(empty($name) || empty($email) || empty($password)){
        die("All fields are required!");
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        die("Invalid email format!");
    }

    if(strlen($password) < 6){
        die("Password must be at least 6 characters!");
    }

    /* CHECK DUPLICATE EMAIL */

    foreach($users as $user){

        if($user['email'] == $email){

            die("Email already exists!");

        }

    }

    /* CREATE NEW USER */

    $newUser = [

        "id" => time(),

        "name" => $name,

        "email" => $email,

        "password" => $hashedPassword

    ];

    /* SAVE USER */

    $users[] = $newUser;

    file_put_contents(
        $usersFile,
        json_encode($users, JSON_PRETTY_PRINT)
    );

    /* LOGIN USER */

    $_SESSION['user'] = $email;

    header("Location: dashboard.php");

    exit();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register - TaskFlow</title>
<link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/9068/9068676.png">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    background:
    linear-gradient(-45deg,#020617,#0f172a,#1e3a8a,#2563eb);

    background-size:400% 400%;

    animation:bg 12s ease infinite;

}

@keyframes bg{

0%{
background-position:0% 50%;
}

50%{
background-position:100% 50%;
}

100%{
background-position:0% 50%;
}

}

.container{

    width:420px;

    padding:35px;

    border-radius:25px;

    background:rgba(255,255,255,0.08);

    backdrop-filter:blur(15px);

    border:1px solid rgba(255,255,255,0.1);

    color:white;

}

.logo{

    text-align:center;

    margin-bottom:25px;

}

.logo img{

    width:80px;

    margin-bottom:10px;

}

.logo h1{

    font-size:42px;

}

.container h2{

    text-align:center;

    margin-bottom:25px;

    font-size:30px;

}

.input-box{

    margin-bottom:18px;

}

.input-box input{

    width:100%;

    padding:15px;

    border:none;

    outline:none;

    border-radius:12px;

    background:rgba(255,255,255,0.1);

    color:white;

    font-size:16px;

}

.input-box input::placeholder{
    color:#cbd5e1;
}

.register-btn{

    width:100%;

    padding:15px;

    border:none;

    border-radius:12px;

    background:#2563eb;

    color:white;

    font-size:18px;

    font-weight:bold;

    cursor:pointer;

    transition:0.3s;

}

.register-btn:hover{

    background:#1d4ed8;

    transform:translateY(-2px);

}

.login-link{

    text-align:center;

    margin-top:20px;

}

.login-link a{

    color:#60a5fa;

    text-decoration:none;

}

.social-login{

    margin-top:25px;

    display:flex;

    gap:15px;

}

.google-btn,
.apple-btn{

    flex:1;

    display:flex;

    align-items:center;

    justify-content:center;

    gap:10px;

    padding:12px;

    border-radius:12px;

    text-decoration:none;

    color:white;

    background:rgba(255,255,255,0.08);

    transition:0.3s;

}

.google-btn:hover,
.apple-btn:hover{

    background:rgba(255,255,255,0.15);

}

.google-btn img,
.apple-btn img{

    width:22px;

}

</style>

</head>
<body>

<div class="container">

    <div class="logo">

        <img src="https://cdn-icons-png.flaticon.com/512/9068/9068679.png">

        <h1>TaskFlow</h1>

    </div>

    <h2>Create Account</h2>

    <form method="POST">

        <div class="input-box">

            <input
            type="text"
            name="name"
            placeholder="Full Name"
            required>

        </div>

        <div class="input-box">

            <input
            type="email"
            name="email"
            placeholder="Email Address"
            required>

        </div>

        <div class="input-box">

            <input
            type="password"
            name="password"
            placeholder="Password"
            required>

        </div>

        <button
        type="submit"
        name="register"
        class="register-btn">

        Register

        </button>

    </form>


    <div class="login-link">

        Already have an account?

        <a href="index.php">
            Login
        </a>

    </div>

</div>

</body>
</html>