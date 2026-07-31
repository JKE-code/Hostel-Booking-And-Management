<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../config/db.php';
require_once 'includes/session_check.php';

// Set page variables
$page_title = 'Edit Booking';
$page_heading = 'Edit Booking';

// Check if booking ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'Booking ID not provided';
    header('Location: dashboard.php');
    exit;
}

$booking_id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $checkin = mysqli_real_escape_string($conn, $_POST['checkin']);
    $checkout = mysqli_real_escape_string($conn, $_POST['checkout']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $payment_type = mysqli_real_escape_string($conn, $_POST['payment_type']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    
    // Calculate nights
    $checkin_date = new DateTime($checkin);
    $checkout_date = new DateTime($checkout);
    $nights = $checkin_date->diff($checkout_date)->days;
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Get current booking details for room prices
        $booking_query = "SELECT * FROM bookings WHERE id = ?";
        $booking_stmt = mysqli_prepare($conn, $booking_query);
        mysqli_stmt_bind_param($booking_stmt, 'i', $booking_id);
        mysqli_stmt_execute($booking_stmt);
        $booking_result = mysqli_stmt_get_result($booking_stmt);
        $current_booking = mysqli_fetch_assoc($booking_result);
        mysqli_stmt_close($booking_stmt);
        
        // Calculate room total
        $rooms_query = "SELECT br.rooms_booked, br.price_per_night 
            FROM booking_rooms br WHERE br.booking_id = ?";
        $rooms_stmt = mysqli_prepare($conn, $rooms_query);
        mysqli_stmt_bind_param($rooms_stmt, 'i', $booking_id);
        mysqli_stmt_execute($rooms_stmt);
        $rooms_result = mysqli_stmt_get_result($rooms_stmt);
        
        $room_total = 0;
        while ($room = mysqli_fetch_assoc($rooms_result)) {
            $room_total += $room['rooms_booked'] * $room['price_per_night'] * $nights;
        }
        mysqli_stmt_close($rooms_stmt);
        
        // Handle add-ons
        if (isset($_POST['addons']) && is_array($_POST['addons'])) {
            // Delete existing add-ons
            $delete_addons = "DELETE FROM booking_addons WHERE booking_id = ?";
            $delete_stmt = mysqli_prepare($conn, $delete_addons);
            mysqli_stmt_bind_param($delete_stmt, 'i', $booking_id);
            mysqli_stmt_execute($delete_stmt);
            mysqli_stmt_close($delete_stmt);
            
            // Insert new add-ons and calculate total
            $addon_total = 0;
            foreach ($_POST['addons'] as $addon_id => $quantity) {
                $quantity = intval($quantity);
                if ($quantity > 0) {
                    // Get addon details
                    $addon_query = "SELECT price, charge_type FROM addons WHERE id = ?";
                    $addon_stmt = mysqli_prepare($conn, $addon_query);
                    mysqli_stmt_bind_param($addon_stmt, 'i', $addon_id);
                    mysqli_stmt_execute($addon_stmt);
                    $addon_result = mysqli_stmt_get_result($addon_stmt);
                    $addon = mysqli_fetch_assoc($addon_result);
                    mysqli_stmt_close($addon_stmt);
                    
                    if ($addon) {
                        // Calculate price based on charge type
                        if ($addon['charge_type'] === 'per_night') {
                            $price = $addon['price'] * $quantity * $nights;
                        } else {
                            $price = $addon['price'] * $quantity;
                        }
                        
                        $addon_total += $price;
                        
                        // Insert addon
                        $insert_addon = "INSERT INTO booking_addons (booking_id, addon_id, quantity, price) VALUES (?, ?, ?, ?)";
                        $insert_stmt = mysqli_prepare($conn, $insert_addon);
                        mysqli_stmt_bind_param($insert_stmt, 'iiid', $booking_id, $addon_id, $quantity, $price);
                        mysqli_stmt_execute($insert_stmt);
                        mysqli_stmt_close($insert_stmt);
                    }
                }
            }
        } else {
            // No add-ons selected, delete all
            $delete_addons = "DELETE FROM booking_addons WHERE booking_id = ?";
            $delete_stmt = mysqli_prepare($conn, $delete_addons);
            mysqli_stmt_bind_param($delete_stmt, 'i', $booking_id);
            mysqli_stmt_execute($delete_stmt);
            mysqli_stmt_close($delete_stmt);
            $addon_total = 0;
        }
        
        // Calculate new total
        $new_total = $room_total + $addon_total + floatval($current_booking['processing_fee']);
        
        // Calculate balance due based on payment type and amount already paid
        $amount_paid = floatval($current_booking['amount_paid']);
        $balance_due = $new_total - $amount_paid;
        
        // Update booking
        $update_booking = "UPDATE bookings SET 
            checkin = ?, 
            checkout = ?, 
            nights = ?,
            status = ?, 
            payment_type = ?,
            total_amount = ?,
            balance_due = ?
            WHERE id = ?";
        $stmt_booking = mysqli_prepare($conn, $update_booking);
        mysqli_stmt_bind_param($stmt_booking, 'ssissddi', $checkin, $checkout, $nights, $status, $payment_type, $new_total, $balance_due, $booking_id);
        mysqli_stmt_execute($stmt_booking);
        mysqli_stmt_close($stmt_booking);
        
        // Update guest info
        $update_guest = "UPDATE guests SET 
            full_name = ?, 
            email = ?, 
            mobile = ?
            WHERE booking_id = ?";
        $stmt_guest = mysqli_prepare($conn, $update_guest);
        mysqli_stmt_bind_param($stmt_guest, 'sssi', $full_name, $email, $mobile, $booking_id);
        mysqli_stmt_execute($stmt_guest);
        mysqli_stmt_close($stmt_guest);
        
        // Commit transaction
        mysqli_commit($conn);
        
        $_SESSION['success'] = 'Booking updated successfully. New total: ₹' . number_format($new_total, 2);
        
        header('Location: dashboard.php');
        exit;
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error'] = 'Failed to update booking: ' . $e->getMessage();
    }
}

