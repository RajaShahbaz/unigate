@extends('university.layouts.master')

@section('title', 'Application Details')
@section('page-title', 'Application Details')

@section('content')
<style>
.reupload_alert{
    display: block !important;
}
    </style>
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Application #{{ $application->application_number }}</h3>
    <a href="{{ route('university.applications.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="row">
    <!-- Left Column - Application Details -->
    <div class="col-lg-8">
        <!-- Student Information -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-user"></i> Student Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Full Name:</strong><br>
                        {{ $application->full_name }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong><br>
                        {{ $application->email }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phone:</strong><br>
                        {{ $application->phone }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date of Birth:</strong><br>
                        {{ $application->date_of_birth ? $application->date_of_birth->format('M d, Y') : 'N/A' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong><br>
                        {{ ucfirst($application->gender) }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Nationality:</strong><br>
                        {{ $application->nationality }}
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Current Address:</strong><br>
                        {{ $application->current_address }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-graduation-cap"></i> Academic Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Previous School/Institution:</strong><br>
                        {{ $application->previous_school ?? 'Not provided' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Previous Degree:</strong><br>
                        {{ $application->previous_degree ?? 'Not provided' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>GPA (0-4 scale):</strong><br>
                        {{ $application->previous_gpa ?? 'Not provided' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Graduation Year:</strong><br>
                        {{ $application->graduation_year ?? 'Not provided' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-file-pdf"></i> Uploaded Documents
                </div>
                <small class="text-muted">Review, download, or request a re-upload</small>
            </div>
            <div class="card-body">
                @php
                    $documentDefinitions = [
                        'transcript_file' => ['label' => 'Transcript'],
                        'id_document_file' => ['label' => 'ID Document'],
                        'photo_file' => ['label' => 'Passport Photo'],
                        'recommendation_letter_file' => ['label' => 'Recommendation Letter'],
                    ];
                    $documentRequests = $application->documentReuploadRequests->groupBy('field_name');
                @endphp
                <div class="row">
                    @forelse($documentDefinitions as $field => $meta)
                        @php
                            $filePath = $application->{$field};
                            $history = $documentRequests->get($field, collect());
                            $latestRequest = $history->sortByDesc('created_at')->first();
                        @endphp
                        <div class="col-md-6 mb-4">
                            <div class="border rounded p-3 h-100 document-tile">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>{{ $meta['label'] }}</strong>
                                        @if($latestRequest && $latestRequest->status === 'pending')
                                            <span class="badge bg-warning text-dark ms-2">Re-upload requested</span>
                                        @elseif($latestRequest)
                                            <span class="badge bg-success ms-2">Updated</span>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        @if($filePath)
                                            <a href="{{ Storage::url($filePath) }}" target="_blank"
                                               class="btn btn-sm btn-outline-primary" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger request-reupload-btn"
                                                data-field-name="{{ $field }}"
                                                data-field-label="{{ $meta['label'] }}"
                                                data-has-file="{{ $filePath ? '1' : '0' }}"
                                                {{ !$filePath ? 'disabled' : '' }}
                                                title="Request new upload">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </div>
                                </div>

                                <p class="text-muted small mb-3">
                                    @if($filePath)
                                        Uploaded {{ optional($application->updated_at)->diffForHumans() }}
                                    @else
                                        Not uploaded yet
                                    @endif
                                </p>

                                @if($latestRequest)
                                    <div class="alert alert-{{ $latestRequest->status === 'pending' ? 'warning' : 'success' }} reupload_alert py-2 mb-0">
                                        <div class="small mb-1"><strong>Reason:</strong> {{ $latestRequest->reason }}</div>
                                        <div class="text-muted small">
                                            Requested {{ $latestRequest->created_at->format('M d, Y H:i') }}
                                            @if($latestRequest->requester)
                                                by {{ $latestRequest->requester->name }}
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-muted small">No re-upload requested.</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-3">
                            No documents configured.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Document Re-upload Modal -->
        <div class="modal fade" id="documentReuploadModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="documentReuploadForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Request Re-upload: <span id="documentFieldLabel"></span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="field_name" id="documentFieldName">
                            <div class="mb-3">
                                <label class="form-label">Reason <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="reason" id="documentReuploadReason"
                                          rows="4" placeholder="Provide clear instructions for the student" required></textarea>
                            </div>
                            <div class="alert alert-danger d-none" id="documentReuploadError"></div>
                            <p class="text-muted small mb-0">
                                The student will receive a push notification with a deep link to this field.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-paper-plane"></i> Send Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Custom Fields Responses -->
        @if($application->fieldResponses->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-wpforms"></i> Custom Field Responses
                </div>
                <div class="card-body">
                    @foreach($application->fieldResponses as $response)
                        <div class="mb-3">
                            <strong>{{ $response->field->field_label }}:</strong><br>
                            @if($response->field->field_type === 'file')
                                <a href="{{ Storage::url($response->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                            @else
                                {{ $response->response_value ?? 'Not provided' }}
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Application Message -->
        @if($application->application_message)
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-comment"></i> Personal Statement
                </div>
                <div class="card-body">
                    {{ $application->application_message }}
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column - Status & Actions -->
    <div class="col-lg-4">
        <!-- Application Status -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Application Status
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Current Status:</strong><br>
                    @php
                        $statusColors = [
                            'submitted' => 'primary',
                            'documents_pending' => 'warning',
                            'under_review' => 'warning',
                            'shortlisted' => 'info',
                            'interview_scheduled' => 'info',
                            'accepted' => 'success',
                            'rejected' => 'danger',
                        ];
                        $color = $statusColors[$application->status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $color }} badge-status fs-6">
                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                    </span>
                </div>

                <div class="mb-3">
                    <strong>Program:</strong><br>
                    {{ $application->interest->name ?? 'N/A' }}
                </div>

                <div class="mb-3">
                    <strong>Submitted:</strong><br>
                    {{ $application->submitted_at ? $application->submitted_at->format('M d, Y H:i') : 'N/A' }}
                </div>

                @if($application->reviewed_at)
                    <div class="mb-3">
                        <strong>Reviewed:</strong><br>
                        {{ $application->reviewed_at->format('M d, Y H:i') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Update Status -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit"></i> Update Status
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('university.applications.status', $application->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="submitted" {{ $application->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="documents_pending" {{ $application->status == 'documents_pending' ? 'selected' : '' }}>Documents Pending</option>
                            <option value="under_review" {{ $application->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="interview_scheduled" {{ $application->status == 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                            <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Admin Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="4">{{ $application->admin_notes }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rejection Reason (if rejected)</label>
                        <textarea name="rejection_reason" class="form-control" rows="3">{{ $application->rejection_reason }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Update Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('documentReuploadModal');
    const modal = new bootstrap.Modal(modalElement);
    const fieldInput = document.getElementById('documentFieldName');
    const fieldLabel = document.getElementById('documentFieldLabel');
    const reasonInput = document.getElementById('documentReuploadReason');
    const errorBox = document.getElementById('documentReuploadError');
    const form = document.getElementById('documentReuploadForm');

    document.querySelectorAll('.request-reupload-btn').forEach((button) => {
        button.addEventListener('click', () => {
            if (button.dataset.hasFile !== '1') {
                alert('This document has not been uploaded yet.');
                return;
            }

            fieldInput.value = button.dataset.fieldName;
            fieldLabel.textContent = button.dataset.fieldLabel;
            reasonInput.value = '';
            errorBox.classList.add('d-none');
            errorBox.textContent = '';
            modal.show();
        });
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        errorBox.classList.add('d-none');

        const formData = new FormData(form);

        fetch('{{ route('university.applications.documents.request-reupload', $application->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(async (response) => {
            const data = await response.json().catch(() => ({}));

            if (response.ok && !data.error) {
                modal.hide();
                window.location.reload();
                return;
            }

            const message = data?.message || 'Failed to submit re-upload request.';
            throw {
                message,
                errors: data?.data ?? null,
            };
        })
        .catch((error) => {
            errorBox.classList.remove('d-none');
            if (error?.errors) {
                const firstError = Object.values(error.errors)[0];
                errorBox.textContent = Array.isArray(firstError) ? firstError[0] : firstError;
            } else {
                errorBox.textContent = error?.message || 'Something went wrong.';
            }
        });
    });
});
</script>
@endpush

