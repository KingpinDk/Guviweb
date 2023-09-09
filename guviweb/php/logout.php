<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user to the login page or any other appropriate page
header("Location: /");
exit();
