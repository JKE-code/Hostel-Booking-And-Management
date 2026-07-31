<?php
// Step 4: Add-ons & Coupons
session_start();

// Validate that required booking data exists in session
if (!isset($_SESSION['booking']['checkin']) || 
    !isset($_SESSION['booking']['checkout']) || 
    !isset($_SESSION['booking']['rooms']) || 
    empty($_SESSION['booking']['rooms'])) {
    // Redirect to start if essential data is missing
    header('Location: booking_step1_dates.php');
    exit();
}

// Check if guest data exists, if not redirect to step 3
if (!isset($_SESSION['booking']['name']) || 
    !isset($_SESSION['booking']['mobile'])) {
    header('Location: booking_step3_guests.php');
    exit();
}

// Prevent caching to avoid form resubmission issues
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header">
    <h1>Book Your Stay</h1>
    <p>Complete your reservation in 5 simple steps</p>
</section>

<!-- Smart Offers & FOMO Section (DISABLED) -->
<!--
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
-->

<!-- Progress Stepper -->
<section class="booking-progress">
    <div class="container">
        <div class="progress-steps">
            <div class="progress-line">
                <div class="progress-line-fill" style="width: 75%;"></div>
            </div>
            <div class="progress-step completed" data-step="1">
                <div class="step-circle">✓</div>
                <div class="step-label">Select Rooms</div>
            </div>
            <div class="progress-step completed" data-step="2">
                <div class="step-circle">✓</div>
                <div class="step-label">Choose Dates</div>
            </div>
            <div class="progress-step completed" data-step="3">
                <div class="step-circle">✓</div>
                <div class="step-label">Guest Info</div>
            </div>
            <div class="progress-step active" data-step="4">
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
            
            <!-- STEP 4: Add-ons & Extras -->
            <div id="step-4" class="booking-section-wrapper active">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Add-ons & Coupons</h2>
                        </div>
                        <div class="section-subtitle">
                            <div class="section-icon">🎁</div>
                            <p>Enhance your stay with these optional extras and apply discount coupons</p>
                        </div>
                    </div>

                    <div class="addons-selection-grid">
                        <?php
                        // Fetch active add-ons from database
                        require_once '../config/db.php';
                        
                        $addon_icons = [
                            'Extra Bed' => '🛏️',
                            'Breakfast' => '🍳',
                            'Campfire' => '🔥',
                            'Dhimsa Dance' => '💃',
                            'Extra Person' => '👤'
                        ];
                        
                        try {
                            $stmt = $pdo->query("SELECT * FROM addons WHERE status = 'active' AND name != 'Extra Person' ORDER BY is_pinned DESC, name ASC");
                            $addons = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            foreach ($addons as $addon):
                                $addon_id = strtolower(str_replace(' ', '_', $addon['name']));
                                $icon = $addon_icons[$addon['name']] ?? '🎁';
                                $charge_text = $addon['charge_type'] === 'per_night' ? '/night' : '/session';
                        ?>
                            <div class="addon-card" data-addon="<?php echo $addon_id; ?>">
                                <input type="checkbox" id="<?php echo $addon_id; ?>" name="<?php echo $addon_id; ?>">
                                <div class="addon-card-content">
                                    <div class="addon-icon-large"><?php echo $icon; ?></div>
                                    <div class="addon-details-full">
                                        <h4><?php echo htmlspecialchars($addon['name']); ?></h4>
                                        <div class="addon-price-large">₹<span id="<?php echo $addon_id; ?>-price"><?php echo number_format($addon['price'], 0); ?></span><?php echo $charge_text; ?></div>
                                    </div>
                                </div>
                                <div class="addon-checkmark">✓</div>
                            </div>
                        <?php 
                            endforeach;
                        } catch (Exception $e) {
                            echo '<p style="color: #ef4444;">Error loading add-ons. Please try again.</p>';
                        }
                        ?>
                    </div>

                    <!-- Coupon Section -->
                    <div class="coupon-section" style="margin-top: 2rem; padding: 1.5rem; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <h4 style="color: #1a202c; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            🎫 <span>Have a Coupon Code?</span>
                        </h4>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
                            <!-- Phone Number Input -->
                            <div>
                                <label style="display: block; color: #374151; font-weight: 600; margin-bottom: 0.5rem;">
                                    Phone Number
                                </label>
                                <input type="tel" id="customer-phone" placeholder="10-digit mobile number" 
                                       style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                                       maxlength="10" pattern="[0-9]{10}">
                            </div>
                            
                            <!-- Coupon Code Input -->
                            <div>
                                <label style="display: block; color: #374151; font-weight: 600; margin-bottom: 0.5rem;">
                                    Coupon Code
                                </label>
                                <input type="text" id="coupon-code" placeholder="Enter coupon code" 
                                       style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; text-transform: uppercase;"
                                       maxlength="20">
                            </div>
                            
                            <!-- Verify Button -->
                            <button type="button" id="verify-coupon-btn" 
                                    style="padding: 0.75rem 1.5rem; background: #8b0000; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                                Apply Coupon
                            </button>
                        </div>
                        
                        <!-- Message Display -->
                        <div id="coupon-message" style="display: none; margin-top: 1rem; padding: 1rem; border-radius: 8px; font-weight: 600;"></div>
                        
                        <!-- Applied Coupon Display -->
                        <div id="discount-section" style="display: none; margin-top: 1rem; padding: 1rem; background: #dcfce7; border: 1px solid #16a34a; border-radius: 8px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="color: #15803d;">✓ Coupon Applied</strong>
                                    <div style="color: #166534; font-size: 0.875rem; margin-top: 0.25rem;">
                                        You saved: <strong>₹<span id="discount-amount">0</span></strong>
                                    </div>
                                </div>
                                <button type="button" id="remove-coupon-btn" 
                                        style="background: #dc2626; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">
                                    Remove
                                </button>
                            </div>
                        </div>
                        
                        <!-- Available Coupons -->
                        <div style="margin-top: 1rem; padding: 1rem; background: #f0f9ff; border-radius: 8px;">
                            <div style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.5rem;">Available Coupons:</div>
                            <div id="available-coupons-container" style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <span style="color: #64748b; font-size: 0.875rem;">Loading coupons...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tip Box -->
                    <div style="text-align: center; margin-top: 1.5rem; padding: 1rem; background: #fffbeb; border-radius: 8px; border: 1px solid #f59e0b;">
                        <p style="color: #92400e; margin: 0; font-size: 0.875rem;">💡 <strong>Tip:</strong> All add-ons are optional. You can skip this step if you don't need any extras.</p>
                    </div>

                    <div class="section-navigation">
                        <button type="button" class="btn-nav btn-prev" onclick="window.location.href='booking_step3_guests.php'">
                            ← Previous
                        </button>
                        <button type="button" class="btn-nav btn-next" id="nextStepBtn">
                            Next: Review & Payment →
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
// Load active coupons from database
function loadActiveCoupons() {
    fetch('get_active_coupons.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('available-coupons-container');
            
            if (data.success && data.coupons.length > 0) {
                container.innerHTML = '';
                
                data.coupons.forEach(coupon => {
                    const couponSpan = document.createElement('span');
                    couponSpan.className = 'coupon-badge';
                    couponSpan.textContent = coupon.code;
                    couponSpan.style.cssText = `
                        background: white; 
                        padding: 0.5rem 0.75rem; 
                        border-radius: 6px; 
                        font-size: 0.75rem; 
                        color: #8b0000; 
                        font-weight: 600;
                        cursor: pointer;
                        position: relative;
                        transition: all 0.2s ease;
                        border: 2px solid #e2e8f0;
                    `;
                    
                    // Add hover effect
                    couponSpan.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-2px)';
                        this.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
                        this.style.borderColor = '#8b0000';
                    });
                    
                    couponSpan.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = 'none';
                        this.style.borderColor = '#e2e8f0';
                    });
                    
                    // Add click to copy functionality
                    couponSpan.addEventListener('click', function() {
                        document.getElementById('coupon-code').value = coupon.code;
                        showMessage('Coupon code copied!', 'info');
                    });
                    
                    // Add tooltip if eligibility exists
                    if (coupon.has_eligibility) {
                        couponSpan.title = coupon.eligibility;
                        
                        // Create custom tooltip
                        const tooltip = document.createElement('div');
                        tooltip.className = 'coupon-tooltip';
                        tooltip.textContent = coupon.eligibility;
                        tooltip.style.cssText = `
                            position: absolute;
                            bottom: 100%;
                            left: 50%;
                            transform: translateX(-50%) translateY(-8px);
                            background: #1a202c;
                            color: white;
                            padding: 0.5rem 0.75rem;
                            border-radius: 6px;
                            font-size: 0.75rem;
                            white-space: nowrap;
                            opacity: 0;
                            pointer-events: none;
                            transition: opacity 0.2s ease;
                            z-index: 1000;
                            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
                        `;
                        
                        // Add arrow
                        const arrow = document.createElement('div');
                        arrow.style.cssText = `
                            position: absolute;
                            top: 100%;
                            left: 50%;
                            transform: translateX(-50%);
                            width: 0;
                            height: 0;
                            border-left: 6px solid transparent;
                            border-right: 6px solid transparent;
                            border-top: 6px solid #1a202c;
                        `;
                        tooltip.appendChild(arrow);
                        
                        couponSpan.style.position = 'relative';
                        couponSpan.appendChild(tooltip);
                        
                        // Show/hide tooltip on hover
                        couponSpan.addEventListener('mouseenter', function() {
                            tooltip.style.opacity = '1';
                        });
                        
                        couponSpan.addEventListener('mouseleave', function() {
                            tooltip.style.opacity = '0';
                        });
                    }
                    
                    container.appendChild(couponSpan);
                });
            } else if (data.success && data.coupons.length === 0) {
                container.innerHTML = '<span style="color: #64748b; font-size: 0.875rem;">No active coupons available at the moment</span>';
            } else {
                container.innerHTML = '<span style="color: #ef4444; font-size: 0.875rem;">Error loading coupons</span>';
            }
        })
        .catch(error => {
            console.error('Error loading coupons:', error);
            document.getElementById('available-coupons-container').innerHTML = 
                '<span style="color: #ef4444; font-size: 0.875rem;">Error loading coupons</span>';
        });
}

