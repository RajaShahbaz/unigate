@extends('layouts.home_page.master')

@section('content')
<style>
    .page-hero {
        background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%);
        padding: 80px 20px;
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
    .universities-section {
        padding: 60px 20px;
        background: white;
    }
    .universities-content {
        max-width: 1200px;
        margin: 0 auto;
    }
    .filters-bar {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 40px;
    }
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    .filter-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    .filter-group select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 16px;
        background: white;
        cursor: pointer;
    }
    .filter-group select:focus {
        outline: none;
        border-color: rgba(70, 90, 40, 1);
    }
    .filter-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    .filter-btn {
        background: rgba(70, 90, 40, 1);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    .filter-btn:hover {
        background: #023d6b;
        transform: translateY(-2px);
    }
    .clear-btn {
        background: transparent;
        color: #666;
        padding: 12px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
    }
    .clear-btn:hover {
        border-color: rgba(70, 90, 40, 1);
        color: rgba(70, 90, 40, 1);
    }
    .universities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }
    .university-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
        cursor: pointer;
    }
    .university-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .university-card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .university-card-content {
        padding: 25px;
    }
    .university-card h3 {
        font-size: 22px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .university-card .location {
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .university-card .interests {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 15px;
    }
    .interest-tag {
        background: rgba(70, 90, 40, 0.09);
        color: rgba(70, 90, 40, 1);
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
    }
    .university-card .view-btn {
        background: rgba(70, 90, 40, 1);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .university-card .view-btn:hover {
        background: #023d6b;
        color: white;
    }
    .no-results {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }
    .no-results h3 {
        font-size: 24px;
        margin-bottom: 10px;
    }
    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 40px;
    }
    .pagination a, .pagination span {
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
    }
    .pagination .active {
        background: rgba(70, 90, 40, 1);
        color: white;
        border-color: rgba(70, 90, 40, 1);
    }
    @media (max-width: 768px) {
        .page-hero h1 {
            font-size: 32px;
        }
        .filters-grid {
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
    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <h1>Find Your Perfect University</h1>
            <p>Discover universities that match your interests and goals</p>
        </div>
    </section>

    <!-- Universities Section -->
    <section class="universities-section">
        <div class="universities-content">
            <!-- Filters -->
            <div class="filters-bar">
                <form method="GET" action="{{ url('/universities') }}" id="filter-form">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="interest-filter">Program/Interest</label>
                            <select name="interest" id="interest-filter">
                                <option value="">All Programs</option>
                                @foreach($allInterests ?? [] as $interest)
                                    <option value="{{ $interest->id }}" {{ $interestId == $interest->id ? 'selected' : '' }}>
                                        {{ $interest->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="city-filter">City/Destination</label>
                            <select name="city" id="city-filter">
                                <option value="">All Cities</option>
                                @foreach($allCities ?? [] as $city)
                                    <option value="{{ $city->id }}" {{ $cityId == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="filter-btn">Apply Filters</button>
                        <a href="{{ url('/universities') }}" class="clear-btn">Clear Filters</a>
                    </div>
                </form>
            </div>

            <!-- Results Count -->
            <div style="margin-bottom: 30px; color: #666;">
                <strong>{{ $universities->total() }}</strong> universities found
            </div>

            <!-- Universities Grid -->
            @if($universities->count() > 0)
                <div class="universities-grid">
                    @foreach($universities as $university)
                    <div class="university-card" onclick="window.location.href='{{ url('/universities/' . $university->id) }}'">
                        @if($university->cover_image)
                            <img src="{{ asset('storage/' . $university->cover_image) }}" alt="{{ $university->name }}" class="university-card-image">
                        @else
                            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-university" style="font-size: 64px; color: white;"></i>
                            </div>
                        @endif
                        <div class="university-card-content">
                            <h3>{{ $university->name }}</h3>
                            @if($university->city)
                                <div class="location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $university->city->name }}
                                </div>
                            @endif
                            @if($university->interests && $university->interests->count() > 0)
                                <div class="interests">
                                    @foreach($university->interests->take(3) as $interest)
                                        <span class="interest-tag">{{ $interest->name }}</span>
                                    @endforeach
                                    @if($university->interests->count() > 3)
                                        <span class="interest-tag">+{{ $university->interests->count() - 3 }} more</span>
                                    @endif
                                </div>
                            @endif
                            <a href="{{ url('/universities/' . $university->id) }}" class="view-btn" onclick="event.stopPropagation();">View Details</a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($universities->hasPages())
                    <div class="pagination">
                        @if($universities->onFirstPage())
                            <span>&laquo; Previous</span>
                        @else
                            <a href="{{ $universities->previousPageUrl() }}">&laquo; Previous</a>
                        @endif

                        @foreach($universities->getUrlRange(1, $universities->lastPage()) as $page => $url)
                            @if($page == $universities->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($universities->hasMorePages())
                            <a href="{{ $universities->nextPageUrl() }}">Next &raquo;</a>
                        @else
                            <span>Next &raquo;</span>
                        @endif
                    </div>
                @endif
            @else
                <div class="no-results">
                    <h3>No universities found</h3>
                    <p>Try adjusting your filters to see more results.</p>
                </div>
            @endif
        </div>
    </section>
</div>

{{-- @include('layouts.home_page.footer') --}}

@endsection
