@extends('university.layouts.master')

@section('title', 'Applications')
@section('page-title', 'Application Management')

@section('content')
<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('university.applications.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="App #, Name, Email..." 
                       value="{{ request('search') }}">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="documents_pending" {{ request('status') == 'documents_pending' ? 'selected' : '' }}>Documents Pending</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                    <option value="interview_scheduled" {{ request('status') == 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Program</label>
                <select name="interest_id" class="form-select">
                    <option value="">All Programs</option>
                    @foreach($interests as $interest)
                        <option value="{{ $interest->id }}" {{ request('interest_id') == $interest->id ? 'selected' : '' }}>
                            {{ $interest->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Tabs -->
<div class="card mb-4">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('university.applications.index') }}">
                    All ({{ $stats['all'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'submitted' ? 'active' : '' }}" 
                   href="{{ route('university.applications.index', ['status' => 'submitted']) }}">
                    Submitted ({{ $stats['submitted'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'documents_pending' ? 'active' : '' }}"
                   href="{{ route('university.applications.index', ['status' => 'documents_pending']) }}">
                    Documents Pending ({{ $stats['documents_pending'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'under_review' ? 'active' : '' }}" 
                   href="{{ route('university.applications.index', ['status' => 'under_review']) }}">
                    Under Review ({{ $stats['under_review'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'shortlisted' ? 'active' : '' }}" 
                   href="{{ route('university.applications.index', ['status' => 'shortlisted']) }}">
                    Shortlisted ({{ $stats['shortlisted'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'accepted' ? 'active' : '' }}" 
                   href="{{ route('university.applications.index', ['status' => 'accepted']) }}">
                    Accepted ({{ $stats['accepted'] }})
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Actions Bar -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <button type="button" class="btn btn-outline-primary" onclick="selectAll()">
            <i class="fas fa-check-square"></i> Select All
        </button>
        <button type="button" class="btn btn-outline-secondary" onclick="deselectAll()">
            <i class="fas fa-square"></i> Deselect All
        </button>
        <button type="button" class="btn btn-outline-success" onclick="bulkAction()">
            <i class="fas fa-tasks"></i> Bulk Action
        </button>
    </div>
    <a href="{{ route('university.applications.export', request()->all()) }}" class="btn btn-outline-info">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

<!-- Applications Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)">
                        </th>
                        <th>Application #</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Program</th>
                        <th>Status</th>
                        <th>GPA</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td>
                                <input type="checkbox" class="app-checkbox" value="{{ $application->id }}">
                            </td>
                            <td>
                                <strong>{{ $application->application_number }}</strong>
                            </td>
                            <td>{{ $application->full_name }}</td>
                            <td>{{ $application->email }}</td>
                            <td>{{ $application->interest->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'submitted' => 'primary',
                                        'documents_pending' => 'warning',
                                        'under_review' => 'warning',
                                        'shortlisted' => 'info',
                                        'interview_scheduled' => 'info',
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                    ];
                                    $color = $statusColors[$application->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} badge-status">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                            </td>
                            <td>{{ $application->previous_gpa ?? 'N/A' }}</td>
                            <td>
                                <small>{{ $application->submitted_at ? $application->submitted_at->format('M d, Y') : 'N/A' }}</small>
                            </td>
                            <td>
                                <a href="{{ route('university.applications.show', $application->id) }}" 
                                   class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted">No applications found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($applications->hasPages())
        <div class="card-footer">
            {{ $applications->links() }}
        </div>
    @endif
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('university.applications.bulk-status') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="application_ids" id="bulkApplicationIds">
                    
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select name="status" class="form-select" required>
                            <option value="documents_pending">Documents Pending</option>
                            <option value="under_review">Under Review</option>
                            <option value="shortlisted">Shortlisted</option>
                            <option value="interview_scheduled">Interview Scheduled</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Admin Notes (Optional)</label>
                        <textarea name="admin_notes" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span id="selectedCount">0</span> application(s) will be updated
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Applications</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.app-checkbox');
        checkboxes.forEach(cb => cb.checked = checkbox.checked);
    }

    function selectAll() {
        document.getElementById('selectAllCheckbox').checked = true;
        toggleSelectAll(document.getElementById('selectAllCheckbox'));
    }

    function deselectAll() {
        document.getElementById('selectAllCheckbox').checked = false;
        toggleSelectAll(document.getElementById('selectAllCheckbox'));
    }

    function bulkAction() {
        const selected = Array.from(document.querySelectorAll('.app-checkbox:checked')).map(cb => cb.value);
        
        if (selected.length === 0) {
            alert('Please select at least one application');
            return;
        }
        
        document.getElementById('bulkApplicationIds').value = JSON.stringify(selected);
        document.getElementById('selectedCount').textContent = selected.length;
        
        const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
        modal.show();
    }
</script>
@endpush

