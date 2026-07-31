<?php
// Step 3: Guest Information
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

// Prevent caching to avoid form resubmission issues
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Fetch room types data for capacity calculation
$room_types_data = [];
try {
    require_once '../config/db.php';
    
    if (isset($pdo) && $pdo instanceof PDO) {
        $stmt = $pdo->prepare("SELECT name, max_adults, max_children FROM room_types WHERE status = 'active'");
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name_key = strtolower(str_replace(['-', ' '], '', $row['name']));
            $room_types_data[$name_key] = [
                'max_adults' => $row['max_adults'],
                'max_children' => $row['max_children']
            ];
        }
    }
} catch (Exception $e) {
    // Use defaults if database fails
    $room_types_data = [
        'nonac' => ['max_adults' => 2, 'max_children' => 1],
        'ac' => ['max_adults' => 2, 'max_children' => 1],
        'deluxe' => ['max_adults' => 2, 'max_children' => 1],
        'suite' => ['max_adults' => 4, 'max_children' => 2]
    ];
}
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
                <div class="progress-line-fill" style="width: 50%;"></div>
            </div>
            <div class="progress-step completed" data-step="1">
                <div class="step-circle">✓</div>
                <div class="step-label">Select Rooms</div>
            </div>
            <div class="progress-step completed" data-step="2">
                <div class="step-circle">✓</div>
                <div class="step-label">Choose Dates</div>
            </div>
            <div class="progress-step active" data-step="3">
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
            
            <!-- STEP 3: Guest Information -->
            <div id="step-3" class="booking-section-wrapper active">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Guest Information</h2>
                        </div>
                        <div class="section-subtitle">
                            <div class="section-icon">🧑</div>
                            <p>Tell us about yourself and your guests</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" required placeholder="Enter your full name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required placeholder="your.email@example.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mobile">Mobile Number <span class="required">*</span></label>
                        <div style="position: relative;">
                            <input type="tel" id="mobile" name="mobile" required placeholder="+91 98765 43210" maxlength="10">
                            <div id="mobile-tooltip" style="display: none; position: absolute; top: -35px; left: 0; background: #dc2626; color: white; padding: 0.5rem 0.75rem; border-radius: 6px; font-size: 0.875rem; white-space: nowrap; z-index: 10; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                <span id="mobile-tooltip-text">Ensure 10-digits</span>
                                <div style="position: absolute; bottom: -4px; left: 20px; width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 4px solid #dc2626;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));">
                        <div class="form-group">
                            <label for="adults">Adults <span class="required">*</span></label>
                            <input type="number" id="adults" name="adults" min="1" value="2" required>
                            <small id="adults-capacity-info" style="color: #666;"></small>
                        </div>
                        <div class="form-group">
                            <label for="children">
                                Children 
                                <span class="info-icon">i</span>
                                <span class="info-tooltip">Considered in this category up to age 12</span>
                            </label>
                            <input type="number" id="children" name="children" min="0" value="0">
                            <small id="children-capacity-info" style="color: #666;"></small>
                        </div>
                        <div class="form-group">
                            <label for="extra_persons">Extra Persons</label>
                            <input type="number" id="extra_persons" name="extra_persons" min="0" max="8" value="0">
                            <small>₹500/person/night</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="special_requests">Special Requests (Optional)</label>
                        <textarea id="special_requests" name="special_requests" rows="3" placeholder="Any special requirements or preferences?"></textarea>
                    </div>

                    <div class="section-navigation">
                        <button type="button" class="btn-nav btn-prev" onclick="window.location.href='booking_step2_rooms.php'">
                            ← Previous
                        </button>
                        <button type="button" class="btn-nav btn-next" id="nextStepBtn">
                            Next: Add-ons & Extras →
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
// Pass room types data to JavaScript
const roomTypesCapacity = <?php echo json_encode($room_types_data); ?>;
const selectedRooms = <?php echo json_encode($_SESSION['booking']['rooms'] ?? ['nonac' => 0, 'ac' => 0, 'deluxe' => 0, 'suite' => 0]); ?>;

