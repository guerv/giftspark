<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&family=Pacifico&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">

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

    .dashboard-container {
      background: #fff5f7;
      border: 2px solid #e6cfc3;
      border-radius: 15px;
      padding: 40px 50px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      width: 450px;
      text-align: center;
    }

    .dashboard-container h2 {
      font-size: 26px;
      color: #5a3e36;
      margin-bottom: 15px;
    }

    .dashboard-container p {
      font-size: 16px;
      color: #7c5b4a;
      margin-bottom: 20px;
    }

    .dashboard-container a {
      display: inline-block;
      text-decoration: none;
      background: #eabfbc;
      color: #5a3e36;
      padding: 12px 25px;
      border-radius: 8px;
      font-weight: bold;
      transition: 0.3s;
    }

    .dashboard-container a:hover {
      background: #ddb0ae;
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
    <div class="dashboard-container">
        <h2>Welcome, <?php echo $_SESSION['email']; ?>!</h2>
        <p>You are now logged in.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>