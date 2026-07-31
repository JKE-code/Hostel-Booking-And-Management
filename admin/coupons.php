<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Set page variables
$page_title = 'Coupon Management';
$page_heading = 'Coupon Management';

$success_message = '';
$error_message = '';

// Handle coupon creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_coupon'])) {
    $code = strtoupper(trim($_POST['code']));
    $discount_type = $_POST['discount_type'];
    $discount_value = floatval($_POST['discount_value']);
    $min_amount = !empty($_POST['min_amount']) ? floatval($_POST['min_amount']) : NULL;
    $max_uses = !empty($_POST['max_uses']) ? intval($_POST['max_uses']) : NULL;
    $expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : NULL;
    
    // Check if code already exists
    $check = mysqli_query($conn, "SELECT id FROM coupons WHERE code = '$code'");
    if (mysqli_num_rows($check) > 0) {
        $error_message = '❌ Coupon code already exists!';
    } else {
        $query = "INSERT INTO coupons (code, discount_type, discount_value, min_amount, max_uses, expiry_date, status) 
                  VALUES ('$code', '$discount_type', $discount_value, " . 
                  ($min_amount ? $min_amount : 'NULL') . ", " . 
                  ($max_uses ? $max_uses : 'NULL') . ", " . 
                  ($expiry_date ? "'$expiry_date'" : 'NULL') . ", 'active')";
        
        if (mysqli_query($conn, $query)) {
            $success_message = '✅ Coupon created successfully!';
        } else {
            $error_message = '❌ Error creating coupon: ' . mysqli_error($conn);
        }
    }
}

// Handle coupon deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM coupons WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $success_message = '✅ Coupon deleted successfully!';
    } else {
        $error_message = '❌ Error deleting coupon.';
    }
}

// Handle coupon status toggle
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $query = "UPDATE coupons SET status = IF(status = 'active', 'expired', 'active') WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $success_message = '✅ Coupon status updated!';
    }
}

// Fetch all coupons
$coupons = [];
$query = "SELECT * FROM coupons ORDER BY id DESC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $coupons[] = $row;
    }
}

// Calculate statistics
$total_coupons = count($coupons);
$active_coupons = 0;
$expired_coupons = 0;

foreach ($coupons as $coupon) {
    if ($coupon['status'] == 'active') {
        $active_coupons++;
    } else {
        $expired_coupons++;
    }
}

// Get usage statistics
$stats = [
    'total_redemptions' => 0,
    'total_discount' => 0,
    'unique_users' => 0,
    'avg_discount' => 0
];

$query = "SELECT COUNT(*) as total, SUM(b.total_amount * 0.1) as discount FROM coupon_usage cu 
          JOIN bookings b ON cu.booking_id = b.id";
