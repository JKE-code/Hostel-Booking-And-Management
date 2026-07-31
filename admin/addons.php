<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Check if PDO connection is available
if ($pdo === null) {
    die("Database connection failed. Please check your database configuration.");
}

// Set page variables
$page_title = 'Add-ons Management';
$page_heading = 'Add-ons & Services Management';

// Handle Create Add-on
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_addon'])) {
    $name = trim($_POST['addon_name']);
    $price = floatval($_POST['addon_price']);
    $charge_type = $_POST['charge_type'];
    
    if (!empty($name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO addons (name, price, charge_type, status) VALUES (?, ?, ?, 'active')");
            $stmt->execute([$name, $price, $charge_type]);
            $_SESSION['success_message'] = '✅ Add-on created successfully!';
        } catch (Exception $e) {
            $_SESSION['error_message'] = '❌ Error creating add-on: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = '❌ Add-on name cannot be empty.';
    }
    header('Location: addons.php');
    exit;
}

// Handle Update Add-on
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_addon'])) {
    $addon_id = intval($_POST['addon_id']);
    $name = trim($_POST['addon_name']);
    $price = floatval($_POST['addon_price']);
    $charge_type = $_POST['charge_type'];
    
    if (!empty($name)) {
        try {
            $stmt = $pdo->prepare("UPDATE addons SET name = ?, price = ?, charge_type = ? WHERE id = ?");
            $stmt->execute([$name, $price, $charge_type, $addon_id]);
            $_SESSION['success_message'] = '✅ Add-on updated successfully!';
        } catch (Exception $e) {
            $_SESSION['error_message'] = '❌ Error updating add-on: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = '❌ Add-on name cannot be empty.';
    }
    header('Location: addons.php');
    exit;
}

// Handle Toggle Pin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_pin'])) {
    $addon_id = intval($_POST['addon_id']);
    $new_pin_status = intval($_POST['new_pin_status']);
    
    try {
        $stmt = $pdo->prepare("UPDATE addons SET is_pinned = ? WHERE id = ?");
        $stmt->execute([$new_pin_status, $addon_id]);
        $_SESSION['success_message'] = '✅ Add-on pin status updated!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = '❌ Error updating pin status: ' . $e->getMessage();
    }
    header('Location: addons.php');
    exit;
}

// Handle Toggle Status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    $addon_id = intval($_POST['addon_id']);
    $new_status = $_POST['new_status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE addons SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $addon_id]);
        $_SESSION['success_message'] = '✅ Add-on status updated!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = '❌ Error updating status: ' . $e->getMessage();
    }
    header('Location: addons.php');
    exit;
}

// Handle Delete Add-on
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_addon'])) {
    $addon_id = intval($_POST['addon_id']);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM addons WHERE id = ?");
        $stmt->execute([$addon_id]);
        $_SESSION['success_message'] = '✅ Add-on deleted successfully!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = '❌ Error deleting add-on: ' . $e->getMessage();
    }
    header('Location: addons.php');
    exit;
}

// Get messages from session and clear them
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Fetch all addons (pinned first if column exists, then by ID)
$addons = [];
$has_pinned_column = false;
$has_status_column = false;

