<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/razorpay.php';

// Check if payment info exists
if (!isset($_SESSION['booking']['payment'])) {
    header('Location: booking_step1_rooms.php');
    exit();
}

$payment = $_SESSION['booking']['payment'];
$booking = $_SESSION['booking'];

// Get Razorpay Key ID (safe to expose to frontend)
$razorpay_key_id = getRazorpayKeyId();

// Convert amount to paise (Razorpay requires amount in smallest currency unit)
$amount_in_paise = round($payment['amount_to_pay'] * 100);
?>
<?php include 'includes/header.php'; ?>

    <!-- Payment Section -->
    <section class="booking-section">
        <div class="container">
            <div class="payment-container">
                <div class="payment-card">
                    <div class="payment-header">
                        <h2>🔒 Secure Payment</h2>
                        <p>Complete your booking by making a secure payment</p>
                    </div>

                    <div class="payment-summary">
                        <div class="summary-row">
                            <span>Booking Reference:</span>
                            <strong><?php echo $payment['booking_ref']; ?></strong>
                        </div>
                        <div class="summary-row">
                            <span>Guest Name:</span>
                            <strong><?php echo htmlspecialchars($booking['guest']['full_name']); ?></strong>
                        </div>
                        <div class="summary-row">
                            <span>Check-in:</span>
                            <strong><?php echo date('d M Y', strtotime($booking['checkin'])); ?></strong>
                        </div>
                        <div class="summary-row">
                            <span>Check-out:</span>
                            <strong><?php echo date('d M Y', strtotime($booking['checkout'])); ?></strong>
                        </div>
                        <div class="summary-row">
                            <span>Nights:</span>
                            <strong><?php echo $booking['nights']; ?></strong>
                        </div>
                        
                        <div class="payment-divider"></div>
                        
                        <div class="summary-row">
                            <span>Total Amount:</span>
                            <strong>₹<?php echo number_format($payment['grand_total'], 2); ?></strong>
                        </div>
                        <div class="summary-row highlight">
                            <span>Amount to Pay Now:</span>
                            <strong>₹<?php echo number_format($payment['amount_to_pay'], 2); ?></strong>
                        </div>
                        <?php if ($payment['balance_due'] > 0): ?>
                            <div class="summary-row">
                                <span>Balance Due at Resort:</span>
                                <strong>₹<?php echo number_format($payment['balance_due'], 2); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>

                    <button id="rzp-button" class="btn-pay-now">
                        Pay ₹<?php echo number_format($payment['amount_to_pay'], 2); ?> Now
                    </button>

                    <div class="payment-security">
                        <p>🔒 Your payment is secured by Razorpay</p>
                        <p style="font-size: 0.875rem; color: #64748b;">We accept UPI, Cards, Net Banking, and Wallets</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Razorpay Checkout Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('rzp-button').onclick = function(e) {
            e.preventDefault();
            
            var options = {
                "key": "<?php echo $razorpay_key_id; ?>", // Only public key_id is exposed
                "amount": "<?php echo $amount_in_paise; ?>", // Amount in paise
                "currency": "INR",
                "name": "Alluri Resorts",
                "description": "Booking: <?php echo $payment['booking_ref']; ?>",
                "image": "../assets/images/Alluri_Logo.png",
                "handler": function (response) {
                    // Payment successful - send to confirmation page
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'booking_confirm.php';
                    
                    var paymentId = document.createElement('input');
                    paymentId.type = 'hidden';
                    paymentId.name = 'razorpay_payment_id';
                    paymentId.value = response.razorpay_payment_id;
                    form.appendChild(paymentId);
                    
                    // Add order_id if available
                    if (response.razorpay_order_id) {
                        var orderId = document.createElement('input');
                        orderId.type = 'hidden';
                        orderId.name = 'razorpay_order_id';
                        orderId.value = response.razorpay_order_id;
                        form.appendChild(orderId);
                    }
                    
                    // Add signature if available
                    if (response.razorpay_signature) {
                        var signature = document.createElement('input');
                        signature.type = 'hidden';
                        signature.name = 'razorpay_signature';
                        signature.value = response.razorpay_signature;
                        form.appendChild(signature);
                    }
                    
                    document.body.appendChild(form);
                    form.submit();
                },
                "prefill": {
                    "name": "<?php echo htmlspecialchars($booking['guest']['full_name']); ?>",
                    "email": "<?php echo htmlspecialchars($booking['guest']['email']); ?>",
                    "contact": "<?php echo htmlspecialchars($booking['guest']['mobile']); ?>"
                },
                "notes": {
                    "booking_ref": "<?php echo $payment['booking_ref']; ?>",
                    "checkin": "<?php echo $booking['checkin']; ?>",
                    "checkout": "<?php echo $booking['checkout']; ?>"
                },
                "theme": {
                    "color": "#8b0000"
                },
                "modal": {
                    "ondismiss": function() {
                        alert('Payment cancelled. You can try again or contact us for assistance.');
                    }
                }
            };
            
            var rzp = new Razorpay(options);
            rzp.on('payment.failed', function (response){
                alert('Payment failed: ' + response.error.description);
                console.error('Payment Error:', response.error);
            });
            rzp.open();
        };
    </script>

<?php include 'includes/footer.php'; ?>
