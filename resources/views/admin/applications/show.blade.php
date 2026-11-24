@extends('layouts.master')

@section('title')
    {{ __('Application Details') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-theme text-white mr-2">
                    <i class="fa fa-file-text"></i>
                </span> {{ __('Application Details') }}
            </h3>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body custom-card-body">
                        <div class="mb-3">
                            <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <h2>{{ __('Application') }} #{{ $application->application_number }}</h2>
                                <p class="text-muted">{{ $application->university->name ?? __('N/A') }}</p>
                                
                                <div class="mb-3">
                                    @php
                                        $statusBadgeClasses = [
                                            'draft' => 'badge-secondary',
                                            'submitted' => 'badge-info',
                                            'documents_pending' => 'badge-warning',
                                            'under_review' => 'badge-primary',
                                            'shortlisted' => 'badge-success',
                                            'interview_scheduled' => 'badge-success',
                                            'accepted' => 'badge-success',
                                            'rejected' => 'badge-danger',
                                            'withdrawn' => 'badge-dark',
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusBadgeClasses[$application->status] ?? 'badge-light' }}">
                                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Applicant Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>{{ __('Name') }}:</strong> {{ $application->first_name }} {{ $application->last_name }}</p>
                                <p><strong>{{ __('Email') }}:</strong> {{ $application->email }}</p>
                                <p><strong>{{ __('Phone') }}:</strong> {{ $application->phone ?? __('N/A') }}</p>
                                <p><strong>{{ __('Date of Birth') }}:</strong> {{ $application->date_of_birth ? \Carbon\Carbon::parse($application->date_of_birth)->format('d M Y') : __('N/A') }}</p>
                                <p><strong>{{ __('Gender') }}:</strong> {{ ucfirst($application->gender ?? __('N/A')) }}</p>
                                <p><strong>{{ __('Nationality') }}:</strong> {{ $application->nationality ?? __('N/A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Application Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>{{ __('University') }}:</strong> {{ $application->university->name ?? __('N/A') }}</p>
                                <p><strong>{{ __('Program') }}:</strong> {{ $application->interest->name ?? __('N/A') }}</p>
                                <p><strong>{{ __('Status') }}:</strong> {{ ucwords(str_replace('_', ' ', $application->status)) }}</p>
                                <p><strong>{{ __('Submitted') }}:</strong> {{ optional($application->submitted_at)->format('d M Y H:i') ?? __('Not submitted') }}</p>
                                <p><strong>{{ __('Last Updated') }}:</strong> {{ $application->updated_at->format('d M Y H:i') }}</p>
                                @if($application->reviewed_at)
                                    <p><strong>{{ __('Reviewed At') }}:</strong> {{ $application->reviewed_at->format('d M Y H:i') }}</p>
                                    <p><strong>{{ __('Reviewed By') }}:</strong> {{ $application->reviewer->full_name ?? __('N/A') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($application->current_address || $application->permanent_address)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Address Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>{{ __('Current Address') }}:</strong> {{ $application->current_address ?? __('N/A') }}</p>
                            <p><strong>{{ __('Permanent Address') }}:</strong> {{ $application->permanent_address ?? __('N/A') }}</p>
                        </div>
                    </div>
                @endif

                @if($application->previous_school || $application->previous_degree)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Educational Background') }}</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>{{ __('Previous School') }}:</strong> {{ $application->previous_school ?? __('N/A') }}</p>
                            <p><strong>{{ __('Previous Degree') }}:</strong> {{ $application->previous_degree ?? __('N/A') }}</p>
                            <p><strong>{{ __('Previous GPA') }}:</strong> {{ $application->previous_gpa ?? __('N/A') }}</p>
                            <p><strong>{{ __('Graduation Year') }}:</strong> {{ $application->graduation_year ?? __('N/A') }}</p>
                        </div>
                    </div>
                @endif

                @if($application->program_preference_reason)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Program Preference Reason') }}</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $application->program_preference_reason }}</p>
                        </div>
                    </div>
                @endif

                @if($application->rejection_reason)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Rejection Reason') }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-danger">{{ $application->rejection_reason }}</p>
                        </div>
                    </div>
                @endif

                @if($application->admin_notes)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Admin Notes') }}</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $application->admin_notes }}</p>
                        </div>
                    </div>
                @endif

                @if($application->fieldResponses->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Custom Field Responses') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Field') }}</th>
                                            <th>{{ __('Response') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($application->fieldResponses as $response)
                                            <tr>
                                                <td>{{ $response->field->field_label ?? __('N/A') }}</td>
                                                <td>
                                                    @if($response->field->field_type === 'file' && $response->response_value)
                                                        <a href="{{ asset('storage/' . $response->response_value) }}" target="_blank">
                                                            {{ __('View File') }}
                                                        </a>
                                                    @else
                                                        {{ $response->response_value ?? __('N/A') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

