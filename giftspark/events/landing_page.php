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
    <div class="logout">
        <a class="logout-button" href='../login/logout.php'>Logout</a>
    </div>
    <?php
        //include '../connect_local.php';
        include '../connect_server.php';
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
                $select2_command = "SELECT `giftspark_events`.*, GROUP_CONCAT(`gift_ideas`.`gift_description` SEPARATOR ', ') AS `gift_names` FROM `giftspark_events` LEFT OUTER JOIN `recipient_gifts` ON `giftspark_events`.`event_id` = `recipient_gifts`.`recipient_id` LEFT OUTER JOIN `gift_ideas` ON `recipient_gifts`.`gift_id` = `gift_ideas`.id WHERE `giftspark_events`.`user_id` = ? GROUP BY `giftspark_events`.`event_id` ORDER BY `giftspark_events`.`birthday_date`";
                $select2_stmt = $dbh->prepare($select2_command);
                $select2_args = [$user_id];
                $select2_success = $select2_stmt->execute($select2_args);
                if ($select2_success) {
                    if ($select2_stmt->rowCount() < 1) {
                        echo "<div>No upcoming events...</div>";
                    } else {
                        echo "<div class='table_container'><table class='upcoming_events'><tr><th>Birthdate</th><th>Birthday Person Name</th><th>Your Notes</th><th>Gifts to Get</th><th>Edit</th><th>Delete</th></tr>";
                        while ($row = $select2_stmt->fetch()) {
                            echo 
                                "<tr>
                                    <td>$row[birthday_date]</td>
                                    <td>$row[person_name]</td>
                                    <td>$row[notes]</td>
                                    <td>$row[gift_names]</td>
                                    <td>
                                        <form action='edit_event.php' method='post'>
                                            <input type='hidden' name='name'  value='$row[person_name]' required>
                                            <input type='hidden' name='date'  value='$row[birthday_date]' required>
                                            <input type='hidden' name='reminder'  value='$row[reminder_time]' required>
                                            <input type='hidden' name='colors_theme' value='$row[color_theme]'required>
                                            <input type='hidden' name='notes'  value='$row[notes]'>
                                            <input type='hidden' name='event_id'  value='$row[event_id]'>
                                            <div id='error-message'></div>
                                            <button type='submit' id='edit-button' class='edit'><i class='fas fa-edit'></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action='delete_event.php' method='post' onsubmit='return confirm('Are you sure you want to delete this event?');'>
                                            <input type='hidden' name='event_id' value='$row[event_id]'>
                                            <button type='submit' id='delete-button' class='edit'><i class='fas fa-trash-alt'></i></button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                        echo "</table></div>";
                    }
                }
            } else {
                echo "<div class='error'> <p>Error occured, result not saved.<p> </div>";
            }
            //add event form.
            echo "<a id='add_event' href='add_event.html'>Add Event</a>";
        } else {
            echo "<div class='error'> <p>You're logged out.<p> </div>";
            echo "<a class='edit' href='../login/login.php'>Login</a>";

        }
    ?>
</body>

</html>