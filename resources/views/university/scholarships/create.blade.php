@extends('university.layouts.master')

@section('title', 'Add Scholarship')
@section('page-title', 'Add New Scholarship')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus"></i> Add New Scholarship
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('university.scholarships.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Scholarship Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="scholarship_type" class="form-label">Scholarship Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('scholarship_type') is-invalid @enderror" 
                                        id="scholarship_type" name="scholarship_type" required>
                                    <option value="">Select Type</option>
                                    <option value="merit" {{ old('scholarship_type') == 'merit' ? 'selected' : '' }}>Merit-Based</option>
                                    <option value="need-based" {{ old('scholarship_type') == 'need-based' ? 'selected' : '' }}>Need-Based</option>
                                    <option value="sports" {{ old('scholarship_type') == 'sports' ? 'selected' : '' }}>Sports</option>
                                    <option value="academic" {{ old('scholarship_type') == 'academic' ? 'selected' : '' }}>Academic Excellence</option>
                                    <option value="research" {{ old('scholarship_type') == 'research' ? 'selected' : '' }}>Research</option>
                                    <option value="minority" {{ old('scholarship_type') == 'minority' ? 'selected' : '' }}>Minority</option>
                                    <option value="international" {{ old('scholarship_type') == 'international' ? 'selected' : '' }}>International Students</option>
                                    <option value="other" {{ old('scholarship_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('scholarship_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="amount_type" class="form-label">Amount Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('amount_type') is-invalid @enderror" 
                                        id="amount_type" name="amount_type" required>
                                    <option value="fixed" {{ old('amount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                    <option value="percentage" {{ old('amount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="full" {{ old('amount_type') == 'full' ? 'selected' : '' }}>Full Scholarship</option>
                                </select>
                                @error('amount_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row" id="amount-fields">
                            <div class="col-md-6 mb-3" id="fixed-amount-field">
                                <label for="amount" class="form-label">Amount (PKR)</label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="percentage-field" style="display: none;">
                                <label for="percentage" class="form-label">Percentage (%)</label>
                                <input type="number" class="form-control @error('percentage') is-invalid @enderror" 
                                       id="percentage" name="percentage" value="{{ old('percentage') }}" 
                                       step="0.01" min="0" max="100">
                                @error('percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="eligibility_criteria" class="form-label">Eligibility Criteria</label>
                            <textarea class="form-control @error('eligibility_criteria') is-invalid @enderror" 
                                      id="eligibility_criteria" name="eligibility_criteria" rows="3">{{ old('eligibility_criteria') }}</textarea>
                            @error('eligibility_criteria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="total_slots" class="form-label">Total Slots</label>
                                <input type="number" class="form-control @error('total_slots') is-invalid @enderror" 
                                       id="total_slots" name="total_slots" value="{{ old('total_slots') }}" min="1">
                                <small class="text-muted">Leave empty for unlimited</small>
                                @error('total_slots')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="available_slots" class="form-label">Available Slots</label>
                                <input type="number" class="form-control @error('available_slots') is-invalid @enderror" 
                                       id="available_slots" name="available_slots" value="{{ old('available_slots') }}" min="0">
                                @error('available_slots')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="application_start_date" class="form-label">Application Start Date</label>
                                <input type="date" class="form-control @error('application_start_date') is-invalid @enderror" 
                                       id="application_start_date" name="application_start_date" value="{{ old('application_start_date') }}">
                                @error('application_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="application_end_date" class="form-label">Application End Date</label>
                                <input type="date" class="form-control @error('application_end_date') is-invalid @enderror" 
                                       id="application_end_date" name="application_end_date" value="{{ old('application_end_date') }}">
                                @error('application_end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                       id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="renewable" 
                                           name="renewable" value="1" {{ old('renewable') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="renewable">
                                        Renewable Scholarship
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3" id="renewable-years-field" style="display: {{ old('renewable') ? 'block' : 'none' }};">
                                <label for="renewable_years" class="form-label">Renewable for (years)</label>
                                <input type="number" class="form-control @error('renewable_years') is-invalid @enderror" 
                                       id="renewable_years" name="renewable_years" value="{{ old('renewable_years') }}" min="1">
                                @error('renewable_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('university.scholarships.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Scholarship
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('amount_type').addEventListener('change', function() {
    const fixedField = document.getElementById('fixed-amount-field');
    const percentageField = document.getElementById('percentage-field');
    
    if (this.value === 'percentage') {
        fixedField.style.display = 'none';
        percentageField.style.display = 'block';
    } else if (this.value === 'fixed') {
        fixedField.style.display = 'block';
        percentageField.style.display = 'none';
    } else {
        fixedField.style.display = 'none';
        percentageField.style.display = 'none';
    }
});

document.getElementById('renewable').addEventListener('change', function() {
    const renewableYearsField = document.getElementById('renewable-years-field');
    renewableYearsField.style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush

