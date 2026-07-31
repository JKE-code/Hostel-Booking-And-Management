# Free Email Services for Automated Booking Emails

## Recommended Free Options

### 1. **Brevo (formerly Sendinblue)** ⭐ RECOMMENDED
- **Free Tier**: 300 emails/day
- **Features**: SMTP, API, Templates, Analytics
- **Setup**: Easy SMTP configuration
- **Best For**: Small to medium hotels

**Setup Steps:**
1. Sign up at [brevo.com](https://www.brevo.com)
2. Go to SMTP & API → SMTP
3. Get your SMTP credentials
4. Use in PHP:
```php
$mail->Host = 'smtp-relay.brevo.com';
$mail->Port = 587;
$mail->Username = 'your-email@example.com';
$mail->Password = 'your-smtp-key';
```

### 2. **Mailgun**
- **Free Tier**: 5,000 emails/month (first 3 months), then 1,000/month
- **Features**: Powerful API, Good deliverability
- **Best For**: Developers comfortable with APIs

### 3. **SendGrid**
- **Free Tier**: 100 emails/day
- **Features**: Good templates, Analytics
- **Best For**: Basic transactional emails

### 4. **Elastic Email**
- **Free Tier**: 100 emails/day
- **Features**: SMTP, API, Templates
- **Best For**: Budget-conscious projects

### 5. **Gmail SMTP** (Not Recommended for Production)
- **Free Tier**: 500 emails/day
- **Limitations**: Can be blocked, not reliable for business
- **Best For**: Testing only

---

## Implementation Example with Brevo

### 1. Install PHPMailer (if not already installed)
```bash
composer require phpmailer/phpmailer
```

### 2. Create Email Configuration File
**File**: `config/email.php`
```php
<?php
return [
    'smtp_host' => 'smtp-relay.brevo.com',
    'smtp_port' => 587,
    'smtp_username' => 'your-email@example.com',
    'smtp_password' => 'your-brevo-smtp-key',
    'from_email' => 'noreply@alluriresorts.com',
    'from_name' => 'Alluri Resorts',
    'reply_to' => 'info@alluriresorts.com'
];
```

### 3. Create Email Helper Function
**File**: `includes/email_helper.php`
```php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendBookingConfirmation($booking_data) {
    $config = require __DIR__ . '/../config/email.php';
    
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_username'];
        $mail->Password = $config['smtp_password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['smtp_port'];
        
        // Recipients
        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($booking_data['email'], $booking_data['name']);
        $mail->addReplyTo($config['reply_to'], $config['from_name']);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation - ' . $booking_data['booking_ref'];
        $mail->Body = getBookingEmailTemplate($booking_data);
        $mail->AltBody = strip_tags($mail->Body);
        
        $mail->send();
        
        // Log email
        logEmail($booking_data['booking_id'], $booking_data['email'], 'booking_confirmation', 'sent');
        
        return true;
    } catch (Exception $e) {
        // Log error
        logEmail($booking_data['booking_id'], $booking_data['email'], 'booking_confirmation', 'failed', $mail->ErrorInfo);
        return false;
    }
}

function logEmail($booking_id, $recipient, $type, $status, $error = null) {
    global $conn;
    
    $query = "INSERT INTO email_log (booking_id, recipient_email, email_type, status, error_message, sent_at) 
              VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'issss', $booking_id, $recipient, $type, $status, $error);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function getBookingEmailTemplate($data) {
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #8b0000 0%, #6b0000 100%); color: white; padding: 30px; text-align: center; }
            .content { background: #f8f9fa; padding: 30px; }
            .booking-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
            .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e2e8f0; }
            .footer { text-align: center; padding: 20px; color: #64748b; font-size: 14px; }
            .button { background: #8b0000; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Booking Confirmed!</h1>
                <p>Thank you for choosing Alluri Resorts</p>
            </div>
            <div class='content'>
                <p>Dear {$data['name']},</p>
                <p>Your booking has been confirmed. Here are your booking details:</p>
                
                <div class='booking-details'>
                    <div class='detail-row'>
                        <strong>Booking Reference:</strong>
                        <span>{$data['booking_ref']}</span>
                    </div>
                    <div class='detail-row'>
                        <strong>Check-in:</strong>
                        <span>{$data['checkin']}</span>
                    </div>
                    <div class='detail-row'>
                        <strong>Check-out:</strong>
                        <span>{$data['checkout']}</span>
                    </div>
                    <div class='detail-row'>
                        <strong>Room Type:</strong>
                        <span>{$data['room_type']}</span>
                    </div>
                    <div class='detail-row'>
                        <strong>Total Amount:</strong>
                        <span>₹{$data['total_amount']}</span>
                    </div>
                </div>
                
                <p>We look forward to welcoming you!</p>
                
                <center>
                    <a href='https://alluriresorts.com/user/booking_invoice.php?ref={$data['booking_ref']}' class='button'>View Invoice</a>
                </center>
            </div>
            <div class='footer'>
                <p>Alluri Resorts, Araku Valley</p>
                <p>Contact: +91-XXXXXXXXXX | Email: info@alluriresorts.com</p>
            </div>
        </div>
    </body>
    </html>
    ";
}
```

### 4. Create Email Log Table
```sql
CREATE TABLE IF NOT EXISTS email_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT,
    recipient_email VARCHAR(255) NOT NULL,
    email_type ENUM('booking_confirmation', 'payment_reminder', 'checkin_reminder', 'checkout_confirmation', 'cancellation') NOT NULL,
    status ENUM('sent', 'failed', 'pending') NOT NULL,
    error_message TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_booking_id (booking_id),
    INDEX idx_status (status),
    INDEX idx_sent_at (sent_at),
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 5. Integrate into Booking Process
In `user/booking_confirm.php`, after successful booking:
```php
require_once __DIR__ . '/../includes/email_helper.php';

// After booking is created
$email_data = [
    'booking_id' => $booking_id,
    'booking_ref' => $booking_ref,
    'name' => $guest_name,
    'email' => $guest_email,
    'checkin' => $checkin,
    'checkout' => $checkout,
    'room_type' => $room_details,
    'total_amount' => number_format($total_amount, 2)
];

sendBookingConfirmation($email_data);
```

---

## Email Types to Implement

1. **Booking Confirmation** - Sent immediately after booking
2. **Payment Reminder** - For advance payment bookings
3. **Check-in Reminder** - 1 day before check-in
4. **Check-out Confirmation** - After check-out
5. **Cancellation Confirmation** - If booking is cancelled

---

## Best Practices

1. **Use Templates**: Create reusable HTML templates
2. **Log Everything**: Track all email attempts in database
3. **Handle Failures**: Implement retry logic for failed emails
4. **Test Thoroughly**: Test with different email providers
5. **Monitor Limits**: Track daily/monthly email usage
6. **Unsubscribe Option**: Include for marketing emails
7. **Mobile Responsive**: Ensure emails look good on mobile

---

## Cost Comparison (if you outgrow free tier)

| Service | Free Tier | Paid Plans Start At |
|---------|-----------|---------------------|
| Brevo | 300/day | $25/month (20k emails) |
| Mailgun | 1,000/month | $35/month (50k emails) |
| SendGrid | 100/day | $19.95/month (50k emails) |
| Elastic Email | 100/day | $9/month (10k emails) |

---

## Recommendation for Alluri Resorts

**Start with Brevo (Sendinblue)**
- 300 emails/day is sufficient for a small resort
- Easy SMTP setup
- Good deliverability
- Free forever plan
- Can upgrade as you grow

If you expect more than 300 bookings/day, consider Mailgun or SendGrid.