$result = mysqli_query($conn, $query);
if ($result && $row = mysqli_fetch_assoc($result)) {
    $stats['total_redemptions'] = $row['total'];
    $stats['total_discount'] = $row['discount'] ?? 0;
    $stats['avg_discount'] = $stats['total_redemptions'] > 0 ? $stats['total_discount'] / $stats['total_redemptions'] : 0;
}
?>
<?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="container">
            <?php if ($success_message): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Create Coupon Section -->
            <section class="create-coupon-section">
                <div class="section-card">
                    <h2>Create New Coupon</h2>
                    <form method="POST" action="coupons.php">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="code">Coupon Code <span class="required">*</span></label>
                                <input type="text" 
                                       id="code" 
                                       name="code" 
                                       required 
                                       placeholder="e.g., WELCOME50" 
                                       maxlength="20"
                                       pattern="[A-Z0-9]+"
                                       style="text-transform: uppercase;">
                                <small>Alphanumeric, no spaces (max 20 characters)</small>
                            </div>

                            <div class="form-group">
                                <label for="discount_type">Discount Type <span class="required">*</span></label>
                                <select id="discount_type" name="discount_type" required>
                                    <option value="percent">Percentage (%)</option>
                                    <option value="flat">Fixed Amount (₹)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="discount_value">Discount Value <span class="required">*</span></label>
                                <input type="number" 
                                       id="discount_value" 
                                       name="discount_value" 
                                       required 
                                       min="1" 
                                       step="0.01"
                                       placeholder="e.g., 10">
                                <small>Enter percentage (1-100) or amount</small>
                            </div>

                            <div class="form-group">
                                <label for="min_amount">Minimum Booking Amount (₹)</label>
                                <input type="number" 
                                       id="min_amount" 
                                       name="min_amount" 
                                       min="0" 
                                       step="100"
                                       placeholder="e.g., 5000">
                                <small>Optional: Minimum amount to use coupon</small>
                            </div>

                            <div class="form-group">
                                <label for="max_uses">Usage Limit</label>
                                <input type="number" 
                                       id="max_uses" 
                                       name="max_uses" 
                                       min="1" 
                                       placeholder="e.g., 100">
                                <small>Optional: Total number of times coupon can be used</small>
                            </div>

                            <div class="form-group">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="date" 
                                       id="expiry_date" 
                                       name="expiry_date"
                                       min="<?php echo date('Y-m-d'); ?>">
                                <small>Optional: Leave blank for no expiry</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" name="create_coupon" class="btn-primary">Create Coupon</button>
                            <button type="reset" class="btn-secondary">Clear Form</button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Active Coupons -->
            <section class="coupons-list-section">
                <div class="section-card">
                    <div class="section-header">
                        <h2>All Coupons</h2>
                        <div class="stats-pills">
                            <span class="stat-pill">Total: <strong><?php echo $total_coupons; ?></strong></span>
                            <span class="stat-pill">Active: <strong><?php echo $active_coupons; ?></strong></span>
                            <span class="stat-pill">Expired: <strong><?php echo $expired_coupons; ?></strong></span>
                        </div>
                    </div>

                    <?php if (empty($coupons)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">🎟️</div>
                            <p>No coupons created yet</p>
                            <small>Create your first coupon using the form above</small>
                        </div>
                    <?php else: ?>
                        <div class="coupons-grid">
                            <?php foreach ($coupons as $coupon): ?>
                                <div class="coupon-card <?php echo $coupon['status'] == 'expired' ? 'expired' : ''; ?>">
                                    <div class="coupon-code"><?php echo htmlspecialchars($coupon['code']); ?></div>
                                    <div class="coupon-discount">
                                        <?php 
                                        if ($coupon['discount_type'] == 'percent') {
                                            echo $coupon['discount_value'] . '% OFF';
                                        } else {
                                            echo '₹' . number_format($coupon['discount_value'], 0) . ' OFF';
                                        }
                                        ?>
                                    </div>
                                    
                                    <?php if ($coupon['min_amount']): ?>
                                        <div class="coupon-details">
                                            <strong>Min Amount:</strong> ₹<?php echo number_format($coupon['min_amount'], 0); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($coupon['max_uses']): ?>
                                        <div class="coupon-details">
                                            <strong>Usage:</strong> <?php echo $coupon['used_count']; ?> / <?php echo $coupon['max_uses']; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($coupon['expiry_date']): ?>
                                        <div class="coupon-details">
                                            <strong>Expires:</strong> <?php echo date('d M Y', strtotime($coupon['expiry_date'])); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <span class="coupon-status <?php echo $coupon['status']; ?>">
                                        <?php echo ucfirst($coupon['status']); ?>
                                    </span>
                                    
                                    <div class="coupon-actions">
                                        <a href="coupons.php?toggle=<?php echo $coupon['id']; ?>" class="btn-edit">
                                            <?php echo $coupon['status'] == 'active' ? 'Deactivate' : 'Activate'; ?>
                                        </a>
                                        <a href="coupons.php?delete=<?php echo $coupon['id']; ?>" 
                                           class="btn-delete" 
                                           onclick="return confirm('Are you sure you want to delete this coupon?')">
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Usage Statistics -->
            <section class="stats-section">
                <div class="section-card">
                    <h2>Coupon Usage Statistics</h2>
                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-icon">📊</div>
                            <div class="stat-content">
                                <div class="stat-value"><?php echo $stats['total_redemptions']; ?></div>
                                <div class="stat-label">Total Redemptions</div>
                            </div>
                        </div>

                        <div class="stat-box">
                            <div class="stat-icon">💰</div>
                            <div class="stat-content">
                                <div class="stat-value">₹<?php echo number_format($stats['total_discount'], 0); ?></div>
                                <div class="stat-label">Total Discount Given</div>
                            </div>
                        </div>

                        <div class="stat-box">
                            <div class="stat-icon">📈</div>
                            <div class="stat-content">
                                <div class="stat-value">₹<?php echo number_format($stats['avg_discount'], 0); ?></div>
                                <div class="stat-label">Avg Discount/Booking</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>
