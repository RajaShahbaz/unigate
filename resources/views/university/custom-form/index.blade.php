@extends('university.layouts.master')

@section('title', 'Multi-Step Form Builder')
@section('page-title', 'Multi-Step Application Form Builder')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-th-list"></i> Multi-Step Form Builder
                    </h5>
                    <div>
                        @if($steps->isEmpty())
                            <form action="{{ route('university.custom-form.use-template') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-magic"></i> Use Default Template
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('university.custom-form.create-step') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Step
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($steps->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-th-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No form steps configured</h5>
                            <p class="text-muted">You can start with our default 7-step template or create your own custom steps.</p>
                            <div class="mt-4">
                                <form action="{{ route('university.custom-form.use-template') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg me-2">
                                        <i class="fas fa-magic"></i> Use Default Template (Recommended)
                                    </button>
                                </form>
                                <a href="{{ route('university.custom-form.create-step') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-plus"></i> Create Custom Steps
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Info:</strong> This is your multi-step admission form that will be shown in the mobile app. 
                            Students will fill this form step by step with progress tracking.
                        </div>

                        <div class="row">
                            @foreach($steps as $step)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 {{ $step->status ? 'border-primary' : 'border-secondary' }}">
                                        <div class="card-header bg-{{ $step->status ? 'primary' : 'secondary' }} text-white">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-{{ $step->step_icon }}"></i>
                                                    <strong>Step {{ $step->step_number }}: {{ $step->step_title }}</strong>
                                                </div>
                                                <div>
                                                    @if($step->is_required)
                                                        <span class="badge bg-warning text-dark">Required</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-3">{{ $step->step_description }}</p>
                                            
                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-list"></i> 
                                                    <strong>{{ $step->fields->count() }} fields</strong>
                                                </small>
                                            </div>

                                            @if($step->fields->isNotEmpty())
                                                <div class="border rounded p-2 mb-3" style="max-height: 150px; overflow-y: auto;">
                                                    <small>
                                                        @foreach($step->fields as $field)
                                                            <div class="mb-1">
                                                                <i class="fas fa-{{ $field->field_icon ?? 'circle' }} text-muted"></i>
                                                                {{ $field->field_label }}
                                                                @if($field->is_required)
                                                                    <span class="text-danger">*</span>
                                                                @endif
                                                                <span class="badge bg-light text-dark">{{ $field->field_type }}</span>
                                                            </div>
                                                        @endforeach
                                                    </small>
                                                </div>
                                            @endif

                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('university.custom-form.manage-fields', $step->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-cog"></i> Manage Fields
                                                </a>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('university.custom-form.edit-step', $step->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('university.custom-form.destroy-step', $step->id) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('Are you sure you want to delete this step and all its fields?');"
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($steps->isNotEmpty())
        <!-- Quick Stats -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stat-card primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Steps</h6>
                                <h3 class="mb-0">{{ $steps->count() }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-th-list fa-2x text-primary"></i>
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
                                <h6 class="text-muted mb-1">Active Steps</h6>
                                <h3 class="mb-0">{{ $steps->where('status', true)->count() }}</h3>
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
                                <h6 class="text-muted mb-1">Total Fields</h6>
                                <h3 class="mb-0">{{ $steps->sum(fn($s) => $s->fields->count()) }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-list fa-2x text-warning"></i>
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
                                <h6 class="text-muted mb-1">Required Fields</h6>
                                <h3 class="mb-0">{{ $steps->sum(fn($s) => $s->fields->where('is_required', true)->count()) }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-asterisk fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

