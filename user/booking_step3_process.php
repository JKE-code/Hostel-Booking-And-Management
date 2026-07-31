<?php
session_start();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');

// Store guest data from POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['booking']['name'] = $_POST['name'] ?? '';
    $_SESSION['booking']['email'] = $_POST['email'] ?? '';
    $_SESSION['booking']['mobile'] = $_POST['mobile'] ?? '';
    $_SESSION['booking']['adults'] = intval($_POST['adults'] ?? 2);
    $_SESSION['booking']['children'] = intval($_POST['children'] ?? 0);
    $_SESSION['booking']['extra_persons'] = intval($_POST['extra_persons'] ?? 0);
    $_SESSION['booking']['special_requests'] = $_POST['special_requests'] ?? '';
    
    // Calculate extra breakfast (children who need breakfast)
    $_SESSION['booking']['extra_breakfast'] = intval($_POST['children'] ?? 0);
    
    echo json_encode(['success' => true]);
    exit();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}
