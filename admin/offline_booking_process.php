<?php
session_start();

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

require_once __DIR__ . '/../config/db.php';

if ($pdo === null) {
    die("Database connection failed.");
}

$action = $_POST['action'] ?? '';

// Step 1: Set Dates Only
if ($action === 'set_dates') {
    $checkin = $_POST['checkin'] ?? '';
    $checkout = $_POST['checkout'] ?? '';
    
    if (empty($checkin) || empty($checkout)) {
        $_SESSION['error_message'] = 'Please select check-in and check-out dates.';
        header('Location: offline_booking.php');
        exit;
    }
    
    // Calculate nights
    $checkin_date = new DateTime($checkin);
    $checkout_date = new DateTime($checkout);
    $nights = $checkin_date->diff($checkout_date)->days;
    
    if ($nights < 1) {
        $_SESSION['error_message'] = 'Checkout must be at least 1 day after checkin.';
        header('Location: offline_booking.php');
        exit;
    }
    
    $_SESSION['offline_booking']['dates'] = [
        'checkin' => $checkin,
        'checkout' => $checkout,
        'nights' => $nights
    ];
    
    $_SESSION['offline_booking']['step'] = 2;
    header('Location: offline_booking_step2.php');
    exit;
}

// Step 2: Select Rooms
if ($action === 'select_rooms') {
    $rooms = $_POST['rooms'] ?? [];
    $selected_rooms = [];
    
    foreach ($rooms as $room_id => $quantity) {
        if ($quantity > 0) {
            $selected_rooms[$room_id] = intval($quantity);
        }
    }
    
    if (empty($selected_rooms)) {
        $_SESSION['error_message'] = 'Please select at least one room.';
        header('Location: offline_booking_step2.php');
        exit;
    }
    
    $_SESSION['offline_booking']['rooms'] = $selected_rooms;
    $_SESSION['offline_booking']['step'] = 3;
    header('Location: offline_booking_step3.php');
    exit;
}

// Step 3: Guest Information
if ($action === 'set_guests') {
    $adults = intval($_POST['adults'] ?? 0);
    $children = intval($_POST['children'] ?? 0);
    $extra_persons = intval($_POST['extra_persons'] ?? 0);
    $guest_name = trim($_POST['guest_name'] ?? '');
    $guest_email = trim($_POST['guest_email'] ?? '');
    $guest_mobile = trim($_POST['guest_mobile'] ?? '');
    
    // Validate required fields
    if (empty($guest_name) || empty($guest_mobile) || $adults < 1) {
        $_SESSION['error_message'] = 'Please fill all required fields.';
        header('Location: offline_booking_step3.php');
        exit;
    }
    
    // Validate mobile number (exactly 10 digits)
    if (!preg_match('/^[0-9]{10}$/', $guest_mobile)) {
        $_SESSION['error_message'] = 'Mobile number must be exactly 10 digits.';
        header('Location: offline_booking_step3.php');
        exit;
    }
    
    $_SESSION['offline_booking']['guests'] = [
        'name' => $guest_name,
        'email' => $guest_email,
        'mobile' => $guest_mobile,
        'adults' => $adults,
        'children' => $children,
        'extra_persons' => $extra_persons
    ];
    
    $_SESSION['offline_booking']['step'] = 4;
    header('Location: offline_booking_step4.php');
    exit;
}

// Step 4: Add-ons
if ($action === 'set_addons') {
    $addons = $_POST['addons'] ?? [];
    $selected_addons = [];
    
    foreach ($addons as $addon_id => $quantity) {
        if ($quantity > 0) {
            $selected_addons[$addon_id] = intval($quantity);
        }
    }
    
    $_SESSION['offline_booking']['addons'] = $selected_addons;
    $_SESSION['offline_booking']['step'] = 5;
    header('Location: offline_booking_step5.php');
    exit;
}

