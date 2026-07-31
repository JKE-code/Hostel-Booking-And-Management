<?php
session_start();

// Check if admin is logged in before entering offline mode
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
    header('Location: login.php');
    exit();
}

// Store admin info before clearing booking data
$admin_id = $_SESSION['admin_id'];
$admin_username = $_SESSION['admin_username'];
$admin_role = $_SESSION['admin_role'] ?? 'staff';

// Clear any existing offline booking data
unset($_SESSION['offline_booking']);

// Set offline mode flag
$_SESSION['offline_mode'] = true;

// Ensure admin session is preserved
$_SESSION['admin_id'] = $admin_id;
$_SESSION['admin_username'] = $admin_username;
$_SESSION['admin_role'] = $admin_role;

// Initialize offline booking session
$_SESSION['offline_booking'] = [
    'step' => 1,
    'rooms' => [],
    'dates' => [],
    'guests' => [],
    'addons' => [],
    'payment_method' => 'cash'
];

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

// Redirect to offline booking
header('Location: offline_booking.php');
exit;
?>
