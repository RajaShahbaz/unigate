<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Dashboard') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --up-primary: #1a4f8c;
            --up-primary-dark: #0f3660;
        }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f5f7fb; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--up-primary) 0%, var(--up-primary-dark) 100%);
            box-shadow: 0 0.15rem 1.75rem rgba(0,0,0,.12);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.88);
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.1);
            border-left-color: #fff;
        }
        .topbar {
            height: 4rem;
            background: #fff;
            box-shadow: 0 0.1rem 0.75rem rgba(0,0,0,.06);
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar px-0">
            <div class="position-sticky pt-3 pb-4">
                <div class="px-3 pb-3 border-bottom border-white border-opacity-25">
                    <h5 class="text-white mb-0"><i class="fas fa-user me-2"></i>{{ __('User Dashboard') }}</h5>
                    <small class="text-white-50">{{ __('University admission portal') }}</small>
                </div>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.portal.dashboard') ? 'active' : '' }}" href="{{ route('user.portal.dashboard') }}">
                            <i class="fas fa-gauge-high me-2"></i>{{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.portal.universities') ? 'active' : '' }}" href="{{ route('user.portal.universities') }}">
                            <i class="fas fa-building-columns me-2"></i>{{ __('Universities') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.portal.applications*') ? 'active' : '' }}" href="{{ route('user.portal.applications') }}">
                            <i class="fas fa-file-signature me-2"></i>{{ __('Applications') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.portal.documents') ? 'active' : '' }}" href="{{ route('user.portal.documents') }}">
                            <i class="fas fa-file-lines me-2"></i>{{ __('Documents') }}
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link" href="{{ route('universities') }}">
                            <i class="fas fa-magnifying-glass me-2"></i>{{ __('Public Universities Page') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="post" action="{{ route('user.portal.logout') }}" class="px-3">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-start text-white text-decoration-none w-100 p-0 border-0">
                                <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 col-lg-10 ms-sm-auto">
            <div class="topbar d-flex align-items-center px-3 sticky-top">
                <h4 class="mb-0 flex-grow-1">@yield('page-title')</h4>
                <span class="text-muted small">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
            </div>
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
