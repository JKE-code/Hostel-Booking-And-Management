<?php
// Enable error logging but don't display errors
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Prevent any output before JSON
ob_start();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once __DIR__ . '/../config/db.php';

// Clear any previous output
ob_end_clean();

// Set JSON headers
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

try {
    // Check if booking ID is provided
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'Booking ID not provided']);
        exit;
    }

    $booking_id = intval($_GET['id']);

    // Check database connection
    if (!$conn || mysqli_connect_errno()) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }

    // Fetch booking details
    $query = "SELECT * FROM bookings WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Query preparation failed']);
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'i', $booking_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        echo json_encode(['success' => false, 'message' => 'Query execution failed']);
        exit;
    }

    $result = mysqli_stmt_get_result($stmt);
    $booking = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$booking) {
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
        exit;
    }

    // Calculate nights
    try {
        $checkin = new DateTime($booking['checkin']);
        $checkout = new DateTime($booking['checkout']);
        $booking['nights'] = $checkin->diff($checkout)->days;
    } catch (Exception $e) {
        $booking['nights'] = 1;
    }

    // Fetch guest details
    $guest_query = "SELECT full_name, email, mobile FROM guests WHERE booking_id = ? LIMIT 1";
    $guest_stmt = mysqli_prepare($conn, $guest_query);
    
    if ($guest_stmt) {
        mysqli_stmt_bind_param($guest_stmt, 'i', $booking_id);
        mysqli_stmt_execute($guest_stmt);
        $guest_result = mysqli_stmt_get_result($guest_stmt);
        $guest = mysqli_fetch_assoc($guest_result);
        mysqli_stmt_close($guest_stmt);

        if ($guest) {
            $booking['full_name'] = $guest['full_name'];
            $booking['email'] = $guest['email'];
            $booking['mobile'] = $guest['mobile'];
        } else {
            $booking['full_name'] = 'N/A';
            $booking['email'] = 'N/A';
            $booking['mobile'] = 'N/A';
        }
    } else {
        $booking['full_name'] = 'N/A';
        $booking['email'] = 'N/A';
        $booking['mobile'] = 'N/A';
    }

    // Get total guests
    $guests_query = "SELECT 
        COALESCE(SUM(adults), 0) as total_adults,
        COALESCE(SUM(children), 0) as total_children,
        COALESCE(SUM(extra_persons), 0) as total_extra_persons
        FROM guests WHERE booking_id = ?";
    $guests_stmt = mysqli_prepare($conn, $guests_query);
    
    if ($guests_stmt) {
        mysqli_stmt_bind_param($guests_stmt, 'i', $booking_id);
        mysqli_stmt_execute($guests_stmt);
        $guests_result = mysqli_stmt_get_result($guests_stmt);
        $guests_data = mysqli_fetch_assoc($guests_result);
        mysqli_stmt_close($guests_stmt);

        $booking['total_adults'] = intval($guests_data['total_adults']);
        $booking['total_children'] = intval($guests_data['total_children']);
        $booking['total_extra_persons'] = intval($guests_data['total_extra_persons']);
    } else {
        $booking['total_adults'] = 0;
        $booking['total_children'] = 0;
        $booking['total_extra_persons'] = 0;
    }

    // Fetch room details
    $rooms_query = "SELECT rt.name, br.rooms_booked, br.price_per_night
        FROM booking_rooms br
        JOIN room_types rt ON br.room_type_id = rt.id
        WHERE br.booking_id = ?";
    $rooms_stmt = mysqli_prepare($conn, $rooms_query);
    
    $room_details = [];
    if ($rooms_stmt) {
        mysqli_stmt_bind_param($rooms_stmt, 'i', $booking_id);
        mysqli_stmt_execute($rooms_stmt);
        $rooms_result = mysqli_stmt_get_result($rooms_stmt);

        while ($room = mysqli_fetch_assoc($rooms_result)) {
            $room_details[] = $room['name'] . ' (' . $room['rooms_booked'] . ')';
        }
        mysqli_stmt_close($rooms_stmt);
    }
    
    $booking['room_details'] = !empty($room_details) ? implode(', ', $room_details) : 'No rooms';

    // Fetch add-ons
    $addons_query = "SELECT a.name, ba.quantity, ba.price
        FROM booking_addons ba
        JOIN addons a ON ba.addon_id = a.id
        WHERE ba.booking_id = ?";
    $addons_stmt = mysqli_prepare($conn, $addons_query);
    
    $addons = [];
    if ($addons_stmt) {
        mysqli_stmt_bind_param($addons_stmt, 'i', $booking_id);
        mysqli_stmt_execute($addons_stmt);
        $addons_result = mysqli_stmt_get_result($addons_stmt);

        while ($addon = mysqli_fetch_assoc($addons_result)) {
            $addons[] = [
                'name' => $addon['name'],
                'quantity' => intval($addon['quantity']),
                'price' => floatval($addon['price'])
            ];
        }
        mysqli_stmt_close($addons_stmt);
    }
    
    $booking['addons'] = $addons;

    // Ensure numeric fields are properly formatted
    $booking['balance_due'] = isset($booking['balance_due']) ? floatval($booking['balance_due']) : 0;
    $booking['processing_fee'] = isset($booking['processing_fee']) ? floatval($booking['processing_fee']) : 0;
    $booking['total_amount'] = isset($booking['total_amount']) ? floatval($booking['total_amount']) : 0;

    // Return success response
    echo json_encode([
        'success' => true, 
        'booking' => $booking
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Log the error
    error_log("Booking details error: " . $e->getMessage());
    
    // Return error response
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred while fetching booking details'
    ]);
}

exit;
