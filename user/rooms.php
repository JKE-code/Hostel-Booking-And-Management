<?php
$page_title = 'Rooms - Alluri Resorts';
$body_class = 'rooms-page';

// Initialize default prices (fallback)
$room_prices = [
    'Non-AC' => 2500,
    'AC' => 3500,
    'Deluxe' => 5000,
    'Suite' => 7000
];

$room_details = [
    'Non-AC' => ['max_adults' => 2, 'max_children' => 1],
    'AC' => ['max_adults' => 2, 'max_children' => 1],
    'Deluxe' => ['max_adults' => 2, 'max_children' => 1],
    'Suite' => ['max_adults' => 4, 'max_children' => 2]
];

// Fetch from database
$db_connected = false;
try {
    require_once '../config/db.php';
    
    if (isset($pdo) && $pdo instanceof PDO) {
        $stmt = $pdo->prepare("SELECT name, price_per_night, max_adults, max_children, total_rooms FROM room_types WHERE status = 'active' ORDER BY price_per_night ASC");
        $stmt->execute();
        
        $db_connected = true;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $room_prices[$row['name']] = $row['price_per_night'];
            $room_details[$row['name']] = [
                'price' => $row['price_per_night'],
                'max_adults' => $row['max_adults'],
                'max_children' => $row['max_children'],
                'total_rooms' => $row['total_rooms']
            ];
        }
    }
} catch (Exception $e) {
    // Use default prices
    $db_connected = false;
}

// Use database prices directly (no GST calculation needed - prices already include GST)
$display_prices = [];
foreach ($room_prices as $type => $price) {
    $display_prices[$type] = round($price);
}

