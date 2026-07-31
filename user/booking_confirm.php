<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Check if payment was successful
if (!isset($_POST['razorpay_payment_id']) || !isset($_SESSION['booking']['payment'])) {
    header('Location: booking_step1_rooms.php');
    exit();
}

$razorpay_payment_id = $_POST['razorpay_payment_id'];
$payment = $_SESSION['booking']['payment'];
$booking = $_SESSION['booking'];

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Insert booking
    $booking_ref = $payment['booking_ref'];
    $checkin = $booking['checkin'];
    $checkout = $booking['checkout'];
    $nights = $booking['nights'];
    $total_amount = $payment['grand_total'];
    $amount_paid = $payment['amount_to_pay'];
    $balance_due = $payment['balance_due'];
    $payment_type = $payment['payment_type'];
    $coupon_id = isset($booking['coupon_id']) ? $booking['coupon_id'] : null;
    
    $query = "INSERT INTO bookings (booking_ref, checkin, checkout, nights, status, total_amount, amount_paid, balance_due, payment_type, processing_fee, coupon_id) 
              VALUES (?, ?, ?, ?, 'confirmed', ?, ?, ?, ?, 0, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssiddssi", $booking_ref, $checkin, $checkout, $nights, $total_amount, $amount_paid, $balance_due, $payment_type, $coupon_id);
    mysqli_stmt_execute($stmt);
    $booking_id = mysqli_insert_id($conn);
    
    // Insert guest information
    $guest = $booking['guest'];
    $query = "INSERT INTO guests (booking_id, full_name, email, mobile, adults, children, extra_persons, special_requests) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isssiiis", $booking_id, $guest['full_name'], $guest['email'], $guest['mobile'], 
                           $guest['adults'], $guest['children'], $guest['extra_persons'], $guest['special_requests']);
    mysqli_stmt_execute($stmt);
    
    // Insert booked rooms
    foreach ($booking['rooms'] as $room) {
        $query = "INSERT INTO booking_rooms (booking_id, room_type_id, rooms_booked, price_per_night) 
                  VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iiid", $booking_id, $room['type_id'], $room['quantity'], $room['price']);
        mysqli_stmt_execute($stmt);
    }
    
    // AUTO-ASSIGN SPECIFIC ROOM NUMBERS
    require_once __DIR__ . '/../admin/includes/room_assignment.php';
    $assignment_result = autoAssignAllRoomsForBooking($conn, $booking_id);
    
    // Log assignment result (optional - for debugging)
    if (!$assignment_result['success']) {
        error_log("Room auto-assignment warning for booking $booking_id: " . $assignment_result['message']);
        // Note: We don't fail the booking if room assignment fails
        // Rooms can be assigned manually later by admin
    }
    
    // Insert addons if any
    if (isset($booking['addons']) && !empty($booking['addons'])) {
        foreach ($booking['addons'] as $addon) {
            $addon_price = $addon['charge_type'] == 'per_night' ? $addon['price'] * $nights : $addon['price'];
            $query = "INSERT INTO booking_addons (booking_id, addon_id, quantity, price) 
                      VALUES (?, ?, 1, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "iid", $booking_id, $addon['id'], $addon_price);
            mysqli_stmt_execute($stmt);
        }
    }
    
    // Insert payment record
    $query = "INSERT INTO payments (booking_id, razorpay_payment_id, method, amount, status) 
              VALUES (?, ?, 'razorpay', ?, 'success')";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isd", $booking_id, $razorpay_payment_id, $amount_paid);
    mysqli_stmt_execute($stmt);
    
    // Update coupon usage if coupon was used
    if ($coupon_id) {
        $query = "UPDATE coupons SET used_count = used_count + 1 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $coupon_id);
        mysqli_stmt_execute($stmt);
        
        // Insert coupon usage record
        $query = "INSERT INTO coupon_usage (coupon_id, booking_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $coupon_id, $booking_id);
        mysqli_stmt_execute($stmt);
    }
    
    // Commit transaction
    mysqli_commit($conn);
    
    // Store booking details for confirmation page
    $_SESSION['confirmed_booking'] = [
        'booking_id' => $booking_id,
        'booking_ref' => $booking_ref,
        'guest_name' => $guest['full_name'],
        'guest_email' => $guest['email'],
        'guest_mobile' => $guest['mobile'],
        'checkin' => $checkin,
        'checkout' => $checkout,
        'nights' => $nights,
        'total_amount' => $total_amount,
        'amount_paid' => $amount_paid,
        'balance_due' => $balance_due,
        'payment_id' => $razorpay_payment_id
    ];
    
    // Clear booking session
    unset($_SESSION['booking']);
    
    // Redirect to success page
    header('Location: booking_success.php');
    exit();
    
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    
    // Log error
    error_log("Booking creation failed: " . $e->getMessage());
    
    // Redirect to error page
    header('Location: booking_error.php?error=' . urlencode('Failed to create booking. Please contact support.'));
    exit();
}
?>
