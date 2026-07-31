<?php
// Prevent any output before JSON
error_reporting(0); // Suppress all errors in output
ini_set('display_errors', 0);

// Start output buffering
ob_start();

// Set JSON header
header('Content-Type: application/json');

// Include database
require_once '../config/db.php';

// Initialize response
$response = ['success' => false, 'message' => ''];

// Check if PDO is available
if (!isset($pdo) || $pdo === null) {
    $response['message'] = 'Database connection error. Please try again later.';
    ob_end_clean();
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response['message'] = 'All fields are required.';
        ob_end_clean();
        echo json_encode($response);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
        ob_end_clean();
        echo json_encode($response);
        exit;
    }
    
    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM contact_messages WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $response['message'] = 'You have already sent a message. Our personnel will contact you shortly. For urgent matters, please call us directly.';
            ob_end_clean();
            echo json_encode($response);
            exit;
        }
        
        // Insert contact message
        $stmt = $pdo->prepare("
            INSERT INTO contact_messages (name, email, subject, message, keep_forever, created_at) 
            VALUES (?, ?, ?, ?, 0, NOW())
        ");
        
        if ($stmt->execute([$name, $email, $subject, $message])) {
            $response['success'] = true;
            $response['message'] = 'Thank you for your message! We will get back to you soon.';
        } else {
            $response['message'] = 'Failed to send message. Please try again or contact us directly.';
        }
        
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate entry
            $response['message'] = 'You have already sent a message. Our personnel will contact you shortly. For urgent matters, please call us directly.';
        } else {
            $response['message'] = 'An error occurred. Please try again later or contact us directly.';
        }
    }
} else {
    $response['message'] = 'Invalid request method.';
}

// Clean buffer and output JSON
ob_end_clean();
echo json_encode($response);
exit;
