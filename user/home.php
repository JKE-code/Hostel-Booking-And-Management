<?php
$page_title = 'Alluri Resorts - Premium Stay in Araku Valley';
$body_class = 'home-page';
include 'includes/header.php';
?>

<!-- Hero Slideshow -->
<section class="hero-slideshow">
    <div class="slideshow-container">
        <div class="slide fade active">
            <img src="../assets/images/Reort_Building.jpg" alt="Araku Valley Mountains">
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <h1>ALLURI RESORTS, ARAKU VALLEY</h1>
                <p>Experience the serene beauty of Araku Valley</p>
                <a href="booking_step1_dates.php" class="btn btn-hero">BOOK NOW</a>
            </div>
        </div>
        <div class="slide fade">
            <img src="../assets/images/Araku_Nature.jpg" alt="Misty Hills">
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <h1>NATURE'S PARADISE</h1>
                <p>Surrounded by lush green hills and coffee plantations</p>
                <a href="rooms.php" class="btn btn-hero">EXPLORE ROOMS</a>
            </div>
        </div>
        <div class="slide fade">
            <img src="../assets/images/Room.jpg" alt="Luxury Resort">
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <h1>PREMIUM COMFORT</h1>
                <p>World-class amenities in the heart of nature</p>
                <a href="amenities.php" class="btn btn-hero">VIEW AMENITIES</a>
            </div>
        </div>
        <button class="slide-arrow prev" onclick="changeSlide(-1)" aria-label="Previous slide">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <button class="slide-arrow next" onclick="changeSlide(1)" aria-label="Next slide">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
    </div>
    <div class="slideshow-dots">
        <span class="dot active" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
    </div>
</section>

<!-- About Section -->
<section class="about">
    <div class="container">
        <h1 class="section-heading" style="font-family: var(--font-accent); font-size: calc(1em + 35px);">Welcome to Alluri Resorts</h1>
        <p>Nestled in the picturesque Araku Valley, Alluri Resorts offers a perfect blend of comfort, luxury, and nature. Our resort is your gateway to experiencing the pristine beauty, rich culture, and warm hospitality of Andhra Pradesh's most enchanting hill station.</p>
        <div class="features">
            <div class="feature-card">
                <div class="feature-image">
                    <img src="../assets/images/Araku_Nature.jpg" alt="Nature's Paradise">
                </div>
                <h3>Nature's Paradise</h3>
                <p>Wake up to green valleys, fresh mountain air, and the peaceful charm of Araku's untouched landscapes.</p>
            </div>
            <div class="feature-card">
                <div class="feature-image">
                    <img src="../assets/images/Room.jpg" alt="Premium Comfort">
                </div>
                <h3>Premium Comfort</h3>
                <p>Enjoy soft bedding, spacious rooms, and thoughtful amenities designed to make your stay calm, refreshing, and effortless.</p>
            </div>
            <div class="feature-card">
                <div class="feature-image">
                    <img src="../assets/images/dhimsa.jpg" alt="Cultural Experience">
                </div>
                <h3>Cultural Experience</h3>
                <p>Immerse yourself in local traditions with vibrant Dhimsa dances and authentic cultural moments that bring Araku's spirit to life.</p>
            </div>
        </div>
    </div>
</section>

<!-- Highlight Section -->
<section class="highlights">
    <div class="container">
        <div class="highlights-content">
            <div class="highlights-image">
                <img src="../assets/images/Alluri_Helpdesk.jpg" alt="Alluri Resorts Reception">
            </div>
            <div class="highlights-text">
                <p class="highlights-label">WHY CHOOSE US</p>
                <h2><span>Your personalized journey into</span> <span class="highlight-accent">Araku's unique heritage</span></h2>
                <p class="highlights-description">We focus on the essential comforts you need and the authentic experiences you crave, ensuring a smooth, engaging, and memorable visit to the valley.</p>
                <ul class="highlight-list">
                    <li><span class="checkmark">✓</span> <span>Authentic Cultural Immersion</span></li>
                    <li><span class="checkmark">✓</span> <span>Prime Location for Exploration</span></li>
                    <li><span class="checkmark">✓</span> <span>Complimentary South Indian Breakfast</span></li>
                    <li><span class="checkmark">✓</span> <span>Comfortable, Clean Accommodation</span></li>
                    <li><span class="checkmark">✓</span> <span>Free Wi-Fi & Secure Parking</span></li>
                    <li><span class="checkmark">✓</span> <span>Fireside Fellowship</span></li>
                </ul>
                <a href="amenities.php" class="btn btn-highlights">LEARN MORE</a>
            </div>
        </div>
    </div>
</section>

