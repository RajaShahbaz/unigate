<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Portal') — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sp-primary: #1a936f;
            --sp-primary-dark: #0f6b52;
        }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f4f7f6; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--sp-primary) 0%, var(--sp-primary-dark) 100%);
            box-shadow: 0 0.15rem 1.75rem rgba(0,0,0,.12);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.88);
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.1);
            border-left-color: #fff;
        }
        .sidebar .nav-link i { width: 1.5rem; }
        .topbar {
            height: 4rem;
            background: #fff;
            box-shadow: 0 0.1rem 0.75rem rgba(0,0,0,.06);
        }
        .content-wrapper { min-height: calc(100vh - 4rem); }
    </style>
    @stack('styles')
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar px-0">
            <div class="position-sticky pt-3 pb-4">
                <div class="px-3 pb-3 border-bottom border-white border-opacity-25">
                    <h5 class="text-white mb-0"><i class="fas fa-user-graduate me-2"></i>{{ __('Student Portal') }}</h5>
                    <small class="text-white-50">{{ __('University admission') }}</small>
                </div>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.portal.dashboard') ? 'active' : '' }}" href="{{ route('student.portal.dashboard') }}">
                            <i class="fas fa-gauge-high"></i> {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.portal.education') ? 'active' : '' }}" href="{{ route('student.portal.education') }}">
                            <i class="fas fa-graduation-cap"></i> {{ __('Education') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.portal.interests') ? 'active' : '' }}" href="{{ route('student.portal.interests') }}">
                            <i class="fas fa-bookmark"></i> {{ __('Interests') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.portal.preferred-cities') ? 'active' : '' }}" href="{{ route('student.portal.preferred-cities') }}">
                            <i class="fas fa-city"></i> {{ __('Preferred cities') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.portal.favorites') ? 'active' : '' }}" href="{{ route('student.portal.favorites') }}">
                            <i class="fas fa-heart"></i> {{ __('Favorites') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.portal.notifications*') ? 'active' : '' }}" href="{{ route('student.portal.notifications') }}">
                            <i class="fas fa-bell"></i> {{ __('Notifications') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.portal.account') ? 'active' : '' }}" href="{{ route('student.portal.account') }}">
                            <i class="fas fa-user-cog"></i> {{ __('Account') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.portal.documents') ? 'active' : '' }}" href="{{ route('student.portal.documents') }}">
                            <i class="fas fa-file-lines"></i> {{ __('Documents') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.portal.applications*') ? 'active' : '' }}" href="{{ route('student.portal.applications') }}">
                            <i class="fas fa-file-signature"></i> {{ __('Applications') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.portal.packages') ? 'active' : '' }}" href="{{ route('student.portal.packages') }}">
                            <i class="fas fa-box-open"></i> {{ __('Application packages') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.portal.payments') ? 'active' : '' }}" href="{{ route('student.portal.payments') }}">
                            <i class="fas fa-wallet"></i> {{ __('JazzCash / payments') }}
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link" href="{{ route('universities') }}">
                            <i class="fas fa-building-columns"></i> {{ __('Browse universities') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="post" action="{{ route('student.portal.logout') }}" class="px-3">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-start text-white text-decoration-none w-100 p-0 border-0">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 col-lg-10 ms-sm-auto">
            <div class="topbar d-flex align-items-center px-3 sticky-top">
                <h4 class="mb-0 flex-grow-1">@yield('page-title')</h4>
                <span class="text-muted small me-2">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
            </div>
            <div class="content-wrapper p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
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
