<?php 
    session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>GiftSpark - Landing Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="css/wumpus.css"> -->
</head>

<body>
    <?php
    $event_id = filter_input(INPUT_POST, "event_id", FILTER_VALIDATE_INT);
    if ($event_id === null || $event_id === false) {
        echo "<div class='error'> <p> Invalid event_id input! <p> </div>";
        $error = true;
    }

    include 'connect_local.php';
    //include 'connect_server.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_id'])) {
        $command = "DELETE FROM `giftspark_events` WHERE `event_id` = ?";
        $stmt = $dbh->prepare($command);
        $args = [$event_id];
        $success = $stmt->execute($args);
        
        if ($success) {
            header("Location: landing_page.php"); // Redirect back to the landing page
            exit();
        } else {
            echo "Error deleting event.";
        }
    }
    ?>
</body>

</html>