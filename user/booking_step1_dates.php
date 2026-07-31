<?php
// Step 1: Date Selection (NEW - Dates come first now)
session_start();

// Initialize session for booking if not exists
if (!isset($_SESSION['booking'])) {
    $_SESSION['booking'] = [];
}

// Set page variables for header
$page_title = 'Book Your Stay - Choose Dates | Alluri Resorts';
$body_class = 'booking_step1_dates-page';
?>
<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header">
    <h1>Book Your Stay</h1>
    <p>Complete your reservation in 5 simple steps</p>
</section>

<!-- Smart Offers & FOMO Section -->
<section class="smart-offers-section">
    <div class="container">
        <div class="offers-banner">
            <div class="offer-item">
                <span class="offer-icon">⚡</span>
                <span class="offer-text"><strong>10% OFF</strong> on early bookings (7+ days advance)</span>
            </div>
            <div class="offer-item">
                <span class="offer-icon">🎉</span>
                <span class="offer-text"><strong>₹500 OFF</strong> on 2-night stays</span>
            </div>
            <div class="offer-item">
                <span class="offer-icon">🌟</span>
                <span class="offer-text"><strong>₹850 OFF</strong> on 5+ night stays</span>
            </div>
        </div>
        <div class="fomo-alerts" id="fomo-alerts"></div>
    </div>
</section>

<!-- Progress Stepper -->
<section class="booking-progress">
    <div class="container">
        <div class="progress-steps">
            <div class="progress-line">
                <div class="progress-line-fill" style="width: 0%;"></div>
            </div>
            <div class="progress-step active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Choose Dates</div>
            </div>
            <div class="progress-step" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Select Rooms</div>
            </div>
            <div class="progress-step" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Guest Info</div>
            </div>
            <div class="progress-step" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Add-ons & Coupons</div>
            </div>
            <div class="progress-step" data-step="5">
                <div class="step-circle">5</div>
                <div class="step-label">Payment</div>
            </div>
        </div>
    </div>
</section>

<!-- Booking Section -->
<section class="booking-section">
    <div class="container">
        <div class="booking-container">
            
            <!-- STEP 1: Date Selection -->
            <div id="step-1" class="booking-section-wrapper active">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Choose Your Dates</h2>
                        </div>
                        <div class="section-subtitle">
                            <div class="section-icon">📅</div>
                            <p>Select check-in and check-out dates for your stay</p>
                        </div>
                    </div>

                    <form id="dateForm" method="POST" action="booking_step2_rooms.php">
                        <div class="form-grid" style="max-width: 600px; margin: 0 auto;">
                            <div class="form-group">
                                <label for="checkin">Check-in Date <span class="required">*</span></label>
                                <input type="date" id="checkin" name="checkin" required 
                                       value="<?php echo $_SESSION['booking']['checkin'] ?? ''; ?>"
                                       style="padding: 1rem; font-size: 1rem;">
                            </div>
                            <div class="form-group">
                                <label for="checkout">Check-out Date <span class="required">*</span></label>
                                <input type="date" id="checkout" name="checkout" required 
                                       value="<?php echo $_SESSION['booking']['checkout'] ?? ''; ?>"
                                       style="padding: 1rem; font-size: 1rem;">
                            </div>
                        </div>

                        <div style="text-align: center; margin-top: 2rem; padding: 1.5rem; background: #f0fdf4; border-radius: 10px; border: 2px solid #10b981;">
                            <div style="font-size: 1rem; color: #065f46; margin-bottom: 0.5rem;">Number of Nights</div>
                            <div style="font-size: 2.5rem; font-weight: 700; color: #10b981;" id="nights_display">0</div>
                        </div>

                        <div class="section-navigation">
                            <button type="submit" class="btn-nav btn-next">
                                Next: Select Rooms →
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
// Set minimum dates
const today = new Date().toISOString().split('T')[0];
const checkinInput = document.getElementById('checkin');
const checkoutInput = document.getElementById('checkout');

checkinInput.min = today;
checkoutInput.min = today;

// Calculate nights
function calculateNights() {
    const checkin = new Date(checkinInput.value);
    const checkout = new Date(checkoutInput.value);
    
    if (checkin && checkout && checkout > checkin) {
        const nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
        document.getElementById('nights_display').textContent = nights;
        return nights;
    } else {
        document.getElementById('nights_display').textContent = '0';
        return 0;
    }
}

checkinInput.addEventListener('change', function() {
    checkoutInput.min = this.value;
    calculateNights();
});

checkoutInput.addEventListener('change', calculateNights);

// Calculate on page load if dates are set
if (checkinInput.value && checkoutInput.value) {
    calculateNights();
}

// Form validation and submission
document.getElementById('dateForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    
    const nights = calculateNights();
    if (nights < 1) {
        alert('Please select valid check-in and check-out dates. Check-out must be at least 1 day after check-in.');
        return;
    }
    
    // Get form data
    const checkin = checkinInput.value;
    const checkout = checkoutInput.value;
    
    // Use AJAX to store in session
    fetch('booking_step1_process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `checkin=${encodeURIComponent(checkin)}&checkout=${encodeURIComponent(checkout)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect using GET (no form resubmission issue)
            window.location.replace('booking_step2_rooms.php');
        } else {
            alert('Error saving dates. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving dates. Please try again.');
    });
});
</script>

<script>
// NUCLEAR FIX: Force navbar scroll behavior - GUARANTEED TO WORK
(function() {
    'use strict';
    
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    
    function forceNavbarScroll() {
        const scrolled = window.scrollY > 50;
        
        if (scrolled) {
            navbar.classList.add('scrolled');
            
            // Force inline styles with !important - OVERRIDES EVERYTHING
            navbar.style.setProperty('background-color', 'rgba(255, 255, 255, 0.98)', 'important');
            navbar.style.setProperty('background', 'rgba(255, 255, 255, 0.98)', 'important');
            navbar.style.setProperty('box-shadow', '0 2px 15px rgba(0, 0, 0, 0.1)', 'important');
            
            // Update text colors
            const links = navbar.querySelectorAll('.nav-menu a');
            links.forEach(link => {
                if (!link.classList.contains('btn-navbook') && !link.classList.contains('nav-book-btn')) {
                    link.style.setProperty('color', '#000', 'important');
                    link.style.setProperty('text-shadow', 'none', 'important');
                }
            });
        } else {
            navbar.classList.remove('scrolled');
            
            // Remove inline styles to show transparent background
            navbar.style.removeProperty('background-color');
            navbar.style.removeProperty('background');
            navbar.style.removeProperty('box-shadow');
            
            // Reset text colors
            const links = navbar.querySelectorAll('.nav-menu a');
            links.forEach(link => {
                if (!link.classList.contains('btn-navbook') && !link.classList.contains('nav-book-btn')) {
                    link.style.removeProperty('color');
                    link.style.removeProperty('text-shadow');
                }
            });
        }
    }
    
    // Run immediately
    forceNavbarScroll();
    
    // Run on scroll with performance optimization
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                forceNavbarScroll();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
})();
</script>

<?php include 'includes/footer.php'; ?>
