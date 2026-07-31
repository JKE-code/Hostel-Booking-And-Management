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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
$addon_id = isset($_POST['addon_id']) ? intval($_POST['addon_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if (!$booking_id || !$addon_id) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

// Get booking details to calculate nights
$booking_query = "SELECT DATEDIFF(checkout, checkin) as nights FROM bookings WHERE id = ?";
$stmt = mysqli_prepare($conn, $booking_query);
mysqli_stmt_bind_param($stmt, 'i', $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$booking) {
    echo json_encode(['success' => false, 'message' => 'Booking not found']);
    exit;
}

$nights = $booking['nights'];

// Get addon details
$addon_query = "SELECT price, charge_type FROM addons WHERE id = ?";
$stmt = mysqli_prepare($conn, $addon_query);
mysqli_stmt_bind_param($stmt, 'i', $addon_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$addon = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$addon) {
    echo json_encode(['success' => false, 'message' => 'Add-on not found']);
    exit;
}

// Calculate price based on charge type
$price = $addon['charge_type'] == 'per_night' ? $addon['price'] * $nights * $quantity : $addon['price'] * $quantity;

// Check if addon already exists for this booking
$check_query = "SELECT id FROM booking_addons WHERE booking_id = ? AND addon_id = ?";
$stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($stmt, 'ii', $booking_id, $addon_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$existing = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($existing) {
    echo json_encode(['success' => false, 'message' => 'Add-on already exists for this booking']);
    exit;
}

// Insert the addon
$insert_query = "INSERT INTO booking_addons (booking_id, addon_id, quantity, price) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param($stmt, 'iiid', $booking_id, $addon_id, $quantity, $price);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    
    // Update booking total amount
    $update_query = "UPDATE bookings SET total_amount = total_amount + ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'di', $price, $booking_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    // Get updated total
    $total_query = "SELECT total_amount FROM bookings WHERE id = ?";
    $stmt = mysqli_prepare($conn, $total_query);
    mysqli_stmt_bind_param($stmt, 'i', $booking_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $updated_booking = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Add-on added successfully',
        'new_total' => $updated_booking['total_amount'],
        'addon_price' => $price
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add add-on: ' . mysqli_error($conn)]);
}
