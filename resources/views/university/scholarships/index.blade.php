@extends('university.layouts.master')

@section('title', 'Scholarships Management')
@section('page-title', 'Scholarships Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-award"></i> Scholarships
                    </h5>
                    <a href="{{ route('university.scholarships.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Scholarship
                    </a>
                </div>
                <div class="card-body">
                    @if($scholarships->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-award fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No scholarships yet</h5>
                            <p class="text-muted">Start by adding your first scholarship program.</p>
                            <a href="{{ route('university.scholarships.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus"></i> Add First Scholarship
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Slots</th>
                                        <th>Application Dates</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scholarships as $scholarship)
                                        <tr>
                                            <td>
                                                <strong>{{ $scholarship->name }}</strong><br>
                                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($scholarship->description, 40) }}</small>
                                            </td>
                                            <td><span class="badge bg-info">{{ ucfirst($scholarship->scholarship_type) }}</span></td>
                                            <td>
                                                @if($scholarship->amount_type === 'full')
                                                    <span class="badge bg-success">Full Scholarship</span>
                                                @elseif($scholarship->amount_type === 'percentage')
                                                    {{ $scholarship->percentage }}%
                                                @else
                                                    PKR {{ number_format($scholarship->amount, 0) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($scholarship->total_slots)
                                                    <span class="badge bg-secondary">
                                                        {{ $scholarship->available_slots }}/{{ $scholarship->total_slots }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Unlimited</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($scholarship->application_start_date && $scholarship->application_end_date)
                                                    <small>
                                                        {{ $scholarship->application_start_date->format('M d, Y') }}<br>
                                                        to {{ $scholarship->application_end_date->format('M d, Y') }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($scholarship->is_accepting_applications)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Open
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">Closed</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('university.scholarships.edit', $scholarship->id) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('university.scholarships.destroy', $scholarship->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Are you sure you want to delete this scholarship?');"
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
                            {{ $scholarships->links() }}
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
                            <h6 class="text-muted mb-1">Total Scholarships</h6>
                            <h3 class="mb-0">{{ $scholarships->total() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-award fa-2x text-primary"></i>
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
                            <h6 class="text-muted mb-1">Accepting Applications</h6>
                            <h3 class="mb-0">{{ $scholarships->filter(fn($s) => $s->is_accepting_applications)->count() }}</h3>
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
                            <h6 class="text-muted mb-1">Total Slots</h6>
                            <h3 class="mb-0">{{ $scholarships->sum('total_slots') ?: 'N/A' }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-users fa-2x text-warning"></i>
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
                            <h6 class="text-muted mb-1">Available Slots</h6>
                            <h3 class="mb-0">{{ $scholarships->sum('available_slots') ?: 'N/A' }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-user-check fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

