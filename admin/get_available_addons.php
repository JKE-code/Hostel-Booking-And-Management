<?php
@ini_set('session.cookie_lifetime', 0);
@ini_set('session.gc_maxlifetime', 3600);
@ini_set('session.cookie_httponly', 1);
@ini_set('session.cookie_secure', 0);
@ini_set('session.use_only_cookies', 1);
@ini_set('session.cookie_samesite', 'Strict');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once 'includes/session_check.php';

header('Content-Type: application/json');

if (!isset($_GET['booking_id'])) {
    echo json_encode(['success' => false, 'message' => 'Booking ID not provided']);
    exit;
}

$booking_id = intval($_GET['booking_id']);

// Get all active add-ons
$addons_query = "SELECT id, name, price, charge_type FROM addons WHERE status = 'active' ORDER BY is_pinned DESC, name ASC";
$result = mysqli_query($conn, $addons_query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch add-ons']);
    exit;
}

$addons = [];
while ($row = mysqli_fetch_assoc($result)) {
    $addons[] = $row;
}

// Get existing add-ons for this booking
$existing_query = "SELECT addon_id FROM booking_addons WHERE booking_id = ?";
$stmt = mysqli_prepare($conn, $existing_query);
mysqli_stmt_bind_param($stmt, 'i', $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$existing_addon_ids = [];
while ($row = mysqli_fetch_assoc($result)) {
    $existing_addon_ids[] = intval($row['addon_id']);
}
mysqli_stmt_close($stmt);

echo json_encode([
    'success' => true,
    'addons' => $addons,
    'existing_addon_ids' => $existing_addon_ids
]);
