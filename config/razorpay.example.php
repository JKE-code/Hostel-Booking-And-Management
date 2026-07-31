<?php
// ============================================================
// Razorpay Configuration Template
// ============================================================
// INSTRUCTIONS:
//   1. Copy this file to razorpay.php:  cp config/razorpay.example.php config/razorpay.php
//   2. Fill in your actual Razorpay credentials in razorpay.php
//   3. razorpay.php is in .gitignore and will never be committed
//
// SECURITY NOTES:
//   - The KEY_SECRET must NEVER be exposed to the browser/frontend
//   - Only KEY_ID is safe to send to the client (Razorpay requires it)
//   - Get your keys from: https://dashboard.razorpay.com/app/keys
// ============================================================

// Razorpay API Credentials
// Use 'rzp_test_...' keys for testing, 'rzp_live_...' for production
define('RAZORPAY_KEY_ID', 'rzp_test_YOUR_KEY_ID_HERE');
define('RAZORPAY_KEY_SECRET', 'YOUR_KEY_SECRET_HERE');

// Razorpay API Endpoint
define('RAZORPAY_API_URL', 'https://api.razorpay.com/v1/');

/**
 * Get Razorpay Key ID (safe to expose to frontend)
 */
function getRazorpayKeyId() {
    return RAZORPAY_KEY_ID;
}

/**
 * Get Razorpay Key Secret (NEVER expose to frontend)
 */
function getRazorpayKeySecret() {
    return RAZORPAY_KEY_SECRET;
}

/**
 * Verify Razorpay Payment Signature
 * Call this on the server after receiving payment callback.
 * Ensures the payment response is genuine and not tampered with.
 *
 * @param string $razorpay_order_id   Order ID from Razorpay (if order was created)
 * @param string $razorpay_payment_id Payment ID from callback
 * @param string $razorpay_signature  Signature from callback
 * @return bool True if signature is valid
 */
function verifyRazorpaySignature($razorpay_order_id, $razorpay_payment_id, $razorpay_signature) {
    $secret = getRazorpayKeySecret();
    $generated_signature = hash_hmac('sha256', $razorpay_order_id . '|' . $razorpay_payment_id, $secret);
    return hash_equals($generated_signature, $razorpay_signature);
}