// Step 5: Complete Booking
if ($action === 'complete_booking') {
    $payment_method = $_POST['payment_method'] ?? 'cash';
    $payment_type = $_POST['payment_type'] ?? 'full';
    $amount_paid = floatval($_POST['amount_paid'] ?? 0);
    $razorpay_payment_id = $_POST['razorpay_payment_id'] ?? null;
    
    try {
        $pdo->beginTransaction();
        
        // Calculate total
        $booking_data = $_SESSION['offline_booking'];
        $total_amount = 0;
        
        // Calculate room costs
        foreach ($booking_data['rooms'] as $room_id => $quantity) {
            $stmt = $pdo->prepare("SELECT price_per_night FROM room_types WHERE id = ?");
            $stmt->execute([$room_id]);
            $room = $stmt->fetch();
            $total_amount += $room['price_per_night'] * $quantity * $booking_data['dates']['nights'];
        }
        
        // Calculate addon costs
        foreach ($booking_data['addons'] as $addon_id => $quantity) {
            $stmt = $pdo->prepare("SELECT price, charge_type FROM addons WHERE id = ?");
            $stmt->execute([$addon_id]);
            $addon = $stmt->fetch();
            
            if ($addon['charge_type'] === 'per_night') {
                $total_amount += $addon['price'] * $quantity * $booking_data['dates']['nights'];
            } else {
                $total_amount += $addon['price'] * $quantity;
            }
        }
        
        // Calculate extra persons cost (fetch from database)
        $extra_persons = $booking_data['guests']['extra_persons'] ?? 0;
        if ($extra_persons > 0) {
            $extra_person_price = 500; // Default
            try {
                $stmt = $pdo->query("SELECT price FROM addons WHERE name = 'Extra Person' AND status = 'active'");
                $row = $stmt->fetch();
                if ($row) {
                    $extra_person_price = floatval($row['price']);
                }
            } catch (Exception $e) {
                error_log('Error fetching extra person price in offline booking: ' . $e->getMessage());
            }
            $total_amount += $extra_person_price * $extra_persons * $booking_data['dates']['nights'];
        }
        
        // Add 3% processing & platform fees (no GST for walk-in bookings)
        $processing_fee = $total_amount * 0.03;
        $total_amount = $total_amount + $processing_fee;
        
        // Generate booking reference
        $booking_ref = 'ALR' . date('YmdHi');
        
        // Insert booking
        $stmt = $pdo->prepare("
            INSERT INTO bookings (booking_ref, checkin, checkout, nights, status, booking_source, 
                                 created_by_admin_id, created_by_admin_name, total_amount, amount_paid, 
                                 balance_due, payment_type, processing_fee, created_at)
            VALUES (?, ?, ?, ?, 'confirmed', 'walkin', ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $balance_due = $total_amount - $amount_paid;
        
        $stmt->execute([
            $booking_ref,
            $booking_data['dates']['checkin'],
            $booking_data['dates']['checkout'],
            $booking_data['dates']['nights'],
            $_SESSION['admin_id'] ?? null,
            $_SESSION['admin_username'] ?? 'Staff',
            $total_amount,
            $amount_paid,
            $balance_due,
            $payment_type,
            $processing_fee
        ]);
        
        $booking_id = $pdo->lastInsertId();
        
        // Insert guest details
        $stmt = $pdo->prepare("
            INSERT INTO guests (booking_id, full_name, email, mobile, adults, children, extra_persons)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $booking_id,
            $booking_data['guests']['name'],
            $booking_data['guests']['email'],
            $booking_data['guests']['mobile'],
            $booking_data['guests']['adults'],
            $booking_data['guests']['children'],
            $booking_data['guests']['extra_persons'] ?? 0
        ]);
        
        // Insert room bookings
        foreach ($booking_data['rooms'] as $room_id => $quantity) {
            $stmt = $pdo->prepare("SELECT price_per_night FROM room_types WHERE id = ?");
            $stmt->execute([$room_id]);
            $room = $stmt->fetch();
            
            $stmt = $pdo->prepare("
                INSERT INTO booking_rooms (booking_id, room_type_id, rooms_booked, price_per_night)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$booking_id, $room_id, $quantity, $room['price_per_night']]);
        }
        
        // Insert addons
        foreach ($booking_data['addons'] as $addon_id => $quantity) {
            $stmt = $pdo->prepare("SELECT price FROM addons WHERE id = ?");
            $stmt->execute([$addon_id]);
            $addon = $stmt->fetch();
            
            $stmt = $pdo->prepare("
                INSERT INTO booking_addons (booking_id, addon_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$booking_id, $addon_id, $quantity, $addon['price']]);
        }
        
        // AUTO-ASSIGN SPECIFIC ROOM NUMBERS
        // Convert PDO connection to mysqli for room assignment function
        require_once __DIR__ . '/includes/room_assignment.php';
        $assignment_result = autoAssignAllRoomsForBooking($conn, $booking_id, $_SESSION['admin_id'] ?? null);
        
        // Log assignment result (optional - for debugging)
        if (!$assignment_result['success']) {
            error_log("Room auto-assignment warning for booking $booking_id: " . $assignment_result['message']);
            // Note: We don't fail the booking if room assignment fails
            // Rooms can be assigned manually later by admin
        }
        
        // Insert payment record
        $payment_id_value = $razorpay_payment_id ?? ($payment_method === 'cash' ? 'CASH_' . time() : 'ONLINE_' . time());
        
        $stmt = $pdo->prepare("
            INSERT INTO payments (booking_id, razorpay_payment_id, method, amount, status, paid_at)
            VALUES (?, ?, ?, ?, 'success', NOW())
        ");
        $stmt->execute([
            $booking_id,
            $payment_id_value,
            $payment_method,
            $amount_paid
        ]);
        
        $pdo->commit();
        
        // Clear session
        unset($_SESSION['offline_booking']);
        
        $_SESSION['success_message'] = 'Booking completed successfully! Booking Reference: ' . $booking_ref;
        header('Location: offline_booking_success.php?ref=' . $booking_ref);
        exit;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error_message'] = 'Error completing booking: ' . $e->getMessage();
        header('Location: offline_booking_step5.php');
        exit;
    }
}

// Go back
if ($action === 'go_back') {
    $current_step = $_SESSION['offline_booking']['step'];
    $_SESSION['offline_booking']['step'] = max(1, $current_step - 1);
    
    if ($current_step == 2) {
        header('Location: offline_booking.php');
    } elseif ($current_step == 3) {
        header('Location: offline_booking_step2.php');
    } elseif ($current_step == 4) {
        header('Location: offline_booking_step3.php');
    } elseif ($current_step == 5) {
        header('Location: offline_booking_step4.php');
    }
    exit;
}

// Cancel booking
if ($action === 'cancel') {
    unset($_SESSION['offline_booking']);
    $_SESSION['success_message'] = 'Booking cancelled.';
    header('Location: offline_booking.php');
    exit;
}

header('Location: offline_booking.php');
exit;
?>