// Calculate room capacity and handle guest count logic
document.addEventListener('DOMContentLoaded', function() {
    const adultsInput = document.getElementById('adults');
    const childrenInput = document.getElementById('children');
    const extraPersonsInput = document.getElementById('extra_persons');
    const adultsCapacityInfo = document.getElementById('adults-capacity-info');
    const childrenCapacityInfo = document.getElementById('children-capacity-info');
    
    let adultsMessageTimeout = null;
    let childrenMessageTimeout = null;
    
    // Calculate total capacity based on selected rooms from session
    function calculateCapacity() {
        let totalMaxAdults = 0;
        let totalMaxChildren = 0;
        
        for (const [roomType, quantity] of Object.entries(selectedRooms)) {
            if (quantity > 0 && roomTypesCapacity[roomType]) {
                totalMaxAdults += roomTypesCapacity[roomType].max_adults * quantity;
                totalMaxChildren += roomTypesCapacity[roomType].max_children * quantity;
            }
        }
        
        return {
            maxAdults: totalMaxAdults || 2, // Default to 2 if no rooms selected
            maxChildren: totalMaxChildren || 1 // Default to 1 if no rooms selected
        };
    }
    
    // Fade out and hide capacity message
    function fadeOutCapacityMessage(element, timeoutVar) {
        // Clear existing timeout for this specific element
        if (timeoutVar === 'adults' && adultsMessageTimeout) {
            clearTimeout(adultsMessageTimeout);
        } else if (timeoutVar === 'children' && childrenMessageTimeout) {
            clearTimeout(childrenMessageTimeout);
        }
        
        const timeout = setTimeout(function() {
            element.style.transition = 'opacity 0.5s ease';
            element.style.opacity = '0';
            setTimeout(function() {
                if (element.textContent.includes('as extra')) {
                    element.textContent = '';
                }
                element.style.opacity = '1';
            }, 500);
        }, 3000); // 3 second delay
        
        // Store timeout reference
        if (timeoutVar === 'adults') {
            adultsMessageTimeout = timeout;
        } else if (timeoutVar === 'children') {
            childrenMessageTimeout = timeout;
        }
    }
    
    // Update adults capacity info
    function updateAdultsCapacity() {
        const capacity = calculateCapacity();
        let adultsValue = parseInt(adultsInput.value) || 0;
        const childrenValue = parseInt(childrenInput.value) || 0;
        
        // Enforce maximum limit (capacity + 8 extra persons max)
        const maxAllowedAdults = capacity.maxAdults + 8;
        if (adultsValue > maxAllowedAdults) {
            adultsValue = maxAllowedAdults;
            adultsInput.value = maxAllowedAdults;
        }
        
        // Enforce minimum
        if (adultsValue < 1) {
            adultsValue = 1;
            adultsInput.value = 1;
        }
        
        // Update the max attribute dynamically
        adultsInput.setAttribute('max', maxAllowedAdults);
        
        // Calculate overflow
        let adultsOverflow = Math.max(0, adultsValue - capacity.maxAdults);
        let childrenOverflow = Math.max(0, childrenValue - capacity.maxChildren);
        
        // Total extra persons (capped at 8)
        let totalExtra = Math.min(8, adultsOverflow + childrenOverflow);
        
        // Update extra persons field (only if user hasn't manually edited it)
        if (!extraPersonsInput.dataset.manuallyEdited) {
            extraPersonsInput.value = totalExtra;
        }
        
        // Update adults capacity info message
        if (adultsValue > capacity.maxAdults) {
            adultsCapacityInfo.textContent = `Max capacity: ${capacity.maxAdults} (${adultsOverflow} as extra)`;
            adultsCapacityInfo.style.color = '#d97706';
            fadeOutCapacityMessage(adultsCapacityInfo, 'adults');
        } else {
            adultsCapacityInfo.textContent = `Max capacity: ${capacity.maxAdults}`;
            adultsCapacityInfo.style.color = '#666';
        }
        
        // Validate extra persons cap
        validateExtraPersons();
    }
    
    // Update children capacity info
    function updateChildrenCapacity() {
        const capacity = calculateCapacity();
        const adultsValue = parseInt(adultsInput.value) || 0;
        let childrenValue = parseInt(childrenInput.value) || 0;
        
        // Enforce maximum limit (capacity + 8 extra persons max)
        const maxAllowedChildren = capacity.maxChildren + 8;
        if (childrenValue > maxAllowedChildren) {
            childrenValue = maxAllowedChildren;
            childrenInput.value = maxAllowedChildren;
        }
        
        // Enforce minimum
        if (childrenValue < 0) {
            childrenValue = 0;
            childrenInput.value = 0;
        }
        
        // Update the max attribute dynamically
        childrenInput.setAttribute('max', maxAllowedChildren);
        
        // Calculate overflow
        let adultsOverflow = Math.max(0, adultsValue - capacity.maxAdults);
        let childrenOverflow = Math.max(0, childrenValue - capacity.maxChildren);
        
        // Total extra persons (capped at 8)
        let totalExtra = Math.min(8, adultsOverflow + childrenOverflow);
        
        // Update extra persons field (only if user hasn't manually edited it)
        if (!extraPersonsInput.dataset.manuallyEdited) {
            extraPersonsInput.value = totalExtra;
        }
        
        // Update children capacity info message
        if (childrenValue > capacity.maxChildren) {
            childrenCapacityInfo.textContent = `Max capacity: ${capacity.maxChildren} (${childrenOverflow} as extra)`;
            childrenCapacityInfo.style.color = '#d97706';
            fadeOutCapacityMessage(childrenCapacityInfo, 'children');
        } else {
            childrenCapacityInfo.textContent = `Max capacity: ${capacity.maxChildren}`;
            childrenCapacityInfo.style.color = '#666';
        }
        
        // Validate extra persons cap
        validateExtraPersons();
    }
    
    // Validate and enforce limits on blur (when user leaves the field)
    adultsInput.addEventListener('blur', function() {
        updateAdultsCapacity();
    });
    
    childrenInput.addEventListener('blur', function() {
        updateChildrenCapacity();
    });
    
    // Also validate on change event
    adultsInput.addEventListener('change', function() {
        updateAdultsCapacity();
    });
    
    childrenInput.addEventListener('change', function() {
        updateChildrenCapacity();
    });
    
    // Validate extra persons field
    function validateExtraPersons() {
        const extraValue = parseInt(extraPersonsInput.value) || 0;
        if (extraValue > 8) {
            extraPersonsInput.value = 8; // Cap at 8
            extraPersonsInput.style.borderColor = '#dc2626';
            extraPersonsInput.style.backgroundColor = '#fee2e2';
        } else {
            extraPersonsInput.style.borderColor = '';
            extraPersonsInput.style.backgroundColor = '';
        }
    }
    
    // Track manual edits to extra persons field
    extraPersonsInput.addEventListener('input', function() {
        extraPersonsInput.dataset.manuallyEdited = 'true';
        validateExtraPersons();
    });
    
    // Reset manual edit flag and update when adults change
    adultsInput.addEventListener('input', function() {
        delete extraPersonsInput.dataset.manuallyEdited;
        updateAdultsCapacity();
    });
    
    // Reset manual edit flag and update when children change
    childrenInput.addEventListener('input', function() {
        delete extraPersonsInput.dataset.manuallyEdited;
        updateChildrenCapacity();
    });
    
    // Prevent arrow keys from exceeding limits
    adultsInput.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
            setTimeout(function() {
                updateAdultsCapacity();
            }, 0);
        }
    });
    
    childrenInput.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
            setTimeout(function() {
                updateChildrenCapacity();
            }, 0);
        }
    });
    
    // Initialize on page load
    const capacity = calculateCapacity();
    
    // Set dynamic max attributes
    adultsInput.setAttribute('max', capacity.maxAdults + 8);
    childrenInput.setAttribute('max', capacity.maxChildren + 8);
    
    adultsCapacityInfo.textContent = `Max capacity: ${capacity.maxAdults}`;
    adultsCapacityInfo.style.color = '#666';
    childrenCapacityInfo.textContent = `Max capacity: ${capacity.maxChildren}`;
    childrenCapacityInfo.style.color = '#666';
    
    // Mobile number validation
    const mobileInput = document.getElementById('mobile');
    const mobileTooltip = document.getElementById('mobile-tooltip');
    const mobileTooltipText = document.getElementById('mobile-tooltip-text');
    let tooltipTimeout = null;
    
    function showMobileTooltip(message) {
        mobileTooltipText.textContent = message;
        mobileTooltip.style.display = 'block';
        mobileInput.style.borderColor = '#dc2626';
        
        // Auto-hide after 3 seconds
        if (tooltipTimeout) clearTimeout(tooltipTimeout);
        tooltipTimeout = setTimeout(function() {
            mobileTooltip.style.display = 'none';
            mobileInput.style.borderColor = '';
        }, 3000);
    }
    
    function hideMobileTooltip() {
        mobileTooltip.style.display = 'none';
        mobileInput.style.borderColor = '';
        if (tooltipTimeout) clearTimeout(tooltipTimeout);
    }
    
    function validateMobile() {
        const mobileValue = mobileInput.value.trim();
        return /^[0-9]{10}$/.test(mobileValue);
    }
    
    // Only remove non-numeric characters, don't show errors while typing
    mobileInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        // Hide tooltip if user is correcting the input
        if (mobileTooltip.style.display === 'block') {
            hideMobileTooltip();
        }
    });
    
    // Add form validation before navigation
    const nextButton = document.getElementById('nextStepBtn');
    if (nextButton) {
        nextButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            const mobileValue = mobileInput.value.trim();
            
            if (mobileValue.length === 0) {
                mobileInput.focus();
                showMobileTooltip('Mobile number required');
                return;
            }
            
            if (!validateMobile()) {
                mobileInput.focus();
                
                if (mobileValue.length < 10) {
                    showMobileTooltip('Ensure 10-digits');
                } else {
                    showMobileTooltip('Only numeric digits allowed');
                }
                return;
            }
            
            // Validate required fields
            const nameValue = document.getElementById('name').value.trim();
            const emailValue = document.getElementById('email').value.trim();
            
            if (!nameValue) {
                document.getElementById('name').focus();
                alert('Please enter your full name');
                return;
            }
            
            if (!emailValue) {
                document.getElementById('email').focus();
                alert('Please enter your email address');
                return;
            }
            
            // Store guest data in PHP session via AJAX
            const formData = new FormData();
            formData.append('name', nameValue);
            formData.append('email', emailValue);
            formData.append('mobile', mobileValue);
            formData.append('adults', document.getElementById('adults').value);
            formData.append('children', document.getElementById('children').value);
            formData.append('extra_persons', document.getElementById('extra_persons').value);
            formData.append('special_requests', document.getElementById('special_requests').value);
            
            // Disable button to prevent double submission
            nextButton.disabled = true;
            nextButton.textContent = 'Saving...';
            
            fetch('booking_step3_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Navigate to next step using replace to avoid back button issues
                    window.location.replace('booking_step4_addons.php');
                } else {
                    alert('Error saving guest data. Please try again.');
                    nextButton.disabled = false;
                    nextButton.textContent = 'Next: Add-ons & Extras →';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving guest data. Please try again.');
                nextButton.disabled = false;
                nextButton.textContent = 'Next: Add-ons & Extras →';
            });
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
