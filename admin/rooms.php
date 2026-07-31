<?php
session_start();

// Clear PHP OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
}

// Prevent all caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require_once __DIR__ . '/../config/db.php';

// Set page variables
$page_title = 'Rooms Dashboard';
$page_heading = 'Room Booking Dashboard';

// Get today's date
$today = date('Y-m-d');

// Fetch room types and their total counts
$room_types = [];
$query = "SELECT id, name, total_rooms FROM room_types WHERE status = 'active' ORDER BY id";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $room_types[$row['id']] = [
            'name' => $row['name'],
            'total' => $row['total_rooms'],
            'occupied' => 0,
            'rooms' => []
        ];
    }
}

// Fetch all rooms with their current booking status (if occupied today)
$query = "
    SELECT 
        r.id as room_id,
        r.room_number,
        r.room_type_id,
        bra.booking_id,
        b.booking_ref,
        b.checkin,
        b.checkout,
        b.status,
        g.full_name,
        g.email,
        g.mobile
    FROM rooms r
    LEFT JOIN booking_room_assignments bra ON r.id = bra.room_id
    LEFT JOIN bookings b ON bra.booking_id = b.id 
        AND b.status IN ('confirmed', 'checkedin')
        AND '$today' BETWEEN b.checkin AND b.checkout
    LEFT JOIN guests g ON b.id = g.booking_id
    ORDER BY r.room_type_id, r.room_number
";

$result = mysqli_query($conn, $query);
$all_rooms = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $room_id = $row['room_id'];
        $type_id = $row['room_type_id'];
        
        // Initialize room data
        if (!isset($all_rooms[$room_id])) {
            $all_rooms[$room_id] = [
                'room_id' => $room_id,
                'room_number' => $row['room_number'],
                'room_type_id' => $type_id,
                'status' => 'available',
                'current_booking' => null
            ];
        }
        
        // If room has a current booking
        if ($row['booking_id']) {
            $all_rooms[$room_id]['status'] = 'occupied';
            $all_rooms[$room_id]['current_booking'] = [
                'booking_id' => $row['booking_id'],
                'booking_ref' => $row['booking_ref'],
                'checkin' => $row['checkin'],
                'checkout' => $row['checkout'],
                'guest_name' => $row['full_name'],
                'guest_email' => $row['email'],
                'guest_mobile' => $row['mobile']
            ];
            
            // Increment occupied count for this room type
            if (isset($room_types[$type_id])) {
                $room_types[$type_id]['occupied']++;
            }
        }
    }
}

// Fetch all bookings for each room (past, current, and future)
$bookings_query = "
    SELECT 
        r.id as room_id,
        b.id as booking_id,
        b.booking_ref,
        b.checkin,
        b.checkout,
        b.status,
        g.full_name
    FROM booking_room_assignments bra
    JOIN rooms r ON bra.room_id = r.id
    JOIN bookings b ON bra.booking_id = b.id
    JOIN guests g ON b.id = g.booking_id
    WHERE b.status IN ('confirmed', 'checkedin', 'checkedout')
    ORDER BY r.id, b.checkin
";

$bookings_result = mysqli_query($conn, $bookings_query);
$room_bookings = [];

if ($bookings_result) {
    while ($row = mysqli_fetch_assoc($bookings_result)) {
        $room_id = $row['room_id'];
        if (!isset($room_bookings[$room_id])) {
            $room_bookings[$room_id] = [];
        }
        $room_bookings[$room_id][] = [
            'booking_ref' => $row['booking_ref'],
            'checkin' => $row['checkin'],
            'checkout' => $row['checkout'],
            'status' => $row['status'],
            'guest_name' => $row['full_name']
        ];
    }
}

// Organize rooms by type
foreach ($all_rooms as $room) {
    $type_id = $room['room_type_id'];
    if (isset($room_types[$type_id])) {
        // Add booking history to room data
        $room['bookings'] = $room_bookings[$room['room_id']] ?? [];
        $room_types[$type_id]['rooms'][] = $room;
    }
}
unset($type); // Unset any lingering references

