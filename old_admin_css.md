/* ======================================== */
/* ADMIN PANEL CSS - Combined */
/* All admin-related CSS files combined */
/* ======================================== */

/* ======================================== */
/* Source: css/admin-coupons.css */
/* ======================================== */

/* Admin Coupon Management Styles */

:root {
    --color-primary: #8b0000;
    --color-secondary: #2d5f3f;
    --color-dark: #1a1a1a;
    --color-gray: #6b7280;
    --color-light-gray: #f3f4f6;
    --color-border: #e5e7eb;
    --color-success: #10b981;
    --color-warning: #f59e0b;
    --color-danger: #ef4444;
    --color-info: #3b82f6;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
    color: var(--color-dark);
    line-height: 1.6;
    min-height: 100vh;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Header */
.admin-header {
    background: linear-gradient(135deg, var(--color-primary) 0%, #6b0000 100%);
    color: white;
    padding: 1.5rem 0;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.admin-header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.admin-header h1 {
    font-family: 'Poppins', sans-serif;
    font-size: 1.75rem;
    font-weight: 700;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-secondary, .btn-logout, .btn-primary, .btn-danger, .btn-generate {
    padding: 0.625rem 1.25rem;
    border: none;
    border-radius: 8px;
    font-family: 'Inter', sans-serif;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.25);
}

.btn-logout {
    background: rgba(239, 68, 68, 0.2);
    color: white;
}

.btn-logout:hover {
    background: rgba(239, 68, 68, 0.3);
}

.btn-primary {
    background: var(--color-primary);
    color: white;
}

.btn-primary:hover {
    background: #6b0000;
}

.btn-nav {
    padding: 0.625rem 1.25rem;
    border: none;
    border-radius: 8px;
    font-family: 'Inter', sans-serif;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

.btn-nav:hover {
    background: rgba(255, 255, 255, 0.25);
}

.btn-nav.active {
    background: white;
    color: var(--color-primary);
}

/* Mobile Navigation Toggle */
.admin-nav-toggle,
.admin-nav-toggle-label {
    display: none;
}

.admin-nav-toggle-label {
    flex-direction: column;
    cursor: pointer;
    gap: 5px;
}

.admin-nav-toggle-label span {
    width: 25px;
    height: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 3px;
}

.admin-nav-toggle:checked ~ .admin-nav-toggle-label span:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px);
}

.admin-nav-toggle:checked ~ .admin-nav-toggle-label span:nth-child(2) {
    opacity: 0;
}

.admin-nav-toggle:checked ~ .admin-nav-toggle-label span:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px);
}
}

.btn-danger {
    background: var(--color-danger);
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

/* Main Content */
.dashboard-main {
    padding: 2rem 0;
}

.section-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.section-card h2 {
    font-family: 'Poppins', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-dark);
    margin-bottom: 0.5rem;
}

/* Form Styles */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 0;
}

.form-group label {
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--color-dark);
}

.required {
    color: var(--color-danger);
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 0.625rem 0.75rem;
    border: 2px solid var(--color-border);
    border-radius: 8px;
    font-family: 'Inter', sans-serif;
    font-size: 0.875rem;
    transition: border-color 0.2s ease;
    background: white;
    width: 100%;
}

.form-group select {
    position: relative;
    z-index: 1;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--color-primary);
    z-index: 2;
}

.form-group small {
    font-size: 0.8125rem;
    color: var(--color-gray);
}

.input-with-button {
    display: flex;
    gap: 0.5rem;
    align-items: stretch;
}

.input-with-button input {
    flex: 1;
    min-width: 0;
}

.input-with-button span {
    display: flex;
    align-items: center;
    white-space: nowrap;
}

.btn-generate {
    background: var(--color-secondary);
    color: white;
    white-space: nowrap;
    flex-shrink: 0;
}

.btn-generate:hover {
    background: #1a4028;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--color-border);
}

/* Coupons List */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.stats-pills {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.stat-pill {
    background: var(--color-light-gray);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    color: var(--color-gray);
}

.stat-pill strong {
    color: var(--color-dark);
    font-weight: 700;
}

.coupons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

.coupon-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid var(--color-border);
    border-radius: 12px;
    padding: 1.5rem;
    position: relative;
    transition: all 0.3s ease;
}

