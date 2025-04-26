<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>GiftSpark - Landing Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php
    include '../connect_local.php';
    //include '../connect_server.php';
    //input validation for the form the user filled in.
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $select1_command = "SELECT * FROM `user_info` WHERE `user_id` = ?";
        $select1_stmt = $dbh->prepare($select1_command);
        $select1_args = [$user_id];
        $select1_success = $select1_stmt->execute($select1_args);
        if ($select1_success) {
            while ($row = $select1_stmt->fetch()) {
                $name = "$row[name]";
            }
            echo "<div class='message'> <h2>Welcome $name!</h2></div>";
            //upcoming events page
            $select2_command = "SELECT * FROM `giftspark_events` WHERE `user_id` = ? ORDER BY `birthday_date` DESC LIMIT 10";
            $select2_stmt = $dbh->prepare($select2_command);
            $select2_args = [$user_id];
            $select2_success = $select2_stmt->execute($select2_args);
            if ($select2_success) {
                if ($select2_stmt->rowCount() < 1) {
                    echo "<div>No upcoming events...</div>";
                } else { ?>
                    <div class='table_container'>
                        <table class='upcoming_events'>
                            <tr>
                                <th>Birthdate</th>
                                <th>Birthday Person Name</th>
                                <th>Gifts</th>
                                <th>Your Notes</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            <?php
                            while ($row = $select2_stmt->fetch()) {
                                $current_eventID = $row["event_id"]; // get event's id
                                $current_gifts = [];

                                // find gifts ids that are linked to the event id
                                $giftIDs_cmd = "SELECT `gift_id` FROM `recipient_gifts` WHERE `recipient_id` = ?";
                                $giftIDs_stmt = $dbh->prepare($giftIDs_cmd);
                                $giftIDs_args = [$current_eventID];
                                $giftIDs_success = $giftIDs_stmt->execute($giftIDs_args);

                                // iterate through each gift id 
                                while ($giftID = $giftIDs_stmt->fetch()) {
                                    $current_giftID = $giftID["gift_id"];
                                    $getgift_cmd = "SELECT * FROM `gift_ideas` WHERE `id` = ?"; //get gift info from DB
                                    $getgift_stmt = $dbh->prepare($getgift_cmd);
                                    $getgift_args = [$current_giftID];
                                    $getgift_success = $getgift_stmt->execute($getgift_args);

                                    if ($getgift_success) {
                                        if ($giftrow = $getgift_stmt->fetch()) {
                                            array_push($current_gifts, $giftrow["gift_description"]);
                                        }
                                    }
                                }
                            ?>
                                <tr>
                                    <td><?= $row["birthday_date"] ?></td>
                                    <td><?= $row["person_name"] ?></td>

                                    <td>
                                        <?php if (count($current_gifts) >= 1) {
                                            echo "<ul>";
                                            foreach ($current_gifts as $giftul) {
                                                echo "<li>$giftul</li>";
                                            }
                                            echo "</ul>";
                                        } ?>
                                    </td>

                                    <td><?= $row["notes"] ?></td>
                                    <td>
                                        <form action='edit_event.php' method='post'>
                                            <input type='hidden' name='name' value='<?= $row["person_name"] ?>' required>
                                            <input type='hidden' name='date' value='<?= $row["birthday_date"] ?>' required>
                                            <input type='hidden' name='reminder' value='<?= $row["reminder_time"] ?>' required>
                                            <input type='hidden' name='colors_theme' value='<?= $row["color_theme"]?>' required>
                                            <input type='hidden' name='gifts' value='<?= htmlspecialchars(json_encode($current_gifts)) ?>' required>
                                            <input type='hidden' name='notes' value='<?= $row["notes"]?>'>
                                            <input type='hidden' name='event_id' value='<?= $row["event_id"]?>'>
                                            <div id='error-message'></div>
                                            <button type='submit' id='edit-button' class='edit'><i class='fas fa-edit'></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action='delete_event.php' method='post' onsubmit="return confirm('Are you sure you want to delete this event?');">
                                            <input type='hidden' name='event_id' value='<?= $row["event_id"] ?>'>
                                            <button type='submit' id='delete-button' class='edit'><i class='fas fa-trash-alt'></i></button>
                                        </form>
                                    </td>
                                <?php echo "</tr>";
                            }
                            echo "</table></div>";
                        }
                    }
                } else {
                    echo "<div class='error'> <p>Error occured, result not saved.<p> </div>";
                }
                //add event form.
                echo "<a class='edit' href='add_event.html'>Add Event</a>";
            } else {
                echo "<div class='error'> <p>You're logged out.<p> </div>";
                echo "<a class='edit' href='../login/login.php'>Login</a>";
            }
                ?>
</body>

</html>