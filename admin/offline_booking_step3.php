<?php
session_start();

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['offline_booking']) || $_SESSION['offline_booking']['step'] < 3) {
    // Allow access if trying to go back
    if (!isset($_GET['back'])) {
        header('Location: offline_booking.php');
        exit;
    }
}

// Allow going back to step 3 from later steps
if (isset($_GET['back']) && $_GET['back'] == 3) {
    $_SESSION['offline_booking']['step'] = 3;
}

$page_title = 'Offline Booking - Step 3';
$page_heading = 'Walk-in / On-Spot Booking';

// Fetch room types data for capacity calculation
$room_types_data = [];
try {
    $stmt = $pdo->query("SELECT id, name, max_adults, max_children FROM room_types WHERE status = 'active'");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $room_types_data[$row['id']] = [
            'name' => $row['name'],
            'max_adults' => $row['max_adults'],
            'max_children' => $row['max_children']
        ];
    }
} catch (Exception $e) {
    $error_message = "Error fetching room types.";
}

// Get selected rooms from session
$selected_rooms = $_SESSION['offline_booking']['rooms'] ?? [];
$guests_data = $_SESSION['offline_booking']['guests'] ?? [];

// Fetch extra person price from database
$extra_person_price = 500; // Default
try {
    $stmt = $pdo->query("SELECT price FROM addons WHERE name = 'Extra Person' AND status = 'active'");
    $row = $stmt->fetch();
    if ($row) {
        $extra_person_price = floatval($row['price']);
    }
} catch (Exception $e) {
    error_log('Error fetching extra person price: ' . $e->getMessage());
}
?>
<?php include 'includes/offline_header.php'; ?>

<main class="dashboard-main">
    <div class="offline-booking-container">
        <div class="offline-badge">🏨 Walk-in Booking Mode</div>
        
        <div class="booking-progress">
            <div class="progress-step completed"><div class="progress-circle">✓</div><div class="progress-label">Select Dates</div></div>
            <div class="progress-step completed"><div class="progress-circle">✓</div><div class="progress-label">Select Rooms</div></div>
            <div class="progress-step active"><div class="progress-circle">3</div><div class="progress-label">Guest Info</div></div>
            <div class="progress-step"><div class="progress-circle">4</div><div class="progress-label">Add-ons</div></div>
            <div class="progress-step"><div class="progress-circle">5</div><div class="progress-label">Payment</div></div>
        </div>

        <div class="booking-card">
            <h2>Step 3: Guest Information</h2>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Enter guest details and specify number of adults and children</p>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div style="background: #fee2e2; border: 1px solid #dc2626; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <?php 
                    echo htmlspecialchars($_SESSION['error_message']); 
                    unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="offline_booking_process.php" id="guestForm" novalidate>
                <input type="hidden" name="action" value="set_guests">
                
                <h3 style="margin-bottom: 1rem; color: #374151;">Contact Information</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label for="guest_name" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Full Name *</label>
                        <input type="text" id="guest_name" name="guest_name" required 
                               value="<?php echo htmlspecialchars($guests_data['name'] ?? ''); ?>"
                               style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                    </div>
                    
                    <div class="form-group">
                        <label for="guest_mobile" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Mobile Number *</label>
                        <div style="position: relative;">
                            <input type="tel" id="guest_mobile" name="guest_mobile" required 
                                   value="<?php echo htmlspecialchars($guests_data['mobile'] ?? ''); ?>"
                                   placeholder="10-digit mobile number" maxlength="10"
                                   style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                            <div id="mobile-tooltip" style="display: none; position: absolute; top: -35px; left: 0; background: #dc2626; color: white; padding: 0.5rem 0.75rem; border-radius: 6px; font-size: 0.875rem; white-space: nowrap; z-index: 10; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                <span id="mobile-tooltip-text">Ensure 10-digits</span>
                                <div style="position: absolute; bottom: -4px; left: 20px; width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 4px solid #dc2626;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="guest_email" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Email (Optional)</label>
                        <input type="email" id="guest_email" name="guest_email" 
                               value="<?php echo htmlspecialchars($guests_data['email'] ?? ''); ?>"
                               style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                    </div>
                </div>
                
                <h3 style="margin-bottom: 1rem; color: #374151;">Guest Count</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label for="adults" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Adults *</label>
                        <input type="number" id="adults" name="adults" min="1" max="50" required 
                               value="<?php echo $guests_data['adults'] ?? 1; ?>"
                               style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                        <small id="adults-capacity-info" style="color: #666; display: block; margin-top: 0.25rem;"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="children" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Children</label>
                        <input type="number" id="children" name="children" min="0" max="50" 
                               value="<?php echo $guests_data['children'] ?? 0; ?>"
                               style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                        <small id="children-capacity-info" style="color: #666; display: block; margin-top: 0.25rem;"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="extra_persons" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Extra Persons</label>
                        <input type="number" id="extra_persons" name="extra_persons" min="0" max="8" 
                               value="<?php echo $guests_data['extra_persons'] ?? 0; ?>"
                               style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                        <small style="color: #666; display: block; margin-top: 0.25rem;">₹<?php echo number_format($extra_person_price, 0); ?>/person/night</small>
                    </div>
                </div>
                
                <div style="background: #f0f9ff; padding: 1rem; border-radius: 8px; border: 1px solid #bfdbfe; margin-bottom: 2rem;">
                    <p style="color: #1e40af; margin: 0; font-size: 0.875rem;">
                        💡 <strong>Tip:</strong> The system will automatically calculate room capacity based on your selected rooms. 
                        If you exceed the capacity, extra person charges will apply.
                    </p>
                </div>
                
                <div class="booking-actions">
                    <button type="button" class="btn-offline btn-offline-secondary" onclick="window.location.href='offline_booking_step2.php?back=2'">← Back</button>
                    <button type="submit" class="btn-offline btn-offline-primary">Next: Add-ons →</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// Pass room types data and selected rooms to JavaScript
