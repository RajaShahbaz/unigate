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
        line-height: 1.15;
    }
    .page-hero p {
        font-size: 20px;
        opacity: 0.95;
        max-width: 640px;
        margin: 0 auto;
        line-height: 1.5;
    }
    .pricing-section {
        padding: 80px 20px;
        background: white;
    }
    .pricing-content {
        max-width: 1200px;
        margin: 0 auto;
    }
    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 32px;
        margin-top: 48px;
    }
    .pricing-card {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 20px;
        padding: 36px 32px;
        text-align: center;
        transition: all 0.3s;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .pricing-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        border-color: rgba(70, 90, 40, 1);
    }
    .pricing-card.featured {
        border-color: rgba(70, 90, 40, 1);
        background: linear-gradient(180deg, rgba(70, 90, 40, 0.06) 0%, #fff 40%);
    }
    .pricing-card.featured::before {
        content: 'Popular';
        position: absolute;
        top: -14px;
        right: 28px;
        background: rgba(70, 90, 40, 1);
        color: white;
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .pricing-header h3 {
        font-size: 26px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
    }
    .pricing-header .desc {
        font-size: 15px;
        color: #5c6c7d;
        line-height: 1.6;
        text-align: left;
        margin-bottom: 24px;
        flex-grow: 0;
    }
    .pricing-price {
        margin-bottom: 28px;
    }
    .pricing-price .amount {
        font-size: 48px;
        font-weight: 700;
        color: rgba(70, 90, 40, 1);
        line-height: 1;
    }
    .pricing-price .currency {
        font-size: 22px;
        color: #666;
        vertical-align: top;
        margin-right: 4px;
    }
    .pricing-price .period {
        font-size: 15px;
        color: #666;
        margin-top: 10px;
    }
    .pricing-button {
        background: rgba(70, 90, 40, 1);
        color: white;
        padding: 16px 32px;
        border: none;
        border-radius: 8px;
        font-size: 17px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        margin-top: auto;
    }
    .pricing-button:hover {
        background: rgb(52, 68, 28);
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(70, 90, 40, 0.35);
        color: white;
    }
    .section-title {
        text-align: center;
        font-size: 40px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 16px;
    }
    .section-subtitle {
        text-align: center;
        font-size: 18px;
        color: #666;
        max-width: 680px;
        margin: 0 auto;
        line-height: 1.55;
    }
    .pricing-footnote {
        text-align: center;
        margin-top: 40px;
        font-size: 15px;
        color: #5c6c7d;
        max-width: 560px;
        margin-left: auto;
        margin-right: auto;
    }
    .pricing-empty {
        text-align: center;
        padding: 48px 24px;
        background: linear-gradient(135deg, rgba(70, 90, 40, 0.08) 0%, rgba(70, 90, 40, 0.04) 100%);
        border-radius: 16px;
        border: 1px dashed rgba(70, 90, 40, 0.25);
        margin-top: 40px;
    }
    .pricing-empty p {
        font-size: 17px;
        color: #5c6c7d;
        margin-bottom: 20px;
    }
    @media (max-width: 768px) {
        .page-hero h1 {
            font-size: 30px;
        }
        .pricing-grid {
            grid-template-columns: 1fr;
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
                    <li><a href="{{ url('/') }}#home">Home</a></li>
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
    <section class="page-hero">
        <div class="container">
            <h1>Application packages</h1>
            <p>
                Choose a package that matches how many university applications you want to submit. Prices are loaded from our catalog and can be purchased after you sign in.
            </p>
        </div>
    </section>

    <section class="pricing-section">
        <div class="pricing-content">
            <h2 class="section-title">Plans for applicants</h2>
            <p class="section-subtitle">
                All packages unlock the same platform features; the difference is how many applications you can submit. Sign in to complete checkout (web or mobile app).
            </p>

            @php
                $packagesList = $applicationPackages ?? collect();
                $count = $packagesList->count();
                $featuredIndex = $count >= 3 ? 1 : ($count === 2 ? 1 : null);
            @endphp

            @if($packagesList->isEmpty())
                <div class="pricing-empty">
                    <p>No packages are published yet. Please check back soon or contact us for options.</p>
                    <a href="{{ url('/contact-us') }}" class="pricing-button" style="max-width: 280px; margin: 0 auto;">Contact us</a>
                </div>
            @else
                <div class="pricing-grid">
                    @foreach($packagesList as $index => $package)
                        <div class="pricing-card {{ ($featuredIndex !== null && $index === $featuredIndex) ? 'featured' : '' }}">
                            <div class="pricing-header">
                                <h3>{{ $package->name }}</h3>
                                <div class="desc">
                                    @if($package->description)
                                        {!! nl2br(e($package->description)) !!}
                                    @else
                                        University application package — purchase after sign-in to submit applications through UniGate on web or in the app.
                                    @endif
                                </div>
                            </div>
                            <div class="pricing-price">
                                <div class="amount">
                                    <span class="currency">{{ $package->currency ?? 'PKR' }}</span>{{ number_format((float) $package->price, ((float) $package->price == floor((float) $package->price) ? 0 : 2)) }}
                                </div>
                                <div class="period">One-time package</div>
                            </div>
                            <a href="{{ url('login') }}" class="pricing-button">Sign in to purchase</a>
                        </div>
                    @endforeach
                </div>
                <p class="pricing-footnote">
                    Pricing and availability are managed by your administrators. If you already have an account, open the student portal after login to view packages and payment options.
                </p>
            @endif
        </div>
    </section>
</div>

{{-- @include('layouts.home_page.footer') --}}

@endsection
