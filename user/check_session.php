<?php
session_start();

header('Content-Type: application/json');

// Return all booking session data
$response = [
    'session_exists' => isset($_SESSION['booking']),
    'booking_data' => $_SESSION['booking'] ?? null,
    'session_id' => session_id(),
    'all_session_keys' => array_keys($_SESSION)
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>