try {
    // Check if required columns exist
    $check_pinned = $pdo->query("SHOW COLUMNS FROM addons LIKE 'is_pinned'");
    $has_pinned_column = $check_pinned->rowCount() > 0;
    
    $check_status = $pdo->query("SHOW COLUMNS FROM addons LIKE 'status'");
    $has_status_column = $check_status->rowCount() > 0;
    
    // Build query based on available columns
    if ($has_status_column && $has_pinned_column) {
        $stmt = $pdo->query("SELECT id, name, price, charge_type, status, COALESCE(is_pinned, 0) as is_pinned FROM addons ORDER BY is_pinned DESC, id ASC");
    } elseif ($has_status_column) {
        $stmt = $pdo->query("SELECT id, name, price, charge_type, status, 0 as is_pinned FROM addons ORDER BY id ASC");
    } else {
        // If status column doesn't exist, use 'active' as default
        $stmt = $pdo->query("SELECT id, name, price, charge_type, 'active' as status, 0 as is_pinned FROM addons ORDER BY id ASC");
    }
    
    $addons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Show warning if columns are missing
    if (!$has_status_column || !$has_pinned_column) {
        $error_message = '⚠️ Database needs updating. Please run the SQL file: admin/update_addons_table.sql';
    }
} catch (Exception $e) {
    $error_message = '❌ Error fetching add-ons: ' . $e->getMessage();
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

            <!-- Create Add-on Section -->
            <section class="section-card">
                <h2>➕ Create New Add-on</h2>
                <form method="POST" action="addons.php" class="addon-create-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="addon_name">Add-on Name <span class="required">*</span></label>
                            <input type="text" id="addon_name" name="addon_name" required placeholder="e.g., Extra Bed">
                        </div>
                        
                        <div class="form-group">
                            <label for="addon_price">Price (₹) <span class="required">*</span></label>
                            <input type="number" id="addon_price" name="addon_price" required min="0" step="1" placeholder="500">
                        </div>
                        
                        <div class="form-group">
                            <label for="charge_type">Charge Type <span class="required">*</span></label>
                            <select id="charge_type" name="charge_type" required>
                                <option value="per_night">Per Night</option>
                                <option value="one_time">One Time</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="create_addon" class="btn-primary">Create Add-on</button>
                        </div>
                    </div>
                </form>
            </section>

            <!-- Current Add-ons -->
            <section class="section-card">
                <h2>🎁 Current Add-ons</h2>
                
                <?php if (empty($addons)): ?>
                    <p>No add-ons found.</p>
                <?php else: ?>
                    <div style="overflow-x: auto; border-radius: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);">
                        <table style="width: 100%; border-collapse: collapse; background: white; border: 2px solid #e2e8f0;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">ID</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Name</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Price</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Charge Type</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Status</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($addons as $addon): ?>
                                    <tr style="transition: background 0.2s ease;" onmouseover="this.style.background='linear-gradient(135deg, #fef2f2 0%, #fee2e2 50%, #fef2f2 100%)'" onmouseout="this.style.background='white'">
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;"><?php echo $addon['id']; ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0; font-weight: 600;"><?php echo htmlspecialchars($addon['name']); ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0; font-weight: 500;">₹<?php echo number_format($addon['price'], 0); ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;"><?php echo $addon['charge_type'] == 'per_night' ? 'Per Night' : 'One Time'; ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <?php
                                            $status_style = $addon['status'] === 'active' 
                                                ? 'background: #dcfce7; color: #166534;' 
                                                : 'background: #fee2e2; color: #991b1b;';
                                            ?>
                                            <span style="<?php echo $status_style; ?> padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">
                                                <?php echo ucfirst($addon['status']); ?>
                                            </span>
                                        </td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                                <?php if ($has_pinned_column): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="addon_id" value="<?php echo $addon['id']; ?>">
                                                    <input type="hidden" name="new_pin_status" value="<?php echo $addon['is_pinned'] ? 0 : 1; ?>">
                                                    <button type="submit" name="toggle_pin" 
                                                            style="background: linear-gradient(135deg, <?php echo $addon['is_pinned'] ? '#6b7280 0%, #4b5563 100%' : '#9ca3af 0%, #6b7280 100%'; ?>); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);"
                                                            title="<?php echo $addon['is_pinned'] ? 'Unpin from top' : 'Pin to top'; ?>"
                                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.3)'" 
                                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.2)'">
                                                        <?php echo $addon['is_pinned'] ? '📌 Unpin' : '📍 Pin'; ?>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                                
                                                <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);" 
                                                        onclick="editAddon(<?php echo $addon['id']; ?>, '<?php echo htmlspecialchars($addon['name'], ENT_QUOTES); ?>', <?php echo $addon['price']; ?>, '<?php echo $addon['charge_type']; ?>')"
                                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)'" 
                                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.3)'">
                                                    Edit
                                                </button>
                                                
                                                <?php if ($has_status_column): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="addon_id" value="<?php echo $addon['id']; ?>">
                                                    <input type="hidden" name="new_status" 
                                                           value="<?php echo $addon['status'] === 'active' ? 'inactive' : 'active'; ?>">
                                                    <button type="submit" name="toggle_status" 
                                                            style="background: linear-gradient(135deg, <?php echo $addon['status'] === 'active' ? '#f59e0b 0%, #d97706 100%' : '#10b981 0%, #059669 100%'; ?>); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);"
                                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.3)'" 
                                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.2)'">
                                                        <?php echo $addon['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                                
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this add-on?');">
                                                    <input type="hidden" name="addon_id" value="<?php echo $addon['id']; ?>">
                                                    <button type="submit" name="delete_addon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);"
                                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.4)'" 
                                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(239, 68, 68, 0.3)'">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Edit Modal -->
            <div id="editModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Edit Add-on</h2>
                        <span class="modal-close" onclick="closeEditModal()">&times;</span>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="addons.php">
                            <input type="hidden" name="addon_id" id="edit_addon_id">
                            
                            <div class="form-group">
                                <label for="edit_addon_name">Add-on Name <span class="required">*</span></label>
                                <input type="text" name="addon_name" id="edit_addon_name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="edit_addon_price">Price (₹) <span class="required">*</span></label>
                                <input type="number" name="addon_price" id="edit_addon_price" required min="0" step="1">
                            </div>
                            
                            <div class="form-group">
                                <label for="edit_charge_type">Charge Type <span class="required">*</span></label>
                                <select name="charge_type" id="edit_charge_type" required>
                                    <option value="per_night">Per Night</option>
                                    <option value="one_time">One Time</option>
                                </select>
                            </div>
                            
                            <div class="form-group" style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #e5e7eb;">
                                <button type="submit" name="update_addon" class="btn btn-primary">Update Add-on</button>
                                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        function editAddon(id, name, price, chargeType) {
            document.getElementById('edit_addon_id').value = id;
            document.getElementById('edit_addon_name').value = name;
            document.getElementById('edit_addon_price').value = price;
            document.getElementById('edit_charge_type').value = chargeType;
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>

<?php include 'includes/footer.php'; ?>
