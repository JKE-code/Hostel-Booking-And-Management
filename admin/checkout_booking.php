<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once 'includes/session_check.php';
require_once 'includes/activity_logger.php';

// Check if booking ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'Booking ID not provided';
    header('Location: dashboard.php');
    exit;
}

$booking_id = intval($_GET['id']);

// Fetch booking details
$query = "SELECT * FROM bookings WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    $_SESSION['error'] = 'Booking not found';
    header('Location: dashboard.php');
    exit;
}

// Check if already checked out
if ($booking['status'] === 'checkedout') {
    $_SESSION['error'] = 'Booking is already checked out';
    header('Location: dashboard.php');
    exit;
}

$old_status = $booking['status'];

// Update booking status to checked out and record the checkout time
$update_query = "UPDATE bookings SET status = 'checkedout', checkout_time = NOW() WHERE id = ?";
$update_stmt = mysqli_prepare($conn, $update_query);
mysqli_stmt_bind_param($update_stmt, 'i', $booking_id);

if (mysqli_stmt_execute($update_stmt)) {
    // Log the activity
    logBookingActivity(
        $conn,
        $booking_id,
        'Check-out',
        'Guest checked out from the resort',
        $old_status,
        'checkedout'
    );
    
    $_SESSION['success'] = 'Booking ' . $booking['booking_ref'] . ' has been checked out successfully';
} else {
    $_SESSION['error'] = 'Failed to checkout booking: ' . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_stmt_close($update_stmt);

header('Location: dashboard.php');
exit;
?>