// Fetch booking details
$query = "SELECT b.*, g.full_name, g.email, g.mobile
    FROM bookings b
    LEFT JOIN guests g ON b.id = g.booking_id
    WHERE b.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    $_SESSION['error'] = 'Booking not found';
    header('Location: dashboard.php');
    exit;
}

// Fetch room details
$rooms_query = "SELECT rt.name, br.rooms_booked
    FROM booking_rooms br
    JOIN room_types rt ON br.room_type_id = rt.id
    WHERE br.booking_id = ?";
$rooms_stmt = mysqli_prepare($conn, $rooms_query);
mysqli_stmt_bind_param($rooms_stmt, 'i', $booking_id);
mysqli_stmt_execute($rooms_stmt);
$rooms_result = mysqli_stmt_get_result($rooms_stmt);
$rooms = [];
while ($room = mysqli_fetch_assoc($rooms_result)) {
    $rooms[] = $room;
}

// Fetch current add-ons
$current_addons_query = "SELECT addon_id, quantity FROM booking_addons WHERE booking_id = ?";
$current_addons_stmt = mysqli_prepare($conn, $current_addons_query);
mysqli_stmt_bind_param($current_addons_stmt, 'i', $booking_id);
mysqli_stmt_execute($current_addons_stmt);
$current_addons_result = mysqli_stmt_get_result($current_addons_stmt);
$current_addons = [];
while ($addon = mysqli_fetch_assoc($current_addons_result)) {
    $current_addons[$addon['addon_id']] = $addon['quantity'];
}

// Fetch ALL add-ons (including deactivated)
$addons_query = "SELECT * FROM addons ORDER BY status DESC, name ASC";
$addons_result = mysqli_query($conn, $addons_query);
$all_addons = [];
while ($addon = mysqli_fetch_assoc($addons_result)) {
    $all_addons[] = $addon;
}

