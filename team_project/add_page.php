<?php 
    session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>GiftSpark - Landing Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
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

    if ($error != true && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        include 'connect_local.php';
        //include 'connect_server.php';
        $insert_command = "INSERT INTO `giftspark_events`(`user_id`, `person_name`, `birthday_date`, `reminder_time`, `color_theme`, `notes`) VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $dbh->prepare($insert_command);
        $insert_args = [$user_id, $name, $birthdate, $reminder, $color, $notes];
        $insert_success = $insert_stmt->execute($insert_args);
        if ($insert_success) {
            if ($insert_stmt->rowCount() == 1) {
                echo "<div class='message'> <h2>Event has been added successfully!</h2></div>";
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