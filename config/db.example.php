<?php
// ============================================================
// Database Configuration Template
// ============================================================
// INSTRUCTIONS:
//   1. Copy this file to db.php:  cp config/db.example.php config/db.php
//   2. Fill in your actual database credentials in db.php
//   3. db.php is in .gitignore and will never be committed
// ============================================================

$host = "localhost";
$user = "your_db_username";
$pass = "your_db_password";
$db   = "your_db_name";

// MySQLi Connection (for legacy code)
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("DB Connection Failed: " . mysqli_connect_error());
}

// PDO Connection (for new code)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("PDO Connection Failed: " . $e->getMessage());
    $pdo = null;
}
