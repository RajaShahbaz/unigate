@extends('user-portal.layouts.master')

@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))

@section('content')
    <p class="text-muted mb-4">{{ __('Welcome to your user dashboard. Manage universities, applications, and documents from one place.') }}</p>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-primary mb-2"><i class="fas fa-building-columns fa-lg"></i></div>
                    <h3 class="h4 mb-0">{{ $stats['universities'] }}</h3>
                    <small class="text-muted">{{ __('Active universities') }}</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-success mb-2"><i class="fas fa-file-signature fa-lg"></i></div>
                    <h3 class="h4 mb-0">{{ $stats['applications'] }}</h3>
                    <small class="text-muted">{{ __('Total applications') }}</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-warning mb-2"><i class="fas fa-hourglass-half fa-lg"></i></div>
                    <h3 class="h4 mb-0">{{ $stats['pending_applications'] }}</h3>
                    <small class="text-muted">{{ __('Pending applications') }}</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-info mb-2"><i class="fas fa-file-lines fa-lg"></i></div>
                    <h3 class="h4 mb-0">{{ $stats['documents'] }}</h3>
                    <small class="text-muted">{{ __('Uploaded documents') }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">{{ __('Quick links') }}</div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('user.portal.universities') }}" class="btn btn-outline-primary">{{ __('Universities') }}</a>
            <a href="{{ route('user.portal.applications') }}" class="btn btn-outline-success">{{ __('Applications') }}</a>
            <a href="{{ route('user.portal.documents') }}" class="btn btn-outline-info">{{ __('Documents') }}</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">{{ __('Recent applications') }}</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>{{ __('Number') }}</th>
                    <th>{{ __('University') }}</th>
                    <th>{{ __('Program') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($recentApplications as $app)
                    <tr>
                        <td>{{ $app->application_number }}</td>
                        <td>{{ $app->university->name ?? '-' }}</td>
                        <td>{{ $app->interest->name ?? '-' }}</td>
                        <td><span class="badge bg-secondary text-uppercase">{{ $app->status }}</span></td>
                        <td><a href="{{ route('user.portal.applications.show', $app->id) }}" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No applications found yet.') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