// Calculate totals
$total_rooms = 0;
$total_occupied = 0;
foreach ($room_types as $type) {
    $total_rooms += $type['total'];
    $total_occupied += $type['occupied'];
}
$total_available = $total_rooms - $total_occupied;
$occupancy_rate = $total_rooms > 0 ? round(($total_occupied / $total_rooms) * 100) : 0;
?>
<?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="container">
            <!-- Overview Stats -->
            <section class="overview-section">
                <div class="stats-grid">
                    <div class="stat-card primary">
                        <div class="stat-icon">🏠</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $total_rooms; ?></div>
                            <div class="stat-label">Total Rooms</div>
                        </div>
                    </div>

                    <div class="stat-card success">
                        <div class="stat-icon">✓</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $total_occupied; ?></div>
                            <div class="stat-label">Occupied</div>
                        </div>
                    </div>

                    <div class="stat-card info">
                        <div class="stat-icon">○</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $total_available; ?></div>
                            <div class="stat-label">Available</div>
                        </div>
                    </div>

                    <div class="stat-card warning">
                        <div class="stat-icon">%</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo $occupancy_rate; ?>%</div>
                            <div class="stat-label">Occupancy Rate</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Room Categories -->
            <section class="room-categories">
                <?php foreach ($room_types as $type_id => $type): ?>
                    <?php
                    $available = $type['total'] - $type['occupied'];
                    // Determine icon based on room type name
                    $icon = '🏠';
                    if (stripos($type['name'], 'suite') !== false) $icon = '👑';
                    elseif (stripos($type['name'], 'deluxe') !== false) $icon = '⭐';
                    elseif (stripos($type['name'], 'non') !== false) $icon = '🌿';
                    elseif (stripos($type['name'], 'ac') !== false || stripos($type['name'], 'a/c') !== false) $icon = '❄️';
                    ?>
                    <div class="category-card">
                        <div class="category-header">
                            <div class="category-info">
                                <h2><?php echo $icon; ?> <?php echo htmlspecialchars($type['name']); ?></h2>
                                <p class="category-desc">
                                    <?php 
                                    if (stripos($type['name'], 'suite') !== false) echo 'Premium luxury suites with exclusive amenities';
                                    elseif (stripos($type['name'], 'deluxe') !== false) echo 'Spacious rooms with modern amenities';
                                    elseif (stripos($type['name'], 'non') !== false) echo 'Budget-friendly rooms with natural ventilation';
                                    else echo 'Comfortable air-conditioned rooms';
                                    ?>
                                </p>
                            </div>
                            <div class="category-stats">
                                <div class="stat-pill">
                                    <span class="pill-label">Total:</span>
                                    <span class="pill-value"><?php echo $type['total']; ?></span>
                                </div>
                                <div class="stat-pill occupied">
                                    <span class="pill-label">Occupied:</span>
                                    <span class="pill-value"><?php echo $type['occupied']; ?></span>
                                </div>
                                <div class="stat-pill available">
                                    <span class="pill-label">Available:</span>
                                    <span class="pill-value"><?php echo $available; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="rooms-grid">
                            <?php foreach ($type['rooms'] as $room): ?>
                                <div class="room-box <?php echo $room['status']; ?>" 
                                     data-room-id="<?php echo $room['room_id']; ?>"
                                     data-room-number="<?php echo $room['room_number']; ?>"
                                     data-status="<?php echo $room['status']; ?>"
                                     onclick="showRoomDetailsById(this)"
                                     style="cursor: pointer;">
                                    <div class="room-number"><?php echo $room['room_number']; ?></div>
                                    <span class="room-status <?php echo $room['status']; ?>">
                                        <?php echo $room['status'] == 'occupied' ? '🔴 Occupied' : '🟢 Available'; ?>
                                    </span>
                                    <?php if ($room['status'] == 'occupied' && $room['current_booking']): ?>
                                        <div class="room-guest" style="font-size: 0.85em; margin-top: 5px; color: #333;">
                                            👤 <?php echo htmlspecialchars(substr($room['current_booking']['guest_name'], 0, 15)); ?>
                                            <?php if (strlen($room['current_booking']['guest_name']) > 15) echo '...'; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </div>
    </main>

    <!-- Room Details Modal -->
    <div id="roomModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalRoomTitle">Room Details</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Dynamic content -->
            </div>
        </div>
    </div>

    <script>
        // Test if modal exists on page load
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('roomModal');
            const modalTitle = document.getElementById('modalRoomTitle');
            const modalBody = document.getElementById('modalBody');
            
            console.log('Modal elements check:');
            console.log('- roomModal:', modal ? 'Found' : 'NOT FOUND');
            console.log('- modalRoomTitle:', modalTitle ? 'Found' : 'NOT FOUND');
            console.log('- modalBody:', modalBody ? 'Found' : 'NOT FOUND');
        });
        
        function showRoomDetailsById(element) {
            console.log('showRoomDetailsById called');
            console.log('Element:', element);
            
            const roomId = element.getAttribute('data-room-id');
            const roomNumber = element.getAttribute('data-room-number');
            
            console.log('Room ID:', roomId);
            console.log('Room Number:', roomNumber);
            
            if (!roomId) {
                alert('Error: Room ID not found');
                return;
            }
            
            const modal = document.getElementById('roomModal');
            const modalTitle = document.getElementById('modalRoomTitle');
            const modalBody = document.getElementById('modalBody');
            
            if (!modal || !modalTitle || !modalBody) {
                console.error('Modal elements not found!');
                console.error('modal:', modal);
                console.error('modalTitle:', modalTitle);
                console.error('modalBody:', modalBody);
                alert('Error: Modal elements not found. Please refresh the page.');
                return;
            }
            
            // Show loading
            modalTitle.textContent = `Room ${roomNumber} Details`;
            modalBody.innerHTML = '<p style="text-align: center; padding: 40px;">Loading...</p>';
            modal.classList.add('active'); // Changed from 'show' to 'active'
            modal.style.display = 'flex'; // Also set display directly
            
            console.log('Fetching room details from:', `get_room_details.php?room_id=${roomId}`);
            
            // Fetch room details
            fetch(`get_room_details.php?room_id=${roomId}`)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);
                    
                    if (!data.success) {
                        modalBody.innerHTML = `<p style="color: red;">${data.message}</p>`;
                        return;
                    }
                    
                    let html = `
                        <div class="booking-detail">
                            <h3>Current Status</h3>
                            <p><strong>Status:</strong> <span style="color: ${data.status === 'occupied' ? '#dc3545' : '#28a745'}; font-weight: bold;">
                                ${data.status === 'occupied' ? '🔴 Occupied' : '🟢 Available'}
                            </span></p>
                        </div>
                    `;
                    
                    // Show current booking if occupied
                    if (data.status === 'occupied' && data.current_booking) {
                        const booking = data.current_booking;
                        const checkin = new Date(booking.checkin).toLocaleDateString('en-IN', { 
                            day: '2-digit', 
                            month: 'short', 
                            year: 'numeric' 
                        });
                        const checkout = new Date(booking.checkout).toLocaleDateString('en-IN', { 
                            day: '2-digit', 
                            month: 'short', 
                            year: 'numeric' 
                        });
                        
                        html += `
                            <div class="booking-detail" style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                <h3 style="color: #856404;">📌 Current Guest</h3>
                                <p><strong>Name:</strong> ${booking.guest_name || 'N/A'}</p>
                                <p><strong>Mobile:</strong> ${booking.guest_mobile || 'N/A'}</p>
                                <p><strong>Email:</strong> ${booking.guest_email || 'N/A'}</p>
                                <p><strong>Booking Ref:</strong> ${booking.booking_ref}</p>
                                <p><strong>Check-in:</strong> ${checkin}</p>
                                <p><strong>Check-out:</strong> ${checkout}</p>
                            </div>
                        `;
                    }
                    
                    // Show booking history
                    console.log('Bookings data:', data.bookings);
                    console.log('Bookings count:', data.bookings ? data.bookings.length : 0);
                    
                    if (data.bookings && data.bookings.length > 0) {
                        html += `
                            <div class="booking-detail">
                                <h3>📅 Booking History (${data.bookings.length} bookings)</h3>
                                <div style="max-height: 300px; overflow-y: auto;">
                        `;
                        
                        data.bookings.forEach((booking, index) => {
                            console.log(`Booking ${index}:`, booking);
                            
                            const checkin = new Date(booking.checkin).toLocaleDateString('en-IN', { 
                                day: '2-digit', 
                                month: 'short', 
                                year: 'numeric' 
                            });
                            const checkout = new Date(booking.checkout).toLocaleDateString('en-IN', { 
                                day: '2-digit', 
                                month: 'short', 
                                year: 'numeric' 
                            });
                            
                            let statusColor = '#6c757d';
                            let statusIcon = '📋';
                            if (booking.status === 'confirmed') {
                                statusColor = '#007bff';
                                statusIcon = '✓';
                            } else if (booking.status === 'checkedin') {
                                statusColor = '#ffc107';
                                statusIcon = '🔑';
                            } else if (booking.status === 'checkedout') {
                                statusColor = '#28a745';
                                statusIcon = '✅';
                            }
                            
                            html += `
                                <div style="padding: 12px; margin-bottom: 10px; border-left: 4px solid ${statusColor}; background: #f8f9fa; border-radius: 4px;">
                                    <p style="margin: 0 0 5px 0;"><strong>${statusIcon} ${booking.guest_name}</strong></p>
                                    <p style="margin: 0; font-size: 0.9em; color: #666;">
                                        ${checkin} → ${checkout}
                                    </p>
                                    <p style="margin: 5px 0 0 0; font-size: 0.85em; color: ${statusColor};">
                                        Ref: ${booking.booking_ref} | Status: ${booking.status.toUpperCase()}
                                    </p>
                                </div>
                            `;
                        });
                        
                        html += `
                                </div>
                            </div>
                        `;
                    } else {
                        html += `
                            <div class="booking-detail">
                                <p style="color: #6c757d; font-style: italic;">No booking history available for this room.</p>
                            </div>
                        `;
                    }
                    
                    modalBody.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching room details:', error);
                    modalBody.innerHTML = '<p style="color: red;">Error loading room details: ' + error.message + '</p>';
                });
        }
        
        function closeModal() {
            const modal = document.getElementById('roomModal');
            if (modal) {
                modal.classList.remove('active'); // Changed from 'show' to 'active'
                modal.style.display = 'none'; // Also set display directly
            }
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('roomModal');
            if (event.target == modal) {
                closeModal();
            }
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>

<?php include 'includes/footer.php'; ?>
