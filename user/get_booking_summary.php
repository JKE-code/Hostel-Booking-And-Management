<?php
session_start();
header('Content-Type: application/json');

// Check if booking data exists
if (!isset($_SESSION['booking'])) {
    echo json_encode(['success' => false, 'error' => 'No booking data found', 'debug' => 'Session booking not set']);
    exit();
}

$booking = $_SESSION['booking'];

// Debug: Log what's in the session
error_log('Booking session data: ' . print_r($booking, true));

// Get room prices from database
require_once '../config/db.php';

$room_prices = [];
$db_error = null;

try {
    if (isset($pdo) && $pdo instanceof PDO) {
        $stmt = $pdo->query("SELECT name, price_per_night FROM room_types WHERE status = 'active'");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name_key = strtolower(str_replace(['-', ' '], '', $row['name']));
            $room_prices[$name_key] = floatval($row['price_per_night']);
        }
    } else if (isset($conn)) {
        $result = mysqli_query($conn, "SELECT name, price_per_night FROM room_types WHERE status = 'active'");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $name_key = strtolower(str_replace(['-', ' '], '', $row['name']));
                $room_prices[$name_key] = floatval($row['price_per_night']);
            }
        }
    }
} catch (Exception $e) {
    $db_error = $e->getMessage();
    error_log('Database error in get_booking_summary: ' . $db_error);
}

// Use default prices if database fails
if (empty($room_prices)) {
    $room_prices = [
        'nonac' => 2500,
        'ac' => 3500,
        'deluxe' => 5000,
        'suite' => 8000
    ];
}

// Calculate room rate
$room_rate = 0;
$rooms = $booking['rooms'] ?? [];

error_log('Rooms in session: ' . print_r($rooms, true));
error_log('Room prices: ' . print_r($room_prices, true));

foreach ($rooms as $type => $qty) {
    if ($qty > 0 && isset($room_prices[$type])) {
        $room_rate += $room_prices[$type] * $qty;
        error_log("Adding room type $type: qty=$qty, price=" . $room_prices[$type] . ", subtotal=" . ($room_prices[$type] * $qty));
    }
}

error_log('Total room rate: ' . $room_rate);

// Get nights
$nights = $booking['nights'] ?? 1;

// Calculate subtotal (room rate * nights)
$subtotal = $room_rate * $nights;

// Fetch addon prices from database
$addon_prices = [];
try {
    if (isset($pdo) && $pdo instanceof PDO) {
        $stmt = $pdo->query("SELECT name, price, charge_type FROM addons WHERE status = 'active'");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $addon_key = strtolower(str_replace(' ', '_', $row['name']));
            $addon_prices[$addon_key] = [
                'price' => floatval($row['price']),
                'charge_type' => $row['charge_type']
            ];
        }
    } else if (isset($conn)) {
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
    error_log('Addon prices from DB: ' . print_r($addon_prices, true));
} catch (Exception $e) {
    error_log('Error fetching addon prices: ' . $e->getMessage());
}

// Extra persons cost (fetch from database or use default)
$extra_persons = $booking['extra_persons'] ?? 0;
$extra_person_price = $addon_prices['extra_person']['price'] ?? 500;
$extra_person_charge_type = $addon_prices['extra_person']['charge_type'] ?? 'per_night';
$extra_persons_cost = $extra_person_charge_type === 'per_night' 
    ? $extra_persons * $extra_person_price * $nights 
    : $extra_persons * $extra_person_price;

error_log("Extra persons: $extra_persons, price: $extra_person_price, charge_type: $extra_person_charge_type, cost: $extra_persons_cost");

// Breakfast cost (fetch from database or use default)
$extra_breakfast = $booking['extra_breakfast'] ?? 0;
$breakfast_price = $addon_prices['breakfast']['price'] ?? 200;
$breakfast_charge_type = $addon_prices['breakfast']['charge_type'] ?? 'per_night';
$breakfast_cost = $breakfast_charge_type === 'per_night' 
    ? $extra_breakfast * $breakfast_price * $nights 
    : $extra_breakfast * $breakfast_price;

