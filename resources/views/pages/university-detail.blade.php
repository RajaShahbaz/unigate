@extends('layouts.home_page.master')

@section('content')
<style>
    .university-hero {
        background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%);
        padding: 80px 20px;
        color: white;
    }
    .university-hero-content {
        max-width: 1200px;
        margin: 0 auto;
    }
    .university-header {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 40px;
        align-items: center;
    }
    .university-logo {
        width: 200px;
        height: 200px;
        background: white;
        border-radius: 20px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .university-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .university-info h1 {
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .university-info p {
        font-size: 18px;
        opacity: 0.9;
        margin-bottom: 10px;
    }
    .university-info .location {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
    }
    .university-detail-section {
        padding: 80px 20px;
        background: white;
    }
    .university-content {
        max-width: 1200px;
        margin: 0 auto;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
    }
    .main-content h2 {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid rgba(70, 90, 40, 1);
    }
    .main-content p {
        font-size: 18px;
        color: #666;
        line-height: 1.8;
        margin-bottom: 30px;
    }
    .info-card {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
    }
    .info-card h3 {
        font-size: 24px;
        font-weight: 600;
        color: rgba(70, 90, 40, 1);
        margin-bottom: 20px;
    }
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #e0e0e0;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-item-label {
        font-weight: 600;
        color: #2c3e50;
    }
    .info-item-value {
        color: #666;
    }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    .gallery-item {
        border-radius: 12px;
        overflow: hidden;
        height: 200px;
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .scholarship-list {
        margin-top: 20px;
    }
    .scholarship-item {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 15px;
        border-left: 4px solid rgba(70, 90, 40, 1);
    }
    .scholarship-item h4 {
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .scholarship-item p {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }
    .favorite-btn {
        background: white;
        color: rgba(70, 90, 40, 1);
        border: 2px solid rgba(70, 90, 40, 1);
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 20px;
    }
    .favorite-btn:hover {
        background: rgba(70, 90, 40, 1);
        color: white;
    }
    .favorite-btn.active {
        background: rgba(70, 90, 40, 1);
        color: white;
    }
    @media (max-width: 768px) {
        .university-header {
            grid-template-columns: 1fr;
        }
        .detail-grid {
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
    <!-- University Hero -->
    <section class="university-hero">
        <div class="university-hero-content">
            <div class="university-header">
                <div class="university-logo">
                    @if($university->logo)
                        <img src="{{ asset('storage/' . $university->logo) }}" alt="{{ $university->name }}">
                    @else
                        <i class="fas fa-university" style="font-size: 80px; color: rgba(70, 90, 40, 1);"></i>
                    @endif
                </div>
                <div class="university-info">
                    <h1>{{ $university->name }}</h1>
                    @if($university->city)
                        <p class="location">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $university->city->name }}
                        </p>
                    @endif
                    @if($university->type)
                        <p><strong>Type:</strong> {{ ucfirst($university->type) }}</p>
                    @endif
                    @auth
                        <button class="favorite-btn {{ $is_favorite ? 'active' : '' }}" onclick="toggleFavorite({{ $university->id }})">
                            <i class="fas fa-heart"></i> {{ $is_favorite ? 'Favorited' : 'Add to Favorites' }}
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- University Detail Section -->
    <section class="university-detail-section">
        <div class="university-content">
            <div class="detail-grid">
                <!-- Main Content -->
                <div class="main-content">
                    @if($university->description)
                        <h2>About University</h2>
                        <p>{{ $university->description }}</p>
                    @endif

                    @if($university->interests && count($university->interests) > 0)
                        <h2>Programs Offered</h2>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 30px;">
                            @foreach($university->interests as $interest)
                                <span style="background: rgba(70, 90, 40, 0.09); color: rgba(70, 90, 40, 1); padding: 8px 16px; border-radius: 20px; font-size: 14px;">
                                    {{ $interest->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if($university->activeGalleries && count($university->activeGalleries) > 0)
                        <h2>Gallery</h2>
                        <div class="gallery-grid">
                            @foreach($university->activeGalleries as $gallery)
                                <div class="gallery-item">
                                    @if($gallery->type === 'image')
                                        <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="Gallery Image">
                                    @else
                                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f0f0f0;">
                                            <i class="fas fa-video" style="font-size: 48px; color: rgba(70, 90, 40, 1);"></i>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($university->activeScholarships && count($university->activeScholarships) > 0)
                        <h2>Scholarships</h2>
                        <div class="scholarship-list">
                            @foreach($university->activeScholarships as $scholarship)
                                <div class="scholarship-item">
                                    <h4>{{ $scholarship->name }}</h4>
                                    @if($scholarship->description)
                                        <p>{{ $scholarship->description }}</p>
                                    @endif
                                    @if($scholarship->amount_type === 'fixed')
                                        <p><strong>Amount:</strong> ${{ number_format($scholarship->amount, 0) }}</p>
                                    @elseif($scholarship->amount_type === 'percentage')
                                        <p><strong>Discount:</strong> {{ $scholarship->percentage }}%</p>
                                    @endif
                                    @if($scholarship->application_deadline)
                                        <p><strong>Deadline:</strong> {{ \Carbon\Carbon::parse($scholarship->application_deadline)->format('F d, Y') }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="sidebar">
                    <div class="info-card">
                        <h3>University Information</h3>
                        @if($university->city)
                            <div class="info-item">
                                <span class="info-item-label">Location</span>
                                <span class="info-item-value">{{ $university->city->name }}</span>
                            </div>
                        @endif
                        @if($university->type)
                            <div class="info-item">
                                <span class="info-item-label">Type</span>
                                <span class="info-item-value">{{ ucfirst($university->type) }}</span>
                            </div>
                        @endif
                        @if($university->ranking)
                            <div class="info-item">
                                <span class="info-item-label">Ranking</span>
                                <span class="info-item-value">#{{ $university->ranking }}</span>
                            </div>
                        @endif
                        @if($university->website)
                            <div class="info-item">
                                <span class="info-item-label">Website</span>
                                <span class="info-item-value">
                                    <a href="{{ $university->website }}" target="_blank" style="color: rgba(70, 90, 40, 1);">Visit Website</a>
                                </span>
                            </div>
                        @endif
                    </div>

                    @if($university->activeAdmissionRequirements && count($university->activeAdmissionRequirements) > 0)
                        <div class="info-card">
                            <h3>Admission Requirements</h3>
                            @foreach($university->activeAdmissionRequirements as $requirement)
                                <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e0e0e0;">
                                    @if($requirement->interest)
                                        <strong>{{ $requirement->interest->name }}</strong>
                                    @endif
                                    @if($requirement->program_level)
                                        <p style="font-size: 14px; color: #666; margin-top: 5px;">Level: {{ ucfirst($requirement->program_level) }}</p>
                                    @endif
                                    @if($requirement->requirements)
                                        <p style="font-size: 14px; color: #333; margin-top: 10px;">{{ $requirement->requirements }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="info-card">
                        @auth
                            <a href="{{ url('/universities/' . $university->id . '/apply') }}" class="pricing-button" style="text-align: center; display: block;">
                                Apply Now
                            </a>
                        @else
                            <a href="{{ url('/login?redirect=' . urlencode('/universities/' . $university->id . '/apply')) }}" class="pricing-button" style="text-align: center; display: block;">
                                Login to Apply
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('layouts.home_page.footer')

@endsection

@section('script')
<script>
    function toggleFavorite(universityId) {
        @auth
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch(`/api/favorites/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    university_id: universityId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.message || 'Failed to update favorite');
                    return;
                }
                const btn = document.querySelector('.favorite-btn');
                if (data.data && data.data.is_favorite) {
                    btn.classList.add('active');
                    btn.innerHTML = '<i class="fas fa-heart"></i> Favorited';
                } else {
                    btn.classList.remove('active');
                    btn.innerHTML = '<i class="fas fa-heart"></i> Add to Favorites';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update favorite. Please try again.');
            });
        @else
            window.location.href = "{{ url('login') }}";
        @endauth
    }
</script>
@endsection
