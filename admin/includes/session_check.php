<?php
// Configure session security BEFORE starting session
// Note: ini_set for session settings must be called before session_start()
// If session is already started, these settings won't apply but won't cause errors
@ini_set('session.cookie_lifetime', 0); // Expire on browser close
@ini_set('session.gc_maxlifetime', 3600); // 1 hour server-side timeout
@ini_set('session.cookie_httponly', 1); // Prevent JavaScript access
@ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
@ini_set('session.use_only_cookies', 1); // Only use cookies, not URL
@ini_set('session.cookie_samesite', 'Strict'); // CSRF protection

if (session_status() === PHP_SESSION_NONE) {
    // Now start the session
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Not logged in - redirect to login
    header('Location: login.php');
    exit();
}

// Create session token if it doesn't exist (for backward compatibility with existing sessions)
if (!isset($_SESSION['session_token'])) {
    $_SESSION['session_token'] = bin2hex(random_bytes(32));
}

// Create user agent if it doesn't exist (for backward compatibility)
if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
}

// Validate user agent (basic fingerprinting) - only check if it was set
if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== '' && $_SESSION['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')) {
    // User agent changed - possible session hijacking
    session_unset();
    session_destroy();
    header('Location: login.php?security=1');
    exit();
}

// Check for session timeout (30 minutes of inactivity)
$timeout_duration = 1800; // 30 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // Session expired due to inactivity
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Regenerate session ID periodically for security (every 30 minutes)
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}
