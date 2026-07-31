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

// Get total guests
$guests_query = "SELECT 
    SUM(COALESCE(adults, 0)) as total_adults,
    SUM(COALESCE(children, 0)) as total_children
    FROM guests WHERE booking_id = ?";
$guests_stmt = mysqli_prepare($conn, $guests_query);
mysqli_stmt_bind_param($guests_stmt, 'i', $booking_id);
mysqli_stmt_execute($guests_stmt);
$guests_result = mysqli_stmt_get_result($guests_stmt);
$guests_data = mysqli_fetch_assoc($guests_result);

// Calculate charges
$room_charges = [];
foreach ($rooms as $room) {
    $room_charges[] = [
        'description' => $room['name'] . ' Room',
        'quantity' => $room['rooms_booked'] . ' x ' . $nights . ' nights',
        'rate' => '₹' . number_format($room['price_per_night'], 0) . '/night',
        'comment' => $room['name'] . ' Room',
        'amount' => $room['rooms_booked'] * $room['price_per_night'] * $nights
    ];
}

// Addon charges
$addon_charges = [];
foreach ($addons as $addon) {
    $quantity = $addon['charge_type'] == 'per_night' ? $addon['quantity'] . ' x ' . $nights . ' nights' : $addon['quantity'];
    $addon_charges[] = [
        'description' => strtoupper($addon['name']),
        'quantity' => $quantity,
        'rate' => '₹' . number_format($addon['unit_price'], 0) . '/' . ($addon['charge_type'] == 'per_night' ? 'night' : 'session'),
        'comment' => $addon['name'],
        'amount' => $addon['total_price']
    ];
}

// Calculate totals
$subtotal = 0;
foreach ($room_charges as $charge) {
    $subtotal += $charge['amount'];
}
foreach ($addon_charges as $charge) {
    $subtotal += $charge['amount'];
}

$grand_total = $booking['total_amount'];
$invoice_date = date('F d, Y');

mysqli_stmt_close($stmt);
mysqli_stmt_close($rooms_stmt);
mysqli_stmt_close($addons_stmt);
mysqli_stmt_close($guests_stmt);
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
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .invoice-actions {
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
                <p class="customer-name"><?php echo strtoupper(htmlspecialchars($booking['full_name'])); ?></p>
                <p class="invoice-id">INVOICE <span class="accent-color">#<?php echo htmlspecialchars($booking['booking_ref']); ?></span></p>
            </section>

            <!-- Reservation Details -->
            <section class="reservation-details">
                <div class="details-column left">
                    <ul>
                        <li><span class="detail-label">Guest Name:</span> <?php echo htmlspecialchars($booking['full_name']); ?></li>
                        <li><span class="detail-label">Email:</span> <?php echo htmlspecialchars($booking['email']); ?></li>
                        <li><span class="detail-label">Arrival Date:</span> <?php echo date('F d, Y', strtotime($booking['checkin'])); ?></li>
                        <li><span class="detail-label">Departure Date:</span> <?php echo date('F d, Y', strtotime($booking['checkout'])); ?></li>
                    </ul>
                </div>
                <div class="details-separator"></div>
                <div class="details-column right">
                    <ul>
                        <li><span class="detail-label">Mobile:</span> <?php echo htmlspecialchars($booking['mobile']); ?></li>
                        <li><span class="detail-label">Number of Nights:</span> <?php echo $nights; ?></li>
                        <li><span class="detail-label">Adults:</span> <?php echo $guests_data['total_adults']; ?></li>
                        <li><span class="detail-label">Children:</span> <?php echo $guests_data['total_children']; ?></li>
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
                        <?php if ($booking['processing_fee'] > 0): ?>
                        <div class="summary-row">
                            <span class="summary-label">Processing Fee</span>
                            <span class="summary-value">₹<?php echo number_format($booking['processing_fee'], 2); ?></span>
                        </div>
                        <?php endif; ?>
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
    </div>

    <!-- Action Buttons -->
    <div class="invoice-actions">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print Invoice</button>
    </div>
</body>
</html>
