@extends('university.layouts.master')

@section('title', 'Programs & Courses')
@section('page-title', 'Programs & Courses Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage your university's programs and course offerings with tuition fees</p>
    <div>
        <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#bulkAddModal">
            <i class="fas fa-plus-circle"></i> Bulk Add Programs
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProgramModal">
            <i class="fas fa-plus"></i> Add Program
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stat-card primary h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Programs
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $programs->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card success h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            With Fees Set
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $programs->filter(fn($p) => $p->pivot->fee || $p->pivot->fee_min)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card info h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Available to Add
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $availableInterests->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($programs->count() > 0)
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Program Name</th>
                            <th>Duration</th>
                            <th>Tuition Fee</th>
                            <th>Fee Range</th>
                            <th>Details</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($programs as $program)
                            <tr>
                                <td>
                                    <strong>{{ $program->name }}</strong>
                                </td>
                                <td>
                                    @if($program->pivot->duration_years)
                                        <span class="badge bg-info">{{ $program->pivot->duration_years }} year(s)</span>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($program->pivot->fee)
                                        <strong class="text-success">PKR {{ number_format($program->pivot->fee, 2) }}</strong>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($program->pivot->fee_min && $program->pivot->fee_max)
                                        <span class="text-primary">
                                            PKR {{ number_format($program->pivot->fee_min, 2) }} -
                                            PKR {{ number_format($program->pivot->fee_max, 2) }}
                                        </span>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($program->pivot->program_details)
                                        <small>{{ \Illuminate\Support\Str::limit($program->description, 100) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('university.programs.edit', $program->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="deleteProgram({{ $program->id }}, '{{ $program->name }}')"
                                            title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-graduation-cap fa-5x text-muted mb-4"></i>
            <h4>No Programs Added Yet</h4>
            <p class="text-muted mb-4">Start by adding programs/courses that your university offers</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProgramModal">
                <i class="fas fa-plus"></i> Add Your First Program
            </button>
        </div>
    </div>
@endif

<!-- Add Program Modal -->
<div class="modal fade" id="addProgramModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('university.programs.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Add New Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($availableInterests->count() > 0)
                        <div class="mb-3">
                            <label for="interest_id" class="form-label">Select Program/Course *</label>
                            <select name="interest_id" id="interest_id" class="form-select" required>
                                <option value="">Choose a program...</option>
                                @foreach($availableInterests as $interest)
                                    <option value="{{ $interest->id }}">{{ $interest->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="fee" class="form-label">Tuition Fee (Single)</label>
                                <div class="input-group">
                                    <span class="input-group-text">PKR</span>
                                    <input type="number" class="form-control" id="fee" name="fee"
                                           step="0.01" min="0" placeholder="0.00">
                                </div>
                                <small class="text-muted">Or use fee range below</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="fee_min" class="form-label">Min Fee (Range)</label>
                                <div class="input-group">
                                    <span class="input-group-text">PKR</span>
                                    <input type="number" class="form-control" id="fee_min" name="fee_min"
                                           step="0.01" min="0" placeholder="0.00">
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="fee_max" class="form-label">Max Fee (Range)</label>
                                <div class="input-group">
                                    <span class="input-group-text">PKR</span>
                                    <input type="number" class="form-control" id="fee_max" name="fee_max"
                                           step="0.01" min="0" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="duration_years" class="form-label">Program Duration (Years)</label>
                            <input type="number" class="form-control" id="duration_years" name="duration_years"
                                   min="1" max="10" placeholder="e.g., 4">
                        </div>

                        <div class="mb-3">
                            <label for="program_details" class="form-label">Program Details</label>
                            <textarea id="mytextarea" name="content" class="form-control  rich-editor" rows="10" >{{ old('description') }}</textarea>

                            <textarea class="form-control" id="program_details" name="program_details"
                                      rows="4" placeholder="Additional information about the program..."></textarea>
                            <small class="text-muted">Max 5000 characters</small>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> All available programs have been added to your university.
                            You can edit existing programs or contact support to add more program categories.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if($availableInterests->count() > 0)
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Program
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Add Modal -->
<div class="modal fade" id="bulkAddModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('university.programs.bulk-add') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Bulk Add Programs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($availableInterests->count() > 0)
                        <p class="text-muted mb-3">Select multiple programs to add at once (fees can be set later)</p>
                        <div class="row">
                            @foreach($availableInterests->chunk(3) as $chunk)
                                @foreach($chunk as $interest)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="interest_ids[]" value="{{ $interest->id }}"
                                                   id="bulk_{{ $interest->id }}">
                                            <label class="form-check-label" for="bulk_{{ $interest->id }}">
                                                {{ $interest->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No programs available to add.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if($availableInterests->count() > 0)
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Add Selected Programs
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function deleteProgram(interestId, programName) {
        if (confirm(`Are you sure you want to remove "${programName}" from your university?\n\nNote: This action will fail if there are applications for this program.`)) {
            const form = document.getElementById('deleteForm');
            form.action = '{{ url("university/programs") }}/' + interestId;
            form.submit();
        }
    }
</script>
 <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>

<script>
tinymce.init({
    license_key: 'gpl',
    selector: 'textarea.rich-editor',
    // plugins: 'lists link image code preview',
    // toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code preview',
     plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | bold italic backcolor | ' +
                    'alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | removeformat | help',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});
</script>
@endpush

