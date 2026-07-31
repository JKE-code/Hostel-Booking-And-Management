<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Set page variables
$page_title = 'Overview';
$page_heading = 'Overview Dashboard';

// Fetch dashboard statistics
$stats = [
    'total_bookings' => 0,
    'total_revenue' => 0,
    'pending_payments' => 0,
    'occupied_rooms' => 0,
    'total_rooms' => 0
];

// Total bookings
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings WHERE status != 'cancelled'");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $stats['total_bookings'] = $row['count'];
}

// Total revenue
$result = mysqli_query($conn, "SELECT SUM(total_amount) as revenue FROM bookings WHERE status IN ('confirmed', 'checkedout')");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $stats['total_revenue'] = $row['revenue'] ?? 0;
}

// Pending payments
$result = mysqli_query($conn, "SELECT SUM(balance_due) as pending FROM bookings WHERE status = 'confirmed' AND balance_due > 0");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $stats['pending_payments'] = $row['pending'] ?? 0;
}

// Total rooms
$result = mysqli_query($conn, "SELECT SUM(total_rooms) as total FROM room_types WHERE status = 'active'");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $stats['total_rooms'] = $row['total'] ?? 0;
}

// Occupied rooms (current date)
$today = date('Y-m-d');
$result = mysqli_query($conn, "
    SELECT SUM(br.rooms_booked) as occupied 
    FROM booking_rooms br
    JOIN bookings b ON br.booking_id = b.id
    WHERE b.status = 'confirmed' 
    AND '$today' BETWEEN b.checkin AND b.checkout
");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $stats['occupied_rooms'] = $row['occupied'] ?? 0;
}

// Fetch room occupancy by type
$room_occupancy = [];
$query = "
    SELECT 
        rt.name,
        rt.total_rooms,
        COALESCE(SUM(br.rooms_booked), 0) as occupied
    FROM room_types rt
    LEFT JOIN booking_rooms br ON rt.id = br.room_type_id
    LEFT JOIN bookings b ON br.booking_id = b.id AND b.status = 'confirmed' AND '$today' BETWEEN b.checkin AND b.checkout
    WHERE rt.status = 'active'
    GROUP BY rt.id, rt.name, rt.total_rooms
";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $room_occupancy[] = $row;
    }
}

// Fetch recent bookings with more details
$recent_bookings = [];
$query = "
    SELECT 
        b.id,
        b.booking_ref,
        g.full_name,
        g.email,
        g.mobile,
        b.checkin,
        b.checkout,
        DATEDIFF(b.checkout, b.checkin) as nights,
        b.total_amount,
        b.status,
        b.payment_type,
        b.balance_due,
        b.processing_fee,
        GROUP_CONCAT(DISTINCT CONCAT(rt.name, ' (', br.rooms_booked, ')') SEPARATOR ', ') as room_details,
        (SELECT SUM(adults) FROM guests WHERE booking_id = b.id) as total_adults,
        (SELECT SUM(children) FROM guests WHERE booking_id = b.id) as total_children,
        (SELECT SUM(extra_persons) FROM guests WHERE booking_id = b.id) as total_extra_persons
    FROM bookings b
    LEFT JOIN guests g ON b.id = g.booking_id
    LEFT JOIN booking_rooms br ON b.id = br.booking_id
    LEFT JOIN room_types rt ON br.room_type_id = rt.id
    GROUP BY b.id
    ORDER BY b.created_at DESC
    LIMIT 10
