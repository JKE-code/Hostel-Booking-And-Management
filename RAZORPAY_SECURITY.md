# Razorpay Integration - Security Documentation

## Security Measures Implemented

### 1. **Separate Configuration File**
- **File**: `config/razorpay.php`
- **Purpose**: Stores Razorpay credentials separately from application code
- **Location**: Outside public web directory (in `/config` folder)

### 2. **Access Control via .htaccess**
- **File**: `config/.htaccess`
- **Protection**: Denies direct HTTP access to all config files
- **Result**: Users cannot access `http://yoursite.com/config/razorpay.php` directly

### 3. **Server-Side Only Secret Key**
- **Key Secret**: `xWiGG7qWNpKhpPTGqBxz57W1`
- **Storage**: Only in `config/razorpay.php` (server-side)
- **Never Exposed**: Secret key is NEVER sent to client-side JavaScript
- **Usage**: Only used server-side for signature verification

### 4. **Public Key ID Exposure (Safe)**
- **Key ID**: `rzp_live_SELdXdwTJDqOUS`
- **Exposure**: Sent to frontend JavaScript (required by Razorpay)
- **Security**: This is safe - Razorpay designed key_id to be public
- **Purpose**: Identifies your Razorpay account for payment processing

### 5. **Payment Signature Verification**
- **Function**: `verifyRazorpaySignature()` in `config/razorpay.php`
- **Purpose**: Verifies payment callbacks are genuine from Razorpay
- **Method**: Uses HMAC SHA256 with secret key
- **Protection**: Prevents fake payment confirmations

### 6. **File Permissions** (Recommended)
```bash
# Set restrictive permissions on config files
chmod 600 config/razorpay.php  # Only owner can read/write
chmod 644 config/db.php         # Owner read/write, others read only
```

### 7. **Environment Variables** (Production Recommendation)
For production servers, consider moving credentials to environment variables:

```php
// Instead of hardcoded values, use:
define('RAZORPAY_KEY_ID', getenv('RAZORPAY_KEY_ID'));
define('RAZORPAY_KEY_SECRET', getenv('RAZORPAY_KEY_SECRET'));
```

Then set in server environment:
```bash
# In Apache .htaccess or httpd.conf
SetEnv RAZORPAY_KEY_ID "rzp_live_SELdXdwTJDqOUS"
SetEnv RAZORPAY_KEY_SECRET "xWiGG7qWNpKhpPTGqBxz57W1"

# Or in PHP-FPM pool config
env[RAZORPAY_KEY_ID] = rzp_live_SELdXdwTJDqOUS
env[RAZORPAY_KEY_SECRET] = xWiGG7qWNpKhpPTGqBxz57W1
```

## How It Works

### Payment Flow:

1. **User clicks "Proceed to Payment"** → Redirects to `booking_payment.php`
2. **Payment page loads** → Only `key_id` is exposed to JavaScript
3. **Razorpay modal opens** → User enters payment details
4. **Payment successful** → Razorpay returns `payment_id`, `order_id`, `signature`
5. **Data sent to server** → `booking_confirm.php` receives payment data
6. **Server verifies** → Uses secret key to verify signature (server-side only)
7. **Booking created** → If verification passes, booking is saved to database

### What's Exposed vs Hidden:

| Item | Exposed to Frontend? | Why? |
|------|---------------------|------|
| Key ID | ✅ Yes | Required by Razorpay, safe to expose |
| Key Secret | ❌ No | Must remain server-side only |
| Payment Amount | ✅ Yes | User needs to see what they're paying |
| Booking Details | ✅ Yes | User needs to review their booking |
| Signature Verification | ❌ No | Done server-side only |

## Additional Security Recommendations

### 1. **Enable Razorpay Webhooks**
- Set up webhooks in Razorpay Dashboard
- Verify webhook signatures server-side
- Handle payment status updates asynchronously

### 2. **SSL Certificate (HTTPS)**
- **Required**: Razorpay requires HTTPS for live mode
- Protects payment data in transit
- Install SSL certificate on your domain

### 3. **Database Security**
- Store payment IDs, not card details
- Never store CVV or full card numbers
- Razorpay handles sensitive payment data

### 4. **Logging**
- Log all payment attempts (success/failure)
- Monitor for suspicious activity
- Keep logs secure and rotate regularly

### 5. **Rate Limiting**
- Implement rate limiting on payment endpoints
- Prevent brute force attacks
- Use CAPTCHA for suspicious activity

## Testing

### Test Mode vs Live Mode:
- **Test Keys**: Start with `rzp_test_...`
- **Live Keys**: Currently using `rzp_live_...` (production)
- **Test Cards**: Use Razorpay test cards for testing
- **No Real Money**: Test mode doesn't charge real money

### Test the Integration:
1. Complete a booking flow
2. Click "Pay Now" button
3. Use Razorpay test card: `4111 1111 1111 1111`
4. CVV: Any 3 digits
5. Expiry: Any future date
6. Verify booking is created in database

## Monitoring

### Check Razorpay Dashboard:
- View all transactions
- Monitor payment success rate
- Check for failed payments
- Review refunds and disputes

### Database Monitoring:
- Check `payments` table for all transactions
- Verify `razorpay_payment_id` is stored
- Monitor payment status updates

## Support

For issues:
1. Check Razorpay Dashboard for payment status
2. Review server error logs
3. Contact Razorpay support: https://razorpay.com/support/
4. Check integration docs: https://razorpay.com/docs/

## Important Notes

⚠️ **Never commit credentials to Git**:
```bash
# Add to .gitignore
config/razorpay.php
.env
```

⚠️ **Backup your keys** securely in a password manager

⚠️ **Rotate keys** if compromised immediately

⚠️ **Test thoroughly** before going live

✅ **Current Status**: Live keys are configured and secured
