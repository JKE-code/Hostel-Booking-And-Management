<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Set page variables
$page_title = 'Pricing Management';
$page_heading = 'Pricing Management';

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_pricing'])) {
    $updates = [];
    
    // Update room prices
    $room_updates = [
        1 => ['price' => $_POST['nonac_price']],  // Non-AC
        2 => ['price' => $_POST['ac_price']],     // AC
        3 => ['price' => $_POST['deluxe_price']], // Deluxe
        4 => ['price' => $_POST['suite_price']]   // Suite
    ];
    
    $all_success = true;
    foreach ($room_updates as $room_id => $data) {
        $price = floatval($data['price']);
        $query = "UPDATE room_types SET price_per_night = $price WHERE id = $room_id";
        if (!mysqli_query($conn, $query)) {
            $all_success = false;
            break;
        }
    }
    
    if ($all_success) {
        $success_message = '✅ Prices updated successfully! Changes are now live across the website.';
    } else {
        $error_message = '❌ Error updating prices. Please try again.';
    }
}

// Fetch current prices
$room_prices = [];
$query = "SELECT id, name, price_per_night FROM room_types WHERE status = 'active' ORDER BY id";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $room_prices[$row['id']] = [
            'name' => $row['name'],
            'price' => $row['price_per_night']
        ];
    }
}

// GST rate (fixed at 5%)
$gst_rate = 0.05;
?>
<?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="container">
            <div class="admin-pricing-container">
                <?php if ($success_message): ?>
                    <div class="success-message"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="current-prices-display">
                    <h2>📊 Current Prices (Live on Website)</h2>
                    <div class="price-comparison">
                        <?php foreach ($room_prices as $room): ?>
                            <div class="price-item">
                                <strong><?php echo htmlspecialchars($room['name']); ?></strong>
                                ₹<?php echo number_format($room['price'], 0); ?>/night
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <form method="POST" action="pricing.php">
                    <h2>🏨 Room Prices</h2>
                    <p style="color: #666; margin-bottom: 1.5rem;">Prices shown include GST</p>
                    
                    <div class="pricing-grid">
                        <!-- Non A/C Room -->
                        <div class="pricing-card">
                            <h3>Non A/C Room</h3>
                            <div class="price-input-group">
                                <label for="nonac_price">Price per Night (₹)</label>
                                <input type="number" 
                                       id="nonac_price" 
                                       name="nonac_price" 
                                       value="<?php echo isset($room_prices[1]) ? $room_prices[1]['price'] : 2500; ?>" 
                                       min="0" 
                                       step="1" 
                                       required>
                                <small>GST included</small>
                            </div>
                        </div>

                        <!-- A/C Room -->
                        <div class="pricing-card">
                            <h3>A/C Room</h3>
                            <div class="price-input-group">
                                <label for="ac_price">Price per Night (₹)</label>
                                <input type="number" 
                                       id="ac_price" 
                                       name="ac_price" 
                                       value="<?php echo isset($room_prices[2]) ? $room_prices[2]['price'] : 3500; ?>" 
                                       min="0" 
                                       step="1" 
                                       required>
                                <small>GST included</small>
                            </div>
                        </div>

                        <!-- Deluxe Room -->
                        <div class="pricing-card">
                            <h3>Deluxe Room</h3>
                            <div class="price-input-group">
                                <label for="deluxe_price">Price per Night (₹)</label>
                                <input type="number" 
                                       id="deluxe_price" 
                                       name="deluxe_price" 
                                       value="<?php echo isset($room_prices[3]) ? $room_prices[3]['price'] : 5000; ?>" 
                                       min="0" 
                                       step="1" 
                                       required>
                                <small>GST included</small>
                            </div>
                        </div>

                        <!-- Suite -->
                        <div class="pricing-card">
                            <h3>Suite</h3>
                            <div class="price-input-group">
                                <label for="suite_price">Price per Night (₹)</label>
                                <input type="number" 
                                       id="suite_price" 
                                       name="suite_price" 
                                       value="<?php echo isset($room_prices[4]) ? $room_prices[4]['price'] : 7000; ?>" 
                                       min="0" 
                                       step="1" 
                                       required>
                                <small>GST included</small>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="save_pricing" class="btn-save">💾 Save & Apply Changes</button>
                </form>
            </div>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>
