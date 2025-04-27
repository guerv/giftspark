<!--
Name: Pournima Mhaskar
Date: 04-26-2025
Class: COMPSCI 1XD3 
About: GiftSpark php handling for when the user clicks the delete button for an event on the landing page.
-->
<?php 
    session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>GiftSpark - Landing Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../images/logo.png" />
</head>

<body>
    <?php
    //receives and validates the event id passed in as a hidden element from the landing page
    $event_id = filter_input(INPUT_POST, "event_id", FILTER_VALIDATE_INT);
    if ($event_id === null || $event_id === false) {
        echo "<div class='error'> <p> Invalid event_id input! <p> </div>";
        $error = true;
    }

    //include '../connect_local.php';
    include '../connect_server.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_id'])) {
        //preparing and executing the event deletion in the database
        $command = "DELETE FROM `giftspark_events` WHERE `event_id` = ?";
        $stmt = $dbh->prepare($command);
        $args = [$event_id];
        $success = $stmt->execute($args);

        //redirects to the landing page with the updated table showing
        if ($success) {
            header("Location: landing_page.php");
            exit();
        } else {
            echo "Error deleting event.";
        }
    }
    ?>
</body>

</html>