// Helper function to show messages
function showMessage(message, type) {
    const messageDiv = document.getElementById('coupon-message');
    messageDiv.textContent = message;
    messageDiv.style.display = 'block';
    
    if (type === 'success') {
        messageDiv.style.background = '#dcfce7';
        messageDiv.style.color = '#15803d';
        messageDiv.style.border = '1px solid #16a34a';
    } else if (type === 'error') {
        messageDiv.style.background = '#fee2e2';
        messageDiv.style.color = '#991b1b';
        messageDiv.style.border = '1px solid #dc2626';
    } else {
        messageDiv.style.background = '#dbeafe';
        messageDiv.style.color = '#1e40af';
        messageDiv.style.border = '1px solid #3b82f6';
    }
    
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 3000);
}

// Check if coupon is already applied in session
function checkExistingCoupon() {
    fetch('get_booking_summary.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.booking.coupon_code) {
                // Coupon already applied
                document.getElementById('coupon-code').value = data.booking.coupon_code;
                document.getElementById('discount-amount').textContent = data.booking.coupon_discount || 0;
                document.getElementById('discount-section').style.display = 'block';
                document.getElementById('coupon-code').disabled = true;
                document.getElementById('verify-coupon-btn').style.display = 'none';
                
                if (data.booking.customer_phone) {
                    document.getElementById('customer-phone').value = data.booking.customer_phone;
                    document.getElementById('customer-phone').disabled = true;
                }
            }
        })
        .catch(error => {
            console.error('Error checking existing coupon:', error);
        });
}

