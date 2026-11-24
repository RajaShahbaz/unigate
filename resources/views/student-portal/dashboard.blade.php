@extends('student-portal.layouts.master')

@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))

@section('content')
    <p class="text-muted mb-4">{{ __('Overview of your applications and activity.') }}</p>
    <div class="row g-3 mb-4">
        @foreach([
            ['key' => 'total_applications', 'label' => __('Total'), 'icon' => 'fa-layer-group', 'class' => 'primary'],
            ['key' => 'pending', 'label' => __('Pending'), 'icon' => 'fa-hourglass-half', 'class' => 'warning'],
            ['key' => 'draft', 'label' => __('Draft'), 'icon' => 'fa-pen', 'class' => 'secondary'],
            ['key' => 'accepted', 'label' => __('Accepted'), 'icon' => 'fa-circle-check', 'class' => 'success'],
            ['key' => 'rejected', 'label' => __('Rejected'), 'icon' => 'fa-circle-xmark', 'class' => 'danger'],
            ['key' => 'shortlisted', 'label' => __('Shortlisted'), 'icon' => 'fa-star', 'class' => 'info'],
            ['key' => 'withdrawn', 'label' => __('Withdrawn'), 'icon' => 'fa-arrow-rotate-left', 'class' => 'dark'],
        ] as $card)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-{{ $card['class'] }} mb-2"><i class="fas {{ $card['icon'] }} fa-lg"></i></div>
                        <h3 class="h4 mb-0">{{ $statistics[$card['key']] }}</h3>
                        <small class="text-muted">{{ $card['label'] }}</small>
                    </div>
                </div>
            </div>
        @endforeach
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
                        <td>{{ $app->university->name ?? '—' }}</td>
                        <td>{{ $app->interest->name ?? '—' }}</td>
                        <td><span class="badge bg-secondary text-uppercase">{{ $app->status }}</span></td>
                        <td><a href="{{ route('student.portal.applications.show', $app->id) }}" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No applications yet.') }} <a href="{{ route('universities') }}">{{ __('Browse universities') }}</a></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
