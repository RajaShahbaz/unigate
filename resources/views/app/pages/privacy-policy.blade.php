@extends('layouts.home_page.master')

@section('title', 'Privacy Policy - UniGate')

@section('css')
<style>
    .privacy-policy-page {
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
    }
</style>
@endsection

@section('content')
<div class="privacy-policy-page">
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <h1><i class="fas fa-shield-alt" style="margin-right: 15px;"></i>Privacy Policy</h1>
            <p class="subtitle">Your Privacy is Our Priority</p>
            <p class="last-updated"><i class="fas fa-calendar-alt" style="margin-right: 8px;"></i>Last Updated: January 20, 2026</p>
        </div>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Introduction Section -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h2>Introduction</h2>
            </div>
            <p>
                Welcome to UniGate. We are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our mobile application and services.
            </p>
        </div>

        <!-- Information We Collect -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-database"></i>
                </div>
                <h2>1. Information We Collect</h2>
            </div>
            
            <h3>1.1 Personal Information</h3>
            <p>When you register and use UniGate, we may collect the following personal information:</p>
            <ul>
                <li><strong><i class="fas fa-user" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Account Information:</strong> Name, email address, phone number, date of birth, gender, nationality</li>
                <li><strong><i class="fas fa-graduation-cap" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Educational Information:</strong> Academic background, previous degrees, GPA, graduation year, transcripts, certificates</li>
                <li><strong><i class="fas fa-file-alt" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Application Data:</strong> University preferences, program selections, application documents, personal statements</li>
                <li><strong><i class="fas fa-id-card" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Identity Documents:</strong> CNIC, passport, or other identification documents uploaded for verification</li>
                <li><strong><i class="fas fa-credit-card" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Payment Information:</strong> Transaction details, payment method (processed securely through JazzCash and other payment gateways)</li>
                <li><strong><i class="fas fa-address-book" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Contact Information:</strong> Emergency contact details, current and permanent addresses</li>
            </ul>

            <h3>1.2 Automatically Collected Information</h3>
            <ul>
                <li><i class="fas fa-mobile-alt" style="color: #667eea; margin-right: 8px;"></i>Device information (device type, operating system, unique device identifiers)</li>
                <li><i class="fas fa-chart-line" style="color: #667eea; margin-right: 8px;"></i>Usage data (app interactions, features used, time spent)</li>
                <li><i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 8px;"></i>Location data (if you grant permission)</li>
                <li><i class="fas fa-server" style="color: #667eea; margin-right: 8px;"></i>Log data (IP address, access times, error logs)</li>
            </ul>
        </div>

        <!-- How We Use Your Information -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <h2>2. How We Use Your Information</h2>
            </div>
            <p>We use the collected information for the following purposes:</p>
            <div class="info-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-university" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i><strong>Application Processing:</strong> To process and manage your university applications</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-user-cog" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i><strong>Account Management:</strong> To create and manage your account, verify your identity</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-money-bill-wave" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i><strong>Payment Processing:</strong> To process payments for application packages and fees</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-bell" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i><strong>Communication:</strong> To send you notifications about application status, updates, and important information</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-chart-bar" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i><strong>Service Improvement:</strong> To analyze usage patterns and improve our services</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-balance-scale" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i><strong>Compliance:</strong> To comply with legal obligations and protect our rights</li>
                    <li style="padding: 12px 0;"><i class="fas fa-headset" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i><strong>Support:</strong> To provide customer support and respond to your inquiries</li>
                </ul>
            </div>
        </div>

        <!-- Information Sharing -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-share-alt"></i>
                </div>
                <h2>3. Information Sharing and Disclosure</h2>
            </div>
            <p>We may share your information in the following circumstances:</p>
            <ul>
                <li><strong><i class="fas fa-university" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>With Universities:</strong> Your application data and documents are shared with the universities you apply to, as necessary for the application process</li>
                <li><strong><i class="fas fa-wallet" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Payment Processors:</strong> We share payment information with JazzCash and other payment gateways to process transactions securely</li>
                <li><strong><i class="fas fa-server" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Service Providers:</strong> We may share data with third-party service providers who assist in operating our platform (hosting, analytics, customer support)</li>
                <li><strong><i class="fas fa-gavel" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Legal Requirements:</strong> We may disclose information if required by law, court order, or government regulation</li>
                <li><strong><i class="fas fa-exchange-alt" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>Business Transfers:</strong> In case of merger, acquisition, or sale of assets, your information may be transferred</li>
                <li><strong><i class="fas fa-check-circle" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>With Your Consent:</strong> We may share information with your explicit consent</li>
            </ul>
            <div class="highlight-box">
                <p style="margin: 0; font-size: 18px;"><strong><i class="fas fa-ban" style="margin-right: 10px;"></i>We do not sell your personal information to third parties.</strong></p>
            </div>
        </div>

        <!-- Data Security -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h2>4. Data Security</h2>
            </div>
            <p>We implement appropriate technical and organizational security measures to protect your personal information:</p>
            <div class="info-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-key" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i>Encryption of sensitive data in transit and at rest</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-user-shield" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i>Secure authentication and access controls</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-shield-alt" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i>Regular security audits and updates</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);"><i class="fas fa-credit-card" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i>Secure payment processing through certified payment gateways</li>
                    <li style="padding: 12px 0;"><i class="fas fa-user-lock" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i>Limited access to personal information on a need-to-know basis</li>
                </ul>
            </div>
            <p style="margin-top: 20px; color: #666; font-style: italic;"><i class="fas fa-info-circle" style="margin-right: 8px;"></i>However, no method of transmission over the internet is 100% secure. While we strive to protect your data, we cannot guarantee absolute security.</p>
        </div>

        <!-- Your Rights -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <h2>5. Your Rights and Choices</h2>
            </div>
            <p>You have the following rights regarding your personal information:</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 25px;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid rgba(70, 90, 40, 1);">
                    <i class="fas fa-eye" style="color: rgba(70, 90, 40, 1); font-size: 24px; margin-bottom: 10px;"></i>
                    <strong>Access</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Request access to your personal data</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid rgba(70, 90, 40, 1);">
                    <i class="fas fa-edit" style="color: rgba(70, 90, 40, 1); font-size: 24px; margin-bottom: 10px;"></i>
                    <strong>Correction</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Update or correct inaccurate information</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid rgba(70, 90, 40, 1);">
                    <i class="fas fa-trash-alt" style="color: rgba(70, 90, 40, 1); font-size: 24px; margin-bottom: 10px;"></i>
                    <strong>Deletion</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Request deletion of your account and data</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid rgba(70, 90, 40, 1);">
                    <i class="fas fa-download" style="color: rgba(70, 90, 40, 1); font-size: 24px; margin-bottom: 10px;"></i>
                    <strong>Data Portability</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Request a copy of your data in a portable format</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid rgba(70, 90, 40, 1);">
                    <i class="fas fa-bell-slash" style="color: rgba(70, 90, 40, 1); font-size: 24px; margin-bottom: 10px;"></i>
                    <strong>Opt-out</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Unsubscribe from marketing communications</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid rgba(70, 90, 40, 1);">
                    <i class="fas fa-hand-paper" style="color: rgba(70, 90, 40, 1); font-size: 24px; margin-bottom: 10px;"></i>
                    <strong>Withdraw Consent</strong>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">Withdraw consent for data processing</p>
                </div>
            </div>
            <p style="margin-top: 25px;"><i class="fas fa-envelope" style="color: rgba(70, 90, 40, 1); margin-right: 8px;"></i>To exercise these rights, please contact us at the information provided below.</p>
        </div>

        <!-- Account Deletion Policy -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-user-times"></i>
                </div>
                <h2>6. Account Deletion Policy</h2>
            </div>
            
            <h3>How to Request Account Deletion</h3>
            <p>You have the right to request deletion of your UniGate account and associated personal data at any time. To request account deletion, you can:</p>
            <div class="info-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);">
                        <i class="fas fa-envelope" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i>
                        <strong>Email Request:</strong> Send an email to <strong>privacy@unigate.com</strong> with the subject line "Account Deletion Request"
                    </li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);">
                        <i class="fas fa-headset" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i>
                        <strong>Support Request:</strong> Contact our support team at <strong>support@unigate.com</strong>
                    </li>
                    <li style="padding: 12px 0;">
                        <i class="fas fa-mobile-alt" style="color: rgba(70, 90, 40, 1); margin-right: 12px; width: 20px;"></i>
                        <strong>In-App Request:</strong> Use the account deletion feature in the app settings (if available)
                    </li>
                </ul>
            </div>

            <h3>Information Required for Deletion Request</h3>
            <p>To process your deletion request, we may require:</p>
            <ul>
                <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>Your registered email address</li>
                <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>Your account username or user ID</li>
                <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>Verification of your identity (for security purposes)</li>
                <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>Confirmation that you understand the consequences of account deletion</li>
            </ul>

            <h3>What Gets Deleted</h3>
            <p>Upon successful account deletion, we will permanently delete or anonymize the following information:</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin: 20px 0;">
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 3px solid rgba(70, 90, 40, 1);">
                    <i class="fas fa-user" style="color: rgba(70, 90, 40, 1); margin-bottom: 8px;"></i>
                    <strong>Account Information</strong>
                    <p style="margin: 8px 0 0; font-size: 14px; color: #666;">Profile data, preferences, settings</p>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 3px solid rgba(70, 90, 40, 1);">
                    <i class="fas fa-file-alt" style="color: rgba(70, 90, 40, 1); margin-bottom: 8px;"></i>
                    <strong>Application Data</strong>
                    <p style="margin: 8px 0 0; font-size: 14px; color: #666;">Draft applications, saved preferences</p>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 3px solid rgba(70, 90, 40, 1);">
                    <i class="fas fa-comments" style="color: rgba(70, 90, 40, 1); margin-bottom: 8px;"></i>
                    <strong>Communication Records</strong>
                    <p style="margin: 8px 0 0; font-size: 14px; color: #666;">Messages, support tickets</p>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 3px solid rgba(70, 90, 40, 1);">
                    <i class="fas fa-bell" style="color: rgba(70, 90, 40, 1); margin-bottom: 8px;"></i>
                    <strong>Notification Preferences</strong>
                    <p style="margin: 8px 0 0; font-size: 14px; color: #666;">All notification settings</p>
                </div>
            </div>

            <h3>What May Be Retained</h3>
            <div class="warning-box">
                <p style="margin: 0 0 15px 0;"><strong><i class="fas fa-exclamation-triangle" style="margin-right: 10px;"></i>Important:</strong> Certain information may be retained for legal, regulatory, or business purposes:</p>
                <ul style="margin: 0; padding-left: 20px;">
                    <li><strong>Submitted Applications:</strong> Applications already submitted to universities may be retained as required by educational institutions and regulatory requirements. These applications are shared with universities and may be subject to their retention policies.</li>
                    <li><strong>Payment Records:</strong> Transaction records and payment history may be retained for accounting, tax, and legal compliance purposes (typically 7 years as required by law).</li>
                    <li><strong>Legal Obligations:</strong> Information required to comply with legal obligations, resolve disputes, or enforce agreements may be retained.</li>
                    <li><strong>Anonymized Data:</strong> Aggregated, anonymized data that cannot identify you may be retained for analytics and service improvement purposes.</li>
                    <li><strong>Security Logs:</strong> Security-related logs and records may be retained for fraud prevention and security purposes.</li>
                </ul>
            </div>

            <h3>Processing Time</h3>
            <div class="info-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);">
                        <i class="fas fa-clock" style="color: rgba(70, 90, 40, 1); margin-right: 12px;"></i>
                        <strong>Initial Response:</strong> We will acknowledge your deletion request within 48 hours
                    </li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(70, 90, 40, 0.1);">
                        <i class="fas fa-hourglass-half" style="color: rgba(70, 90, 40, 1); margin-right: 12px;"></i>
                        <strong>Processing Time:</strong> Account deletion will be completed within 30 days of verification
                    </li>
                    <li style="padding: 10px 0;">
                        <i class="fas fa-check-circle" style="color: #10b981; margin-right: 12px;"></i>
                        <strong>Confirmation:</strong> You will receive email confirmation once deletion is complete
                    </li>
                </ul>
            </div>

            <h3>Consequences of Account Deletion</h3>
            <p>Please be aware that account deletion is <strong>irreversible</strong> and will result in:</p>
            <div class="warning-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);">
                        <i class="fas fa-times-circle" style="color: #dc3545; margin-right: 10px;"></i>
                        <strong>Loss of Access:</strong> You will immediately lose access to your account and all associated data
                    </li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);">
                        <i class="fas fa-times-circle" style="color: #dc3545; margin-right: 10px;"></i>
                        <strong>Application Impact:</strong> Any pending or draft applications may be cancelled or lost
                    </li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);">
                        <i class="fas fa-times-circle" style="color: #dc3545; margin-right: 10px;"></i>
                        <strong>No Refunds:</strong> Account deletion does not entitle you to refunds for previously purchased packages
                    </li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(220, 53, 69, 0.2);">
                        <i class="fas fa-times-circle" style="color: #dc3545; margin-right: 10px;"></i>
                        <strong>Data Recovery:</strong> Deleted data cannot be recovered or restored
                    </li>
                    <li style="padding: 10px 0;">
                        <i class="fas fa-times-circle" style="color: #dc3545; margin-right: 10px;"></i>
                        <strong>Re-registration:</strong> You will need to create a new account if you wish to use UniGate again in the future
                    </li>
                </ul>
            </div>

            <h3>Exceptions and Restrictions</h3>
            <p>We may delay or deny account deletion requests in the following circumstances:</p>
            <ul>
                <li><i class="fas fa-gavel" style="color: #ffc107; margin-right: 8px;"></i>If there is an ongoing legal dispute or investigation</li>
                <li><i class="fas fa-shield-alt" style="color: #ffc107; margin-right: 8px;"></i>If deletion would compromise fraud prevention or security measures</li>
                <li><i class="fas fa-file-invoice-dollar" style="color: #ffc107; margin-right: 8px;"></i>If there are outstanding payment obligations or disputes</li>
                <li><i class="fas fa-balance-scale" style="color: #ffc107; margin-right: 8px;"></i>If retention is required by law, regulation, or court order</li>
            </ul>
            <p style="margin-top: 15px;">In such cases, we will inform you of the reason for the delay or denial and provide an estimated timeline for when deletion can proceed.</p>

            <h3>Data Backup Before Deletion</h3>
            <div class="info-box">
                <p style="margin: 0;"><i class="fas fa-info-circle" style="color: rgba(70, 90, 40, 1); margin-right: 10px;"></i><strong>Recommendation:</strong> Before requesting account deletion, we recommend downloading or saving any important data, documents, or information you may need in the future. Once your account is deleted, this information cannot be recovered.</p>
            </div>
        </div>

        <!-- Data Retention -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-archive"></i>
                </div>
                <h2>7. Data Retention</h2>
            </div>
            <p>We retain your personal information for as long as necessary to:</p>
            <ul>
                <li><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i>Provide our services to you</li>
                <li><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i>Comply with legal obligations</li>
                <li><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i>Resolve disputes and enforce agreements</li>
                <li><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i>Maintain application records as required by universities</li>
            </ul>
            <p style="margin-top: 15px; color: #666;"><i class="fas fa-info-circle" style="margin-right: 8px;"></i>Application data may be retained for extended periods as required by educational institutions and regulatory requirements.</p>
        </div>

        <!-- Children's Privacy -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-child"></i>
                </div>
                <h2>8. Children's Privacy</h2>
            </div>
            <div class="info-box">
                <p style="margin: 0;"><i class="fas fa-exclamation-triangle" style="color: #ffc107; margin-right: 10px; font-size: 20px;"></i>UniGate is intended for users who are at least 18 years old or have parental consent. We do not knowingly collect personal information from children under 18 without parental consent. If you believe we have collected information from a child, please contact us immediately.</p>
            </div>
        </div>

        <!-- International Data Transfers -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h2>9. International Data Transfers</h2>
            </div>
            <p>Your information may be transferred to and processed in countries other than your country of residence. These countries may have different data protection laws. By using UniGate, you consent to the transfer of your information to these countries.</p>
        </div>

        <!-- Third-Party Links -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-link"></i>
                </div>
                <h2>10. Third-Party Links</h2>
            </div>
            <p>Our app may contain links to third-party websites or services (such as university websites, payment gateways). We are not responsible for the privacy practices of these third parties. We encourage you to review their privacy policies.</p>
        </div>

        <!-- Changes to Policy -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <h2>11. Changes to This Privacy Policy</h2>
            </div>
            <p>We may update this Privacy Policy from time to time. We will notify you of any material changes by:</p>
            <ul>
                <li><i class="fas fa-bullhorn" style="color: #667eea; margin-right: 8px;"></i>Posting the new Privacy Policy in the app</li>
                <li><i class="fas fa-bell" style="color: #667eea; margin-right: 8px;"></i>Sending you a notification</li>
                <li><i class="fas fa-calendar-check" style="color: #667eea; margin-right: 8px;"></i>Updating the "Last Updated" date</li>
            </ul>
            <p style="margin-top: 15px;">Your continued use of UniGate after changes become effective constitutes acceptance of the updated policy.</p>
        </div>

        <!-- Contact Us -->
        <div class="contact-card">
            <h2><i class="fas fa-envelope" style="margin-right: 15px;"></i>Contact Us</h2>
            <p style="font-size: 18px; margin-bottom: 30px; opacity: 0.95;">If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:</p>
            <ul>
                <li><i class="fas fa-envelope" style="margin-right: 12px;"></i><strong>Email:</strong> privacy@unigate.com</li>
                <li><i class="fas fa-headset" style="margin-right: 12px;"></i><strong>Support:</strong> support@unigate.com</li>
                <li><i class="fas fa-map-marker-alt" style="margin-right: 12px;"></i><strong>Address:</strong> [Your Company Address]</li>
            </ul>
        </div>

        <!-- Footer Note -->
        <div class="footer-note">
            <i class="fas fa-check-circle" style="color: #10b981; font-size: 24px; margin-bottom: 15px;"></i>
            <p style="margin: 0; font-size: 16px;">
                By using UniGate, you acknowledge that you have read and understood this Privacy Policy and agree to the collection, use, and disclosure of your information as described herein.
            </p>
        </div>
    </div>
</div>
@endsection
