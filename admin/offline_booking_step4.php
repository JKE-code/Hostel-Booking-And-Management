<?php
session_start();

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['offline_booking']) || $_SESSION['offline_booking']['step'] < 4) {
    // Allow access if trying to go back
    if (!isset($_GET['back'])) {
        header('Location: offline_booking.php');
        exit;
    }
}

// Allow going back to step 4 from later steps
if (isset($_GET['back']) && $_GET['back'] == 4) {
    $_SESSION['offline_booking']['step'] = 4;
}

$page_title = 'Offline Booking - Step 3';
$page_heading = 'Walk-in / On-Spot Booking';

// Fetch active addons (excluding Extra Person since it's handled in guest info)
$addons = [];
try {
    $stmt = $pdo->query("SELECT * FROM addons WHERE status = 'active' AND name != 'Extra Person' ORDER BY is_pinned DESC, name ASC");
    $addons = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = "Error fetching add-ons.";
}

$selected_addons = $_SESSION['offline_booking']['addons'] ?? [];
?>
<?php include 'includes/offline_header.php'; ?>

<main class="dashboard-main">
    <div class="offline-booking-container">
        <div class="offline-badge">🏨 Walk-in Booking Mode</div>
        
        <div class="booking-progress">
            <div class="progress-step completed"><div class="progress-circle">✓</div><div class="progress-label">Select Dates</div></div>
            <div class="progress-step completed"><div class="progress-circle">✓</div><div class="progress-label">Select Rooms</div></div>
            <div class="progress-step completed"><div class="progress-circle">✓</div><div class="progress-label">Guest Info</div></div>
            <div class="progress-step active"><div class="progress-circle">4</div><div class="progress-label">Add-ons</div></div>
            <div class="progress-step"><div class="progress-circle">5</div><div class="progress-label">Payment</div></div>
        </div>

        <div class="booking-card">
            <h2>Step 4: Select Add-ons (Optional)</h2>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Enhance the stay with additional services</p>
            
            <form method="POST" action="offline_booking_process.php">
                <input type="hidden" name="action" value="set_addons">
                
                <div class="room-grid">
                    <?php 
                    // Map add-ons to their images
                    $addon_images = [
                        'Extra Bed' => '../assets/images/Comfy_Bed.jpg',
                        'Breakfast' => '../assets/images/breakfast.jpg',
                        'Campfire' => '../assets/images/Campfire.jpg',
                        'Dhimsa Dance' => '../assets/images/Dhimsa_Photo_Main.png',
                        'Extra Person' => '../assets/images/service.jpg'
                    ];
                    
                    foreach ($addons as $addon): 
                        $addon_name = $addon['name'];
                        $addon_image = $addon_images[$addon_name] ?? '../assets/images/service.jpg';
                        $is_pinned = $addon['is_pinned'] == 1;
                    ?>
                        <div class="room-card <?php echo $is_pinned ? 'pinned-addon' : ''; ?>" data-addon-id="<?php echo $addon['id']; ?>">
                            <div class="room-image-container" onclick="openRoomImageModal('<?php echo $addon_image; ?>', '<?php echo htmlspecialchars($addon_name); ?>')">
                                <img src="<?php echo $addon_image; ?>" alt="<?php echo htmlspecialchars($addon_name); ?>" class="room-image">
                                <?php if ($is_pinned): ?>
                                    <div class="room-badge" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">⭐ Popular</div>
                                <?php else: ?>
                                    <div class="room-badge"><?php echo $addon['charge_type'] === 'per_night' ? 'Per Night' : 'One Time'; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="room-card-content">
                                <h3><?php echo htmlspecialchars($addon_name); ?></h3>
                                <div class="room-price">₹<?php echo number_format($addon['price'], 0); ?><span class="price-unit"><?php echo $addon['charge_type'] === 'per_night' ? '/night' : ''; ?></span></div>
                                
                                <div class="room-features">
                                    <div class="feature-item">
                                        <span class="feature-icon"><?php echo $addon['charge_type'] === 'per_night' ? '📅' : '🎯'; ?></span>
                                        <span class="feature-text"><?php echo $addon['charge_type'] === 'per_night' ? 'Charged per night' : 'One-time charge'; ?></span>
                                    </div>
                                </div>
                                
                                <div class="quantity-selector">
                                    <label class="quantity-label">Select Quantity:</label>
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn" onclick="updateAddonQty(<?php echo $addon['id']; ?>, -1)">−</button>
                                        <span class="quantity-value" id="addon-qty-<?php echo $addon['id']; ?>"><?php echo $selected_addons[$addon['id']] ?? 0; ?></span>
                                        <button type="button" class="quantity-btn" onclick="updateAddonQty(<?php echo $addon['id']; ?>, 1)">+</button>
                                    </div>
                                    <input type="hidden" name="addons[<?php echo $addon['id']; ?>]" id="addon-input-<?php echo $addon['id']; ?>" value="<?php echo $selected_addons[$addon['id']] ?? 0; ?>">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="booking-actions">
                    <button type="button" class="btn-offline btn-offline-secondary" onclick="window.location.href='offline_booking_step3.php?back=3'">← Back</button>
                    <button type="submit" class="btn-offline btn-offline-primary">Next: Payment →</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function updateAddonQty(addonId, change) {
    const qtyEl = document.getElementById('addon-qty-' + addonId);
    const inputEl = document.getElementById('addon-input-' + addonId);
    const card = document.querySelector(`[data-addon-id="${addonId}"]`);
    
    let qty = Math.max(0, parseInt(qtyEl.textContent) + change);
    qtyEl.textContent = qty;
    inputEl.value = qty;
    card.classList.toggle('selected', qty > 0);
}
</script>

</body>
</html>
