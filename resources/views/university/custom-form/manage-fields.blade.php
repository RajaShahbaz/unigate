@extends('university.layouts.master')

@section('title', 'Manage Fields')
@section('page-title', 'Manage Fields - Step ' . $step->step_number)

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-{{ $step->step_icon }}"></i>
                                Step {{ $step->step_number }}: {{ $step->step_title }}
                            </h5>
                            <small>{{ $step->step_description }}</small>
                        </div>
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addFieldModal">
                            <i class="fas fa-plus"></i> Add Field
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($step->fields->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No fields in this step</h5>
                            <p class="text-muted">Add fields that users will fill in this step.</p>
                            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addFieldModal">
                                <i class="fas fa-plus"></i> Add First Field
                            </button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Field Name</th>
                                        <th>Label</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($step->fields as $field)
                                        <tr>
                                            <td>{{ $field->display_order }}</td>
                                            <td><code>{{ $field->field_name }}</code></td>
                                            <td>
                                                <strong>{{ $field->field_label }}</strong>
                                                @if($field->helper_text)
                                                    <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($field->helper_text, 40) }}</small>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-secondary">{{ $field->field_type }}</span></td>
                                            <td>
                                                @if($field->is_required)
                                                    <span class="badge bg-danger">Required</span>
                                                @else
                                                    <span class="badge bg-light text-dark">Optional</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($field->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-primary edit-field-btn"
                                                            data-field-id="{{ $field->id }}"
                                                            data-field-name="{{ $field->field_name }}"
                                                            data-field-type="{{ $field->field_type }}"
                                                            data-field-label="{{ $field->field_label }}"
                                                            data-field-placeholder="{{ $field->field_placeholder }}"
                                                            data-field-helper="{{ $field->helper_text }}"
                                                            data-field-required="{{ $field->is_required ? '1' : '0' }}"
                                                            data-field-status="{{ $field->status ? '1' : '0' }}"
                                                            data-field-order="{{ $field->display_order }}"
                                                            data-field-options='@json($field->options_array)'>
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger delete-field-btn"
                                                            data-field-id="{{ $field->id }}"
                                                            data-field-label="{{ $field->field_label }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('university.custom-form.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Form Builder
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Field Modal -->
<div class="modal fade" id="addFieldModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Field</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('university.custom-form.store-field', $step->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="field_name" class="form-label">Field Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="field_name" name="field_name" required
                                   placeholder="e.g., first_name">
                            <small class="text-muted">Use lowercase with underscores (no spaces)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="field_type" class="form-label">Field Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="field_type" name="field_type" required>
                                <option value="text">Text Input</option>
                                <option value="email">Email</option>
                                <option value="tel">Phone Number</option>
                                <option value="number">Number</option>
                                <option value="date">Date</option>
                                <option value="textarea">Text Area</option>
                                <option value="select">Dropdown Select</option>
                                <option value="radio">Radio Buttons</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="file">File Upload</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="field_label" class="form-label">Field Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="field_label" name="field_label" required
                               placeholder="e.g., First Name">
                    </div>

                    <div class="mb-3">
                        <label for="field_placeholder" class="form-label">Placeholder Text</label>
                        <input type="text" class="form-control" id="field_placeholder" name="field_placeholder"
                               placeholder="e.g., Enter your first name">
                    </div>

                    <!-- Course Selection for interest_id field -->
                    <div class="mb-3" id="course-selection-field" style="display: none;">
                        <label class="form-label">Select Courses/Programs <span class="text-danger">*</span></label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @if(isset($courses) && $courses->count() > 0)
                                @foreach($courses as $course)
                                    <div class="form-check">
                                        <input class="form-check-input course-checkbox" type="checkbox" 
                                               name="selected_course_ids[]" 
                                               value="{{ $course->id }}" 
                                               id="course_{{ $course->id }}">
                                        <label class="form-check-label" for="course_{{ $course->id }}">
                                            {{ $course->name }}
                                            @if($course->pivot->duration_years)
                                                <small class="text-muted">({{ $course->pivot->duration_years }} years)</small>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No courses/programs available. Please add courses first in Programs section.</p>
                            @endif
                        </div>
                        <small class="text-muted">Select the courses that should appear in the dropdown</small>
                    </div>

                    <!-- Regular Options Field for other select/radio/checkbox fields -->
                    <div class="mb-3" id="options-field" style="display: none;">
                        <label for="field_options" class="form-label">Options (one per line)</label>
                        <textarea class="form-control" id="field_options" name="field_options" rows="4"
                                  placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
                        <small class="text-muted">For select, radio, or checkbox fields (not for interest_id)</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="field_icon" class="form-label">Icon (Font Awesome)</label>
                            <input type="text" class="form-control" id="field_icon" name="field_icon"
                                   placeholder="e.g., user">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" class="form-control" id="display_order" name="display_order"
                                   value="0" min="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="helper_text" class="form-label">Helper Text</label>
                        <input type="text" class="form-control" id="helper_text" name="helper_text"
                               placeholder="Additional help text for users">
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_required" name="is_required" value="1">
                            <label class="form-check-label" for="is_required">
                                Required Field
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                            <label class="form-check-label" for="status">
                                Active
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add Field
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Field Modal -->
<div class="modal fade" id="editFieldModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Field</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editFieldForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_field_id" name="field_id">
                    
                    <div class="mb-3">
                        <label for="edit_field_label" class="form-label">Field Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_field_label" name="field_label" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_field_placeholder" class="form-label">Placeholder Text</label>
                        <input type="text" class="form-control" id="edit_field_placeholder" name="field_placeholder">
                    </div>

                    <!-- Course Selection for interest_id field (Edit) -->
                    <div class="mb-3" id="edit-course-selection-field" style="display: none;">
                        <label class="form-label">Select Courses/Programs <span class="text-danger">*</span></label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @if(isset($courses) && $courses->count() > 0)
                                @foreach($courses as $course)
                                    <div class="form-check">
                                        <input class="form-check-input edit-course-checkbox" type="checkbox" 
                                               name="selected_course_ids[]" 
                                               value="{{ $course->id }}" 
                                               id="edit_course_{{ $course->id }}">
                                        <label class="form-check-label" for="edit_course_{{ $course->id }}">
                                            {{ $course->name }}
                                            @if($course->pivot->duration_years)
                                                <small class="text-muted">({{ $course->pivot->duration_years }} years)</small>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No courses/programs available. Please add courses first in Programs section.</p>
                            @endif
                        </div>
                        <small class="text-muted">Select the courses that should appear in the dropdown</small>
                    </div>

                    <!-- Regular Options Field for other select/radio/checkbox fields (Edit) -->
                    <div class="mb-3" id="edit-options-field" style="display: none;">
                        <label for="edit_field_options" class="form-label">Options (one per line)</label>
                        <textarea class="form-control" id="edit_field_options" name="field_options" rows="4"
                                  placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
                        <small class="text-muted">For select, radio, or checkbox fields (not for interest_id)</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_display_order" class="form-label">Display Order</label>
                            <input type="number" class="form-control" id="edit_display_order" name="display_order" min="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_helper_text" class="form-label">Helper Text</label>
                        <input type="text" class="form-control" id="edit_helper_text" name="helper_text">
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_is_required" name="is_required" value="1">
                            <label class="form-check-label" for="edit_is_required">
                                Required Field
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_status" name="status" value="1">
                            <label class="form-check-label" for="edit_status">
                                Active
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Field
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Handle field type and field name changes
const fieldTypeSelect = document.getElementById('field_type');
const fieldNameInput = document.getElementById('field_name');
const optionsField = document.getElementById('options-field');
const courseSelectionField = document.getElementById('course-selection-field');

function updateOptionsFieldVisibility() {
    const fieldType = fieldTypeSelect.value;
    const fieldName = fieldNameInput.value;
    const isInterestId = fieldName === 'interest_id';
    const showOptions = ['select', 'radio', 'checkbox'].includes(fieldType);
    
    if (isInterestId && fieldType === 'select') {
        // Show course selection for interest_id select fields
        courseSelectionField.style.display = 'block';
        optionsField.style.display = 'none';
    } else if (showOptions) {
        // Show regular options field for other select/radio/checkbox fields
        courseSelectionField.style.display = 'none';
        optionsField.style.display = 'block';
    } else {
        // Hide both for other field types
        courseSelectionField.style.display = 'none';
        optionsField.style.display = 'none';
    }
}

fieldTypeSelect.addEventListener('change', updateOptionsFieldVisibility);
fieldNameInput.addEventListener('input', updateOptionsFieldVisibility);

// Edit field button handler
document.querySelectorAll('.edit-field-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const fieldId = this.dataset.fieldId;
        const fieldName = this.dataset.fieldName;
        const fieldType = this.dataset.fieldType;
        const fieldLabel = this.dataset.fieldLabel;
        const fieldPlaceholder = this.dataset.fieldPlaceholder || '';
        const fieldHelper = this.dataset.fieldHelper || '';
        const fieldRequired = this.dataset.fieldRequired === '1';
        const fieldStatus = this.dataset.fieldStatus === '1';
        const fieldOrder = this.dataset.fieldOrder || '0';
        const fieldOptions = this.dataset.fieldOptions ? JSON.parse(this.dataset.fieldOptions) : [];
        
        // Populate edit form
        document.getElementById('edit_field_id').value = fieldId;
        document.getElementById('edit_field_label').value = fieldLabel;
        document.getElementById('edit_field_placeholder').value = fieldPlaceholder;
        document.getElementById('edit_helper_text').value = fieldHelper;
        document.getElementById('edit_display_order').value = fieldOrder;
        document.getElementById('edit_is_required').checked = fieldRequired;
        document.getElementById('edit_status').checked = fieldStatus;
        
        // Set form action
        const stepId = {{ $step->id }};
        document.getElementById('editFieldForm').action = `/university/custom-form/step/${stepId}/field/${fieldId}`;
        
        // Handle options display based on field type and name
        const editOptionsField = document.getElementById('edit-options-field');
        const editCourseSelectionField = document.getElementById('edit-course-selection-field');
        
        if (fieldName === 'interest_id' && fieldType === 'select') {
            // Show course selection and pre-select courses
            editCourseSelectionField.style.display = 'block';
            editOptionsField.style.display = 'none';
            
            // Uncheck all first
            document.querySelectorAll('.edit-course-checkbox').forEach(cb => cb.checked = false);
            
            // Check selected courses
            if (Array.isArray(fieldOptions) && fieldOptions.length > 0) {
                fieldOptions.forEach(option => {
                    const courseId = option.value || option.id;
                    const checkbox = document.getElementById(`edit_course_${courseId}`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }
        } else if (['select', 'radio', 'checkbox'].includes(fieldType)) {
            // Show regular options field
            editCourseSelectionField.style.display = 'none';
            editOptionsField.style.display = 'block';
            
            // Populate options textarea
            let optionsText = '';
            if (Array.isArray(fieldOptions) && fieldOptions.length > 0) {
                optionsText = fieldOptions.map(opt => {
                    return typeof opt === 'object' ? (opt.label || opt.name || opt.value) : opt;
                }).join('\n');
            }
            document.getElementById('edit_field_options').value = optionsText;
        } else {
            // Hide both
            editCourseSelectionField.style.display = 'none';
            editOptionsField.style.display = 'none';
        }
        
        // Show modal
        const editModal = new bootstrap.Modal(document.getElementById('editFieldModal'));
        editModal.show();
    });
});

// Handle edit form submission
document.getElementById('editFieldForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = this.action;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.error) {
            location.reload();
        } else {
            alert('Failed to update field: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update field. Please try again.');
    });
});

// Delete field confirmation
document.querySelectorAll('.delete-field-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const fieldId = this.dataset.fieldId;
        const fieldLabel = this.dataset.fieldLabel;
        
        if (confirm(`Are you sure you want to delete the field "${fieldLabel}"?`)) {
            fetch(`{{ route('university.custom-form.manage-fields', $step->id) }}/../field/${fieldId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    location.reload();
                } else {
                    alert('Failed to delete field: ' + data.message);
                }
            });
        }
    });
});
</script>
@endpush

