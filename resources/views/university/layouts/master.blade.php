<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'University Portal') - {{ config('app.name') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --info-color: #36b9cc;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #fff;
        }

        .sidebar .nav-link i {
            width: 25px;
        }

        .topbar {
            height: 4.375rem;
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
        }

        .stat-card {
            border-left: 4px solid;
        }

        .stat-card.primary { border-left-color: var(--primary-color); }
        .stat-card.success { border-left-color: var(--success-color); }
        .stat-card.warning { border-left-color: var(--warning-color); }
        .stat-card.danger { border-left-color: var(--danger-color); }
        .stat-card.info { border-left-color: var(--info-color); }

        .badge-status {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
        }

        .content-wrapper {
            min-height: calc(100vh - 4.375rem);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block sidebar px-0">
                <div class="position-sticky">
                    <div class="text-center py-4 border-bottom border-white border-opacity-25">
                        <h5 class="text-white mb-0">
                            <i class="fas fa-university"></i>
                            University Portal
                        </h5>
                    </div>
                    <ul class="nav flex-column mt-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('university.dashboard') ? 'active' : '' }}"
                               href="{{ route('university.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('university.applications.*') ? 'active' : '' }}"
                               href="{{ route('university.applications.index') }}">
                                <i class="fas fa-file-alt"></i>
                                Applications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('university.programs.*') ? 'active' : '' }}"
                               href="{{ route('university.programs.index') }}">
                                <i class="fas fa-graduation-cap"></i>
                                Programs/Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('university.gallery.*') ? 'active' : '' }}"
                               href="{{ route('university.gallery.index') }}">
                                <i class="fas fa-images"></i>
                                Gallery
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('university.scholarships.*') ? 'active' : '' }}"
                               href="{{ route('university.scholarships.index') }}">
                                <i class="fas fa-award"></i>
                                Scholarships
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('university.admission-requirements.*') ? 'active' : '' }}"
                               href="{{ route('university.admission-requirements.index') }}">
                                <i class="fas fa-clipboard-check"></i>
                                Admission Requirements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('university.custom-form.*') ? 'active' : '' }}"
                               href="{{ route('university.custom-form.index') }}">
                                <i class="fas fa-th-list"></i>
                                Application Form Builder
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('university.form-fields.*') ? 'active' : '' }}"
                               href="{{ route('university.form-fields.index') }}">
                                <i class="fa fa-file"></i>
                                Custom Fields (Old)
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('university.university-profile') ? 'active' : '' }}"
                               href="{{ route('university.university-profile') }}">
                                <i class="fas fa-building"></i>
                                University Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('university.profile') ? 'active' : '' }}"
                               href="{{ route('university.profile') }}">
                                <i class="fas fa-user-circle"></i>
                                My Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('university.change-password') ? 'active' : '' }}"
                               href="{{ route('university.change-password') }}">
                                <i class="fas fa-key"></i>
                                Change Password
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link" href="{{ route('university.logout') }}">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <!-- Top Bar -->
                <div class="topbar d-flex align-items-center px-3 sticky-top">
                    <div class="flex-grow-1">
                        <h4 class="mb-0 text-gray-800">@yield('page-title', 'Dashboard')</h4>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="me-3 text-gray-600">
                            Welcome, <strong>{{ auth()->guard('university')->user()->name }}</strong>
                        </span>
                        <img src="{{ auth()->guard('university')->user()->profile_image }}"
                             alt="Profile"
                             class="rounded-circle"
                             style="width: 40px; height: 40px; object-fit: cover;">
                    </div>
                </div>

                <!-- Content -->
                <div class="content-wrapper py-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>

