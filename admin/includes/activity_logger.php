<?php
/**
 * Activity Logger - Logs all admin actions on bookings
 * Works with existing booking_activity_log table structure
 * 
 * @param mysqli $conn Database connection
 * @param int $booking_id The booking ID
 * @param string $action Action performed (e.g., "Status Changed", "Add-on Added")
 * @param int|null $admin_id Admin who performed the action
 * @return bool Success status
 */
function logBookingActivity($conn, $booking_id, $action, $description = null, $old_value = null, $new_value = null, $admin_id = null, $ip_address = null) {
    // Get admin ID from session if not provided
    if ($admin_id === null && isset($_SESSION['admin_id'])) {
        $admin_id = $_SESSION['admin_id'];
    }
    
    // Combine description, old_value, new_value into action string for current schema
    $action_text = $action;
    if ($description) {
        $action_text .= ': ' . $description;
    }
    if ($old_value && $new_value) {
        $action_text .= ' (Changed from "' . $old_value . '" to "' . $new_value . '")';
    } elseif ($new_value) {
        $action_text .= ' (Set to "' . $new_value . '")';
    }
    
    $query = "INSERT INTO booking_activity_log (booking_id, action, performed_by_admin_id) VALUES (?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        error_log("Failed to prepare activity log statement: " . mysqli_error($conn));
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, 'isi', $booking_id, $action_text, $admin_id);
    
    $result = mysqli_stmt_execute($stmt);
    
    if (!$result) {
        error_log("Failed to log activity: " . mysqli_error($conn));
    }
    
    mysqli_stmt_close($stmt);
    
    return $result;
}

/**
 * Get activity logs for a specific booking
 * 
 * @param mysqli $conn Database connection
 * @param int $booking_id The booking ID
 * @param int|null $limit Number of logs to retrieve (null for all)
 * @return array Array of activity logs
 */
function getBookingActivityLogs($conn, $booking_id, $limit = null) {
    $query = "
        SELECT 
            bal.*,
            a.username as admin_username
        FROM booking_activity_log bal
        LEFT JOIN admins a ON bal.performed_by_admin_id = a.id
        WHERE bal.booking_id = ?
        ORDER BY bal.timestamp DESC
    ";
    
    if ($limit !== null) {
        $query .= " LIMIT ?";
    }
    
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        error_log("Failed to prepare statement: " . mysqli_error($conn));
        return [];
    }
    
    if ($limit !== null) {
        mysqli_stmt_bind_param($stmt, 'ii', $booking_id, $limit);
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $booking_id);
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Failed to execute statement: " . mysqli_error($conn));
        mysqli_stmt_close($stmt);
        return [];
    }
    
    $result = mysqli_stmt_get_result($stmt);
    
    $logs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $logs[] = $row;
    }
    
    mysqli_stmt_close($stmt);
    
    return $logs;
}

/**
 * Get all activity logs with optional filters
 * 
 * @param mysqli $conn Database connection
 * @param array $filters Optional filters (admin_id, date_from, date_to, action_type)
 * @param int $limit Number of logs to retrieve
 * @param int $offset Offset for pagination
 * @return array Array of activity logs
 */
function getAllActivityLogs($conn, $filters = [], $limit = 50, $offset = 0) {
    $where_clauses = [];
    $params = [];
    $types = '';
    
    if (isset($filters['admin_id'])) {
        $where_clauses[] = "bal.performed_by_admin_id = ?";
        $params[] = $filters['admin_id'];
        $types .= 'i';
    }
    
    if (isset($filters['date_from'])) {
        $where_clauses[] = "DATE(bal.timestamp) >= ?";
        $params[] = $filters['date_from'];
        $types .= 's';
    }
    
    if (isset($filters['date_to'])) {
        $where_clauses[] = "DATE(bal.timestamp) <= ?";
        $params[] = $filters['date_to'];
        $types .= 's';
    }
    
    if (isset($filters['action_type'])) {
        $where_clauses[] = "bal.action LIKE ?";
        $params[] = '%' . $filters['action_type'] . '%';
        $types .= 's';
    }
    
    $where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
    
    $query = "
        SELECT 
            bal.*,
            b.booking_ref,
            a.username as admin_username,
            g.full_name as guest_name
        FROM booking_activity_log bal
        LEFT JOIN bookings b ON bal.booking_id = b.id
        LEFT JOIN admins a ON bal.performed_by_admin_id = a.id
        LEFT JOIN guests g ON b.id = g.booking_id
        $where_sql
        ORDER BY bal.timestamp DESC
        LIMIT ? OFFSET ?
    ";
    
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';
    
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        error_log("Failed to prepare statement: " . mysqli_error($conn));
        return [];
    }
    
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Failed to execute statement: " . mysqli_error($conn));
        mysqli_stmt_close($stmt);
        return [];
    }
    
    $result = mysqli_stmt_get_result($stmt);
    
    $logs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $logs[] = $row;
    }
    
    mysqli_stmt_close($stmt);
    
    return $logs;
}
