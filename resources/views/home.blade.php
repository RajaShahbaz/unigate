@extends('layouts.home_page.master')

@section('content')
    <style>
        :root {
            --primary-blue: rgba(70, 90, 40, 1);
            --primary-color: {{ $settings['theme_primary_color'] ?? '#56cc99' }};
            --secondary-color: {{ $settings['theme_secondary_color'] ?? '#215679' }};
            --secondary-color1: {{ $settings['theme_secondary_color_1'] ?? '#38a3a5' }};
            --primary-background-color: {{ $settings['theme_primary_background_color'] ?? '#f2f5f7' }};
            --text--secondary-color: {{ $settings['theme_text_secondary_color'] ?? '#5c788c' }};
        }

        /* Hero Section Styles - Matching Image */
        .hero-section-new {
            background: linear-gradient(to right, #f8f9fa 0%, #e9ecef 100%);
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 20px;
            position: relative;
            overflow: hidden;
        }

        .hero-section-new::after {
            content: 'UniGate';
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 200px;
            font-weight: 900;
            color: rgba(0, 0, 0, 0.03);
            white-space: nowrap;
            z-index: 0;
        }

        .hero-content-new {
            max-width: 900px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-headline {
            font-size: 56px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-headline .highlight-word {
            position: relative;
            display: inline-block;
            background: rgba(70, 90, 40, 1);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            margin: 0 5px;
        }

        .hero-headline .arrow-drawing {
            position: absolute;
            left: -40px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 2px;
            background: #2c3e50;
            border-radius: 2px;
        }

        .hero-headline .arrow-drawing::before {
            content: '';
            position: absolute;
            right: -5px;
            top: -3px;
            width: 8px;
            height: 8px;
            border-right: 2px solid #2c3e50;
            border-top: 2px solid #2c3e50;
            transform: rotate(45deg);
        }

        .hero-tagline {
            font-size: 18px;
            color: #5c6c7d;
            margin-bottom: 40px;
            font-weight: 400;
        }

        .hero-search-section {
            margin-top: 40px;
        }

        .search-label {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 15px;
            display: block;
            font-weight: 500;
        }

        .search-inputs-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .search-dropdown {
            position: relative;
            min-width: 180px;
        }

        .search-dropdown select {
            width: 100%;
            padding: 14px 40px 14px 20px;
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            color: #2c3e50;
            appearance: none;
            cursor: pointer;
            outline: none;
            transition: all 0.3s;
        }

        .search-dropdown select:hover {
            border-color: rgba(70, 90, 40, 1);
            background: #ffffff;
        }

        .search-dropdown::after {
            content: '▼';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 12px;
            pointer-events: none;
        }

        .search-separator {
            font-size: 16px;
            color: #666;
            font-weight: 500;
        }

        .start-journey-btn {
            background: rgba(70, 90, 40, 1);
            color: white !important;
            padding: 14px 35px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .start-journey-btn:hover {
            background: rgb(132, 186, 50);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(11, 86, 48, 0.3);
            color: white;
        }

        .start-journey-btn::after {
            /* content: '>';
            font-size: 18px; */
        }

        @media (max-width: 768px) {
            .hero-headline {
                font-size: 36px;
            }

            .search-inputs-row {
                flex-direction: column;
            }

            .search-separator {
                display: none;
            }

            /* Responsive for info cards */
            .info-cards-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
    <script src="{{ asset('assets/home_page/js/jquery-1-12-4.min.js') }}"></script>

    <header class="navbar">
        <div class="container">
            <div class="navbarWrapper">
                <div class="navLogoWrapper">
                    <div class="navLogo">
                        <a href="{{ url('/') }}">
                            <img src="{{asset('images/logo.png') }}"
                                class="logo" alt="">
                        </a>
                    </div>
                </div>
                <div class="menuListWrapper">
                    <ul class="listItems">
                        <li>
                            <a href="{{ url('/') }}#home">{{ __('home') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/features') }}">{{ __('features') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/about-us') }}">{{ __('about_us') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/pricing') }}">{{ __('pricing') }}</a>
                        </li>
                        @if (count($faqs))
                            <li>
                                <a href="{{ url('/') }}#faq">{{ __('faqs') }}</a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ url('/contact-us') }}">{{ __('contact') }}</a>
                        </li>
                        @if (count($guidances))
                            <li>
                                <div class="dropdown">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                        id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ __('guidance') }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        @foreach ($guidances as $key => $guidance)
                                            <li><a class="dropdown-item"
                                                    href="{{ $guidance->link }}">{{ $guidance->name }}</a></li>
                                            @if (count($guidances) > $key + 1)
                                                <hr>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @endif

                    </ul>
                    <div class="hamburg">
                        <span data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                                class="fa-solid fa-bars"></i></span>
                    </div>
                </div>

                <div class="loginBtnsWrapper d-flex align-items-center justify-content-end flex-wrap gap-2">
                    @include('partials.public_nav_auth_buttons')
                </div>
            </div>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <div class="navLogoWrapper">
                        <div class="navLogo">
                            <img src="{{asset('images/logo.png') }}"
                                alt="">
                        </div>
                    </div>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="listItems">
                        <li>
                            <a href="{{ url('/') }}#home">{{ __('home') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/features') }}">{{ __('features') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/about-us') }}">{{ __('about_us') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/pricing') }}">{{ __('pricing') }}</a>
                        </li>
                        @if (count($faqs))
                            <li>
                                <a href="{{ url('/') }}#faq">{{ __('faqs') }}</a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ url('/contact-us') }}">{{ __('contact') }}</a>
                        </li>
                        @if (count($guidances))
                            <li>
                                <div class="dropdown">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                        id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ __('guidance') }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        @foreach ($guidances as $key => $guidance)
                                            <li><a class="dropdown-item"
                                                    href="{{ $guidance->link }}">{{ $guidance->name }}</a></li>
                                            @if (count($guidances) > $key + 1)
                                                <hr>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @endif
                        <li>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                    id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('language') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @foreach ($languages as $key => $language)
                                        <li><a class="dropdown-item"
                                                href="{{ url('set-language') . '/' . $language->code }}">{{ $language->name }}</a>
                                        </li>
                                        @if (count($languages) > $key + 1)
                                            <hr>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <div class="mt-3 px-2">
                        @include('partials.public_nav_auth_buttons', ['offcanvasDismiss' => true])
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- navbar ends here -->

    <div class="main">
        <!-- Hero Section - Matching Image Design -->
        <section class="hero-section-new" id="home">
            <div class="hero-content-new">
                <h1 class="hero-headline">
                    <span class="highlight-word">
                        <span class=""></span>
                         Apply
                    </span>
                     to Top Universities in Pakistan
                      – All in One Place
                </h1>
                <p class="hero-tagline">Powered by the world's leading AI technology</p>

                <div class="hero-search-section">
                    <label class="search-label">I'm interested in</label>
                    <div class="search-inputs-row">
                        <div class="search-dropdown col-8 col-md-3">
                            <select name="degree" id="degree-select">
                                <option value="">Select degree</option>
                                @foreach ($degreeLevels ?? [] as $degree)
                                    <option value="{{ $degree }}">{{ $degree }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="search-separator">in</span>
                        <div class="search-dropdown col-8 col-md-3">
                            <select name="discipline" id="discipline-select">
                                <option value="">Select discipline</option>
                                @foreach ($interests ?? [] as $interest)
                                    <option value="{{ $interest->id }}">{{ $interest->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="search-separator">in</span>
                        <div class="search-dropdown col-8 col-md-3">
                            <select name="destination" id="destination-select">
                                <option value="">Select destination</option>
                                @foreach ($cities ?? [] as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <a href="/register" class="start-journey-btn">Start my journey</a>
                </div>
            </div>
        </section>

        @include('registration_form')

        <!-- Rest of the sections from the new design -->
        <!-- 7-Day Free Trial Banner -->
        {{-- <section class="trial-banner" style="background: var(--light-blue); padding: 60px 20px;">
        <div class="container" style="max-width: 1200px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
                <div>
                    <h2 style="font-size: 36px; color: var(--primary-blue); margin-bottom: 15px;">7-Day Free Trial</h2>
                    <p style="font-size: 18px; color: var(--gray); margin-bottom: 25px;">Unlock premium features and get personalized guidance for your study abroad journey.</p>
                    <a href="/register" class="trial-btn" style="background: var(--primary-blue); color: white; padding: 15px 40px; border-radius: 25px; text-decoration: none; display: inline-block; font-size: 16px;">Start Free Trial</a>
        </div>
                <div style="text-align: center;">
            <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>
        </div>
    </section> --}}

        <!-- Trending Articles Section -->
        <section class="section" style="padding: 80px 20px; background: white;">
            <div class="container" style="max-width: 1200px; margin: 0 auto;">
                <h2 class="section-title"
                    style="text-align: center; font-size: 36px; font-weight: 700; margin-bottom: 50px; color: #333;">Unlock
                    Global Scientific for Success!</h2>
                <div class="cards-grid"
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                    @foreach ($blogPosts ?? [] as $post)
                        <div class="card"
                            style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); position: relative;">
                            <span
                                style="position: absolute; top: 15px; right: 15px; background: #dc3545; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; z-index: 1;">Trending</span>
                            {{-- <img src="{{ $post->image ?? 'https://via.placeholder.com/400x200' }}"
                                alt="{{ $post->title }}" style="width: 100%; height: 200px; object-fit: cover;"> --}}
                                  @if($post->image)
                            <img src="{{ asset( $post->image) }}" alt="{{ $post->name }}" class="university-card-image">
                        @else
                            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%); display: flex; align-items: center; justify-content: center;">
                                {{-- <i class="fas fa-university" style="font-size: 64px; color: white;"></i> --}}
                                <i class="fa-solid fa-blog" style="font-size: 64px; color: white;"></i>

                            </div>
                        @endif
                            <div style="padding: 20px;">
                                <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 10px; color: #333;">
                                    {{ $post->title }}</h3>
                                <a href="/blog/{{ $post->id }}" class="text-light"
                                    style="display: inline-block; margin-top: 15px; padding: 10px 25px; background: rgba(70, 90, 40, 1); color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">Read
                                    More</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Success Stories Section -->
        <section class="section" style="padding: 80px 20px; background: rgba(70, 90, 40, 0.09);">
            <div class="container" style="max-width: 1200px; margin: 0 auto;">
                <h2 class="section-title"
                    style="text-align: center; font-size: 36px; font-weight: 700; margin-bottom: 50px; color: #333;">Know
                    About Our Success Stories</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                    @foreach ($testimonials ?? [] as $testimonial)
                        <div
                            style="background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%); color: white; padding: 30px; border-radius: 12px;">
                            <p style="font-size: 16px; font-style: italic; margin-bottom: 20px;">
                                "{{ $testimonial->quote }}"</p>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div
                                    style="width: 50px; height: 50px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; color: rgba(70, 90, 40, 1); font-weight: 700;">
                                    {{ substr($testimonial->name, 0, 1) }}</div>
                                <div>
                                    <h4 style="margin-bottom: 5px;">{{ $testimonial->name }}</h4>
                                    <p style="font-size: 14px; opacity: 0.9;">{{ $testimonial->university }} -
                                        {{ $testimonial->program }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Top Universities Section -->
        <section class="section" style="padding: 80px 20px; background: rgba(70, 90, 40, 1); color: white;">
            <div class="container" style="max-width: 1200px; margin: 0 auto;">
                <h2 class="section-title"
                    style="text-align: center; font-size: 36px; font-weight: 700; margin-bottom: 50px; color: white;">Dive
                    Into The Top Universities</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                    @foreach ($topUniversities ?? [] as $university)
                        <div
                            style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            {{-- <img src="{{ $university->cover_image ?? 'https://via.placeholder.com/400x200' }}"
                                alt="{{ $university->name }}" style="width: 100%; height: 200px; object-fit: cover;"> --}}
                                  @if($university->cover_image)
                            <img src="{{ asset($university->cover_image) }}" alt="{{ $university->name }}" class="university-card-image">
                        @else
                            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, rgba(70, 90, 40, 1) 0%, #667eea 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-university" style="font-size: 64px; color: white;"></i>
                            </div>
                        @endif
                            <div style="padding: 20px;">
                                <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 10px; color: #333;">
                                    {{ $university->name }}</h3>
                                <p style="color: #666; margin-bottom: 15px;">{{ $university->city->name ?? 'N/A' }}</p>
                                <a href="/universities/{{ $university->id }}" class="text-light"
                                    style="display: inline-block; padding: 10px 25px; background: rgba(70, 90, 40, 1); color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">View
                                    University</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Scholarships Section -->
        <section class="section" style="padding: 80px 20px; background: white;">
            <div class="container" style="max-width: 1200px; margin: 0 auto;">
                <h2 class="section-title"
                    style="text-align: center; font-size: 36px; font-weight: 700; margin-bottom: 50px; color: #333;">
                    Scholarship for You</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                    @foreach ($scholarships ?? [] as $scholarship)
                        <div
                            style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); position: relative;">
                            <span
                                style="position: absolute; top: 15px; right: 15px; background: #28a745; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600;">New</span>
                            <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 10px; color: #333;">
                                {{ $scholarship->name }}</h3>
                            <p style="color: #666; margin-bottom: 10px;">{{ $scholarship->university->name ?? 'N/A' }}</p>
                            <div style="font-size: 24px; font-weight: 700; color: rgba(70, 90, 40, 1); margin: 15px 0;">
                                @if ($scholarship->amount_type === 'fixed')
                                    ${{ number_format($scholarship->amount, 0) }}
                                @elseif($scholarship->amount_type === 'percentage')
                                    {{ $scholarship->percentage }}% Off
                                @else
                                    Full Scholarship
                                @endif
                            </div>
                            <a href="/scholarships/{{ $scholarship->id }}"
                                style="display: inline-block; margin-top: 15px; padding: 12px 30px; background: rgba(70, 90, 40, 1); color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">Apply
                                Now</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Apply to Universities in Single Click Section -->
        <section class="section"
            style="padding: 100px 20px; background: linear-gradient(to bottom, rgba(70, 90, 40, 0.09) 0%, #e8f5e9 100%); position: relative; overflow: hidden;">
            <div class="container" style="max-width: 1200px; margin: 0 auto;">
                <div
                    style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 60px; align-items: center; position: relative; z-index: 1;">
                    <!-- Left Side - Text and CTA -->
                    <div>
                        <h2
                            style="font-size: 48px; font-weight: 700; color: #2c3e50; margin-bottom: 25px; line-height: 1.2;">
                            Apply to universities in single click</h2>
                        <p style="font-size: 20px; color: #5c6c7d; margin-bottom: 40px; line-height: 1.6;">Complete your
                            study abroad application process and apply to multiple universities with a single click.</p>
                        <a href="/applications"
                            style="display: inline-block; background: #20b2aa; color: white; padding: 16px 40px; border-radius: 8px; text-decoration: none; font-size: 18px; font-weight: 600; transition: all 0.3s; box-shadow: 0 4px 15px rgba(32, 178, 170, 0.3);">
                            Apply now
                        </a>
                    </div>

                    <!-- Right Side - UI Mockups -->
                    <div style="position: relative; height: 500px;">
                        <!-- Main UI Frame -->
                        <div
                            style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); width: 350px; height: 450px; background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); opacity: 0.95;">
                            <!-- Header Bar -->
                            <div style="height: 40px; background: #f5f5f5; border-radius: 8px; margin-bottom: 20px;"></div>
                            <!-- Input Fields -->
                            <div style="margin-bottom: 15px;">
                                <div style="height: 50px; background: #f5f5f5; border-radius: 8px; margin-bottom: 12px;">
                                </div>
                                <div style="height: 50px; background: #f5f5f5; border-radius: 8px; margin-bottom: 12px;">
                                </div>
                                <div style="height: 80px; background: #f5f5f5; border-radius: 8px; margin-bottom: 12px;">
                                </div>
                            </div>
                            <!-- Content Sections -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 15px;">
                                <div style="height: 100px; background: #f5f5f5; border-radius: 8px;"></div>
                                <div style="height: 100px; background: #f5f5f5; border-radius: 8px;"></div>
                            </div>
                            <div style="height: 60px; background: #f5f5f5; border-radius: 8px;"></div>
                        </div>

                        <!-- University Card Overlay -->
                        <div
                            style="position: absolute; left: 0; top: 20%; width: 280px; height: 180px; background: white; border-radius: 16px; padding: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.2); z-index: 2;">
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                                <div
                                    style="width: 50px; height: 50px; background: #28a745; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 20px;">
                                    CSU</div>
                                <div style="flex: 1;">
                                    <div style="font-size: 16px; font-weight: 600; color: #2c3e50; margin-bottom: 4px;">
                                        Colorado State University</div>
                                    <div style="font-size: 14px; color: #666;">Masters in Computer Science</div>
                                </div>
                            </div>
                            <div
                                style="display: flex; align-items: center; gap: 8px; margin-bottom: 15px; font-size: 13px; color: #666;">
                                <span style="color: #dc3545;">●</span>
                                <span>Deadline: 30 Dec, 2020</span>
                            </div>
                            <!-- Progress Bar -->
                            <div style="margin-bottom: 10px;">
                                <div style="display: flex; gap: 4px; margin-bottom: 8px;">
                                    <div style="flex: 1; height: 6px; background: #ffc107; border-radius: 3px;"></div>
                                    <div style="flex: 1; height: 6px; background: #e0e0e0; border-radius: 3px;"></div>
                                    <div style="flex: 1; height: 6px; background: #e0e0e0; border-radius: 3px;"></div>
                                    <div style="flex: 1; height: 6px; background: #e0e0e0; border-radius: 3px;"></div>
                                </div>
                                <div style="display: flex; gap: 4px; font-size: 10px; color: #666;">
                                    <div style="flex: 1; text-align: center; color: #ffc107; font-weight: 600;">
                                        Documentation</div>
                                    <div style="flex: 1; text-align: center;">Acceptance</div>
                                    <div style="flex: 1; text-align: center;">Financial</div>
                                    <div style="flex: 1; text-align: center;">VISA</div>
                                </div>
                            </div>
                            <div style="height: 4px; background: #ffc107; border-radius: 2px; width: 100%;"></div>
                        </div>

                        <!-- Documentation Checklist Overlay -->
                        <div
                            style="position: absolute; right: 50px; top: 10%; width: 240px; background: white; border-radius: 16px; padding: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.2); z-index: 3; border-right: 4px solid #ffc107;">
                            <div
                                style="position: absolute; right: -4px; top: 20px; background: #ffc107; color: white; padding: 6px 12px; border-radius: 4px 0 0 4px; font-size: 11px; font-weight: 600; writing-mode: vertical-rl; text-orientation: mixed;">
                                Documentation</div>
                            <div style="font-size: 14px; font-weight: 600; color: #2c3e50; margin-bottom: 15px;">Checklist
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="color: #28a745; font-size: 18px;">✓</span>
                                    <span style="font-size: 13px; color: #333;">Add additional details</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="color: #28a745; font-size: 18px;">✓</span>
                                    <span style="font-size: 13px; color: #333;">Upload documents</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="color: #28a745; font-size: 18px;">✓</span>
                                    <span style="font-size: 13px; color: #333;">Create SOP/Essay</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="color: #28a745; font-size: 18px;">✓</span>
                                    <span style="font-size: 13px; color: #333;">Pay application fee (89 USD)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Get Prior Information About Section -->
        <section class="section" style="padding: 80px 20px; background: white;">
            <div class="container" style="max-width: 1200px; margin: 0 auto;">
                <h2 style="font-size: 42px; font-weight: 700; color: rgba(70, 90, 40, 1); margin-bottom: 60px; text-align: left;">Get
                    prior information about</h2>
                <div class="info-cards-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
                    <!-- Card 1: Accommodation -->
                    <div class="info-card"
                        style="background: linear-gradient(135deg, rgba(70, 90, 40, 0.09) 0%, rgba(70, 90, 40, 0.16) 100%); border-radius: 16px; padding: 30px; transition: all 0.3s; cursor: pointer; position: relative; overflow: hidden;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(70, 90, 40, 0.2)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(70, 90, 40, 0.1); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div
                                style="width: 60px; height: 60px; background: rgba(70, 90, 40, 1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                <i class="fas fa-home" style="font-size: 28px; color: white;"></i>
                            </div>
                            <h3 style="font-size: 22px; font-weight: 600; color: rgba(70, 90, 40, 1); margin-bottom: 12px;">
                                Accommodation</h3>
                            <p style="font-size: 15px; color: #5c6c7d; margin-bottom: 20px; line-height: 1.5;">Find your
                                home away from home, where comfort meets convenience</p>
                            <div style="display: flex; align-items: center; color: rgba(70, 90, 40, 1); font-weight: 600;">
                                <span style="font-size: 14px;">Learn more</span>
                                <i class="fas fa-arrow-right" style="margin-left: 10px; font-size: 14px;"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Travel -->
                    <div class="info-card"
                        style="background: linear-gradient(135deg, rgba(70, 90, 40, 0.09) 0%, rgba(70, 90, 40, 0.16) 100%); border-radius: 16px; padding: 30px; transition: all 0.3s; cursor: pointer; position: relative; overflow: hidden;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(70, 90, 40, 0.2)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(70, 90, 40, 0.1); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div
                                style="width: 60px; height: 60px; background: rgba(70, 90, 40, 1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                <i class="fas fa-suitcase" style="font-size: 28px; color: white;"></i>
                            </div>
                            <h3 style="font-size: 22px; font-weight: 600; color: rgba(70, 90, 40, 1); margin-bottom: 12px;">Travel</h3>
                            <p style="font-size: 15px; color: #5c6c7d; margin-bottom: 20px; line-height: 1.5;">Embark on
                                your educational journey with stress-free travel arrangements</p>
                            <div style="display: flex; align-items: center; color: rgba(70, 90, 40, 1); font-weight: 600;">
                                <span style="font-size: 14px;">Learn more</span>
                                <i class="fas fa-arrow-right" style="margin-left: 10px; font-size: 14px;"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Forex -->
                    <div class="info-card"
                        style="background: linear-gradient(135deg, rgba(70, 90, 40, 0.09) 0%, rgba(70, 90, 40, 0.16) 100%); border-radius: 16px; padding: 30px; transition: all 0.3s; cursor: pointer; position: relative; overflow: hidden;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(70, 90, 40, 0.2)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(70, 90, 40, 0.1); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div
                                style="width: 60px; height: 60px; background: rgba(70, 90, 40, 1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                <div
                                    style="width: 40px; height: 40px; border: 3px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative;">
                                    <div
                                        style="position: absolute; left: -8px; width: 0; height: 0; border-top: 6px solid transparent; border-bottom: 6px solid transparent; border-right: 8px solid white;">
                                    </div>
                                    <span style="font-size: 12px; font-weight: 700; color: white;">FX</span>
                                    <div
                                        style="position: absolute; right: -8px; width: 0; height: 0; border-top: 6px solid transparent; border-bottom: 6px solid transparent; border-left: 8px solid white;">
                                    </div>
                                </div>
                            </div>
                            <h3 style="font-size: 22px; font-weight: 600; color: rgba(70, 90, 40, 1); margin-bottom: 12px;">Forex</h3>
                            <p style="font-size: 15px; color: #5c6c7d; margin-bottom: 20px; line-height: 1.5;">Navigate
                                currency transitions effortlessly with tailored forex solutions</p>
                            <div style="display: flex; align-items: center; color: rgba(70, 90, 40, 1); font-weight: 600;">
                                <span style="font-size: 14px;">Learn more</span>
                                <i class="fas fa-arrow-right" style="margin-left: 10px; font-size: 14px;"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Education Loan -->
                    <div class="info-card"
                        style="background: linear-gradient(135deg, rgba(70, 90, 40, 0.09) 0%, rgba(70, 90, 40, 0.16) 100%); border-radius: 16px; padding: 30px; transition: all 0.3s; cursor: pointer; position: relative; overflow: hidden;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(70, 90, 40, 0.2)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(70, 90, 40, 0.1); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div
                                style="width: 60px; height: 60px; background: rgba(70, 90, 40, 1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; position: relative;">
                                <i class="fas fa-graduation-cap"
                                    style="font-size: 24px; color: white; position: absolute; top: 8px;"></i>
                                <i class="fas fa-dollar-sign"
                                    style="font-size: 20px; color: white; position: absolute; bottom: 8px;"></i>
                            </div>
                            <h3 style="font-size: 22px; font-weight: 600; color: rgba(70, 90, 40, 1); margin-bottom: 12px;">Education
                                Loan</h3>
                            <p style="font-size: 15px; color: #5c6c7d; margin-bottom: 20px; line-height: 1.5;">Turn
                                aspirations into reality with flexible and student-friendly loan options</p>
                            <div style="display: flex; align-items: center; color: rgba(70, 90, 40, 1); font-weight: 600;">
                                <span style="font-size: 14px;">Learn more</span>
                                <i class="fas fa-arrow-right" style="margin-left: 10px; font-size: 14px;"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5: Communication -->
                    <div class="info-card"
                        style="background: linear-gradient(135deg, rgba(70, 90, 40, 0.09) 0%, rgba(70, 90, 40, 0.16) 100%); border-radius: 16px; padding: 30px; transition: all 0.3s; cursor: pointer; position: relative; overflow: hidden;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(70, 90, 40, 0.2)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(70, 90, 40, 0.1); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div
                                style="width: 60px; height: 60px; background: rgba(70, 90, 40, 1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; position: relative;">
                                <div style="position: relative; width: 40px; height: 30px;">
                                    <div
                                        style="position: absolute; left: 0; top: 0; width: 20px; height: 20px; background: white; border-radius: 8px 8px 8px 2px; opacity: 0.8;">
                                    </div>
                                    <div
                                        style="position: absolute; right: 0; bottom: 0; width: 20px; height: 20px; background: white; border-radius: 8px 8px 2px 8px; opacity: 0.8;">
                                    </div>
                                    <div
                                        style="position: absolute; left: 4px; top: 4px; width: 4px; height: 4px; background: rgba(70, 90, 40, 1); border-radius: 50%;">
                                    </div>
                                    <div
                                        style="position: absolute; left: 8px; top: 4px; width: 4px; height: 4px; background: rgba(70, 90, 40, 1); border-radius: 50%;">
                                    </div>
                                    <div
                                        style="position: absolute; left: 12px; top: 4px; width: 4px; height: 4px; background: rgba(70, 90, 40, 1); border-radius: 50%;">
                                    </div>
                                    <div
                                        style="position: absolute; right: 4px; bottom: 4px; width: 4px; height: 4px; background: rgba(70, 90, 40, 1); border-radius: 50%;">
                                    </div>
                                    <div
                                        style="position: absolute; right: 8px; bottom: 4px; width: 4px; height: 4px; background: rgba(70, 90, 40, 1); border-radius: 50%;">
                                    </div>
                                    <div
                                        style="position: absolute; right: 12px; bottom: 4px; width: 4px; height: 4px; background: rgba(70, 90, 40, 1); border-radius: 50%;">
                                    </div>
                                </div>
                            </div>
                            <h3 style="font-size: 22px; font-weight: 600; color: rgba(70, 90, 40, 1); margin-bottom: 12px;">
                                Communication</h3>
                            <p style="font-size: 15px; color: #5c6c7d; margin-bottom: 20px; line-height: 1.5;">Stay
                                connected globally to loved ones while you pursue your dreams</p>
                            <div style="display: flex; align-items: center; color: rgba(70, 90, 40, 1); font-weight: 600;">
                                <span style="font-size: 14px;">Learn more</span>
                                <i class="fas fa-arrow-right" style="margin-left: 10px; font-size: 14px;"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6: Insurance -->
                    <div class="info-card"
                        style="background: linear-gradient(135deg, rgba(70, 90, 40, 0.09) 0%, rgba(70, 90, 40, 0.16) 100%); border-radius: 16px; padding: 30px; transition: all 0.3s; cursor: pointer; position: relative; overflow: hidden;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(70, 90, 40, 0.2)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(70, 90, 40, 0.1); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div
                                style="width: 60px; height: 60px; background: rgba(70, 90, 40, 1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                <i class="fas fa-shield-alt" style="font-size: 28px; color: white;"></i>
                            </div>
                            <h3 style="font-size: 22px; font-weight: 600; color: rgba(70, 90, 40, 1); margin-bottom: 12px;">Insurance
                            </h3>
                            <p style="font-size: 15px; color: #5c6c7d; margin-bottom: 20px; line-height: 1.5;">Guard your
                                educational path with peace of mind</p>
                            <div style="display: flex; align-items: center; color: rgba(70, 90, 40, 1); font-weight: 600;">
                                <span style="font-size: 14px;">Learn more</span>
                                <i class="fas fa-arrow-right" style="margin-left: 10px; font-size: 14px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- App Download Section -->
        <section class="section"
            style="padding: 80px 20px; background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); border-radius: 30px; margin: 60px 20px;">
            <div class="container" style="max-width: 1200px; margin: 0 auto;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;">
                    <!-- Left Side - Text and Download Buttons -->
                    <div>
                        <h2
                            style="font-size: 42px; font-weight: 700; color: white; margin-bottom: 20px; line-height: 1.2;">
                            Get your hands on our app</h2>
                        <p style="font-size: 18px; color: rgba(255,255,255,0.9); margin-bottom: 10px;">experience a hassle
                            free onboard</p>
                        <p style="font-size: 18px; color: rgba(255,255,255,0.9); margin-bottom: 40px;">to all the features
                        </p>

                        <!-- App Store Buttons -->
                        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <a href="{{ $settings['app_link'] ?? '#' }}" target="_blank"
                                style="display: inline-block; text-decoration: none;">
                                <div
                                    style="background: white; padding: 12px 25px; border-radius: 8px; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;">
                                    <div
                                        style="width: 30px; height: 30px; background: #34a853; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                        <div
                                            style="width: 0; height: 0; border-left: 8px solid white; border-top: 5px solid transparent; border-bottom: 5px solid transparent; margin-left: 2px;">
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 10px; color: #666; line-height: 1;">GET IT ON</div>
                                        <div style="font-size: 16px; font-weight: 600; color: #333;">Google Play</div>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ $settings['ios_app_link'] ?? '#' }}" target="_blank"
                                style="display: inline-block; text-decoration: none;">
                                <div
                                    style="background: white; padding: 12px 25px; border-radius: 8px; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;">
                                    <div
                                        style="width: 30px; height: 30px; background: #000; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                                            <path
                                                d="M17.05 20.28c-.98.95-2.05.88-3.08.4-1.09-.5-2.08-.48-3.24 0-1.44.62-2.2.44-3.06-.4C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div style="font-size: 10px; color: #666; line-height: 1;">Download on the</div>
                                        <div style="font-size: 16px; font-weight: 600; color: #333;">App Store</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Right Side - Phone Mockups -->
                    <div style="position: relative; height: 500px;">
                        <!-- Front Phone -->
                        <div
                            style="position: absolute; right: 0; top: 0; width: 200px; height: 400px; background: white; border-radius: 25px; padding: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); z-index: 2; transform: rotate(-5deg);">
                            <div
                                style="background: rgba(70, 90, 40, 0.09); border-radius: 20px; height: 100%; padding: 15px; overflow: hidden;">
                                <!-- Phone Header -->
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-size: 12px; color: #333;">
                                    <span>9:41</span>
                                    <span style="font-weight: 600;">Hi John</span>
                                    <div style="display: flex; gap: 3px;">
                                        <div style="width: 4px; height: 4px; background: #333; border-radius: 50%;"></div>
                                        <div style="width: 4px; height: 4px; background: #333; border-radius: 50%;"></div>
                                        <div style="width: 4px; height: 4px; background: #333; border-radius: 50%;"></div>
                                    </div>
                                </div>
                                <!-- Phone Content -->
                                <div>
                                    <h3 style="font-size: 18px; font-weight: 700; color: rgba(70, 90, 40, 1); margin-bottom: 15px;">
                                        Start Your Journey</h3>
                                    <button
                                        style="background: rgba(70, 90, 40, 1); color: white; border: none; padding: 10px 20px; border-radius: 8px; width: 100%; margin-bottom: 20px; font-size: 14px; font-weight: 600;">Create
                                        my Profile</button>
                                    <div style="margin-bottom: 15px;">
                                        <div style="font-size: 12px; color: #666; margin-bottom: 8px;">Quick Access</div>
                                        <div
                                            style="height: 40px; background: rgba(70, 90, 40, 0.1); border-radius: 8px; margin-bottom: 8px;">
                                        </div>
                                        <div style="height: 40px; background: rgba(70, 90, 40, 0.1); border-radius: 8px;">
                                        </div>
                                    </div>
                                    <div
                                        style="background: #ffc107; padding: 10px; border-radius: 8px; font-size: 10px; text-align: center; color: #333; font-weight: 600;">
                                        iSchoolConnect STUDY ABROAD FEST
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Back Phone -->
                        <div
                            style="position: absolute; right: 80px; top: 50px; width: 200px; height: 400px; background: white; border-radius: 25px; padding: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 1; transform: rotate(5deg);">
                            <div
                                style="background: #f3e5f5; border-radius: 20px; height: 100%; padding: 15px; overflow: hidden;">
                                <!-- Phone Header -->
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-size: 12px; color: #333;">
                                    <span>9:41</span>
                                    <div style="display: flex; gap: 3px;">
                                        <div style="width: 4px; height: 4px; background: #333; border-radius: 50%;"></div>
                                        <div style="width: 4px; height: 4px; background: #333; border-radius: 50%;"></div>
                                    </div>
                                </div>
                                <!-- Phone Content -->
                                <div>
                                    <h3 style="font-size: 18px; font-weight: 700; color: #764ba2; margin-bottom: 20px;">
                                        Your Journey</h3>
                                    <div style="margin-bottom: 15px;">
                                        <div style="font-size: 14px; color: #333; margin-bottom: 10px; font-weight: 600;">
                                            Discover Programs</div>
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                            <div
                                                style="width: 16px; height: 16px; border: 2px solid #764ba2; border-radius: 4px;">
                                            </div>
                                            <div
                                                style="height: 12px; background: rgba(118,75,162,0.2); border-radius: 4px; flex: 1;">
                                            </div>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                            <div
                                                style="width: 16px; height: 16px; border: 2px solid #764ba2; border-radius: 4px;">
                                            </div>
                                            <div
                                                style="height: 12px; background: rgba(118,75,162,0.2); border-radius: 4px; flex: 1;">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 14px; color: #333; margin-bottom: 10px; font-weight: 600;">
                                            Prepare and Apply</div>
                                        <div style="height: 60px; background: rgba(118,75,162,0.1); border-radius: 8px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section - Matching Image Design -->
        @if (isset($faqs) && count($faqs) > 0)
            <section class="section" style="padding: 80px 20px; background: white;">
                <div class="container" style="max-width: 900px; margin: 0 auto;">
                    <h2
                        style="text-align: center; font-size: 42px; font-weight: 700; margin-bottom: 60px; color: #2c3e50;">
                        Frequently asked questions</h2>
                    <div class="faq-list">
                        @foreach ($faqs as $index => $faq)
                            <div class="faq-item-new"
                                style="border-bottom: 1px solid #e0e0e0; padding: 25px 0; cursor: pointer;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div class="faq-question-new"
                                        style="font-size: 18px; font-weight: 500; color: #2c3e50; flex: 1;">
                                        {{ $faq->title }}</div>
                                    <div class="faq-toggle-icon"
                                        style="font-size: 24px; color: #764ba2; font-weight: 300; margin-left: 20px; transition: transform 0.3s;">
                                        +</div>
                                </div>
                                <div class="faq-answer-new"
                                    style="font-size: 16px; color: #666; margin-top: 15px; display: none; line-height: 1.6; padding-right: 40px;">
                                    {{ $faq->description }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Application FAQ Section - Before Footer -->
        <section class="section" style="padding: 100px 20px; background: white;">
            <div class="container" style="max-width: 900px; margin: 0 auto;">
                <h2 style="text-align: center; font-size: 42px; font-weight: 700; margin-bottom: 60px; color: rgba(70, 90, 40, 1);">
                    Frequently asked questions</h2>
                <div class="application-faq-list">
                    <!-- FAQ Item 1 -->
                    <div class="application-faq-item"
                        style="border-bottom: 1px solid #e0e0e0; padding: 25px 0; cursor: pointer;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="application-faq-question"
                                style="font-size: 18px; font-weight: 500; color: #2c3e50; flex: 1;">What is UniGate?</div>
                            <div class="application-faq-toggle-icon"
                                style="font-size: 24px; color: rgba(70, 90, 40, 1); font-weight: 300; margin-left: 20px; transition: transform 0.3s;">
                                +</div>
                        </div>
                        <div class="application-faq-answer"
                            style="font-size: 16px; color: #666; margin-top: 15px; display: none; line-height: 1.6; padding-right: 40px;">
                            UniGate is a comprehensive online platform that allows students to apply to multiple
                            universities worldwide with a single click. Our platform simplifies the study abroad application
                            process, helping you discover programs, complete applications, and track your application status
                            all in one place.
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="application-faq-item"
                        style="border-bottom: 1px solid #e0e0e0; padding: 25px 0; cursor: pointer;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="application-faq-question"
                                style="font-size: 18px; font-weight: 500; color: #2c3e50; flex: 1;">How do I apply to
                                universities online through UniGate?</div>
                            <div class="application-faq-toggle-icon"
                                style="font-size: 24px; color: rgba(70, 90, 40, 1); font-weight: 300; margin-left: 20px; transition: transform 0.3s;">
                                +</div>
                        </div>
                        <div class="application-faq-answer"
                            style="font-size: 16px; color: #666; margin-top: 15px; display: none; line-height: 1.6; padding-right: 40px;">
                            Applying through UniGate is simple! First, create your profile and complete your academic
                            information. Then, browse through our extensive database of universities and programs. Once you
                            find programs you're interested in, you can apply to multiple universities with just a single
                            click. Our platform guides you through each step, from document uploads to application fee
                            payments.
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="application-faq-item"
                        style="border-bottom: 1px solid #e0e0e0; padding: 25px 0; cursor: pointer;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="application-faq-question"
                                style="font-size: 18px; font-weight: 500; color: #2c3e50; flex: 1;">Can I track my
                                application status in real-time?</div>
                            <div class="application-faq-toggle-icon"
                                style="font-size: 24px; color: rgba(70, 90, 40, 1); font-weight: 300; margin-left: 20px; transition: transform 0.3s;">
                                +</div>
                        </div>
                        <div class="application-faq-answer"
                            style="font-size: 16px; color: #666; margin-top: 15px; display: none; line-height: 1.6; padding-right: 40px;">
                            Yes! UniGate provides a comprehensive application tracking system. Once you submit your
                            applications, you can track the status of each application in real-time. You'll receive updates
                            on document verification, application review, acceptance status, and any additional
                            requirements. All updates are available in your dashboard, and you'll also receive email
                            notifications for important milestones.
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="application-faq-item"
                        style="border-bottom: 1px solid #e0e0e0; padding: 25px 0; cursor: pointer;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="application-faq-question"
                                style="font-size: 18px; font-weight: 500; color: #2c3e50; flex: 1;">How many universities
                                can I apply to?</div>
                            <div class="application-faq-toggle-icon"
                                style="font-size: 24px; color: rgba(70, 90, 40, 1); font-weight: 300; margin-left: 20px; transition: transform 0.3s;">
                                +</div>
                        </div>
                        <div class="application-faq-answer"
                            style="font-size: 16px; color: #666; margin-top: 15px; display: none; line-height: 1.6; padding-right: 40px;">
                            There's no limit to the number of universities you can apply to through UniGate! You can apply
                            to as many universities and programs as you wish. Our platform makes it easy to manage multiple
                            applications simultaneously, and you can track all of them from your unified dashboard.
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="application-faq-item"
                        style="border-bottom: 1px solid #e0e0e0; padding: 25px 0; cursor: pointer;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="application-faq-question"
                                style="font-size: 18px; font-weight: 500; color: #2c3e50; flex: 1;">What documents do I
                                need to apply?</div>
                            <div class="application-faq-toggle-icon"
                                style="font-size: 24px; color: rgba(70, 90, 40, 1); font-weight: 300; margin-left: 20px; transition: transform 0.3s;">
                                +</div>
                        </div>
                        <div class="application-faq-answer"
                            style="font-size: 16px; color: #666; margin-top: 15px; display: none; line-height: 1.6; padding-right: 40px;">
                            The required documents vary by university and program, but typically include: academic
                            transcripts, degree certificates, English proficiency test scores (IELTS/TOEFL), Statement of
                            Purpose (SOP), Letters of Recommendation (LOR), passport copy, and financial documents. Our
                            platform will show you the specific requirements for each university you're applying to, and you
                            can upload all documents directly through your dashboard.
                        </div>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="application-faq-item"
                        style="border-bottom: 1px solid #e0e0e0; padding: 25px 0; cursor: pointer;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="application-faq-question"
                                style="font-size: 18px; font-weight: 500; color: #2c3e50; flex: 1;">How long does the
                                application process take?</div>
                            <div class="application-faq-toggle-icon"
                                style="font-size: 24px; color: rgba(70, 90, 40, 1); font-weight: 300; margin-left: 20px; transition: transform 0.3s;">
                                +</div>
                        </div>
                        <div class="application-faq-answer"
                            style="font-size: 16px; color: #666; margin-top: 15px; display: none; line-height: 1.6; padding-right: 40px;">
                            The application process timeline depends on the university and program. Generally, once you
                            submit your complete application with all required documents, universities typically respond
                            within 4-8 weeks. However, some programs may have faster or slower response times. You can track
                            the expected response timeline for each application in your dashboard, and we'll notify you as
                            soon as any updates are available.
                        </div>
                    </div>

                    <!-- FAQ Item 7 -->
                    <div class="application-faq-item"
                        style="border-bottom: 1px solid #e0e0e0; padding: 25px 0; cursor: pointer;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="application-faq-question"
                                style="font-size: 18px; font-weight: 500; color: #2c3e50; flex: 1;">Is there a fee for
                                using UniGate to apply?</div>
                            <div class="application-faq-toggle-icon"
                                style="font-size: 24px; color: rgba(70, 90, 40, 1); font-weight: 300; margin-left: 20px; transition: transform 0.3s;">
                                +</div>
                        </div>
                        <div class="application-faq-answer"
                            style="font-size: 16px; color: #666; margin-top: 15px; display: none; line-height: 1.6; padding-right: 40px;">
                            UniGate offers both free and premium plans. You can create an account and browse universities
                            for free. Application fees are set by individual universities and vary by program. UniGate
                            provides transparent pricing, and you can see all fees before submitting your application. We
                            also offer package deals that can help you save money when applying to multiple universities.
                        </div>
                    </div>

                    <!-- FAQ Item 8 -->
                    <div class="application-faq-item"
                        style="border-bottom: 1px solid #e0e0e0; padding: 25px 0; cursor: pointer;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="application-faq-question"
                                style="font-size: 18px; font-weight: 500; color: #2c3e50; flex: 1;">Can I edit my
                                application after submission?</div>
                            <div class="application-faq-toggle-icon"
                                style="font-size: 24px; color: rgba(70, 90, 40, 1); font-weight: 300; margin-left: 20px; transition: transform 0.3s;">
                                +</div>
                        </div>
                        <div class="application-faq-answer"
                            style="font-size: 16px; color: #666; margin-top: 15px; display: none; line-height: 1.6; padding-right: 40px;">
                            Once an application is submitted, it's sent to the university for review. However, you can still
                            update certain documents or information if the university requests changes. You'll receive
                            notifications if any updates are needed, and you can upload revised documents directly through
                            your dashboard. For major changes, you may need to contact the university directly or create a
                            new application.
                        </div>
                    </div>

                    <!-- FAQ Item 9 -->
                    <div class="application-faq-item"
                        style="border-bottom: 1px solid #e0e0e0; padding: 25px 0; cursor: pointer;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="application-faq-question"
                                style="font-size: 18px; font-weight: 500; color: #2c3e50; flex: 1;">What happens after I
                                receive an acceptance letter?</div>
                            <div class="application-faq-toggle-icon"
                                style="font-size: 24px; color: rgba(70, 90, 40, 1); font-weight: 300; margin-left: 20px; transition: transform 0.3s;">
                                +</div>
                        </div>
                        <div class="application-faq-answer"
                            style="font-size: 16px; color: #666; margin-top: 15px; display: none; line-height: 1.6; padding-right: 40px;">
                            Congratulations! Once you receive an acceptance letter, UniGate will guide you through the next
                            steps, which typically include: accepting the offer, submitting financial documentation,
                            applying for a student visa, arranging accommodation, and preparing for your journey. Our
                            platform provides resources and support for each of these steps to ensure a smooth transition to
                            your chosen university.
                        </div>
                    </div>

                    <!-- FAQ Item 10 -->
                    <div class="application-faq-item"
                        style="border-bottom: 1px solid #e0e0e0; padding: 25px 0; cursor: pointer;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="application-faq-question"
                                style="font-size: 18px; font-weight: 500; color: #2c3e50; flex: 1;">Do you provide support
                                if I have questions during the application process?</div>
                            <div class="application-faq-toggle-icon"
                                style="font-size: 24px; color: rgba(70, 90, 40, 1); font-weight: 300; margin-left: 20px; transition: transform 0.3s;">
                                +</div>
                        </div>
                        <div class="application-faq-answer"
                            style="font-size: 16px; color: #666; margin-top: 15px; display: none; line-height: 1.6; padding-right: 40px;">
                            Absolutely! UniGate provides comprehensive support throughout your application journey. You can
                            reach our support team via email, live chat, or phone. We also offer detailed guides, video
                            tutorials, and a knowledge base to help answer common questions. Our team is available to assist
                            with technical issues, application questions, document verification, and any other concerns you
                            may have.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('script')
    <script>
        // Handle search form submission
        document.querySelector('.start-journey-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const degree = document.getElementById('degree-select').value;
            const discipline = document.getElementById('discipline-select').value;
            const destination = document.getElementById('destination-select').value;

            // Redirect to universities page with filters
            const params = new URLSearchParams();
            if (degree) params.append('degree', degree);
            if (discipline) params.append('interest', discipline);
            if (destination) params.append('city', destination);

            window.location.href = '/universities?' + params.toString();
        });

        // FAQ Toggle - New Design
        document.querySelectorAll('.faq-item-new').forEach(item => {
            item.addEventListener('click', function() {
                const answer = this.querySelector('.faq-answer-new');
                const icon = this.querySelector('.faq-toggle-icon');
                const isActive = answer.style.display === 'block';

                // Close all other FAQs
                document.querySelectorAll('.faq-item-new').forEach(otherItem => {
                    if (otherItem !== this) {
                        otherItem.querySelector('.faq-answer-new').style.display = 'none';
                        otherItem.querySelector('.faq-toggle-icon').textContent = '+';
                        otherItem.querySelector('.faq-toggle-icon').style.transform =
                        'rotate(0deg)';
                    }
                });

                // Toggle current FAQ
                if (isActive) {
                    answer.style.display = 'none';
                    icon.textContent = '+';
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    answer.style.display = 'block';
                    icon.textContent = '−';
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });

        // Application FAQ Toggle
        document.querySelectorAll('.application-faq-item').forEach(item => {
            item.addEventListener('click', function() {
                const answer = this.querySelector('.application-faq-answer');
                const icon = this.querySelector('.application-faq-toggle-icon');
                const isActive = answer.style.display === 'block';

                // Close all other Application FAQs
                document.querySelectorAll('.application-faq-item').forEach(otherItem => {
                    if (otherItem !== this) {
                        otherItem.querySelector('.application-faq-answer').style.display = 'none';
                        otherItem.querySelector('.application-faq-toggle-icon').textContent = '+';
                        otherItem.querySelector('.application-faq-toggle-icon').style.transform =
                            'rotate(0deg)';
                    }
                });

                // Toggle current FAQ
                if (isActive) {
                    answer.style.display = 'none';
                    icon.textContent = '+';
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    answer.style.display = 'block';
                    icon.textContent = '−';
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });
    </script>
@endsection
