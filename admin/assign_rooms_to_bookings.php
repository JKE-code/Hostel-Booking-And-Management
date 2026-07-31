<?php
/**
 * Helper script to assign specific room numbers to bookings
 * This assigns rooms based on room type and availability
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once 'includes/session_check.php';
require_once 'includes/room_assignment.php';

// Only main admin can run this
if ($_SESSION['admin_role'] !== 'main') {
    die('Access denied. Only main admin can run this script.');
}

echo "<h2>Room Assignment Script</h2>";
echo "<p>This script assigns specific room numbers to bookings that don't have room assignments yet.</p>";

// Get all bookings that need room assignments
$query = "
    SELECT 
        b.id as booking_id,
        b.booking_ref,
        b.checkin,
        b.checkout,
        b.status
    FROM bookings b
    WHERE b.status IN ('pending', 'confirmed', 'checkedin')
    AND NOT EXISTS (
        SELECT 1 FROM booking_room_assignments bra 
        WHERE bra.booking_id = b.id
    )
    ORDER BY b.checkin, b.id
";

$result = mysqli_query($conn, $query);
$bookings_to_assign = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings_to_assign[] = $row;
    }
}

if (empty($bookings_to_assign)) {
    echo "<p style='color: green;'>✓ All bookings already have room assignments!</p>";
    echo "<p><a href='rooms.php'>← Back to Rooms Dashboard</a></p>";
    exit;
}

echo "<h3>Found " . count($bookings_to_assign) . " booking(s) without room assignments:</h3>";

$assigned_count = 0;
$failed_count = 0;

foreach ($bookings_to_assign as $booking) {
    echo "<div style='border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
    echo "<strong>Booking: {$booking['booking_ref']}</strong><br>";
    echo "Check-in: {$booking['checkin']} | Check-out: {$booking['checkout']}<br>";
    echo "Status: {$booking['status']}<br>";
    
    // Use the auto-assignment function
    $result = autoAssignAllRoomsForBooking($conn, $booking['booking_id'], $_SESSION['admin_id']);
    
    if ($result['success']) {
        echo "<span style='color: green;'>✓ " . $result['message'] . "</span><br>";
        foreach ($result['results'] as $type_result) {
            if (!empty($type_result['assigned_rooms'])) {
                echo "<span style='color: green;'>  → Rooms: " . implode(', ', $type_result['assigned_rooms']) . "</span><br>";
            }
        }
        $assigned_count++;
    } else {
        echo "<span style='color: red;'>✗ " . $result['message'] . "</span><br>";
        foreach ($result['results'] as $type_result) {
            if (!$type_result['success']) {
                echo "<span style='color: orange;'>  → " . $type_result['message'] . "</span><br>";
            }
        }
        $failed_count++;
    }
    
    echo "</div>";
}

echo "<hr>";
echo "<h3>Summary:</h3>";
echo "<p>✓ Successfully assigned: $assigned_count booking(s)</p>";
echo "<p>✗ Failed: $failed_count booking(s)</p>";
echo "<p><a href='rooms.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>View Rooms Dashboard</a></p>";
?>
