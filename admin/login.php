<?php
// Configure session to expire when browser closes
ini_set('session.cookie_lifetime', 0); // 0 = expire on browser close
ini_set('session.gc_maxlifetime', 3600); // 1 hour server-side timeout
ini_set('session.cookie_httponly', 1); // Prevent JavaScript access
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
ini_set('session.use_only_cookies', 1); // Only use cookies, not URL

session_start();

// Regenerate session ID to prevent fixation attacks
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

require_once __DIR__ . '/../config/db.php';

// If already logged in, redirect based on role
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $redirect = ($_SESSION['admin_role'] === 'staff') ? 'bookings.php' : 'dashboard.php';
    header('Location: ' . $redirect);
    exit();
}

$error_message = '';

// Check for various logout/error reasons
if (isset($_GET['timeout']) && $_GET['timeout'] == '1') {
    $error_message = 'Your session has expired due to inactivity. Please login again.';
} elseif (isset($_GET['expired']) && $_GET['expired'] == '1') {
    $error_message = 'Your session has expired. Please login again.';
} elseif (isset($_GET['security']) && $_GET['security'] == '1') {
    $error_message = 'Security check failed. Please login again.';
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Query to get admin user
    $query = "SELECT id, username, password_hash, role FROM admins WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);
        
        $password_valid = false;
        
        // Check if it's a bcrypt hash (starts with $2y$)
        if (substr($admin['password_hash'], 0, 4) === '$2y$') {
            // New secure bcrypt hash
            $password_valid = password_verify($password, $admin['password_hash']);
        } else {
            // Old SHA-256 hash (backward compatibility)
            $hashed_password = hash('sha256', $password);
            $password_valid = ($hashed_password === $admin['password_hash']);
            
            // If valid, upgrade to bcrypt
            if ($password_valid) {
                $new_hash = password_hash($password, PASSWORD_BCRYPT);
                $update_query = "UPDATE admins SET password_hash = '$new_hash' WHERE id = {$admin['id']}";
                mysqli_query($conn, $update_query);
            }
        }
        
        // Verify password
        if ($password_valid) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_role'] = $admin['role'];
            $_SESSION['login_time'] = time();
            $_SESSION['last_activity'] = time();
            $_SESSION['session_token'] = bin2hex(random_bytes(32)); // Unique session token
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            // Redirect based on role
            $redirect = ($admin['role'] === 'staff') ? 'bookings.php' : 'dashboard.php';
            header('Location: ' . $redirect);
            exit();
        } else {
            $error_message = 'Invalid username or password';
        }
    } else {
        $error_message = 'Invalid username or password';
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
    <title>Admin Login - Alluri Resorts</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-login-container">
        <div class="login-box">
            <h1>🔐 Admin Login</h1>
            <p>Alluri Resorts Management</p>

            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autocomplete="username" autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>

                <button type="submit" class="login-btn">Login to Dashboard</button>
            </form>

            <div class="back-link">
                <a href="../user/home.php">← Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