mysqli_stmt_close($stmt);
mysqli_stmt_close($rooms_stmt);
mysqli_stmt_close($current_addons_stmt);
?>
<?php include 'includes/header.php'; ?>

    <!-- Edit Booking Content -->
    <section class="dashboard-container">
        <div class="container">
            <div class="dashboard-card" style="max-width: 900px; margin: 0 auto;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                    <a href="dashboard.php" style="background: #f1f5f9; color: #475569; padding: 0.75rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">← Back</a>
                    <h2 style="margin: 0;">✏️ Edit Booking - <?php echo htmlspecialchars($booking['booking_ref']); ?></h2>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #dc2626;">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" style="display: grid; gap: 2rem;">
                    <!-- Guest Information -->
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #10b981;">
                        <h3 style="margin: 0 0 1.5rem 0; color: #10b981; font-size: 1.125rem;">Guest Information</h3>
                        <div style="display: grid; gap: 1rem;">
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #475569;">Full Name</label>
                                <input type="text" name="full_name" value="<?php echo htmlspecialchars($booking['full_name']); ?>" required style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #475569;">Email</label>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($booking['email']); ?>" required style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #475569;">Mobile</label>
                                    <input type="tel" name="mobile" value="<?php echo htmlspecialchars($booking['mobile']); ?>" required style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #3b82f6;">
                        <h3 style="margin: 0 0 1.5rem 0; color: #3b82f6; font-size: 1.125rem;">Booking Details</h3>
                        <div style="display: grid; gap: 1rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #475569;">Check-in Date</label>
                                    <input type="date" name="checkin" value="<?php echo $booking['checkin']; ?>" required style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #475569;">Check-out Date</label>
                                    <input type="date" name="checkout" value="<?php echo $booking['checkout']; ?>" required style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #475569;">Status</label>
                                    <select name="status" required style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
                                        <option value="pending" <?php echo $booking['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="checkedout" <?php echo $booking['status'] === 'checkedout' ? 'selected' : ''; ?>>Checked Out</option>
                                        <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #475569;">Payment Type</label>
                                    <select name="payment_type" required style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
                                        <option value="advance" <?php echo $booking['payment_type'] === 'advance' ? 'selected' : ''; ?>>Advance (50%)</option>
                                        <option value="full" <?php echo $booking['payment_type'] === 'full' ? 'selected' : ''; ?>>Full Payment</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room Details (Read-only) -->
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #f59e0b;">
                        <h3 style="margin: 0 0 1rem 0; color: #f59e0b; font-size: 1.125rem;">Room Details (Read-only)</h3>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                            <?php foreach ($rooms as $room): ?>
                                <span style="background: white; padding: 0.75rem 1.25rem; border-radius: 8px; font-weight: 600; color: #475569; border: 2px solid #e2e8f0;">
                                    <?php echo htmlspecialchars($room['name']); ?> (<?php echo $room['rooms_booked']; ?>)
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <p style="margin-top: 1rem; color: #64748b; font-size: 0.875rem;">Note: To change rooms, please create a new booking.</p>
                    </div>

                    <!-- Add-ons (Editable) -->
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #8b5cf6;">
                        <h3 style="margin: 0 0 1rem 0; color: #8b5cf6; font-size: 1.125rem;">Add-ons (Including Deactivated)</h3>
                        <p style="margin-bottom: 1.5rem; color: #64748b; font-size: 0.875rem;">Select add-ons and specify quantities. Total will be recalculated automatically.</p>
                        <div style="display: grid; gap: 1rem;">
                            <?php foreach ($all_addons as $addon): ?>
                                <div style="background: white; padding: 1rem; border-radius: 8px; border: 2px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #1a202c; margin-bottom: 0.25rem;">
                                            <?php echo htmlspecialchars($addon['name']); ?>
                                            <?php if ($addon['status'] === 'inactive'): ?>
                                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-left: 0.5rem;">Deactivated</span>
                                            <?php endif; ?>
                                        </div>
                                        <div style="color: #64748b; font-size: 0.875rem;">
                                            ₹<?php echo number_format($addon['price'], 2); ?> 
                                            <?php echo $addon['charge_type'] === 'per_night' ? 'per night' : 'one-time'; ?>
                                        </div>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <label style="font-weight: 600; color: #475569; font-size: 0.875rem;">Qty:</label>
                                        <input type="number" name="addons[<?php echo $addon['id']; ?>]" value="<?php echo isset($current_addons[$addon['id']]) ? $current_addons[$addon['id']] : 0; ?>" min="0" max="99" style="width: 80px; padding: 0.5rem; border: 2px solid #cbd5e1; border-radius: 6px; text-align: center; font-weight: 600;">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Payment Summary (Read-only) -->
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #8b0000;">
                        <h3 style="margin: 0 0 1rem 0; color: #8b0000; font-size: 1.125rem;">Current Payment Summary</h3>
                        <div style="display: grid; gap: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: white; border-radius: 8px;">
                                <span style="font-weight: 600; color: #475569;">Current Total:</span>
                                <strong style="color: #1a202c;">₹<?php echo number_format($booking['total_amount'], 2); ?></strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: #dcfce7; border-radius: 8px;">
                                <span style="font-weight: 600; color: #166534;">Amount Paid:</span>
                                <strong style="color: #166534;">₹<?php echo number_format($booking['amount_paid'], 2); ?></strong>
                            </div>
                            <?php if ($booking['balance_due'] > 0): ?>
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: #fee2e2; border-radius: 8px;">
                                <span style="font-weight: 600; color: #991b1b;">Current Balance Due:</span>
                                <strong style="color: #991b1b;">₹<?php echo number_format($booking['balance_due'], 2); ?></strong>
                            </div>
                            <?php endif; ?>
                            <p style="margin-top: 0.5rem; color: #64748b; font-size: 0.875rem; font-style: italic;">
                                Note: Total will be recalculated based on add-ons when you save. Amount paid remains the same.
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 1rem; padding-top: 1rem; border-top: 2px solid #e2e8f0;">
                        <button type="submit" style="flex: 1; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1rem 2rem; border: none; border-radius: 10px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">💾 Save Changes</button>
                        <a href="dashboard.php" style="flex: 1; background: #f1f5f9; color: #475569; padding: 1rem 2rem; border-radius: 10px; font-weight: 600; font-size: 1rem; text-align: center; text-decoration: none; transition: all 0.3s ease;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
