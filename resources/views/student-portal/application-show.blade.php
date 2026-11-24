@extends('student-portal.layouts.master')

@section('title', __('Application'))
@section('page-title', __('Application') . ' ' . $application->application_number)

@section('content')
    <div class="mb-3">
        <a href="{{ route('student.portal.applications') }}" class="btn btn-outline-secondary btn-sm">&larr; {{ __('Back') }}</a>
    </div>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body row g-3">
            <div class="col-md-6"><strong>{{ __('University') }}</strong><br>{{ $application->university->name ?? '—' }}</div>
            <div class="col-md-6"><strong>{{ __('Program') }}</strong><br>{{ $application->interest->name ?? '—' }}</div>
            <div class="col-md-6"><strong>{{ __('Status') }}</strong><br><span class="badge bg-secondary">{{ $application->status }}</span></div>
            <div class="col-md-6"><strong>{{ __('Submitted') }}</strong><br>{{ $application->submitted_at?->format('Y-m-d H:i') ?? '—' }}</div>
            <div class="col-md-6"><strong>{{ __('Name') }}</strong><br>{{ $application->first_name }} {{ $application->last_name }}</div>
            <div class="col-md-6"><strong>{{ __('Email') }}</strong><br>{{ $application->email }}</div>
            <div class="col-md-6"><strong>{{ __('Phone') }}</strong><br>{{ $application->phone ?? '—' }}</div>
        </div>
    </div>
    @if($application->fieldResponses->count())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">{{ __('Custom fields') }}</div>
            <ul class="list-group list-group-flush">
                @foreach($application->fieldResponses as $fr)
                    <li class="list-group-item"><strong>{{ optional($fr->field)->field_label ?? __('Field') }}</strong><br>{{ \Illuminate\Support\Str::limit($fr->response_value ?? $fr->file_path ?? '—', 200) }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
