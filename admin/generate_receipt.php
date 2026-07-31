<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../config/db.php';

// Check if booking ID is provided
if (!isset($_GET['id'])) {
    die('Booking ID not provided');
}

$booking_id = intval($_GET['id']);

// Fetch complete booking details
$query = "SELECT b.*, g.full_name, g.email, g.mobile
    FROM bookings b
    LEFT JOIN guests g ON b.id = g.booking_id
    WHERE b.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    die('Booking not found');
}

// Calculate nights
$checkin = new DateTime($booking['checkin']);
$checkout = new DateTime($booking['checkout']);
$nights = $checkin->diff($checkout)->days;

// Fetch room details
$rooms_query = "SELECT rt.name, br.rooms_booked, br.price_per_night
    FROM booking_rooms br
    JOIN room_types rt ON br.room_type_id = rt.id
    WHERE br.booking_id = ?";
$rooms_stmt = mysqli_prepare($conn, $rooms_query);
mysqli_stmt_bind_param($rooms_stmt, 'i', $booking_id);
mysqli_stmt_execute($rooms_stmt);
$rooms_result = mysqli_stmt_get_result($rooms_stmt);
$rooms = [];
while ($room = mysqli_fetch_assoc($rooms_result)) {
    $rooms[] = $room;
}

// Fetch add-ons
$addons_query = "SELECT a.name, a.price as unit_price, a.charge_type, ba.quantity, ba.price as total_price
    FROM booking_addons ba
    JOIN addons a ON ba.addon_id = a.id
    WHERE ba.booking_id = ?";
$addons_stmt = mysqli_prepare($conn, $addons_query);
mysqli_stmt_bind_param($addons_stmt, 'i', $booking_id);
mysqli_stmt_execute($addons_stmt);
$addons_result = mysqli_stmt_get_result($addons_stmt);
$addons = [];
while ($addon = mysqli_fetch_assoc($addons_result)) {
    $addons[] = $addon;
}

// Build receipt items
$receipt_items = [];

// Room charges
foreach ($rooms as $room) {
    $unit_cost = $room['price_per_night'];
    $quantity = $room['rooms_booked'] . ' room(s) × ' . $nights . ' night(s)';
    $amount = $room['rooms_booked'] * $room['price_per_night'] * $nights;
    $receipt_items[] = [
        'description' => $room['name'] . ' Room',
        'unit_cost' => $unit_cost,
        'quantity' => $quantity,
        'amount' => $amount
    ];
}

// Addon charges
foreach ($addons as $addon) {
    $unit_cost = $addon['unit_price'];
    if ($addon['charge_type'] == 'per_night') {
        $quantity = $addon['quantity'] . ' × ' . $nights . ' night(s)';
    } else {
        $quantity = $addon['quantity'];
    }
    $receipt_items[] = [
        'description' => $addon['name'],
        'unit_cost' => $unit_cost,
        'quantity' => $quantity,
        'amount' => $addon['total_price']
    ];
}

// Calculate totals
$subtotal = 0;
foreach ($receipt_items as $item) {
    $subtotal += $item['amount'];
}

$receipt_date = date('F d, Y');
$receipt_number = 'RCP-' . $booking['booking_ref'];

// Determine payment type
$payment_type_display = $booking['payment_type'] === 'full' ? 'Full Payment (100%)' : 'Advance Payment (50%)';

