<?php
/**
 * Auto-assign specific room numbers to a booking
 * 
 * @param mysqli $conn Database connection
 * @param int $booking_id The booking ID
 * @param int $room_type_id The room type ID
 * @param int $rooms_needed Number of rooms to assign
 * @param string $checkin Check-in date (Y-m-d format)
 * @param string $checkout Check-out date (Y-m-d format)
 * @param int|null $admin_id Admin ID (optional, for tracking)
 * @return array ['success' => bool, 'assigned_rooms' => array, 'message' => string]
 */
function autoAssignRooms($conn, $booking_id, $room_type_id, $rooms_needed, $checkin, $checkout, $admin_id = null) {
    // Find available rooms of this type for the given date range
    $query = "
        SELECT r.id, r.room_number
        FROM rooms r
        WHERE r.room_type_id = ?
        AND r.id NOT IN (
            SELECT bra.room_id
            FROM booking_room_assignments bra
            JOIN bookings b ON bra.booking_id = b.id
            WHERE b.status IN ('pending', 'confirmed', 'checkedin')
            AND b.id != ?
            AND (
                (b.checkin <= ? AND b.checkout > ?)
                OR (b.checkin < ? AND b.checkout >= ?)
                OR (b.checkin >= ? AND b.checkout <= ?)
            )
        )
        ORDER BY r.room_number
        LIMIT ?
    ";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'iisssssi', 
        $room_type_id, 
        $booking_id,
        $checkin, $checkin,
        $checkout, $checkout,
        $checkin, $checkout,
        $rooms_needed
    );
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $available_rooms = [];
    while ($room = mysqli_fetch_assoc($result)) {
        $available_rooms[] = $room;
    }
    mysqli_stmt_close($stmt);
    
    // Check if we have enough rooms
    if (count($available_rooms) < $rooms_needed) {
        return [
            'success' => false,
            'assigned_rooms' => [],
            'message' => "Not enough available rooms. Found " . count($available_rooms) . " but need $rooms_needed"
        ];
    }
    
    // Assign the rooms
    $assigned_rooms = [];
    $insert_query = "INSERT INTO booking_room_assignments (booking_id, room_id, assigned_by_admin_id) VALUES (?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    
    foreach ($available_rooms as $room) {
        mysqli_stmt_bind_param($insert_stmt, 'iii', $booking_id, $room['id'], $admin_id);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            $assigned_rooms[] = $room['room_number'];
        } else {
            mysqli_stmt_close($insert_stmt);
            return [
                'success' => false,
                'assigned_rooms' => $assigned_rooms,
                'message' => "Failed to assign room {$room['room_number']}: " . mysqli_error($conn)
            ];
        }
    }
    
    mysqli_stmt_close($insert_stmt);
    
    return [
        'success' => true,
        'assigned_rooms' => $assigned_rooms,
        'message' => "Successfully assigned " . count($assigned_rooms) . " room(s): " . implode(', ', $assigned_rooms)
    ];
}

/**
 * Auto-assign rooms for all room types in a booking
 * 
 * @param mysqli $conn Database connection
 * @param int $booking_id The booking ID
 * @param int|null $admin_id Admin ID (optional)
 * @return array ['success' => bool, 'results' => array, 'message' => string]
 */
function autoAssignAllRoomsForBooking($conn, $booking_id, $admin_id = null) {
    // Get booking details
    $booking_query = "SELECT checkin, checkout FROM bookings WHERE id = ?";
    $stmt = mysqli_prepare($conn, $booking_query);
    mysqli_stmt_bind_param($stmt, 'i', $booking_id);
    mysqli_stmt_execute($stmt);
    $booking_result = mysqli_stmt_get_result($stmt);
    $booking = mysqli_fetch_assoc($booking_result);
    mysqli_stmt_close($stmt);
    
    if (!$booking) {
        return [
            'success' => false,
            'results' => [],
            'message' => "Booking not found"
        ];
    }
    
    // Get all room types for this booking
    $rooms_query = "SELECT room_type_id, rooms_booked FROM booking_rooms WHERE booking_id = ?";
    $stmt = mysqli_prepare($conn, $rooms_query);
    mysqli_stmt_bind_param($stmt, 'i', $booking_id);
    mysqli_stmt_execute($stmt);
    $rooms_result = mysqli_stmt_get_result($stmt);
    
    $results = [];
    $all_success = true;
    $total_assigned = 0;
    
    while ($room_type = mysqli_fetch_assoc($rooms_result)) {
        $result = autoAssignRooms(
            $conn,
            $booking_id,
            $room_type['room_type_id'],
            $room_type['rooms_booked'],
            $booking['checkin'],
            $booking['checkout'],
            $admin_id
        );
        
        $results[] = $result;
        
        if (!$result['success']) {
            $all_success = false;
        } else {
            $total_assigned += count($result['assigned_rooms']);
        }
    }
    
    mysqli_stmt_close($stmt);
    
    return [
        'success' => $all_success,
        'results' => $results,
        'message' => $all_success 
            ? "Successfully assigned $total_assigned room(s)" 
            : "Some room assignments failed"
    ];
}
