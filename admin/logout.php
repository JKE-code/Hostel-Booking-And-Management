<?php
/**
 * Admin Logout
 * College Hostel Management System
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/auth.php';

// Logout the admin
logoutAdmin();

// Redirect to login page
header('Location: login.php?logged_out=1');
exit();
?>
