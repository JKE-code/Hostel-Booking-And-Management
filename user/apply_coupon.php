<?php
session_start();

if (isset($_POST['coupon_id'])) {
    $_SESSION['booking']['coupon_id'] = intval($_POST['coupon_id']);
}

header('Location: booking_step5_review.php');
exit();
?>
