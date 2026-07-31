<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once 'includes/session_check.php';

// Check if booking ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'Booking ID not provided';
    header('Location: bookings.php');
    exit;
}

$booking_id = intval($_GET['id']);

// Fetch booking details
$query = "SELECT booking_ref FROM bookings WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    $_SESSION['error'] = 'Booking not found';
    header('Location: bookings.php');
    exit;
}

$booking_ref = $booking['booking_ref'];

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Delete booking add-ons
    $delete_addons = "DELETE FROM booking_addons WHERE booking_id = ?";
    $stmt_addons = mysqli_prepare($conn, $delete_addons);
    mysqli_stmt_bind_param($stmt_addons, 'i', $booking_id);
    mysqli_stmt_execute($stmt_addons);
    
    // Delete booking rooms
    $delete_rooms = "DELETE FROM booking_rooms WHERE booking_id = ?";
    $stmt_rooms = mysqli_prepare($conn, $delete_rooms);
    mysqli_stmt_bind_param($stmt_rooms, 'i', $booking_id);
    mysqli_stmt_execute($stmt_rooms);
    
    // Delete guests
    $delete_guests = "DELETE FROM guests WHERE booking_id = ?";
    $stmt_guests = mysqli_prepare($conn, $delete_guests);
    mysqli_stmt_bind_param($stmt_guests, 'i', $booking_id);
    mysqli_stmt_execute($stmt_guests);
    
    // Delete booking
    $delete_booking = "DELETE FROM bookings WHERE id = ?";
    $stmt_booking = mysqli_prepare($conn, $delete_booking);
    mysqli_stmt_bind_param($stmt_booking, 'i', $booking_id);
    mysqli_stmt_execute($stmt_booking);
    
    // Commit transaction
    mysqli_commit($conn);
    
    $_SESSION['success'] = 'Booking ' . $booking_ref . ' has been deleted successfully';
    
    mysqli_stmt_close($stmt_addons);
    mysqli_stmt_close($stmt_rooms);
    mysqli_stmt_close($stmt_guests);
    mysqli_stmt_close($stmt_booking);
    
} catch (Exception $e) {
    // Rollback on error
    mysqli_rollback($conn);
    $_SESSION['error'] = 'Failed to delete booking: ' . $e->getMessage();
}

mysqli_stmt_close($stmt);

header('Location: bookings.php');
exit;
?>