";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recent_bookings[] = $row;
    }
}
?>
<?php include 'includes/header.php'; ?>

    <!-- Dashboard Content -->
    <section class="dashboard-container">
        <div class="container">
            <!-- Stats Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 1.5rem; color: white; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <div style="font-size: 2.5rem; opacity: 0.9;">📅</div>
                        <div style="background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">Total</div>
                    </div>
                    <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;"><?php echo $stats['total_bookings']; ?></div>
                    <div style="font-size: 0.875rem; opacity: 0.9; font-weight: 500;">Total Bookings</div>
                </div>

                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 16px; padding: 1.5rem; color: white; box-shadow: 0 10px 30px rgba(240, 147, 251, 0.3); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <div style="font-size: 2.5rem; opacity: 0.9;">💰</div>
                        <div style="background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">Revenue</div>
                    </div>
                    <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">₹<?php echo number_format($stats['total_revenue'], 0); ?></div>
                    <div style="font-size: 0.875rem; opacity: 0.9; font-weight: 500;">Total Revenue</div>
                </div>

                <div style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border-radius: 16px; padding: 1.5rem; color: #8b4513; box-shadow: 0 10px 30px rgba(252, 182, 159, 0.3); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <div style="font-size: 2.5rem; opacity: 0.9;">⏳</div>
                        <div style="background: rgba(139, 69, 19, 0.1); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">Pending</div>
                    </div>
                    <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">₹<?php echo number_format($stats['pending_payments'], 0); ?></div>
                    <div style="font-size: 0.875rem; opacity: 0.9; font-weight: 500;">Pending Payments</div>
                </div>

                <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 16px; padding: 1.5rem; color: #2c5f5d; box-shadow: 0 10px 30px rgba(168, 237, 234, 0.3); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <div style="font-size: 2.5rem; opacity: 0.9;">🏠</div>
                        <div style="background: rgba(44, 95, 93, 0.1); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">Occupancy</div>
                    </div>
                    <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                        <span><?php echo $stats['occupied_rooms']; ?></span>/<span style="font-size: 1.5rem; opacity: 0.7;"><?php echo $stats['total_rooms']; ?></span>
                    </div>
                    <div style="font-size: 0.875rem; opacity: 0.9; font-weight: 500;">Rooms Occupied</div>
                </div>
            </div>

            <!-- Room Occupancy Status -->
            <div class="dashboard-card" style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 2rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #1a202c; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>🏠</span> Current Room Occupancy
                </h2>
                <div class="room-occupancy" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    <?php foreach ($room_occupancy as $room): ?>
                        <?php 
                        $available = $room['total_rooms'] - $room['occupied'];
                        $occupancy_percent = ($room['total_rooms'] > 0) ? round(($room['occupied'] / $room['total_rooms']) * 100) : 0;
                        $is_high = $occupancy_percent >= 80;
                        $is_medium = $occupancy_percent >= 50 && $occupancy_percent < 80;
                        $color = $is_high ? '#ef4444' : ($is_medium ? '#f59e0b' : '#10b981');
                        ?>
                        <div class="room-status" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; padding: 1.5rem; border-left: 4px solid <?php echo $color; ?>; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                            <h4 style="font-size: 1.125rem; font-weight: 600; color: #1a202c; margin-bottom: 1rem;"><?php echo htmlspecialchars($room['name']); ?></h4>
                            <div style="margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem; color: #64748b;">
                                    <span>Occupancy</span>
                                    <span style="font-weight: 600; color: <?php echo $color; ?>;"><?php echo $occupancy_percent; ?>%</span>
                                </div>
                                <div style="background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden;">
                                    <div style="background: <?php echo $color; ?>; height: 100%; width: <?php echo $occupancy_percent; ?>%; transition: width 0.3s ease;"></div>
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; font-size: 0.875rem;">
                                <div style="text-align: center; padding: 0.5rem; background: white; border-radius: 8px;">
                                    <div style="font-weight: 700; font-size: 1.25rem; color: #1a202c;"><?php echo $room['total_rooms']; ?></div>
                                    <div style="color: #64748b; font-size: 0.75rem;">Total</div>
                                </div>
                                <div style="text-align: center; padding: 0.5rem; background: white; border-radius: 8px;">
                                    <div style="font-weight: 700; font-size: 1.25rem; color: <?php echo $color; ?>;"><?php echo $room['occupied']; ?></div>
                                    <div style="color: #64748b; font-size: 0.75rem;">Occupied</div>
                                </div>
                                <div style="text-align: center; padding: 0.5rem; background: white; border-radius: 8px;">
                                    <div style="font-weight: 700; font-size: 1.25rem; color: #10b981;"><?php echo $available; ?></div>
                                    <div style="color: #64748b; font-size: 0.75rem;">Available</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="dashboard-card" style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #1a202c; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>📋</span> Recent Bookings
                </h2>
                <div class="bookings-table" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; border: 2px solid #e2e8f0;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Booking ID</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Guest Name</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Room Type</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Check-in</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Check-out</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Amount</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_bookings)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 3rem; color: #94a3b8; font-size: 0.875rem; border: 1px solid #e2e8f0;">
                                        <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                                        No bookings found
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_bookings as $booking): ?>
                                    <tr style="transition: background 0.2s ease; cursor: pointer;" onclick="viewBookingDetails(<?php echo $booking['id']; ?>)" onmouseover="this.style.background='linear-gradient(135deg, #fef2f2 0%, #fee2e2 50%, #fef2f2 100%)'" onmouseout="this.style.background='white'">
                                        <td style="padding: 1rem; font-weight: 600; color: #8b0000; font-size: 0.875rem; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($booking['booking_ref']); ?></td>
                                        <td style="padding: 1rem; color: #1e293b; font-weight: 500; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                        <td style="padding: 1rem; color: #64748b; font-size: 0.875rem; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($booking['room_details']); ?></td>
                                        <td style="padding: 1rem; color: #64748b; font-size: 0.875rem; border: 1px solid #e2e8f0;"><?php echo date('d M Y', strtotime($booking['checkin'])); ?></td>
                                        <td style="padding: 1rem; color: #64748b; font-size: 0.875rem; border: 1px solid #e2e8f0;"><?php echo date('d M Y', strtotime($booking['checkout'])); ?></td>
                                        <td style="padding: 1rem; font-weight: 600; color: #1e293b; border: 1px solid #e2e8f0;">₹<?php echo number_format($booking['total_amount'], 0); ?></td>
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
                                            <span style="<?php echo $style; ?> padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">
                                                <?php echo ucfirst($booking['status']); ?>
                                            </span>
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

    <script>
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
                console.log('Response:', data); // Debug log
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
                        <button onclick="editBooking(${booking.id})" title="Add Add-ons" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: white; border: 2px dashed #9ca3af; color: #6b7280; border-radius: 50%; font-weight: 700; cursor: pointer; transition: all 0.3s ease; font-size: 1.5rem;" onmouseover="this.style.borderColor='#f59e0b'; this.style.color='#f59e0b'" onmouseout="this.style.borderColor='#9ca3af'; this.style.color='#6b7280'">
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

                <!-- Action Buttons -->
                <div style="display: flex; gap: 1rem; flex-wrap: wrap; padding-top: 1rem; border-top: 2px solid #e2e8f0;">
                    <button onclick="checkinBooking(${booking.id})" style="flex: 1; min-width: 140px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">🔑 Check-in</button>
                    <button onclick="checkoutBooking(${booking.id})" style="flex: 1; min-width: 140px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">✅ Check-out</button>
                    <button onclick="generateReceipt(${booking.id})" style="flex: 1; min-width: 140px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">🧾 Receipt</button>
                    <button onclick="generateInvoice(${booking.id})" style="flex: 1; min-width: 140px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);">📄 Invoice</button>
                    <button onclick="deleteBooking(${booking.id})" style="flex: 1; min-width: 140px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);">🗑️ Delete</button>
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

    function editBooking(id) {
        window.location.href = 'edit_booking.php?id=' + id;
    }

    function checkinBooking(id) {
        if (confirm('Mark this booking as checked in?')) {
            window.location.href = 'checkin_booking.php?id=' + id;
        }
    }

    function checkoutBooking(id) {
        if (confirm('Mark this booking as checked out?')) {
            window.location.href = 'checkout_booking.php?id=' + id;
        }
    }

    function generateReceipt(id) {
        window.open('generate_receipt.php?id=' + id, '_blank');
    }

    function generateInvoice(id) {
        window.open('generate_invoice.php?id=' + id, '_blank');
    }

    function deleteBooking(id) {
        if (confirm('Are you sure you want to delete this booking? This action cannot be undone.')) {
            window.location.href = 'delete_booking.php?id=' + id;
        }
    }

    // Close modal when clicking outside
    document.getElementById('bookingModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookingModal();
        }
    });
    </script>

<?php include 'includes/footer.php'; ?>
