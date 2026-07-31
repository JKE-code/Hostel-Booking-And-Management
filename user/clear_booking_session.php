<?php
session_start();

// Clear booking session
unset($_SESSION['booking']);

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => 'Booking session cleared successfully'
]);
?>
