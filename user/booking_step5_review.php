<?php
// Step 5: Review & Payment
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

require_once '../config/razorpay.php';

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
                <div class="progress-line-fill" style="width: 100%;"></div>
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
            <div class="progress-step completed" data-step="4">
                <div class="step-circle">✓</div>
                <div class="step-label">Add-ons & Coupons</div>
            </div>
            <div class="progress-step active" data-step="5">
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
            
            <!-- STEP 5: Review & Payment -->
            <div id="step-5" class="booking-section-wrapper active">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Review & Payment</h2>
                        </div>
                        <div class="section-subtitle">
                            <div class="section-icon">💳</div>
                            <p>Review your booking and complete payment</p>
                        </div>
                    </div>

                    <!-- Booking Summary -->
                    <div class="booking-summary-enhanced">
                        <div class="summary-header">
                            <span style="font-size: 1.5rem;">📋</span>
                            <h3>Booking Summary</h3>
                        </div>
                        
                        <div class="summary-items">
                            <div class="summary-item">
                                <span>Room Type:</span>
                                <span id="summary_room">Not selected</span>
                            </div>
                            <div class="summary-item">
                                <span>Check-in:</span>
                                <span id="summary_checkin">-</span>
                            </div>
                            <div class="summary-item">
                                <span>Check-out:</span>
                                <span id="summary_checkout">-</span>
                            </div>
                            <div class="summary-item">
                                <span>Nights:</span>
                                <span id="summary_nights">-</span>
                            </div>
                            <div class="summary-item">
                                <span>Room Rate (per night):</span>
                                <span id="summary_room_rate">₹0</span>
                            </div>
                            <div class="summary-item">
                                <span>Subtotal (Room):</span>
                                <span id="summary_subtotal">₹0</span>
                            </div>
                            <div class="summary-item">
                                <span>Extra Persons:</span>
                                <span id="summary_extra">₹0</span>
                            </div>
                            <div class="summary-item">
                                <span>Extra Breakfast:</span>
                                <span id="summary_breakfast">₹0</span>
                            </div>
                            <div class="summary-item" id="campfire-row" style="display: none;">
                                <span>🔥 Campfire Evening:</span>
                                <span id="summary_campfire">₹0</span>
                            </div>
                            <div class="summary-item" id="dhimsa-row" style="display: none;">
                                <span>💃 Dhimsa Dance:</span>
                                <span id="summary_dhimsa">₹0</span>
                            </div>
                            <div class="summary-item" id="smart-offers-row" style="display: none; color: #10b981;">
                                <span>⚡ Smart Offers Discount:</span>
                                <span id="summary_smart_offers">-₹0</span>
                            </div>
                            <div class="summary-item">
                                <span>Processing Fees (<span class="fee-tooltip" title="2% Payment processing fees&#10;1% Platform fees">3%</span>):</span>
                                <span id="summary_other_taxes">₹0</span>
                            </div>
                            <div class="summary-item" id="discount-row" style="display: none; color: #10b981;">
                                <span>Coupon Discount:</span>
                                <span id="summary_discount">-₹0</span>
                            </div>
                            <div class="summary-divider"></div>
                            <div class="summary-item total">
                                <span>Total Amount:</span>
                                <span id="summary_total">₹0</span>
                            </div>
                        </div>

                        <!-- Payment Type Selection -->
                        <div class="payment-type-section">
                            <h4>Select Payment Type</h4>
                            <div class="payment-options">
                                <label class="payment-option">
                                    <input type="radio" name="payment_type" value="full" checked>
                                    <span>
                                        <strong>Full Payment</strong><br>
                                        <small>Pay complete amount now</small><br>
                                        <span class="payment-amount" id="full_payment_amount">₹0</span>
                                    </span>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_type" value="advance">
                                    <span>
                                        <strong>Advance Payment (50%)</strong><br>
                                        <small>Pay 50% now, rest at hotel</small><br>
                                        <span class="payment-amount" id="advance_payment_amount">₹0</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="terms">
                            <label>
                                <input type="checkbox" id="terms" name="terms" required>
                                <span>I agree to the <a href="terms-and-conditions.php" target="_blank" class="terms-link">Terms & Conditions</a> and <a href="#" onclick="openPolicyModal(); return false;" class="terms-link">Cancellation Policy</a></span>
                            </label>
                        </div>

                        <div class="payment-section">
                            <button id="razorpay-button" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.125rem;">
                                💳 Proceed to Payment
                            </button>
                        </div>
                    </div>

                    <div class="section-navigation">
                        <button type="button" class="btn-nav btn-prev" onclick="window.location.href='booking_step4_addons.php'">
                            ← Previous
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
    <img class="image-modal-content" id="modalImage" alt="">
    <div class="image-modal-caption" id="modalCaption"></div>