<!-- Guest Highlights Section -->
<section class="guest-highlights">
    <div class="container">
        <h2>Guest Highlights</h2>
        <p class="section-subtitle">See what our guests are experiencing at Alluri Resorts</p>
        <div class="guest-carousel-container">
            <button class="carousel-nav prev-guest" onclick="moveGuestCarousel(-1)" aria-label="Previous">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <div class="guest-carousel">
                <div class="guest-carousel-track">
                    <!-- Guest Highlight 1 -->
                    <div class="guest-highlight-card">
                        <div class="guest-image">
                            <img src="../assets/images/IMG-20251102-WA0001.jpg" alt="Guest Experience">
                        </div>
                        <div class="guest-review">
                            <div class="guest-rating">⭐⭐⭐⭐⭐</div>
                            <p class="guest-comment">"Amazing stay! The rooms were clean and comfortable. The breakfast was delicious and the staff was very helpful."</p>
                            <p class="guest-name">- Rajesh Kumar</p>
                        </div>
                    </div>
                    <!-- Guest Highlight 2 -->
                    <div class="guest-highlight-card">
                        <div class="guest-image">
                            <img src="../assets/images/IMG-20251102-WA0003.jpg" alt="Guest Experience">
                        </div>
                        <div class="guest-review">
                            <div class="guest-rating">⭐⭐⭐⭐⭐</div>
                            <p class="guest-comment">"Perfect location to explore Araku Valley. The resort is peaceful and the views are breathtaking."</p>
                            <p class="guest-name">- Priya Sharma</p>
                        </div>
                    </div>
                    <!-- Guest Highlight 3 -->
                    <div class="guest-highlight-card">
                        <div class="guest-image">
                            <img src="../assets/images/IMG-20251102-WA0005.jpg" alt="Guest Experience">
                        </div>
                        <div class="guest-review">
                            <div class="guest-rating">⭐⭐⭐⭐⭐</div>
                            <p class="guest-comment">"Loved the cultural experience! The Dhimsa dance performance was mesmerizing. Highly recommend!"</p>
                            <p class="guest-name">- Amit Patel</p>
                        </div>
                    </div>
                    <!-- Guest Highlight 4 -->
                    <div class="guest-highlight-card">
                        <div class="guest-image">
                            <img src="../assets/images/IMG-20251102-WA0007.jpg" alt="Guest Experience">
                        </div>
                        <div class="guest-review">
                            <div class="guest-rating">⭐⭐⭐⭐⭐</div>
                            <p class="guest-comment">"Great family vacation spot. Kids enjoyed the campfire and the nearby attractions. Will visit again!"</p>
                            <p class="guest-name">- Sneha Reddy</p>
                        </div>
                    </div>
                    <!-- Guest Highlight 5 -->
                    <div class="guest-highlight-card">
                        <div class="guest-image">
                            <img src="../assets/images/IMG-20251102-WA0009.jpg" alt="Guest Experience">
                        </div>
                        <div class="guest-review">
                            <div class="guest-rating">⭐⭐⭐⭐⭐</div>
                            <p class="guest-comment">"Excellent hospitality and service. The staff went above and beyond to make our stay comfortable."</p>
                            <p class="guest-name">- Vikram Singh</p>
                        </div>
                    </div>
                    <!-- Guest Highlight 6 -->
                    <div class="guest-highlight-card">
                        <div class="guest-image">
                            <img src="../assets/images/IMG-20251102-WA0011.jpg" alt="Guest Experience">
                        </div>
                        <div class="guest-review">
                            <div class="guest-rating">⭐⭐⭐⭐⭐</div>
                            <p class="guest-comment">"Beautiful resort surrounded by nature. Perfect getaway from city life. The coffee plantations nearby are a must-visit!"</p>
                            <p class="guest-name">- Meera Iyer</p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-nav next-guest" onclick="moveGuestCarousel(1)" aria-label="Next">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
        <div class="carousel-dots" id="guest-dots"></div>
    </div>
</section>

<!-- Location Section -->
<section class="location">
    <div class="container">
        <h2 style="font-family: var(--font-accent); font-size: calc(1em + 30px);">Find Us Here</h2>
        <div class="location-content">
            <div class="location-info">
                <h3>Alluri Resorts</h3>
                <p>Araku - Visakhapatnam Road<br>Opposite to ITI (Govt)<br>Ravvalaguda, Araku Valley<br>Andhra Pradesh 531149</p>
                <div class="contact-details">
                    <p><strong>Landline:</strong> <a href="tel:08936249888">08936-249888</a></p>
                    <p><strong>Mobile/WhatsApp:</strong> <a href="https://wa.me/919392952669" target="_blank">+91 93929 52669</a></p>
                </div>
                <a href="https://wa.me/919392952669" target="_blank" class="btn btn-secondary">Chat on WhatsApp</a>
            </div>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4493.035067087091!2d82.89168017579358!3d18.31250167549566!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a3a4b98e9f479ed%3A0x404b1c7567a8599a!2sAlluri%20Resorts!5e1!3m2!1sen!2sin!4v1763569834275!5m2!1sen!2sin" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>