.coupon-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.coupon-card.expired {
    opacity: 0.6;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
}

.coupon-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.coupon-code {
    font-family: 'Poppins', monospace;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-primary);
    letter-spacing: 1px;
}

.coupon-status {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.coupon-status.active {
    background: #d1fae5;
    color: #065f46;
}

.coupon-status.expired {
    background: #fee2e2;
    color: #991b1b;
}

.coupon-discount {
    font-size: 2rem;
    font-weight: 700;
    color: var(--color-success);
    margin: 0.5rem 0;
}

.coupon-description {
    font-size: 0.875rem;
    color: var(--color-gray);
    margin-bottom: 1rem;
}

.coupon-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--color-border);
}

.coupon-detail {
    display: flex;
    justify-content: space-between;
    font-size: 0.8125rem;
}

.coupon-detail-label {
    color: var(--color-gray);
}

.coupon-detail-value {
    font-weight: 600;
    color: var(--color-dark);
}

.coupon-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.btn-copy, .btn-delete {
    flex: 1;
    padding: 0.5rem;
    border: none;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-copy {
    background: var(--color-info);
    color: white;
}

.btn-copy:hover {
    background: #2563eb;
}

.btn-delete {
    background: var(--color-danger);
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
}

/* Statistics */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
}

.stat-box {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid var(--color-border);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    font-size: 2.5rem;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-family: 'Poppins', sans-serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--color-dark);
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--color-gray);
    margin-top: 0.25rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--color-gray);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 2000;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.modal.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 16px;
    max-width: 500px;
    width: 100%;
    overflow: hidden;
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background: linear-gradient(135deg, var(--color-primary) 0%, #6b0000 100%);
    color: white;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    font-size: 1.25rem;
    margin: 0;
}

.modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 1.5rem;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
}

.modal-body {
    padding: 2rem;
}

.coupon-preview {
    background: var(--color-light-gray);
    padding: 1rem;
    border-radius: 8px;
    margin: 1rem 0;
    font-family: 'Poppins', monospace;
    font-size: 1.25rem;
    font-weight: 700;
    text-align: center;
    color: var(--color-primary);
}

.warning-text {
    color: var(--color-warning);
    font-weight: 600;
    margin-top: 1rem;
}

