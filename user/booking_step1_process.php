<?php
session_start();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');

// Store dates from POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkin']) && isset($_POST['checkout'])) {
    $_SESSION['booking']['checkin'] = $_POST['checkin'];
    $_SESSION['booking']['checkout'] = $_POST['checkout'];
    
    // Calculate nights
    $checkin = new DateTime($_POST['checkin']);
    $checkout = new DateTime($_POST['checkout']);
    $_SESSION['booking']['nights'] = $checkin->diff($checkout)->days;
    
    echo json_encode(['success' => true]);
    exit();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}
