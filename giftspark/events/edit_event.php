<!DOCTYPE html>
<!-- Author: Pournima Mhaskar
    Date Last Updated:
 -->
<html>

<head>
    <title>Result</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <!--<script src="../js/script.js"></script> -->
</head>
<?php 
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
?>
<body>
    <div class="logout">
        <a class="logout-button" href='../login/logout.php'>Logout</a>
    </div>
    <div class="form-container">
        <h1>Edit your Event</h1>
        <form class="form" action="submit_edit_event.php" method="post">
            <input id="name" type="text" class="element" placeholder="Birthday Person Name" name="name" value='<?php echo $name?>' required>
            Birthday Person's Birthdate:
            <input id="date" type="date" class="element" placeholder="Birth date" name="date" value='<?php echo $birthdate?>' required>
            Set Reminder Time:
            <input id="reminder_time" type="datetime-local" class="element" placeholder="Set Reminder Time" name="reminder" value='<?php echo $reminder?>' required>
            Set a custom color theme for your reminder:
            <input id="color_theme" type="color" placeholder="Color Theme" name="colors_theme" value='<?php echo $color?>' required>
            <input id="notes" type="text" class="element" placeholder="Your Notes" name="notes" value='<?php echo $notes?>'>
            <input type='hidden' name='event_id'  value='<?php echo $event_id?>'>
            <div id='error-message'></div>
            <input type="submit" id="submit-button" class="submit" value="Submit">
        </form>
    </div>
</body>

</html>