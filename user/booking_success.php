<?php
session_start();

// Check if booking was confirmed
if (!isset($_SESSION['confirmed_booking'])) {
    header('Location: home.php');
    exit();
}

$booking = $_SESSION['confirmed_booking'];

// Get room details from session if available
$rooms_display = '';
if (isset($_SESSION['booking']['rooms'])) {
    $room_parts = [];
    foreach ($_SESSION['booking']['rooms'] as $room) {
        $room_parts[] = $room['quantity'] . ' x ' . $room['name'];
    }
    $rooms_display = implode(', ', $room_parts);
}

// Determine payment type display
$payment_type_display = 'Full (100%)';
if (isset($_SESSION['booking']['payment']['payment_type']) && $_SESSION['booking']['payment']['payment_type'] == 'advance') {
    $payment_type_display = 'Advance (50%)';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - Alluri Resorts</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        }
        
        .navbar-simple {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .navbar-simple .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .navbar-simple .logo img {
            height: 60px;
            width: auto;
        }
        
        .success-container {
            max-width: 800px;
            margin: 100px auto 50px;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .success-icon {
            font-size: 80px;
            color: #8b0000;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .success-container h1 {
            color: #8b0000;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
        }
        
        .success-message {
            text-align: center;
            color: #666;
            margin: 20px 0;
            font-size: 1.125rem;
        }
        
        .booking-details {
            background: #f5f5f5;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            text-align: left;
        }
        
        .booking-details h3 {
            color: #8b0000;
            margin-bottom: 15px;
            border-bottom: 2px solid #8b0000;
            padding-bottom: 10px;
            font-size: 1.25rem;
            font-weight: 700;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #333;
        }
        
        .detail-value {
            color: #666;
        }
        
        .payment-id {
            background: #c2dec2;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-family: monospace;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            min-width: 180px;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #8b0000 0%, #a52a2a 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(139, 0, 0, 0.3);
        }
        
        @media (max-width: 768px) {
            .success-container {
                margin: 50px 20px;
                padding: 20px;
            }
            
            .success-container h1 {
                font-size: 1.5rem;
            }
            
            .booking-details {
                padding: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
        }
        
        @media print {
            .action-buttons {
                display: none;
            }
            
            .navbar-simple {
                display: none;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar-simple">
        <div class="container">
            <div class="logo">
                <a href="home.php">
                    <img src="../assets/images/Alluri_Logo.png" alt="Alluri Resorts Logo">
                </a>
            </div>
        </div>
    </nav>

    <div class="success-container">
        <div class="success-icon">✓</div>
        <h1>Booking Confirmed!</h1>
        <p class="success-message">Thank you for your payment. Your booking has been confirmed.</p>

        <div class="booking-details">
            <h3>Booking Details</h3>
            <div class="detail-row">
                <span class="detail-label">Booking ID:</span>
                <span class="detail-value"><?php echo htmlspecialchars($booking['booking_ref']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Guest Name:</span>
                <span class="detail-value"><?php echo htmlspecialchars($booking['guest_name']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value"><?php echo htmlspecialchars($booking['guest_email']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Mobile:</span>
                <span class="detail-value"><?php echo htmlspecialchars($booking['guest_mobile']); ?></span>
            </div>
            <?php if ($rooms_display): ?>
            <div class="detail-row">
                <span class="detail-label">Room Type:</span>
                <span class="detail-value"><?php echo htmlspecialchars($rooms_display); ?></span>
            </div>
            <?php endif; ?>
            <div class="detail-row">
                <span class="detail-label">Check-in Date:</span>
                <span class="detail-value"><?php echo date('d M Y', strtotime($booking['checkin'])); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Check-out Date:</span>
                <span class="detail-value"><?php echo date('d M Y', strtotime($booking['checkout'])); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Number of Nights:</span>
                <span class="detail-value"><?php echo $booking['nights']; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                <span class="detail-value" style="font-weight: bold; color: #8b0000;">₹<?php echo number_format($booking['total_amount'], 2); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Type:</span>
                <span class="detail-value" style="font-weight: bold;"><?php echo $payment_type_display; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount Paid Now:</span>
                <span class="detail-value" style="font-weight: bold; color: #28a745;">₹<?php echo number_format($booking['amount_paid'], 2); ?></span>
            </div>
            <?php if ($booking['balance_due'] > 0): ?>
            <div class="detail-row">
                <span class="detail-label">Remaining Amount (Pay at Hotel):</span>
                <span class="detail-value" style="font-weight: bold; color: #dc3545;">₹<?php echo number_format($booking['balance_due'], 2); ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="payment-id">
            <strong>Payment ID:</strong><br>
            <span><?php echo htmlspecialchars($booking['payment_id']); ?></span>
        </div>

        <div class="action-buttons">
            <a href="booking_invoice.php" class="btn btn-primary">📄 View Invoice</a>
            <a href="booking_receipt.php" class="btn btn-primary">🧾 View Receipt</a>
            <button onclick="window.print()" class="btn btn-primary">🖨️ Print Confirmation</button>
            <a href="home.php" class="btn btn-primary">🏠 Back to Home</a>
        </div>

        <p class="success-message" style="margin-top: 30px;">
            A confirmation email has been sent to your registered email address.<br>
            We look forward to welcoming you at Alluri Resorts!
        </p>
    </div>
</body>
</html>
