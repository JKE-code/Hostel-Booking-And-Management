<?php
// Check if in offline mode
if (!isset($_SESSION['offline_mode']) || $_SESSION['offline_mode'] !== true) {
    header('Location: login.php');
    exit();
}

// Prevent caching of offline pages
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#8b0000">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Walk-in Booking - Alluri Resorts</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Offline Booking Specific Styles */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            margin: 0;
            padding: 0;
        }

        .offline-booking-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .booking-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
            padding: 0 1rem;
        }

        .booking-progress::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 10%;
            right: 10%;
            height: 4px;
            background: linear-gradient(90deg, #e5e7eb 0%, #e5e7eb 100%);
            z-index: 0;
        }

        .progress-step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .progress-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: white;
            border: 3px solid #e5e7eb;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-weight: 700;
            font-size: 1.125rem;
            transition: all 0.3s ease;
        }

        .progress-step.active .progress-circle {
            background: linear-gradient(135deg, #8b0000 0%, #6b0000 100%);
            border-color: #8b0000;
            color: white;
            box-shadow: 0 4px 16px rgba(139, 0, 0, 0.4);
            transform: scale(1.1);
        }

        .progress-step.completed .progress-circle {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: #10b981;
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .progress-label {
            font-size: 0.875rem;
            color: #9ca3af;
            font-weight: 600;
        }

        .progress-step.active .progress-label {
            color: #8b0000;
            font-weight: 700;
        }

        .progress-step.completed .progress-label {
            color: #10b981;
        }

        .booking-card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .booking-card h2 {
            color: #8b0000;
            margin-bottom: 0.5rem;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .booking-card > p {
            color: #6b7280;
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        .room-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 1.5rem;
        }

        .room-card {
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .room-card:hover {
            border-color: #8b0000;
            box-shadow: 0 8px 24px rgba(139, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .room-card.selected {
            border-color: #8b0000;
            background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
            box-shadow: 0 8px 24px rgba(139, 0, 0, 0.2);
        }

        .room-image-container {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
        }

        .room-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .room-card:hover .room-image {
            transform: scale(1.1);
        }

        .room-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, #8b0000 0%, #6b0000 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .room-card-content {
            padding: 1.5rem;
        }

        .room-card h3 {
            color: #1f2937;
            margin-bottom: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .room-price {
            font-size: 2rem;
            color: #8b0000;
            font-weight: 700;
            margin: 0.75rem 0;
            display: flex;
            align-items: baseline;
            gap: 0.25rem;
        }

        .price-unit {
            font-size: 1rem;
            color: #6b7280;
            font-weight: 500;
        }

        .room-features {
            margin: 1.25rem 0;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #4b5563;
            font-size: 0.95rem;
        }

        .feature-icon {
            font-size: 1.25rem;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            border-radius: 8px;
        }

        .feature-text {
            font-weight: 500;
        }

        .room-details {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }

        .quantity-selector {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f3f4f6;
        }

        .quantity-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
        }

        .quantity-btn {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 2px solid #8b0000;
            background: white;
            color: #8b0000;
            font-weight: 700;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-btn:hover {
            background: #8b0000;
            color: white;
            transform: scale(1.1);
        }

        .quantity-btn:active {
            transform: scale(0.95);
        }

        .quantity-value {
            font-size: 1.75rem;
            font-weight: 700;
            min-width: 50px;
            text-align: center;
            color: #1f2937;
        }

        .pinned-addon {
            position: relative;
        }

        .pinned-addon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%);
            pointer-events: none;
            z-index: 0;
        }

        .pinned-addon .room-image-container,
        .pinned-addon .room-card-content {
            position: relative;
            z-index: 1;
        }

        .booking-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn-offline {
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 1.05rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-offline-primary {
            background: linear-gradient(135deg, #8b0000 0%, #6b0000 100%);
            color: white;
        }

        .btn-offline-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 0, 0, 0.4);
        }

        .btn-offline-primary:active {
            transform: translateY(0);
        }

        .btn-offline-secondary {
            background: white;
            color: #6b7280;
            border: 2px solid #e5e7eb;
        }

        .btn-offline-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #374151;
        }

        .offline-badge {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border-radius: 24px;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            }
            50% {
                box-shadow: 0 4px 20px rgba(245, 158, 11, 0.5);
            }
        }

        /* Secret Admin Exit Button */
        .secret-admin-exit {
            position: fixed;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            background: transparent;
            border: none;
            cursor: pointer;
            opacity: 0.1;
            transition: opacity 0.3s ease;
            z-index: 9999;
            font-size: 1.5rem;
        }

        .secret-admin-exit:hover {
            opacity: 1;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #dc2626;
        }

        .success-message {
            background: #d1fae5;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #10b981;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .offline-booking-container {
                padding: 1rem;
            }

            .booking-card {
                padding: 1.5rem;
            }

            .room-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .booking-progress {
                padding: 0;
            }

            .progress-label {
                font-size: 0.75rem;
            }

            .progress-circle {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .booking-actions {
                flex-direction: column;
            }

            .btn-offline {
                width: 100%;
            }

            .room-price {
                font-size: 1.5rem;
            }

            .booking-card h2 {
                font-size: 1.5rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .room-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1025px) {
            .room-grid {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            }
        }

        /* Image Modal Styles */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.95);
            animation: fadeIn 0.3s ease;
        }

        .image-modal.show {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .image-modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 85vh;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }

        .image-modal-close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 50px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10001;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
        }

        .image-modal-close:hover,
        .image-modal-close:focus {
            color: #8b0000;
            transform: scale(1.2);
        }

        .image-modal-caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #fff;
            padding: 20px;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.25rem;
            font-weight: 600;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
        }

        .room-image-container {
            cursor: pointer;
        }

        .room-image-container:hover .room-image {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<!-- Room Image Modal -->
<div id="roomImageModal" class="image-modal">
    <span class="image-modal-close" onclick="closeRoomImageModal()">&times;</span>
    <img class="image-modal-content" id="roomModalImage" alt="">
    <div class="image-modal-caption" id="roomModalCaption"></div>
</div>

<!-- Secret Admin Exit Button (Triple Click to Exit) -->
<button class="secret-admin-exit" onclick="secretExit()" title="Admin Exit">🔒</button>

<script>
// Aggressive back button prevention
(function() {
    // Push initial state
    history.pushState(null, null, location.href);
    
    // Override back button
    window.onpopstate = function () {
        history.pushState(null, null, location.href);
    };
    
    // Additional prevention on load
    window.addEventListener('load', function() {
        history.pushState(null, null, location.href);
    });
    
    // Monitor for back navigation attempts
    window.addEventListener('popstate', function(e) {
        history.pushState(null, null, location.href);
        // Force reload to check session
        location.reload();
    });
    
    // Prevent page from being cached
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
})();

// Secret exit functionality
let clickCount = 0;
let clickTimer = null;

function secretExit() {
    clickCount++;
    
    if (clickCount === 1) {
        clickTimer = setTimeout(() => {
            clickCount = 0;
        }, 1000);
    }
    
    if (clickCount === 3) {
        clearTimeout(clickTimer);
        if (confirm('Exit Walk-in Mode? This will log you out.')) {
            // Clear booking session and logout
            window.location.href = 'offline_exit.php';
        }
        clickCount = 0;
    }
}

// Disable keyboard shortcuts that might navigate away
document.addEventListener('keydown', function(e) {
    // Disable Alt+Left (back), Alt+Right (forward)
    if (e.altKey && (e.keyCode === 37 || e.keyCode === 39)) {
        e.preventDefault();
        return false;
    }
    // Disable Backspace navigation (except in input fields)
    if (e.keyCode === 8 && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
        e.preventDefault();
        return false;
    }
    // Close modal with Escape key
    if (e.keyCode === 27) {
        closeRoomImageModal();
    }
});

// Image Modal Functions
function openRoomImageModal(imageSrc, caption) {
    const modal = document.getElementById('roomImageModal');
    const modalImg = document.getElementById('roomModalImage');
    const modalCaption = document.getElementById('roomModalCaption');
    
    modal.classList.add('show');
    modalImg.src = imageSrc;
    modalCaption.textContent = caption;
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeRoomImageModal() {
    const modal = document.getElementById('roomImageModal');
    modal.classList.remove('show');
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside the image
document.getElementById('roomImageModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRoomImageModal();
    }
});
</script>
