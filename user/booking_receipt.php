<?php
session_start();

// Check if booking was confirmed
if (!isset($_SESSION['confirmed_booking'])) {
    header('Location: home.php');
    exit();
}

$booking = $_SESSION['confirmed_booking'];

// Get additional booking details from session
$rooms = isset($_SESSION['booking']['rooms']) ? $_SESSION['booking']['rooms'] : [];
$addons = isset($_SESSION['booking']['addons']) ? $_SESSION['booking']['addons'] : [];
$guest = isset($_SESSION['booking']['guest']) ? $_SESSION['booking']['guest'] : [];
$nights = $booking['nights'];

// Fetch addon prices from database for extra person charges
require_once '../config/db.php';
$extra_person_price = 500; // Default
try {
    if (isset($pdo)) {
        $stmt = $pdo->query("SELECT price FROM addons WHERE name = 'Extra Person' AND status = 'active'");
        $row = $stmt->fetch();
        if ($row) {
            $extra_person_price = floatval($row['price']);
        }
    } else if (isset($conn)) {
        $result = mysqli_query($conn, "SELECT price FROM addons WHERE name = 'Extra Person' AND status = 'active'");
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $extra_person_price = floatval($row['price']);
        }
    }
} catch (Exception $e) {
    error_log('Error fetching extra person price in receipt: ' . $e->getMessage());
}

// Build receipt items
$receipt_items = [];

// Room charges
foreach ($rooms as $room) {
    $unit_cost = $room['price'];
    $quantity = $room['quantity'] . ' room(s) × ' . $nights . ' night(s)';
    $amount = $room['quantity'] * $room['price'] * $nights;
    $receipt_items[] = [
        'description' => $room['name'] . ' Room',
        'unit_cost' => $unit_cost,
        'quantity' => $quantity,
        'amount' => $amount
    ];
}

// Extra person charges
if (isset($guest['extra_persons']) && $guest['extra_persons'] > 0) {
    $unit_cost = $extra_person_price;
    $quantity = $guest['extra_persons'] . ' person(s) × ' . $nights . ' night(s)';
    $amount = $guest['extra_persons'] * $extra_person_price * $nights;
    $receipt_items[] = [
        'description' => 'Extra Person Charges',
        'unit_cost' => $unit_cost,
        'quantity' => $quantity,
        'amount' => $amount
    ];
}

// Addon charges
foreach ($addons as $addon) {
    $unit_cost = $addon['price'];
    if ($addon['charge_type'] == 'per_night') {
        $quantity = $nights . ' night(s)';
        $amount = $addon['price'] * $nights;
    } else {
        $quantity = '1';
        $amount = $addon['price'];
    }
    $receipt_items[] = [
        'description' => $addon['name'],
        'unit_cost' => $unit_cost,
        'quantity' => $quantity,
        'amount' => $amount
    ];
}

// Calculate totals
$subtotal = 0;
foreach ($receipt_items as $item) {
    $subtotal += $item['amount'];
}
$gst = $subtotal * 0.05; // 5% GST
$total = $subtotal + $gst;

$receipt_date = date('F d, Y');
$receipt_number = 'RCP-' . $booking['booking_ref'];

// Determine payment type
$payment_type_display = 'Full Payment (100%)';
if (isset($_SESSION['booking']['payment']['payment_type']) && $_SESSION['booking']['payment']['payment_type'] == 'advance') {
    $payment_type_display = 'Advance Payment (50%)';
}
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
        
        .checkout-notice {
            background: #ffc107;
            padding: 1rem 2rem;
            text-align: center;
            border-top: 3px solid #ff9800;
        }
        
        .checkout-notice p {
            color: #000;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
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
        
        .btn-secondary {
            background: #666;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #555;
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
            
            .checkout-notice {
                page-break-inside: avoid;
            }
        }
        
        @media (max-width: 768px) {
            .receipt-container {
                padding: 20px;
            }
            
            .receipt-details {
                flex-direction: column;
            }
            
            .details-right {
                text-align: left;
                margin-top: 20px;
            }
            
            .receipt-table {
                font-size: 12px;
            }
            
            .receipt-table th,
            .receipt-table td {
                padding: 5px;
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
                <p><strong>PAID BY:</strong> <?php echo htmlspecialchars($booking['guest_name']); ?></p>
                <p><strong>Contact:</strong> <?php echo htmlspecialchars($booking['guest_mobile']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['guest_email']); ?></p>
                <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($booking['payment_id']); ?></p>
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
            <p>GST (5%): ₹<?php echo number_format($gst, 2); ?></p>
            <div class="summary-divider"></div>
            <p style="font-weight: bold; font-size: 16px;">Total Amount: ₹<?php echo number_format($total, 2); ?></p>
            <p class="amount-paid">Amount Paid: ₹<?php echo number_format($booking['amount_paid'], 2); ?></p>
            <p class="payment-status">✓ PAYMENT SUCCESSFUL</p>
            <?php if ($booking['balance_due'] > 0): ?>
                <p class="advance-note">(50% Advance Payment Received)</p>
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

        <!-- Checkout Notice -->
        <div class="checkout-notice">
            <p>📋 This is your payment receipt. Full receipt will be provided during check-out.</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="receipt-actions">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print Receipt</button>
        <a href="booking_success.php" class="btn btn-secondary">← Back to Confirmation</a>
        <a href="home.php" class="btn btn-secondary">🏠 Back to Home</a>
    </div>
</body>
</html>
