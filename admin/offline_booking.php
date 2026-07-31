<?php
session_start();

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../config/db.php';

// Check if PDO connection is available
if ($pdo === null) {
    die("Database connection failed. Please check your database configuration.");
}

// Mark that we're in offline mode (for security)
$_SESSION['offline_mode'] = true;

// Reset booking if new booking requested
if (isset($_GET['new']) && $_GET['new'] == 1) {
    unset($_SESSION['offline_booking']);
}

// Set page variables
$page_title = 'Offline Booking';
$page_heading = 'Walk-in / On-Spot Booking';

// Initialize session for booking if not exists
if (!isset($_SESSION['offline_booking'])) {
    $_SESSION['offline_booking'] = [
        'step' => 1,
        'dates' => [],
        'guests' => [],
        'rooms' => [],
        'addons' => [],
        'payment_method' => 'cash'
    ];
}

// Allow going back to step 1 from any step
if (isset($_GET['back']) && $_GET['back'] == 1) {
    $_SESSION['offline_booking']['step'] = 1;
}

// Get current step
$current_step = $_SESSION['offline_booking']['step'];

?>
<?php include 'includes/offline_header.php'; ?>

<main class="dashboard-main">
    <div class="offline-booking-container">
        <div class="offline-badge">🏨 Walk-in Booking Mode</div>
        
        <!-- Progress Bar -->
        <div class="booking-progress">
            <div class="progress-step <?php echo $current_step >= 1 ? 'active' : ''; ?> <?php echo $current_step > 1 ? 'completed' : ''; ?>">
                <div class="progress-circle"><?php echo $current_step > 1 ? '✓' : '1'; ?></div>
                <div class="progress-label">Select Dates</div>
            </div>
            <div class="progress-step <?php echo $current_step >= 2 ? 'active' : ''; ?> <?php echo $current_step > 2 ? 'completed' : ''; ?>">
                <div class="progress-circle"><?php echo $current_step > 2 ? '✓' : '2'; ?></div>
                <div class="progress-label">Select Rooms</div>
            </div>
            <div class="progress-step <?php echo $current_step >= 3 ? 'active' : ''; ?> <?php echo $current_step > 3 ? 'completed' : ''; ?>">
                <div class="progress-circle"><?php echo $current_step > 3 ? '✓' : '3'; ?></div>
                <div class="progress-label">Guest Info</div>
            </div>
            <div class="progress-step <?php echo $current_step >= 4 ? 'active' : ''; ?> <?php echo $current_step > 4 ? 'completed' : ''; ?>">
                <div class="progress-circle"><?php echo $current_step > 4 ? '✓' : '4'; ?></div>
                <div class="progress-label">Add-ons</div>
            </div>
            <div class="progress-step <?php echo $current_step >= 5 ? 'active' : ''; ?>">
                <div class="progress-circle">5</div>
                <div class="progress-label">Payment</div>
            </div>
        </div>

        <?php if ($current_step == 1): ?>
            <!-- Step 1: Dates Only -->
            <div class="booking-card">
                <h2>Step 1: Select Dates</h2>
                <p style="color: #6b7280; margin-bottom: 1.5rem;">Choose check-in and check-out dates</p>
                
                <form method="POST" action="offline_booking_process.php" id="datesForm">
                    <input type="hidden" name="action" value="set_dates">
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="form-group">
                            <label for="checkin" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Check-in Date *</label>
                            <input type="date" id="checkin" name="checkin" required 
                                   value="<?php echo $_SESSION['offline_booking']['dates']['checkin'] ?? ''; ?>"
                                   style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                        </div>
                        
                        <div class="form-group">
                            <label for="checkout" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Check-out Date *</label>
                            <input type="date" id="checkout" name="checkout" required 
                                   value="<?php echo $_SESSION['offline_booking']['dates']['checkout'] ?? ''; ?>"
                                   style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                        </div>
                    </div>
                    
                    <div style="background: #f0fdf4; padding: 1.5rem; border-radius: 12px; border: 2px solid #10b981; margin-bottom: 2rem; text-align: center;">
                        <div style="font-size: 1rem; color: #065f46; margin-bottom: 0.5rem;">Number of Nights</div>
                        <div style="font-size: 2rem; font-weight: 700; color: #10b981;" id="nights_display">0</div>
                    </div>
                    
                    <div class="booking-actions">
                        <button type="submit" class="btn-offline btn-offline-primary">Next: Select Rooms →</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

    </div>
</main>

<script>
// Set minimum dates
const today = new Date().toISOString().split('T')[0];
const checkinInput = document.getElementById('checkin');
const checkoutInput = document.getElementById('checkout');

if (checkinInput && checkoutInput) {
    checkinInput.min = today;
    checkoutInput.min = today;

    // Calculate nights
    function calculateNights() {
        const checkin = new Date(checkinInput.value);
        const checkout = new Date(checkoutInput.value);
        
        if (checkin && checkout && checkout > checkin) {
            const nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
            document.getElementById('nights_display').textContent = nights;
            return nights;
        } else {
            document.getElementById('nights_display').textContent = '0';
            return 0;
        }
    }

    checkinInput.addEventListener('change', function() {
        checkoutInput.min = this.value;
        calculateNights();
    });

    checkoutInput.addEventListener('change', calculateNights);

    // Calculate on page load if dates are set
    if (checkinInput.value && checkoutInput.value) {
        calculateNights();
    }

    // Form validation
    document.getElementById('datesForm').addEventListener('submit', function(e) {
        const nights = calculateNights();
        if (nights < 1) {
            e.preventDefault();
            alert('Please select valid dates. Check-out must be at least 1 day after check-in.');
        }
    });
}
</script>

</body>
</html>
