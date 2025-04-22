<?php
session_start();
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $stmt = $dbh->prepare("SELECT user_id, password FROM user_info WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $user_id = $user['user_id'];
            $db_password = $user['password'];

            if ($password === $db_password) {
                $insert = $dbh->prepare("INSERT INTO user_sessions (user_id) VALUES (?)");
                $insert->execute([$user_id]);

                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "User not found!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f7f3f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column; 
        }

        .login-box {
            background: #fff5f7;
            border: 2px solid #e6cfc3;
            border-radius: 15px;
            padding: 40px 50px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 450px;
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 25px;
            color: #5a3e36;
            font-size: 26px;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            color: #7c5b4a;
            font-weight: bold;
            text-align: left;
        }

        input[type="email"],
        input[type="password"] {
            width: 94%;
            padding: 12px;
            border: 1px solid #dabfb1;
            border-radius: 8px;
            background-color: #fffaf9;
            font-size: 14px;
            transition: 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #ffbcbc;
            box-shadow: 0 0 5px #ffe3e3;
        }

        button {
            width: 100%; 
            margin-top: 20px;
            padding: 15px;
            background: #eabfbc;
            border: none;
            border-radius: 8px;
            color: #5a3e36;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ddb0ae;
        }

        .error-msg {
            background-color: #ffe3e3;
            padding: 10px;
            border-radius: 8px;
            color: #a94442;
            margin-bottom: 15px;
            text-align: center;
        }

        .site-logo {
            display: block;
            margin-bottom: 20px; 
            width: 120px;
            height: auto;
        }
    </style>

</head>
<body>
    <img src="image/logo.png" alt="Site Logo" class="site-logo">

    <?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>
    <form class="login-box" method="post" action="">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <p style="margin-top: 15px;">
        Don't have an account? 
        <a href="create/index.html" style="color: #5a3e36; text-decoration: underline;">
            Sign up here
        </a>
</p>

</body>
</html>