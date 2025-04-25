<?php
session_start();
require_once './connect.php';

// Get and sanitize data
$firstName = trim($_POST['firstName'] ?? '');
$lastName = trim($_POST['lastName'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

// Validate
$errors = [];
if (empty($firstName)) $errors[] = "First name required";
if (empty($lastName)) $errors[] = "Last name required";
if (empty($email)) $errors[] = "Email required";
if (empty($password)) $errors[] = "Password required";

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}

if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters";
}
if ($password !== $confirmPassword) {
    $errors[] = "Passwords do not match";
}

// Handle errors
if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit();
}

// Create account
try {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $fullName = "$firstName $lastName";
    
    $stmt = $dbh->prepare("INSERT INTO user_info (`name`, email, `password`, phone) VALUES (?, ?, ?, ?)");
    $stmt->execute([$fullName, $email, $hashedPassword, $phone]);
    
    echo json_encode(['success' => true]);
    exit();
    
} catch (PDOException $e) {
    error_log("Account creation failed: " . $e->getMessage());
    echo json_encode(['success' => false, 'errors' => ['System error']]);
    exit();
}
?>