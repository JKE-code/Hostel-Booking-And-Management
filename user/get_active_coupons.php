<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

try {
    // Fetch active coupons that haven't expired
    $query = "SELECT 
                code, 
                discount_type, 
                discount_value, 
                min_amount, 
                max_uses, 
                used_count, 
                expiry_date 
              FROM coupons 
              WHERE status = 'active' 
              AND (expiry_date IS NULL OR expiry_date >= CURDATE())
              AND (max_uses IS NULL OR used_count < max_uses)
              ORDER BY discount_value DESC";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception('Database query failed: ' . mysqli_error($conn));
    }
    
    $coupons = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Build eligibility text
        $eligibility = [];
        
        if ($row['min_amount']) {
            $eligibility[] = 'Min booking: ₹' . number_format($row['min_amount'], 0);
        }
        
        if ($row['max_uses']) {
            $remaining = $row['max_uses'] - $row['used_count'];
            $eligibility[] = $remaining . ' uses left';
        }
        
        if ($row['expiry_date']) {
            $eligibility[] = 'Valid till ' . date('d M Y', strtotime($row['expiry_date']));
        }
        
        // Build discount text
        if ($row['discount_type'] === 'percent') {
            $discount_text = $row['discount_value'] . '% OFF';
        } else {
            $discount_text = '₹' . number_format($row['discount_value'], 0) . ' OFF';
        }
        
        $coupons[] = [
            'code' => $row['code'],
            'discount_text' => $discount_text,
            'eligibility' => implode(' • ', $eligibility),
            'has_eligibility' => !empty($eligibility)
        ];
    }
    
    echo json_encode([
        'success' => true,
        'coupons' => $coupons
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
