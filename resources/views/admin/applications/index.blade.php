@extends('layouts.master')

@section('title')
    {{ __('Applications') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-theme text-white mr-2">
                    <i class="fa fa-file-text"></i>
                </span> {{ __('Applications') }}
            </h3>
        </div>

        <div class="row">
            @foreach ($statusOptions as $status => $label)
                <div class="col-sm-6 col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body custom-card-body">
                            <p class="text-muted mb-1">{{ __($label) }}</p>
                            <h3 class="mb-0">{{ number_format($statusStats[$status] ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card mb-4">
            <div class="card-body custom-card-body">
                <form method="GET" class="row align-items-end g-3">
                    <div class="col-md-2 mb-2">
                        <label class="form-label text-muted">{{ __('Search') }}</label>
                        <input type="text" name="search" value="{{ $filters['search'] }}" class="form-control"
                            placeholder="{{ __('Application number, name or email') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label text-muted">{{ __('Status') }}</label>
                        <select name="status" class="form-control">
                            <option value="all">{{ __('All statuses') }}</option>
                            @foreach ($statusOptions as $key => $label)
                                <option value="{{ $key }}" {{ $filters['status'] === $key ? 'selected' : '' }}>
                                    {{ __($label) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label text-muted">{{ __('University') }}</label>
                        <select name="university_id" class="form-control">
                            <option value="">{{ __('All universities') }}</option>
                            @foreach ($universities as $university)
                                <option value="{{ $university->id }}"
                                    {{ (int) $filters['university_id'] === $university->id ? 'selected' : '' }}>
                                    {{ $university->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label text-muted">{{ __('Date From') }}</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label text-muted">{{ __('Date To') }}</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-theme btn-block">{{ __('Filter') }}</button>
                        <a href="{{ route('admin.applications.index') }}" class="btn btn-link btn-sm px-0">{{ __('Reset') }}</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body custom-card-body">
                <div class="table-responsive">
                    <table id="applications-table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{ __('Application #') }}</th>
                                <th>{{ __('Applicant') }}</th>
                                <th>{{ __('University / Program') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Submitted') }}</th>
                                <th>{{ __('Last Update') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
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
                            @foreach ($applications as $application)
                                <tr>
                                    <td class="font-weight-bold">{{ $application->application_number }}</td>
                                    @php
                                        $applicantName = trim(($application->first_name ?? '') . ' ' . ($application->last_name ?? ''));
                                        if ($applicantName === '') {
                                            $applicantName = $application->user->full_name ?? __('N/A');
                                        }
                                        $applicantEmail = $application->email ?? ($application->user->email ?? null);
                                    @endphp
                                    <td>
                                        <div>{{ $applicantName }}</div>
                                        @if ($applicantEmail)
                                            <small class="text-muted">{{ $applicantEmail }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $application->university->name ?? __('N/A') }}</div>
                                        <small class="text-muted">{{ $application->interest->name ?? __('Program not set') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusBadgeClasses[$application->status] ?? 'badge-light' }}">
                                            {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ optional($application->submitted_at)->format('d M Y') ?? __('Not submitted') }}</td>
                                    <td>{{ $application->updated_at->format('d M Y') }}</td>
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
    </div>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#applications-table').DataTable({
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                "order": [[5, "desc"]], // Sort by Last Update date
                "language": {
                    "search": "{{ __('Search') }}:",
                    "lengthMenu": "{{ __('Show') }} _MENU_ {{ __('entries') }}",
                    "info": "{{ __('Showing') }} _START_ {{ __('to') }} _END_ {{ __('of') }} _TOTAL_ {{ __('entries') }}",
                    "infoEmpty": "{{ __('Showing') }} 0 {{ __('to') }} 0 {{ __('of') }} 0 {{ __('entries') }}",
                    "infoFiltered": "({{ __('filtered from') }} _MAX_ {{ __('total entries') }})",
                    "paginate": {
                        "first": "{{ __('First') }}",
                        "last": "{{ __('Last') }}",
                        "next": "{{ __('Next') }}",
                        "previous": "{{ __('Previous') }}"
                    }
                }
            });
        });
    </script>
@endsection