</div>

<!-- Cancellation Policy Modal -->
<div id="cancellationPolicyModal" class="policy-modal">
    <div class="policy-modal-content">
        <button class="policy-modal-close" onclick="closePolicyModal()">&times;</button>
        
        <div class="policy-modal-header">
            <h2>Cancellation & Refund Policy</h2>
        </div>

        <div class="policy-highlight">
            <strong>⚠️ Important:</strong> Cancellation charges apply based on the time of cancellation before your check-in date.
        </div>

        <div class="policy-section">
            <h3>Cancellation Timeline & Refunds</h3>
            <ul>
                <li><strong>30+ days before check-in:</strong> Full refund minus 10% processing fee</li>
                <li><strong>15-29 days before check-in:</strong> 50% refund of total booking amount</li>
                <li><strong>7-14 days before check-in:</strong> 25% refund of total booking amount</li>
                <li><strong>Less than 7 days before check-in:</strong> No refund</li>
                <li><strong>No-show:</strong> No refund</li>
            </ul>
        </div>

        <div class="policy-section">
            <h3>How to Cancel Your Booking</h3>
            <p>To cancel your reservation, please contact us:</p>
            <ul>
                <li>📞 Phone: <a href="tel:08936249888">08936-249888</a></li>
                <li>💬 WhatsApp: <a href="https://wa.me/919392952669">+91 93929 52669</a></li>
                <li>📧 Email: info@alluriresorts.com</li>
            </ul>
            <p>Please provide your booking reference number and registered mobile number/email.</p>
        </div>

        <div class="policy-section">
            <h3>Refund Processing</h3>
            <p>Approved refunds will be processed within <strong>7-10 business days</strong> to the original payment method used during booking.</p>
            <p><em>Note: Processing fees and transaction charges are non-refundable.</em></p>
        </div>

        <div class="policy-section">
            <h3>Booking Modifications</h3>
            <p>If you wish to modify your booking (change dates, room type, or guest count), please contact us at least <strong>7 days before check-in</strong>. Modifications are subject to availability and may incur additional charges.</p>
        </div>

        <div class="policy-section">
            <h3>Force Majeure</h3>
            <p>In case of unforeseen circumstances such as natural disasters, government restrictions, or other events beyond our control, the resort reserves the right to cancel bookings with a full refund or offer alternative dates without penalty.</p>
        </div>

        <div class="policy-highlight">
            <strong>💡 Tip:</strong> We recommend purchasing travel insurance to protect your booking against unforeseen circumstances.
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <button onclick="closePolicyModal()" class="btn btn-primary" style="padding: 0.875rem 2rem;">
                I Understand
            </button>
        </div>
    </div>
</div>

<script>
// Store booking data globally
let bookingData = null;