// Handle coupon verification
function verifyCoupon() {
    const couponCode = document.getElementById('coupon-code').value.trim().toUpperCase();
    const phone = document.getElementById('customer-phone').value.trim();
    const verifyBtn = document.getElementById('verify-coupon-btn');
    
    if (!couponCode) {
        showMessage('Please enter a coupon code', 'error');
        return;
    }
    
    if (!phone || !/^[0-9]{10}$/.test(phone)) {
        showMessage('Please enter a valid 10-digit phone number', 'error');
        return;
    }
    
    // Disable button during verification
    verifyBtn.disabled = true;
    verifyBtn.textContent = 'Verifying...';
    
    const formData = new FormData();
    formData.append('coupon_code', couponCode);
    formData.append('phone', phone);
    
    fetch('verify_coupon.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showMessage(data.message, 'success');
            
            // Display discount section
            document.getElementById('discount-amount').textContent = data.discount_formatted;
            document.getElementById('discount-section').style.display = 'block';
            
            // Hide coupon input section
            document.getElementById('coupon-code').disabled = true;
            document.getElementById('customer-phone').disabled = true;
            verifyBtn.style.display = 'none';
        } else {
            showMessage(data.error, 'error');
            verifyBtn.disabled = false;
            verifyBtn.textContent = 'Apply Coupon';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error verifying coupon. Please try again.', 'error');
        verifyBtn.disabled = false;
        verifyBtn.textContent = 'Apply Coupon';
    });
}

