<?php
require_once '../connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    try {
        // Check user exists
        $stmt = $dbh->prepare("SELECT user_id, password FROM user_info WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($password, $user['password'])) {
            header("Location: ../login.html?error=invalid_credentials");
            exit();
        }
        
        // Login successful
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: ../dashboard.html");
        exit();
        
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        header("Location: ../login.html?error=database_error");
        exit();
    }
}
?>