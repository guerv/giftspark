<?php
/**
 * Name: Megann Nkenglack
 * MacID: nkenglam
 * Student Number: 400590482
 * Date: 04-25-2025
 * Class: COMPSCI 1XD3 
 * About: validate email 
 */
//require_once '../connect_local.php';
require_once '../connect_server.php';

header('Content-Type: application/json');

try {
    // Get the raw POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!isset($data['email'])) {
        throw new Exception('Email not provided');
    }
    
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    $stmt = $dbh->prepare("SELECT email FROM user_info WHERE email = ?");
    $stmt->execute([$email]);
    
    echo json_encode([
        'exists' => $stmt->rowCount() > 0
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}