error_log("Extra breakfast: $extra_breakfast, price: $breakfast_price, charge_type: $breakfast_charge_type, cost: $breakfast_cost");

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
        error_log("Campfire: price=$campfire_price, charge_type=$campfire_charge_type, cost=$campfire_cost");
    }
    if (in_array('dhimsa', $booking['addons'])) {
        $dhimsa_price = $addon_prices['dhimsa_dance']['price'] ?? 3000;
        $dhimsa_charge_type = $addon_prices['dhimsa_dance']['charge_type'] ?? 'one_time';
        $dhimsa_cost = $dhimsa_charge_type === 'per_night' 
            ? $dhimsa_price * $nights 
            : $dhimsa_price;
        error_log("Dhimsa: price=$dhimsa_price, charge_type=$dhimsa_charge_type, cost=$dhimsa_cost");
    }
}

// Calculate base total before discounts
$base_total = $subtotal + $extra_persons_cost + $breakfast_cost + $campfire_cost + $dhimsa_cost;

// Smart Offers Discount (DISABLED)
$smart_offers_discount = 0;

// DISABLED: Early booking discount (7+ days advance)
// $checkin = new DateTime($booking['checkin']);
// $today = new DateTime();
// $days_advance = $today->diff($checkin)->days;
// if ($days_advance >= 7) {
//     $smart_offers_discount += $base_total * 0.10; // 10% off
// }

// DISABLED: Multi-night discounts
// if ($nights == 2) {
//     $smart_offers_discount += 500;
// } elseif ($nights >= 5) {
//     $smart_offers_discount += 850;
// }

// Apply smart offers discount
$total_after_smart_offers = $base_total - $smart_offers_discount;

// Coupon discount
$coupon_discount = $booking['coupon_discount'] ?? 0;

// Calculate total after all discounts
$total_after_discounts = $total_after_smart_offers - $coupon_discount;

// Processing fees (3% of total after discounts)
$processing_fees = $total_after_discounts * 0.03;

// Final total
$total = $total_after_discounts + $processing_fees;

// Advance amount (50%)
$advance_amount = $total * 0.50;

// Prepare response
$response = [
    'success' => true,
    'booking' => [
        'checkin' => $booking['checkin'] ?? '',
        'checkout' => $booking['checkout'] ?? '',
        'room_names' => getRoomNames($rooms),
        'rooms' => $rooms,
        'room_rate' => $room_rate,
        'nights' => $nights,
        'subtotal' => $subtotal,
        'name' => $booking['name'] ?? '',
        'email' => $booking['email'] ?? '',
        'mobile' => $booking['mobile'] ?? '',
        'adults' => $booking['adults'] ?? 2,
        'children' => $booking['children'] ?? 0,
        'extra_persons' => $extra_persons,
        'extra_persons_cost' => $extra_persons_cost,
        'extra_breakfast' => $extra_breakfast,
        'breakfast_cost' => $breakfast_cost,
        'campfire_cost' => $campfire_cost,
        'dhimsa_cost' => $dhimsa_cost,
        'smart_offers_discount' => $smart_offers_discount,
        'coupon_discount' => $coupon_discount,
        'coupon_code' => $booking['coupon_code'] ?? '',
        'customer_phone' => $booking['customer_phone'] ?? '',
        'processing_fees' => $processing_fees,
        'total' => $total,
        'advance_amount' => $advance_amount,
        'has_rooms' => $room_rate > 0
    ]
];

// Helper function to get room names
function getRoomNames($rooms) {
    $names = [];
    $room_labels = [
        'nonac' => 'Non-AC Room',
        'ac' => 'AC Room',
        'deluxe' => 'Deluxe Room',
        'suite' => 'Suite'
    ];
    
    foreach ($rooms as $type => $qty) {
        if ($qty > 0 && isset($room_labels[$type])) {
            $names[] = $room_labels[$type] . ' x' . $qty;
        }
    }
    
    return implode(', ', $names);
}

echo json_encode($response);