// Load booking data from session via AJAX
fetch('get_booking_summary.php')
    .then(response => response.json())
    .then(data => {
        console.log('Booking data received:', data);
        
        if (data.success) {
            bookingData = data.booking;
            updateBookingSummary(bookingData);
            
            // Additional validation
            if (!bookingData.has_rooms || bookingData.room_rate <= 0) {
                console.error('No rooms selected in booking data');
                showBookingError('No rooms selected. Please go back to Step 2 and select rooms.');
            }
        } else {
            console.error('Error loading booking data:', data);
            showBookingError('Error loading booking data: ' + (data.error || 'Unknown error') + (data.debug ? ' (' + data.debug + ')' : ''));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showBookingError('Error loading booking data. Please check console for details.');
    });

function showBookingError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.style.cssText = 'background: #fee2e2; color: #991b1b; padding: 1.5rem; border-radius: 8px; border: 2px solid #dc2626; margin: 2rem auto; max-width: 800px; text-align: center;';
    errorDiv.innerHTML = `
        <h3 style="margin: 0 0 1rem 0;">⚠️ Booking Error</h3>
        <p style="margin: 0 0 1rem 0;">${message}</p>
        <button onclick="window.location.href='booking_step1_dates.php'" style="background: #8b0000; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">
            Start Over
        </button>
    `;
    
    const container = document.querySelector('.booking-container');
    if (container) {
        container.insertBefore(errorDiv, container.firstChild);
    }
}

function updateBookingSummary(booking) {
    // Update summary fields
    document.getElementById('summary_room').textContent = booking.room_names || 'Not selected';
    document.getElementById('summary_checkin').textContent = booking.checkin || '-';
    document.getElementById('summary_checkout').textContent = booking.checkout || '-';
    document.getElementById('summary_nights').textContent = booking.nights || '-';
    document.getElementById('summary_room_rate').textContent = '₹' + (booking.room_rate || 0).toLocaleString();
    document.getElementById('summary_subtotal').textContent = '₹' + (booking.subtotal || 0).toLocaleString();
    document.getElementById('summary_extra').textContent = '₹' + (booking.extra_persons_cost || 0).toLocaleString();
    document.getElementById('summary_breakfast').textContent = '₹' + (booking.breakfast_cost || 0).toLocaleString();
    
    // Show/hide addon rows
    if (booking.campfire_cost > 0) {
        document.getElementById('campfire-row').style.display = 'flex';
        document.getElementById('summary_campfire').textContent = '₹' + booking.campfire_cost.toLocaleString();
    }
    
    if (booking.dhimsa_cost > 0) {
        document.getElementById('dhimsa-row').style.display = 'flex';
        document.getElementById('summary_dhimsa').textContent = '₹' + booking.dhimsa_cost.toLocaleString();
    }
    
    // Show smart offers discount if applicable
    if (booking.smart_offers_discount > 0) {
        document.getElementById('smart-offers-row').style.display = 'flex';
        document.getElementById('summary_smart_offers').textContent = '-₹' + booking.smart_offers_discount.toLocaleString();
    }
    
    document.getElementById('summary_other_taxes').textContent = '₹' + (booking.processing_fees || 0).toLocaleString();
    
    // Show coupon discount if applicable
    if (booking.coupon_discount > 0) {
        document.getElementById('discount-row').style.display = 'flex';
        document.getElementById('summary_discount').textContent = '-₹' + booking.coupon_discount.toLocaleString();
    }
    
    document.getElementById('summary_total').textContent = '₹' + (booking.total || 0).toLocaleString();
    document.getElementById('full_payment_amount').textContent = '₹' + (booking.total || 0).toLocaleString();
    document.getElementById('advance_payment_amount').textContent = '₹' + (booking.advance_amount || 0).toLocaleString();
}

