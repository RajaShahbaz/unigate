@extends('university.layouts.master')

@section('title', 'Edit Program')
@section('page-title', 'Edit Program Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-edit"></i> Edit Program: <strong>{{ $program->name }}</strong></span>
                <a href="{{ route('university.programs.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('university.programs.update', $program->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> You can set either a single tuition fee OR a fee range (min-max), or both.
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fee" class="form-label">Tuition Fee (Single)</label>
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input type="number"
                                       class="form-control @error('fee') is-invalid @enderror"
                                       id="fee"
                                       name="fee"
                                       step="0.01"
                                       min="0"
                                       value="{{ old('fee', $program->pivot->fee) }}"
                                       placeholder="0.00">
                            </div>
                            <small class="text-muted">Standard tuition fee</small>
                            @error('fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="fee_min" class="form-label">Minimum Fee (Range)</label>
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input type="number"
                                       class="form-control @error('fee_min') is-invalid @enderror"
                                       id="fee_min"
                                       name="fee_min"
                                       step="0.01"
                                       min="0"
                                       value="{{ old('fee_min', $program->pivot->fee_min) }}"
                                       placeholder="0.00">
                            </div>
                            <small class="text-muted">Lowest fee in range</small>
                            @error('fee_min')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="fee_max" class="form-label">Maximum Fee (Range)</label>
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input type="number"
                                       class="form-control @error('fee_max') is-invalid @enderror"
                                       id="fee_max"
                                       name="fee_max"
                                       step="0.01"
                                       min="0"
                                       value="{{ old('fee_max', $program->pivot->fee_max) }}"
                                       placeholder="0.00">
                            </div>
                            <small class="text-muted">Highest fee in range</small>
                            @error('fee_max')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="duration_years" class="form-label">Program Duration (Years)</label>
                        <input type="number"
                               class="form-control @error('duration_years') is-invalid @enderror"
                               id="duration_years"
                               name="duration_years"
                               min="1"
                               max="10"
                               value="{{ old('duration_years', $program->pivot->duration_years) }}"
                               placeholder="e.g., 4">
                        <small class="text-muted">How many years does this program take to complete?</small>
                        @error('duration_years')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="program_details" class="form-label">Program Details & Description</label>
                        <textarea class="form-control rich-editor @error('program_details') is-invalid @enderror"
                                  id="program_details"
                                  name="program_details"
                                  rows="6"
                                  placeholder="Provide detailed information about the program, admission requirements, career prospects, etc.">{{ old('program_details', $program->pivot->program_details) }}</textarea>
                        <small class="text-muted">Maximum 5000 characters. This will be shown to students when they browse programs.</small>
                        @error('program_details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Program
                        </button>
                        <a href="{{ route('university.programs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Stats for this Program -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-chart-bar"></i> Program Statistics
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h4 class="text-primary">
                            {{ \Illuminate\Support\Facades\DB::table('applications')->where('university_id', $university->id)->where('interest_id', $program->id)->count() }}
                        </h4>
                        <p class="text-muted mb-0">Total Applications</p>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-success">
                            {{ \Illuminate\Support\Facades\DB::table('applications')->where('university_id', $university->id)->where('interest_id', $program->id)->where('status', 'accepted')->count() }}
                        </h4>
                        <p class="text-muted mb-0">Accepted</p>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-warning">
                            {{ \Illuminate\Support\Facades\DB::table('applications')->where('university_id', $university->id)->where('interest_id', $program->id)->whereIn('status', ['submitted', 'under_review'])->count() }}
                        </h4>
                        <p class="text-muted mb-0">Pending Review</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-update fee_max when fee_min changes (if fee_max is empty)
    document.getElementById('fee_min').addEventListener('change', function() {
        const feeMax = document.getElementById('fee_max');
        if (!feeMax.value && this.value) {
            // Suggest a fee_max that's 50% higher than fee_min
            const suggestedMax = parseFloat(this.value) * 1.5;
            feeMax.value = suggestedMax.toFixed(2);
        }
    });
</script>
 <script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>

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

