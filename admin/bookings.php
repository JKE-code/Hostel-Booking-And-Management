<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Set page variables
$page_title = 'Bookings';
$page_heading = 'Bookings Management';

// Get filter parameters
$filter_payment = isset($_GET['payment']) ? $_GET['payment'] : 'all';
$filter_room = isset($_GET['room']) ? $_GET['room'] : 'all';
$filter_date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$filter_date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Build query with filters
$query = "
    SELECT 
        b.id,
        b.booking_ref,
        g.full_name,
        b.checkin,
        b.checkout,
        DATEDIFF(b.checkout, b.checkin) as nights,
        b.total_amount,
        b.payment_type,
        b.status,
        GROUP_CONCAT(CONCAT(rt.name, ' (', br.rooms_booked, ')') SEPARATOR ', ') as room_details,
        (SELECT SUM(adults) FROM guests WHERE booking_id = b.id) as total_adults,
        (SELECT SUM(children) FROM guests WHERE booking_id = b.id) as total_children
    FROM bookings b
    LEFT JOIN guests g ON b.id = g.booking_id
    LEFT JOIN booking_rooms br ON b.id = br.booking_id
    LEFT JOIN room_types rt ON br.room_type_id = rt.id
    WHERE 1=1
";

// Apply payment filter
if ($filter_payment != 'all') {
    if ($filter_payment == 'complete') {
        $query .= " AND b.payment_type = 'full'";
    } elseif ($filter_payment == 'advance') {
        $query .= " AND b.payment_type = 'advance'";
    } elseif ($filter_payment == 'pending') {
        $query .= " AND b.balance_due > 0";
    }
}

// Apply room type filter
if ($filter_room != 'all') {
    $room_filter = mysqli_real_escape_string($conn, $filter_room);
    $query .= " AND EXISTS (
        SELECT 1 FROM booking_rooms br2 
        JOIN room_types rt2 ON br2.room_type_id = rt2.id 
        WHERE br2.booking_id = b.id 
        AND LOWER(rt2.name) LIKE '%$room_filter%'
    )";
}

// Apply date range filter
if (!empty($filter_date_from)) {
    $date_from = mysqli_real_escape_string($conn, $filter_date_from);
    $query .= " AND b.checkin >= '$date_from'";
}

if (!empty($filter_date_to)) {
    $date_to = mysqli_real_escape_string($conn, $filter_date_to);
    $query .= " AND b.checkout <= '$date_to'";
}

$query .= " GROUP BY b.id ORDER BY b.created_at DESC";

