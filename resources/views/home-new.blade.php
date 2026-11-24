@extends('layouts.home_page.master')

@section('title', 'UniGate - Apply to Top Universities in Pakistan – All in One Place')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary-blue: rgba(70, 90, 40, 1);
        --secondary-blue: rgb(90, 125, 37);
        --purple: #764ba2;
        --light-blue: rgba(70, 90, 40, 0.09);
        --light-purple: #f3e5f5;
        --yellow: #ffc107;
        --green: #28a745;
        --red: #dc3545;
        --white: #ffffff;
        --dark: #333333;
        --gray: #666666;
        --light-gray: #f5f5f5;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: var(--dark);
    }

    /* Top Header Bar (Light Blue) */
    .top-header-bar {
        background: var(--light-blue);
        padding: 8px 0;
        border-bottom: 1px solid rgba(70, 90, 40, 0.1);
    }

    .top-header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .top-logo {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-blue);
        text-decoration: none;
    }

    .top-nav-links {
        display: flex;
        gap: 25px;
        list-style: none;
        align-items: center;
    }

    .top-nav-links a {
        color: var(--primary-blue);
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s;
    }

    .top-nav-links a:hover {
        color: var(--secondary-blue);
    }

    .sign-up-btn {
        background: var(--primary-blue);
        color: white;
        padding: 8px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.3s;
    }

    .sign-up-btn:hover {
        background: var(--secondary-blue);
        color: white;
    }

    /* Main Header (White) */
    .main-header {
        background: white;
        padding: 15px 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .main-header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .search-bar {
        flex: 1;
        position: relative;
    }

    .search-bar input {
        width: 100%;
        padding: 12px 50px 12px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 25px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s;
    }

    .search-bar input:focus {
        border-color: var(--primary-blue);
    }

    .search-bar button {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
    }

    .header-btns {
        display: flex;
        gap: 10px;
    }

    .btn-login, .btn-signup {
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-login {
        background: transparent;
        color: var(--primary-blue);
        border: 2px solid var(--primary-blue);
    }

    .btn-login:hover {
        background: var(--primary-blue);
        color: white;
    }

    .btn-signup {
        background: var(--primary-blue);
        color: white;
        border: 2px solid var(--primary-blue);
    }

    .btn-signup:hover {
        background: var(--secondary-blue);
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--light-blue) 0%, var(--light-purple) 100%);
        padding: 80px 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: 'UniGate';
        position: absolute;
        font-size: 200px;
        font-weight: 900;
        color: rgba(70, 90, 40, 0.05);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 0;
    }

    .hero-content {
        max-width: 900px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .hero-title {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--dark);
    }

    .hero-title .highlight {
        background: var(--purple);
        color: white;
        padding: 5px 15px;
        border-radius: 8px;
        display: inline-block;
    }

    .hero-search {
        margin: 40px 0;
    }

    .hero-search input {
        width: 100%;
        padding: 18px 30px;
        border: 2px solid #e0e0e0;
        border-radius: 30px;
        font-size: 16px;
        outline: none;
    }

    .hero-search button {
        margin-top: 15px;
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 25px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .hero-search button:hover {
        background: var(--secondary-blue);
    }

    .feature-icons {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        margin-top: 50px;
    }

    .feature-icon {
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .feature-icon:hover {
        transform: translateY(-5px);
    }

    .feature-icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 30px;
        color: white;
    }

    .feature-icon:nth-child(1) .feature-icon-circle { background: #ff6b6b; }
    .feature-icon:nth-child(2) .feature-icon-circle { background: #4ecdc4; }
    .feature-icon:nth-child(3) .feature-icon-circle { background: #45b7d1; }
    .feature-icon:nth-child(4) .feature-icon-circle { background: #f9ca24; }
    .feature-icon:nth-child(5) .feature-icon-circle { background: #6c5ce7; }
    .feature-icon:nth-child(6) .feature-icon-circle { background: #a29bfe; }
    .feature-icon:nth-child(7) .feature-icon-circle { background: #fd79a8; }
    .feature-icon:nth-child(8) .feature-icon-circle { background: #00b894; }

    .feature-icon-text {
        font-size: 12px;
        color: var(--gray);
        font-weight: 500;
    }

    /* Secondary Navigation Bar */
    .secondary-nav {
        background: var(--primary-blue);
        padding: 15px 0;
        position: sticky;
        top: 0;
        z-index: 999;
    }

    .secondary-nav-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .secondary-logo {
        color: white;
        font-size: 20px;
        font-weight: 700;
        text-decoration: none;
    }

    .secondary-nav-links {
        display: flex;
        gap: 30px;
        list-style: none;
    }

    .secondary-nav-links a {
        color: white;
        text-decoration: none;
        font-size: 14px;
        transition: opacity 0.3s;
    }

    .secondary-nav-links a:hover {
        opacity: 0.8;
    }

    /* 7-Day Free Trial Banner */
    .trial-banner {
        background: var(--light-blue);
        padding: 60px 20px;
        position: relative;
        overflow: hidden;
    }

    .trial-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 200px;
        height: 200px;
        background: white;
        clip-path: polygon(0 0, 100% 0, 0 100%);
        opacity: 0.3;
    }

    .trial-banner-content {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: center;
    }

    .trial-text h2 {
        font-size: 36px;
        color: var(--primary-blue);
        margin-bottom: 15px;
    }

    .trial-text p {
        font-size: 18px;
        color: var(--gray);
        margin-bottom: 25px;
    }

    .trial-btn {
        background: var(--primary-blue);
        color: white;
        padding: 15px 40px;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        transition: background 0.3s;
    }

    .trial-btn:hover {
        background: var(--secondary-blue);
        color: white;
    }

    .trial-illustration {
        text-align: center;
    }

    .trial-illustration img {
        max-width: 100%;
        height: auto;
    }

    /* Section Styles */
    .section {
        padding: 80px 20px;
    }

    .section-title {
        text-align: center;
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 50px;
        color: var(--dark);
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Cards Grid */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--red);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .card-content {
        padding: 20px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }

    .card-btn {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 25px;
        background: var(--primary-blue);
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        transition: background 0.3s;
    }

    .card-btn:hover {
        background: var(--secondary-blue);
        color: white;
    }

    /* Testimonials */
    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .testimonial-card {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        position: relative;
    }

    .testimonial-quote {
        font-size: 16px;
        font-style: italic;
        margin-bottom: 20px;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .testimonial-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-blue);
        font-weight: 700;
    }

    .testimonial-info h4 {
        margin-bottom: 5px;
    }

    .testimonial-info p {
        font-size: 14px;
        opacity: 0.9;
    }

    /* Dark Section */
    .section-dark {
        background: var(--primary-blue);
        color: white;
    }

    .section-dark .section-title {
        color: white;
    }

    /* Colored Sections */
    .section-purple {
        background: var(--purple);
        color: white;
    }

    .section-yellow {
        background: var(--yellow);
        color: var(--dark);
    }

    .section-light-purple {
        background: var(--light-purple);
    }

    .section-light-blue {
        background: var(--light-blue);
    }

    .section-light-green {
        background: #e8f5e9;
    }

    /* Scholarship Cards */
    .scholarship-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        position: relative;
    }

    .scholarship-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--green);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .scholarship-name {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }

    .scholarship-amount {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-blue);
        margin: 15px 0;
    }

    .scholarship-btn {
        background: var(--primary-blue);
        color: white;
        padding: 12px 30px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-top: 15px;
        transition: background 0.3s;
    }

    .scholarship-btn:hover {
        background: var(--secondary-blue);
        color: white;
    }

    /* FAQ Section */
    .faq-item {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .faq-item:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .faq-question {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .faq-answer {
        font-size: 14px;
        color: var(--gray);
        display: none;
    }

    .faq-item.active .faq-answer {
        display: block;
    }

    /* Footer */
    .footer {
        background: var(--primary-blue);
        color: white;
        padding: 60px 20px 30px;
        text-align: center;
    }

    .footer-cta {
        margin-bottom: 40px;
    }

    .footer-cta h2 {
        font-size: 32px;
        margin-bottom: 20px;
    }

    .footer-btn {
        background: white;
        color: var(--primary-blue);
        padding: 15px 40px;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .footer-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        color: var(--primary-blue);
    }

    .footer-copyright {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.2);
        font-size: 14px;
        opacity: 0.8;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 32px;
        }

        .trial-banner-content {
            grid-template-columns: 1fr;
        }

        .top-nav-links {
            display: none;
        }

        .secondary-nav-links {
            display: none;
        }

        .cards-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Top Header Bar -->
<div class="top-header-bar">
    <div class="top-header-content">
        <a href="/" class="top-logo">UniGate</a>
        <ul class="top-nav-links">
            <li><a href="/about-us">About Us</a></li>
            <li><a href="/contact-us">Contact Us</a></li>
            {{-- <li><a href="#blog">Blog</a></li> --}}
            <li><a href="/login">Login</a></li>
            <li><a href="/register" class="sign-up-btn">Sign Up</a></li>
        </ul>
    </div>
</div>

<!-- Main Header -->
<div class="main-header">
    <div class="main-header-content">
        <div class="search-bar">
            <input type="text" placeholder="Search for universities, courses, exams, scholarships...">
            <button>Search</button>
        </div>
        <div class="header-btns">
            <a href="/login" class="btn-login">Login</a>
            <a href="/register" class="btn-signup">Sign Up</a>
        </div>
    </div>
</div>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">
            The best way to <span class="highlight">explore</span> study abroad destination
        </h1>
        <div class="hero-search">
            <input type="text" placeholder="Search for universities, courses, exams, scholarships...">
            <button>Search</button>
        </div>
        <div class="feature-icons">
            <div class="feature-icon">
                <div class="feature-icon-circle"><i class="fas fa-university"></i></div>
                <div class="feature-icon-text">Universities</div>
            </div>
            <div class="feature-icon">
                <div class="feature-icon-circle"><i class="fas fa-book"></i></div>
                <div class="feature-icon-text">Courses</div>
            </div>
            <div class="feature-icon">
                <div class="feature-icon-circle"><i class="fas fa-clipboard-check"></i></div>
                <div class="feature-icon-text">Exams</div>
            </div>
            <div class="feature-icon">
                <div class="feature-icon-circle"><i class="fas fa-award"></i></div>
                <div class="feature-icon-text">Scholarships</div>
            </div>
            <div class="feature-icon">
                <div class="feature-icon-circle"><i class="fas fa-globe"></i></div>
                <div class="feature-icon-text">Countries</div>
            </div>
            <div class="feature-icon">
                <div class="feature-icon-circle"><i class="fas fa-city"></i></div>
                <div class="feature-icon-text">Cities</div>
            </div>
            <div class="feature-icon">
                <div class="feature-icon-circle"><i class="fas fa-blog"></i></div>
                <div class="feature-icon-text">Blogs</div>
            </div>
            <div class="feature-icon">
                <div class="feature-icon-circle"><i class="fas fa-calendar"></i></div>
                <div class="feature-icon-text">Events</div>
            </div>
        </div>
    </div>
</section>

<!-- Secondary Navigation -->
<div class="secondary-nav">
    <div class="secondary-nav-content">
        <a href="/" class="secondary-logo">UniGate</a>
        <ul class="secondary-nav-links">
            <li><a href="#ai-tools">AI Tools</a></li>
            <li><a href="#universities">Universities</a></li>
            <li><a href="#courses">Courses</a></li>
            <li><a href="#exams">Exams</a></li>
            <li><a href="#scholarships">Scholarships</a></li>
            <li><a href="#countries">Countries</a></li>
            <li><a href="#blog">Blogs</a></li>
            <li><a href="#events">Events</a></li>
        </ul>
    </div>
</div>

<!-- 7-Day Free Trial Banner -->
<section class="trial-banner">
    <div class="trial-banner-content">
        <div class="trial-text">
            <h2>7-Day Free Trial</h2>
            <p>Access all premium features for 7 days. No credit card required.</p>
            <a href="/register" class="trial-btn">Start Free Trial</a>
        </div>
        <div class="trial-illustration">
            <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>
</section>

<!-- Trending Articles Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Unlock Global Scientific for Success!</h2>
        <div class="cards-grid">
            @foreach($blogPosts ?? [] as $post)
            <div class="card" style="position: relative;">
                <span class="card-badge">Trending</span>
                  {{-- @if($post->image)
                            <img src="{{ asset($post->image) }}" alt="{{ $post->name }}" class="university-card-image">
                        @else
                            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%); display: flex; align-items: center; justify-content: center;">

                                <i class="fa-solid fa-blog" style="font-size: 64px; color: white;"></i>
                            </div>
                        @endif --}}

                {{-- <img src="{{ $post->image ?? 'https://via.placeholder.com/400x200' }}" alt="{{ $post->title }}" class="card-image"> --}}
                <div class="card-content">
                    <h3 class="card-title">{{ $post->title }}</h3>
                    <a href="/blog/{{ $post->id }}" class="card-btn">Read More</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Success Stories Section -->
<section class="section section-light-blue">
    <div class="container">
        <h2 class="section-title">Know About Our Success Stories</h2>
        <div class="testimonials-grid">
            @foreach($testimonials ?? [] as $testimonial)
            <div class="testimonial-card">
                <p class="testimonial-quote">"{{ $testimonial->quote }}"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">{{ substr($testimonial->name, 0, 1) }}</div>
                    <div class="testimonial-info">
                        <h4>{{ $testimonial->name }}</h4>
                        <p>{{ $testimonial->university }} - {{ $testimonial->program }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Top Universities Section -->
<section class="section section-dark">
    <div class="container">
        <h2 class="section-title">Dive Into The Top Universities</h2>
        <div class="cards-grid">
            @foreach($topUniversities ?? [] as $university)
            <div class="card">
  @if($university->cover_image)
                            <img src="{{ asset($university->cover_image) }}" alt="{{ $university->name }}" class="university-card-image">
                        @else
                            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-university" style="font-size: 64px; color: white;"></i>
                            </div>
                        @endif
                {{-- <img src="{{ $university->cover_image ?? 'https://via.placeholder.com/400x200' }}" alt="{{ $university->name }}" class="card-image"> --}}
                <div class="card-content">
                    <h3 class="card-title">{{ $university->name }}</h3>
                    <p style="color: rgba(255,255,255,0.8); margin-bottom: 15px;">{{ $university->city->name ?? 'N/A' }}</p>
                    <a href="/universities/{{ $university->id }}" class="card-btn">View University</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Scholarships Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Scholarship for You</h2>
        <div class="cards-grid">
            @foreach($scholarships ?? [] as $scholarship)
            <div class="scholarship-card">
                <span class="scholarship-badge">New</span>
                <h3 class="scholarship-name">{{ $scholarship->name }}</h3>
                <p style="color: var(--gray); margin-bottom: 10px;">{{ $scholarship->university->name ?? 'N/A' }}</p>
                <div class="scholarship-amount">
                    @if($scholarship->amount_type === 'fixed')
                        ${{ number_format($scholarship->amount, 0) }}
                    @elseif($scholarship->amount_type === 'percentage')
                        {{ $scholarship->percentage }}% Off
                    @else
                        Full Scholarship
                    @endif
                </div>
                <a href="/scholarships/{{ $scholarship->id }}" class="scholarship-btn">Apply Now</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- AI Powered Video Interview Analyzer Section -->
<section class="section section-yellow">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
            <div>
                <h2 class="section-title" style="text-align: left; margin-bottom: 20px;">AI Powered Video Interview Analyzer</h2>
                <p style="font-size: 18px; margin-bottom: 25px;">Practice your interview skills with our AI-powered tool and get instant feedback.</p>
                <a href="/ai-interview" class="trial-btn" style="background: var(--primary-blue);">Try Now</a>
            </div>
            <div style="text-align: center;">
                <div style="width: 100%; height: 300px; background: rgba(255,255,255,0.3); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 64px;">
                    <i class="fas fa-video"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Get Personalized AI Recommendations Section -->
<section class="section section-light-purple">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
            <div style="text-align: center;">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                    <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <i class="fas fa-clock" style="font-size: 32px; color: var(--primary-blue); margin-bottom: 10px;"></i>
                        <p style="font-size: 14px; color: var(--gray);">Quick Match</p>
                    </div>
                    <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <i class="fas fa-user" style="font-size: 32px; color: var(--primary-blue); margin-bottom: 10px;"></i>
                        <p style="font-size: 14px; color: var(--gray);">Personalized</p>
                    </div>
                    <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <i class="fas fa-comments" style="font-size: 32px; color: var(--primary-blue); margin-bottom: 10px;"></i>
                        <p style="font-size: 14px; color: var(--gray);">AI Powered</p>
                    </div>
                </div>
            </div>
            <div>
                <h2 class="section-title" style="text-align: left; margin-bottom: 20px;">Get Personalized AI Recommendations</h2>
                <p style="font-size: 18px; margin-bottom: 25px;">Our AI matches you with the best universities and courses based on your profile.</p>
                <a href="/ai-recommendations" class="trial-btn" style="background: var(--primary-blue);">Get Started</a>
            </div>
        </div>
    </div>
</section>

<!-- Apply to Universities in a Single Click Section -->
<section class="section section-light-blue">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
            <div>
                <h2 class="section-title" style="text-align: left; margin-bottom: 20px;">Apply to Universities in a Single Click</h2>
                <p style="font-size: 18px; margin-bottom: 25px;">Streamline your application process with our easy-to-use platform.</p>
                <a href="/applications" class="trial-btn" style="background: var(--primary-blue);">Start Application</a>
            </div>
            <div style="text-align: center;">
                <div style="width: 100%; height: 300px; background: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <i class="fas fa-laptop" style="font-size: 64px; color: var(--primary-blue);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Try Our Writing Mentor Section -->
<section class="section section-light-green">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
            <div style="text-align: center;">
                <div style="width: 100%; height: 300px; background: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <i class="fas fa-file-alt" style="font-size: 64px; color: var(--primary-blue);"></i>
                </div>
            </div>
            <div>
                <h2 class="section-title" style="text-align: left; margin-bottom: 20px;">Try Our Writing Mentor</h2>
                <p style="font-size: 18px; margin-bottom: 25px;">Get expert feedback on your essays, SOPs, and LORs.</p>
                <a href="/writing-mentor" class="trial-btn" style="background: var(--primary-blue);">Try Now</a>
            </div>
        </div>
    </div>
</section>

<!-- Get Other Information About Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Get Other Information About</h2>
        <div class="cards-grid">
            <div class="card">
                <div class="card-content" style="text-align: center; padding: 40px;">
                    <i class="fas fa-passport" style="font-size: 48px; color: var(--primary-blue); margin-bottom: 20px;"></i>
                    <h3 class="card-title">Student Visa</h3>
                    <p style="color: var(--gray); margin: 15px 0;">Complete guide to student visa requirements and application process.</p>
                    <a href="/visa-info" class="card-btn">Learn More</a>
                </div>
            </div>
            <div class="card">
                <div class="card-content" style="text-align: center; padding: 40px;">
                    <i class="fas fa-money-bill-wave" style="font-size: 48px; color: var(--primary-blue); margin-bottom: 20px;"></i>
                    <h3 class="card-title">Education Loan</h3>
                    <p style="color: var(--gray); margin: 15px 0;">Find the best education loan options for your studies abroad.</p>
                    <a href="/education-loan" class="card-btn">Learn More</a>
                </div>
            </div>
            <div class="card">
                <div class="card-content" style="text-align: center; padding: 40px;">
                    <i class="fas fa-home" style="font-size: 48px; color: var(--primary-blue); margin-bottom: 20px;"></i>
                    <h3 class="card-title">Accommodation</h3>
                    <p style="color: var(--gray); margin: 15px 0;">Explore accommodation options near your university.</p>
                    <a href="/accommodation" class="card-btn">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scholarship Achievement Section -->
<section class="section section-dark">
    <div class="container">
        <h2 class="section-title">Our Students Have Received Scholarships Worth $7 Million</h2>
        <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; margin-top: 40px;">
            @foreach($testimonials ?? [] as $testimonial)
            <div style="text-align: center;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: white; color: var(--primary-blue); display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 700; margin: 0 auto 10px;">{{ substr($testimonial->name, 0, 1) }}</div>
                <h4 style="color: white; margin-bottom: 5px;">{{ $testimonial->name }}</h4>
                <p style="color: rgba(255,255,255,0.8); font-size: 14px;">{{ $testimonial->university }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Talk to Expert Section -->
<section class="section section-purple">
    <div class="container" style="text-align: center;">
        <h2 class="section-title" style="color: white;">Still Wondering How to Get to Your Dream Destination?</h2>
        <a href="/contact" class="trial-btn" style="background: white; color: var(--purple); margin-top: 30px;">Talk to an Expert</a>
    </div>
</section>

<!-- Choosing Destination Section -->
<section class="section section-dark">
    <div class="container">
        <h2 class="section-title">Choosing Your Destination Is Simple</h2>
        <p style="text-align: center; font-size: 18px; color: rgba(255,255,255,0.9); margin-bottom: 40px;">Answer a few questions and let our AI find the perfect match for you.</p>
        <div style="text-align: center;">
            <a href="/destination-quiz" class="trial-btn" style="background: white; color: var(--primary-blue);">Get Started</a>
        </div>
    </div>
</section>

<!-- University Logos Section -->
<section class="section section-dark">
    <div class="container">
        <h2 class="section-title">Our Students Have Chosen From</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 30px; margin-top: 40px; align-items: center;">
            @foreach($topUniversities ?? [] as $university)
            <div style="text-align: center; padding: 20px; background: rgba(255,255,255,0.1); border-radius: 12px;">
                <div style="font-size: 14px; color: white; font-weight: 600;">{{ $university->name }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- App Download Section -->
<section class="section section-purple">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
            <div>
                <h2 class="section-title" style="text-align: left; color: white; margin-bottom: 20px;">Get Your Hands On Our App</h2>
                <p style="font-size: 18px; color: rgba(255,255,255,0.9); margin-bottom: 30px;">Download the UniGate app for a seamless study abroad experience.</p>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="#" style="display: inline-block;">
                        <div style="background: white; padding: 12px 25px; border-radius: 8px; color: var(--purple); font-weight: 600;">Download on the App Store</div>
                    </a>
                    <a href="#" style="display: inline-block;">
                        <div style="background: white; padding: 12px 25px; border-radius: 8px; color: var(--purple); font-weight: 600;">Get it on Google Play</div>
                    </a>
                </div>
            </div>
            <div style="text-align: center;">
                <div style="display: flex; gap: 20px; justify-content: center;">
                    <div style="width: 150px; height: 300px; background: rgba(255,255,255,0.2); border-radius: 25px;"></div>
                    <div style="width: 150px; height: 300px; background: rgba(255,255,255,0.2); border-radius: 25px;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
@if(isset($faqs) && count($faqs) > 0)
<section class="section section-light-gray">
    <div class="container">
        <h2 class="section-title">Frequently Asked Questions</h2>
        <div style="max-width: 800px; margin: 0 auto;">
            @foreach($faqs as $faq)
            <div class="faq-item">
                <div class="faq-question">{{ $faq->title }}</div>
                <div class="faq-answer">{{ $faq->description }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-cta">
            <h2>It's your time to start your journey with us</h2>
            <a href="/register" class="footer-btn">Get Started</a>
        </div>
        <div class="footer-copyright">
            <p>&copy; {{ date('Y') }} UniGate. All rights reserved.</p>
        </div>
    </div>
</footer>
@endsection

@section('script')
<script>
    // FAQ Toggle
    document.querySelectorAll('.faq-item').forEach(item => {
        item.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    });
</script>
@endsection
