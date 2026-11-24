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
    .about-section {
        padding: 80px 20px;
        background: white;
    }
    .about-content {
        max-width: 1200px;
        margin: 0 auto;
    }
    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
        margin-bottom: 60px;
    }
    .about-text h2 {
        font-size: 36px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
    }
    .about-text p {
        font-size: 18px;
        color: #666;
        line-height: 1.8;
        margin-bottom: 20px;
    }
    .about-image {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .about-image img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }
    .features-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }
    .feature-card {
        background: linear-gradient(135deg, rgba(70, 90, 40, 0.09) 0%, rgba(70, 90, 40, 0.16) 100%);
        padding: 30px;
        border-radius: 16px;
        text-align: center;
    }
    .feature-card i {
        font-size: 48px;
        color: rgba(70, 90, 40, 1);
        margin-bottom: 20px;
    }
    .feature-card h3 {
        font-size: 22px;
        font-weight: 600;
        color: rgba(70, 90, 40, 1);
        margin-bottom: 15px;
    }
    .feature-card p {
        font-size: 16px;
        color: #5c6c7d;
        line-height: 1.6;
    }
    @media (max-width: 768px) {
        .about-grid {
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
            <h1>About UniGate</h1>
            <p>Your Gateway to Global Education</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="about-content">
            <div class="about-grid">
                <div class="about-text">
                    <h2>Who We Are</h2>
                    <p>{{ $settings['about_us'] ?? 'UniGate is a comprehensive platform designed to simplify the study abroad application process. We connect students with universities worldwide, making it easier than ever to pursue your educational dreams.' }}</p>
                    <p>Our mission is to democratize access to quality education by removing barriers and streamlining the application process. We believe every student deserves the opportunity to study at their dream university, regardless of their location or background.</p>
                </div>
                <div class="about-image">
                    <img src="https://via.placeholder.com/600x400?text=UniGate+Team" alt="About UniGate">
                </div>
            </div>

            <!-- Features List -->
            <div>
                <h2 style="text-align: center; font-size: 36px; font-weight: 700; color: #2c3e50; margin-bottom: 50px;">Why Choose UniGate</h2>
                <div class="features-list">
                    @foreach($about_us_lists ?? [] as $point)
                    <div class="feature-card">
                        <i class="fas fa-check-circle"></i>
                        <h3>{{ trim($point) }}</h3>
                        <p>Experience the best in study abroad services with our comprehensive platform.</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>

{{-- @include('layouts.home_page.footer') --}}

@endsection
