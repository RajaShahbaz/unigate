@extends('university.layouts.master')

@section('title', 'Create Custom Field')
@section('page-title', 'Create Custom Form Field')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus"></i> Add New Custom Field
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('university.form-fields.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="field_name" class="form-label">Field Name (Unique Identifier) *</label>
                        <input type="text" 
                               class="form-control @error('field_name') is-invalid @enderror" 
                               id="field_name" 
                               name="field_name" 
                               value="{{ old('field_name') }}"
                               placeholder="e.g., portfolio_url"
                               required>
                        <small class="text-muted">Use lowercase letters, numbers, and underscores only</small>
                        @error('field_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="field_label" class="form-label">Field Label (Display Name) *</label>
                        <input type="text" 
                               class="form-control @error('field_label') is-invalid @enderror" 
                               id="field_label" 
                               name="field_label" 
                               value="{{ old('field_label') }}"
                               placeholder="e.g., Portfolio URL"
                               required>
                        @error('field_label')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="field_description" class="form-label">Field Description</label>
                        <textarea class="form-control @error('field_description') is-invalid @enderror" 
                                  id="field_description" 
                                  name="field_description" 
                                  rows="2"
                                  placeholder="Help text for students">{{ old('field_description') }}</textarea>
                        @error('field_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="field_type" class="form-label">Field Type *</label>
                        <select class="form-select @error('field_type') is-invalid @enderror" 
                                id="field_type" 
                                name="field_type"
                                onchange="handleFieldTypeChange()"
                                required>
                            <option value="">Select Field Type</option>
                            <option value="text" {{ old('field_type') == 'text' ? 'selected' : '' }}>Text (Single Line)</option>
                            <option value="textarea" {{ old('field_type') == 'textarea' ? 'selected' : '' }}>Textarea (Multi-line)</option>
                            <option value="number" {{ old('field_type') == 'number' ? 'selected' : '' }}>Number</option>
                            <option value="date" {{ old('field_type') == 'date' ? 'selected' : '' }}>Date</option>
                            <option value="email" {{ old('field_type') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="url" {{ old('field_type') == 'url' ? 'selected' : '' }}>URL</option>
                            <option value="file" {{ old('field_type') == 'file' ? 'selected' : '' }}>File Upload</option>
                            <option value="select" {{ old('field_type') == 'select' ? 'selected' : '' }}>Dropdown (Select)</option>
                            <option value="checkbox" {{ old('field_type') == 'checkbox' ? 'selected' : '' }}>Checkbox (Multiple)</option>
                            <option value="radio" {{ old('field_type') == 'radio' ? 'selected' : '' }}>Radio (Single)</option>
                        </select>
                        @error('field_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Options for select/checkbox/radio -->
                    <div id="optionsField" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Field Options</label>
                            <div id="optionsList">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="field_options[]" placeholder="Option 1">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addOption()">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>
                    </div>

                    <!-- File settings -->
                    <div id="fileSettings" style="display: none;">
                        <div class="mb-3">
                            <label for="file_types" class="form-label">Allowed File Types</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="file_types" 
                                   name="file_types" 
                                   value="{{ old('file_types', 'pdf,doc,docx') }}"
                                   placeholder="e.g., pdf,doc,docx">
                            <small class="text-muted">Comma-separated file extensions</small>
                        </div>
                        <div class="mb-3">
                            <label for="max_file_size" class="form-label">Max File Size (KB)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="max_file_size" 
                                   name="max_file_size" 
                                   value="{{ old('max_file_size', '5120') }}"
                                   placeholder="5120">
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="is_required" 
                               name="is_required"
                               {{ old('is_required') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_required">
                            Required Field
                        </label>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="status" 
                               name="status"
                               checked>
                        <label class="form-check-label" for="status">
                            Active (visible to students)
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Field
                        </button>
                        <a href="{{ route('university.form-fields.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function handleFieldTypeChange() {
        const type = document.getElementById('field_type').value;
        const optionsField = document.getElementById('optionsField');
        const fileSettings = document.getElementById('fileSettings');
        
        if (['select', 'checkbox', 'radio'].includes(type)) {
            optionsField.style.display = 'block';
        } else {
            optionsField.style.display = 'none';
        }
        
        if (type === 'file') {
            fileSettings.style.display = 'block';
        } else {
            fileSettings.style.display = 'none';
        }
    }

    function addOption() {
        const optionsList = document.getElementById('optionsList');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" class="form-control" name="field_options[]" placeholder="New Option">
            <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
                <i class="fas fa-minus"></i>
            </button>
        `;
        optionsList.appendChild(div);
    }

    function removeOption(button) {
        const optionsList = document.getElementById('optionsList');
        if (optionsList.children.length > 1) {
            button.closest('.input-group').remove();
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        handleFieldTypeChange();
    });
</script>
@endpush

