<?php
session_start();

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

require_once __DIR__ . '/../config/db.php';

$page_title = 'Booking Successful';
$page_heading = 'Booking Confirmed';

$booking_ref = $_GET['ref'] ?? '';

if (empty($booking_ref)) {
    header('Location: offline_booking.php');
    exit;
}

// Fetch booking details
try {
    $stmt = $pdo->prepare("
        SELECT b.*, g.full_name, g.mobile, g.email 
        FROM bookings b 
        LEFT JOIN guests g ON b.id = g.booking_id 
        WHERE b.booking_ref = ?
    ");
    $stmt->execute([$booking_ref]);
    $booking = $stmt->fetch();
    
    if (!$booking) {
        header('Location: offline_booking.php');
        exit;
    }
} catch (Exception $e) {
    die("Error fetching booking details.");
}
?>
<?php include 'includes/offline_header.php'; ?>

<main class="dashboard-main">
    <div class="offline-booking-container">
        <div class="booking-card" style="text-align: center; max-width: 600px; margin: 0 auto;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">✅</div>
            <h2 style="color: #10b981; margin-bottom: 1rem;">Booking Confirmed!</h2>
            
            <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 1.5rem; border-radius: 8px; margin: 2rem 0;">
                <p style="font-size: 0.875rem; color: #92400e; margin-bottom: 0.5rem;">Booking Reference</p>
                <p style="font-size: 2rem; font-weight: 700; color: #8b0000; margin: 0;"><?php echo $booking_ref; ?></p>
            </div>
            
            <div style="text-align: left; margin: 2rem 0;">
                <h3 style="color: #1f2937; margin-bottom: 1rem;">Booking Details</h3>
                <p><strong>Guest Name:</strong> <?php echo htmlspecialchars($booking['full_name']); ?></p>
                <p><strong>Mobile:</strong> <?php echo htmlspecialchars($booking['mobile']); ?></p>
                <p><strong>Check-in:</strong> <?php echo date('d M Y', strtotime($booking['checkin'])); ?></p>
                <p><strong>Check-out:</strong> <?php echo date('d M Y', strtotime($booking['checkout'])); ?></p>
                <p><strong>Nights:</strong> <?php echo $booking['nights']; ?></p>
                <p><strong>Total Amount:</strong> ₹<?php echo number_format($booking['total_amount'], 0); ?></p>
                <p><strong>Amount Paid:</strong> ₹<?php echo number_format($booking['amount_paid'], 0); ?></p>
                <?php if ($booking['balance_due'] > 0): ?>
                <p style="color: #f59e0b;"><strong>Balance Due:</strong> ₹<?php echo number_format($booking['balance_due'], 0); ?></p>
                <?php endif; ?>
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
                <button onclick="window.location.href='offline_booking.php?new=1'" class="btn-offline btn-offline-primary">
                    New Booking
                </button>
                <button onclick="if(confirm('Exit Walk-in Mode? This will log you out.')) window.location.href='offline_exit.php'" class="btn-offline btn-offline-secondary">
                    Exit Walk-in Mode
                </button>
            </div>
        </div>
    </div>
</main>

</body>
</html>