.modal-footer {
    padding: 1.5rem;
    background: var(--color-light-gray);
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

/* Success Message */
.success-message {
    background: #d1fae5;
    color: #065f46;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    display: none;
}

.success-message.show {
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 0 1rem;
    }
    
    .admin-header .container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: nowrap;
        gap: 1rem;
    }
    
    .admin-nav-toggle-label {
        display: flex;
        z-index: 1002;
    }
    
    .admin-header h1 {
        flex: 1;
        font-size: 1.4rem;
    }
    
    .header-actions {
        position: fixed;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100vh;
        background: linear-gradient(135deg, var(--color-primary) 0%, #6b0000 100%);
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: left 0.3s ease;
        z-index: 1001;
        gap: 2rem;
    }
    
    .admin-nav-toggle:checked ~ .header-actions {
        left: 0;
    }
    
    .header-actions .btn-secondary,
    .header-actions .btn-logout,
    .header-actions .btn-nav {
        width: 80%;
        max-width: 300px;
        padding: 1rem 1.5rem;
        font-size: 1.1rem;
        text-align: center;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .coupons-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .admin-header h1 {
        font-size: 1.2rem;
    }
}


/* Add-ons Pricing Specific Styles */
.addons-pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.addon-price-item {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid var(--color-border);
    border-radius: 12px;
    padding: 1.25rem;
}

.addon-price-item label {
    display: block;
    font-weight: 600;
    font-size: 0.9375rem;
    color: var(--color-dark);
    margin-bottom: 0.75rem;
}

.price-input-group {
    display: flex;
    align-items: stretch;
    gap: 0;
    border: 2px solid var(--color-border);
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.currency-symbol {
    display: flex;
    align-items: center;
    padding: 0 0.75rem;
    background: #f3f4f6;
    border-right: 2px solid var(--color-border);
    font-weight: 600;
    color: var(--color-gray);
}

.price-input-group input {
    flex: 1;
    border: none;
    padding: 0.625rem 0.75rem;
    font-size: 0.9375rem;
    min-width: 0;
}

.price-input-group input:focus {
    outline: none;
}

.btn-update {
    padding: 0.625rem 1rem;
    background: var(--color-primary);
    color: white;
    border: none;
    border-left: 2px solid var(--color-border);
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-update:hover {
    background: #6b0000;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .addons-pricing-grid {
        grid-template-columns: 1fr;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
}


/* ======================================== */
/* Source: css/admin-rooms-enhanced.css */
/* ======================================== */

/* Admin Rooms Dashboard - Enhanced Design */

:root {
    --color-primary: #8b0000;
    --color-secondary: #2d5f3f;
    --color-dark: #1a1a1a;
    --color-gray: #6b7280;
    --color-light-gray: #f3f4f6;
    --color-border: #e5e7eb;
    --color-success: #10b981;
    --color-warning: #f59e0b;
    --color-danger: #ef4444;
    --color-info: #3b82f6;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
    color: var(--color-dark);
    line-height: 1.6;
    min-height: 100vh;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Header */
.admin-header {
    background: linear-gradient(135deg, var(--color-primary) 0%, #6b0000 100%);
    color: white;
    padding: 1.5rem 0;
    box-shadow: var(--shadow-lg);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.admin-header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.admin-header h1 {
    font-family: 'Poppins', sans-serif;
    font-size: 1.75rem;
    font-weight: 700;
    letter-spacing: -0.5px;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-secondary, .btn-logout {
    padding: 0.625rem 1.25rem;
    border: none;
    border-radius: 8px;
    font-family: 'Inter', sans-serif;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    backdrop-filter: blur(10px);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-1px);
}

.btn-logout {
    background: rgba(239, 68, 68, 0.2);
    color: white;
}

.btn-logout:hover {
    background: rgba(239, 68, 68, 0.3);
}

.btn-nav {
    padding: 0.625rem 1.25rem;
    border: none;
    border-radius: 8px;
    font-family: 'Inter', sans-serif;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    background: rgba(255, 255, 255, 0.15);
    color: white;
    backdrop-filter: blur(10px);
}

.btn-nav:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-1px);
}

.btn-nav.active {
    background: white;
    color: var(--color-primary);
}

/* Mobile Navigation Toggle */
.admin-nav-toggle,
.admin-nav-toggle-label {
    display: none;
}

.admin-nav-toggle-label {
    flex-direction: column;
    cursor: pointer;
    gap: 5px;
}

.admin-nav-toggle-label span {
    width: 25px;
    height: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 3px;
}

.admin-nav-toggle:checked ~ .admin-nav-toggle-label span:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px);
}

.admin-nav-toggle:checked ~ .admin-nav-toggle-label span:nth-child(2) {
    opacity: 0;
}

.admin-nav-toggle:checked ~ .admin-nav-toggle-label span:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px);
}

/* Main Content */
.dashboard-main {
    padding: 2.5rem 0;
}

/* Overview Stats */
.overview-section {
    margin-bottom: 3rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: white;
    padding: 1.75rem;
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: all 0.3s ease;
    border-left: 4px solid var(--color-primary);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.stat-card.primary { border-left-color: var(--color-primary); }
.stat-card.success { border-left-color: var(--color-success); }
.stat-card.info { border-left-color: var(--color-info); }
.stat-card.warning { border-left-color: var(--color-warning); }

.stat-icon {
    font-size: 2.5rem;
    line-height: 1;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-family: 'Poppins', sans-serif;
    font-size: 2.25rem;
    font-weight: 700;
    color: var(--color-dark);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Room Categories */
.room-categories {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
}

.category-card {
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    transition: all 0.3s ease;
}

.category-card:hover {
    box-shadow: var(--shadow-xl);
}

.category-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 2rem;
    border-bottom: 3px solid var(--color-primary);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.category-info h2 {
    font-family: 'Poppins', sans-serif;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--color-dark);
    margin-bottom: 0.5rem;
}

.category-desc {
    font-size: 0.95rem;
    color: var(--color-gray);
    font-weight: 500;
}

.category-stats {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.stat-pill {
    background: white;
    padding: 0.625rem 1.25rem;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: var(--shadow-sm);
    border: 2px solid var(--color-border);
}

.stat-pill.occupied {
    background: #fef2f2;
    border-color: var(--color-danger);
}

.stat-pill.available {
    background: #f0fdf4;
    border-color: var(--color-success);
}

.pill-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-gray);
}

