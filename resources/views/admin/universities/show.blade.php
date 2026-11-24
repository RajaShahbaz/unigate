@extends('layouts.master')

@section('title')
    {{ __('University Details') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-theme text-white mr-2">
                    <i class="fa fa-university"></i>
                </span> {{ __('University Details') }}
            </h3>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body custom-card-body">
                        <div class="mb-3">
                            <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                            <a href="{{ route('admin.universities.edit', $university->id) }}" class="btn btn-primary">
                                <i class="fa fa-edit"></i> {{ __('Edit') }}
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                @if($university->logo)
                                    <img src="{{ asset('storage/' . $university->logo) }}" alt="Logo" class="img-fluid rounded">
                                @else
                                    <div class="bg-light p-5 text-center rounded">
                                        <i class="fa fa-university fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h2>{{ $university->name }}</h2>
                                <p class="text-muted">{{ $university->city->name ?? __('N/A') }}</p>
                                
                                <div class="mb-3">
                                    <span class="badge badge-{{ $university->status ? 'success' : 'danger' }}">
                                        {{ $university->status ? __('Active') : __('Inactive') }}
                                    </span>
                                    <span class="badge badge-info ml-2">
                                        {{ ucfirst(str_replace('-', ' ', $university->type ?? __('N/A'))) }}
                                    </span>
                                </div>

                                @if($university->description)
                                    <p>{{ $university->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Contact Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>{{ __('Email') }}:</strong> {{ $university->email ?? __('N/A') }}</p>
                                <p><strong>{{ __('Phone') }}:</strong> {{ $university->phone ?? __('N/A') }}</p>
                                <p><strong>{{ __('Website') }}:</strong> 
                                    @if($university->website)
                                        <a href="{{ $university->website }}" target="_blank">{{ $university->website }}</a>
                                    @else
                                        {{ __('N/A') }}
                                    @endif
                                </p>
                                <p><strong>{{ __('Address') }}:</strong> {{ $university->address ?? __('N/A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Additional Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>{{ __('Established Year') }}:</strong> {{ $university->established_year ?? __('N/A') }}</p>
                                <p><strong>{{ __('Ranking') }}:</strong> {{ $university->ranking ?? __('N/A') }}</p>
                                <p><strong>{{ __('Hostel Available') }}:</strong> {{ $university->hostel_available ? __('Yes') : __('No') }}</p>
                                <p><strong>{{ __('Scholarship Available') }}:</strong> {{ $university->scholarship_available ? __('Yes') : __('No') }}</p>
                                <p><strong>{{ __('Created') }}:</strong> {{ $university->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Programs/Interests') }}</h5>
                    </div>
                    <div class="card-body">
                        @if($university->interests->count() > 0)
                            <div class="row">
                                @foreach($university->interests as $interest)
                                    <div class="col-md-4 mb-2">
                                        <span class="badge badge-primary">{{ $interest->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">{{ __('No programs assigned') }}</p>
                        @endif
                    </div>
                </div>

                @if($university->universityAdmins->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('University Admins') }} ({{ $university->universityAdmins->count() }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Phone') }}</th>
                                            <th>{{ __('Position') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Last Login') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($university->universityAdmins as $admin)
                                            <tr>
                                                <td>{{ $admin->name }}</td>
                                                <td>{{ $admin->email }}</td>
                                                <td>{{ $admin->phone ?? __('N/A') }}</td>
                                                <td>{{ $admin->position ?? __('N/A') }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $admin->status ? 'success' : 'danger' }}">
                                                        {{ $admin->status ? __('Active') : __('Inactive') }}
                                                    </span>
                                                </td>
                                                <td>{{ $admin->last_login_at ? $admin->last_login_at->format('d M Y H:i') : __('Never') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Applications') }} ({{ $university->applications->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @if($university->applications->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Application #') }}</th>
                                            <th>{{ __('Applicant') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Submitted') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($university->applications->take(10) as $application)
                                            <tr>
                                                <td>{{ $application->application_number }}</td>
                                                <td>{{ $application->first_name }} {{ $application->last_name }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $application->status)) }}</span>
                                                </td>
                                                <td>{{ optional($application->submitted_at)->format('d M Y') ?? __('Not submitted') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($university->applications->count() > 10)
                                <p class="text-muted">{{ __('Showing first 10 applications') }}</p>
                            @endif
                        @else
                            <p class="text-muted">{{ __('No applications yet') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