// Initialize Razorpay payment with session data
document.addEventListener('DOMContentLoaded', function() {
    const razorpayButton = document.getElementById('razorpay-button');
    
    if (razorpayButton) {
        razorpayButton.addEventListener('click', function() {
            // Validate booking data exists
            if (!bookingData) {
                alert('Booking data not loaded. Please refresh the page and try again.');
                return;
            }
            
            // Check if rooms are selected
            if (!bookingData.has_rooms || bookingData.room_rate <= 0) {
                alert('Please select a room. Go back to Step 1 to select rooms.');
                return;
            }
            
            // Check if guest information exists
            if (!bookingData.name || !bookingData.email || !bookingData.mobile) {
                alert('Guest information is missing. Please go back to Step 3 to fill in your details.');
                return;
            }
            
            // Check terms and conditions
            const termsChecked = document.getElementById('terms')?.checked || false;
            if (!termsChecked) {
                alert('Please agree to the Terms & Conditions and Cancellation Policy.');
                return;
            }
            
            // Get payment type
            const paymentType = document.querySelector('input[name="payment_type"]:checked')?.value || 'full';
            const amount = paymentType === 'advance' ? Math.round(bookingData.advance_amount * 100) : Math.round(bookingData.total * 100);
            
            if (amount <= 0) {
                alert('Invalid payment amount. Please go back and check your booking.');
                return;
            }
            
            // Payment description
            const description = paymentType === 'advance' 
                ? 'Advance Payment (50%) - Room Booking' 
                : 'Full Payment - Room Booking';
            
            // Razorpay options
            const options = {
                key: '<?php echo getRazorpayKeyId(); ?>', // Live Razorpay key
                amount: amount,
                currency: 'INR',
                name: 'Alluri Resorts',
                description: description,
                image: '../assets/images/Alluri_Logo.png',
                prefill: {
                    name: bookingData.name,
                    email: bookingData.email,
                    contact: bookingData.mobile
                },
                theme: {
                    color: '#8b0000'
                },
                handler: function(response) {
                    // Payment successful - save booking to database
                    // Pass back all Razorpay response fields for server-side verification
                    saveBooking(response, paymentType);
                },
                modal: {
                    ondismiss: function() {
                        console.log('Payment cancelled by user');
                    }
                }
            };
            
            const rzp = new Razorpay(options);
            
            rzp.on('payment.failed', function(response) {
                console.error('Payment failed:', response.error);
                alert('Payment failed: ' + response.error.description);
            });
            
            rzp.open();
        });
    }
});

// Save booking to database after successful payment
function saveBooking(paymentResponse, paymentType) {
    const formData = new FormData();
    formData.append('payment_id', paymentResponse.razorpay_payment_id);
    formData.append('payment_type', paymentType);
    // Send order_id and signature for server-side verification
    if (paymentResponse.razorpay_order_id) {
        formData.append('razorpay_order_id', paymentResponse.razorpay_order_id);
    }
    if (paymentResponse.razorpay_signature) {
        formData.append('razorpay_signature', paymentResponse.razorpay_signature);
    }
    
    // Show loading state
    const razorpayButton = document.getElementById('razorpay-button');
    razorpayButton.disabled = true;
    razorpayButton.textContent = 'Processing...';
    
    fetch('process_booking_payment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to success page
            window.location.href = 'booking_success.php?booking_id=' + data.booking_id + '&ref=' + data.booking_ref;
        } else {
            alert('Error saving booking: ' + (data.error || 'Unknown error'));
            razorpayButton.disabled = false;
            razorpayButton.textContent = '💳 Proceed to Payment';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving booking. Please contact support with payment ID: ' + paymentResponse.razorpay_payment_id);
        razorpayButton.disabled = false;
        razorpayButton.textContent = '💳 Proceed to Payment';
    });
}

// Policy modal functions
function openPolicyModal() {
    document.getElementById('cancellationPolicyModal').style.display = 'flex';
}

function closePolicyModal() {
    document.getElementById('cancellationPolicyModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('cancellationPolicyModal');
    if (event.target == modal) {
        closePolicyModal();
    }
}
</script>

<!-- Razorpay Integration -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<?php include 'includes/footer.php'; ?>
