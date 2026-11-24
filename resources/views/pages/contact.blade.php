@extends('layouts.home_page.master')

@section('content')
<style>
    .page-hero {
        background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%);
        padding: 100px 20px;
        text-align: center;
        color: white;
    }
    .page-hero h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
    }
    .page-hero p {
        font-size: 20px;
        opacity: 0.9;
    }
    .contact-section {
        padding: 80px 20px;
        background: white;
    }
    .contact-content {
        max-width: 1200px;
        margin: 0 auto;
    }
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
    }
    .contact-info h2 {
        font-size: 36px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 30px;
    }
    .contact-item {
        display: flex;
        align-items: start;
        gap: 20px;
        margin-bottom: 30px;
    }
    .contact-item i {
        font-size: 24px;
        color: rgba(70, 90, 40, 1);
        width: 50px;
        height: 50px;
        background: rgba(70, 90, 40, 0.09);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .contact-item-content h3 {
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    .contact-item-content p {
        font-size: 16px;
        color: #666;
        line-height: 1.6;
    }
    .contact-form {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 20px;
    }
    .contact-form h2 {
        font-size: 36px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 30px;
    }
    .form-group {
        margin-bottom: 25px;
    }
    .form-group label {
        display: block;
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 14px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s;
    }
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: rgba(70, 90, 40, 1);
    }
    .form-group textarea {
        min-height: 150px;
        resize: vertical;
    }
    .submit-btn {
        background: rgba(70, 90, 40, 1);
        color: white;
        padding: 16px 40px;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
    }
    .submit-btn:hover {
        background: #023d6b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(70, 90, 40, 0.3);
    }
    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
        .page-hero h1 {
            font-size: 32px;
        }
    }
</style>

<header class="navbar">
    <div class="container">
        <div class="navbarWrapper">
            <div class="navLogoWrapper">
                <div class="navLogo">
                    <a href="{{ url('/') }}">
                        <img src="{{ $settings['horizontal_logo'] ?? asset('assets/landing_page_images/Logo1.svg') }}" class="logo" alt="">
                    </a>
                </div>
            </div>
            <div class="menuListWrapper">
                <ul class="listItems">
                    <li><a href="{{ url('/home') }}">Home</a></li>
                    <li><a href="{{ url('/features') }}">Features</a></li>
                    <li><a href="{{ url('/about-us') }}">About Us</a></li>
                    <li><a href="{{ url('/pricing') }}">Pricing</a></li>
                    <li><a href="{{ url('/contact-us') }}">Contact</a></li>
                </ul>
            </div>
            <div class="loginBtnsWrapper d-flex align-items-center justify-content-end flex-wrap gap-2">
                @include('partials.public_nav_auth_buttons')
            </div>
        </div>
    </div>
</header>

<div class="main">
    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <h1>Contact Us</h1>
            <p>We're here to help you on your study abroad journey</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="contact-content">
            <div class="contact-grid">
                <!-- Contact Info -->
                <div class="contact-info">
                    <h2>Get in Touch</h2>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="contact-item-content">
                            <h3>Address</h3>
                            <p>Islamabad, Pakistan</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div class="contact-item-content">
                            <h3>Phone</h3>
                            <p>{{ $settings['phone'] ?? '+923005314050' }}</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div class="contact-item-content">
                            <h3>Email</h3>
                            <p>{{ $settings['mail_username'] ?? 'info@unigate.pk' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form">
                    <h2>Send us a Message</h2>
                    @if(session('success'))
                        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form action="{{ url('contact-us') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Your Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- @include('layouts.home_page.footer') --}}

@endsection
