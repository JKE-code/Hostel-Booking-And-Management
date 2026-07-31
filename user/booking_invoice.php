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
    error_log('Error fetching extra person price in invoice: ' . $e->getMessage());
}

// Calculate charges
$room_charges = [];
foreach ($rooms as $room) {
    $room_charges[] = [
        'description' => $room['name'] . ' Room',
        'quantity' => $room['quantity'] . ' x ' . $nights . ' nights',
        'rate' => '₹' . number_format($room['price'], 0) . '/night',
        'comment' => $room['name'] . ' Room',
        'amount' => $room['quantity'] * $room['price'] * $nights
    ];
}

// Extra person charges
if (isset($guest['extra_persons']) && $guest['extra_persons'] > 0) {
    $extra_charge = $guest['extra_persons'] * $extra_person_price * $nights;
    $room_charges[] = [
        'description' => 'Extra Person Charges',
        'quantity' => $guest['extra_persons'] . ' x ' . $nights . ' nights',
        'rate' => '₹' . number_format($extra_person_price, 0) . '/person/night',
        'comment' => 'Additional Guests',
        'amount' => $extra_charge
    ];
}

// Addon charges
$addon_charges = [];
foreach ($addons as $addon) {
    $addon_amount = $addon['charge_type'] == 'per_night' ? $addon['price'] * $nights : $addon['price'];
    $quantity = $addon['charge_type'] == 'per_night' ? $nights . ' nights' : '1';
    $addon_charges[] = [
        'description' => strtoupper($addon['name']),
        'quantity' => $quantity,
        'rate' => '₹' . number_format($addon['price'], 0) . '/' . ($addon['charge_type'] == 'per_night' ? 'night' : 'session'),
        'comment' => $addon['name'],
        'amount' => $addon_amount
    ];
}

// Calculate totals
$subtotal = $booking['total_amount'] / 1.05; // Remove GST to get subtotal
$gst = $booking['total_amount'] - $subtotal;
$service_charge = $subtotal * 0.03; // 3% service charge
$grand_total = $booking['total_amount'];

