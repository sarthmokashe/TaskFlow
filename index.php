<?php

session_start();

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $usersFile = "users.json";

    if(file_exists($usersFile)){

        $users = json_decode(
            file_get_contents($usersFile),
            true
        );

        if($users){

            foreach($users as $user){

                if(

                    $user['email'] == $email &&

                    password_verify(
                        $password,
                        $user['password']
                    )

                ){

                    $_SESSION['user'] = $user['name'];

                    header("Location: dashboard.php");

                    exit();

                }

            }

        }

    }

    $error = "Invalid Email or Password";

}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TaskFlow</title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/9068/9068676.png">

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="container">

    <!-- LEFT -->

    <div class="left-panel">

        <img src="https://cdn-icons-png.flaticon.com/512/9068/9068676.png" class="logo">

        <h1>TaskFlow</h1>

        <p>
            Organize your tasks, boost productivity,
            and manage your workflow beautifully.
        </p>

        <div class="features">

            <div>
                <i class="fa-solid fa-list-check"></i>
                Task Management
            </div>

            <div>
                <i class="fa-solid fa-chart-line"></i>
                Productivity Tracking
            </div>

            <div>
                <i class="fa-solid fa-cloud"></i>
                Cloud Access
            </div>

        </div>

    </div>

    <!-- RIGHT -->

    <div class="right-panel">

        <h2>Welcome Back</h2>

        <p class="subtitle">
            Continue managing your tasks efficiently
        </p>

        <?php if($error != ""): ?>

            <div class="error-msg">
                <?php echo $error; ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <input
            type="email"
            name="email"
            placeholder="Enter Email"
            required>

            <input
            type="password"
            name="password"
            placeholder="Enter Password"
            required>

            <button type="submit" class="login-btn">
                Login
            </button>

        </form>

        <!-- SOCIAL -->

       

        <div class="register-link">

            Don’t have an account?

            <a href="register.php">
                Create Account
            </a>

        </div>

    </div>

</div>

</body>
</html>