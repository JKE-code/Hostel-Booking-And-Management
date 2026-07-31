<?php
session_start();
require_once '../config/db.php';
require_once '../config/razorpay.php';

header('Content-Type: application/json');

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

// Check if booking data exists in session
if (!isset($_SESSION['booking'])) {
    echo json_encode(['success' => false, 'error' => 'No booking data found in session']);
    exit();
}

$booking = $_SESSION['booking'];
$payment_id        = $_POST['payment_id'] ?? '';
$payment_type      = $_POST['payment_type'] ?? 'full';
$razorpay_order_id = $_POST['razorpay_order_id'] ?? '';
$razorpay_signature = $_POST['razorpay_signature'] ?? '';

// Validate payment ID
if (empty($payment_id)) {
    echo json_encode(['success' => false, 'error' => 'Payment ID is required']);
    exit();
}

// ----------------------------------------------------------------
// Razorpay Signature Verification
// Verify the payment is genuine before saving anything to the DB.
// Without this check a bad actor could POST a fake payment_id.
// ----------------------------------------------------------------
if (!empty($razorpay_order_id) && !empty($razorpay_signature)) {
    // Standard flow: order was created server-side, verify signature
    if (!verifyRazorpaySignature($razorpay_order_id, $payment_id, $razorpay_signature)) {
        error_log("Razorpay signature verification failed. payment_id=$payment_id order_id=$razorpay_order_id");
        echo json_encode(['success' => false, 'error' => 'Payment verification failed. Please contact support.']);
        exit();
    }
} else {
    // No order_id / signature means a direct payment (no server-side order).
    // Verify the payment exists and is captured via Razorpay Fetch API.
    $ch = curl_init(RAZORPAY_API_URL . 'payments/' . urlencode($payment_id));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD        => getRazorpayKeyId() . ':' . getRazorpayKeySecret(),
        CURLOPT_TIMEOUT        => 10,
    ]);
    $response     = curl_exec($ch);
    $http_code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error   = curl_error($ch);
    curl_close($ch);

    if ($curl_error || $http_code !== 200) {
        error_log("Razorpay payment fetch failed. payment_id=$payment_id http=$http_code curl_err=$curl_error");
        echo json_encode(['success' => false, 'error' => 'Could not verify payment with Razorpay. Please contact support.']);
        exit();
    }

    $payment_data = json_decode($response, true);
    if (empty($payment_data['id']) || $payment_data['status'] !== 'captured') {
        error_log("Razorpay payment not captured. payment_id=$payment_id status=" . ($payment_data['status'] ?? 'unknown'));
        echo json_encode(['success' => false, 'error' => 'Payment has not been captured. Please contact support.']);
        exit();
    }
}

