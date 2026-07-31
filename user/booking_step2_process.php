<?php
session_start();

// Prevent caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');

// Store room selections from POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['booking']['rooms'] = [
        'nonac' => intval($_POST['nonac_qty'] ?? 0),
        'ac' => intval($_POST['ac_qty'] ?? 0),
        'deluxe' => intval($_POST['deluxe_qty'] ?? 0),
        'suite' => intval($_POST['suite_qty'] ?? 0)
    ];
    
    echo json_encode(['success' => true]);
    exit();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}
