@extends('university.layouts.master')

@section('title', 'Edit Custom Field')
@section('page-title', 'Edit Custom Form Field')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-edit"></i> Edit Custom Field
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('university.form-fields.update', $field->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="field_name" class="form-label">Field Name (Unique Identifier) *</label>
                        <input type="text" 
                               class="form-control @error('field_name') is-invalid @enderror" 
                               id="field_name" 
                               name="field_name" 
                               value="{{ old('field_name', $field->field_name) }}"
                               required>
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
                               value="{{ old('field_label', $field->field_label) }}"
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
                                  rows="2">{{ old('field_description', $field->field_description) }}</textarea>
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
                            <option value="text" {{ $field->field_type == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="textarea" {{ $field->field_type == 'textarea' ? 'selected' : '' }}>Textarea</option>
                            <option value="number" {{ $field->field_type == 'number' ? 'selected' : '' }}>Number</option>
                            <option value="date" {{ $field->field_type == 'date' ? 'selected' : '' }}>Date</option>
                            <option value="email" {{ $field->field_type == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="url" {{ $field->field_type == 'url' ? 'selected' : '' }}>URL</option>
                            <option value="file" {{ $field->field_type == 'file' ? 'selected' : '' }}>File Upload</option>
                            <option value="select" {{ $field->field_type == 'select' ? 'selected' : '' }}>Dropdown</option>
                            <option value="checkbox" {{ $field->field_type == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                            <option value="radio" {{ $field->field_type == 'radio' ? 'selected' : '' }}>Radio</option>
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
                                @php
                                    $options = $field->field_options ? json_decode($field->field_options, true) : [];
                                @endphp
                                @if(count($options) > 0)
                                    @foreach($options as $option)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="field_options[]" value="{{ $option }}">
                                            <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="field_options[]" placeholder="Option 1">
                                        <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                @endif
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
                                   value="{{ old('file_types', $field->file_types) }}"
                                   placeholder="e.g., pdf,doc,docx">
                        </div>
                        <div class="mb-3">
                            <label for="max_file_size" class="form-label">Max File Size (KB)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="max_file_size" 
                                   name="max_file_size" 
                                   value="{{ old('max_file_size', $field->max_file_size) }}">
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="is_required" 
                               name="is_required"
                               {{ $field->is_required ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_required">
                            Required Field
                        </label>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="status" 
                               name="status"
                               {{ $field->status ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">
                            Active
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Field
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