const roomTypesCapacity = <?php echo json_encode($room_types_data); ?>;
const selectedRooms = <?php echo json_encode($selected_rooms); ?>;

document.addEventListener('DOMContentLoaded', function() {
    const adultsInput = document.getElementById('adults');
    const childrenInput = document.getElementById('children');
    const extraPersonsInput = document.getElementById('extra_persons');
    const adultsCapacityInfo = document.getElementById('adults-capacity-info');
    const childrenCapacityInfo = document.getElementById('children-capacity-info');
    
    let adultsMessageTimeout = null;
    let childrenMessageTimeout = null;
    
    // Calculate total capacity based on selected rooms
    function calculateCapacity() {
        let totalMaxAdults = 0;
        let totalMaxChildren = 0;
        
        for (const [roomTypeId, quantity] of Object.entries(selectedRooms)) {
            if (quantity > 0 && roomTypesCapacity[roomTypeId]) {
                totalMaxAdults += roomTypesCapacity[roomTypeId].max_adults * quantity;
                totalMaxChildren += roomTypesCapacity[roomTypeId].max_children * quantity;
            }
        }
        
        return {
            maxAdults: totalMaxAdults || 2,
            maxChildren: totalMaxChildren || 1
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
        const adultsValue = parseInt(adultsInput.value) || 0;
        const childrenValue = parseInt(childrenInput.value) || 0;
        
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
            adultsCapacityInfo.style.color = '#10b981';
        }
        
        // Validate extra persons cap
        validateExtraPersons();
    }
    
    // Update children capacity info
    function updateChildrenCapacity() {
        const capacity = calculateCapacity();
        const adultsValue = parseInt(adultsInput.value) || 0;
        const childrenValue = parseInt(childrenInput.value) || 0;
        
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
            childrenCapacityInfo.style.color = '#10b981';
        }
        
        // Validate extra persons cap
        validateExtraPersons();
    }
    
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
    
    // Initialize on page load
    const capacity = calculateCapacity();
    adultsCapacityInfo.textContent = `Max capacity: ${capacity.maxAdults}`;
    adultsCapacityInfo.style.color = '#10b981';
    childrenCapacityInfo.textContent = `Max capacity: ${capacity.maxChildren}`;
    childrenCapacityInfo.style.color = '#10b981';
    
    // Mobile number validation
    const mobileInput = document.getElementById('guest_mobile');
    const mobileTooltip = document.getElementById('mobile-tooltip');
    const mobileTooltipText = document.getElementById('mobile-tooltip-text');
    const guestForm = document.getElementById('guestForm');
    let tooltipTimeout = null;
    
    function showMobileTooltip(message) {
        mobileTooltipText.textContent = message;
        mobileTooltip.style.display = 'block';
        mobileInput.style.borderColor = '#dc2626';
        
        // Auto-hide after 3 seconds
        if (tooltipTimeout) clearTimeout(tooltipTimeout);
        tooltipTimeout = setTimeout(function() {
            mobileTooltip.style.display = 'none';
            mobileInput.style.borderColor = '#e5e7eb';
        }, 3000);
    }
    
    function hideMobileTooltip() {
        mobileTooltip.style.display = 'none';
        mobileInput.style.borderColor = '#e5e7eb';
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
    
    guestForm.addEventListener('submit', function(e) {
        const mobileValue = mobileInput.value.trim();
        
        if (mobileValue.length === 0) {
            e.preventDefault();
            mobileInput.focus();
            showMobileTooltip('Mobile number required');
            return;
        }
        
        if (!validateMobile()) {
            e.preventDefault();
            mobileInput.focus();
            
            if (mobileValue.length < 10) {
                showMobileTooltip('Ensure 10-digits');
            } else {
                showMobileTooltip('Only numeric digits allowed');
            }
        }
    });
});
</script>

</body>
</html>