// Format prices for display
function formatPrice($price) {
    return '₹' . number_format($price, 0, '.', ',');
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <h1>Our Rooms</h1>
    <p>Choose from our collection of comfortable and luxurious rooms</p>
</section>

<!-- Rooms Section -->
<section class="rooms-section">
    <div class="container">
        <div class="rooms-grid">
            <!-- Non A/C Room -->
            <div class="room-card">
                <img src="../assets/images/Non-AC/Non_AC.jpg" alt="Non A/C Room" onclick="openRoomImageModal(this.src, this.alt)" style="cursor: pointer;">
                <div class="room-info">
                    <h3>Non A/C Room</h3>
                    <p class="room-size">170 sq. ft.</p>
                    <p class="room-capacity"><?php echo isset($room_details['Non-AC']) ? $room_details['Non-AC']['max_adults'] : 2; ?> Adults + <?php echo isset($room_details['Non-AC']) ? $room_details['Non-AC']['max_children'] : 1; ?> Child</p>
                    <p class="room-price"><span id="nonac-price"><?php echo formatPrice($display_prices['Non-AC']); ?></span> <span>per night</span></p>
                    <div class="room-amenities">
                        <span>🛏️ Bed</span>
                        <span>🛁 Bathroom</span>
                        <span>🏬 Wardrobe</span>
                        <span>📶 Free Wi-Fi</span>
                        <span>📺 Smart TV</span>
                    </div>
                    <a href="booking_step1_dates.php?room=non-ac" class="btn btn-book">Book Now</a>
                </div>
            </div>

            <!-- A/C Room -->
            <div class="room-card">
                <img src="../assets/images/AC/AC.jpg" alt="A/C Room" onclick="openRoomImageModal(this.src, this.alt)" style="cursor: pointer;">
                <div class="room-info">
                    <h3>A/C Room</h3>
                    <p class="room-size">170 sq. ft.</p>
                    <p class="room-capacity"><?php echo isset($room_details['AC']) ? $room_details['AC']['max_adults'] : 2; ?> Adults + <?php echo isset($room_details['AC']) ? $room_details['AC']['max_children'] : 1; ?> Child</p>
                    <p class="room-price"><span id="ac-price"><?php echo formatPrice($display_prices['AC']); ?></span> <span>per night</span></p>
                    <div class="room-amenities">
                        <span>🛏️ Bed</span>
                        <span>🛁 Bathroom</span>
                        <span>🏬 Wardrobe</span>
                        <span>❄️ Air Conditioning</span>
                        <span>📶 Free Wi-Fi</span>
                        <span>📺 Smart TV</span>
                    </div>
                    <a href="booking_step1_dates.php?room=ac" class="btn btn-book">Book Now</a>
                </div>
            </div>

            <!-- Deluxe A/C Room -->
            <div class="room-card">
                <img src="../assets/images/Deluxe/1.jpg" alt="Deluxe A/C Room" onclick="openDeluxeSlideshow()" style="cursor: pointer;" data-room-type="deluxe">
                <div class="room-info">
                    <h3>Deluxe A/C Room</h3>
                    <p class="room-size">250 sq. ft.</p>
                    <p class="room-capacity"><?php echo isset($room_details['Deluxe']) ? $room_details['Deluxe']['max_adults'] : 2; ?> Adults + <?php echo isset($room_details['Deluxe']) ? $room_details['Deluxe']['max_children'] : 1; ?> Child</p>
                    <p class="room-price"><span id="deluxe-price"><?php echo formatPrice($display_prices['Deluxe']); ?></span> <span>per night</span></p>
                    <div class="room-amenities">
                        <span>🛏️ Bed</span>
                        <span>🛁 Bathroom</span>
                        <span>🏬 Wardrobe</span>
                        <span>🏔️ Premium Accommodation</span>
                        <span>❄️ Air Conditioning</span>
                        <span>📶 Free Wi-Fi</span>
                        <span>🪑 Seating Area</span>
                        <span>📺 Smart TV</span>
                    </div>
                    <a href="booking_step1_dates.php?room=deluxe" class="btn btn-book">Book Now</a>
                </div>
            </div>

            <!-- Suite -->
            <div class="room-card">
                <img src="../assets/images/Suite/4.jpg" alt="Suite" onclick="openSuiteSlideshow()" style="cursor: pointer;" data-room-type="suite">
                <div class="room-info">
                    <h3>Suite</h3>
                    <p class="room-size">420 sq. ft.</p>
                    <p class="room-capacity"><?php echo isset($room_details['Suite']) ? $room_details['Suite']['max_adults'] : 4; ?> Adults + <?php echo isset($room_details['Suite']) ? $room_details['Suite']['max_children'] : 2; ?> Children</p>
                    <p class="room-price"><span id="suite-price"><?php echo formatPrice($display_prices['Suite']); ?></span> <span>per night</span></p>
                    <div class="room-amenities">
                        <span>🛏️ Beds</span>
                        <span>🛁 Bathroom</span>
                        <span>🏬 Wardrobe</span>
                        <span>🛋️ Combined Living Area</span>
                        <span>🏔️ Premium Accommodation</span>
                        <span>👨‍👩‍👧‍👦 Spacious</span>
                        <span>⭐ Full Amenities</span>
                        <span>❄️ Air Conditioning</span>
                        <span>📶 Free Wi-Fi</span>
                        <span>🪑 Seating Area</span>
                        <span>📺 Smart TV</span>
                    </div>
                    <a href="booking_step1_dates.php?room=suite" class="btn btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <p class="gst-note" style="max-width: 800px; margin: 2rem auto 0;">
            <small><strong>Note:</strong> All room prices are inclusive of applicable taxes.</small>
        </p>
    </div>
</section>

<!-- Room Amenities Included -->
<section class="room-amenities-included">
    <div class="container">
        <h2>All Rooms Include</h2>
        <div class="amenities-icons-grid">
            <div class="amenity-icon-item">
                <img src="../assets/images/Wifi.jpeg" alt="Free WiFi">
                <p>Free WiFi</p>
            </div>
            <div class="amenity-icon-item">
                <img src="../assets/images/breakfast.jpg" alt="Complimentary Breakfast">
                <p>Complimentary Breakfast</p>
            </div>
            <div class="amenity-icon-item">
                <img src="../assets/images/service.jpg" alt="Room Service">
                <p>Room Service</p>
            </div>
            <div class="amenity-icon-item">
                <img src="../assets/images/TV.jpg" alt="Cable TV">
                <p>Cable TV</p>
            </div>
        </div>
    </div>
</section>

<!-- Room Image Modal -->
<div id="roomImageModal" class="image-modal">
    <span class="image-modal-close" onclick="closeRoomImageModal()">&times;</span>
    <img class="image-modal-content" id="roomModalImage" alt="">
    <div class="image-modal-caption" id="roomModalCaption"></div>
    <!-- Slideshow arrows (hidden by default, shown for Suite) -->
    <button class="modal-slide-arrow modal-prev" id="modalPrevBtn" onclick="changeModalSlide(-1)" style="display: none;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>
    <button class="modal-slide-arrow modal-next" id="modalNextBtn" onclick="changeModalSlide(1)" style="display: none;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </button>
</div>

<!-- Clear localStorage for room prices (temporary fix) -->
<script>
// Clear any cached room prices from localStorage to use database prices
if (localStorage.getItem('roomPrices')) {
    localStorage.removeItem('roomPrices');
    console.log('Cleared cached room prices from localStorage');
}
</script>

<?php include 'includes/footer.php'; ?>