.pill-value {
    font-family: 'Poppins', sans-serif;
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--color-dark);
}

/* Rooms Grid */
.rooms-grid {
    padding: 2rem;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.25rem;
}

.room-box {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid var(--color-border);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.room-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--color-success);
    transition: all 0.3s ease;
}

.room-box.occupied::before {
    background: var(--color-danger);
}

.room-box:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--color-primary);
}

.room-box.occupied {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-color: var(--color-danger);
}

.room-box.occupied:hover {
    border-color: var(--color-danger);
}

.room-number {
    font-family: 'Poppins', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-dark);
    margin-bottom: 0.75rem;
}

.room-status {
    display: inline-block;
    padding: 0.375rem 0.875rem;
    border-radius: 50px;
    font-size: 0.8125rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.room-status.available {
    background: #d1fae5;
    color: #065f46;
}

.room-status.occupied {
    background: #fecaca;
    color: #991b1b;
}

.room-guest {
    margin-top: 0.75rem;
    font-size: 0.875rem;
    color: var(--color-gray);
    font-weight: 500;
}

.room-guest strong {
    color: var(--color-dark);
    display: block;
    margin-bottom: 0.25rem;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 2000;
    animation: fadeIn 0.2s ease;
}

.modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background: white;
    border-radius: 20px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    background: linear-gradient(135deg, var(--color-primary) 0%, #6b0000 100%);
    color: white;
    padding: 1.75rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    font-family: 'Poppins', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
}

.modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 2rem;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    line-height: 1;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.modal-body {
    padding: 2rem;
    overflow-y: auto;
    max-height: calc(90vh - 100px);
}

.detail-group {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--color-border);
}

.detail-group:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.detail-label {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--color-gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-dark);
}

.detail-value.large {
    font-family: 'Poppins', sans-serif;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--color-primary);
}

.payment-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.payment-badge.complete {
    background: #d1fae5;
    color: #065f46;
}

.payment-badge.advance {
    background: #dbeafe;
    color: #1e40af;
}

.payment-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--color-gray);
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state-text {
    font-size: 1.125rem;
    font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 0 1rem;
    }
    
    .admin-header .container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: nowrap;
        gap: 1rem;
    }
    
    .admin-nav-toggle-label {
        display: flex;
        z-index: 1002;
    }
    
    .admin-header h1 {
        flex: 1;
        font-size: 1.4rem;
    }
    
    .header-actions {
        position: fixed;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100vh;
        background: linear-gradient(135deg, var(--color-primary) 0%, #6b0000 100%);
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: left 0.3s ease;
        z-index: 1001;
        gap: 2rem;
    }
    
    .admin-nav-toggle:checked ~ .header-actions {
        left: 0;
    }
    
    .header-actions .btn-secondary,
    .header-actions .btn-logout,
    .header-actions .btn-nav {
        width: 80%;
        max-width: 300px;
        padding: 1rem 1.5rem;
        font-size: 1.1rem;
        text-align: center;
    }
    
    .category-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .rooms-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        padding: 1.5rem;
    }
    
    .stat-value {
        font-size: 1.75rem;
    }
    
    .modal-content {
        margin: 1rem;
    }
}

@media (max-width: 480px) {
    .admin-header h1 {
        font-size: 1.2rem;
    }
}


/* ======================================== */
/* Source: css/invoice.css */
/* ======================================== */

/* Invoice Template CSS - Alluri Resorts */

/* CSS Custom Properties */
:root {
    --color-primary: #8b0000;
    --color-white: #ffffff;
    --color-text: #333333;
    --color-dark: #200e01;
    --color-border: #ddd;
    --font-primary: "Montserrat", sans-serif;
}

/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: var(--font-primary);
    color: var(--color-text);
    background: #f5f5f5;
    line-height: 1.6;
}

