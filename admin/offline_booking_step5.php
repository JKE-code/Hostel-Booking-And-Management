<?php
session_start();

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/razorpay.php';

if (!isset($_SESSION['offline_booking']) || $_SESSION['offline_booking']['step'] < 5) {
    // Allow access if trying to go back
    if (!isset($_GET['back'])) {
        header('Location: offline_booking.php');
        exit;
    }
}

// Allow going back to step 5 from later steps
if (isset($_GET['back']) && $_GET['back'] == 5) {
    $_SESSION['offline_booking']['step'] = 5;
}

$page_title = 'Offline Booking - Step 5';
$page_heading = 'Walk-in / On-Spot Booking';

$booking_data = $_SESSION['offline_booking'];

// Get Razorpay Key ID (safe to expose to frontend)
$razorpay_key_id = getRazorpayKeyId();

// Calculate total
$total_amount = 0;
$room_details = [];
$addon_details = [];

// Get room details and calculate
foreach ($booking_data['rooms'] as $room_id => $quantity) {
    $stmt = $pdo->prepare("SELECT * FROM room_types WHERE id = ?");
    $stmt->execute([$room_id]);
    $room = $stmt->fetch();
    $room_cost = $room['price_per_night'] * $quantity * $booking_data['dates']['nights'];
    $total_amount += $room_cost;
    $room_details[] = [
        'name' => $room['name'],
        'quantity' => $quantity,
        'price' => $room['price_per_night'],
        'nights' => $booking_data['dates']['nights'],
        'total' => $room_cost
    ];
}

// Get addon details and calculate
foreach ($booking_data['addons'] as $addon_id => $quantity) {
    $stmt = $pdo->prepare("SELECT * FROM addons WHERE id = ?");
    $stmt->execute([$addon_id]);
    $addon = $stmt->fetch();
    
    if ($addon['charge_type'] === 'per_night') {
        $addon_cost = $addon['price'] * $quantity * $booking_data['dates']['nights'];
    } else {
        $addon_cost = $addon['price'] * $quantity;
    }
    
    $total_amount += $addon_cost;
    $addon_details[] = [
        'name' => $addon['name'],
        'quantity' => $quantity,
        'price' => $addon['price'],
        'charge_type' => $addon['charge_type'],
        'total' => $addon_cost
    ];
}

$subtotal = $total_amount;
$processing_fee = $total_amount * 0.03;
$total_amount = $subtotal + $processing_fee;

$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['error_message']);
?>
<?php include 'includes/offline_header.php'; ?>

