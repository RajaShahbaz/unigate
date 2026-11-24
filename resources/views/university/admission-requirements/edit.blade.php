@extends('university.layouts.master')

@section('title', 'Edit Admission Requirement')
@section('page-title', 'Edit Admission Requirement')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit"></i> Edit Admission Requirement
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('university.admission-requirements.update', $requirement->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="program_level" class="form-label">Program Level <span class="text-danger">*</span></label>
                                <select class="form-select @error('program_level') is-invalid @enderror"
                                        id="program_level" name="program_level" required>
                                    <option value="">Select Level</option>
                                    <option value="undergraduate" {{ old('program_level', $requirement->program_level) == 'undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                    <option value="postgraduate" {{ old('program_level', $requirement->program_level) == 'postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                                    <option value="diploma" {{ old('program_level', $requirement->program_level) == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="certificate" {{ old('program_level', $requirement->program_level) == 'certificate' ? 'selected' : '' }}>Certificate</option>
                                    <option value="doctoral" {{ old('program_level', $requirement->program_level) == 'doctoral' ? 'selected' : '' }}>Doctoral</option>
                                    <option value="associate" {{ old('program_level', $requirement->program_level) == 'associate' ? 'selected' : '' }}>Associate</option>
                                </select>
                                @error('program_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="interest_id" class="form-label">Specific Program (Optional)</label>
                                <select class="form-select @error('interest_id') is-invalid @enderror"
                                        id="interest_id" name="interest_id">
                                    <option value="">General Requirements</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}" {{ old('interest_id', $requirement->interest_id) == $program->id ? 'selected' : '' }}>
                                            {{ $program->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Leave empty for general requirements</small>
                                @error('interest_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="general_requirements" class="form-label">General Requirements</label>
                            <textarea class="form-control @error('general_requirements') is-invalid @enderror"
                                      id="general_requirements" name="general_requirements" rows="3">{{ old('general_requirements', $requirement->general_requirements) }}</textarea>
                            @error('general_requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="academic_requirements" class="form-label">Academic Requirements</label>
                            <textarea class="form-control @error('academic_requirements') is-invalid @enderror"
                                      id="academic_requirements" name="academic_requirements" rows="3">{{ old('academic_requirements', $requirement->academic_requirements) }}</textarea>
                            @error('academic_requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="minimum_percentage" class="form-label">Minimum Percentage (%)</label>
                                <input type="number" class="form-control @error('minimum_percentage') is-invalid @enderror"
                                       id="minimum_percentage" name="minimum_percentage" value="{{ old('minimum_percentage', $requirement->minimum_percentage) }}"
                                       step="0.01" min="0" max="100">
                                @error('minimum_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="minimum_gpa" class="form-label">Minimum GPA</label>
                                <input type="number" class="form-control @error('minimum_gpa') is-invalid @enderror"
                                       id="minimum_gpa" name="minimum_gpa" value="{{ old('minimum_gpa', $requirement->minimum_gpa) }}"
                                       step="0.01" min="0" max="5">
                                @error('minimum_gpa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="entrance_tests" class="form-label">Entrance Tests</label>
                                <input type="text" class="form-control @error('entrance_tests') is-invalid @enderror"
                                       id="entrance_tests" name="entrance_tests" value="{{ old('entrance_tests', $requirement->entrance_tests) }}"
                                       placeholder="e.g., SAT, MDCAT, ECAT">
                                @error('entrance_tests')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="minimum_test_score" class="form-label">Minimum Test Score</label>
                                <input type="number" class="form-control @error('minimum_test_score') is-invalid @enderror"
                                       id="minimum_test_score" name="minimum_test_score" value="{{ old('minimum_test_score', $requirement->minimum_test_score) }}"
                                       min="0">
                                @error('minimum_test_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="required_documents" class="form-label">Required Documents</label>
                            <textarea class="form-control @error('required_documents') is-invalid @enderror"
                                      id="required_documents" name="required_documents" rows="2">{{ old('required_documents', $requirement->required_documents) }}</textarea>
                            <small class="text-muted">List all required documents</small>
                            @error('required_documents')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="language_requirements" class="form-label">Language Requirements</label>
                                <input type="text" class="form-control @error('language_requirements') is-invalid @enderror"
                                       id="language_requirements" name="language_requirements" value="{{ old('language_requirements', $requirement->language_requirements) }}"
                                       placeholder="e.g., IELTS 6.0, TOEFL 80">
                                @error('language_requirements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="language_test_score" class="form-label">Language Test Score</label>
                                <input type="number" class="form-control @error('language_test_score') is-invalid @enderror"
                                       id="language_test_score" name="language_test_score" value="{{ old('language_test_score', $requirement->language_test_score) }}"
                                       step="0.01" min="0">
                                @error('language_test_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="specific_requirements" class="form-label">Program-Specific Requirements</label>
                            <textarea class="form-control @error('specific_requirements') is-invalid @enderror"
                                      id="specific_requirements" name="specific_requirements" rows="3">{{ old('specific_requirements', $requirement->specific_requirements) }}</textarea>
                            @error('specific_requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="application_deadline" class="form-label">Application Deadline</label>
                                <input type="date" class="form-control @error('application_deadline') is-invalid @enderror"
                                       id="application_deadline" name="application_deadline" value="{{ old('application_deadline', $requirement->application_deadline?->format('Y-m-d')) }}">
                                @error('application_deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="application_fee" class="form-label">Application Fee (PKR)</label>
                                <input type="number" class="form-control @error('application_fee') is-invalid @enderror"
                                       id="application_fee" name="application_fee" value="{{ old('application_fee', $requirement->application_fee) }}"
                                       step="0.01" min="0">
                                @error('application_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="admission_process" class="form-label">Admission Process</label>
                            <textarea class="form-control @error('admission_process') is-invalid @enderror"
                                      id="admission_process" name="admission_process" rows="3">{{ old('admission_process', $requirement->admission_process) }}</textarea>
                            @error('admission_process')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="estimated_processing_days" class="form-label">Processing Time (days)</label>
                                <input type="number" class="form-control @error('estimated_processing_days') is-invalid @enderror"
                                       id="estimated_processing_days" name="estimated_processing_days" value="{{ old('estimated_processing_days', $requirement->estimated_processing_days) }}"
                                       min="1">
                                @error('estimated_processing_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="interview_required" class="form-label">Interview Required</label>
                                <input type="text" class="form-control @error('interview_required') is-invalid @enderror"
                                       id="interview_required" name="interview_required" value="{{ old('interview_required', $requirement->interview_required) }}"
                                       placeholder="Yes/No/Conditional">
                                @error('interview_required')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="portfolio_required" class="form-label">Portfolio Required</label>
                                <input type="text" class="form-control @error('portfolio_required') is-invalid @enderror"
                                       id="portfolio_required" name="portfolio_required" value="{{ old('portfolio_required', $requirement->portfolio_required) }}"
                                       placeholder="Yes/No/Conditional">
                                @error('portfolio_required')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="status"
                                       name="status" value="1" {{ old('status', $requirement->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('university.admission-requirements.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Requirement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

