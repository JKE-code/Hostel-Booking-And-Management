<?php
session_start();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');

// Store addon data from POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get selected addons
    $addons = [];
    
    if (isset($_POST['campfire']) && $_POST['campfire'] === 'true') {
        $addons[] = 'campfire';
    }
    
    if (isset($_POST['dhimsa']) && $_POST['dhimsa'] === 'true') {
        $addons[] = 'dhimsa';
    }
    
    $_SESSION['booking']['addons'] = $addons;
    
    // Store coupon data if applied
    if (isset($_POST['coupon_code']) && !empty($_POST['coupon_code'])) {
        $_SESSION['booking']['coupon_code'] = $_POST['coupon_code'];
        $_SESSION['booking']['coupon_discount'] = floatval($_POST['coupon_discount'] ?? 0);
    } else {
        // Clear coupon if not applied
        unset($_SESSION['booking']['coupon_code']);
        unset($_SESSION['booking']['coupon_discount']);
    }
    
    echo json_encode(['success' => true]);
    exit();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}
