<!--
Name: Nivedit John
Date: 04-26-2025
Class: COMPSCI 1XD3 
About: GiftSpark Logout session, redirection handling
-->
<?php
session_start();  
session_unset();  
session_destroy();  

header("Location: login.php");
exit();
?>
