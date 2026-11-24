<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\DocumentReuploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ApplicationDocumentController extends Controller
{
    /**
     * Default document fields present on applications table.
     */
    private const DEFAULT_DOCUMENTS = [
        'transcript_file' => [
            'label' => 'Transcript',
            'step_number' => 5,
            'highlight_field' => 'transcript_file',
        ],
        'id_document_file' => [
            'label' => 'ID Document',
            'step_number' => 5,
            'highlight_field' => 'id_document_file',
        ],
        'photo_file' => [
            'label' => 'Passport Photo',
            'step_number' => 5,
            'highlight_field' => 'photo_file',
        ],
        'recommendation_letter_file' => [
            'label' => 'Recommendation Letter',
            'step_number' => 5,
            'highlight_field' => 'recommendation_letter_file',
        ],
    ];

    /**
     * Request a document re-upload for a specific field.
     */
    public function requestReupload(Request $request, $applicationId)
    {
        DB::beginTransaction();
        $admin = Auth::guard('university')->user();
        $application = Application::byUniversity($admin->university_id)
            ->with('user')
            ->findOrFail($applicationId);

        $documentKeys = array_keys(self::DEFAULT_DOCUMENTS);

        $validated = $request->validate([
            'field_name' => ['required', Rule::in($documentKeys)],
            'reason' => 'required|string|min:10|max:1000',
        ]);

        $fieldName = $validated['field_name'];
        $documentMeta = self::DEFAULT_DOCUMENTS[$fieldName];

        if (empty($application->{$fieldName})) {
            return response()->json([
                'error' => true,
                'message' => 'Selected document has not been uploaded yet.',
            ], 422);
        }

        // Prevent duplicate pending requests for same field
        $hasPending = $application->documentReuploadRequests()
            ->where('field_name', $fieldName)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return response()->json([
                'error' => true,
                'message' => 'A re-upload request for this document is already pending.',
            ], 409);
        }

        $requestRecord = DocumentReuploadRequest::create([
            'application_id' => $application->id,
            'field_name' => $fieldName,
            'field_label' => $documentMeta['label'],
            'field_source' => 'default_document',
            'step_number' => $documentMeta['step_number'],
            'highlight_field' => $documentMeta['highlight_field'],
            'previous_status' => $application->status === 'documents_pending'
                ? ($application->status_before_documents_pending ?? 'under_review')
                : $application->status,
            'reason' => $validated['reason'],
            'requested_by' => $admin->id,
            'status' => 'pending',
        ]);

        // Update application status to under_review when document is rejected
        // Always set to under_review when document is rejected, regardless of current status
        $updateData = ['status' => 'under_review'];
        
        // Save previous status if not already saved
        if ($application->status !== 'under_review') {
            $updateData['status_before_documents_pending'] = $application->status;
        } elseif (!$application->status_before_documents_pending) {
            $updateData['status_before_documents_pending'] = 'under_review';
        }
        
        $application->update($updateData);

        // Send push notification to the student (if token exists)
        if (!function_exists('send_notification')) {
            require_once app_path('Helpers/notification_helper.php');
        }

        $title = 'Document needs attention';
        $body = 'Please re-upload your ' . strtolower($documentMeta['label']) . '.';
        
        // Build deep link URL for the form view
        $baseUrl = config('app.url');
        $formUrl = $baseUrl . '/api/multi-step-application/form-view?' . http_build_query([
            'university_id' => $application->university_id,
            'user_id' => $application->user_id,
            'application_id' => $application->id,
            'document_request_id' => $requestRecord->id,
            'highlight_field' => $documentMeta['highlight_field'],
            'highlight_step' => $documentMeta['step_number'],
        ]);
        
        $customData = [
            'type' => 'document_reupload',
            'application_id' => (string) $application->id,
            'university_id' => (string) $application->university_id,
            'highlight_field' => $documentMeta['highlight_field'],
            'highlight_step' => (string) $documentMeta['step_number'],
            'document_request_id' => (string) $requestRecord->id,
            'deep_link_url' => $formUrl,
        ];

        send_notification([$application->user_id], $title, $body, 'document_reupload', $customData, $formUrl);
        DB::commit();
        return response()->json([
            'error' => false,
            'message' => 'Document re-upload request sent successfully.',
        ]);
    }
}

