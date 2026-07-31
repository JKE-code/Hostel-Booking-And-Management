<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Set page variables
$page_title = 'Analytics';
$page_heading = 'Analytics Dashboard';

// Get revenue data by month (last 12 months)
$revenue_data = [];
$revenue_labels = [];
for ($i = 11; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $revenue_labels[] = date('M Y', strtotime("-$i months"));
    
    $query = "SELECT COALESCE(SUM(total_amount), 0) as revenue 
              FROM bookings 
              WHERE DATE_FORMAT(checkin, '%Y-%m') = '$month' 
              AND status IN ('confirmed', 'checkedout')";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $revenue_data[] = $row['revenue'];
}

// Get bookings by day of week
$bookings_by_day = [];
$day_labels = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
for ($i = 0; $i < 7; $i++) {
    $query = "SELECT COUNT(*) as count 
              FROM bookings 
              WHERE DAYOFWEEK(checkin) = " . ($i + 1) . " 
              AND status != 'cancelled'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $bookings_by_day[] = $row['count'];
}

// Get room type distribution (only rooms with bookings)
$room_type_data = [];
$room_type_labels = [];
$query = "SELECT rt.name, COUNT(br.id) as bookings 
          FROM booking_rooms br
          INNER JOIN room_types rt ON rt.id = br.room_type_id 
          INNER JOIN bookings b ON br.booking_id = b.id 
          WHERE b.status != 'cancelled' AND rt.status = 'active'
          GROUP BY rt.id, rt.name 
          HAVING bookings > 0
          ORDER BY bookings DESC";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $room_type_labels[] = $row['name'];
    $room_type_data[] = intval($row['bookings']);
}

// If no bookings exist, show placeholder data
if (empty($room_type_data)) {
    $room_type_labels = ['No Data'];
    $room_type_data = [1];
}

// Get summary statistics
$total_bookings_query = "SELECT COUNT(*) as total FROM bookings WHERE status != 'cancelled'";
$total_bookings_result = mysqli_query($conn, $total_bookings_query);
$total_bookings = mysqli_fetch_assoc($total_bookings_result)['total'];

$total_revenue_query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM bookings WHERE status IN ('confirmed', 'checkedout')";
$total_revenue_result = mysqli_query($conn, $total_revenue_query);
$total_revenue = mysqli_fetch_assoc($total_revenue_result)['total'];

$avg_booking_query = "SELECT COALESCE(AVG(total_amount), 0) as avg FROM bookings WHERE status IN ('confirmed', 'checkedout')";
$avg_booking_result = mysqli_query($conn, $avg_booking_query);
$avg_booking = mysqli_fetch_assoc($avg_booking_result)['avg'];

$this_month_revenue_query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM bookings WHERE DATE_FORMAT(checkin, '%Y-%m') = '" . date('Y-m') . "' AND status IN ('confirmed', 'checkedout')";
$this_month_revenue_result = mysqli_query($conn, $this_month_revenue_query);
$this_month_revenue = mysqli_fetch_assoc($this_month_revenue_result)['total'];
?>
<?php include 'includes/header.php'; ?>

    <!-- Dashboard Content -->
    <section class="dashboard-container">
        <div class="container">
            
            <!-- Summary Statistics -->
            <div class="stats-summary">
                <div class="stat-summary-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-value"><?php echo number_format($total_bookings); ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                
                <div class="stat-summary-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-value">₹<?php echo number_format($total_revenue, 0); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                
                <div class="stat-summary-card">
                    <div class="stat-icon">📈</div>
                    <div class="stat-value">₹<?php echo number_format($avg_booking, 0); ?></div>
                    <div class="stat-label">Average Booking</div>
                </div>
                
                <div class="stat-summary-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-value">₹<?php echo number_format($this_month_revenue, 0); ?></div>
                    <div class="stat-label">This Month Revenue</div>
                </div>
            </div>
            
            <!-- Revenue Analytics -->
            <div class="dashboard-card">
                <h2>📊 Revenue Analytics (Last 12 Months)</h2>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Bookings by Day -->
            <div class="dashboard-card">
                <h2>📈 Bookings by Day of Week</h2>
                <div class="chart-container">
                    <canvas id="bookingsDayChart"></canvas>
                </div>
            </div>

            <!-- Room Type Distribution -->
            <div class="dashboard-card">
                <h2>🏠 Room Type Distribution</h2>
                <div class="chart-container">
                    <canvas id="roomTypeChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($revenue_labels); ?>,
                datasets: [{
                    label: 'Revenue (₹)',
                    data: <?php echo json_encode($revenue_data); ?>,
                    borderColor: '#8b0000',
                    backgroundColor: 'rgba(139, 0, 0, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: ₹' + context.parsed.y.toLocaleString('en-IN');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString('en-IN');
                            }
                        }
                    }
                }
            }
        });

        // Bookings by Day Chart
        const bookingsDayCtx = document.getElementById('bookingsDayChart').getContext('2d');
        const bookingsDayChart = new Chart(bookingsDayCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($day_labels); ?>,
                datasets: [{
                    label: 'Number of Bookings',
                    data: <?php echo json_encode($bookings_by_day); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(139, 0, 0, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(139, 0, 0, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Room Type Chart
        const roomTypeCtx = document.getElementById('roomTypeChart').getContext('2d');
        const roomTypeChart = new Chart(roomTypeCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($room_type_labels); ?>,
                datasets: [{
                    label: 'Bookings',
                    data: <?php echo json_encode($room_type_data); ?>,
                    backgroundColor: [
                        'rgba(139, 0, 0, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)'
                    ],
                    borderColor: [
                        'rgba(139, 0, 0, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>

<?php include 'includes/footer.php'; ?>
