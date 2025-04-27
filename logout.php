<?php
session_start(); // Start or resume the session

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to homepage
header("Location: index.php");
exit();
?>
