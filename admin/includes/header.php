<?php
// Include session check (handles timeout and security)
require_once __DIR__ . '/session_check.php';

// Get admin role
$admin_role = $_SESSION['admin_role'] ?? 'staff';

// Define allowed pages for each role
$role_permissions = [
    'main' => ['dashboard.php', 'bookings.php', 'rooms.php', 'pricing.php', 'addons.php', 'coupons.php', 
               'menu.php', 'queries.php', 'analytics.php', 'activity_logs.php', 'staff.php', 'offline_enter.php', 'offline_booking.php', 
               'offline_booking_step2.php', 'offline_booking_step3.php', 'offline_booking_step4.php', 
               'offline_booking_success.php', 'offline_booking_process.php', 'offline_exit.php', 'logout.php'],
    'staff' => ['bookings.php', 'rooms.php', 'offline_enter.php', 'offline_booking.php', 
                'offline_booking_step2.php', 'offline_booking_step3.php', 'offline_booking_step4.php', 
                'offline_booking_success.php', 'offline_booking_process.php', 'offline_exit.php', 'logout.php']
];

// Check if current page is allowed for this role
$current_page = basename($_SERVER['PHP_SELF']);
$allowed_pages = $role_permissions[$admin_role] ?? $role_permissions['staff'];

// If in offline mode, allow offline pages
if (isset($_SESSION['offline_mode']) && $_SESSION['offline_mode'] === true) {
    $offline_pages = ['offline_booking.php', 'offline_booking_step2.php', 'offline_booking_step3.php', 
                      'offline_booking_step4.php', 'offline_booking_success.php', 'offline_booking_process.php',
                      'offline_exit.php'];
    
    if (!in_array($current_page, $offline_pages)) {
        header('Location: offline_booking.php');
        exit();
    }
} else {
    // Check role-based access
    if (!in_array($current_page, $allowed_pages)) {
        // Redirect staff to bookings page, main admin to dashboard
        $redirect = ($admin_role === 'staff') ? 'bookings.php' : 'dashboard.php';
        header('Location: ' . $redirect);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#8b0000">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms Dashboard - Alluri Resorts - <?php echo time(); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%); margin: 0; padding: 0;">

<!-- Admin Header -->
<header class="admin-header">
    <div class="container">
        <h1>📊 <?php echo isset($page_heading) ? $page_heading : 'Overview Dashboard'; ?></h1>
        
        <!-- Hamburger Menu Toggle (same structure as user pages) -->
        <input type="checkbox" id="admin-nav-toggle" class="admin-nav-toggle" style="display: none;">
        <label for="admin-nav-toggle" class="admin-nav-toggle-label">
            <span></span>
            <span></span>
            <span></span>
        </label>
        
        <div class="admin-nav">
            <?php if ($admin_role === 'main'): ?>
                <a href="dashboard.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'class="active"' : ''; ?>>📊 Overview</a>
            <?php endif; ?>
            
            <a href="bookings.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'bookings.php') ? 'class="active"' : ''; ?>>📅 Bookings</a>
            <a href="rooms.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'rooms.php') ? 'class="active"' : ''; ?>>🏠 Rooms</a>
            
            <?php if ($admin_role === 'main'): ?>
                <a href="pricing.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'pricing.php') ? 'class="active"' : ''; ?>>💰 Pricing</a>
                <a href="addons.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'addons.php') ? 'class="active"' : ''; ?>>⚙️ Add-ons</a>
                <a href="coupons.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'coupons.php') ? 'class="active"' : ''; ?>>🎟️ Coupons</a>
                <a href="menu.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'menu.php') ? 'class="active"' : ''; ?>>🍽️ Menu</a>
                <a href="queries.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'queries.php') ? 'class="active"' : ''; ?>>📧 Queries</a>
                <a href="analytics.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'analytics.php') ? 'class="active"' : ''; ?>>📈 Analytics</a>
                <a href="activity_logs.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'activity_logs.php') ? 'class="active"' : ''; ?>>📋 Activity Logs</a>
                <a href="staff.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'staff.php') ? 'class="active"' : ''; ?>>👥 Staff</a>
            <?php endif; ?>
            
            <a href="offline_enter.php" <?php echo (strpos(basename($_SERVER['PHP_SELF']), 'offline_') !== false) ? 'class="active"' : ''; ?>>🏨 Walk-in</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</header>
