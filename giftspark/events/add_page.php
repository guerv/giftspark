<!--
Name: Natalia Guevara - gifts, Pournima Mhaskar - events
Student Numbers: 400570316, 
Date: 04-26-2025
Class: COMPSCI 1XD3 
About: GiftSpark HTML form for Adding a new birthday event into the database after error checking and validating inputs received from the add_event.html form.
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

    $gifts = json_decode($_POST["gifts"], true); 

    if (is_array($gifts)) {
        foreach ($gifts as $gift) {
            $gift = htmlspecialchars($gift);
        }
    } else {
        $gifts = [];
    }


    $notes = filter_input(INPUT_POST, "notes", FILTER_SANITIZE_SPECIAL_CHARS);
    if ($notes === null || $notes === false) {
        echo "<div class='error'> <p> Invalid notes input! <p> </div>";
        $error = true;
    }
    //checks the user is still logged in
    if ($error != true && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        //include '../connect_local.php';
        include '../connect_server.php';
        //the new event with the given details inserted into the database
        $insert_command = "INSERT INTO `giftspark_events`(`user_id`, `person_name`, `birthday_date`, `reminder_time`, `color_theme`, `notes`) VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $dbh->prepare($insert_command);
        $insert_args = [$user_id, $name, $birthdate, $reminder, $color, $notes];
        $insert_success = $insert_stmt->execute($insert_args);


        // preparing command for gift ideas
        if (count($gifts) > 0) {
            $cmd_gifts = "INSERT INTO gift_ideas (gift_description) VALUES ";
            for ($i = 0; $i < count($gifts); $i++) {
                $cmd_gifts = $cmd_gifts . "(?)";
                if ($i < count($gifts) - 1) {
                    $cmd_gifts = $cmd_gifts . ",";
                }
            }

            $stmt_gifts = $dbh->prepare($cmd_gifts);
            $params_gifts = [];
            foreach ($gifts as $gift) {
                array_push($params_gifts, $gift);
            }
            $success_gifts = $stmt_gifts->execute($params_gifts);
            // linking gifts to the recipient 
            //1. Must find the id of both the person and gifts
            $cmd_id_recipient = "SELECT * FROM giftspark_events ORDER BY `event_id` DESC LIMIT 1";
            $stmt_id_recipient = $dbh->prepare($cmd_id_recipient);
            $success_id_recipient = $stmt_id_recipient->execute([]);

            if ($recip_id_row = $stmt_id_recipient->fetch()) {
                $recip_id = $recip_id_row["event_id"];
            } else { // no birthday people recorded yet 
                $recip_id = 1;
            }

            $limit = count($gifts);

            $cmd_id_gifts = "SELECT * FROM gift_ideas ORDER BY id DESC LIMIT $limit";
            $stmt_id_gifts = $dbh->prepare($cmd_id_gifts);
            $success_id_gifts = $stmt_id_gifts->execute([]);

            $gift_ids = [];

            while ($id_gifts_row = $stmt_id_gifts->fetch()) {
                array_push($gift_ids, $id_gifts_row["id"]);
            }
            //2. link the gifts to recipients
            $cmd_link = "INSERT INTO recipient_gifts (recipient_id,gift_id) VALUES ";
            for ($i = 0; $i < count($gifts); $i++) {
                $cmd_link = $cmd_link . "(?,?)";
                if ($i < count($gifts) - 1) {
                    $cmd_link = $cmd_link . ",";
                }
            }
            $stmt_link = $dbh->prepare($cmd_link);
            $params_link = [];

            for ($i = 0; $i < count($gifts); $i++) {
                array_push($params_link, $recip_id);
                array_push($params_link, $gift_ids[$i]);
            }
            $success_link = $stmt_link->execute($params_link);
            
            //after everything has been inserted correctly, the user is redirected back to the landing page with the new event showing in the table
            if ($insert_success && $success_gifts && $success_id_recipient && $success_id_gifts && $success_link) {
                if ($insert_stmt->rowCount() == 1) {
                    header("Location: landing_page.php"); // Redirect back to the landing page
                    exit();
                } else {
                    echo "<div class='error'> <p>Error occured, result not saved.<p> </div>";
                }
            }
        } else {
            if ($insert_success)
                if ($insert_stmt->rowCount() == 1) {
                    header("Location: landing_page.php"); // Redirect back to the landing page
                    exit();
                } else {
                    echo "<div class='error'> <p>Error occured, result not saved.<p> </div>";
                }
            }
        }
    else {
        echo "<div class='error'> <p>Error occured, result not saved.<p> </div>";
    }
    //add event form.
    echo "<a class='play_again' href='landing_page.php'>Back to Home</a>";
    ?>
</body>

</html>