<main class="dashboard-main">
    <div class="offline-booking-container">
        <div class="offline-badge">🏨 Walk-in Booking Mode</div>
        
        <div class="booking-progress">
            <div class="progress-step completed"><div class="progress-circle">✓</div><div class="progress-label">Select Dates</div></div>
            <div class="progress-step completed"><div class="progress-circle">✓</div><div class="progress-label">Select Rooms</div></div>
            <div class="progress-step completed"><div class="progress-circle">✓</div><div class="progress-label">Guest Info</div></div>
            <div class="progress-step completed"><div class="progress-circle">✓</div><div class="progress-label">Add-ons</div></div>
            <div class="progress-step active"><div class="progress-circle">5</div><div class="progress-label">Payment</div></div>
        </div>

        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem;">
            <!-- Booking Summary -->
            <div class="booking-card">
                <h2>Booking Summary</h2>
                
                <div style="margin: 1.5rem 0;">
                    <h3 style="color: #1f2937; margin-bottom: 1rem;">Guest Details</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($booking_data['guests']['name']); ?></p>
                    <p><strong>Mobile:</strong> <?php echo htmlspecialchars($booking_data['guests']['mobile']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($booking_data['guests']['email'] ?: 'N/A'); ?></p>
                    <p><strong>Guests:</strong> <?php echo $booking_data['guests']['adults']; ?> Adults, <?php echo $booking_data['guests']['children']; ?> Children</p>
                </div>
                
                <div style="margin: 1.5rem 0;">
                    <h3 style="color: #1f2937; margin-bottom: 1rem;">Stay Details</h3>
                    <p><strong>Check-in:</strong> <?php echo date('d M Y', strtotime($booking_data['dates']['checkin'])); ?></p>
                    <p><strong>Check-out:</strong> <?php echo date('d M Y', strtotime($booking_data['dates']['checkout'])); ?></p>
                    <p><strong>Nights:</strong> <?php echo $booking_data['dates']['nights']; ?></p>
                </div>
                
                <div style="margin: 1.5rem 0;">
                    <h3 style="color: #1f2937; margin-bottom: 1rem;">Rooms</h3>
                    <?php foreach ($room_details as $room): ?>
                        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb;">
                            <span><?php echo $room['name']; ?> × <?php echo $room['quantity']; ?> (<?php echo $room['nights']; ?> nights)</span>
                            <span>₹<?php echo number_format($room['total'], 0); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (!empty($addon_details)): ?>
                <div style="margin: 1.5rem 0;">
                    <h3 style="color: #1f2937; margin-bottom: 1rem;">Add-ons</h3>
                    <?php foreach ($addon_details as $addon): ?>
                        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb;">
                            <span><?php echo $addon['name']; ?> × <?php echo $addon['quantity']; ?></span>
                            <span>₹<?php echo number_format($addon['total'], 0); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <div style="margin-top: 2rem; padding-top: 1rem; border-top: 2px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Subtotal:</span>
                        <span>₹<?php echo number_format($subtotal, 0); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Processing & Platform Fees (3%):</span>
                        <span>₹<?php echo number_format($processing_fee, 0); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 1.5rem; font-weight: 700; color: #8b0000;">
                        <span>Total:</span>
                        <span>₹<?php echo number_format($total_amount, 0); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Payment Form -->
            <div class="booking-card">
                <h2>Payment Details</h2>
                
                <form method="POST" action="offline_booking_process.php" id="paymentForm">
                    <input type="hidden" name="action" value="complete_booking">
                    
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Payment Method</label>
                        <select name="payment_method" id="payment_method" required 
                                style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px;">
                            <option value="cash">💵 Cash</option>
                            <option value="online">💳 Online/Card</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Payment Type</label>
                        <select name="payment_type" id="payment_type" required 
                                style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px;"
                                onchange="updateAmountField()">
                            <option value="full">Full Payment</option>
                            <option value="advance">Advance Payment</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Amount Paid</label>
                        <input type="number" name="amount_paid" id="amount_paid" required 
                               value="<?php echo $total_amount; ?>" min="0" step="1"
                               style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <small style="color: #6b7280;">Total Amount: ₹<?php echo number_format($total_amount, 0); ?></small>
                    </div>
                    
                    <div id="balance_display" style="padding: 1rem; background: #fef3c7; border-radius: 8px; margin-bottom: 1.5rem; display: none;">
                        <strong>Balance Due:</strong> <span id="balance_amount">₹0</span>
                    </div>
                    
                    <div class="booking-actions" style="flex-direction: column; gap: 1rem;">
                        <button type="submit" id="completeBookingBtn" class="btn-offline btn-offline-primary" style="width: 100%;">
                            Complete Booking
                        </button>
                        <button type="button" class="btn-offline btn-offline-secondary" style="width: 100%;"
                                onclick="window.location.href='offline_booking_step4.php?back=4'">
                            ← Back
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
const totalAmount = <?php echo $total_amount; ?>;
const razorpayKeyId = "<?php echo $razorpay_key_id; ?>";
const guestName = "<?php echo htmlspecialchars($booking_data['guests']['name'] ?? 'Guest'); ?>";
const guestEmail = "<?php echo htmlspecialchars($booking_data['guests']['email'] ?? ''); ?>";
const guestMobile = "<?php echo htmlspecialchars($booking_data['guests']['mobile'] ?? ''); ?>";
const checkinDate = "<?php echo $booking_data['dates']['checkin'] ?? ''; ?>";
const checkoutDate = "<?php echo $booking_data['dates']['checkout'] ?? ''; ?>";

function updateAmountField() {
    const paymentType = document.getElementById('payment_type').value;
    const amountField = document.getElementById('amount_paid');
    
    if (paymentType === 'full') {
        amountField.value = totalAmount;
    } else {
        amountField.value = Math.round(totalAmount * 0.5);
    }
    updateBalance();
}

function updateBalance() {
    const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
    const balance = totalAmount - amountPaid;
    
    if (balance > 0) {
        document.getElementById('balance_display').style.display = 'block';
        document.getElementById('balance_amount').textContent = '₹' + balance.toFixed(0);
    } else {
        document.getElementById('balance_display').style.display = 'none';
    }
}

document.getElementById('amount_paid').addEventListener('input', updateBalance);

// Handle form submission
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const paymentMethod = document.getElementById('payment_method').value;
    
    if (paymentMethod === 'online') {
        e.preventDefault();
        openRazorpay();
    }
    // If cash, form submits normally
});

function openRazorpay() {
    const amountToPay = parseFloat(document.getElementById('amount_paid').value) || 0;
    const amountInPaise = Math.round(amountToPay * 100);
    
    if (amountToPay <= 0) {
        alert('Please enter a valid amount to pay');
        return;
    }
    
    const bookingRef = 'ALR' + Date.now();
    
    var options = {
        "key": razorpayKeyId,
        "amount": amountInPaise,
        "currency": "INR",
        "name": "Alluri Resorts",
        "description": "Walk-in Booking Payment",
        "image": "../assets/images/Alluri_Logo.png",
        "handler": function (response) {
            // Payment successful - submit form with payment details
            const form = document.getElementById('paymentForm');
            
            // Add Razorpay payment ID
            const paymentIdInput = document.createElement('input');
            paymentIdInput.type = 'hidden';
            paymentIdInput.name = 'razorpay_payment_id';
            paymentIdInput.value = response.razorpay_payment_id;
            form.appendChild(paymentIdInput);
            
            // Add order_id if available
            if (response.razorpay_order_id) {
                const orderIdInput = document.createElement('input');
                orderIdInput.type = 'hidden';
                orderIdInput.name = 'razorpay_order_id';
                orderIdInput.value = response.razorpay_order_id;
                form.appendChild(orderIdInput);
            }
            
            // Add signature if available
            if (response.razorpay_signature) {
                const signatureInput = document.createElement('input');
                signatureInput.type = 'hidden';
                signatureInput.name = 'razorpay_signature';
                signatureInput.value = response.razorpay_signature;
                form.appendChild(signatureInput);
            }
            
            // Submit the form
            form.submit();
        },
        "prefill": {
            "name": guestName,
            "email": guestEmail,
            "contact": guestMobile
        },
        "notes": {
            "booking_type": "walk-in",
            "checkin": checkinDate,
            "checkout": checkoutDate
        },
        "theme": {
            "color": "#8b0000"
        },
        "modal": {
            "ondismiss": function() {
                alert('Payment cancelled. You can try again or choose cash payment.');
            }
        }
    };
    
    var rzp = new Razorpay(options);
    rzp.on('payment.failed', function (response){
        alert('Payment failed: ' + response.error.description);
        console.error('Payment Error:', response.error);
    });
    rzp.open();
}
</script>

</body>
</html>
