@extends('layouts.master')

@section('title')
    {{ __('Manage Universities') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-theme text-white mr-2">
                    <i class="fa fa-university"></i>
                </span> {{ __('Manage Universities') }}
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">{{ __('Filter Universities') }}</h5>
                    <a href="{{ route('admin.universities.create') }}" class="btn btn-theme">
                        <i class="fa fa-plus"></i> {{ __('Add New University') }}
                    </a>
                </div>
                <form method="GET" class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="form-label text-muted">{{ __('Search') }}</label>
                        <input type="text" name="search" value="{{ $search }}" class="form-control"
                               placeholder="{{ __('Name, email or phone') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label text-muted">{{ __('Status') }}</label>
                        <select name="status" class="form-control">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                            <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label text-muted">{{ __('Type') }}</label>
                        <select name="type" class="form-control">
                            <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>{{ __('All types') }}</option>
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ $typeFilter === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button class="btn btn-theme btn-block" type="submit">{{ __('Filter') }}</button>
                        <a href="{{ route('admin.universities.index') }}" class="btn btn-link btn-sm px-0">{{ __('Reset') }}</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body custom-card-body">
                <div class="table-responsive">
                    <table id="universities-table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{ __('University') }}</th>
                                <th>{{ __('City') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Applications') }}</th>
                                <th>{{ __('Created') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($universities as $university)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $university->name }}</div>
                                        <small class="text-muted">{{ $university->email }}</small>
                                    </td>
                                    <td>{{ $university->city->name ?? __('N/A') }}</td>
                                    <td>{{ ucfirst(str_replace('-', ' ', $university->type ?? __('N/A'))) }}</td>
                                    <td>
                                        @if ($university->status)
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($university->applications_count) }}</td>
                                    <td>{{ $university->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.universities.show', $university->id) }}" 
                                               class="btn btn-sm btn-info" title="{{ __('View Details') }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.universities.edit', $university->id) }}" 
                                               class="btn btn-sm btn-primary" title="{{ __('Edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.universities.destroy', $university->id) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this university?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete') }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
            $('#universities-table').DataTable({
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                "order": [[5, "desc"]], // Sort by Created date
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

