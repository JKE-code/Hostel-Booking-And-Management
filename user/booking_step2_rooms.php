<?php
// Step 2: Room Selection (NEW - Rooms come after dates now)
session_start();

// Prevent caching to avoid form resubmission issues
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect back if no dates selected
if (!isset($_SESSION['booking']['checkin']) || !isset($_SESSION['booking']['checkout'])) {
    header('Location: booking_step1_dates.php');
    exit();
}

$checkin_date = $_SESSION['booking']['checkin'];
$checkout_date = $_SESSION['booking']['checkout'];
$nights = $_SESSION['booking']['nights'];

// Initialize default prices and availability
$room_prices = [
    'nonac' => 2500,
    'ac' => 3500,
    'deluxe' => 5000,
    'suite' => 7000
];

$room_availability = [
    'nonac' => 10,
    'ac' => 10,
    'deluxe' => 10,
    'suite' => 10
];

// Fetch from database with date-based availability
try {
    require_once '../config/db.php';
    
    if (isset($pdo) && $pdo instanceof PDO) {
        // Get room types with prices and total rooms
        $stmt = $pdo->prepare("SELECT id, name, price_per_night, total_rooms FROM room_types WHERE status = 'active' ORDER BY price_per_night ASC");
        $stmt->execute();
        
        $room_types_data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name_key = strtolower(str_replace(['-', ' '], '', $row['name']));
            $room_prices[$name_key] = $row['price_per_night'];
            $room_types_data[$name_key] = [
                'id' => $row['id'],
                'total' => $row['total_rooms'],
                'name' => $row['name']
            ];
        }
        
        // Calculate available rooms for selected date range
        foreach ($room_types_data as $key => $data) {
            // Get booked rooms for this type during the selected dates
            $stmt = $pdo->prepare("
                SELECT COALESCE(SUM(br.rooms_booked), 0) as booked
                FROM booking_rooms br
                JOIN bookings b ON br.booking_id = b.id
                WHERE br.room_type_id = ?
                AND b.status = 'confirmed'
                AND (
                    (b.checkin <= ? AND b.checkout > ?) OR
                    (b.checkin < ? AND b.checkout >= ?) OR
                    (b.checkin >= ? AND b.checkout <= ?)
                )
            ");
            $stmt->execute([
                $data['id'], 
                $checkin_date, $checkin_date,
                $checkout_date, $checkout_date,
                $checkin_date, $checkout_date
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $booked = $result['booked'] ?? 0;
            
            $room_availability[$key] = max(0, $data['total'] - $booked);
        }
    }
} catch (Exception $e) {
    // Use default prices and availability
    error_log("Database error in booking_step2_rooms.php: " . $e->getMessage());
}

// Use database prices directly
$display_prices = [];
foreach ($room_prices as $type => $price) {
    $display_prices[$type] = round($price);
}

// Format prices for display
function formatPrice($price) {
    return number_format($price, 0, '.', ',');
}
?>
<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header">
    <h1>Book Your Stay</h1>
    <p>Complete your reservation in 5 simple steps</p>
</section>

<!-- Smart Offers & FOMO Section -->
<section class="smart-offers-section">
    <div class="container">
        <div class="offers-banner">
            <div class="offer-item">
                <span class="offer-icon">⚡</span>
                <span class="offer-text"><strong>10% OFF</strong> on early bookings (7+ days advance)</span>
            </div>
            <div class="offer-item">
                <span class="offer-icon">🎉</span>
                <span class="offer-text"><strong>₹500 OFF</strong> on 2-night stays</span>
            </div>
            <div class="offer-item">
                <span class="offer-icon">🌟</span>
                <span class="offer-text"><strong>₹850 OFF</strong> on 5+ night stays</span>
            </div>
        </div>
        <div class="fomo-alerts" id="fomo-alerts"></div>
    </div>
</section>

<!-- Progress Stepper -->
<section class="booking-progress">
    <div class="container">
        <div class="progress-steps">
            <div class="progress-line">
                <div class="progress-line-fill" style="width: 20%;"></div>
            </div>
            <div class="progress-step completed" data-step="1">
                <div class="step-circle">✓</div>
                <div class="step-label">Choose Dates</div>
            </div>
            <div class="progress-step active" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Select Rooms</div>
            </div>
            <div class="progress-step" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Guest Info</div>
            </div>
            <div class="progress-step" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Add-ons & Coupons</div>
            </div>
            <div class="progress-step" data-step="5">
                <div class="step-circle">5</div>
                <div class="step-label">Payment</div>
            </div>
        </div>
    </div>
</section>

<!-- Selected Dates Summary -->
<section class="booking-section" style="padding-top: 1rem;">
    <div class="container">
        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); padding: 1.5rem; border-radius: 12px; border: 2px solid #10b981; margin-bottom: 2rem; text-align: center;">
            <div style="font-size: 1.125rem; color: #065f46; margin-bottom: 0.5rem;">📅 Your Selected Dates</div>
            <div style="font-size: 1.25rem; font-weight: 700; color: #047857;">
                <?php echo date('M d, Y', strtotime($checkin_date)); ?> → <?php echo date('M d, Y', strtotime($checkout_date)); ?>
                <span style="margin-left: 1rem; color: #10b981;">(<?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?>)</span>
            </div>
            <a href="booking_step1_dates.php" style="color: #8b0000; font-size: 0.875rem; text-decoration: underline; margin-top: 0.5rem; display: inline-block;">Change Dates</a>
        </div>
    </div>
</section>

<!-- Booking Section -->
<section class="booking-section" style="padding-top: 0;">
    <div class="container">
        <div class="booking-container">
            
            <!-- STEP 2: Room Selection -->
            <div id="step-2" class="booking-section-wrapper active">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Select Your Rooms</h2>
                        </div>
                        <div class="section-subtitle">
                            <div class="section-icon">🏠</div>
                            <p>Choose room types and quantities (showing availability for your dates)</p>
                        </div>
                    </div>

                    <div class="rooms-selection-grid">
                        <!-- Non A/C Room -->
                        <div class="room-selection-card" data-room="nonac">
                            <div class="room-card-header">
                                <img src="../assets/images/Non-AC/Non_AC.jpg" alt="Non A/C Room" class="room-card-image" onclick="openRoomImageModal(this.src, this.alt)" style="cursor: pointer;">
                                <div class="room-card-info">
                                    <h3>Non A/C Room</h3>
                                    <div class="room-card-price">
                                        ₹<span id="booking-nonac-price"><?php echo formatPrice($display_prices['nonac']); ?></span>
                                        <small>/night</small>
                                    </div>
                                    <div class="room-availability" style="color: <?php echo $room_availability['nonac'] > 0 ? '#10b981' : '#ef4444'; ?>; font-weight: 600; margin-top: 0.5rem;">
                                        <?php if ($room_availability['nonac'] > 0): ?>
                                            ✓ <?php echo $room_availability['nonac']; ?> available
                                        <?php else: ?>
                                            ✗ Fully booked
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="room-quantity-selector">
                                <label>Number of Rooms:</label>
                                <div class="quantity-controls">
                                    <button type="button" class="qty-btn" data-action="decrease" data-room="nonac" <?php echo $room_availability['nonac'] == 0 ? 'disabled' : ''; ?>>−</button>
                                    <span class="qty-display" id="nonac-display">0</span>
                                    <button type="button" class="qty-btn" data-action="increase" data-room="nonac" <?php echo $room_availability['nonac'] == 0 ? 'disabled' : ''; ?>>+</button>
                                    <input type="hidden" id="nonac_qty" name="nonac_qty" value="0" min="0" max="<?php echo $room_availability['nonac']; ?>" data-price="<?php echo $display_prices['nonac']; ?>" data-room-type="Non A/C Room">
                                </div>
                            </div>
                        </div>

                        <!-- A/C Room -->
                        <div class="room-selection-card" data-room="ac">
                            <div class="room-card-header">
                                <img src="../assets/images/AC/AC.jpg" alt="A/C Room" class="room-card-image" onclick="openRoomImageModal(this.src, this.alt)" style="cursor: pointer;">
                                <div class="room-card-info">
                                    <h3>A/C Room</h3>
                                    <div class="room-card-price">
                                        ₹<span id="booking-ac-price"><?php echo formatPrice($display_prices['ac']); ?></span>
                                        <small>/night</small>
                                    </div>
                                    <div class="room-availability" style="color: <?php echo $room_availability['ac'] > 0 ? '#10b981' : '#ef4444'; ?>; font-weight: 600; margin-top: 0.5rem;">
                                        <?php if ($room_availability['ac'] > 0): ?>
                                            ✓ <?php echo $room_availability['ac']; ?> available
                                        <?php else: ?>
                                            ✗ Fully booked
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="room-quantity-selector">
                                <label>Number of Rooms:</label>
                                <div class="quantity-controls">
                                    <button type="button" class="qty-btn" data-action="decrease" data-room="ac" <?php echo $room_availability['ac'] == 0 ? 'disabled' : ''; ?>>−</button>
                                    <span class="qty-display" id="ac-display">0</span>
                                    <button type="button" class="qty-btn" data-action="increase" data-room="ac" <?php echo $room_availability['ac'] == 0 ? 'disabled' : ''; ?>>+</button>
                                    <input type="hidden" id="ac_qty" name="ac_qty" value="0" min="0" max="<?php echo $room_availability['ac']; ?>" data-price="<?php echo $display_prices['ac']; ?>" data-room-type="A/C Room">
                                </div>
                            </div>
                        </div>

                        <!-- Deluxe A/C Room -->
                        <div class="room-selection-card" data-room="deluxe">
                            <div class="room-card-header">
                                <img src="../assets/images/Deluxe/1.jpg" alt="Deluxe A/C Room" class="room-card-image" onclick="openDeluxeSlideshow()" style="cursor: pointer;">
                                <div class="room-card-info">
                                    <h3>Deluxe A/C Room</h3>
                                    <div class="room-card-price">
                                        ₹<span id="booking-deluxe-price"><?php echo formatPrice($display_prices['deluxe']); ?></span>
                                        <small>/night</small>
                                    </div>
                                    <div class="room-availability" style="color: <?php echo $room_availability['deluxe'] > 0 ? '#10b981' : '#ef4444'; ?>; font-weight: 600; margin-top: 0.5rem;">
                                        <?php if ($room_availability['deluxe'] > 0): ?>
                                            ✓ <?php echo $room_availability['deluxe']; ?> available
                                        <?php else: ?>
                                            ✗ Fully booked
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="room-quantity-selector">
                                <label>Number of Rooms:</label>
                                <div class="quantity-controls">
                                    <button type="button" class="qty-btn" data-action="decrease" data-room="deluxe" <?php echo $room_availability['deluxe'] == 0 ? 'disabled' : ''; ?>>−</button>
                                    <span class="qty-display" id="deluxe-display">0</span>
                                    <button type="button" class="qty-btn" data-action="increase" data-room="deluxe" <?php echo $room_availability['deluxe'] == 0 ? 'disabled' : ''; ?>>+</button>
                                    <input type="hidden" id="deluxe_qty" name="deluxe_qty" value="0" min="0" max="<?php echo $room_availability['deluxe']; ?>" data-price="<?php echo $display_prices['deluxe']; ?>" data-room-type="Deluxe A/C Room">
                                </div>
                            </div>
                        </div>

                        <!-- Suite -->
                        <div class="room-selection-card" data-room="suite">
                            <div class="room-card-header">
                                <img src="../assets/images/Suite/4.jpg" alt="Suite" class="room-card-image" onclick="openSuiteSlideshow()" style="cursor: pointer;">
                                <div class="room-card-info">
                                    <h3>Suite</h3>
                                    <div class="room-card-price">
                                        ₹<span id="booking-suite-price"><?php echo formatPrice($display_prices['suite']); ?></span>
                                        <small>/night</small>
                                    </div>
                                    <div class="room-availability" style="color: <?php echo $room_availability['suite'] > 0 ? '#10b981' : '#ef4444'; ?>; font-weight: 600; margin-top: 0.5rem;">
                                        <?php if ($room_availability['suite'] > 0): ?>
                                            ✓ <?php echo $room_availability['suite']; ?> available
                                        <?php else: ?>
                                            ✗ Fully booked
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="room-quantity-selector">
                                <label>Number of Rooms:</label>
                                <div class="quantity-controls">
                                    <button type="button" class="qty-btn" data-action="decrease" data-room="suite" <?php echo $room_availability['suite'] == 0 ? 'disabled' : ''; ?>>−</button>
                                    <span class="qty-display" id="suite-display">0</span>
                                    <button type="button" class="qty-btn" data-action="increase" data-room="suite" <?php echo $room_availability['suite'] == 0 ? 'disabled' : ''; ?>>+</button>
                                    <input type="hidden" id="suite_qty" name="suite_qty" value="0" min="0" max="<?php echo $room_availability['suite']; ?>" data-price="<?php echo $display_prices['suite']; ?>" data-room-type="Suite">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <strong style="font-size: 1.125rem;">Total Rooms Selected: <span id="total_rooms_count" style="color: #8b0000; font-size: 1.5rem;">0</span></strong>
                    </div>

                    <div class="section-navigation">
                        <button type="button" class="btn-nav btn-prev" onclick="window.location.href='booking_step1_dates.php'">
                            ← Previous: Change Dates
                        </button>
                        <button type="button" class="btn-nav btn-next" onclick="submitRoomSelection()">
                            Next: Guest Information →
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Room Image Modal -->
<div id="roomImageModal" class="image-modal">
    <span class="image-modal-close" onclick="closeRoomImageModal()">&times;</span>
    <img class="image-modal-content" id="roomModalImage" alt="">
    <div class="image-modal-caption" id="roomModalCaption"></div>
    <button class="modal-slide-arrow modal-prev" id="modalPrevBtn" onclick="changeModalSlide(-1)" style="display: none;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>
    <button class="modal-slide-arrow modal-next" id="modalNextBtn" onclick="changeModalSlide(1)" style="display: none;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </button>
</div>

<script>
// Submit room selection to step 3
function submitRoomSelection() {
    // Get room quantities
    const rooms = {
        nonac_qty: document.getElementById('nonac_qty').value,
        ac_qty: document.getElementById('ac_qty').value,
        deluxe_qty: document.getElementById('deluxe_qty').value,
        suite_qty: document.getElementById('suite_qty').value
    };
    
    // Check if at least one room is selected
    const totalRooms = parseInt(rooms.nonac_qty) + parseInt(rooms.ac_qty) + 
                       parseInt(rooms.deluxe_qty) + parseInt(rooms.suite_qty);
    
    if (totalRooms === 0) {
        alert('Please select at least one room');
        return;
    }
    
    // Use AJAX to store in session without POST form submission
    fetch('booking_step2_process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(rooms)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Use replace instead of href to avoid adding to history
            window.location.replace('booking_step3_guests.php');
        } else {
            alert('Error saving room selection. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving room selection. Please try again.');
    });
}
</script>

<?php include 'includes/footer.php'; ?>
