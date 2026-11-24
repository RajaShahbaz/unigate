@extends('university.layouts.master')

@section('title', 'Create Form Step')
@section('page-title', 'Create New Form Step')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus"></i> Create New Form Step
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('university.custom-form.store-step') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="step_number" class="form-label">Step Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('step_number') is-invalid @enderror"
                                       id="step_number" name="step_number" value="{{ old('step_number') }}" 
                                       min="1" required>
                                @error('step_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="display_order" class="form-label">Display Order</label>
                                <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                                       id="display_order" name="display_order" value="{{ old('display_order', 0) }}" 
                                       min="0">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="step_title" class="form-label">Step Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('step_title') is-invalid @enderror"
                                   id="step_title" name="step_title" value="{{ old('step_title') }}" 
                                   placeholder="e.g., Personal Information" required>
                            @error('step_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="step_description" class="form-label">Step Description</label>
                            <input type="text" class="form-control @error('step_description') is-invalid @enderror"
                                   id="step_description" name="step_description" value="{{ old('step_description') }}" 
                                   placeholder="e.g., Tell us about yourself">
                            @error('step_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="step_icon" class="form-label">Icon (Font Awesome)</label>
                            <input type="text" class="form-control @error('step_icon') is-invalid @enderror"
                                   id="step_icon" name="step_icon" value="{{ old('step_icon', 'file-alt') }}" 
                                   placeholder="e.g., user, phone, graduation-cap">
                            <small class="text-muted">
                                Visit <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a> for icon names
                            </small>
                            @error('step_icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_required"
                                       name="is_required" value="1" {{ old('is_required', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_required">
                                    Required Step
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="status"
                                       name="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Next Step:</strong> After creating this step, you'll be able to add fields to it.
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('university.custom-form.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Step
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

