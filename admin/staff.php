<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Only main admin can access this page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_role'] !== 'main') {
    header('Location: login.php');
    exit();
}

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        // Add new staff
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        $password = $_POST['password'];
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        
        // Validate
        if (empty($username) || empty($password)) {
            $message = 'Username and password are required';
            $message_type = 'error';
        } elseif (strlen($password) < 6) {
            $message = 'Password must be at least 6 characters';
            $message_type = 'error';
        } else {
            // Check if username exists
            $check_query = "SELECT id FROM admins WHERE username = '$username'";
            $check_result = mysqli_query($conn, $check_query);
            
            if (mysqli_num_rows($check_result) > 0) {
                $message = 'Username already exists';
                $message_type = 'error';
            } else {
                // Hash password with bcrypt
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                
                // Insert new staff
                $insert_query = "INSERT INTO admins (username, password_hash, role) 
                                VALUES ('$username', '$password_hash', '$role')";
                
                if (mysqli_query($conn, $insert_query)) {
                    $message = 'Staff member added successfully';
                    $message_type = 'success';
                } else {
                    $message = 'Error adding staff member';
                    $message_type = 'error';
                }
            }
        }
    } elseif ($action === 'edit') {
        // Edit existing staff
        $staff_id = intval($_POST['staff_id']);
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $new_password = $_POST['new_password'] ?? '';
        
        // Validate
        if (empty($username)) {
            $message = 'Username is required';
            $message_type = 'error';
        } else {
            // Check if username exists for other users
            $check_query = "SELECT id FROM admins WHERE username = '$username' AND id != $staff_id";
            $check_result = mysqli_query($conn, $check_query);
            
            if (mysqli_num_rows($check_result) > 0) {
                $message = 'Username already exists';
                $message_type = 'error';
            } else {
                // Update staff
                if (!empty($new_password)) {
                    if (strlen($new_password) < 6) {
                        $message = 'Password must be at least 6 characters';
                        $message_type = 'error';
                    } else {
                        // Update with new password
                        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
                        $update_query = "UPDATE admins SET username = '$username', password_hash = '$password_hash', 
                                        role = '$role' WHERE id = $staff_id";
                    }
                } else {
                    // Update without changing password
                    $update_query = "UPDATE admins SET username = '$username', role = '$role' WHERE id = $staff_id";
                }
                
                if (isset($update_query) && mysqli_query($conn, $update_query)) {
                    $message = 'Staff member updated successfully';
                    $message_type = 'success';
                } elseif (!isset($update_query)) {
                    // Password validation failed, message already set
                } else {
                    $message = 'Error updating staff member';
                    $message_type = 'error';
                }
            }
        }
    } elseif ($action === 'delete') {
        // Delete staff
        $staff_id = intval($_POST['staff_id']);
        
        // Prevent deleting yourself
        if ($staff_id == $_SESSION['admin_id']) {
            $message = 'You cannot delete your own account';
            $message_type = 'error';
        } else {
            $delete_query = "DELETE FROM admins WHERE id = $staff_id";
            
            if (mysqli_query($conn, $delete_query)) {
                $message = 'Staff member deleted successfully';
                $message_type = 'success';
            } else {
                $message = 'Error deleting staff member';
                $message_type = 'error';
            }
        }
    }
}

// Fetch all staff members
$staff_query = "SELECT id, username, role FROM admins ORDER BY role DESC, username ASC";
$staff_result = mysqli_query($conn, $staff_query);

include 'includes/header.php';
?>

<div class="admin-container">
    <div class="page-header-admin">
        <h1>👥 Staff Management</h1>
        <p>Manage admin and staff accounts</p>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Add New Staff Button -->
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddModal()">➕ Add New Staff</button>
    </div>

    <!-- Staff Table -->
    <div style="overflow-x: auto; border-radius: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08); margin-top: 2rem;">
        <table style="width: 100%; border-collapse: collapse; background: white; border: 2px solid #e2e8f0;">
            <thead>
                <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">ID</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Username</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Role</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($staff = mysqli_fetch_assoc($staff_result)): ?>
                    <tr style="transition: background 0.2s ease;" onmouseover="this.style.background='linear-gradient(135deg, #fef2f2 0%, #fee2e2 50%, #fef2f2 100%)'" onmouseout="this.style.background='white'">
                        <td style="padding: 1rem; border: 1px solid #e2e8f0;"><?php echo $staff['id']; ?></td>
                        <td style="padding: 1rem; border: 1px solid #e2e8f0; font-weight: 500;"><?php echo htmlspecialchars($staff['username']); ?></td>
                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                            <?php
                            $role_style = $staff['role'] === 'main' 
                                ? 'background: linear-gradient(135deg, #8b0000 0%, #6b0000 100%); color: white;' 
                                : 'background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white;';
                            ?>
                            <span style="<?php echo $role_style; ?> padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">
                                <?php echo $staff['role'] === 'main' ? '👑 Main Admin' : '👤 Staff'; ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);" 
                                        onclick='openEditModal(<?php echo json_encode($staff); ?>)'
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)'" 
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.3)'">
                                    ✏️ Edit
                                </button>
                                <?php if ($staff['id'] != $_SESSION['admin_id']): ?>
                                    <button style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);" 
                                            onclick="confirmDelete(<?php echo $staff['id']; ?>, '<?php echo htmlspecialchars($staff['username']); ?>')"
                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.4)'" 
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(239, 68, 68, 0.3)'">
                                        🗑️ Delete
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Staff Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h2>➕ Add New Staff</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="add">
            
            <div class="form-group">
                <label for="add_username">Username *</label>
                <input type="text" id="add_username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="add_password">Password * (min 6 characters)</label>
                <input type="password" id="add_password" name="password" required minlength="6">
            </div>
            
            <div class="form-group">
                <label for="add_role">Role *</label>
                <select id="add_role" name="role" required>
                    <option value="staff">Staff Admin</option>
                    <option value="main">Main Admin</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Staff</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Staff Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>✏️ Edit Staff</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_staff_id" name="staff_id">
            
            <div class="form-group">
                <label for="edit_username">Username *</label>
                <input type="text" id="edit_username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="edit_new_password">New Password (leave empty to keep current)</label>
                <input type="password" id="edit_new_password" name="new_password" minlength="6">
                <small>Only fill this if you want to change the password</small>
            </div>
            
            <div class="form-group">
                <label for="edit_role">Role *</label>
                <select id="edit_role" name="role" required>
                    <option value="staff">Staff Admin</option>
                    <option value="main">Main Admin</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Staff</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h2>⚠️ Confirm Delete</h2>
        <p>Are you sure you want to delete <strong id="delete_username"></strong>?</p>
        <p style="color: #ef4444;">This action cannot be undone.</p>
        <form method="POST" action="">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" id="delete_staff_id" name="staff_id">
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
// Add Modal
function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}

// Edit Modal
function openEditModal(staff) {
    document.getElementById('edit_staff_id').value = staff.id;
    document.getElementById('edit_username').value = staff.username;
    document.getElementById('edit_role').value = staff.role;
    document.getElementById('edit_new_password').value = '';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Delete Modal
function confirmDelete(id, username) {
    document.getElementById('delete_staff_id').value = id;
    document.getElementById('delete_username').textContent = username;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
