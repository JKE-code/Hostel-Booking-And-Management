/* Admin Panel Styles */

/* Container */
.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 30px;
    border: 1px solid #888;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    line-height: 20px;
}

.close:hover,
.close:focus {
    color: #000;
}

/* Admin Header */
.admin-header {
    background: linear-gradient(135deg, #8b0000 0%, #5b0202 100%);
    color: white;
    padding: 1.5rem 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.admin-header .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

.admin-header h1 {
    margin: 0 0 1rem 0;
    font-size: 1.8rem;
}

.admin-nav {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.admin-nav a {
    color: white;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    transition: background 0.3s;
    font-size: 0.9rem;
}

.admin-nav a:hover {
    background: rgba(255, 255, 255, 0.1);
}

.admin-nav a.active {
    background: rgba(255, 255, 255, 0.2);
    font-weight: 600;
}

.admin-nav .logout-btn {
    background: rgba(255, 255, 255, 0.15);
    margin-left: auto;
}

/* Main Content */
.dashboard-main {
    padding: 2rem 0;
}

.dashboard-main .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Section Card */
.section-card {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.section-card h2 {
    margin: 0 0 1.5rem 0;
    color: #333;
    font-size: 1.5rem;
}

/* Messages */
.success-message {
    background: #d4edda;
    color: #155724;
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1.5rem;
    border: 1px solid #c3e6cb;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1.5rem;
    border: 1px solid #f5c6cb;
}

/* Form Styles */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    align-items: end;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group select {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    font-family: inherit;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #8b0000;
}

/* Buttons */
.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: #8b0000;
    color: white;
}

.btn-primary:hover {
    background: #5b0202;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-warning {
    background: #ffc107;
    color: #333;
}

.btn-warning:hover {
    background: #e0a800;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-small {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
}

/* Table Styles */
.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.data-table thead {
    background: #f8f9fa;
}

.data-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #dee2e6;
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.data-table tbody tr:hover {
    background: #f8f9fa;
}

/* Status Badge */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-buttons form {
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-nav {
        flex-direction: column;
    }
    
    .admin-nav .logout-btn {
        margin-left: 0;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn-small {
        width: 100%;
    }
}


/* ========================================
   ADMIN LOGIN PAGE STYLES
   ======================================== */

.admin-login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #8b0000 0%, #5b0202 100%);
    padding: 2rem;
}

.login-box {
    background: white;
    border-radius: 15px;
    padding: 3rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    width: 100%;
    max-width: 450px;
    text-align: center;
}

.login-box h1 {
    margin: 0 0 0.5rem 0;
    color: #8b0000;
    font-size: 2rem;
}

.login-box > p {
    margin: 0 0 2rem 0;
    color: #666;
    font-size: 1rem;
}

.login-form {
    text-align: left;
    margin-top: 2rem;
}

.login-form .form-group {
    margin-bottom: 1.5rem;
}

.login-form .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

.login-form .form-group input {
    width: 100%;
    padding: 0.875rem;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    font-family: inherit;
    transition: border-color 0.3s;
}

.login-form .form-group input:focus {
    outline: none;
    border-color: #8b0000;
}

.login-btn {
    width: 100%;
    padding: 1rem;
    background: #8b0000;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
    margin-top: 1rem;
}

.login-btn:hover {
    background: #5b0202;
}

.back-link {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
}

.back-link a {
    color: #666;
    text-decoration: none;
    font-size: 0.95rem;
    transition: color 0.3s;
}

.back-link a:hover {
    color: #8b0000;
}

/* Login page error message */
.admin-login-container .error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border: 1px solid #f5c6cb;
    text-align: center;
}

/* Responsive login page */
@media (max-width: 480px) {
    .login-box {
        padding: 2rem 1.5rem;
    }
    
    .login-box h1 {
        font-size: 1.5rem;
    }
}


/* ========================================
   TOGGLE SLIDER STYLES
   ======================================== */

.toggle-container {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.toggle-btn {
    padding: 1rem 2rem;
    border: 2px solid #8b0000;
    background: white;
    color: #8b0000;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s;
}

.toggle-btn:hover {
    background: #f8f9fa;
}

.toggle-btn.active {
    background: #8b0000;
    color: white;
}

/* Responsive toggle */
@media (max-width: 768px) {
    .toggle-container {
        flex-direction: column;
    }
    
    .toggle-btn {
        width: 100%;
    }
}
