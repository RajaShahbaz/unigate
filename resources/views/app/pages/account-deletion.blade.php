@extends('layouts.home_page.master')

@section('title', 'Delete Account - UniGate')

@section('css')
<style>
    .account-deletion-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 0;
    }
    
    .hero-section {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 80px 20px 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 20s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .hero-section h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 15px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .hero-section .subtitle {
        font-size: 20px;
        opacity: 0.95;
        margin-bottom: 10px;
    }
    
    .hero-section .warning-icon {
        font-size: 64px;
        margin-bottom: 20px;
        animation: shake 2s infinite;
    }
    
    @keyframes shake {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-5deg); }
        75% { transform: rotate(5deg); }
    }
    
    .content-wrapper {
        max-width: 900px;
        margin: -40px auto 0;
        padding: 0 20px 60px;
        position: relative;
        z-index: 2;
    }
    
    .section-card {
        background: white;
        border-radius: 16px;
        padding: 35px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .section-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    
    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .section-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
        font-size: 24px;
    }
    
    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }
    
    .info-box {
        background: #f8f9fa;
        border-left: 4px solid rgba(70, 90, 40, 1);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .info-box.warning {
        background: #fff3cd;
        border-left-color: #ffc107;
    }
    
    .info-box.danger {
        background: #f8d7da;
        border-left-color: #dc3545;
    }
    
    .info-box.success {
        background: #d4edda;
        border-left-color: #28a745;
    }
    
    .info-box h4 {
        margin-top: 0;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .info-box ul {
        margin-bottom: 0;
        padding-left: 20px;
    }
    
    .info-box li {
        margin-bottom: 8px;
    }
    
    .deletion-form {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }
    
    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: rgba(70, 90, 40, 1);
    }
    
    .form-group .help-text {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }
    
    .confirmation-input {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 20px;
    }
    
    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }
    
    .btn-delete:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: none;
    }
    
    .alert.show {
        display: block;
    }
    
    .alert-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
    
    .alert-danger {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
    
    .alert-warning {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
    }
    
    .login-prompt {
        background: white;
        border-radius: 16px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .btn-login {
        background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
        transition: all 0.3s ease;
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(70, 90, 40, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
        margin-right: 10px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 32px;
        }
        
        .hero-section .subtitle {
            font-size: 16px;
        }
        
        .section-card, .deletion-form {
            padding: 25px;
        }
        
        .content-wrapper {
            padding: 0 15px 40px;
        }
    }
</style>
@endsection

@section('content')
<div class="account-deletion-page">
    <div class="hero-section">
        <div class="hero-content">
            <div class="warning-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1>Delete Your Account</h1>
            <p class="subtitle">We're sorry to see you go</p>
            <p class="last-updated">Request account deletion</p>
        </div>
    </div>

    <div class="content-wrapper">
        <!-- Alert Messages -->
        <div id="alertMessage" class="alert"></div>

        <!-- Login Prompt (if not authenticated) -->
        <div id="loginPrompt" class="login-prompt" style="display: none;">
            <i class="fas fa-lock" style="font-size: 48px; color: rgba(70, 90, 40, 1); margin-bottom: 20px;"></i>
            <h2>Authentication Required</h2>
            <p>You need to be logged in to delete your account. Please log in to continue.</p>
            <a href="/login" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login to Continue
            </a>
        </div>

        <!-- Deletion Form (if authenticated) -->
        <div id="deletionForm" class="deletion-form" style="display: none;">
            <!-- Information Section -->
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h2 class="section-title">What Will Be Deleted</h2>
                </div>
                <div class="info-box">
                    <h4><i class="fas fa-check-circle" style="color: #dc3545;"></i> Account Information</h4>
                    <ul>
                        <li>Your profile data and personal information</li>
                        <li>Account preferences and settings</li>
                        <li>All draft applications</li>
                        <li>Your interests, preferred cities, and favorites</li>
                        <li>Education records</li>
                        <li>All notification preferences</li>
                        <li>All active API tokens</li>
                    </ul>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon" style="background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%);">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h2 class="section-title">What Will Be Retained</h2>
                </div>
                <div class="info-box success">
                    <h4><i class="fas fa-lock" style="color: #28a745;"></i> For Legal & University Records</h4>
                    <ul>
                        <li><strong>Submitted Applications:</strong> Retained for university records and processing</li>
                        <li><strong>Payment Records:</strong> Retained for legal compliance and financial records</li>
                        <li><strong>Anonymized Data:</strong> Aggregated, anonymized data for analytics (no personal identifiers)</li>
                    </ul>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h2 class="section-title">Important Information</h2>
                </div>
                <div class="info-box warning">
                    <h4><i class="fas fa-info-circle"></i> Before You Proceed</h4>
                    <ul>
                        <li>Account deletion is <strong>permanent and cannot be undone</strong></li>
                        <li>You will lose access to all your data immediately</li>
                        <li>This action will log you out of all devices</li>
                        <li>Processing time: Account deletion will be completed within 30 days of verification</li>
                        <li>If you have outstanding payments or active subscriptions, please resolve them first</li>
                    </ul>
                </div>
                <div class="info-box">
                    <h4><i class="fas fa-question-circle"></i> Need Help?</h4>
                    <p>If you have questions or concerns, please contact us:</p>
                    <ul>
                        <li><strong>Email:</strong> privacy@unigate.com</li>
                        <li><strong>Support:</strong> support@unigate.com</li>
                    </ul>
                </div>
            </div>

            <!-- Deletion Form -->
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <h2 class="section-title">Request Account Deletion</h2>
                </div>
                
                <form id="deleteAccountForm">
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-key"></i> Enter Your Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            placeholder="Enter your account password"
                            autocomplete="current-password"
                        >
                        <div class="help-text">We need to verify your identity before deleting your account.</div>
                    </div>

                    <div class="form-group">
                        <label for="confirmation">
                            <i class="fas fa-exclamation-circle"></i> Type "DELETE" to Confirm
                        </label>
                        <input 
                            type="text" 
                            id="confirmation" 
                            name="confirmation" 
                            required 
                            placeholder="Type DELETE in capital letters"
                            class="confirmation-input"
                            autocomplete="off"
                        >
                        <div class="help-text">This confirms that you understand this action is permanent.</div>
                    </div>

                    <div class="info-box danger">
                        <h4><i class="fas fa-ban"></i> Final Warning</h4>
                        <p>By clicking the button below, you acknowledge that:</p>
                        <ul>
                            <li>You understand this action is <strong>irreversible</strong></li>
                            <li>All your account data will be permanently deleted</li>
                            <li>You will lose access to all services immediately</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn-delete" id="deleteButton">
                        <i class="fas fa-trash-alt"></i> Delete My Account Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('deleteAccountForm');
    const loginPrompt = document.getElementById('loginPrompt');
    const deletionForm = document.getElementById('deletionForm');
    const alertMessage = document.getElementById('alertMessage');
    const deleteButton = document.getElementById('deleteButton');
    const confirmationInput = document.getElementById('confirmation');

    // Check if user is authenticated
    checkAuthentication();

    // Real-time confirmation validation
    confirmationInput.addEventListener('input', function() {
        if (this.value.toUpperCase() === 'DELETE') {
            this.style.borderColor = '#28a745';
        } else {
            this.style.borderColor = '#e0e0e0';
        }
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('confirmation').value;

        // Validate confirmation
        if (confirmation.toUpperCase() !== 'DELETE') {
            showAlert('Please type "DELETE" exactly to confirm account deletion.', 'danger');
            return;
        }

        if (!password) {
            showAlert('Please enter your password.', 'danger');
            return;
        }

        // Disable button and show loading
        deleteButton.disabled = true;
        deleteButton.innerHTML = '<span class="spinner"></span> Processing...';

        try {
            // Get auth token from localStorage or session
            const token = localStorage.getItem('auth_token') || getCookie('auth_token');
            
            if (!token) {
                showAlert('You are not authenticated. Please log in first.', 'danger');
                checkAuthentication();
                return;
            }

            const response = await fetch('/api/account', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    password: password,
                    confirmation: confirmation
                })
            });

            const data = await response.json();

            if (response.ok && !data.error) {
                showAlert(data.message || 'Your account has been successfully deleted. We\'re sorry to see you go.', 'success');
                form.reset();
                
                // Redirect after 3 seconds
                setTimeout(() => {
                    window.location.href = '/';
                }, 3000);
            } else {
                let errorMessage = data.message || 'Failed to delete account. Please try again.';
                if (data.data && typeof data.data === 'object') {
                    const errors = Object.values(data.data).flat();
                    errorMessage = errors.join(', ');
                }
                showAlert(errorMessage, 'danger');
                deleteButton.disabled = false;
                deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i> Delete My Account Permanently';
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('An error occurred. Please check your internet connection and try again.', 'danger');
            deleteButton.disabled = false;
            deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i> Delete My Account Permanently';
        }
    });

    function checkAuthentication() {
        // Try to get token from localStorage or cookie
        const token = localStorage.getItem('auth_token') || getCookie('auth_token');
        
        if (token) {
            // Verify token is valid by making a simple API call
            fetch('/api/profile', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    deletionForm.style.display = 'block';
                    loginPrompt.style.display = 'none';
                } else {
                    loginPrompt.style.display = 'block';
                    deletionForm.style.display = 'none';
                }
            })
            .catch(() => {
                loginPrompt.style.display = 'block';
                deletionForm.style.display = 'none';
            });
        } else {
            loginPrompt.style.display = 'block';
            deletionForm.style.display = 'none';
        }
    }

    function showAlert(message, type) {
        alertMessage.textContent = message;
        alertMessage.className = `alert alert-${type} show`;
        alertMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            alertMessage.classList.remove('show');
        }, 5000);
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }
});
</script>
@endsection
