<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once 'includes/session_check.php';

header('Content-Type: application/json');

if (!isset($_GET['room_id'])) {
    echo json_encode(['success' => false, 'message' => 'Room ID not provided']);
    exit;
}

$room_id = intval($_GET['room_id']);
$today = date('Y-m-d');

// Get room basic info
$query = "SELECT r.id, r.room_number, r.room_type_id, rt.name as room_type_name
          FROM rooms r
          JOIN room_types rt ON r.room_type_id = rt.id
          WHERE r.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $room_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$room = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$room) {
    echo json_encode(['success' => false, 'message' => 'Room not found']);
    exit;
}

// Get current booking if occupied today
$query = "
    SELECT 
        b.id as booking_id,
        b.booking_ref,
        b.checkin,
        b.checkout,
        b.status,
        g.full_name as guest_name,
        g.email as guest_email,
        g.mobile as guest_mobile
    FROM booking_room_assignments bra
    JOIN bookings b ON bra.booking_id = b.id
    JOIN guests g ON b.id = g.booking_id
    WHERE bra.room_id = ?
    AND b.status IN ('confirmed', 'checkedin')
    AND ? BETWEEN b.checkin AND b.checkout
";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'is', $room_id, $today);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$current_booking = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Get all bookings for this room
$query = "
    SELECT 
        b.booking_ref,
        b.checkin,
        b.checkout,
        b.status,
        g.full_name as guest_name
    FROM booking_room_assignments bra
    JOIN bookings b ON bra.booking_id = b.id
    JOIN guests g ON b.id = g.booking_id
    WHERE bra.room_id = ?
    AND b.status IN ('confirmed', 'checkedin', 'checkedout')
    ORDER BY b.checkin DESC
    LIMIT 20
";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $room_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$bookings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bookings[] = $row;
}
mysqli_stmt_close($stmt);

echo json_encode([
    'success' => true,
    'room' => $room,
    'status' => $current_booking ? 'occupied' : 'available',
    'current_booking' => $current_booking,
    'bookings' => $bookings
]);
