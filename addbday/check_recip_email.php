<?php 
include "../connect.php";

$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

$cmd_email_exists = "SELECT * FROM bday_recipients WHERE email = ?"; 
$stmt_email_exists = $dbh->prepare($cmd_email_exists);
$success_email_exists = $stmt_email_exists->execute([$email]);

if ($email_row = $stmt_email_exists->fetch()){
    $email_exists = true; 
} else {
    $email_exists = false; 
}

echo json_encode(["exists" => $email_exists]);
?>