<?php
try {
    $dbh = new PDO(
        "mysql:host=localhost;dbname=johnn4_db",
        "root",
        ""
    );
} catch (Exception $e) {
    die("ERROR: Couldn't connect. {$e->getMessage()}");
}
?>