/* Invoice Container */
.invoice-container {
    max-width: 900px;
    margin: 2rem auto;
    background: var(--color-white);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    position: relative;
}

/* Header Section */
.invoice-header {
    position: relative;
    background-image: url('../images/Reort_Building.jpg');
    background-size: cover;
    background-position: center;
    padding: 3rem 2rem;
    min-height: 250px;
}

.header-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--color-dark);
    opacity: 0.75;
}

.header-content {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.header-left {
    flex: 1;
}

.invoice-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--color-white);
    margin-bottom: 0.5rem;
    letter-spacing: 2px;
}

.hotel-name {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--color-white);
    margin-bottom: 0.75rem;
}

.hotel-address,
.hotel-website {
    color: var(--color-white);
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.header-right {
    text-align: right;
    flex-shrink: 0;
    min-width: 140px;
    max-width: 140px;
}

.logo-placeholder {
    margin-bottom: 1rem;
    width: 120px;
    height: auto;
    margin-left: auto;
}

.invoice-logo {
    width: 120px;
    max-width: 120px;
    height: auto;
    background: var(--color-white);
    padding: 0.5rem;
    border-radius: 8px;
    display: block;
}

.invoice-date {
    color: var(--color-white);
    font-size: 1rem;
    font-weight: 500;
}

/* Invoice Body */
.invoice-body {
    padding: 2.5rem 2rem;
}

/* Customer Information */
.customer-info {
    margin-bottom: 2rem;
}

.customer-name {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--color-text);
    margin-bottom: 0.5rem;
}

.invoice-id {
    font-size: 1.1rem;
    font-weight: 500;
    color: var(--color-text);
}

.accent-color {
    color: var(--color-primary);
    font-weight: 700;
}

.payment-id-display {
    font-size: 0.95rem;
    color: var(--color-text);
    margin-top: 0.5rem;
}

.payment-id-display span {
    font-family: monospace;
    background: #f0f0f0;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

/* Reservation Details */
.reservation-details {
    display: flex;
    gap: 2rem;
    margin-bottom: 2.5rem;
    padding: 1.5rem;
    background: #fafafa;
    border-radius: 8px;
}

.details-column {
    flex: 1;
}

.details-column ul {
    list-style: none;
}

.details-column li {
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
    color: var(--color-text);
}

.details-column li::before {
    content: "• ";
    color: var(--color-primary);
    font-weight: bold;
    margin-right: 0.5rem;
}

.detail-label {
    font-weight: 600;
}

.details-separator {
    width: 1px;
    background: var(--color-border);
}

/* Charges Table */
.charges-section {
    margin-bottom: 2rem;
}

.charges-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1.5rem;
}

.charges-table thead {
    border-bottom: 2px solid var(--color-border);
}

