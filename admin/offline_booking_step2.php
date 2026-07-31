<?php
session_start();

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['offline_booking']) || $_SESSION['offline_booking']['step'] < 2) {
    // Allow access if trying to go back
    if (!isset($_GET['back'])) {
        header('Location: offline_booking.php');
        exit;
    }
}

// Allow going back to step 2 from later steps
if (isset($_GET['back']) && $_GET['back'] == 2) {
    $_SESSION['offline_booking']['step'] = 2;
}

$page_title = 'Offline Booking - Step 2';
$page_heading = 'Walk-in / On-Spot Booking';

// Get dates from session
$checkin_date = $_SESSION['offline_booking']['dates']['checkin'] ?? date('Y-m-d');
$checkout_date = $_SESSION['offline_booking']['dates']['checkout'] ?? date('Y-m-d', strtotime('+1 day'));
$nights = $_SESSION['offline_booking']['dates']['nights'] ?? 1;

// Fetch active room types with date-based availability
$room_types = [];
try {
    $stmt = $pdo->query("SELECT * FROM room_types WHERE status = 'active' ORDER BY price_per_night ASC");
    $room_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate available rooms for selected date range
    foreach ($room_types as &$room_type) {
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
            $room_type['id'], 
            $checkin_date, $checkin_date,
            $checkout_date, $checkout_date,
            $checkin_date, $checkout_date
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $booked = $result['booked'] ?? 0;
        
        $room_type['available'] = max(0, $room_type['total_rooms'] - $booked);
    }
} catch (Exception $e) {
    $error_message = "Error fetching room types: " . $e->getMessage();
}
?>
<?php include 'includes/offline_header.php'; ?>

<main class="dashboard-main">
    <div class="offline-booking-container">
        <div class="offline-badge">🏨 Walk-in Booking Mode</div>
        
        <div class="booking-progress">
            <div class="progress-step completed"><div class="progress-circle">✓</div><div class="progress-label">Select Dates</div></div>
            <div class="progress-step active"><div class="progress-circle">2</div><div class="progress-label">Select Rooms</div></div>
            <div class="progress-step"><div class="progress-circle">3</div><div class="progress-label">Guest Info</div></div>
            <div class="progress-step"><div class="progress-circle">4</div><div class="progress-label">Add-ons</div></div>
            <div class="progress-step"><div class="progress-circle">5</div><div class="progress-label">Payment</div></div>
        </div>

        <!-- Selected Dates Summary -->
        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); padding: 1.5rem; border-radius: 12px; border: 2px solid #10b981; margin-bottom: 2rem; text-align: center;">
            <div style="font-size: 1.125rem; color: #065f46; margin-bottom: 0.5rem;">📅 Selected Dates</div>
            <div style="font-size: 1.25rem; font-weight: 700; color: #047857;">
                <?php echo date('M d, Y', strtotime($checkin_date)); ?> → <?php echo date('M d, Y', strtotime($checkout_date)); ?>
                <span style="margin-left: 1rem; color: #10b981;">(<?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?>)</span>
            </div>
        </div>

        <div class="booking-card">
            <h2>Step 2: Select Rooms</h2>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Choose room types and quantities (showing availability for selected dates)</p>
            
            <form method="POST" action="offline_booking_process.php" id="roomSelectionForm">
                <input type="hidden" name="action" value="select_rooms">
                
                <div class="room-grid">
                    <?php 
                    // Map room types to their image folders
                    $room_images = [
                        'Non-AC' => '../assets/images/Non-AC/Non_AC.jpg',
                        'AC' => '../assets/images/AC/AC.jpg',
                        'Deluxe' => '../assets/images/Deluxe/1.jpg',
                        'Suite' => '../assets/images/Suite/1.jpg'
                    ];
                    
                    foreach ($room_types as $room): 
                        $room_name = $room['name'];
                        $room_image = $room_images[$room_name] ?? '../assets/images/Room.jpg';
                        $is_available = $room['available'] > 0;
                    ?>
                        <div class="room-card <?php echo !$is_available ? 'room-unavailable' : ''; ?>" data-room-id="<?php echo $room['id']; ?>">
                            <div class="room-image-container" onclick="openRoomImageModal('<?php echo $room_image; ?>', '<?php echo htmlspecialchars($room_name); ?> Room')">
                                <img src="<?php echo $room_image; ?>" alt="<?php echo htmlspecialchars($room_name); ?>" class="room-image">
                                <div class="room-badge"><?php echo htmlspecialchars($room_name); ?></div>
                                <?php if (!$is_available): ?>
                                    <div class="room-badge" style="background: #ef4444; right: 12px; left: auto;">Fully Booked</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="room-card-content">
                                <h3><?php echo htmlspecialchars($room_name); ?> Room</h3>
                                <div class="room-price">₹<?php echo number_format($room['price_per_night'], 0); ?><span class="price-unit">/night</span></div>
                                
                                <div class="room-features">
                                    <div class="feature-item">
                                        <span class="feature-icon">👥</span>
                                        <span class="feature-text"><?php echo $room['max_adults']; ?> Adults, <?php echo $room['max_children']; ?> Child</span>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-icon" style="<?php echo $is_available ? 'color: #10b981;' : 'color: #ef4444;'; ?>">
                                            <?php echo $is_available ? '✓' : '✗'; ?>
                                        </span>
                                        <span class="feature-text" style="<?php echo $is_available ? 'color: #10b981;' : 'color: #ef4444;'; ?> font-weight: 600;">
                                            <?php echo $room['available']; ?> of <?php echo $room['total_rooms']; ?> available
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="quantity-selector">
                                    <label class="quantity-label">Select Quantity:</label>
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $room['id']; ?>, -1, <?php echo $room['available']; ?>)" <?php echo !$is_available ? 'disabled' : ''; ?>>−</button>
                                        <span class="quantity-value" id="qty-<?php echo $room['id']; ?>">0</span>
                                        <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $room['id']; ?>, 1, <?php echo $room['available']; ?>)" <?php echo !$is_available ? 'disabled' : ''; ?>>+</button>
                                    </div>
                                    <input type="hidden" name="rooms[<?php echo $room['id']; ?>]" id="input-<?php echo $room['id']; ?>" value="0" data-max="<?php echo $room['available']; ?>">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="booking-actions">
                    <button type="button" class="btn-offline btn-offline-secondary" onclick="window.location.href='offline_booking.php?back=1'">← Back</button>
                    <button type="submit" class="btn-offline btn-offline-primary">Next: Guest Info →</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function updateQuantity(roomId, change, maxAvailable) {
    const qtyElement = document.getElementById('qty-' + roomId);
    const inputElement = document.getElementById('input-' + roomId);
    const roomCard = document.querySelector(`[data-room-id="${roomId}"]`);
    
    // Get max from data attribute if not passed
    if (typeof maxAvailable === 'undefined') {
        maxAvailable = parseInt(inputElement.getAttribute('data-max')) || 999;
    }
    
    let currentQty = parseInt(qtyElement.textContent);
    let newQty = currentQty + change;
    
    // Enforce limits
    newQty = Math.max(0, Math.min(newQty, maxAvailable));
    
    qtyElement.textContent = newQty;
    inputElement.value = newQty;
    
    if (newQty > 0) {
        roomCard.classList.add('selected');
    } else {
        roomCard.classList.remove('selected');
    }
}
</script>

</body>
</html>
