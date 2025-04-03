<?php
session_start();
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // Check if user exists in user_info
        $stmt = $conn->prepare("SELECT user_id, password FROM user_info WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $db_password);
            $stmt->fetch();

            // Check if password matches directly (no hashing)
            if ($password === $db_password) {
                // Insert login record into user_sessions
                $stmt = $conn->prepare("INSERT INTO user_sessions (user_id) VALUES (?)");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();

                // Start session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;

                header("Location: dashboard.php"); // Redirect to dashboard
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
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