$invoice_date = date('F d, Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Invoice - Alluri Resorts</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .invoice-header {
            background: linear-gradient(135deg, rgba(139, 0, 0, 0.9), rgba(165, 42, 42, 0.9)),
                        url('../assets/images/Reort_Building.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 40px;
            position: relative;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 1;
        }
        
        .header-left {
            flex: 1;
        }
        
        .invoice-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .hotel-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .hotel-address {
            font-size: 0.9rem;
            margin: 5px 0;
            opacity: 0.9;
        }
        
        .hotel-website {
            font-size: 0.9rem;
            margin-top: 10px;
            opacity: 0.9;
        }
        
        .header-right {
            text-align: right;
        }
        
        .invoice-logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
            background: white;
            padding: 10px;
            border-radius: 10px;
        }
        
        .invoice-date {
            font-size: 1rem;
            font-weight: 500;
        }
        
        .invoice-body {
            padding: 40px;
        }
        
        .customer-info {
            margin-bottom: 30px;
        }
        
        .customer-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }
        
        .invoice-id {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 5px;
        }
        
        .accent-color {
            color: #8b0000;
            font-weight: 600;
        }
        
        .payment-id-display {
            font-size: 0.9rem;
            color: #666;
            font-family: monospace;
        }
        
        .reservation-details {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
        }
        
        .details-column {
            flex: 1;
        }
        
        .details-column ul {
            list-style: none;
        }
        
        .details-column li {
            margin: 10px 0;
            font-size: 0.95rem;
        }
        
        .detail-label {
            font-weight: 600;
            color: #333;
        }
        
        .details-separator {
            width: 2px;
            background: #ddd;
        }
        
        .charges-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .charges-table thead {
            background: #8b0000;
            color: white;
        }
        
        .charges-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .charges-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
        }
        
        .charges-table tbody tr:hover {
            background: #f9f9f9;
        }
        
        .amount-col {
            text-align: right;
            font-weight: 600;
        }
        
        .summary-and-notes {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            margin-top: 30px;
        }
        
        .promo-note {
            flex: 1;
            font-size: 0.85rem;
            color: #666;
        }
        
        .promo-note p {
            margin: 10px 0;
        }
        
        .financial-summary {
            min-width: 350px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 0.95rem;
        }
        
        .summary-label {
            color: #666;
        }
        
        .summary-value {
            font-weight: 600;
            color: #333;
        }
        
        .grand-total-separator {
            height: 2px;
            background: #8b0000;
            margin: 15px 0;
        }
        
        .grand-total-row {
            padding: 15px 0;
        }
        
        .grand-total-label {
            font-size: 1.2rem;
            font-weight: 700;
            color: #8b0000;
        }
        
        .grand-total-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #8b0000;
        }
        
        .invoice-footer {
            background: #8b0000;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .checkout-notice {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            text-align: center;
            font-size: 0.95rem;
            color: #856404;
        }
        
        .invoice-actions {
            max-width: 900px;
            margin: 20px auto;
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            border: none;
        }
        
        .btn-primary {
            background: #8b0000;
            color: white;
        }
        
        .btn-primary:hover {
            background: #a52a2a;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: white;
            color: #8b0000;
            border: 2px solid #8b0000;
        }
        
        .btn-secondary:hover {
            background: #8b0000;
            color: white;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .invoice-actions {
                display: none;
            }
            
            .checkout-notice {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .invoice-header {
                padding: 20px;
            }
            
            .header-content {
                flex-direction: column;
            }
            
            .header-right {
                text-align: left;
                margin-top: 20px;
            }
            
            .invoice-body {
                padding: 20px;
            }
            
            .reservation-details {
                flex-direction: column;
                gap: 20px;
            }
            
            .details-separator {
                display: none;
            }
            
            .summary-and-notes {
                flex-direction: column;
            }
            
            .charges-table {
                font-size: 0.8rem;
            }
            
            .charges-table th,
            .charges-table td {
                padding: 10px 5px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header Section -->
        <header class="invoice-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="invoice-title">INVOICE</h1>
                    <p class="hotel-name">ALLURI RESORTS</p>
                    <p class="hotel-address">Araku - Visakhapatnam Road</p>
                    <p class="hotel-address">Opposite to ITI (Govt), Ravvalaguda</p>
                    <p class="hotel-address">Araku Valley, Andhra Pradesh 531149</p>
                    <p class="hotel-website">www.alluriresorts.com</p>
                </div>
                <div class="header-right">
                    <img src="../assets/images/Alluri_Logo.png" alt="Alluri Resorts Logo" class="invoice-logo">
                    <p class="invoice-date">Date: <?php echo $invoice_date; ?></p>
                </div>
            </div>
        </header>

        <!-- Main Invoice Body -->
        <main class="invoice-body">
            <!-- Customer Information -->
            <section class="customer-info">
                <p class="customer-name"><?php echo strtoupper(htmlspecialchars($booking['guest_name'])); ?></p>
                <p class="invoice-id">INVOICE <span class="accent-color">#<?php echo htmlspecialchars($booking['booking_ref']); ?></span></p>
                <p class="payment-id-display">Payment ID: <?php echo htmlspecialchars($booking['payment_id']); ?></p>
            </section>

            <!-- Reservation Details -->
            <section class="reservation-details">
                <div class="details-column left">
                    <ul>
                        <li><span class="detail-label">Guest Name:</span> <?php echo htmlspecialchars($booking['guest_name']); ?></li>
                        <li><span class="detail-label">Email:</span> <?php echo htmlspecialchars($booking['guest_email']); ?></li>
                        <li><span class="detail-label">Arrival Date:</span> <?php echo date('F d, Y', strtotime($booking['checkin'])); ?></li>
                        <li><span class="detail-label">Departure Date:</span> <?php echo date('F d, Y', strtotime($booking['checkout'])); ?></li>
                    </ul>
                </div>
                <div class="details-separator"></div>
                <div class="details-column right">
                    <ul>
                        <li><span class="detail-label">Mobile:</span> <?php echo htmlspecialchars($booking['guest_mobile']); ?></li>
                        <li><span class="detail-label">Number of Nights:</span> <?php echo $nights; ?></li>
                        <li><span class="detail-label">Adults:</span> <?php echo isset($guest['adults']) ? $guest['adults'] : '-'; ?></li>
                        <li><span class="detail-label">Children:</span> <?php echo isset($guest['children']) ? $guest['children'] : '-'; ?></li>
                    </ul>
                </div>
            </section>

            <!-- Charges Table -->
            <section class="charges-section">
                <table class="charges-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Rate</th>
                            <th>Comment</th>
                            <th class="amount-col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($room_charges as $charge): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($charge['description']); ?></td>
                            <td><?php echo htmlspecialchars($charge['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($charge['rate']); ?></td>
                            <td><?php echo htmlspecialchars($charge['comment']); ?></td>
                            <td class="amount-col">₹<?php echo number_format($charge['amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php foreach ($addon_charges as $charge): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($charge['description']); ?></td>
                            <td><?php echo htmlspecialchars($charge['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($charge['rate']); ?></td>
                            <td><?php echo htmlspecialchars($charge['comment']); ?></td>
                            <td class="amount-col">₹<?php echo number_format($charge['amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Summary and Notes -->
                <div class="summary-and-notes">
                    <div class="promo-note">
                        <p>*Your booking details have been sent to your registered email address.</p>
                        <p>*For any queries, please contact us at +91 93929 52669</p>
                        <p>*Check-in time: 2:00 PM | Check-out time: 11:00 AM</p>
                    </div>
                    <div class="financial-summary">
                        <div class="summary-row">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value">₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">GST (5%)</span>
                            <span class="summary-value">₹<?php echo number_format($gst, 2); ?></span>
                        </div>
                        <div class="grand-total-separator"></div>
                        <div class="summary-row grand-total-row">
                            <span class="summary-label grand-total-label">GRAND TOTAL</span>
                            <span class="summary-value grand-total-value">₹<?php echo number_format($grand_total, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Amount Paid</span>
                            <span class="summary-value" style="color: #28a745;">₹<?php echo number_format($booking['amount_paid'], 2); ?></span>
                        </div>
                        <?php if ($booking['balance_due'] > 0): ?>
                        <div class="summary-row">
                            <span class="summary-label">Balance Due</span>
                            <span class="summary-value" style="color: #dc3545;">₹<?php echo number_format($booking['balance_due'], 2); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="invoice-footer">
            <p>WE HOPE YOU HAD A GREAT STAY!</p>
        </footer>

        <!-- Checkout Notice -->
        <div class="checkout-notice">
            <p>📋 This is your booking invoice. Full invoice will be provided during check-out.</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="invoice-actions">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print Invoice</button>
        <a href="booking_success.php" class="btn btn-secondary">← Back to Confirmation</a>
        <a href="home.php" class="btn btn-secondary">🏠 Back to Home</a>
    </div>
</body>
</html>
