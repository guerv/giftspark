<!--
Name: Pournima Mhaskar 
Date: 04-26-2025
Class: COMPSCI 1XD3 
About: GiftSpark php handling the updating of an event that has been edited in the edit_event.php form
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
    <!-- <link rel="stylesheet" href="../css/style.css"> -->
    <link rel="icon" type="image/x-icon" href="../images/logo.png" />
</head>

<body>
    <?php
    //input validation for the form the user filled in.
    $error = false;
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    if ($name === null || $name === false) {
        echo "<div class='error'> <p> Invalid name! <p> </div>";
        $error = true;
    }
    $birthdate = filter_input(INPUT_POST, "date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ($birthdate === null || $birthdate === false) {
        echo "<div class='error'> <p>Invalid birthdate!<p> </div>";
        $error = true;
    } 

    $reminder = filter_input(INPUT_POST, "reminder", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ($reminder === null || $reminder === false) {
        echo "<div class='error'> <p>Invalid reminder time!<p> </div>";
        $error = true;
    }

    $color = filter_input(INPUT_POST, 'colors_theme', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!($color && preg_match('/^#[0-9A-Fa-f]{6}$/', $color))) {
        echo "<div class='error'> <p>Invalid color format!<p> </div>";
        $error = true;
    }
    $notes = filter_input(INPUT_POST, "notes", FILTER_SANITIZE_SPECIAL_CHARS);
    if ($notes === null || $notes === false) {
        echo "<div class='error'> <p> Invalid notes input! <p> </div>";
        $error = true;
    }
    $event_id = filter_input(INPUT_POST, "event_id", FILTER_VALIDATE_INT);
    if ($event_id === null || $event_id === false) {
        echo "<div class='error'> <p> Invalid event_id input! <p> </div>";
        $error = true;
    }

    if ($error != true && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        //include '../connect_local.php';
        include '../connect_local.php';
        //update command is prepared and executed with all the details updated
        $update_command = "UPDATE `giftspark_events` SET `user_id` = ?, `person_name` = ?, `birthday_date` = ?, `reminder_time` = ?, `color_theme` = ?, `notes` = ? WHERE `event_id` = ?";
        $update_stmt = $dbh->prepare($update_command);
        $update_args = [$user_id, $name, $birthdate, $reminder, $color, $notes, $event_id];
        $update_success = $update_stmt->execute($update_args);
        if ($update_success) {
            //redirected back to the landing page to show the updated events table
            if (($update_stmt->rowCount() == 1) || ($update_stmt->rowCount() == 0)) {
                header("Location: landing_page.php");
                exit();
            } else {
                echo "<div class='error'> <p>Error occured, result not saved.<p> </div>";
            }
        }
    } else {
        echo "<div class='error'> <p>Error occured, result not saved.<p> </div>";
    }
    //add event form.
    echo "<a class='play_again' href='landing_page.php'>Back to Home</a>";
    ?>
</body>

</html>