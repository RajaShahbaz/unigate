@extends('user-portal.layouts.master')

@section('title', __('Application Details'))
@section('page-title', __('Application') . ' ' . $application->application_number)

@section('content')
    <div class="mb-3">
        <a href="{{ route('user.portal.applications') }}" class="btn btn-outline-secondary btn-sm">&larr; {{ __('Back') }}</a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">{{ __('Application overview') }}</div>
        <div class="card-body row g-3">
            <div class="col-md-6"><strong>{{ __('University') }}</strong><br>{{ $application->university->name ?? '-' }}</div>
            <div class="col-md-6"><strong>{{ __('Program') }}</strong><br>{{ $application->interest->name ?? '-' }}</div>
            <div class="col-md-6"><strong>{{ __('Status') }}</strong><br><span class="badge bg-secondary text-uppercase">{{ $application->status }}</span></div>
            <div class="col-md-6"><strong>{{ __('Submitted at') }}</strong><br>{{ $application->submitted_at?->format('Y-m-d H:i') ?? '-' }}</div>
            <div class="col-md-6"><strong>{{ __('Name') }}</strong><br>{{ $application->first_name }} {{ $application->last_name }}</div>
            <div class="col-md-6"><strong>{{ __('Email') }}</strong><br>{{ $application->email }}</div>
        </div>
    </div>

    @if($application->fieldResponses->count())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">{{ __('Submitted fields') }}</div>
            <ul class="list-group list-group-flush">
                @foreach($application->fieldResponses as $response)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $response->field->field_label ?? $response->field_name ?? __('Field') }}</span>
                        <span class="text-muted">{{ is_scalar($response->response_value) ? $response->response_value : json_encode($response->response_value) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
