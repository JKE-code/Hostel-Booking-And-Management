<?php
// Review submission handler
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Start output buffering to prevent any accidental output
ob_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'debug_step' => ''
];

try {
    $response['debug_step'] = 'start';
    
    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    $response['debug_step'] = 'method_check_passed';
    
    // Get form data
    $name = isset($_POST['reviewer-name']) ? trim($_POST['reviewer-name']) : '';
    $email = isset($_POST['reviewer-email']) ? trim($_POST['reviewer-email']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $review_text = isset($_POST['review-text']) ? trim($_POST['review-text']) : '';
    
    $response['debug_step'] = 'data_received';
    
    // Validate inputs
    if (empty($name)) {
        throw new Exception('Please enter your name');
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please enter a valid email address');
    }
    
    if ($rating < 1 || $rating > 5) {
        throw new Exception('Please select a rating');
    }
    
    if (empty($review_text)) {
        throw new Exception('Please write your review');
    }
    
    if (strlen($review_text) < 10) {
        throw new Exception('Review must be at least 10 characters long');
    }
    
    $response['debug_step'] = 'validation_passed';
    
    // Connect to database
    require_once '../config/db.php';
    
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        throw new Exception('Database connection failed. Please try again later.');
    }
    
    $response['debug_step'] = 'db_connected';
    
    // Check if email already submitted a review
    $checkStmt = $pdo->prepare("SELECT id FROM reviews WHERE email = ?");
    $checkStmt->execute([$email]);
    $existingReview = $checkStmt->fetch();
    
    $response['debug_step'] = 'duplicate_check_done';
    $response['debug_existing'] = $existingReview ? 'found' : 'not_found';
    
    if ($existingReview) {
        $response['debug_step'] = 'duplicate_found';
        throw new Exception('You have already submitted a review. Thank you for your feedback!');
    }
    
    $response['debug_step'] = 'inserting';
    
    // Insert review into database
    $stmt = $pdo->prepare("INSERT INTO reviews (name, email, rating, review, keep_forever, created_at) VALUES (?, ?, ?, ?, FALSE, NOW())");
    $result = $stmt->execute([$name, $email, $rating, $review_text]);
    
    $response['debug_step'] = 'insert_executed';
    
    if ($result) {
        $response['success'] = true;
        $response['message'] = 'Successfully submitted review. Thank you!';
        $response['debug_step'] = 'success';
        $response['debug_insert_id'] = $pdo->lastInsertId();
    } else {
        throw new Exception('Failed to submit review. Please try again.');
    }
    
} catch (PDOException $e) {
    $response['debug_step'] = 'pdo_exception';
    $response['debug_error_code'] = $e->getCode();
    
    // Check if it's a duplicate entry error
    if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false) {
        $response['success'] = false;
        $response['message'] = 'You have already submitted a review. Thank you for your feedback!';
    } else {
        $response['success'] = false;
        $response['message'] = 'Sorry, there was an error submitting your review. Please try again later.';
        error_log("Review submission PDO error: " . $e->getMessage());
    }
} catch (Exception $e) {
    $response['debug_step'] = 'exception';
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

// Clear any accidental output
ob_end_clean();

// Send clean JSON response
echo json_encode($response);
exit;
?>