try {
    // Start transaction
    if (isset($pdo)) {
        $pdo->beginTransaction();
    } else {
        mysqli_begin_transaction($conn);
    }
    
    // Calculate amounts
    $rooms = $booking['rooms'] ?? [];
    $nights = $booking['nights'] ?? 1;
    
    // Get room prices from database
    $room_prices = [];
    if (isset($pdo)) {
        $stmt = $pdo->query("SELECT name, price_per_night FROM room_types WHERE status = 'active'");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name_key = strtolower(str_replace(['-', ' '], '', $row['name']));
            $room_prices[$name_key] = floatval($row['price_per_night']);
        }
    } else {
        $result = mysqli_query($conn, "SELECT name, price_per_night FROM room_types WHERE status = 'active'");
        while ($row = mysqli_fetch_assoc($result)) {
            $name_key = strtolower(str_replace(['-', ' '], '', $row['name']));
            $room_prices[$name_key] = floatval($row['price_per_night']);
        }
    }
    
    // Calculate room rate
    $room_rate = 0;
    foreach ($rooms as $type => $qty) {
        if ($qty > 0 && isset($room_prices[$type])) {
            $room_rate += $room_prices[$type] * $qty;
        }
    }
    
    $subtotal = $room_rate * $nights;
    
    // Fetch addon prices from database
    $addon_prices = [];
    try {
        if (isset($pdo)) {
            $stmt = $pdo->query("SELECT name, price, charge_type FROM addons WHERE status = 'active'");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $addon_key = strtolower(str_replace(' ', '_', $row['name']));
                $addon_prices[$addon_key] = [
                    'price' => floatval($row['price']),
                    'charge_type' => $row['charge_type']
                ];
            }
        } else {
            $result = mysqli_query($conn, "SELECT name, price, charge_type FROM addons WHERE status = 'active'");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $addon_key = strtolower(str_replace(' ', '_', $row['name']));
                    $addon_prices[$addon_key] = [
                        'price' => floatval($row['price']),
                        'charge_type' => $row['charge_type']
                    ];
                }
            }
        }
    } catch (Exception $e) {
        error_log('Error fetching addon prices in payment: ' . $e->getMessage());
    }
    
    // Extra costs (fetch from database or use defaults)
    $extra_persons = $booking['extra_persons'] ?? 0;
    $extra_person_price = $addon_prices['extra_person']['price'] ?? 500;
    $extra_person_charge_type = $addon_prices['extra_person']['charge_type'] ?? 'per_night';
    $extra_persons_cost = $extra_person_charge_type === 'per_night' 
        ? $extra_persons * $extra_person_price * $nights 
        : $extra_persons * $extra_person_price;
    
    $extra_breakfast = $booking['extra_breakfast'] ?? 0;
    $breakfast_price = $addon_prices['breakfast']['price'] ?? 200;
    $breakfast_charge_type = $addon_prices['breakfast']['charge_type'] ?? 'per_night';
    $breakfast_cost = $breakfast_charge_type === 'per_night' 
        ? $extra_breakfast * $breakfast_price * $nights 
        : $extra_breakfast * $breakfast_price;
    
    // Addon costs (fetch from database or use defaults)
    $campfire_cost = 0;
    $dhimsa_cost = 0;
    if (isset($booking['addons'])) {
        if (in_array('campfire', $booking['addons'])) {
            $campfire_price = $addon_prices['campfire']['price'] ?? 2000;
            $campfire_charge_type = $addon_prices['campfire']['charge_type'] ?? 'one_time';
            $campfire_cost = $campfire_charge_type === 'per_night' 
                ? $campfire_price * $nights 
                : $campfire_price;
        }
        if (in_array('dhimsa', $booking['addons'])) {
            $dhimsa_price = $addon_prices['dhimsa_dance']['price'] ?? 3000;
            $dhimsa_charge_type = $addon_prices['dhimsa_dance']['charge_type'] ?? 'one_time';
            $dhimsa_cost = $dhimsa_charge_type === 'per_night' 
                ? $dhimsa_price * $nights 
                : $dhimsa_price;
        }
    }
    
    $base_total = $subtotal + $extra_persons_cost + $breakfast_cost + $campfire_cost + $dhimsa_cost;
    
    // Smart offers discount (DISABLED)
    $smart_offers_discount = 0;
    
    // DISABLED: Early booking discount
    // $checkin = new DateTime($booking['checkin']);
    // $today = new DateTime();
    // $days_advance = $today->diff($checkin)->days;
    // if ($days_advance >= 7) {
    //     $smart_offers_discount += $base_total * 0.10;
    // }
    
    // DISABLED: Multi-night discounts
    // if ($nights == 2) {
    //     $smart_offers_discount += 500;
    // } elseif ($nights >= 5) {
    //     $smart_offers_discount += 850;
    // }
    
    $total_after_smart_offers = $base_total - $smart_offers_discount;
    
    // Coupon discount
    $coupon_discount = $booking['coupon_discount'] ?? 0;
    $total_after_discounts = $total_after_smart_offers - $coupon_discount;
    
    // Processing fees
    $processing_fees = $total_after_discounts * 0.03;
    $total_amount = $total_after_discounts + $processing_fees;
    
    // Paid amount
    $paid_amount = ($payment_type === 'advance') ? ($total_amount * 0.50) : $total_amount;
    $balance_amount = $total_amount - $paid_amount;
    
    // Insert booking into database
    $booking_status = 'confirmed';
    $payment_status_value = ($payment_type === 'advance') ? 'advance' : 'full';
    
    $guest_name = $booking['name'] ?? '';
    $guest_email = $booking['email'] ?? '';
    $guest_mobile = $booking['mobile'] ?? '';
    $adults = $booking['adults'] ?? 2;
    $children = $booking['children'] ?? 0;
    $special_requests = $booking['special_requests'] ?? '';
    $checkin_date = $booking['checkin'] ?? '';
    $checkout_date = $booking['checkout'] ?? '';
    
    // Generate booking reference
    $booking_ref = 'AR' . date('Ymd') . rand(1000, 9999);
    
    // Insert into bookings table
    if (isset($pdo)) {
        $sql = "INSERT INTO bookings (
            booking_ref, checkin, checkout, nights, status, booking_source,
            special_requirements, total_amount, amount_paid, balance_due,
            payment_type, processing_fee, coupon_id, discount_amount, created_at
        ) VALUES (
            ?, ?, ?, ?, ?, 'online', ?, ?, ?, ?, ?, ?, ?, ?, NOW()
        )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $booking_ref, $checkin_date, $checkout_date, $nights, $booking_status,
            $special_requests, $total_amount, $paid_amount, $balance_amount,
            $payment_status_value, $processing_fees, 
            ($booking['coupon_id'] ?? null), $coupon_discount
        ]);
        
        $booking_id = $pdo->lastInsertId();
    } else {
        $coupon_id_value = isset($booking['coupon_id']) ? $booking['coupon_id'] : 'NULL';
        
        $sql = "INSERT INTO bookings (
            booking_ref, checkin, checkout, nights, status, booking_source,
            special_requirements, total_amount, amount_paid, balance_due,
            payment_type, processing_fee, coupon_id, discount_amount, created_at
        ) VALUES (
            '$booking_ref', '$checkin_date', '$checkout_date', $nights, '$booking_status', 'online',
            '$special_requests', $total_amount, $paid_amount, $balance_amount,
            '$payment_status_value', $processing_fees, $coupon_id_value, $coupon_discount, NOW()
        )";
        
        mysqli_query($conn, $sql);
        $booking_id = mysqli_insert_id($conn);
    }
    
    // Insert guest information
    if (isset($pdo)) {
        $stmt = $pdo->prepare("INSERT INTO guests (
            booking_id, full_name, email, mobile, adults, children, 
            extra_persons, special_requests, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        
        $stmt->execute([
            $booking_id, $guest_name, $guest_email, $guest_mobile,
            $adults, $children, $extra_persons, $special_requests
        ]);
    } else {
        mysqli_query($conn, "INSERT INTO guests (
            booking_id, full_name, email, mobile, adults, children, 
            extra_persons, special_requests, created_at
        ) VALUES (
            $booking_id, '$guest_name', '$guest_email', '$guest_mobile',
            $adults, $children, $extra_persons, '$special_requests', NOW()
        )");
    }
    
    // Insert room bookings
    foreach ($rooms as $type => $qty) {
        if ($qty > 0) {
            // Get room_type_id from database
            $room_type_name = ucfirst($type);
            
            if (isset($pdo)) {
                $stmt = $pdo->prepare("SELECT id, price_per_night FROM room_types WHERE LOWER(REPLACE(REPLACE(name, '-', ''), ' ', '')) = ? AND status = 'active'");
                $stmt->execute([strtolower($type)]);
                $room_type = $stmt->fetch();
                
                if ($room_type) {
                    $price_per_night = $room_type['price_per_night'];
                    $total_price = $price_per_night * $nights * $qty;
                    
                    $stmt = $pdo->prepare("INSERT INTO booking_rooms (booking_id, room_type_id, rooms_booked, price_per_night, total_price) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$booking_id, $room_type['id'], $qty, $price_per_night, $total_price]);
                }
            } else {
                $result = mysqli_query($conn, "SELECT id, price_per_night FROM room_types WHERE LOWER(REPLACE(REPLACE(name, '-', ''), ' ', '')) = '" . strtolower($type) . "' AND status = 'active'");
                $room_type = mysqli_fetch_assoc($result);
                
                if ($room_type) {
                    $price_per_night = $room_type['price_per_night'];
                    $total_price = $price_per_night * $nights * $qty;
                    
                    mysqli_query($conn, "INSERT INTO booking_rooms (booking_id, room_type_id, rooms_booked, price_per_night, total_price) VALUES ($booking_id, " . $room_type['id'] . ", $qty, $price_per_night, $total_price)");
                }
            }
        }
    }
    
    // Insert addons if any
    if (isset($booking['addons']) && is_array($booking['addons'])) {
        foreach ($booking['addons'] as $addon_name) {
            $addon_display_name = ucfirst(str_replace('_', ' ', $addon_name));
            
            if (isset($pdo)) {
                $stmt = $pdo->prepare("SELECT id, price FROM addons WHERE LOWER(REPLACE(name, ' ', '_')) = ? AND status = 'active'");
                $stmt->execute([strtolower($addon_name)]);
                $addon = $stmt->fetch();
                
                if ($addon) {
                    $stmt = $pdo->prepare("INSERT INTO booking_addons (booking_id, addon_id, quantity, price) VALUES (?, ?, 1, ?)");
                    $stmt->execute([$booking_id, $addon['id'], $addon['price']]);
                }
            } else {
                $result = mysqli_query($conn, "SELECT id, price FROM addons WHERE LOWER(REPLACE(name, ' ', '_')) = '" . strtolower($addon_name) . "' AND status = 'active'");
                $addon = mysqli_fetch_assoc($result);
                
                if ($addon) {
                    mysqli_query($conn, "INSERT INTO booking_addons (booking_id, addon_id, quantity, price) VALUES ($booking_id, " . $addon['id'] . ", 1, " . $addon['price'] . ")");
                }
            }
        }
    }
    
    // Insert payment record
    if (isset($pdo)) {
        $stmt = $pdo->prepare("INSERT INTO payments (booking_id, razorpay_payment_id, method, amount, status, payment_date) VALUES (?, ?, 'razorpay', ?, 'success', NOW())");
        $stmt->execute([$booking_id, $payment_id, $paid_amount]);
    } else {
        mysqli_query($conn, "INSERT INTO payments (booking_id, razorpay_payment_id, method, amount, status, payment_date) VALUES ($booking_id, '$payment_id', 'razorpay', $paid_amount, 'success', NOW())");
    }
    
    // Update coupon usage if coupon was used
    if (isset($booking['coupon_id']) && $booking['coupon_id'] > 0) {
        if (isset($pdo)) {
            $stmt = $pdo->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?");
            $stmt->execute([$booking['coupon_id']]);
            
            $stmt = $pdo->prepare("INSERT INTO coupon_usage (coupon_id, booking_id, mobile, discount_amount, used_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$booking['coupon_id'], $booking_id, $guest_mobile, $coupon_discount]);
        } else {
            mysqli_query($conn, "UPDATE coupons SET used_count = used_count + 1 WHERE id = " . $booking['coupon_id']);
            mysqli_query($conn, "INSERT INTO coupon_usage (coupon_id, booking_id, mobile, discount_amount, used_at) VALUES (" . $booking['coupon_id'] . ", $booking_id, '$guest_mobile', $coupon_discount, NOW())");
        }
    }
    
    // Commit transaction
    if (isset($pdo)) {
        $pdo->commit();
    } else {
        mysqli_commit($conn);
    }
    
    // Clear booking session
    unset($_SESSION['booking']);
    
    // Return success
    echo json_encode([
        'success' => true,
        'booking_id' => $booking_id,
        'booking_ref' => $booking_ref
    ]);
    
} catch (Exception $e) {
    // Rollback transaction
    if (isset($pdo)) {
        $pdo->rollBack();
    } else {
        mysqli_rollback($conn);
    }
    
    error_log('Booking error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Failed to save booking: ' . $e->getMessage()
    ]);
}
?>
