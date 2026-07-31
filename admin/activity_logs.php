<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../config/db.php';
require_once 'includes/activity_logger.php';

// Set page variables
$page_title = 'Activity Logs';
$page_heading = 'Booking Activity Logs';

// Get filters
$filter_admin = $_GET['admin_id'] ?? null;
$filter_date_from = $_GET['date_from'] ?? null;
$filter_date_to = $_GET['date_to'] ?? null;
$filter_action = $_GET['action'] ?? null;

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 50;
$offset = ($page - 1) * $per_page;

// Build filters array
$filters = [];
if ($filter_admin) $filters['admin_id'] = $filter_admin;
if ($filter_date_from) $filters['date_from'] = $filter_date_from;
if ($filter_date_to) $filters['date_to'] = $filter_date_to;
if ($filter_action) $filters['action_type'] = $filter_action;

// Get logs
$logs = getAllActivityLogs($conn, $filters, $per_page, $offset);

// Get all admins for filter dropdown
$admins_query = "SELECT id, username FROM admins ORDER BY username";
$admins_result = mysqli_query($conn, $admins_query);
$admins = [];
while ($admin = mysqli_fetch_assoc($admins_result)) {
    $admins[] = $admin;
}
?>
<?php include 'includes/header.php'; ?>

<main class="dashboard-main">
    <div class="container">
        <!-- Filters -->
        <section class="filters-section" style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="margin-top: 0;">🔍 Filter Logs</h3>
            <form method="GET" action="activity_logs.php" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Admin:</label>
                    <select name="admin_id" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">All Admins</option>
                        <?php foreach ($admins as $admin): ?>
                            <option value="<?php echo $admin['id']; ?>" <?php echo $filter_admin == $admin['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($admin['username']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">From Date:</label>
                    <input type="date" name="date_from" value="<?php echo htmlspecialchars($filter_date_from ?? ''); ?>" 
                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">To Date:</label>
                    <input type="date" name="date_to" value="<?php echo htmlspecialchars($filter_date_to ?? ''); ?>" 
                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Action:</label>
                    <input type="text" name="action" value="<?php echo htmlspecialchars($filter_action ?? ''); ?>" 
                           placeholder="e.g., Status, Payment, Add-on"
                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div style="display: flex; align-items: flex-end; gap: 10px;">
                    <button type="submit" style="padding: 8px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Apply Filters
                    </button>
                    <a href="activity_logs.php" style="padding: 8px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">
                        Clear
                    </a>
                </div>
            </form>
        </section>

        <!-- Activity Logs Table -->
        <section class="logs-section" style="background: white; padding: 20px; border-radius: 8px;">
            <h3 style="margin-top: 0;">📋 Activity Logs</h3>
            
            <?php if (empty($logs)): ?>
                <p style="text-align: center; color: #6c757d; padding: 40px;">No activity logs found.</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 12px; text-align: left;">Timestamp</th>
                                <th style="padding: 12px; text-align: left;">Booking Ref</th>
                                <th style="padding: 12px; text-align: left;">Guest</th>
                                <th style="padding: 12px; text-align: left;">Action</th>
                                <th style="padding: 12px; text-align: left;">Description</th>
                                <th style="padding: 12px; text-align: left;">Admin</th>
                                <th style="padding: 12px; text-align: left;">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 12px; font-size: 0.9em;">
                                        <?php echo date('d M Y, H:i', strtotime($log['timestamp'])); ?>
                                    </td>
                                    <td style="padding: 12px;">
                                        <a href="dashboard.php?booking_id=<?php echo $log['booking_id']; ?>" 
                                           style="color: #007bff; text-decoration: none;">
                                            <?php echo htmlspecialchars($log['booking_ref'] ?? 'N/A'); ?>
                                        </a>
                                    </td>
                                    <td style="padding: 12px;">
                                        <?php echo htmlspecialchars($log['guest_name'] ?? 'N/A'); ?>
                                    </td>
                                    <td style="padding: 12px;">
                                        <span style="background: #e7f3ff; color: #004085; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;">
                                            <?php echo htmlspecialchars($log['action']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px; max-width: 300px;">
                                        <!-- Action details are in the action column -->
                                    </td>
                                    <td style="padding: 12px;">
                                        <?php echo htmlspecialchars($log['admin_username'] ?? 'System'); ?>
                                    </td>
                                    <td style="padding: 12px; font-size: 0.85em; color: #666;">
                                        N/A
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div style="margin-top: 20px; text-align: center;">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?><?php echo $filter_admin ? '&admin_id=' . $filter_admin : ''; ?><?php echo $filter_date_from ? '&date_from=' . $filter_date_from : ''; ?><?php echo $filter_date_to ? '&date_to=' . $filter_date_to : ''; ?><?php echo $filter_action ? '&action=' . $filter_action : ''; ?>" 
                           style="padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 0 5px;">
                            ← Previous
                        </a>
                    <?php endif; ?>
                    
                    <span style="padding: 8px 16px;">Page <?php echo $page; ?></span>
                    
                    <?php if (count($logs) == $per_page): ?>
                        <a href="?page=<?php echo $page + 1; ?><?php echo $filter_admin ? '&admin_id=' . $filter_admin : ''; ?><?php echo $filter_date_from ? '&date_from=' . $filter_date_from : ''; ?><?php echo $filter_date_to ? '&date_to=' . $filter_date_to : ''; ?><?php echo $filter_action ? '&action=' . $filter_action : ''; ?>" 
                           style="padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 0 5px;">
                            Next →
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
