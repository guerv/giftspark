<!DOCTYPE html>
<!-- 
    update DB for new recipient + gift ideas
    - meant to be temp to demonstrate success 
-->

<?php
include "connect.php";


$name = filter_input(INPUT_POST, "new_name", FILTER_SANITIZE_SPECIAL_CHARS);

$birthday_unsafe = $_POST["new_bday"];
//echo $birthday_unsafe;
//$birthday = strtotime($birthday_unsafe);
//echo $birthday; 

$email = filter_input(INPUT_POST, "new_email", FILTER_SANITIZE_EMAIL);

$gifts = json_decode($_POST["gifts"], true); // should not be false/null

if (is_array($gifts)) {
    foreach ($gifts as $gift) {
        $gift = htmlspecialchars($gift);
    }
}

$paramsok = false;

if (
    $name !== null and $name !== "" and
    $birthday_unsafe !== null and $birthday_unsafe !== "" and
    $email !== null and $email !== "" and
    is_array($gifts)
) {
    $paramsok = true;

    // preparing command for recipient 
    $cmd_recipient = "INSERT INTO bday_recipients (bday_name,email,birthday) VALUES (?,?,?)";
    $stmt_recipient = $dbh->prepare($cmd_recipient);
    $params_recipient = [$name, $email, $birthday_unsafe];
    $success_recipient = $stmt_recipient->execute($params_recipient);

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
        $cmd_id_recipient = "SELECT * FROM bday_recipients ORDER BY id DESC LIMIT 1";
        $stmt_id_recipient = $dbh->prepare($cmd_id_recipient);
        $success_id_recipient = $stmt_id_recipient->execute([]); 

        if ($recip_id_row = $stmt_id_recipient->fetch()) {
            $recip_id = $recip_id_row["id"]; 
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
            $cmd_link = $cmd_link."(?,?)"; 
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

    }

}



?>
<html>

<head>
    <title>Successfully added</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/basic_examples.css">
</head>

<body>
    <?php
    echo "<ul>";
    foreach ($gifts as $gift) {
        echo "<li>$gift</li>";
    }
    echo "</ul>";

    if ($success_recipient and $success_gifts and $success_id_recipient and $success_id_gifts and
        $success_link 
    ){
        echo "SUCCESS. Check DB.";
    }
    ?>
</body>

</html>