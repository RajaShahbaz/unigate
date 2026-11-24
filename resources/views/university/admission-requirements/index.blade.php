@extends('university.layouts.master')

@section('title', 'Admission Requirements')
@section('page-title', 'Admission Requirements Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-check"></i> Admission Requirements
                    </h5>
                    <a href="{{ route('university.admission-requirements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Requirement
                    </a>
                </div>
                <div class="card-body">
                    @if($requirements->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No admission requirements yet</h5>
                            <p class="text-muted">Start by defining admission requirements for your programs.</p>
                            <a href="{{ route('university.admission-requirements.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus"></i> Add First Requirement
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Program Level</th>
                                        <th>Program</th>
                                        <th>Academic Requirements</th>
                                        <th>Entrance Tests</th>
                                        <th>Application Fee</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requirements as $requirement)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ ucfirst($requirement->program_level) }}</span>
                                            </td>
                                            <td>
                                                @if($requirement->interest)
                                                    <strong>{{ $requirement->interest->name }}</strong>
                                                @else
                                                    <span class="text-muted">General Requirements</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>
                                                    @if($requirement->minimum_percentage)
                                                        Min %: {{ $requirement->minimum_percentage }}%<br>
                                                    @endif
                                                    @if($requirement->minimum_gpa)
                                                        Min GPA: {{ $requirement->minimum_gpa }}
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                @if($requirement->entrance_tests)
                                                    <small>{{ \Illuminate\Support\Str::limit($requirement->entrance_tests, 30) }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($requirement->application_fee)
                                                    PKR {{ number_format($requirement->application_fee, 0) }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($requirement->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('university.admission-requirements.edit', $requirement->id) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('university.admission-requirements.destroy', $requirement->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Are you sure you want to delete this requirement?');"
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $requirements->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Requirements</h6>
                            <h3 class="mb-0">{{ $requirements->total() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-clipboard-check fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active</h6>
                            <h3 class="mb-0">{{ $requirements->where('status', true)->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Program Levels</h6>
                            <h3 class="mb-0">{{ $requirements->pluck('program_level')->unique()->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-graduation-cap fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">General Requirements</h6>
                            <h3 class="mb-0">{{ $requirements->whereNull('interest_id')->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-list fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

