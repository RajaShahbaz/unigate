@extends('layouts.master')

@section('title')
    {{ __('Manage Students') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-theme text-white mr-2">
                    <i class="fa fa-users"></i>
                </span> {{ __('Manage Students') }}
            </h3>
        </div>

        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body custom-card-body">
                        <p class="text-muted mb-1">{{ __('Total') }}</p>
                        <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body custom-card-body">
                        <p class="text-muted mb-1">{{ __('Active') }}</p>
                        <h3 class="mb-0 text-success">{{ number_format($stats['active']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body custom-card-body">
                        <p class="text-muted mb-1">{{ __('Inactive') }}</p>
                        <h3 class="mb-0 text-danger">{{ number_format($stats['inactive']) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body custom-card-body">
                <form method="GET" class="row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="form-label text-muted">{{ __('Search') }}</label>
                        <input type="text" name="search" value="{{ $filters['search'] }}" class="form-control"
                               placeholder="{{ __('Name, email or phone') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label text-muted">{{ __('Status') }}</label>
                        <select name="status" class="form-control">
                            <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                            <option value="active" {{ $filters['status'] === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="inactive" {{ $filters['status'] === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label text-muted">{{ __('Gender') }}</label>
                        <select name="gender" class="form-control">
                            <option value="all" {{ $filters['gender'] === 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                            <option value="male" {{ $filters['gender'] === 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                            <option value="female" {{ $filters['gender'] === 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                            <option value="other" {{ $filters['gender'] === 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label text-muted">{{ __('School') }}</label>
                        <select name="school_id" class="form-control">
                            <option value="all" {{ $filters['school_id'] === 'all' ? 'selected' : '' }}>{{ __('All schools') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ (string) $filters['school_id'] === (string) $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
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
                    <div class="col-md-2 mt-2">
                        <button class="btn btn-theme btn-block" type="submit">{{ __('Filter') }}</button>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-link btn-sm px-0">{{ __('Reset') }}</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body custom-card-body">
                <div class="table-responsive">
                    <table id="students-table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{ __('Student') }}</th>
                                <th>{{ __('Contact') }}</th>
                                <th>{{ __('Gender') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('School') }}</th>
                                <th>{{ __('Joined') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td class="font-weight-bold">{{ $student->full_name }}</td>
                                    <td>
                                        <div>{{ $student->email }}</div>
                                        <small class="text-muted">{{ $student->mobile }}</small>
                                    </td>
                                    <td>{{ ucfirst($student->gender ?? __('N/A')) }}</td>
                                    <td>
                                        @if ($student->status)
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->school->name ?? __('N/A') }}</td>
                                    <td>{{ $student->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.students.show', $student->id) }}" 
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
            $('#students-table').DataTable({
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                "order": [[5, "desc"]], // Sort by Joined date
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

