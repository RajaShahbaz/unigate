<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationFieldResponse;
use App\Models\University;
use App\Models\UniversityApplicationField;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    /**
     * Get required fields for a university application
     */
    public function getApplicationForm(Request $request, $universityId)
    {
        try {
            $university = University::with('interests')->findOrFail($universityId);

            // Get custom fields defined by the university
            $customFields = UniversityApplicationField::where('university_id', $universityId)
                ->active()
                ->ordered()
                ->get();

            return response()->json([
                'error' => false,
                'message' => 'Application form retrieved successfully',
                'data' => [
                    'university' => $university,
                    'custom_fields' => $customFields,
                    'general_fields' => $this->getGeneralFieldsDefinition()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve application form',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get general fields definition
     */
    private function getGeneralFieldsDefinition()
    {
        return [
            'first_name' => ['type' => 'text', 'required' => true, 'label' => 'First Name'],
            'last_name' => ['type' => 'text', 'required' => true, 'label' => 'Last Name'],
            'email' => ['type' => 'email', 'required' => true, 'label' => 'Email'],
            'phone' => ['type' => 'tel', 'required' => true, 'label' => 'Phone Number'],
            'date_of_birth' => ['type' => 'date', 'required' => true, 'label' => 'Date of Birth'],
            'gender' => ['type' => 'select', 'required' => true, 'label' => 'Gender', 'options' => ['male', 'female', 'other']],
            'current_address' => ['type' => 'textarea', 'required' => true, 'label' => 'Current Address'],
            'permanent_address' => ['type' => 'textarea', 'required' => false, 'label' => 'Permanent Address'],
            'nationality' => ['type' => 'text', 'required' => true, 'label' => 'Nationality'],
            'previous_school' => ['type' => 'text', 'required' => false, 'label' => 'Previous School/Institution'],
            'previous_degree' => ['type' => 'text', 'required' => false, 'label' => 'Previous Degree'],
            'previous_gpa' => ['type' => 'number', 'required' => false, 'label' => 'Previous GPA'],
            'graduation_year' => ['type' => 'number', 'required' => false, 'label' => 'Graduation Year'],
            'transcript_file' => ['type' => 'file', 'required' => false, 'label' => 'Academic Transcript'],
            'id_document_file' => ['type' => 'file', 'required' => false, 'label' => 'ID Document'],
            'photo_file' => ['type' => 'file', 'required' => false, 'label' => 'Passport Photo'],
            'recommendation_letter_file' => ['type' => 'file', 'required' => false, 'label' => 'Recommendation Letter'],
            'application_message' => ['type' => 'textarea', 'required' => false, 'label' => 'Personal Statement/Message'],
        ];
    }

    /**
     * Get all applications for the authenticated user grouped by status
     */
    public function myApplications(Request $request)
    {
        try {
            $user = Auth::user();

            // Get all applications for the user with document reupload requests
            $applications = Application::with([
                'university', 
                'interest', 
                'reviewer',
                'documentReuploadRequests' => function($query) {
                    $query->where('status', 'pending')
                          ->orderBy('created_at', 'desc');
                }
            ])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Group applications by status
            $groupedApplications = [
                'draft' => [],
                'submitted' => [],
                'documents_pending' => [],
                'under_review' => [],
                'shortlisted' => [],
                'accepted' => [],
                'rejected' => [],
                'withdrawn' => [],
            ];

            // Count totals
            $counts = [
                'total' => $applications->count(),
                'draft' => 0,
                'submitted' => 0,
                'documents_pending' => 0,
                'under_review' => 0,
                'shortlisted' => 0,
                'accepted' => 0,
                'rejected' => 0,
                'withdrawn' => 0,
            ];

            $baseUrl = config('app.url');

            // Group applications and count by status
            foreach ($applications as $application) {
                $status = $application->status;

                $applicationData = [
                    'id' => $application->id,
                    'application_number' => $application->application_number,
                    'university' => [
                        'id' => $application->university->id ?? null,
                        'name' => $application->university->name ?? 'N/A',
                        'logo' => $application->university->logo ?? null,
                        'city' => $application->university->city ?? null,
                    ],
                    'interest' => [
                        'id' => $application->interest->id ?? null,
                        'name' => $application->interest->name ?? 'N/A',
                    ],
                    'status' => $application->status,
                    'submitted_at' => $application->submitted_at ? $application->submitted_at->format('Y-m-d H:i:s') : null,
                    'reviewed_at' => $application->reviewed_at ? $application->reviewed_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $application->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $application->updated_at->format('Y-m-d H:i:s'),
                ];

                // Add reviewer info if available
                if ($application->reviewer) {
                    $applicationData['reviewer'] = [
                        'id' => $application->reviewer->id,
                        'name' => $application->reviewer->full_name ?? 'N/A',
                    ];
                }

                // Add rejection reason if rejected
                if ($status === 'rejected' && $application->rejection_reason) {
                    $applicationData['rejection_reason'] = $application->rejection_reason;
                }

                // Add rejected documents with reasons and URLs
                $rejectedDocuments = [];
                if ($application->documentReuploadRequests->isNotEmpty()) {
                    foreach ($application->documentReuploadRequests as $docRequest) {
                        // Build form view URL for this specific document
                        $formUrl = $baseUrl . '/api/multi-step-application/form-view?' . http_build_query([
                            'university_id' => $application->university_id,
                            'user_id' => $application->user_id,
                            'application_id' => $application->id,
                            'document_request_id' => $docRequest->id,
                            'highlight_field' => $docRequest->highlight_field,
                            'highlight_step' => $docRequest->step_number,
                        ]);

                        $rejectedDocuments[] = [
                            'id' => $docRequest->id,
                            'field_name' => $docRequest->field_name,
                            'field_label' => $docRequest->field_label,
                            'reason' => $docRequest->reason,
                            'step_number' => $docRequest->step_number,
                            'highlight_field' => $docRequest->highlight_field,
                            'status' => $docRequest->status,
                            'requested_at' => $docRequest->created_at->format('Y-m-d H:i:s'),
                            'form_url' => $formUrl,
                        ];
                    }
                }

                if (!empty($rejectedDocuments)) {
                    $applicationData['rejected_documents'] = $rejectedDocuments;
                }

                // Add to appropriate status group
                if (isset($groupedApplications[$status])) {
                    $groupedApplications[$status][] = $applicationData;
                    $counts[$status]++;
                }
            }

            return response()->json([
                'error' => false,
                'message' => 'Applications retrieved successfully',
                'data' => [
                    'summary' => $counts,
                    'applications' => $groupedApplications,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve applications',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or save draft application
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'university_id' => 'required|exists:universities,id',
                'interest_id' => 'required|exists:interests,id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'date_of_birth' => 'required|date|before:today',
                'gender' => 'required|in:male,female,other',
                'current_address' => 'required|string',
                'permanent_address' => 'nullable|string',
                'nationality' => 'required|string|max:100',
                'previous_school' => 'nullable|string|max:255',
                'previous_degree' => 'nullable|string|max:255',
                'previous_gpa' => 'nullable|numeric|min:0|max:4',
                'graduation_year' => 'nullable|digits:4|integer|min:1950|max:' . (date('Y') + 1),
                'transcript_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'id_document_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'photo_file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'recommendation_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'application_message' => 'nullable|string|max:5000',
                'custom_fields' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $data = $validator->validated();
            $data['user_id'] = $user->id;
            $data['status'] = 'draft';

            // Handle file uploads
            $fileFields = ['transcript_file', 'id_document_file', 'photo_file', 'recommendation_letter_file'];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $data[$field] = $request->file($field)->store('applications/documents', 'public');
                }
            }

            // Extract custom fields
            $customFields = $data['custom_fields'] ?? [];
            unset($data['custom_fields']);

            $application = Application::create($data);

            // Handle custom fields
            if (!empty($customFields)) {
                $this->saveCustomFieldResponses($application, $customFields, $request);
            }

            DB::commit();

            $application->load(['university', 'interest', 'fieldResponses.field']);

            return response()->json([
                'error' => false,
                'message' => 'Application draft saved successfully',
                'data' => $application
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to save application',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update draft application
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $application = Application::where('user_id', $user->id)->findOrFail($id);

            if (!$application->isEditable()) {
                return response()->json([
                    'error' => true,
                    'message' => 'This application cannot be edited',
                    'data' => null
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|max:255',
                'phone' => 'sometimes|string|max:20',
                'date_of_birth' => 'sometimes|date|before:today',
                'gender' => 'sometimes|in:male,female,other',
                'current_address' => 'sometimes|string',
                'permanent_address' => 'nullable|string',
                'nationality' => 'sometimes|string|max:100',
                'previous_school' => 'nullable|string|max:255',
                'previous_degree' => 'nullable|string|max:255',
                'previous_gpa' => 'nullable|numeric|min:0|max:4',
                'graduation_year' => 'nullable|digits:4|integer|min:1950|max:' . (date('Y') + 1),
                'transcript_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'id_document_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'photo_file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'recommendation_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'application_message' => 'nullable|string|max:5000',
                'custom_fields' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $data = $validator->validated();

            // Handle file uploads
            $fileFields = ['transcript_file', 'id_document_file', 'photo_file', 'recommendation_letter_file'];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Delete old file
                    if ($application->$field && Storage::disk('public')->exists($application->$field)) {
                        Storage::disk('public')->delete($application->$field);
                    }
                    $data[$field] = $request->file($field)->store('applications/documents', 'public');
                }
            }

            // Extract custom fields
            $customFields = $data['custom_fields'] ?? null;
            unset($data['custom_fields']);

            $application->update($data);

            // Update custom fields if provided
            if ($customFields !== null) {
                $this->saveCustomFieldResponses($application, $customFields, $request);
            }

            DB::commit();

            $application->load(['university', 'interest', 'fieldResponses.field']);

            return response()->json([
                'error' => false,
                'message' => 'Application updated successfully',
                'data' => $application
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to update application',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit application
     */
    public function submit($id)
    {
        // try {
            $user = Auth::user();
            $application = Application::where('user_id', $user->id)->findOrFail($id);
            if(empty($user))
            {
                $user= User::find($application->user_id);
            }

            if ($application->status !== 'draft') {
                return response()->json([
                    'error' => true,
                    'message' => 'Application has already been submitted',
                    'data' => null
                ], 400);
            }

            // Validate required custom fields
            $customFields = UniversityApplicationField::where('university_id', $application->university_id)
                ->where('is_required', true)
                ->active()
                ->get();

            foreach ($customFields as $field) {
                $response = ApplicationFieldResponse::where('application_id', $application->id)
                    ->where('university_application_field_id', $field->id)
                    ->first();

                if (!$response || (empty($response->response_value) && empty($response->file_path))) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Please complete all required fields: ' . $field->field_label,
                        'data' => null
                    ], 400);
                }
            }

            $application->update([
                'status' => 'submitted',
                'submitted_at' => now()
            ]);

            $application->load(['university', 'interest', 'fieldResponses.field']);

            return response()->json([
                'error' => false,
                'message' => 'Application submitted successfully',
                'data' => $application
            ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'error' => true,
        //         'message' => 'Failed to submit application',
        //         'data' => $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Withdraw application
     */
    public function withdraw($id)
    {
        try {
            $user = Auth::user();
            $application = Application::where('user_id', $user->id)->findOrFail($id);

            if (!$application->canBeWithdrawn()) {
                return response()->json([
                    'error' => true,
                    'message' => 'This application cannot be withdrawn',
                    'data' => null
                ], 403);
            }

            $application->update(['status' => 'withdrawn']);

            return response()->json([
                'error' => false,
                'message' => 'Application withdrawn successfully',
                'data' => $application
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to withdraw application',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single application details
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $application = Application::with(['university', 'interest', 'fieldResponses.field', 'reviewer'])
                ->where('user_id', $user->id)
                ->findOrFail($id);

            return response()->json([
                'error' => false,
                'message' => 'Application retrieved successfully',
                'data' => $application
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve application',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete draft application
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $application = Application::where('user_id', $user->id)->findOrFail($id);

            if (!$application->isEditable()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Only draft applications can be deleted',
                    'data' => null
                ], 403);
            }

            $application->delete();

            return response()->json([
                'error' => false,
                'message' => 'Application deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete application',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save custom field responses
     */
    private function saveCustomFieldResponses($application, $customFields, $request)
    {
        foreach ($customFields as $fieldId => $value) {
            $field = UniversityApplicationField::find($fieldId);

            if (!$field || $field->university_id != $application->university_id) {
                continue;
            }

            $responseData = [
                'application_id' => $application->id,
                'university_application_field_id' => $fieldId,
            ];

            if ($field->field_type === 'file' && $request->hasFile("custom_fields.{$fieldId}")) {
                $filePath = $request->file("custom_fields.{$fieldId}")
                    ->store('applications/custom_fields', 'public');
                $responseData['file_path'] = $filePath;
            } else {
                $responseData['response_value'] = $value;
            }

            ApplicationFieldResponse::updateOrCreate(
                [
                    'application_id' => $application->id,
                    'university_application_field_id' => $fieldId
                ],
                $responseData
            );
        }
    }
}
