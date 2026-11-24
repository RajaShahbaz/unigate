@extends('university.layouts.master')

@section('title', 'Edit Form Step')
@section('page-title', 'Edit Form Step')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit"></i> Edit Form Step {{ $step->step_number }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('university.custom-form.update-step', $step->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Step Number</label>
                            <input type="text" class="form-control" value="{{ $step->step_number }}" disabled>
                            <small class="text-muted">Step number cannot be changed</small>
                        </div>

                        <div class="mb-3">
                            <label for="step_title" class="form-label">Step Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('step_title') is-invalid @enderror"
                                   id="step_title" name="step_title" value="{{ old('step_title', $step->step_title) }}"
                                   placeholder="e.g., Personal Information" required>
                            @error('step_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="step_description" class="form-label">Step Description</label>
                            <input type="text" class="form-control @error('step_description') is-invalid @enderror"
                                   id="step_description" name="step_description" value="{{ old('step_description', $step->step_description) }}"
                                   placeholder="e.g., Tell us about yourself">
                            @error('step_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="step_icon" class="form-label">Icon (Font Awesome)</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-{{ $step->step_icon }}"></i>
                                </span>
                                <input type="text" class="form-control @error('step_icon') is-invalid @enderror"
                                       id="step_icon" name="step_icon" value="{{ old('step_icon', $step->step_icon) }}"
                                       placeholder="e.g., user, phone, graduation-cap">
                            </div>
                            <small class="text-muted">
                                Visit <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a> for icon names
                            </small>
                            @error('step_icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                                   id="display_order" name="display_order" value="{{ old('display_order', $step->display_order) }}"
                                   min="0">
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_required"
                                       name="is_required" value="1" {{ old('is_required', $step->is_required) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_required">
                                    Required Step
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="status"
                                       name="status" value="1" {{ old('status', $step->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Note:</strong> Fields are managed separately. Click "Manage Fields" to add/edit fields for this step.
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('university.custom-form.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <div>
                                <a href="{{ route('university.custom-form.manage-fields', $step->id) }}" class="btn btn-info me-2">
                                    <i class="fas fa-cog"></i> Manage Fields
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Step
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

