<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Set page variables
$page_title = 'Menu Management';
$page_heading = 'Breakfast Menu Management';

// Handle Add New Menu Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $category_id = intval($_POST['category_id']);
    $item_name = trim($_POST['item_name']);
    
    if (!empty($item_name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO menu_items (category_id, name, status) VALUES (?, ?, 'active')");
            $stmt->execute([$category_id, $item_name]);
            $_SESSION['success_message'] = '✅ Menu item added successfully!';
        } catch (Exception $e) {
            $_SESSION['error_message'] = '❌ Error adding menu item: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = '❌ Item name cannot be empty.';
    }
    header('Location: menu.php');
    exit;
}

// Handle Update Menu Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item'])) {
    $item_id = intval($_POST['item_id']);
    $item_name = trim($_POST['item_name']);
    $category_id = intval($_POST['category_id']);
    
    if (!empty($item_name)) {
        try {
            $stmt = $pdo->prepare("UPDATE menu_items SET name = ?, category_id = ? WHERE id = ?");
            $stmt->execute([$item_name, $category_id, $item_id]);
            $_SESSION['success_message'] = '✅ Menu item updated successfully!';
        } catch (Exception $e) {
            $_SESSION['error_message'] = '❌ Error updating menu item: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = '❌ Item name cannot be empty.';
    }
    header('Location: menu.php');
    exit;
}

// Handle Delete Menu Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $item_id = intval($_POST['item_id']);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
        $stmt->execute([$item_id]);
        $_SESSION['success_message'] = '✅ Menu item deleted successfully!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = '❌ Error deleting menu item: ' . $e->getMessage();
    }
    header('Location: menu.php');
    exit;
}

// Handle Toggle Status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    $item_id = intval($_POST['item_id']);
    $new_status = $_POST['new_status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE menu_items SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $item_id]);
        $_SESSION['success_message'] = '✅ Menu item status updated!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = '❌ Error updating status: ' . $e->getMessage();
    }
    header('Location: menu.php');
    exit;
}

// Get messages from session and clear them
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Fetch all categories
$categories = [];
try {
    $stmt = $pdo->query("SELECT id, name FROM menu_categories WHERE status = 'active' ORDER BY id");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = '❌ Error fetching categories.';
}

// Fetch all menu items with category names
$menu_items = [];
try {
    $stmt = $pdo->query("
        SELECT mi.id, mi.name, mi.status, mi.category_id, mc.name as category_name
        FROM menu_items mi
        JOIN menu_categories mc ON mi.category_id = mc.id
        ORDER BY mc.id, mi.id
    ");
    $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = '❌ Error fetching menu items.';
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

            <!-- Add New Menu Item -->
            <section class="section-card">
                <h2>➕ Add New Menu Item</h2>
                <form method="POST" class="form-grid">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" name="item_name" id="item_name" required 
                               placeholder="e.g., Idli (2 pieces)">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="add_item" class="btn btn-primary">Add Item</button>
                    </div>
                </form>
            </section>

            <!-- Current Menu Items -->
            <section class="section-card">
                <h2>📋 Current Menu Items</h2>
                
                <?php if (empty($menu_items)): ?>
                    <p>No menu items found.</p>
                <?php else: ?>
                    <div style="overflow-x: auto; border-radius: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);">
                        <table style="width: 100%; border-collapse: collapse; background: white; border: 2px solid #e2e8f0;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">ID</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Category</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Item Name</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Status</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($menu_items as $item): ?>
                                    <tr style="transition: background 0.2s ease;" onmouseover="this.style.background='linear-gradient(135deg, #fef2f2 0%, #fee2e2 50%, #fef2f2 100%)'" onmouseout="this.style.background='white'">
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;"><?php echo $item['id']; ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($item['category_name']); ?></td>
                                        <td style="padding: 1rem; font-weight: 500; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <?php
                                            $status_style = $item['status'] === 'active' 
                                                ? 'background: #dcfce7; color: #166534;' 
                                                : 'background: #fee2e2; color: #991b1b;';
                                            ?>
                                            <span style="<?php echo $status_style; ?> padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">
                                                <?php echo ucfirst($item['status']); ?>
                                            </span>
                                        </td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                                <!-- Edit Button -->
                                                <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);" 
                                                        onclick="editItem(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>', <?php echo $item['category_id']; ?>)"
                                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)'" 
                                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.3)'">
                                                    Edit
                                                </button>
                                                
                                                <!-- Toggle Status -->
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                    <input type="hidden" name="new_status" 
                                                           value="<?php echo $item['status'] === 'active' ? 'inactive' : 'active'; ?>">
                                                    <button type="submit" name="toggle_status" 
                                                            style="background: linear-gradient(135deg, <?php echo $item['status'] === 'active' ? '#f59e0b 0%, #d97706 100%' : '#10b981 0%, #059669 100%'; ?>); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);"
                                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.3)'" 
                                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.2)'">
                                                        <?php echo $item['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>
                                                    </button>
                                                </form>
                                                
                                                <!-- Delete Button -->
                                                <form method="POST" style="display: inline;" 
                                                      onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                    <button type="submit" name="delete_item" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);"
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

            <!-- Edit Modal (Hidden by default) -->
            <div id="editModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close" onclick="closeEditModal()">&times;</span>
                    <h2>Edit Menu Item</h2>
                    <form method="POST">
                        <input type="hidden" name="item_id" id="edit_item_id">
                        
                        <div class="form-group">
                            <label for="edit_category_id">Category</label>
                            <select name="category_id" id="edit_category_id" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_item_name">Item Name</label>
                            <input type="text" name="item_name" id="edit_item_name" required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="update_item" class="btn btn-primary">Update Item</button>
                            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <script>
        function editItem(id, name, categoryId) {
            document.getElementById('edit_item_id').value = id;
            document.getElementById('edit_item_name').value = name;
            document.getElementById('edit_category_id').value = categoryId;
            document.getElementById('editModal').style.display = 'block';
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

        // Auto-hide success and error messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.querySelector('.success-message');
            const errorMessage = document.querySelector('.error-message');
            
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.transition = 'opacity 0.5s ease';
                    successMessage.style.opacity = '0';
                    setTimeout(function() {
                        successMessage.style.display = 'none';
                    }, 500);
                }, 5000); // Hide after 5 seconds
            }
            
            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.style.transition = 'opacity 0.5s ease';
                    errorMessage.style.opacity = '0';
                    setTimeout(function() {
                        errorMessage.style.display = 'none';
                    }, 500);
                }, 5000); // Hide after 5 seconds
            }
        });
    </script>

<?php include 'includes/footer.php'; ?>
