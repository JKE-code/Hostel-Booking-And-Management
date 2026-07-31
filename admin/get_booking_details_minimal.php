<?php
// Minimal version to test if basic JSON output works
header('Content-Type: application/json');

// Test 1: Can we output anything?
echo json_encode(['test' => 'basic output works']);
exit;
?>
