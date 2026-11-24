<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationPackage;
use App\Models\CustomApplicationStep;
use App\Models\DocumentReuploadRequest;
use App\Models\University;
use App\Models\User;
use App\Models\UserApplicationPackage;
use App\Services\AdmissionFormStepService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MultiStepApplicationController extends Controller
{
    /**
     * Get HTML form view for WebView (mobile app)
     */
    public function getFormView(Request $request, $id = null)
    {
        try {
            // Support both route parameter (web) and query parameter (API)
            $universityId = $id ?? $request->input('university_id');

            if (!$universityId) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'University ID is required'
                    ], 422);
                }
                return redirect()->route('universities')->with('error', 'University ID is required');
            }

            $user = Auth::user();
            if (empty($user)) {
                // For API requests, try to get user from user_id parameter
                if ($request->expectsJson() || $request->header('Authorization')) {
                    $user = User::find($request->user_id);
                } else {
                    // For web requests, redirect to login
                    return redirect()->route('login')->with('redirect', $request->fullUrl());
                }
            }
            
            if (!$user) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'User authentication required'
                    ], 401);
                }
                return redirect()->route('login')->with('redirect', $request->fullUrl());
            }

            $authHeader = $request->header('Authorization');
            if ($authHeader) {
                $token = str_replace('Bearer ', '', $authHeader);
            } else {
                $token = null;
            }
            $university = University::findOrFail($universityId);

            $application = null;
            $requestedApplicationId = $request->input('application_id');

            if ($requestedApplicationId) {
                $application = Application::where('id', $requestedApplicationId)
                    ->where('user_id', $user->id)
                    ->first();
            }

            if (!$application) {
                $application = Application::where('user_id', $user->id)
                    ->where('university_id', $universityId)
                    ->where('status', 'draft')
                    ->first();
            }

            $highlightField = $request->query('highlight_field');
            $highlightStep = $request->query('highlight_step');
            $documentRequestBanner = null;
            if ($request->filled('document_request_id')) {
                $documentRequest = DocumentReuploadRequest::with('application')
                    ->where('id', $request->document_request_id)
                    ->first();

                if ($documentRequest && $documentRequest->application && $documentRequest->application->user_id === $user->id) {
                    if (!$application) {
                        $application = $documentRequest->application;
                    }

                    if ($documentRequest->status === 'pending') {
                        $highlightField = $highlightField ?: ($documentRequest->highlight_field ?? $documentRequest->field_name);
                        $highlightStep = $highlightStep ?: $documentRequest->step_number;
                        $documentRequestBanner = $documentRequest;
                    }
                }
            }

            if (!$application && $requestedApplicationId) {
                return response()->json([
                    'error' => true,
                    'message' => 'Application not found'
                ], 404);
            }

            // Get form steps
            $customSteps = CustomApplicationStep::with('activeFields')
                ->where('university_id', $universityId)
                ->active()
                ->ordered()
                ->get();

            if ($customSteps->isNotEmpty()) {
                $steps = $customSteps;
                $isCustom = true;
            } else {
                $steps = collect(AdmissionFormStepService::getFormSteps())->map(function ($step) {
                    return (object) $step;
                });
                $isCustom = false;
            }

            // Prepare user data for pre-filling
            $userData = [
                'first_name' => $user->first_name ?? '',
                'last_name' => $user->last_name ?? '',
                'email' => $user->email ?? '',
                'mobile' => $user->mobile ?? '',
                'gender' => $user->gender ?? '',
                'dob' => $user->dob ?? '',
                'current_address' => $user->current_address ?? '',
                'permanent_address' => $user->permanent_address ?? '',
            ];

            // If application exists, merge application data
            if ($application) {
                $userData = array_merge($userData, $application->toArray());
            }

            // Check if user has purchased a package
            $package = ApplicationPackage::where('status', 1)->first();
            $hasPackage = false;
            $userPackage = null;

            if ($user && $package) {
                // Check if user has purchased this package
                $userPackage = UserApplicationPackage::where('user_id', $user->id)
                    ->where('package_id', $package->id)
                    ->where('payment_status', 'completed')
                    ->first();

                $hasPackage = $userPackage !== null;
            }

            return view('application.webview-form', compact(
                'university',
                'token',
                'user',
                'steps',
                'isCustom',
                'userData',
                'application',
                'highlightField',
                'highlightStep',
                'documentRequestBanner',
                'package',
                'hasPackage',
                'userPackage'
            ));
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->header('Authorization')) {
                return response()->json([
                    'error' => true,
                    'message' => 'Failed to load form',
                    'data' => $e->getMessage()
                ], 500);
            }
            // For web requests, redirect back with error
            $universityId = $id ?? $request->input('university_id');
            if ($universityId) {
                return redirect()->route('university.detail', $universityId)->with('error', 'Failed to load application form: ' . $e->getMessage());
            }
            return redirect()->route('universities')->with('error', 'Failed to load application form');
        }
    }

    /**
     * Get form steps configuration for a university
     */
    public function getFormSteps(Request $request)
    {
        try {
            $universityId = $request->input('university_id');

            if (!$universityId) {
                return response()->json([
                    'error' => true,
                    'message' => 'University ID is required'
                ], 422);
            }

            // Check if university has custom steps
            $customSteps = CustomApplicationStep::with('activeFields')
                ->where('university_id', $universityId)
                ->active()
                ->ordered()
                ->get();

            if ($customSteps->isNotEmpty()) {
                // Use custom steps from database
                $steps = $customSteps->map(function ($step) {
                    return [
                        'step' => $step->step_number,
                        'title' => $step->step_title,
                        'description' => $step->step_description,
                        'icon' => $step->step_icon,
                        'is_required' => $step->is_required,
                        'fields' => $step->activeFields->map(function ($field) {
                            $fieldData = [
                                'name' => $field->field_name,
                                'type' => $field->field_type,
                                'label' => $field->field_label,
                                'placeholder' => $field->field_placeholder,
                                'required' => $field->is_required,
                                'validation' => $field->validation_rules,
                                'icon' => $field->field_icon,
                                'helper_text' => $field->helper_text,
                            ];

                            // Add options for select/radio/checkbox
                            if ($field->options_array) {
                                $fieldData['options'] = $field->options_array;
                            }

                            // Add file-specific fields
                            if ($field->field_type === 'file') {
                                $fieldData['accept'] = $field->accept_types;
                                $fieldData['maxSize'] = $field->max_file_size ? ($field->max_file_size / 1024) . 'MB' : null;
                            }

                            // Add min/max for number fields
                            if ($field->field_type === 'number') {
                                if ($field->min_value !== null) $fieldData['min'] = $field->min_value;
                                if ($field->max_value !== null) $fieldData['max'] = $field->max_value;
                            }

                            return $fieldData;
                        })->toArray()
                    ];
                })->toArray();

                return response()->json([
                    'error' => false,
                    'message' => 'Custom form steps retrieved successfully',
                    'data' => [
                        'steps' => $steps,
                        'total_steps' => count($steps),
                        'is_custom' => true,
                    ]
                ]);
            } else {
                // Use default steps
                $steps = AdmissionFormStepService::getFormSteps();

                return response()->json([
                    'error' => false,
                    'message' => 'Default form steps retrieved successfully',
                    'data' => [
                        'steps' => $steps,
                        'total_steps' => count($steps),
                        'is_custom' => false,
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve form steps',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific step configuration
     */
    public function getStep(Request $request, $stepNumber)
    {
        try {
            $universityId = $request->input('university_id');

            if (!$universityId) {
                return response()->json([
                    'error' => true,
                    'message' => 'University ID is required'
                ], 422);
            }

            // Check for custom step
            $customStep = CustomApplicationStep::with('activeFields')
                ->where('university_id', $universityId)
                ->where('step_number', $stepNumber)
                ->active()
                ->first();

            if ($customStep) {
                $step = [
                    'step' => $customStep->step_number,
                    'title' => $customStep->step_title,
                    'description' => $customStep->step_description,
                    'icon' => $customStep->step_icon,
                    'is_required' => $customStep->is_required,
                    'fields' => $customStep->activeFields->map(function ($field) {
                        $fieldData = [
                            'name' => $field->field_name,
                            'type' => $field->field_type,
                            'label' => $field->field_label,
                            'placeholder' => $field->field_placeholder,
                            'required' => $field->is_required,
                            'validation' => $field->validation_rules,
                            'icon' => $field->field_icon,
                            'helper_text' => $field->helper_text,
                        ];

                        if ($field->options_array) {
                            $fieldData['options'] = $field->options_array;
                        }

                        if ($field->field_type === 'file') {
                            $fieldData['accept'] = $field->accept_types;
                            $fieldData['maxSize'] = $field->max_file_size ? ($field->max_file_size / 1024) . 'MB' : null;
                        }

                        return $fieldData;
                    })->toArray()
                ];

                return response()->json([
                    'error' => false,
                    'message' => 'Custom step retrieved successfully',
                    'data' => $step
                ]);
            }

            // Fall back to default steps
            $steps = AdmissionFormStepService::getFormSteps();
            $step = collect($steps)->firstWhere('step', (int)$stepNumber);

            if (!$step) {
                return response()->json([
                    'error' => true,
                    'message' => 'Invalid step number'
                ], 404);
            }

            return response()->json([
                'error' => false,
                'message' => 'Default step retrieved successfully',
                'data' => $step
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve step',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start a new application (create draft)
     */
    public function startApplication(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'university_id' => 'required|exists:universities,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->first(),
                    'data' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            if (empty($user)) {
                $user = User::find($request->user_id);
            }

            // Check if user already has a draft application for this university
            $existingDraft = Application::where('user_id', $user->id)
                ->where('university_id', $request->university_id)
                ->where('status', 'draft')
                ->first();

            if ($existingDraft) {
                return response()->json([
                    'error' => false,
                    'message' => 'Existing draft application found',
                    'data' => [
                        'application' => $existingDraft,
                        'can_continue' => true,
                    ]
                ]);
            }

            // Create new draft application
            $application = Application::create([
                'user_id' => $user->id,
                'university_id' => $request->university_id,
                'status' => 'draft',
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Application started successfully',
                'data' => [
                    'application' => $application,
                    'next_step' => 1,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to start application',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save step data
     */
    public function saveStep(Request $request, $applicationId, $stepNumber)
    {
        try {
            $user = Auth::user();
            if(empty($user))
            {
                $user=User::find($request->user_id);
            }
            $application = Application::where('id', $applicationId)
                ->where('user_id', $user->id)
                ->where('status', 'draft')
                ->firstOrFail();

            // Check if university has custom steps
            $customStep = CustomApplicationStep::with('activeFields')
                ->where('university_id', $application->university_id)
                ->where('step_number', $stepNumber)
                ->active()
                ->first();

            // Get validation rules for this step
            if ($customStep) {
                // Build validation rules from custom fields
                $rules = [];
                $interestIdFieldName = null; // Track which field is actually interest_id

                foreach ($customStep->activeFields as $field) {
                    $fieldRules = [];
                    if ($field->is_required) {
                        $fieldRules[] = 'required';
                    } else {
                        $fieldRules[] = 'nullable';
                    }

                    // Special validation for interest_id
                    if ($field->field_name === 'interest_id' && $field->field_type === 'select') {
                        $interestIdFieldName = 'interest_id';
                        // Validate that interest_id exists and belongs to the university
                        $university = University::findOrFail($application->university_id);
                        $validInterestIds = $university->interests()->pluck('interests.id')->toArray();
                        $fieldRules[] = 'in:' . implode(',', $validInterestIds);
                    } elseif ($field->field_type === 'select' && $field->field_options) {
                        // For select fields with predefined options, validate against those options
                        $options = json_decode($field->field_options, true);
                        if (is_array($options) && !empty($options)) {
                            $validValues = [];
                            foreach ($options as $option) {
                                if (is_array($option) && isset($option['value'])) {
                                    $validValues[] = (string)$option['value']; // Convert to string for comparison
                                } elseif (is_string($option)) {
                                    $validValues[] = $option;
                                }
                            }
                            if (!empty($validValues)) {
                                // Escape commas in values and join
                                $escapedValues = array_map(function($val) {
                                    return str_replace(',', '\\,', $val);
                                }, $validValues);
                                $fieldRules[] = 'in:' . implode(',', $escapedValues);
                            }
                        }
                        // Also add any custom validation rules if provided
                        if ($field->validation_rules) {
                            $fieldRules[] = $field->validation_rules;
                        }
                    } elseif ($field->validation_rules) {
                        $fieldRules[] = $field->validation_rules;
                    }

                    $rules[$field->field_name] = implode('|', $fieldRules);
                }

                // Check if any field is meant to be interest_id (by checking field options or label)
                // Exclude previous_degree and other academic fields that are not for program selection
                foreach ($customStep->activeFields as $field) {
                    $normalizedFieldName = strtolower(str_replace([' ', '-', '_'], '', $field->field_name));
                    $normalizedLabel = strtolower(str_replace([' ', '-', '_'], '', $field->field_label ?? ''));

                    // Skip if this is previous_degree or other academic history fields
                    $academicFields = ['previousdegree', 'previous_degree', 'lastcompleteddegree', 'last_completed_degree'];
                    if (in_array($normalizedFieldName, $academicFields) || in_array($normalizedLabel, $academicFields)) {
                        continue;
                    }

                    // If field name or label suggests it's for program/course selection (not previous degree) and it's a select field
                    if ($field->field_type === 'select' &&
                        ($field->field_name === 'interest_id' ||
                         strpos($normalizedFieldName, 'program') !== false ||
                         strpos($normalizedFieldName, 'course') !== false ||
                         (strpos($normalizedLabel, 'program') !== false && strpos($normalizedLabel, 'previous') === false) ||
                         (strpos($normalizedLabel, 'course') !== false && strpos($normalizedLabel, 'previous') === false))) {
                        $interestIdFieldName = $field->field_name;

                        // Update validation to use interest_id validation
                        $university = University::findOrFail($application->university_id);
                        $validInterestIds = $university->interests()->pluck('interests.id')->toArray();
                        $rules[$field->field_name] = ($field->is_required ? 'required' : 'nullable') . '|in:' . implode(',', $validInterestIds);
                    }
                }

                // Map request data: if interest_id is sent but field expects different name, map it
                if ($interestIdFieldName && $interestIdFieldName !== 'interest_id' && $request->has('interest_id')) {
                    $request->merge([$interestIdFieldName => $request->input('interest_id')]);
                }

                // Also handle reverse: if field name is sent but should map to interest_id
                if ($interestIdFieldName && $interestIdFieldName !== 'interest_id') {
                    if ($request->has($interestIdFieldName) && !$request->has('interest_id')) {
                        $request->merge(['interest_id' => $request->input($interestIdFieldName)]);
                    }
                }
            } else {
                $rules = AdmissionFormStepService::getStepValidationRules($stepNumber);

                // For default forms, also validate interest_id belongs to university
                if (isset($rules['interest_id']) && $stepNumber == 4) {
                    $university = University::findOrFail($application->university_id);
                    $validInterestIds = $university->interests()->pluck('interests.id')->toArray();
                    $rules['interest_id'] = 'required|in:' . implode(',', $validInterestIds);
                }
            }

            if ($customStep) {
                foreach ($customStep->activeFields as $field) {
                    $normalizedFieldName = strtolower(str_replace([' ', '-', '_'], '', $field->field_name));
                    $normalizedLabel = strtolower(str_replace([' ', '-', '_'], '', $field->field_label ?? ''));

                    // Skip if this is previous_degree or other academic history fields
                    $academicFields = ['previousdegree', 'previous_degree', 'lastcompleteddegree', 'last_completed_degree'];
                    if (in_array($normalizedFieldName, $academicFields) || in_array($normalizedLabel, $academicFields)) {
                        continue;
                    }

                    // If this field is meant to be interest_id (program/course selection, not previous degree)
                    if ($field->field_type === 'select' &&
                        ($field->field_name === 'interest_id' ||
                         strpos($normalizedFieldName, 'program') !== false ||
                         strpos($normalizedFieldName, 'course') !== false ||
                         (strpos($normalizedLabel, 'program') !== false && strpos($normalizedLabel, 'previous') === false) ||
                         (strpos($normalizedLabel, 'course') !== false && strpos($normalizedLabel, 'previous') === false))) {

                        // If field is required but not present, check if interest_id is present
                        if ($field->is_required && !$request->has($field->field_name) && $request->has('interest_id')) {
                            $request->merge([$field->field_name => $request->input('interest_id')]);
                        }
                    }

                    // Handle field name variations for previous_degree and other common fields
                    $commonFieldMappings = [
                        'previousdegree' => 'previous_degree',
                        'previous_degree' => 'previous_degree',
                        'lastcompleteddegree' => 'previous_degree',
                        'last_completed_degree' => 'previous_degree',
                    ];

                    foreach ($commonFieldMappings as $variation => $actualFieldName) {
                        if ($normalizedFieldName === $variation || $normalizedLabel === $variation) {
                            // Check if request has the variation but not the actual field name
                            foreach ($request->all() as $key => $value) {
                                $normalizedKey = strtolower(str_replace([' ', '-', '_'], '', $key));
                                if ($normalizedKey === $variation && $key !== $actualFieldName && $field->field_name === $actualFieldName) {
                                    $request->merge([$actualFieldName => $value]);
                                }
                            }
                        }
                    }

                    // For select fields, if value sent doesn't match any option value, try to match by label
                    if ($field->field_type === 'select' && $field->field_options && $request->has($field->field_name)) {
                        $sentValue = $request->input($field->field_name);
                        $options = json_decode($field->field_options, true);

                        if (is_array($options)) {
                            $valueFound = false;
                            $matchedValue = null;

                            foreach ($options as $option) {
                                if (is_array($option)) {
                                    // Check if sent value matches the option value
                                    if (isset($option['value']) && (string)$option['value'] === (string)$sentValue) {
                                        $valueFound = true;
                                        break;
                                    }
                                    // Check if sent value matches the option label (case-insensitive)
                                    if (isset($option['label']) && strtolower(trim($option['label'])) === strtolower(trim($sentValue))) {
                                        $matchedValue = $option['value'];
                                        break;
                                    }
                                } elseif (is_string($option) && $option === $sentValue) {
                                    $valueFound = true;
                                    break;
                                }
                            }

                            // If we found a match by label, use the corresponding value
                            if (!$valueFound && $matchedValue !== null) {
                                $request->merge([$field->field_name => $matchedValue]);
                            }
                        }
                    }
                }
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->first(),
                    'data' => $validator->errors()
                ], 422);
            }

            // Get fields for this step
            if ($customStep) {
                $stepFields = $customStep->activeFields;
            } else {
                $stepFields = AdmissionFormStepService::getStepFields($stepNumber);
            }
            $updateData = [];

            // Process each field
            foreach ($stepFields as $field) {
                $fieldName = $customStep ? $field->field_name : $field['name'];
                $fieldType = $customStep ? $field->field_type : $field['type'];

                // Check if this field should map to interest_id
                $shouldMapToInterestId = false;
                if ($customStep) {
                    $normalizedFieldName = strtolower(str_replace([' ', '-', '_'], '', $fieldName));
                    $normalizedLabel = strtolower(str_replace([' ', '-', '_'], '', $field->field_label ?? ''));

                    // Skip if this is previous_degree or other academic history fields
                    $academicFields = ['previousdegree', 'previous_degree', 'lastcompleteddegree', 'last_completed_degree'];
                    if (!in_array($normalizedFieldName, $academicFields) && !in_array($normalizedLabel, $academicFields)) {
                        // If field name or label suggests it's for program/course selection (not previous degree)
                        if ($fieldType === 'select' &&
                            ($fieldName === 'interest_id' ||
                             strpos($normalizedFieldName, 'program') !== false ||
                             strpos($normalizedFieldName, 'course') !== false ||
                             (strpos($normalizedLabel, 'program') !== false && strpos($normalizedLabel, 'previous') === false) ||
                             (strpos($normalizedLabel, 'course') !== false && strpos($normalizedLabel, 'previous') === false))) {
                            $shouldMapToInterestId = true;
                        }
                    }
                } else {
                    $shouldMapToInterestId = ($fieldName === 'interest_id');
                }

                // Get value from request - check both field name and interest_id
                $fieldValue = null;
                if ($request->has($fieldName)) {
                    $fieldValue = $request->input($fieldName);
                } elseif ($shouldMapToInterestId && $request->has('interest_id')) {
                    $fieldValue = $request->input('interest_id');
                }

                if ($fieldValue !== null) {
                    // Handle file uploads
                    if ($fieldType === 'file' && $request->hasFile($fieldName)) {
                        $file = $request->file($fieldName);
                        $path = $file->store('applications/' . $applicationId, 'public');
                        $updateData[$fieldName] = $path;

                        DocumentReuploadRequest::resolveForField($application, $fieldName);
                    } else {
                        // If this field maps to interest_id, save it as interest_id
                        if ($shouldMapToInterestId && $fieldName !== 'interest_id') {
                            $updateData['interest_id'] = $fieldValue;
                        } else {
                            $updateData[$fieldName] = $fieldValue;
                        }
                    }
                }
            }

            // Update application
            $application->update($updateData);

            // Calculate progress
            $completedSteps = $this->getCompletedSteps($application);
            $progress = AdmissionFormStepService::calculateProgress($stepNumber, $completedSteps);

            // Determine next step
            $nextStep = (int)$stepNumber + 1;
            $totalSteps = count(AdmissionFormStepService::getFormSteps());
            $canProceed = $nextStep <= $totalSteps;

            return response()->json([
                'error' => false,
                'message' => 'Step saved successfully',
                'data' => [
                    'application' => $application->fresh(),
                    'current_step' => (int)$stepNumber,
                    'next_step' => $canProceed ? $nextStep : null,
                    'progress' => $progress,
                    'completed_steps' => $completedSteps,
                    'can_proceed' => $canProceed,
                    'can_submit' => $nextStep > $totalSteps,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to save step',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get application progress
     */
    public function getProgress($applicationId)
    {
        try {
            $user = Auth::user();
            $application = Application::where('id', $applicationId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $completedSteps = $this->getCompletedSteps($application);
            $totalSteps = count(AdmissionFormStepService::getFormSteps());
            $progress = AdmissionFormStepService::calculateProgress(count($completedSteps), $completedSteps);

            // Determine current step (first incomplete step)
            $currentStep = 1;
            for ($i = 1; $i <= $totalSteps; $i++) {
                if (!in_array($i, $completedSteps)) {
                    $currentStep = $i;
                    break;
                }
                $currentStep = $i + 1;
            }

            return response()->json([
                'error' => false,
                'message' => 'Progress retrieved successfully',
                'data' => [
                    'application' => $application,
                    'current_step' => $currentStep,
                    'completed_steps' => $completedSteps,
                    'total_steps' => $totalSteps,
                    'progress_percentage' => $progress,
                    'can_submit' => count($completedSteps) >= ($totalSteps - 1), // All steps except review
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve progress',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit application
     */
    public function submitApplication(Request $request, $applicationId)
    {
        try {
            $user = Auth::user();
            if(empty($user))
            {
                $user= User::find($request->user_id);
            }

            $application = Application::where('id', $applicationId)
                ->where('user_id', $user->id)
                ->where('status', 'draft')
                ->firstOrFail();


            // Validate all required steps are completed
            $completedSteps = $this->getCompletedSteps($application);
            $totalSteps = count(AdmissionFormStepService::getFormSteps()) - 1; // Exclude review step

            if (count($completedSteps) < $totalSteps) {
                return response()->json([
                    'error' => true,
                    'message' => 'Please complete all required steps before submitting',
                    'data' => [
                        'completed_steps' => $completedSteps,
                        'required_steps' => $totalSteps,
                    ]
                ], 422);
            }

            // Check if user has purchased a package
            $package = ApplicationPackage::where('status', 1)->first();
            if ($package) {
                $hasPackage = UserApplicationPackage::where('user_id', $user->id)
                    ->where('package_id', $package->id)
                    ->where('payment_status', 'completed')
                    ->exists();

                if (!$hasPackage) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Please purchase a package before submitting your application',
                        'data' => [
                            'package' => $package,
                            'package_required' => true
                        ]
                    ], 422);
                }
            }

            // Submit application
            DB::beginTransaction();
            try {
                $application->update([
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);

                DB::commit();

                return response()->json([
                    'error' => false,
                    'message' => 'Application submitted successfully',
                    'data' => [
                        'application' => $application->fresh(),
                        'application_number' => $application->application_number,
                    ]
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to submit application',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get application summary for review
     */
    public function getApplicationSummary(Request $request, $applicationId)
    {
        try {
            $user = Auth::user();
            if (empty($user)) {
                $user = User::find($request->user_id);
            }

            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => 'User not found'
                ], 401);
            }

            $application = Application::with(['university', 'interest'])
                ->where('id', $applicationId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $summary = [
                'personal_info' => [
                    'First Name' => $application->first_name,
                    'Last Name' => $application->last_name,
                    'Date of Birth' => $application->date_of_birth?->format('Y-m-d'),
                    'Gender' => ucfirst($application->gender),
                    'Nationality' => $application->nationality,
                ],
                'contact_info' => [
                    'Email' => $application->email,
                    'Phone' => $application->phone,
                    'Current Address' => $application->current_address,
                    'Permanent Address' => $application->permanent_address ?: 'Same as current',
                ],
                'academic_info' => [
                    'Previous School' => $application->previous_school,
                    'Previous Degree' => $application->previous_degree,
                    'Graduation Year' => $application->graduation_year,
                    'GPA/Percentage' => $application->previous_gpa,
                ],
                'program_info' => [
                    'University' => $application->university->name,
                    'Program' => $application->interest->name ?? 'Not selected',
                ],
                'documents' => [
                    'Photo' => !empty($application->photo_file),
                    'ID Document' => !empty($application->id_document_file),
                    'Transcript' => !empty($application->transcript_file),
                    'Recommendation Letter' => !empty($application->recommendation_letter_file),
                ],
            ];

            return response()->json([
                'error' => false,
                'message' => 'Application summary retrieved successfully',
                'data' => [
                    'application' => $application,
                    'summary' => $summary,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve application summary',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get completed steps for an application
     */
    private function getCompletedSteps($application)
    {
        $completedSteps = [];
        $steps = AdmissionFormStepService::getFormSteps();

        foreach ($steps as $step) {
            if ($step['step'] === 7) continue; // Skip review step

            $stepComplete = true;
            foreach ($step['fields'] as $field) {
                if ($field['required'] && empty($application->{$field['name']})) {
                    $stepComplete = false;
                    break;
                }
            }

            if ($stepComplete && !empty($step['fields'])) {
                $completedSteps[] = $step['step'];
            }
        }

        return $completedSteps;
    }

    /**
     * Delete draft application
     */
    public function deleteDraft($applicationId)
    {
        try {
            $user = Auth::user();
            $application = Application::where('id', $applicationId)
                ->where('user_id', $user->id)
                ->where('status', 'draft')
                ->firstOrFail();

            $application->delete();

            return response()->json([
                'error' => false,
                'message' => 'Draft application deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete draft application',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
