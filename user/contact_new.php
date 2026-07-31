<?php 
$page_title = 'Contact Us - Alluri Resorts';
$body_class = 'contact-page';
include 'includes/header.php'; 
?>

<!-- Page Header -->
<section class="page-header">
    <h1>Contact Us</h1>
    <p>We'd love to hear from you. Get in touch with us today!</p>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-container">
            <!-- Contact Info -->
            <div class="contact-info">
                <h2>Alluri Resorts</h2>
                <div class="info-item">
                    <h3>📍 Address</h3>
                    <p>Araku - Visakhapatnam Road<br>Opposite to ITI (Govt)<br>Ravvalaguda, Araku Valley<br>Andhra Pradesh 531149</p>
                </div>
                <div class="info-item">
                    <h3>📞 Phone</h3>
                    <p><a href="tel:08936249888">Landline: 08936-249888</a></p>
                </div>
                <div class="info-item">
                    <h3>📱 Mobile / WhatsApp</h3>
                    <p><a href="https://wa.me/919392952669" target="_blank">+91 93929 52669</a></p>
                </div>
                <div class="info-item">
                    <a href="https://wa.me/919392952669" target="_blank" class="btn btn-contact"><span>Chat on WhatsApp</span></a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form">
                <h2>Send us a Message</h2>
                <p style="background: #fff3cd; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #856404;">
                    <strong>📱 Quick Response:</strong> For immediate assistance, please contact us via WhatsApp or phone. We typically respond within minutes!
                </p>
                <form id="contact-form" onsubmit="return submitContactForm(event);">
                    <div class="form-group">
                        <label for="contact_name">Full Name</label>
                        <input type="text" id="contact_name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_email">Email Address</label>
                        <input type="email" id="contact_email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_subject">Subject</label>
                        <input type="text" id="contact_subject" name="subject" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_message">Message</label>
                        <textarea id="contact_message" name="message" rows="5" required></textarea>
                    </div>

                    <button type="submit" id="submit-btn" class="btn btn-contact"><span>Send Message</span></button>
                </form>
                
                <!-- Message Display Area -->
                <div id="form-message" style="margin-top: 30px; margin-bottom: 30px;"></div>
            </div>
        </div>

        <!-- Map -->
        <div class="map-section" style="margin-top: 50px;">
            <h2>Find Us on Map</h2>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4493.035067087091!2d82.89168017579358!3d18.31250167549566!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a3a4b98e9f479ed%3A0x404b1c7567a8599a!2sAlluri%20Resorts!5e1!3m2!1sen!2sin!4v1763569834275!5m2!1sen!2sin" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<script>
function submitContactForm(event) {
    event.preventDefault();
    
    var form = document.getElementById('contact-form');
    var messageDiv = document.getElementById('form-message');
    var submitBtn = document.getElementById('submit-btn');
    
    // Get form data
    var formData = new FormData(form);
    
    // Disable button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span>Sending...</span>';
    
    // Clear previous message
    messageDiv.innerHTML = '';
    
    // Create XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'submit_contact.php', true);
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    
                    if (response.success) {
                        messageDiv.innerHTML = '<div style="background: #d4edda; color: #155724; padding: 25px; border-radius: 10px; border: 3px solid #28a745; font-size: 18px; font-weight: bold; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">✅ SUCCESS!<br><br><span style="font-weight: normal; font-size: 16px;">' + response.message + '</span></div>';
                        form.reset();
                    } else {
                        messageDiv.innerHTML = '<div style="background: #fff3cd; color: #856404; padding: 25px; border-radius: 10px; border: 3px solid #ffc107; font-size: 18px; font-weight: bold; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">⚠️ NOTICE<br><br><span style="font-weight: normal; font-size: 16px;">' + response.message + '</span></div>';
                    }
                    
                    // Scroll to message
                    messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                } catch (e) {
                    messageDiv.innerHTML = '<div style="background: #f8d7da; color: #721c24; padding: 25px; border-radius: 10px; border: 3px solid #dc3545; font-size: 18px; font-weight: bold; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">❌ ERROR<br><br><span style="font-weight: normal; font-size: 16px;">Invalid response from server. Please try again.</span></div>';
                }
            } else {
                messageDiv.innerHTML = '<div style="background: #f8d7da; color: #721c24; padding: 25px; border-radius: 10px; border: 3px solid #dc3545; font-size: 18px; font-weight: bold; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">❌ ERROR<br><br><span style="font-weight: normal; font-size: 16px;">Server error. Please contact us directly.</span></div>';
            }
            
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Send Message</span>';
        }
    };
    
    xhr.send(formData);
    return false;
}
</script>

<?php include 'includes/footer.php'; ?>
