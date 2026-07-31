<?php
session_start();

$error_message = isset($_GET['error']) ? $_GET['error'] : 'An error occurred while processing your booking.';
?>
<?php include 'includes/header.php'; ?>

    <!-- Error Section -->
    <section class="booking-section">
        <div class="container">
            <div class="error-container">
                <div class="error-card">
                    <div class="error-icon">✗</div>
                    <h1>Booking Failed</h1>
                    <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
                    
                    <div class="error-info">
                        <p>We're sorry, but we couldn't complete your booking at this time.</p>
                        <p>Please try again or contact our support team for assistance.</p>
                    </div>
                    
                    <div class="error-actions">
                        <a href="booking_step1_rooms.php" class="btn-primary">Try Again</a>
                        <a href="contact.php" class="btn-secondary">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .error-container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 2rem 1rem;
        }
        
        .error-card {
            background: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .error-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            font-size: 4rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .error-card h1 {
            color: #1a202c;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .error-message {
            color: #dc2626;
            font-size: 1.125rem;
            margin-bottom: 2rem;
            font-weight: 600;
        }
        
        .error-info {
            background: #fef2f2;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        
        .error-info p {
            color: #991b1b;
            margin: 0.5rem 0;
            font-size: 0.9375rem;
        }
        
        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-primary, .btn-secondary {
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #8b0000 0%, #a52a2a 100%);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(139, 0, 0, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: #8b0000;
            border: 2px solid #8b0000;
        }
        
        .btn-secondary:hover {
            background: #8b0000;
            color: white;
        }
        
        @media (max-width: 768px) {
            .error-card {
                padding: 2rem 1rem;
            }
            
            .error-card h1 {
                font-size: 1.5rem;
            }
            
            .error-actions {
                flex-direction: column;
            }
            
            .btn-primary, .btn-secondary {
                width: 100%;
            }
        }
    </style>

<?php include 'includes/footer.php'; ?>
