<!DOCTYPE html>
<!--
    GIFTSPARK (mock) Bday Recip. Form
-->

<?php

$gifts = [];



?>
<html>

<head>
    <meta charset="utf-8" />
    <title>Add</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.png" />
    <meta name="viewport" content="width=device-width" />

    <!--<link rel="stylesheet" href="css/cal.css" />-->

    <script src="js/addgift.js"></script>
</head>

<body>
    <form id="form" method="POST" action="success_add.php">
        <div>
            <label for="new_name">Name of Birthday Person</label>
        </div>
        <div>
            <input type="text" name="new_name" id="new_name" required />
        </div>

        <br />

        <div>
            <label for="new_bday">Birthday Date</label>
        </div>
        <div>
            <input type="date" name="new_bday" id="new_bday" required />
        </div>

        <br />

        <div>
            <label for="new_email">Their Email</label>
        </div>
        <div>
            <input type="text" name="new_email" id="new_email" required />
            <span id="email_feedback"></span>
        </div>

        <br />

        <div>
            <label for="new_gifts">Gift Ideas</label>
        </div>
        <div>
            <input type="text" id="new_gifts" />
            <input type="button" id="add_gift" value="Add Gift!" />
        </div>
        <div>
            <ul id="gift_list"></ul>
        </div>

        <input type="hidden" name="gifts" id="gifts_pass" />

        <br />

        <input type="submit" id="submit" />
    </form>
</body>

</html>