@extends('layouts.home_page.master')

@section('title', 'Terms & Conditions - UniGate')

@section('css')
<style>
    .terms-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 0;
    }
    
    .hero-section {
        background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%);
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
    
    .hero-section .last-updated {
        font-size: 14px;
        opacity: 0.9;
        margin-top: 10px;
    }
    
    .content-wrapper {
        max-width: 1000px;
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
        background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        color: white;
        font-size: 24px;
        flex-shrink: 0;
    }
    
    .section-card h2 {
        color: rgba(70, 90, 40, 1);
        font-size: 28px;
        font-weight: 600;
        margin: 0;
        flex: 1;
    }
    
    .section-card h3 {
        color: #333;
        font-size: 22px;
        font-weight: 600;
        margin-top: 25px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    
    .section-card h3::before {
        content: '';
        width: 4px;
        height: 22px;
        background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%);
        margin-right: 12px;
        border-radius: 2px;
    }
    
    .section-card p {
        color: #555;
        line-height: 1.8;
        margin-bottom: 15px;
        font-size: 16px;
    }
    
    .section-card ul, .section-card ol {
        margin-left: 25px;
        margin-bottom: 20px;
    }
    
    .section-card li {
        color: #555;
        line-height: 1.8;
        margin-bottom: 12px;
        font-size: 16px;
        position: relative;
        padding-left: 10px;
    }
    
    .section-card li::marker {
        color: rgba(70, 90, 40, 1);
    }
    
    .section-card strong {
        color: rgba(70, 90, 40, 1);
        font-weight: 600;
    }
    
    .info-box {
        background: linear-gradient(135deg, rgba(70, 90, 40, 0.09) 0%, #f3e5f5 100%);
        border-left: 4px solid rgba(70, 90, 40, 1);
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .info-box strong {
        color: rgba(70, 90, 40, 1);
        display: block;
        margin-bottom: 10px;
        font-size: 18px;
    }
    
    .highlight-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .warning-box {
        background: #ffe6e6;
        border-left: 4px solid #dc3545;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .success-box {
        background: #e6f7e6;
        border-left: 4px solid #28a745;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .contact-card {
        background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%);
        color: white;
        border-radius: 16px;
        padding: 40px;
        margin-top: 40px;
        text-align: center;
    }
    
    .contact-card h2 {
        color: white;
        margin-bottom: 25px;
    }
    
    .contact-card ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .contact-card li {
        margin-bottom: 15px;
        font-size: 18px;
    }
    
    .contact-card strong {
        color: white;
        display: inline-block;
        margin-right: 10px;
    }
    
    .footer-note {
        background: #f8f9fa;
        border-top: 3px solid rgba(70, 90, 40, 1);
        padding: 30px;
        border-radius: 12px;
        margin-top: 40px;
        text-align: center;
        color: #666;
        font-size: 15px;
    }
    
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin: 25px 0;
    }
    
    .feature-item {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid rgba(70, 90, 40, 1);
        transition: transform 0.3s ease;
    }
    
    .feature-item:hover {
        transform: translateX(5px);
    }
    
    .feature-item i {
        color: rgba(70, 90, 40, 1);
        font-size: 24px;
        margin-bottom: 10px;
    }
    
    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 32px;
        }
        
        .hero-section .subtitle {
            font-size: 16px;
        }
        
        .section-card {
            padding: 25px;
        }
        
        .section-card h2 {
            font-size: 24px;
        }
        
        .section-header {
            flex-direction: column;
            text-align: center;
        }
        
        .section-icon {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .feature-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="terms-page">
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <h1><i class="fas fa-file-contract" style="margin-right: 15px;"></i>Terms & Conditions</h1>
            <p class="subtitle">Understanding Your Rights and Responsibilities</p>
            <p class="last-updated"><i class="fas fa-calendar-alt" style="margin-right: 8px;"></i>Last Updated: January 20, 2026</p>
        </div>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Introduction Section -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h2>Introduction</h2>
            </div>
            <p>
                Welcome to UniGate. These Terms and Conditions ("Terms") govern your access to and use of the UniGate mobile application and services ("Service"). By accessing or using UniGate, you agree to be bound by these Terms. If you do not agree to these Terms, please do not use our Service.
            </p>
        </div>

        <!-- Acceptance of Terms -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>1. Acceptance of Terms</h2>
            </div>
            <div class="info-box">
                <p style="margin: 0;"><i class="fas fa-info-circle" style="color: rgba(70, 90, 40, 1); margin-right: 10px;"></i>By creating an account, accessing, or using UniGate, you acknowledge that you have read, understood, and agree to be bound by these Terms and our Privacy Policy. You must be at least 18 years old or have parental consent to use this Service.</p>
            </div>
        </div>

        <!-- Description of Service -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h2>2. Description of Service</h2>
            </div>
            <p>UniGate is a platform that facilitates university application processes. Our Service includes:</p>
            <div class="feature-grid">
                <div class="feature-item">
                    <i class="fas fa-search"></i>
                    <strong>University Search</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Search and discover universities and programs</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-file-upload"></i>
                    <strong>Application Submission</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Submit online applications to universities</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-folder-open"></i>
                    <strong>Document Management</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Upload and manage application documents</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <strong>Status Tracking</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Track your application status</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-credit-card"></i>
                    <strong>Payment Processing</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Secure payment for application packages</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-bell"></i>
                    <strong>Notifications</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Receive updates about your applications</p>
                </div>
            </div>
            <div class="warning-box" style="margin-top: 25px;">
                <p style="margin: 0;"><i class="fas fa-exclamation-triangle" style="color: #dc3545; margin-right: 10px;"></i><strong>Important:</strong> UniGate acts as an intermediary platform. We do not guarantee admission to any university or make admission decisions.</p>
            </div>
        </div>

        <!-- User Accounts -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h2>3. User Accounts and Registration</h2>
            </div>
            
            <h3>3.1 Account Creation</h3>
            <ul>
                <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>You must provide accurate, current, and complete information during registration</li>
                <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>You are responsible for maintaining the confidentiality of your account credentials</li>
                <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>You are responsible for all activities that occur under your account</li>
                <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>You must notify us immediately of any unauthorized use of your account</li>
                <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>One person or entity may maintain only one account</li>
            </ul>

            <h3>3.2 Account Responsibilities</h3>
            <ul>
                <li><i class="fas fa-id-card" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>You must provide valid identification documents when required</li>
                <li><i class="fas fa-address-book" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>You are responsible for keeping your contact information up to date</li>
                <li><i class="fas fa-balance-scale" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>You must comply with all applicable laws and regulations</li>
                <li><i class="fas fa-user-lock" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>You must not share your account with others</li>
            </ul>
        </div>

        <!-- Application Process -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h2>4. Application Process</h2>
            </div>
            
            <h3>4.1 Application Submission</h3>
            <ul>
                <li><i class="fas fa-file-alt" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>You are responsible for the accuracy and completeness of all information submitted</li>
                <li><i class="fas fa-certificate" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>You must provide authentic documents and information</li>
                <li><i class="fas fa-exclamation-triangle" style="color: #ffc107; margin-right: 8px;"></i>False or misleading information may result in application rejection or account termination</li>
                <li><i class="fas fa-clock" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Application deadlines are set by universities and must be strictly adhered to</li>
            </ul>

            <h3>4.2 Document Requirements</h3>
            <ul>
                <li><i class="fas fa-upload" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>You must upload required documents in the specified formats</li>
                <li><i class="fas fa-eye" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Documents must be clear, legible, and authentic</li>
                <li><i class="fas fa-redo" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>We reserve the right to request document re-upload if quality is insufficient</li>
                <li><i class="fas fa-ban" style="color: #dc3545; margin-right: 8px;"></i>Forged or fraudulent documents will result in immediate account termination</li>
            </ul>

            <h3>4.3 Application Review</h3>
            <div class="info-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-university" style="color: rgba(70, 90, 40, 1); margin-right: 12px;"></i>Universities are solely responsible for reviewing and making admission decisions</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-hand-paper" style="color: rgba(70, 90, 40, 1); margin-right: 12px;"></i>UniGate does not influence or guarantee admission outcomes</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-hourglass-half" style="color: rgba(70, 90, 40, 1); margin-right: 12px;"></i>Application review times vary by university</li>
                    <li style="padding: 10px 0;"><i class="fas fa-comments" style="color: rgba(70, 90, 40, 1); margin-right: 12px;"></i>We facilitate communication but are not responsible for university decisions</li>
                </ul>
            </div>
        </div>

        <!-- Payments and Fees -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h2>5. Payments and Fees</h2>
            </div>
            
            <h3>5.1 Application Packages</h3>
            <ul>
                <li><i class="fas fa-shopping-cart" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Application packages must be purchased before submitting applications</li>
                <li><i class="fas fa-coins" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Package prices are displayed in PKR (Pakistani Rupees) or as specified</li>
                <li><i class="fas fa-percent" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>All prices are subject to applicable taxes</li>
                <li><i class="fas fa-lock" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Package purchases are non-refundable once payment is processed</li>
            </ul>

            <h3>5.2 Payment Processing</h3>
            <ul>
                <li><i class="fas fa-shield-alt" style="color: #10b981; margin-right: 8px;"></i>Payments are processed through secure third-party payment gateways (JazzCash, etc.)</li>
                <li><i class="fas fa-credit-card" style="color: #10b981; margin-right: 8px;"></i>We do not store your complete payment card information</li>
                <li><i class="fas fa-file-contract" style="color: #10b981; margin-right: 8px;"></i>Payment transactions are subject to the terms of the payment gateway</li>
                <li><i class="fas fa-wallet" style="color: #10b981; margin-right: 8px;"></i>You are responsible for ensuring sufficient funds for transactions</li>
            </ul>

            <h3>5.3 Refunds</h3>
            <div class="highlight-box">
                <p style="margin: 0 0 15px 0;"><strong><i class="fas fa-info-circle" style="margin-right: 10px;"></i>Refund Policy:</strong></p>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Application package fees are generally non-refundable</li>
                    <li>Refunds may be considered only in exceptional circumstances (technical errors, duplicate payments)</li>
                    <li>Refund requests must be submitted within 7 days of payment</li>
                    <li>Refunds, if approved, will be processed within 14-21 business days</li>
                    <li>Refunds will be issued to the original payment method</li>
                </ul>
            </div>
        </div>

        <!-- User Conduct -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2>6. User Conduct and Prohibited Activities</h2>
            </div>
            <p>You agree NOT to:</p>
            <div class="warning-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);"><i class="fas fa-times-circle" style="color: #dc3545; margin-right: 10px;"></i>Submit false, inaccurate, or misleading information</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);"><i class="fas fa-times-circle" style="color: #dc3545; margin-right: 10px;"></i>Upload forged, fraudulent, or unauthorized documents</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);"><i class="fas fa-times-circle" style="color: #dc3545; margin-right: 10px;"></i>Impersonate another person or entity</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);"><i class="fas fa-times-circle" style="color: #dc3545; margin-right: 10px;"></i>Attempt to gain unauthorized access to the Service</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);"><i class="fas fa-times-circle" style="color: #dc3545; margin-right: 10px;"></i>Use the Service for any illegal or unauthorized purpose</li>
                    <li style="padding: 10px 0;"><i class="fas fa-times-circle" style="color: #dc3545; margin-right: 10px;"></i>Violate any applicable laws or regulations</li>
                </ul>
            </div>
        </div>

        <!-- Intellectual Property -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-copyright"></i>
                </div>
                <h2>7. Intellectual Property</h2>
            </div>
            <ul>
                <li><i class="fas fa-lock" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>All content, features, and functionality of UniGate are owned by us or our licensors</li>
                <li><i class="fas fa-ban" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>You may not copy, reproduce, distribute, or create derivative works without permission</li>
                <li><i class="fas fa-file" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Your submitted content remains your property, but you grant us a license to use it for Service provision</li>
                <li><i class="fas fa-university" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>University logos and content are the property of respective universities</li>
            </ul>
        </div>

        <!-- Privacy and Data Protection -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>8. Privacy and Data Protection</h2>
            </div>
            <p>Your use of UniGate is also governed by our Privacy Policy. By using the Service, you consent to:</p>
            <div class="success-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(40, 167, 69, 0.2);"><i class="fas fa-check-circle" style="color: #28a745; margin-right: 10px;"></i>Collection and processing of your personal information as described in the Privacy Policy</li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(40, 167, 69, 0.2);"><i class="fas fa-check-circle" style="color: #28a745; margin-right: 10px;"></i>Sharing of application data with universities you apply to</li>
                    <li style="padding: 10px 0;"><i class="fas fa-check-circle" style="color: #28a745; margin-right: 10px;"></i>Use of your information for Service provision and improvement</li>
                </ul>
            </div>
        </div>

        <!-- Disclaimers -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2>9. Disclaimers and Limitations of Liability</h2>
            </div>
            
            <h3>9.1 Service Availability</h3>
            <ul>
                <li>We strive to maintain Service availability but do not guarantee uninterrupted access</li>
                <li>The Service may be unavailable due to maintenance, updates, or technical issues</li>
                <li>We are not liable for any losses due to Service unavailability</li>
            </ul>

            <h3>9.2 No Admission Guarantee</h3>
            <div class="warning-box">
                <p style="margin: 0;"><i class="fas fa-exclamation-triangle" style="color: #dc3545; margin-right: 10px;"></i><strong>Important:</strong> UniGate does not guarantee admission to any university. Admission decisions are made solely by universities.</p>
            </div>

            <h3>9.3 Limitation of Liability</h3>
            <p>To the maximum extent permitted by law:</p>
            <ul>
                <li>UniGate is provided "as is" without warranties of any kind</li>
                <li>We disclaim all warranties, express or implied</li>
                <li>Our liability is limited to the amount you paid for the Service in the past 12 months</li>
                <li>We are not liable for indirect, incidental, or consequential damages</li>
            </ul>
        </div>

        <!-- Account Termination and Deletion -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-user-times"></i>
                </div>
                <h2>11. Account Termination and Deletion</h2>
            </div>
            
            <h3>11.1 Termination by You (Account Deletion)</h3>
            <p>You have the right to request deletion of your account at any time. To request account deletion:</p>
            <div class="info-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);">
                        <i class="fas fa-envelope" style="color: rgba(70, 90, 40, 1); margin-right: 12px;"></i>
                        Send an email to <strong>privacy@unigate.com</strong> or <strong>support@unigate.com</strong> with "Account Deletion Request"
                    </li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);">
                        <i class="fas fa-mobile-alt" style="color: rgba(70, 90, 40, 1); margin-right: 12px;"></i>
                        Use the account deletion feature in the app settings (if available)
                    </li>
                    <li style="padding: 10px 0;">
                        <i class="fas fa-user-check" style="color: rgba(70, 90, 40, 1); margin-right: 12px;"></i>
                        Provide verification of your identity for security purposes
                    </li>
                </ul>
            </div>

            <h3>11.2 What Happens When You Delete Your Account</h3>
            <div class="warning-box">
                <p style="margin: 0 0 15px 0;"><strong><i class="fas fa-exclamation-triangle" style="margin-right: 10px;"></i>Important Consequences:</strong></p>
                <ul style="margin: 0; padding-left: 20px;">
                    <li><strong>Immediate Loss of Access:</strong> You will immediately lose access to your account and all associated data</li>
                    <li><strong>Application Impact:</strong> Any pending or draft applications may be cancelled or lost. Applications already submitted to universities may continue to be processed by those universities</li>
                    <li><strong>No Refunds:</strong> Account deletion does not entitle you to refunds for previously purchased packages (see Refund Policy in Section 5.3)</li>
                    <li><strong>Irreversible:</strong> Account deletion is permanent and cannot be undone. Deleted data cannot be recovered</li>
                    <li><strong>Re-registration Required:</strong> You will need to create a new account if you wish to use UniGate again in the future</li>
                </ul>
            </div>

            <h3>11.3 Data Retention After Deletion</h3>
            <p>Certain information may be retained after account deletion for legal, regulatory, or business purposes:</p>
            <ul>
                <li><i class="fas fa-file-alt" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i><strong>Submitted Applications:</strong> Applications already submitted to universities may be retained as required by educational institutions</li>
                <li><i class="fas fa-money-bill-wave" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i><strong>Payment Records:</strong> Transaction records may be retained for accounting and legal compliance (typically 7 years)</li>
                <li><i class="fas fa-gavel" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i><strong>Legal Requirements:</strong> Information required to comply with legal obligations or resolve disputes</li>
                <li><i class="fas fa-chart-bar" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i><strong>Anonymized Data:</strong> Aggregated, anonymized data that cannot identify you</li>
            </ul>
            <p style="margin-top: 15px;">For more details, please refer to our <a href="{{ route('app.privacy-policy') }}" style="color: rgba(70, 90, 40, 1); text-decoration: underline;">Privacy Policy</a>, specifically the Account Deletion Policy section.</p>

            <h3>11.4 Processing Time</h3>
            <div class="info-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);">
                        <i class="fas fa-clock" style="color: rgba(70, 90, 40, 1); margin-right: 12px;"></i>
                        <strong>Acknowledgment:</strong> We will acknowledge your deletion request within 48 hours
                    </li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);">
                        <i class="fas fa-hourglass-half" style="color: rgba(70, 90, 40, 1); margin-right: 12px;"></i>
                        <strong>Completion:</strong> Account deletion will be completed within 30 days of verification
                    </li>
                    <li style="padding: 10px 0;">
                        <i class="fas fa-check-circle" style="color: #10b981; margin-right: 12px;"></i>
                        <strong>Confirmation:</strong> You will receive email confirmation once deletion is complete
                    </li>
                </ul>
            </div>

            <h3>11.5 Termination by Us</h3>
            <p>We may suspend or terminate your account if you:</p>
            <div class="warning-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);"><i class="fas fa-ban" style="color: #dc3545; margin-right: 10px;"></i>Violate these Terms</li>
                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);"><i class="fas fa-ban" style="color: #dc3545; margin-right: 10px;"></i>Provide false or fraudulent information</li>
                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);"><i class="fas fa-ban" style="color: #dc3545; margin-right: 10px;"></i>Engage in prohibited activities</li>
                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);"><i class="fas fa-ban" style="color: #dc3545; margin-right: 10px;"></i>Fail to pay required fees</li>
                    <li style="padding: 8px 0;"><i class="fas fa-ban" style="color: #dc3545; margin-right: 10px;"></i>Violate applicable laws</li>
                </ul>
            </div>
            <p style="margin-top: 15px;"><i class="fas fa-info-circle" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Termination by us may result in immediate loss of access to your applications and data. We will notify you of the reason for termination when possible.</p>

            <h3>11.6 Exceptions to Deletion</h3>
            <p>We may delay or deny account deletion requests in certain circumstances:</p>
            <ul>
                <li><i class="fas fa-gavel" style="color: #ffc107; margin-right: 8px;"></i>Ongoing legal disputes or investigations</li>
                <li><i class="fas fa-shield-alt" style="color: #ffc107; margin-right: 8px;"></i>Fraud prevention or security concerns</li>
                <li><i class="fas fa-file-invoice-dollar" style="color: #ffc107; margin-right: 8px;"></i>Outstanding payment obligations</li>
                <li><i class="fas fa-balance-scale" style="color: #ffc107; margin-right: 8px;"></i>Legal or regulatory requirements</li>
            </ul>
            <p style="margin-top: 15px;">In such cases, we will inform you of the reason and provide an estimated timeline for when deletion can proceed.</p>
        </div>

        <!-- Modifications to Terms -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <h2>12. Modifications to Terms</h2>
            </div>
            <p>We reserve the right to modify these Terms at any time. We will notify you of material changes by:</p>
            <ul>
                <li><i class="fas fa-bullhorn" style="color: #667eea; margin-right: 8px;"></i>Posting updated Terms in the app</li>
                <li><i class="fas fa-bell" style="color: #667eea; margin-right: 8px;"></i>Sending you a notification</li>
                <li><i class="fas fa-calendar-check" style="color: #667eea; margin-right: 8px;"></i>Updating the "Last Updated" date</li>
            </ul>
            <p style="margin-top: 15px;">Your continued use of UniGate after changes constitutes acceptance of the modified Terms.</p>
        </div>

        <!-- Contact Information -->
        <div class="contact-card">
            <h2><i class="fas fa-envelope" style="margin-right: 15px;"></i>Contact Information</h2>
            <p style="font-size: 18px; margin-bottom: 30px; opacity: 0.95;">For questions, concerns, or support regarding these Terms, please contact us:</p>
            <ul>
                <li><i class="fas fa-headset" style="margin-right: 12px;"></i><strong>Support:</strong> support@unigate.com</li>
                <li><i class="fas fa-gavel" style="margin-right: 12px;"></i><strong>Legal:</strong> legal@unigate.com</li>
                <li><i class="fas fa-map-marker-alt" style="margin-right: 12px;"></i><strong>Address:</strong> [Your Company Address]</li>
            </ul>
        </div>

        <!-- Footer Note -->
        <div class="footer-note">
            <i class="fas fa-check-circle" style="color: #10b981; font-size: 24px; margin-bottom: 15px;"></i>
            <p style="margin: 0; font-size: 16px;">
                By using UniGate, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. If you do not agree to these Terms, please discontinue use of the Service immediately.
            </p>
        </div>
    </div>
</div>
@endsection