mysqli_stmt_close($stmt);
mysqli_stmt_close($rooms_stmt);
mysqli_stmt_close($addons_stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - Alluri Resorts</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .receipt-logo {
            width: 100px;
            height: auto;
            margin-bottom: 15px;
        }
        
        .receipt-title {
            color: #5b0202;
            font-size: 32px;
            margin: 10px 0;
            font-weight: bold;
        }
        
        .receipt-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .details-left, .details-right {
            flex: 1;
        }
        
        .details-right {
            text-align: right;
        }
        
        .details-left p, .details-right p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .details-left strong, .details-right strong {
            font-weight: bold;
        }
        
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .receipt-table thead {
            background: #f5f5f5;
            border-bottom: 2px solid #333;
        }
        
        .receipt-table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        .receipt-table th:nth-child(2),
        .receipt-table th:nth-child(3),
        .receipt-table th:nth-child(4) {
            text-align: right;
        }
        
        .receipt-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .receipt-table td:nth-child(2),
        .receipt-table td:nth-child(4) {
            text-align: right;
        }
        
        .receipt-table td:nth-child(3) {
            text-align: center;
        }
        
        .receipt-summary {
            text-align: right;
            margin-bottom: 30px;
        }
        
        .receipt-summary p {
            margin: 5px 0;
        }
        
        .summary-divider {
            border-top: 3px double #333;
            margin: 10px 0 10px auto;
            width: 250px;
        }
        
        .amount-paid {
            margin: 10px 0;
            font-size: 18px;
            font-weight: bold;
            color: #5b0202;
        }
        
        .payment-status {
            margin: 5px 0;
            color: #28a745;
            font-weight: bold;
        }
        
        .advance-note {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        
        .remaining-amount {
            margin: 10px 0;
            color: #dc3545;
            font-weight: bold;
        }
        
        .additional-info {
            border-top: 2px solid #333;
            padding-top: 20px;
            margin-top: 30px;
        }
        
        .additional-info h3 {
            color: #5b0202;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .additional-info p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .receipt-footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .receipt-footer p {
            font-size: 11px;
            color: #666;
            margin: 5px 0;
        }
        
        .receipt-actions {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #8b0000;
            color: white;
        }
        
        .btn-primary:hover {
            background: #6b0000;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .receipt-container {
                box-shadow: none;
                padding: 15px 25px;
            }
            
            .receipt-actions {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <img src="../assets/images/Alluri_Logo.png" alt="Alluri Resorts Logo" class="receipt-logo">
            <h1 class="receipt-title">RECEIPT</h1>
        </div>

        <!-- Two Column Details -->
        <div class="receipt-details">
            <!-- Left Column -->
            <div class="details-left">
                <p><strong>RECEIPT #:</strong> <?php echo htmlspecialchars($receipt_number); ?></p>
                <p><strong>RECEIPT DATE:</strong> <?php echo $receipt_date; ?></p>
                <p><strong>PAID BY:</strong> <?php echo htmlspecialchars($booking['full_name']); ?></p>
                <p><strong>Contact:</strong> <?php echo htmlspecialchars($booking['mobile']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></p>
            </div>

            <!-- Right Column -->
            <div class="details-right">
                <p style="font-weight: bold; font-size: 16px;">ALLURI RESORTS</p>
                <p>Araku - Visakhapatnam Road</p>
                <p>Opposite to ITI (Govt), Ravvalaguda</p>
                <p>Araku Valley, Andhra Pradesh 531149</p>
                <p>alluriresorts.host@gmail.com</p>
                <p>Phone: 08936-249888</p>
                <p>Mobile: +91 93929 52669</p>
            </div>
        </div>

        <!-- Itemized Table -->
        <table class="receipt-table">
            <thead>
                <tr>
                    <th>DESCRIPTION</th>
                    <th>UNIT COST</th>
                    <th>QUANTITY</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($receipt_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td>₹<?php echo number_format($item['unit_cost'], 2); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td>₹<?php echo number_format($item['amount'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Financial Summary -->
        <div class="receipt-summary">
            <p>Subtotal: ₹<?php echo number_format($subtotal, 2); ?></p>
            <?php if ($booking['processing_fee'] > 0): ?>
            <p>Processing Fee: ₹<?php echo number_format($booking['processing_fee'], 2); ?></p>
            <?php endif; ?>
            <div class="summary-divider"></div>
            <p style="font-weight: bold; font-size: 16px;">Total Amount: ₹<?php echo number_format($booking['total_amount'], 2); ?></p>
            <p class="amount-paid">Amount Paid: ₹<?php echo number_format($booking['amount_paid'], 2); ?></p>
            <p class="payment-status">✓ PAYMENT SUCCESSFUL</p>
            <?php if ($booking['balance_due'] > 0): ?>
                <p class="advance-note">(<?php echo $payment_type_display; ?>)</p>
                <p class="remaining-amount">Remaining Balance: ₹<?php echo number_format($booking['balance_due'], 2); ?></p>
            <?php endif; ?>
        </div>

        <!-- Additional Information -->
        <div class="additional-info">
            <h3>ADDITIONAL INFORMATION</h3>
            <p>Booking Reference: <?php echo htmlspecialchars($booking['booking_ref']); ?></p>
            <p>Check-in Date: <?php echo date('F d, Y', strtotime($booking['checkin'])); ?></p>
            <p>Check-out Date: <?php echo date('F d, Y', strtotime($booking['checkout'])); ?></p>
            <p>Number of Nights: <?php echo $nights; ?></p>
            <p>Check-in Time: 2:00 PM (Afternoon)</p>
            <p>Check-out Time: 11:00 AM (Morning)</p>
            <p>Payment Type: <?php echo $payment_type_display; ?></p>
            <?php if ($booking['balance_due'] > 0): ?>
            <p style="color: #dc3545; font-weight: bold;">Remaining Amount (Pay at Hotel): ₹<?php echo number_format($booking['balance_due'], 2); ?></p>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p>This is a computer-generated receipt and does not require a signature.</p>
            <p>Thank you for choosing Alluri Resorts!</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="receipt-actions">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print Receipt</button>
    </div>
</body>
</html>
