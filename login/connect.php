<?php
try {
    $dbh = new PDO(
        "mysql:host=localhost;dbname=giftspark",
        "root",
        ""
    );
} catch (Exception $e) {
    die("ERROR: Couldn't connect. {$e->getMessage()}");
}
?>
