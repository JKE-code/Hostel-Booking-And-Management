<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

$coupon_code = strtoupper(trim($_POST['coupon_code'] ?? ''));
$phone = trim($_POST['phone'] ?? '');

// Validate inputs
if (empty($coupon_code)) {
    echo json_encode(['success' => false, 'error' => 'Please enter a coupon code']);
    exit();
}

if (empty($phone) || !preg_match('/^[0-9]{10}$/', $phone)) {
    echo json_encode(['success' => false, 'error' => 'Please enter a valid 10-digit phone number']);
    exit();
}

// Check if booking data exists in session
if (!isset($_SESSION['booking'])) {
    echo json_encode(['success' => false, 'error' => 'No booking data found. Please start from step 1.']);
    exit();
}

try {
    // Fetch coupon from database
    $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code = ? AND status = 'active'");
    $stmt->execute([$coupon_code]);
    $coupon = $stmt->fetch();
    
    if (!$coupon) {
        echo json_encode(['success' => false, 'error' => 'Invalid or expired coupon code']);
        exit();
    }
    
    // Check expiry date
    if ($coupon['expiry_date'] && strtotime($coupon['expiry_date']) < time()) {
        echo json_encode(['success' => false, 'error' => 'This coupon has expired']);
        exit();
    }
    
    // Check usage limit
    if ($coupon['max_uses'] && $coupon['used_count'] >= $coupon['max_uses']) {
        echo json_encode(['success' => false, 'error' => 'This coupon has reached its usage limit']);
        exit();
    }
    
    // Calculate booking subtotal (before coupon)
    $booking = $_SESSION['booking'];
    $subtotal = 0;
    
    // Get room prices
    if (isset($booking['room_id'])) {
        $room_stmt = $pdo->prepare("SELECT price_per_night FROM rooms WHERE id = ?");
        $room_stmt->execute([$booking['room_id']]);
        $room = $room_stmt->fetch();
        
        if ($room) {
            $nights = isset($booking['nights']) ? intval($booking['nights']) : 1;
            $subtotal = $room['price_per_night'] * $nights;
        }
    } else if (isset($booking['rooms'])) {
        // Get room prices from room_types
        $room_prices = [];
        $stmt = $pdo->query("SELECT name, price_per_night FROM room_types WHERE status = 'active'");
        while ($row = $stmt->fetch()) {
            $name_key = strtolower(str_replace(['-', ' '], '', $row['name']));
            $room_prices[$name_key] = floatval($row['price_per_night']);
        }
        
        // Calculate subtotal from rooms array
        $nights = isset($booking['nights']) ? intval($booking['nights']) : 1;
        foreach ($booking['rooms'] as $type => $qty) {
            if ($qty > 0 && isset($room_prices[$type])) {
                $subtotal += $room_prices[$type] * $qty * $nights;
            }
        }
    }
    
    // Add extra person charges if any
    if (isset($booking['extra_persons_cost'])) {
        $subtotal += floatval($booking['extra_persons_cost']);
    }
    
    // Add breakfast charges if any
    if (isset($booking['breakfast_cost'])) {
        $subtotal += floatval($booking['breakfast_cost']);
    }
    
    // Add addon costs
    if (isset($booking['addons']) && is_array($booking['addons'])) {
        foreach ($booking['addons'] as $addon) {
            if ($addon === 'campfire') {
                $addon_stmt = $pdo->query("SELECT price FROM addons WHERE name = 'Campfire' AND status = 'active'");
                $addon_data = $addon_stmt->fetch();
                if ($addon_data) {
                    $subtotal += $addon_data['price'];
                }
            } elseif ($addon === 'dhimsa') {
                $addon_stmt = $pdo->query("SELECT price FROM addons WHERE name = 'Dhimsa Dance' AND status = 'active'");
                $addon_data = $addon_stmt->fetch();
                if ($addon_data) {
                    $subtotal += $addon_data['price'];
                }
            }
        }
    }
    
    // Check minimum amount requirement
    if ($coupon['min_amount'] && $subtotal < $coupon['min_amount']) {
        echo json_encode([
            'success' => false, 
            'error' => 'Minimum booking amount of ₹' . number_format($coupon['min_amount'], 0) . ' required for this coupon'
        ]);
        exit();
    }
    
    // Calculate discount
    $discount = 0;
    if ($coupon['discount_type'] === 'percent') {
        $discount = ($subtotal * $coupon['discount_value']) / 100;
    } else {
        $discount = $coupon['discount_value'];
    }
    
    // Ensure discount doesn't exceed subtotal
    $discount = min($discount, $subtotal);
    
    // Store coupon info in session
    $_SESSION['booking']['coupon_code'] = $coupon_code;
    $_SESSION['booking']['coupon_id'] = $coupon['id'];
    $_SESSION['booking']['coupon_discount'] = $discount;
    $_SESSION['booking']['customer_phone'] = $phone;
    
    echo json_encode([
        'success' => true,
        'message' => 'Coupon applied successfully!',
        'discount' => $discount,
        'discount_formatted' => number_format($discount, 0),
        'coupon_code' => $coupon_code
    ]);
    
} catch (Exception $e) {
    error_log('Coupon verification error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred while verifying the coupon. Please try again.'
    ]);
}
?>