.charges-table th {
    padding: 1rem 0.75rem;
    text-align: left;
    font-weight: 600;
    color: var(--color-text);
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.charges-table th.amount-col {
    text-align: right;
}

.charges-table tbody tr {
    border-bottom: 1px solid var(--color-border);
}

.charges-table td {
    padding: 1rem 0.75rem;
    color: var(--color-text);
    font-size: 0.95rem;
}

.charges-table td.description-col {
    font-weight: 600;
}

.charges-table td.amount-col {
    text-align: right;
    font-weight: 600;
}

/* Summary and Notes */
.summary-and-notes {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
    margin-top: 2rem;
}

.promo-note {
    flex: 1;
    font-size: 0.9rem;
    color: var(--color-text);
    font-style: italic;
}

.promo-note p {
    margin-bottom: 0.5rem;
}

.financial-summary {
    min-width: 300px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    font-size: 1rem;
}

.summary-label {
    color: var(--color-text);
}

.summary-value {
    font-weight: 600;
    color: var(--color-text);
}

.grand-total-separator {
    border-top: 3px double var(--color-border);
    margin: 0.5rem 0;
}

.grand-total-row {
    padding: 1rem 0;
}

.grand-total-label {
    font-weight: 700;
    font-size: 1.2rem;
    color: var(--color-text);
}

.grand-total-value {
    font-weight: 700;
    font-size: 1.3rem;
    color: var(--color-primary);
}

/* Footer Message Bar */
.invoice-footer {
    background: var(--color-primary);
    padding: 1.5rem 2rem;
    text-align: center;
}

.invoice-footer p {
    color: var(--color-white);
    font-size: 1.2rem;
    font-weight: 700;
    letter-spacing: 1px;
    margin: 0;
}

/* Checkout Notice */
.checkout-notice {
    background: #ffc107;
    padding: 1rem 2rem;
    text-align: center;
    border-top: 3px solid #ff9800;
}

.checkout-notice p {
    color: #000;
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0;
    letter-spacing: 0.5px;
}

/* Print Styles */
@media print {
    body {
        background: var(--color-white);
        margin: 0;
        padding: 0;
    }

    .invoice-container {
        margin: 0;
        box-shadow: none;
        max-width: 100%;
        width: 100%;
    }

    .invoice-header {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
        padding: 2rem 1.5rem;
        min-height: auto;
        page-break-after: avoid;
    }

    .header-content {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .header-left {
        display: table-cell;
        width: 65%;
        vertical-align: top;
    }

    .header-right {
        display: table-cell;
        width: 35%;
        vertical-align: top;
        text-align: right;
    }

    .logo-placeholder {
        display: inline-block;
        margin-bottom: 0.5rem;
        width: auto;
        max-width: 100px;
    }

    .invoice-logo {
        width: 90px;
        max-width: 90px;
        height: auto;
        display: block;
        margin-left: auto;
        background: white;
        padding: 0.5rem;
        border-radius: 8px;
    }

    .invoice-footer {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }

    .checkout-notice {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
        page-break-inside: avoid;
    }

    .invoice-actions {
        display: none !important;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .invoice-container {
        margin: 0;
    }

    .invoice-header {
        padding: 2rem 1.5rem;
        min-height: 200px;
    }

    .header-content {
        flex-direction: column;
        gap: 1.5rem;
    }

    .header-right {
        text-align: left;
    }

    .invoice-title {
        font-size: 2rem;
    }

    .hotel-name {
        font-size: 1.2rem;
    }

    .invoice-body {
        padding: 1.5rem 1rem;
    }

    .customer-name {
        font-size: 1.4rem;
    }

    .reservation-details {
        flex-direction: column;
        gap: 1rem;
    }

    .details-separator {
        display: none;
    }

    .charges-table {
        font-size: 0.85rem;
    }

    .charges-table th,
    .charges-table td {
        padding: 0.75rem 0.5rem;
    }

    .summary-and-notes {
        flex-direction: column;
        gap: 1.5rem;
    }

    .financial-summary {
        width: 100%;
    }

    .invoice-footer p {
        font-size: 1rem;
    }
}

/* Invoice Action Buttons */
.invoice-actions {
  max-width: 900px;
  margin: 2rem auto;
  padding: 0 2rem 2rem;
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.invoice-actions .btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  font-family: var(--font-primary);
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
}

.invoice-actions .btn-primary {
  background: var(--color-primary);
  color: var(--color-white);
}

.invoice-actions .btn-primary:hover {
  background: #6d0000;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(139, 0, 0, 0.3);
}

.invoice-actions .btn-secondary {
  background: #6c757d;
  color: var(--color-white);
}

.invoice-actions .btn-secondary:hover {
  background: #5a6268;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

@media print {
  .invoice-actions {
    display: none;
  }
}

/* PDF Generation Specific Styles */
.pdf-generation {
  transform: scale(0.88);
  transform-origin: top left;
  margin: 0 !important;
  padding: 0 !important;
  position: relative;
  top: 0 !important;
  left: 0 !important;
  width: 210mm;
  background: white;
}

.pdf-generation .invoice-header {
  margin-top: 0 !important;
  padding-top: 2rem;
}

.pdf-generation .invoice-body {
  padding: 1.5rem 1.5rem !important;
}

.pdf-generation .charges-table {
  page-break-inside: avoid !important;
}

.pdf-generation .no-page-break {
  page-break-inside: avoid !important;
}

/* Enhanced Print Styles for PDF Generation */
@media print {
  * {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    color-adjust: exact !important;
  }

  body {
    background: white;
    margin: 0 !important;
    padding: 0 !important;
  }

  .invoice-container {
    margin: 0 !important;
    padding: 0 !important;
    box-shadow: none;
    max-width: 100%;
    width: 210mm;
    min-height: auto;
    page-break-after: avoid;
    page-break-before: avoid;
    page-break-inside: avoid;
  }

  .invoice-header {
    page-break-after: avoid;
    page-break-inside: avoid;
    min-height: 200px;
    max-height: 250px;
    overflow: visible !important;
  }

  .invoice-logo {
    width: 90px !important;
    max-width: 90px !important;
    height: auto !important;
    display: block !important;
    margin-left: auto !important;
    background: white !important;
    padding: 0.5rem !important;
    border-radius: 8px !important;
  }

  .logo-placeholder {
    display: inline-block !important;
    margin-bottom: 0.5rem !important;
    width: auto !important;
    max-width: 100px !important;
  }

  .header-content {
    display: table !important;
    width: 100% !important;
    table-layout: fixed !important;
  }

  .header-left {
    display: table-cell !important;
    width: 65% !important;
    vertical-align: top !important;
  }

  .header-right {
    display: table-cell !important;
    width: 35% !important;
    vertical-align: top !important;
    text-align: right !important;
  }

  .invoice-body {
    padding: 1.5rem 1.5rem;
    page-break-inside: avoid;
  }

  .customer-info {
    page-break-after: avoid;
    page-break-inside: avoid;
  }

  .reservation-details {
    page-break-after: avoid;
    page-break-inside: avoid;
  }

  .charges-section {
    page-break-inside: avoid;
  }

  .charges-table {
    page-break-inside: avoid;
  }

  .charges-table thead {
    page-break-after: avoid;
  }

  .charges-table tbody tr {
    page-break-inside: avoid;
  }

  .summary-and-notes {
    page-break-inside: avoid;
    page-break-before: avoid;
  }

  .financial-summary {
    page-break-inside: avoid;
  }

  .invoice-footer {
    page-break-before: avoid;
    page-break-inside: avoid;
    padding: 1rem 1.5rem;
  }

  .invoice-actions {
    display: none !important;
  }

  /* Ensure colors are preserved */
  .invoice-header,
  .header-overlay,
  .invoice-footer {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  /* Compact spacing for print */
  .invoice-body {
    font-size: 0.9rem;
  }

  .customer-info {
    margin-bottom: 1.5rem;
    clear: both;
    page-break-before: avoid;
  }

  .invoice-body {
    clear: both;
  }

  .reservation-details {
    margin-bottom: 1.5rem;
    padding: 1rem;
    display: table !important;
    width: 100% !important;
    table-layout: fixed !important;
    page-break-inside: avoid;
  }

  .details-column {
    display: table-cell !important;
    width: 47% !important;
    vertical-align: top !important;
    padding: 0.5rem !important;
  }

  .details-column ul {
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  .details-column li {
    margin-bottom: 0.5rem !important;
    font-size: 0.85rem !important;
    line-height: 1.6 !important;
  }

  .details-column li::before {
    content: none !important;
  }

  .details-separator {
    display: table-cell !important;
    width: 6% !important;
    vertical-align: middle !important;
    text-align: center !important;
    position: relative !important;
  }

  .details-separator::before {
    content: '' !important;
    position: absolute !important;
    left: 50% !important;
    top: 10% !important;
    bottom: 10% !important;
    width: 2px !important;
    background: #ddd !important;
    transform: translateX(-50%) !important;
  }

  .charges-table th,
  .charges-table td {
    padding: 0.6rem 0.5rem;
    font-size: 0.85rem;
  }

  .summary-and-notes {
    margin-top: 1rem;
  }

  .financial-summary .summary-row {
    padding: 0.5rem 0;
  }
}

/* Specific adjustments for html2pdf generation */
@page {
  size: A4;
  margin: 0;
}


/* ======================================== */
/* Common Admin Inline Styles */
/* Extracted from admin HTML files */
/* ======================================== */


/* ======================================== */
/* Admin Login Styles */
/* ======================================== */

.admin-login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
    padding: 20px;
}

.login-box {
    background: white;
    padding: 3rem;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    width: 100%;
}

.login-box h1 {
    text-align: center;
    color: var(--color-primary);
    margin-bottom: 0.5rem;
    font-size: 2rem;
}

.login-box p {
    text-align: center;
    color: #666;
    margin-bottom: 2rem;
}

.login-form .form-group {
    margin-bottom: 1.5rem;
}

.login-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--color-dark);
}

.login-form input {
    width: 100%;
    padding: 0.9rem;
    border: 2px solid var(--color-border);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.login-form input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 10px rgba(139, 0, 0, 0.1);
}

.login-btn {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(139, 0, 0, 0.3);
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    display: none;
    text-align: center;
}

.back-link {
    text-align: center;
    margin-top: 1.5rem;
}

.back-link a {
    color: var(--color-primary);
    text-decoration: none;
    font-weight: 500;
}

.back-link a:hover {
    text-decoration: underline;
}

/* ======================================== */
/* Admin Dashboard Styles */
/* ======================================== */

body.admin-page {
    background-color: #f5f5f5;
}

.admin-header {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
    color: white;
    padding: 1.5rem 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.admin-header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.admin-header h1 {
    color: white;
    margin: 0;
    font-size: 1.8rem;
}

.logout-btn {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 0.6rem 1.5rem;
    border: 2px solid white;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    background: white;
    color: var(--color-primary);
}

.dashboard-container {
    padding: 3rem 0;
}

.dashboard-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.dashboard-card h2 {
    color: var(--color-primary);
    margin-bottom: 1.5rem;
    text-align: left;
}

.dashboard-card h2::after {
    display: none;
}

.price-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.room-price-card {
    border: 2px solid var(--color-border);
    padding: 1.5rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.room-price-card:hover {
    border-color: var(--color-primary);
    box-shadow: 0 4px 15px rgba(139, 0, 0, 0.1);
}

.room-price-card h3 {
    color: var(--color-primary);
    margin-bottom: 1rem;
    font-size: 1.3rem;
}

.price-input-group {
    margin-bottom: 1rem;
}

.price-input-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--color-dark);
    font-size: 0.9rem;
}

.price-input-group input {
    width: 100%;
    padding: 0.8rem;
    border: 2px solid var(--color-border);
    border-radius: 8px;
    font-size: 1rem;
}

.price-input-group input:focus {
    outline: none;
    border-color: var(--color-primary);
}

.current-price {
    background: var(--color-cream);
    padding: 0.8rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    text-align: center;
}

.current-price strong {
    color: var(--color-primary);
    font-size: 1.5rem;
}

.save-btn {
    width: 100%;
    padding: 0.8rem;
    background: var(--color-primary);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.save-btn:hover {
    background: var(--color-secondary);
    transform: translateY(-2px);
}

.save-all-btn {
    background: linear-gradient(135deg, #2d5f3f 0%, #1a4028 100%);
    color: white;
    padding: 1rem 2rem;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 1rem;
}

.save-all-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(45, 95, 63, 0.3);
}

.success-message {
    background: #d4edda;
    color: #155724;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    display: none;
    text-align: center;
}

.info-box {
    background: rgba(139, 0, 0, 0.1);
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid var(--color-primary);
    margin-bottom: 2rem;
}

.info-box p {
    margin: 0;
    color: var(--color-dark);
}

.admin-tools-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.admin-tool-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-radius: 10px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    text-align: center;
    cursor: pointer;
}

.admin-tool-card:hover {
    border-color: var(--color-primary);
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(139, 0, 0, 0.15);
}

.admin-tool-card h3 {
    color: var(--color-primary);
    margin-bottom: 0.5rem;
    font-size: 1.2rem;
}

.admin-tool-card p {
    color: #666;
    font-size: 0.9rem;
    margin: 0;
}

.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

/* ======================================== */
/* Admin Responsive Styles */
/* ======================================== */

@media (max-width: 768px) {
    .admin-header h1 {
        font-size: 1.3rem;
    }
    
    .logout-btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .price-grid {
        grid-template-columns: 1fr;
    }
    
    .admin-tools-grid {
        grid-template-columns: 1fr;
    }
    
    .login-box {
        padding: 2rem;
    }
}