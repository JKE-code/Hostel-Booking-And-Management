<?php
$page_title = 'Amenities - Alluri Resorts';
$body_class = 'amenities-page';

// Fetch breakfast addon price from database
$breakfast_price = 200; // Default fallback

try {
    require_once '../config/db.php';
    
    if (isset($pdo) && $pdo instanceof PDO) {
        $stmt = $pdo->prepare("SELECT price FROM addons WHERE name = 'Breakfast' AND status = 'active' LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $breakfast_price = $result['price'];
        }
    }
} catch (Exception $e) {
    // Use default price
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <h1>Our Amenities</h1>
    <p>Enjoy world-class facilities and services</p>
</section>

<!-- Amenities Section -->
<section class="amenities-section">
    <div class="container">
        <div class="amenities-grid-large">
            <!-- Free Wi-Fi -->
            <div class="amenity-card">
                <img src="../assets/images/Wifi_Main.png" alt="Free Wi-Fi">
                <div class="amenity-icon-large">📶</div>
                <h3>Free Wi-Fi</h3>
                <p>High-speed internet connectivity throughout the resort to keep you connected with the world.</p>
            </div>

            <!-- Free Parking -->
            <div class="amenity-card">
                <img src="https://images.unsplash.com/photo-1590674899484-d5640e854abe?w=400&h=250&fit=crop" alt="Free Parking">
                <div class="amenity-icon-large">🚗</div>
                <h3>Free Car Parking</h3>
                <p>Secure, ample parking space available for all guests at no additional charge.</p>
            </div>

            <!-- Complimentary Breakfast -->
            <div class="amenity-card">
                <img src="../assets/images/breakfast.jpg" alt="Breakfast">
                <div class="amenity-icon-large">🍽️</div>
                <h3>Complimentary Breakfast</h3>
                <p>Delicious authentic breakfast included with every room booking. See menu details below.</p>
            </div>

            <!-- 24/7 Service -->
            <div class="amenity-card">
                <img src="../assets/images/service.jpg" alt="24/7 Service">
                <div class="amenity-icon-large">🛎️</div>
                <h3>24/7 Guest Service</h3>
                <p>Round-the-clock customer support for all your needs and queries.</p>
            </div>
        </div>
    </div>
</section>

<!-- Special Cultural Experiences Section -->
<section class="cultural-experiences-section">
    <div class="container">
        <h2>Special Cultural Experiences</h2>
        <p class="section-subtitle">Enhance your stay with authentic Araku cultural experiences (chargeable)</p>

        <!-- Dhimsa Dance - Image Left, Text Right -->
        <div class="experience-item">
            <div class="experience-image">
                <img src="../assets/images/dhimsa.jpg" alt="Dhimsa Dance">
            </div>
            <div class="experience-content">
                <div class="experience-icon">🎭</div>
                <h3>Dhimsa Cultural Dance</h3>
                <p>Experience authentic Dhimsa dance performance by local tribal artists. A mesmerizing 1-hour cultural show showcasing traditional music and dance.</p>
            </div>
        </div>

        <!-- Campfire - Text Left, Image Right -->
        <div class="experience-item">
            <div class="experience-content">
                <div class="experience-icon">🔥</div>
                <h3>Campfire Nights</h3>
                <p>Enjoy cozy bonfire evenings with friends and family under the starlit Araku sky. Perfect for creating memorable moments.</p>
            </div>
            <div class="experience-image">
                <img src="../assets/images/Campfire.jpg" alt="Campfire">
            </div>
        </div>
    </div>
</section>

<!-- Dhimsa Dance Video Section -->
<section class="dhimsa-video-section">
    <div class="container">
        <h2>Experience Dhimsa Dance</h2>
        <p class="video-description">Watch the vibrant and energetic Dhimsa dance, a traditional tribal dance form of the Araku Valley. This captivating performance showcases the rich cultural heritage of the local tribal communities.</p>
        <div class="video-wrapper">
            <video id="dhimsa-video" autoplay loop muted playsinline poster="../assets/images/dhimsa.jpg">
                <source src="../assets/images/Dhimsa_Vid.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <button type="button" id="video-mute-btn" class="video-mute-button" title="Click to unmute">
                <span id="mute-icon">🔇</span>
            </button>
        </div>
    </div>
</section>

<!-- Breakfast Menu -->
<section class="breakfast-menu">
    <div class="container">
        <h2>Breakfast Menu (Complimentary)</h2>
        <div class="menu-grid">
            <?php
            try {
                // Fetch menu items grouped by category
                $stmt = $pdo->prepare("
                    SELECT mc.name as category_name, mi.name as item_name
                    FROM menu_items mi
                    JOIN menu_categories mc ON mi.category_id = mc.id
                    WHERE mi.status = 'active' AND mc.status = 'active'
                    ORDER BY mc.id, mi.id
                ");
                $stmt->execute();
                $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Group items by category
                $menu_by_category = [];
                foreach ($menu_items as $item) {
                    $menu_by_category[$item['category_name']][] = $item['item_name'];
                }
                
                // Display each category
                foreach ($menu_by_category as $category => $items) {
                    echo '<div class="menu-item">';
                    echo '<h3>' . htmlspecialchars($category) . '</h3>';
                    echo '<ul>';
                    foreach ($items as $item) {
                        echo '<li>' . htmlspecialchars($item) . '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }
                
                // Fallback if no items found
                if (empty($menu_by_category)) {
                    echo '<div class="menu-item"><p>Menu items will be updated soon.</p></div>';
                }
            } catch (Exception $e) {
                // Fallback to static menu on error
                ?>
                <div class="menu-item">
                    <h3>Main Items</h3>
                    <ul>
                        <li>Idli (2 pieces)</li>
                        <li>Vada (1 piece)</li>
                        <li>Puri with curry (2 pieces) OR Upma</li>
                    </ul>
                </div>
                <div class="menu-item">
                    <h3>Accompaniments</h3>
                    <ul>
                        <li>Groundnut or Coconut chutney</li>
                        <li>Dry Chana Dal with Coconut</li>
                        <li>Ginger Chutney</li>
                        <li>Ghee</li>
                        <li>Karampodi or Pappula Podi</li>
                        <li>Sambar</li>
                    </ul>
                </div>
                <div class="menu-item">
                    <h3>Beverages</h3>
                    <ul>
                        <li>Coffee or Tea (1 cup)</li>
                    </ul>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="breakfast-note">
            <p><strong>Additional Breakfast:</strong> ₹<?php echo number_format($breakfast_price, 0); ?> per extra person</p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
