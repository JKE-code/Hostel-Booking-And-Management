<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Check if PDO is available
if ($pdo === null) {
    die("Database connection failed. Please check your database configuration.");
}

// Set page variables
$page_title = 'Queries & Reviews Management';
$page_heading = 'Manage Queries & Reviews';

// Handle Delete Contact Message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_contact'])) {
    $contact_id = intval($_POST['contact_id']);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$contact_id]);
        $_SESSION['success_message'] = '✅ Contact message deleted successfully!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = '❌ Error deleting contact message.';
    }
    header('Location: queries.php');
    exit;
}

// Handle Keep Forever Contact
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['keep_contact'])) {
    $contact_id = intval($_POST['contact_id']);
    $keep_value = intval($_POST['keep_value']);
    
    try {
        $stmt = $pdo->prepare("UPDATE contact_messages SET keep_forever = ? WHERE id = ?");
        $stmt->execute([$keep_value, $contact_id]);
        $_SESSION['success_message'] = $keep_value ? '✅ Contact message marked to keep forever!' : '✅ Contact message unmarked from keep forever!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = '❌ Error updating contact message.';
    }
    header('Location: queries.php');
    exit;
}

// Handle Delete Review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review'])) {
    $review_id = intval($_POST['review_id']);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$review_id]);
        $_SESSION['success_message'] = '✅ Review deleted successfully!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = '❌ Error deleting review.';
    }
    header('Location: queries.php');
    exit;
}

// Handle Keep Forever Review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['keep_review'])) {
    $review_id = intval($_POST['review_id']);
    $keep_value = intval($_POST['keep_value']);
    
    try {
        $stmt = $pdo->prepare("UPDATE reviews SET keep_forever = ? WHERE id = ?");
        $stmt->execute([$keep_value, $review_id]);
        $_SESSION['success_message'] = $keep_value ? '✅ Review marked to keep forever!' : '✅ Review unmarked from keep forever!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = '❌ Error updating review.';
    }
    header('Location: queries.php');
    exit;
}

