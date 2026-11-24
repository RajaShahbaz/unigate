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
        max-width: 720px;
        margin: 0 auto 28px;
        line-height: 1.5;
    }
    .page-hero-cta {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
        margin-top: 8px;
    }
    .page-hero-cta a {
        display: inline-block;
        padding: 14px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .page-hero-cta a:hover {
        transform: translateY(-2px);
    }
    .page-hero-cta .btn-solid {
        background: white;
        color: rgba(70, 90, 40, 1);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    }
    .page-hero-cta .btn-outline {
        background: transparent;
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.85);
    }
    .features-section {
        padding: 80px 20px;
        background: white;
    }
    .features-content {
        max-width: 1200px;
        margin: 0 auto;
    }
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 28px;
        margin-top: 48px;
    }
    .feature-card {
        background: linear-gradient(135deg, rgba(70, 90, 40, 0.09) 0%, rgba(70, 90, 40, 0.16) 100%);
        padding: 36px 28px;
        border-radius: 16px;
        text-align: left;
        transition: all 0.3s;
        border: 1px solid rgba(70, 90, 40, 0.08);
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(70, 90, 40, 0.2);
    }
    .feature-card .icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: rgba(70, 90, 40, 1);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    .feature-card .icon-wrap i {
        font-size: 24px;
    }
    .feature-card h3 {
        font-size: 20px;
        font-weight: 600;
        color: rgba(70, 90, 40, 1);
        margin-bottom: 12px;
    }
    .feature-card p {
        font-size: 15px;
        color: #5c6c7d;
        line-height: 1.65;
        margin: 0;
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
        max-width: 700px;
        margin: 0 auto 0;
        line-height: 1.55;
    }
    .dynamic-section {
        margin-top: 72px;
        padding-top: 56px;
        border-top: 1px solid #e8ecef;
    }
    .feature-card--center {
        text-align: center;
    }
    .feature-card--center .icon-wrap {
        margin-left: auto;
        margin-right: auto;
    }
    @media (max-width: 768px) {
        .page-hero h1 {
            font-size: 30px;
        }
        .page-hero p {
            font-size: 17px;
        }
        .section-title {
            font-size: 28px;
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
    <section class="page-hero">
        <div class="container">
            <h1>Everything you need to apply for higher education</h1>
            <p>
                UniGate helps you discover universities, compare options, understand costs, and follow your applications—from first search to submission—whether you are aiming for undergraduate, graduate, or professional programs.
            </p>
            <div class="page-hero-cta">
                <a href="{{ url('/universities') }}" class="btn-solid">Browse universities</a>
                @auth
                    @php
                        $u = auth()->user();
                        $dash = (method_exists($u, 'hasRole') && $u->hasRole('Student')) && empty($u->school_id) ? route('user.portal.dashboard') : route('dashboard');
                    @endphp
                    <a href="{{ $dash }}" class="btn-outline">Go to dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-outline">Sign in to track applications</a>
                @endauth
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="features-content">
            <h2 class="section-title">Built for serious applicants</h2>
            <p class="section-subtitle">
                These tools are designed around real admission journeys: research, comparison, budgeting, preparation, and staying on top of deadlines and requirements.
            </p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="icon-wrap"><i class="fas fa-columns"></i></div>
                    <h3>Compare universities</h3>
                    <p>
                        Explore programs side by side—location, focus areas, admission criteria, and campus highlights—so you can shortlist schools that truly fit your goals instead of guessing from scattered websites.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap"><i class="fas fa-coins"></i></div>
                    <h3>Fees, expenses &amp; costs</h3>
                    <p>
                        See tuition-oriented context where institutions publish it, and plan your budget with a clearer picture of what studying at each university may involve before you commit time and money to an application.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap"><i class="fas fa-trophy"></i></div>
                    <h3>Rankings &amp; reputation signals</h3>
                    <p>
                        Use ranking and profile data to understand how institutions present themselves and how they compare within your field of interest—one more input alongside fit, location, and cost.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap"><i class="fas fa-book-open"></i></div>
                    <h3>Study guides &amp; requirements</h3>
                    <p>
                        Access structured information on admission requirements, documents, and program-level guidance so you know what each university expects before you start your application packet.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap"><i class="fas fa-tasks"></i></div>
                    <h3>Application tracking</h3>
                    <p>
                        Keep progress visible in one place: where you have applied, what is still pending, and what needs your attention—so deadlines and follow-ups do not slip through the cracks.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap"><i class="fas fa-heart"></i></div>
                    <h3>Favorites &amp; saved universities</h3>
                    <p>
                        Save institutions you are considering, revisit them quickly, and narrow your list as you learn more about programs, cities, and financial fit.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap"><i class="fas fa-file-alt"></i></div>
                    <h3>Documents &amp; application data</h3>
                    <p>
                        Organize the information and files universities ask for, aligned to how applications are actually submitted—less last-minute scrambling, fewer missing pieces.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrap"><i class="fas fa-graduation-cap"></i></div>
                    <h3>Multi-university applications</h3>
                    <p>
                        Apply to more than one institution through a single, coherent workflow instead of juggling completely separate portals and formats for every school.
                    </p>
                </div>
            </div>

            {{-- @if(($features ?? collect())->isNotEmpty() || (isset($featureSections) && count($featureSections) > 0))
                <div class="dynamic-section">
                    @if(($features ?? collect())->isNotEmpty())
                        <h2 class="section-title">More highlights</h2>
                        <p class="section-subtitle">Additional capabilities we continue to expand based on student and university feedback.</p>
                        <div class="features-grid">
                            @foreach($features as $feature)
                                <div class="feature-card feature-card--center">
                                    <div class="icon-wrap"><i class="fas fa-check-circle"></i></div>
                                    <h3>{{ $feature->name }}</h3>
                                    <p>{{ $feature->description ?? 'Designed to support your application journey.' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(isset($featureSections) && count($featureSections) > 0)
                        @foreach($featureSections as $section)
                            <div style="margin-top: 56px;">
                                <h2 class="section-title">{{ $section->title ?? 'Additional features' }}</h2>
                                @if($section->description)
                                    <p class="section-subtitle">{{ $section->description }}</p>
                                @endif
                                <div class="features-grid">
                                    @foreach($section->feature_section_list ?? [] as $item)
                                        <div class="feature-card feature-card--center">
                                            <div class="icon-wrap"><i class="fas fa-star"></i></div>
                                            <h3>{{ $item->title ?? 'Feature' }}</h3>
                                            <p>{{ $item->description ?? '' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif --}}

            {{-- <div style="text-align: center; margin-top: 64px;">
                <p style="font-size: 17px; color: #5c6c7d; margin-bottom: 20px;">Questions about how UniGate fits your plans?</p>
                <a href="{{ url('/contact-us') }}" class="commonBtn" style="display: inline-block; padding: 14px 32px; text-decoration: none;">Contact us</a>
            </div> --}}
        </div>
    </section>
</div>

{{-- @include('layouts.home_page.footer') --}}

@endsection
