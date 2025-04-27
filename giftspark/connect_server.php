<?php
/**
 * Connects to database on the server for given user name.
 * 
 */
try {
    $dbh = new PDO(
        "mysql:host=localhost;dbname=mhaskarp_db",
        "mhaskarp_local",
        "o-+K(I*1"
    );
} catch (Exception $e) {
    die("ERROR: Couldn't connect. {$e->getMessage()}");
}