// Get messages from session and clear them
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Fetch all contact messages
$contact_messages = [];
try {
    $stmt = $pdo->query("
        SELECT id, name, email, subject, message, keep_forever, created_at
        FROM contact_messages
        ORDER BY created_at DESC
    ");
    $contact_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = '❌ Error fetching contact messages.';
}

// Fetch all reviews
$reviews = [];
try {
    $stmt = $pdo->query("
        SELECT id, name, email, rating, review, keep_forever, created_at
        FROM reviews
        ORDER BY created_at DESC
    ");
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = '❌ Error fetching reviews.';
}

// Calculate items older than 10 days
$ten_days_ago = date('Y-m-d H:i:s', strtotime('-10 days'));
$old_contacts_count = 0;
$old_reviews_count = 0;

foreach ($contact_messages as $contact) {
    if ($contact['created_at'] < $ten_days_ago && !$contact['keep_forever']) {
        $old_contacts_count++;
    }
}

foreach ($reviews as $review) {
    if ($review['created_at'] < $ten_days_ago && !$review['keep_forever']) {
        $old_reviews_count++;
    }
}
?>
<?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="container">
            <?php if ($success_message): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Info Box -->
            <section class="section-card">
                <div style="background: #fff3cd; padding: 1rem; border-radius: 8px; color: #856404;">
                    <strong>ℹ️ Auto-Delete Policy:</strong> Items older than 10 days will be automatically deleted unless marked as "Keep Forever".
                    <br><strong>Current Status:</strong> 
                    <?php echo $old_contacts_count; ?> contact message(s) and <?php echo $old_reviews_count; ?> review(s) are eligible for deletion.
                </div>
            </section>

            <!-- Toggle Slider -->
            <section class="section-card">
                <div class="toggle-container">
                    <button class="toggle-btn active" onclick="showSection('contacts')">
                        📧 Contact Messages (<?php echo count($contact_messages); ?>)
                    </button>
                    <button class="toggle-btn" onclick="showSection('reviews')">
                        ⭐ Reviews (<?php echo count($reviews); ?>)
                    </button>
                </div>
            </section>

            <!-- Contact Messages Section -->
            <section id="contacts-section" class="section-card">
                <h2>📧 Contact Messages</h2>
                
                <?php if (empty($contact_messages)): ?>
                    <p>No contact messages found.</p>
                <?php else: ?>
                    <div style="overflow-x: auto; border-radius: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);">
                        <table style="width: 100%; border-collapse: collapse; background: white; border: 2px solid #e2e8f0;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">ID</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Name</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Email</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Subject</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Message</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Date</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Status</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contact_messages as $contact): ?>
                                    <?php
                                    $is_old = ($contact['created_at'] < $ten_days_ago && !$contact['keep_forever']);
                                    $bg_color = $is_old ? '#fff3cd' : 'white';
                                    ?>
                                    <tr style="background: <?php echo $bg_color; ?>; transition: all 0.2s ease;" onmouseover="if(this.style.background==='white' || this.style.background==='rgb(255, 255, 255)') this.style.background='linear-gradient(135deg, #fef2f2 0%, #fee2e2 50%, #fef2f2 100%)'" onmouseout="this.style.background='<?php echo $bg_color; ?>'">
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;"><?php echo $contact['id']; ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0; font-weight: 500;"><?php echo htmlspecialchars($contact['name']); ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($contact['email']); ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($contact['subject']); ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0; max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                            <?php echo htmlspecialchars(substr($contact['message'], 0, 100)) . (strlen($contact['message']) > 100 ? '...' : ''); ?>
                                        </td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0; white-space: nowrap;"><?php echo date('M d, Y', strtotime($contact['created_at'])); ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <?php if ($contact['keep_forever']): ?>
                                                <span style="background: #dcfce7; color: #166534; padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">Keep Forever</span>
                                            <?php elseif ($is_old): ?>
                                                <span style="background: #fee2e2; color: #991b1b; padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">Will Delete</span>
                                            <?php else: ?>
                                                <span style="background: #d1ecf1; color: #0c5460; padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                                <!-- View Full Message -->
                                                <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);" class="view-contact-btn"
                                                        data-name="<?php echo htmlspecialchars($contact['name'], ENT_QUOTES); ?>"
                                                        data-email="<?php echo htmlspecialchars($contact['email'], ENT_QUOTES); ?>"
                                                        data-subject="<?php echo htmlspecialchars($contact['subject'], ENT_QUOTES); ?>"
                                                        data-message="<?php echo htmlspecialchars($contact['message'], ENT_QUOTES); ?>"
                                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)'" 
                                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.3)'">
                                                    View
                                                </button>
                                                
                                                <!-- Keep Forever Toggle -->
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                                    <input type="hidden" name="keep_value" value="<?php echo $contact['keep_forever'] ? 0 : 1; ?>">
                                                    <button type="submit" name="keep_contact" 
                                                            style="background: linear-gradient(135deg, <?php echo $contact['keep_forever'] ? '#f59e0b 0%, #d97706 100%' : '#10b981 0%, #059669 100%'; ?>); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);"
                                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.3)'" 
                                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.2)'">
                                                        <?php echo $contact['keep_forever'] ? 'Unkeep' : 'Keep'; ?>
                                                    </button>
                                                </form>
                                                
                                                <!-- Delete Button -->
                                                <form method="POST" style="display: inline;" 
                                                      onsubmit="return confirm('Are you sure you want to delete this contact message?');">
                                                    <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                                    <button type="submit" name="delete_contact" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);"
                                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.4)'" 
                                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(239, 68, 68, 0.3)'">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Reviews Section -->
            <section id="reviews-section" class="section-card" style="display: none;">
                <h2>⭐ Customer Reviews</h2>
                
                <?php if (empty($reviews)): ?>
                    <p>No reviews found.</p>
                <?php else: ?>
                    <div style="overflow-x: auto; border-radius: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);">
                        <table style="width: 100%; border-collapse: collapse; background: white; border: 2px solid #e2e8f0;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">ID</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Name</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Email</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Rating</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Review</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Date</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Status</th>
                                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem; border: 1px solid #e2e8f0;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reviews as $review): ?>
                                    <?php
                                    $is_old = ($review['created_at'] < $ten_days_ago && !$review['keep_forever']);
                                    $bg_color = $is_old ? '#fff3cd' : 'white';
                                    ?>
                                    <tr style="background: <?php echo $bg_color; ?>; transition: all 0.2s ease;" onmouseover="if(this.style.background==='white' || this.style.background==='rgb(255, 255, 255)') this.style.background='linear-gradient(135deg, #fef2f2 0%, #fee2e2 50%, #fef2f2 100%)'" onmouseout="this.style.background='<?php echo $bg_color; ?>'">
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;"><?php echo $review['id']; ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0; font-weight: 500;"><?php echo htmlspecialchars($review['name']); ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($review['email']); ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <?php 
                                            $stars = str_repeat('⭐', $review['rating']);
                                            echo $stars . ' (' . $review['rating'] . ')';
                                            ?>
                                        </td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0; max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                            <?php echo htmlspecialchars(substr($review['review'], 0, 100)) . (strlen($review['review']) > 100 ? '...' : ''); ?>
                                        </td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0; white-space: nowrap;"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <?php if ($review['keep_forever']): ?>
                                                <span style="background: #dcfce7; color: #166534; padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">Keep Forever</span>
                                            <?php elseif ($is_old): ?>
                                                <span style="background: #fee2e2; color: #991b1b; padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">Will Delete</span>
                                            <?php else: ?>
                                                <span style="background: #d1ecf1; color: #0c5460; padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 1rem; border: 1px solid #e2e8f0;">
                                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                                <!-- View Full Review -->
                                                <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);" class="view-review-btn"
                                                        data-name="<?php echo htmlspecialchars($review['name'], ENT_QUOTES); ?>"
                                                        data-rating="<?php echo $review['rating']; ?>"
                                                        data-review="<?php echo htmlspecialchars($review['review'], ENT_QUOTES); ?>"
                                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)'" 
                                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.3)'">
                                                    View
                                                </button>
                                                
                                                <!-- Keep Forever Toggle -->
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                                    <input type="hidden" name="keep_value" value="<?php echo $review['keep_forever'] ? 0 : 1; ?>">
                                                    <button type="submit" name="keep_review" 
                                                            style="background: linear-gradient(135deg, <?php echo $review['keep_forever'] ? '#f59e0b 0%, #d97706 100%' : '#10b981 0%, #059669 100%'; ?>); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);"
                                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.3)'" 
                                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.2)'">
                                                        <?php echo $review['keep_forever'] ? 'Unkeep' : 'Keep'; ?>
                                                    </button>
                                                </form>
                                                
                                                <!-- Delete Button -->
                                                <form method="POST" style="display: inline;" 
                                                      onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                    <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                                    <button type="submit" name="delete_review" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);"
                                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.4)'" 
                                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(239, 68, 68, 0.3)'">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>

            <!-- View Modal -->
            <div id="viewModal" class="modal" style="display: none;">
                <div class="modal-content" style="max-width: 600px;">
                    <span class="close" onclick="closeViewModal()">&times;</span>
                    <div id="modalContent"></div>
                </div>
            </div>

        </div>
    </main>

    <script>
        function showSection(section) {
            const contactsSection = document.getElementById('contacts-section');
            const reviewsSection = document.getElementById('reviews-section');
            const buttons = document.querySelectorAll('.toggle-btn');
            
            // Add fade out animation to current section
            const currentSection = section === 'contacts' ? reviewsSection : contactsSection;
            const nextSection = section === 'contacts' ? contactsSection : reviewsSection;
            
            // Fade out current section
            currentSection.style.animation = 'fadeOut 0.3s ease-in-out';
            
            setTimeout(() => {
                // Hide current section
                currentSection.style.display = 'none';
                currentSection.style.animation = '';
                
                // Show and fade in next section
                nextSection.style.display = 'block';
                nextSection.style.animation = 'fadeIn 0.5s ease-in-out';
                
                // Update button states
                if (section === 'contacts') {
                    buttons[0].classList.add('active');
                    buttons[1].classList.remove('active');
                } else {
                    buttons[0].classList.remove('active');
                    buttons[1].classList.add('active');
                }
            }, 300);
        }

        // Event listeners for contact message view buttons
        document.addEventListener('DOMContentLoaded', function() {
            // Contact message view buttons
            document.querySelectorAll('.view-contact-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const subject = this.getAttribute('data-subject');
                    const message = this.getAttribute('data-message');
                    viewMessage(name, email, subject, message);
                });
            });

            // Review view buttons
            document.querySelectorAll('.view-review-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const name = this.getAttribute('data-name');
                    const rating = this.getAttribute('data-rating');
                    const review = this.getAttribute('data-review');
                    viewReview(name, rating, review);
                });
            });
        });

        function viewMessage(name, email, subject, message) {
            const content = `
                <h2>📧 Contact Message</h2>
                <p><strong>Name:</strong> ${escapeHtml(name)}</p>
                <p><strong>Email:</strong> ${escapeHtml(email)}</p>
                <p><strong>Subject:</strong> ${escapeHtml(subject)}</p>
                <p><strong>Message:</strong></p>
                <p style="background: #f8f9fa; padding: 1rem; border-radius: 5px; white-space: pre-wrap;">${escapeHtml(message)}</p>
            `;
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('viewModal').style.display = 'block';
        }

        function viewReview(name, rating, review) {
            const stars = '⭐'.repeat(rating);
            const content = `
                <h2>⭐ Customer Review</h2>
                <p><strong>Name:</strong> ${escapeHtml(name)}</p>
                <p><strong>Rating:</strong> ${stars} (${rating}/5)</p>
                <p><strong>Review:</strong></p>
                <p style="background: #f8f9fa; padding: 1rem; border-radius: 5px; white-space: pre-wrap;">${escapeHtml(review)}</p>
            `;
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('viewModal').style.display = 'block';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('viewModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Auto-hide success and error messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.querySelector('.success-message');
            const errorMessage = document.querySelector('.error-message');
            
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.transition = 'opacity 0.5s ease';
                    successMessage.style.opacity = '0';
                    setTimeout(function() {
                        successMessage.style.display = 'none';
                    }, 500);
                }, 5000); // Hide after 5 seconds
            }
            
            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.style.transition = 'opacity 0.5s ease';
                    errorMessage.style.opacity = '0';
                    setTimeout(function() {
                        errorMessage.style.display = 'none';
                    }, 500);
                }, 5000); // Hide after 5 seconds
            }
        });
    </script>

<?php include 'includes/footer.php'; ?>