// Handle coupon removal
function removeCoupon() {
    // Clear session coupon via AJAX
    fetch('booking_step4_process.php', {
        method: 'POST',
        body: new FormData()
    })
    .then(() => {
        // Reset UI
        document.getElementById('discount-section').style.display = 'none';
        document.getElementById('coupon-code').value = '';
        document.getElementById('coupon-code').disabled = false;
        document.getElementById('customer-phone').disabled = false;
        document.getElementById('verify-coupon-btn').style.display = 'block';
        document.getElementById('verify-coupon-btn').disabled = false;
        document.getElementById('verify-coupon-btn').textContent = 'Apply Coupon';
        
        showMessage('Coupon removed', 'info');
    });
}

// Handle Next button click to save addon data
document.addEventListener('DOMContentLoaded', function() {
    // Load active coupons on page load
    loadActiveCoupons();
    
    // Check if coupon is already applied in session
    checkExistingCoupon();
    
    // Attach event listeners
    document.getElementById('verify-coupon-btn').addEventListener('click', verifyCoupon);
    document.getElementById('remove-coupon-btn').addEventListener('click', removeCoupon);
    
    // Allow Enter key to verify coupon
    document.getElementById('coupon-code').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            verifyCoupon();
        }
    });
    
    document.getElementById('customer-phone').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            verifyCoupon();
        }
    });
    
    const nextButton = document.getElementById('nextStepBtn');
    
    if (nextButton) {
        nextButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Collect addon selections
            const campfireCheckbox = document.getElementById('campfire');
            const dhimsaCheckbox = document.getElementById('dhimsa_dance');
            
            const formData = new FormData();
            formData.append('campfire', campfireCheckbox && campfireCheckbox.checked ? 'true' : 'false');
            formData.append('dhimsa', dhimsaCheckbox && dhimsaCheckbox.checked ? 'true' : 'false');
            
            // Check if coupon is applied
            const discountSection = document.getElementById('discount-section');
            if (discountSection && discountSection.style.display !== 'none') {
                const couponCode = document.getElementById('coupon-code').value;
                const discountAmount = document.getElementById('discount-amount').textContent;
                formData.append('coupon_code', couponCode);
                formData.append('coupon_discount', discountAmount);
            }
            
            // Disable button to prevent double submission
            nextButton.disabled = true;
            nextButton.textContent = 'Saving...';
            
            // Save to session via AJAX
            fetch('booking_step4_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Navigate to next step using replace to avoid back button issues
                    window.location.replace('booking_step5_review.php');
                } else {
                    alert('Error saving addon data. Please try again.');
                    nextButton.disabled = false;
                    nextButton.textContent = 'Next: Review & Payment →';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving addon data. Please try again.');
                nextButton.disabled = false;
                nextButton.textContent = 'Next: Review & Payment →';
            });
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
