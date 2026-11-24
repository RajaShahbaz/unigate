@extends('layouts.master')

@section('title')
    {{ __('Student Details') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-theme text-white mr-2">
                    <i class="fa fa-user"></i>
                </span> {{ __('Student Details') }}
            </h3>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body custom-card-body">
                        <div class="mb-3">
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <h2>{{ $student->full_name }}</h2>
                                <p class="text-muted">{{ $student->email }}</p>
                                
                                <div class="mb-3">
                                    <span class="badge badge-{{ $student->status ? 'success' : 'danger' }}">
                                        {{ $student->status ? __('Active') : __('Inactive') }}
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
                                <h5 class="mb-0">{{ __('Personal Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>{{ __('First Name') }}:</strong> {{ $student->first_name }}</p>
                                <p><strong>{{ __('Last Name') }}:</strong> {{ $student->last_name }}</p>
                                <p><strong>{{ __('Email') }}:</strong> {{ $student->email }}</p>
                                <p><strong>{{ __('Mobile') }}:</strong> {{ $student->mobile ?? __('N/A') }}</p>
                                <p><strong>{{ __('Gender') }}:</strong> {{ ucfirst($student->gender ?? __('N/A')) }}</p>
                                <p><strong>{{ __('Date of Birth') }}:</strong> {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d M Y') : __('N/A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('School Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>{{ __('School') }}:</strong> {{ $student->school->name ?? __('N/A') }}</p>
                                <p><strong>{{ __('Joined') }}:</strong> {{ $student->created_at->format('d M Y') }}</p>
                                <p><strong>{{ __('Last Updated') }}:</strong> {{ $student->updated_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($student->applications->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Applications') }} ({{ $student->applications->count() }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Application #') }}</th>
                                            <th>{{ __('University') }}</th>
                                            <th>{{ __('Program') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Submitted') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->applications as $application)
                                            <tr>
                                                <td>{{ $application->application_number }}</td>
                                                <td>{{ $application->university->name ?? __('N/A') }}</td>
                                                <td>{{ $application->interest->name ?? __('N/A') }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $application->status)) }}</span>
                                                </td>
                                                <td>{{ optional($application->submitted_at)->format('d M Y') ?? __('Not submitted') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.applications.show', $application->id) }}" 
                                                       class="btn btn-sm btn-info" title="{{ __('View Details') }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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

