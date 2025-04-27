<?php
session_start(); // Start or resume the session

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to home
header("Location: index.php");
exit();
?>
