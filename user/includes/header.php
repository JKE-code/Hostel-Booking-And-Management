<?php
// Only require database if needed (for booking pages)
// require_once __DIR__ . '/../../config/db.php';

// Determine current page for active nav highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Set page title if not already set
if (!isset($page_title)) {
    $page_title = 'Alluri Resorts - Premium Stay in Araku Valley';
}

// Set body class if not already set
if (!isset($body_class)) {
    $body_class = $current_page . '-page';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#8b0000">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="<?php echo $body_class; ?>">

<!-- Floating Leaves Background -->
<div class="floating-leaves">
    <div class="floating-leaf">🍃</div>
    <div class="floating-leaf">🌿</div>
    <div class="floating-leaf">🍃</div>
    <div class="floating-leaf">🌿</div>
    <div class="floating-leaf">🍃</div>
</div>

<!-- Navigation -->
<nav class="navbar">
    <div class="container nav-container">
        <div class="logo">
            <a href="home.php">
                <img src="../assets/images/NEWER_LOGO.png" alt="Alluri Resorts Logo" class="logo-img">
            </a>
        </div>
        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <label for="nav-toggle" class="nav-toggle-label">
            <span></span>
            <span></span>
            <span></span>
        </label>
        <ul class="nav-menu">
            <li><a href="home.php" class="<?php echo ($current_page == 'home') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="rooms.php" class="<?php echo ($current_page == 'rooms') ? 'active' : ''; ?>">Rooms</a></li>
            <li><a href="amenities.php" class="<?php echo ($current_page == 'amenities') ? 'active' : ''; ?>">Amenities</a></li>
            <li><a href="tourism.php" class="<?php echo ($current_page == 'tourism') ? 'active' : ''; ?>">Tourism</a></li>
            <li><a href="reviews.php" class="<?php echo ($current_page == 'reviews') ? 'active' : ''; ?>">Reviews</a></li>
            <li><a href="faq.php" class="<?php echo ($current_page == 'faq') ? 'active' : ''; ?>">FAQ</a></li>
            <li><a href="contact.php" class="<?php echo ($current_page == 'contact') ? 'active' : ''; ?>">Contact</a></li>
            <li><a href="booking_step1_dates.php" class="btn btn-navbook nav-book-btn"><span>Book Now</span></a></li>
        </ul>
    </div>
</nav>
