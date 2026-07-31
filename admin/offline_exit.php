<?php
session_start();

// Completely destroy the session
session_unset();
session_destroy();

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

// Redirect to login page
header('Location: login.php');
exit;
?>