$result = mysqli_query($conn, $query);
$bookings = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
}
?>
<?php include 'includes/header.php'; ?>

    <!-- Dashboard Content -->
    <section class="dashboard-container">
        <div class="container">
            <div class="dashboard-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h2 style="margin-bottom: 0;">📅 All Bookings</h2>
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 0.75rem 1.5rem; border-radius: 50px; border: 2px solid #e2e8f0;">
                        <span style="font-weight: 600; color: #64748b; font-size: 0.875rem;">Total: </span>
                        <span style="font-weight: 700; color: #1a202c; font-size: 1.125rem;"><?php echo count($bookings); ?></span>
                    </div>
                </div>
                
                <!-- Filters -->
                <form method="GET" action="bookings.php" class="filters" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 16px; margin-bottom: 2rem; border: 2px solid #e2e8f0; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                    <div class="filter-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-weight: 600; font-size: 0.875rem; color: #475569;">Payment Status</label>
                        <select name="payment" id="filter-payment" style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 10px; font-size: 0.875rem; background: white; transition: all 0.3s ease;">
                            <option value="all" <?php echo $filter_payment == 'all' ? 'selected' : ''; ?>>All</option>
                            <option value="complete" <?php echo $filter_payment == 'complete' ? 'selected' : ''; ?>>Complete</option>
                            <option value="advance" <?php echo $filter_payment == 'advance' ? 'selected' : ''; ?>>Advance (50%)</option>
                            <option value="pending" <?php echo $filter_payment == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        </select>
                    </div>

                    <div class="filter-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-weight: 600; font-size: 0.875rem; color: #475569;">Room Type</label>
                        <select name="room" id="filter-room" style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 10px; font-size: 0.875rem; background: white; transition: all 0.3s ease;">
                            <option value="all" <?php echo $filter_room == 'all' ? 'selected' : ''; ?>>All Rooms</option>
                            <option value="non" <?php echo $filter_room == 'non' ? 'selected' : ''; ?>>Non A/C</option>
                            <option value="ac" <?php echo $filter_room == 'ac' ? 'selected' : ''; ?>>A/C</option>
                            <option value="deluxe" <?php echo $filter_room == 'deluxe' ? 'selected' : ''; ?>>Deluxe</option>
                            <option value="suite" <?php echo $filter_room == 'suite' ? 'selected' : ''; ?>>Suite</option>
                        </select>
                    </div>

                    <div class="filter-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-weight: 600; font-size: 0.875rem; color: #475569;">Date From</label>
                        <input type="date" name="date_from" id="filter-date-from" value="<?php echo htmlspecialchars($filter_date_from); ?>" style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 10px; font-size: 0.875rem; background: white; transition: all 0.3s ease;">
                    </div>

                    <div class="filter-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-weight: 600; font-size: 0.875rem; color: #475569;">Date To</label>
                        <input type="date" name="date_to" id="filter-date-to" value="<?php echo htmlspecialchars($filter_date_to); ?>" style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 10px; font-size: 0.875rem; background: white; transition: all 0.3s ease;">
                    </div>

                    <div class="filter-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="opacity: 0;">Apply</label>
                        <button type="submit" class="action-btn btn-view" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #8b0000 0%, #6b0000 100%); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(139, 0, 0, 0.3);">Apply Filters</button>
                    </div>
                </form>

                <!-- Bookings Table -->
                <div class="bookings-table" style="overflow-x: auto; border-radius: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);">
                    <table style="width: 100%; border-collapse: collapse; background: white; border: 2px solid #e2e8f0;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0; white-space: nowrap;">Booking ID</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Guest Name</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Room Details</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0; white-space: nowrap;">Guests</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0; white-space: nowrap;">Check-in</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0; white-space: nowrap;">Check-out</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0; white-space: nowrap;">Nights</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0; white-space: nowrap;">Amount</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Payment</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Status</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bookings)): ?>
                                <tr>
                                    <td colspan="11" style="text-align: center; padding: 3rem; color: #94a3b8; font-size: 0.875rem; border: 1px solid #e2e8f0;">
                                        <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                                        No bookings found
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr style="transition: all 0.2s ease;" onmouseover="this.style.background='linear-gradient(135deg, #fef2f2 0%, #fee2e2 50%, #fef2f2 100%)'" onmouseout="this.style.background='white'">
                                        <td style="padding: 1rem; font-weight: 600; color: #8b0000; font-size: 0.875rem; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($booking['booking_ref']); ?></td>
                                        <td style="padding: 1rem; color: #1e293b; font-weight: 500; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                        <td style="padding: 1rem; color: #64748b; font-size: 0.875rem; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($booking['room_details']); ?></td>
                                        <td style="padding: 1rem; color: #64748b; font-size: 0.875rem; white-space: nowrap; border: 1px solid #e2e8f0;">
                                            <?php 
                                            $adults = $booking['total_adults'] ?? 0;
                                            $children = $booking['total_children'] ?? 0;
                                            echo '<span style="font-weight: 600; color: #1e293b;">' . $adults . '</span> A';
                                            if ($children > 0) echo ', <span style="font-weight: 600; color: #1e293b;">' . $children . '</span> C';
                                            ?>
                                        </td>
                                        <td style="padding: 1rem; color: #64748b; font-size: 0.875rem; white-space: nowrap; border: 1px solid #e2e8f0;"><?php echo date('d M Y', strtotime($booking['checkin'])); ?></td>
                                        <td style="padding: 1rem; color: #64748b; font-size: 0.875rem; white-space: nowrap; border: 1px solid #e2e8f0;"><?php echo date('d M Y', strtotime($booking['checkout'])); ?></td>
                                        <td style="padding: 1rem; color: #1e293b; font-weight: 600; text-align: center; border: 1px solid #e2e8f0;"><?php echo $booking['nights']; ?></td>
                                        <td style="padding: 1rem; font-weight: 600; color: #1e293b; white-space: nowrap; border: 1px solid #e2e8f0;">₹<?php echo number_format($booking['total_amount'], 0); ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <?php 
                                            if ($booking['payment_type'] == 'full') {
                                                echo '<span style="background: #dcfce7; color: #166534; padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block; white-space: nowrap;">Complete</span>';
                                            } elseif ($booking['payment_type'] == 'advance') {
                                                echo '<span style="background: #dbeafe; color: #1e40af; padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block; white-space: nowrap;">Advance</span>';
                                            } else {
                                                echo '<span style="background: #fef3c7; color: #92400e; padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block; white-space: nowrap;">Pending</span>';
                                            }
                                            ?>
                                        </td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <?php
                                            $status_colors = [
                                                'confirmed' => 'background: #dcfce7; color: #166534;',
                                                'pending' => 'background: #fef3c7; color: #92400e;',
                                                'cancelled' => 'background: #fee2e2; color: #991b1b;',
                                                'checkedout' => 'background: #e0e7ff; color: #3730a3;'
                                            ];
                                            $style = $status_colors[$booking['status']] ?? 'background: #f1f5f9; color: #475569;';
                                            ?>
                                            <span style="<?php echo $style; ?> padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block; white-space: nowrap;">
                                                <?php echo ucfirst($booking['status']); ?>
                                            </span>
                                        </td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <button onclick="viewBookingDetails(<?php echo $booking['id']; ?>)" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 0.5rem 1rem; border-radius: 8px; border: none; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3); white-space: nowrap;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.3)'">View</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Details Modal -->
    <div id="bookingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); z-index: 2000; align-items: center; justify-content: center; padding: 2rem;">
        <div style="background: white; border-radius: 20px; max-width: 800px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 24px 48px rgba(0, 0, 0, 0.3);">
            <div style="background: linear-gradient(135deg, #8b0000 0%, #6b0000 100%); color: white; padding: 1.5rem 2rem; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700;">📋 Booking Details</h2>
                <button onclick="closeBookingModal()" style="background: rgba(255, 255, 255, 0.2); border: none; color: white; font-size: 1.5rem; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">&times;</button>
            </div>
            <div id="bookingDetailsContent" style="padding: 2rem;">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Add-ons Selection Modal -->
    <div id="addonsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); z-index: 2001; align-items: center; justify-content: center; padding: 2rem;">
        <div style="background: white; border-radius: 20px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 24px 48px rgba(0, 0, 0, 0.3);">
            <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 1.5rem 2rem; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700;">🎁 Add Add-ons</h2>
                <button onclick="closeAddonsModal()" style="background: rgba(255, 255, 255, 0.2); border: none; color: white; font-size: 1.5rem; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">&times;</button>
            </div>
            <div id="addonsListContent" style="padding: 2rem;">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
    let currentBookingId = null;
    let currentBookingData = null;

    function viewBookingDetails(bookingId) {
        // Show modal
        document.getElementById('bookingModal').style.display = 'flex';
        document.getElementById('bookingDetailsContent').innerHTML = '<div style="text-align: center; padding: 3rem;"><div style="font-size: 3rem; margin-bottom: 1rem;">⏳</div><p>Loading booking details...</p></div>';
        
        // Fetch booking details
        fetch('get_booking_details.php?id=' + bookingId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Response:', data);
                if (data.success) {
                    displayBookingDetails(data.booking);
                } else {
                    document.getElementById('bookingDetailsContent').innerHTML = '<div style="text-align: center; padding: 3rem;"><div style="font-size: 3rem; margin-bottom: 1rem;">❌</div><p style="color: #ef4444;">Error: ' + (data.message || 'Unknown error') + '</p></div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('bookingDetailsContent').innerHTML = '<div style="text-align: center; padding: 3rem;"><div style="font-size: 3rem; margin-bottom: 1rem;">❌</div><p style="color: #ef4444;">Error loading booking details: ' + error.message + '</p></div>';
            });
    }

    function displayBookingDetails(booking) {
        currentBookingData = booking; // Store for later use
        
        const addonsHtml = booking.addons && booking.addons.length > 0 
            ? booking.addons.map(addon => `<span style="background: linear-gradient(135deg, #e0e7ff 0%, #dbeafe 100%); color: #1e40af; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600; display: inline-block; margin: 0.25rem;">${addon.name} (₹${addon.price})</span>`).join('')
            : '<span style="color: #94a3b8;">No add-ons</span>';

        const html = `
            <div style="display: grid; gap: 1.5rem;">
                <!-- Booking Info -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #8b0000;">
                    <h3 style="margin: 0 0 1rem 0; color: #8b0000; font-size: 1.125rem;">Booking Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div><strong>Booking ID:</strong> ${booking.booking_ref}</div>
                        <div><strong>Status:</strong> <span style="background: ${getStatusColor(booking.status)}; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 600;">${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}</span></div>
                        <div><strong>Check-in:</strong> ${formatDate(booking.checkin)}</div>
                        <div><strong>Check-out:</strong> ${formatDate(booking.checkout)}</div>
                        <div><strong>Nights:</strong> ${booking.nights}</div>
                        <div><strong>Payment:</strong> ${booking.payment_type === 'full' ? 'Full Payment' : 'Advance (50%)'}</div>
                    </div>
                </div>

                <!-- Guest Info -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #10b981;">
                    <h3 style="margin: 0 0 1rem 0; color: #10b981; font-size: 1.125rem;">Guest Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div><strong>Name:</strong> ${booking.full_name}</div>
                        <div><strong>Email:</strong> ${booking.email}</div>
                        <div><strong>Mobile:</strong> ${booking.mobile}</div>
                        <div><strong>Adults:</strong> ${booking.total_adults}</div>
                        <div><strong>Children:</strong> ${booking.total_children}</div>
                        ${booking.total_extra_persons > 0 ? `<div><strong>Extra Persons:</strong> ${booking.total_extra_persons}</div>` : ''}
                    </div>
                </div>

                <!-- Room Details -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #3b82f6;">
                    <h3 style="margin: 0 0 1rem 0; color: #3b82f6; font-size: 1.125rem;">Room Details</h3>
                    <p>${booking.room_details}</p>
                </div>

                <!-- Add-ons -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #f59e0b;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 style="margin: 0; color: #f59e0b; font-size: 1.125rem;">Add-ons</h3>
                        <button onclick="showAddonsModal(${booking.id})" title="Add Add-ons" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: white; border: 2px dashed #9ca3af; color: #6b7280; border-radius: 50%; font-weight: 700; cursor: pointer; transition: all 0.3s ease; font-size: 1.5rem;" onmouseover="this.style.borderColor='#f59e0b'; this.style.color='#f59e0b'" onmouseout="this.style.borderColor='#9ca3af'; this.style.color='#6b7280'">
                            +
                        </button>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        ${addonsHtml}
                    </div>
                </div>

                <!-- Payment Details -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #8b0000;">
                    <h3 style="margin: 0 0 1rem 0; color: #8b0000; font-size: 1.125rem;">Payment Details</h3>
                    <div style="display: grid; gap: 0.75rem;">
                        <div style="display: flex; justify-content: space-between;"><span>Total Amount:</span><strong>₹${Number(booking.total_amount).toLocaleString()}</strong></div>
                        ${booking.processing_fee > 0 ? `<div style="display: flex; justify-content: space-between;"><span>Processing Fee:</span><strong>₹${Number(booking.processing_fee).toLocaleString()}</strong></div>` : ''}
                        ${booking.balance_due > 0 ? `<div style="display: flex; justify-content: space-between; color: #ef4444;"><span>Balance Due:</span><strong>₹${Number(booking.balance_due).toLocaleString()}</strong></div>` : ''}
                    </div>
                </div>

                <!-- Action Buttons (Only Receipt and Invoice) -->
                <div style="display: flex; gap: 1rem; flex-wrap: wrap; padding-top: 1rem; border-top: 2px solid #e2e8f0;">
                    <button onclick="generateReceipt(${booking.id})" style="flex: 1; min-width: 140px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">🧾 Receipt</button>
                    <button onclick="generateInvoice(${booking.id})" style="flex: 1; min-width: 140px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);">📄 Invoice</button>
                </div>
            </div>
        `;
        
        document.getElementById('bookingDetailsContent').innerHTML = html;
    }

    function getStatusColor(status) {
        const colors = {
            'confirmed': '#dcfce7; color: #166534',
            'pending': '#fef3c7; color: #92400e',
            'cancelled': '#fee2e2; color: #991b1b',
            'checkedout': '#e0e7ff; color: #3730a3'
        };
        return colors[status] || '#f1f5f9; color: #475569';
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').style.display = 'none';
    }

    function showAddonsModal(bookingId) {
        currentBookingId = bookingId;
        document.getElementById('addonsModal').style.display = 'flex';
        document.getElementById('addonsListContent').innerHTML = '<div style="text-align: center; padding: 3rem;"><div style="font-size: 3rem; margin-bottom: 1rem;">⏳</div><p>Loading add-ons...</p></div>';
        
        // Fetch available add-ons
        fetch('get_available_addons.php?booking_id=' + bookingId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayAddonsSelection(data.addons, data.existing_addon_ids);
                } else {
                    document.getElementById('addonsListContent').innerHTML = '<div style="text-align: center; padding: 3rem;"><div style="font-size: 3rem; margin-bottom: 1rem;">❌</div><p style="color: #ef4444;">Error: ' + (data.message || 'Unknown error') + '</p></div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('addonsListContent').innerHTML = '<div style="text-align: center; padding: 3rem;"><div style="font-size: 3rem; margin-bottom: 1rem;">❌</div><p style="color: #ef4444;">Error loading add-ons</p></div>';
            });
    }

    function displayAddonsSelection(addons, existingAddonIds) {
        if (!addons || addons.length === 0) {
            document.getElementById('addonsListContent').innerHTML = '<p style="text-align: center; color: #94a3b8;">No add-ons available</p>';
            return;
        }

        let html = '<div style="display: grid; gap: 1rem;">';
        
        addons.forEach(addon => {
            const isExisting = existingAddonIds.includes(addon.id);
            const bgColor = isExisting ? '#f1f5f9' : 'white';
            const textColor = isExisting ? '#94a3b8' : '#1e293b';
            const borderColor = isExisting ? '#cbd5e1' : '#e2e8f0';
            const cursor = isExisting ? 'not-allowed' : 'pointer';
            
            html += `
                <div style="background: ${bgColor}; border: 2px solid ${borderColor}; border-radius: 12px; padding: 1rem; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease; ${isExisting ? 'opacity: 0.6;' : ''}" ${!isExisting ? `onmouseover="this.style.borderColor='#f59e0b'" onmouseout="this.style.borderColor='#e2e8f0'"` : ''}>
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 0.5rem 0; color: ${textColor}; font-size: 1rem; font-weight: 600;">${addon.name}</h4>
                        <p style="margin: 0; color: ${isExisting ? '#94a3b8' : '#64748b'}; font-size: 0.875rem;">
                            ₹${addon.price} ${addon.charge_type === 'per_night' ? 'per night' : 'one-time'}
                        </p>
                    </div>
                    <button 
                        onclick="addAddonToBooking(${addon.id}, '${addon.name}', ${addon.price})" 
                        ${isExisting ? 'disabled' : ''}
                        style="background: ${isExisting ? '#cbd5e1' : 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)'}; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: ${cursor}; transition: all 0.3s ease; box-shadow: ${isExisting ? 'none' : '0 2px 8px rgba(245, 158, 11, 0.3)'};">
                        ${isExisting ? 'Added' : 'Add'}
                    </button>
                </div>
            `;
        });
        
        html += '</div>';
        document.getElementById('addonsListContent').innerHTML = html;
    }

    function addAddonToBooking(addonId, addonName, addonPrice) {
        if (!currentBookingId) {
            alert('Error: Booking ID not found');
            return;
        }

        // Show loading state
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Adding...';
        button.disabled = true;

        // Send request to add addon
        const formData = new FormData();
        formData.append('booking_id', currentBookingId);
        formData.append('addon_id', addonId);
        formData.append('quantity', 1);

        fetch('add_booking_addon.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close addons modal
                closeAddonsModal();
                
                // Refresh booking details to show updated add-ons and total
                viewBookingDetails(currentBookingId);
                
                // Show success message
                alert('Add-on added successfully! New total: ₹' + Number(data.new_total).toLocaleString());
            } else {
                alert('Error: ' + data.message);
                button.textContent = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding add-on');
            button.textContent = originalText;
            button.disabled = false;
        });
    }

    function closeAddonsModal() {
        document.getElementById('addonsModal').style.display = 'none';
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').style.display = 'none';
    }

    function editBooking(id) {
        window.location.href = 'edit_booking.php?id=' + id;
    }

    function generateReceipt(id) {
        window.open('generate_receipt.php?id=' + id, '_blank');
    }

    function generateInvoice(id) {
        window.open('generate_invoice.php?id=' + id, '_blank');
    }

    // Close modal when clicking outside
    document.getElementById('bookingModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookingModal();
        }
    });

    // Close addons modal when clicking outside
    document.getElementById('addonsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddonsModal();
        }
    });
    </script>

<?php include 'includes/footer.php'; ?>
