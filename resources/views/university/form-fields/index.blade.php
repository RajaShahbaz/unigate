@extends('university.layouts.master')

@section('title', 'Custom Form Fields')
@section('page-title', 'Custom Application Form Fields')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Create and manage custom fields for your application form</p>
    <a href="{{ route('university.form-fields.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Field
    </a>
</div>

@if($fields->count() > 0)
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="fieldsTable">
                    <thead class="table-light">
                        <tr>
                            <th width="40"><i class="fas fa-arrows-alt"></i></th>
                            <th>Field Name</th>
                            <th>Label</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Status</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fields as $field)
                            <tr data-id="{{ $field->id }}">
                                <td class="text-center">
                                    <i class="fas fa-grip-vertical text-muted" style="cursor: move;"></i>
                                </td>
                                <td><code>{{ $field->field_name }}</code></td>
                                <td>{{ $field->field_label }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($field->field_type) }}</span>
                                </td>
                                <td>
                                    @if($field->is_required)
                                        <span class="badge bg-warning">Required</span>
                                    @else
                                        <span class="badge bg-secondary">Optional</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('university.form-fields.toggle-status', $field->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-{{ $field->status ? 'success' : 'secondary' }}">
                                            {{ $field->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('university.form-fields.edit', $field->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteField({{ $field->id }})" title="Delete">
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
            <i class="fas fa-wpforms fa-5x text-muted mb-4"></i>
            <h4>No Custom Fields Yet</h4>
            <p class="text-muted mb-4">Create custom fields to collect additional information from applicants</p>
            <a href="{{ route('university.form-fields.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Your First Field
            </a>
        </div>
    </div>
@endif

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    function deleteField(id) {
        if (confirm('Are you sure you want to delete this field? This action cannot be undone if the field has responses.')) {
            const form = document.getElementById('deleteForm');
            form.action = '{{ url("university/form-fields") }}/' + id;
            form.submit();
        }
    }

    // Sortable for drag and drop reordering
    @if($fields->count() > 0)
        const el = document.querySelector('#fieldsTable tbody');
        const sortable = Sortable.create(el, {
            handle: '.fa-grip-vertical',
            animation: 150,
            onEnd: function(evt) {
                const orders = [];
                const rows = document.querySelectorAll('#fieldsTable tbody tr');
                
                rows.forEach((row, index) => {
                    orders.push({
                        id: row.dataset.id,
                        sort_order: index
                    });
                });

                // Save new order
                fetch('{{ route("university.form-fields.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        field_orders: orders
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-success alert-dismissible fade show';
                        alert.innerHTML = `
                            <i class="fas fa-check-circle"></i> Field order updated successfully
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.content-wrapper').insertBefore(alert, document.querySelector('.content-wrapper').firstChild);
                        
                        setTimeout(() => alert.remove(), 3000);
                    }
                });
            }
        });
    @endif
</script>
@endpush

