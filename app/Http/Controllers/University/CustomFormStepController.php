<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Models\CustomApplicationStep;
use App\Models\CustomApplicationStepField;
use App\Repositories\CustomApplicationStep\CustomApplicationStepInterface;
use App\Repositories\CustomApplicationStepField\CustomApplicationStepFieldInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomFormStepController extends Controller
{
    private CustomApplicationStepInterface $stepRepository;
    private CustomApplicationStepFieldInterface $fieldRepository;

    public function __construct(
        CustomApplicationStepInterface $stepRepository,
        CustomApplicationStepFieldInterface $fieldRepository
    ) {
        $this->stepRepository = $stepRepository;
        $this->fieldRepository = $fieldRepository;
    }

    /**
     * Display custom form steps
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $universityId = $admin->university_id;

        $steps = CustomApplicationStep::with('fields')
            ->where('university_id', $universityId)
            ->ordered()
            ->get();

        return view('university.custom-form.index', compact('steps', 'admin'));
    }

    /**
     * Show form to create new step
     */
    public function createStep()
    {
        $admin = Auth::guard('university')->user();
        return view('university.custom-form.create-step', compact('admin'));
    }

    /**
     * Store new step
     */
    public function storeStep(Request $request)
    {
        $admin = Auth::guard('university')->user();

        $validator = Validator::make($request->all(), [
            'step_number' => 'required|integer|min:1',
            'step_title' => 'required|string|max:255',
            'step_description' => 'nullable|string',
            'step_icon' => 'nullable|string|max:50',
            'display_order' => 'nullable|integer',
            'is_required' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['university_id'] = $admin->university_id;

            $this->stepRepository->create($data);

            return redirect()->route('university.custom-form.index')
                ->with('success', 'Form step created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create step: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form to edit step
     */
    public function editStep($id)
    {
        $admin = Auth::guard('university')->user();

        $step = CustomApplicationStep::where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        return view('university.custom-form.edit-step', compact('step', 'admin'));
    }

    /**
     * Update step
     */
    public function updateStep(Request $request, $id)
    {
        $admin = Auth::guard('university')->user();

        $step = CustomApplicationStep::where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'step_title' => 'required|string|max:255',
            'step_description' => 'nullable|string',
            'step_icon' => 'nullable|string|max:50',
            'display_order' => 'nullable|integer',
            'is_required' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->stepRepository->update($id, $request->all());

            return redirect()->route('university.custom-form.index')
                ->with('success', 'Form step updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update step: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete step
     */
    public function destroyStep($id)
    {
        $admin = Auth::guard('university')->user();

        $step = CustomApplicationStep::where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        try {
            $this->stepRepository->deleteById($id);

            return redirect()->route('university.custom-form.index')
                ->with('success', 'Form step deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete step: ' . $e->getMessage());
        }
    }

    /**
     * Show fields for a step
     */
    public function manageFields($stepId)
    {
        $admin = Auth::guard('university')->user();

        $step = CustomApplicationStep::with('fields')
            ->where('id', $stepId)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        // Get university courses/interests for interest_id field selection
        $university = $admin->university;
        $courses = $university->interests()
            ->withPivot('fee', 'fee_min', 'fee_max', 'duration_years', 'program_details')
            ->orderBy('interests.name')
            ->get();

        return view('university.custom-form.manage-fields', compact('step', 'admin', 'courses'));
    }

    /**
     * Store new field
     */
    public function storeField(Request $request, $stepId)
    {
        $admin = Auth::guard('university')->user();

        $step = CustomApplicationStep::where('id', $stepId)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'field_name' => 'required|string|max:255',
            'field_type' => 'required|in:text,email,tel,date,number,select,textarea,file,radio,checkbox',
            'field_label' => 'required|string|max:255',
            'field_placeholder' => 'nullable|string',
            'field_options' => 'nullable|string',
            'selected_course_ids' => 'nullable|array',
            'selected_course_ids.*' => 'nullable|exists:interests,id',
            'is_required' => 'nullable|boolean',
            'validation_rules' => 'nullable|string',
            'field_icon' => 'nullable|string|max:50',
            'helper_text' => 'nullable|string',
            'min_value' => 'nullable|integer',
            'max_value' => 'nullable|integer',
            'accept_types' => 'nullable|string',
            'max_file_size' => 'nullable|integer',
            'display_order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['custom_application_step_id'] = $stepId;

            // Handle interest_id field with course selection
            if ($request->field_name === 'interest_id' && $request->has('selected_course_ids')) {
                $courseIds = is_array($request->selected_course_ids) ? $request->selected_course_ids : [$request->selected_course_ids];
                $university = $admin->university;
                $courses = $university->interests()->whereIn('interests.id', $courseIds)->get();
                
                $optionsArray = [];
                foreach ($courses as $course) {
                    $optionsArray[] = [
                        'value' => $course->id,
                        'label' => $course->name
                    ];
                }
                $data['field_options'] = json_encode($optionsArray);
            } elseif ($request->has('field_options') && !empty($request->field_options)) {
                // Parse options (one per line or comma-separated)
                $options = array_filter(array_map('trim', explode("\n", $request->field_options)));
                $optionsArray = [];
                foreach ($options as $option) {
                    $optionsArray[] = [
                        'value' => strtolower(str_replace(' ', '_', $option)),
                        'label' => $option
                    ];
                }
                $data['field_options'] = json_encode($optionsArray);
            }

            $this->fieldRepository->create($data);

            return redirect()->route('university.custom-form.manage-fields', $stepId)
                ->with('success', 'Field added successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add field: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update field
     */
    public function updateField(Request $request, $stepId, $fieldId)
    {
        $admin = Auth::guard('university')->user();

        $step = CustomApplicationStep::where('id', $stepId)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        $field = CustomApplicationStepField::where('id', $fieldId)
            ->where('custom_application_step_id', $stepId)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'field_label' => 'required|string|max:255',
            'field_placeholder' => 'nullable|string',
            'field_options' => 'nullable|string',
            'selected_course_ids' => 'nullable|array',
            'selected_course_ids.*' => 'nullable|exists:interests,id',
            'is_required' => 'nullable|boolean',
            'helper_text' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();

            // Handle interest_id field with course selection
            if ($field->field_name === 'interest_id' && $request->has('selected_course_ids')) {
                $courseIds = is_array($request->selected_course_ids) ? $request->selected_course_ids : [$request->selected_course_ids];
                $university = $admin->university;
                $courses = $university->interests()->whereIn('interests.id', $courseIds)->get();
                
                $optionsArray = [];
                foreach ($courses as $course) {
                    $optionsArray[] = [
                        'value' => $course->id,
                        'label' => $course->name
                    ];
                }
                $data['field_options'] = json_encode($optionsArray);
            } elseif ($request->has('field_options') && !empty($request->field_options)) {
                $options = array_filter(array_map('trim', explode("\n", $request->field_options)));
                $optionsArray = [];
                foreach ($options as $option) {
                    $optionsArray[] = [
                        'value' => strtolower(str_replace(' ', '_', $option)),
                        'label' => $option
                    ];
                }
                $data['field_options'] = json_encode($optionsArray);
            }

            $this->fieldRepository->update($fieldId, $data);

            return response()->json([
                'error' => false,
                'message' => 'Field updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update field: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete field
     */
    public function destroyField($stepId, $fieldId)
    {
        $admin = Auth::guard('university')->user();

        $step = CustomApplicationStep::where('id', $stepId)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        $field = CustomApplicationStepField::where('id', $fieldId)
            ->where('custom_application_step_id', $stepId)
            ->firstOrFail();

        try {
            $this->fieldRepository->deleteById($fieldId);

            return response()->json([
                'error' => false,
                'message' => 'Field deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete field: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Use default template
     */
    public function useDefaultTemplate()
    {
        $admin = Auth::guard('university')->user();
        $universityId = $admin->university_id;

        try {
            DB::beginTransaction();

            // Delete existing custom steps
            CustomApplicationStep::where('university_id', $universityId)->delete();

            // Create default steps from template
            $defaultSteps = \App\Services\AdmissionFormStepService::getFormSteps();

            foreach ($defaultSteps as $stepConfig) {
                $step = CustomApplicationStep::create([
                    'university_id' => $universityId,
                    'step_number' => $stepConfig['step'],
                    'step_title' => $stepConfig['title'],
                    'step_description' => $stepConfig['description'],
                    'step_icon' => $stepConfig['icon'],
                    'display_order' => $stepConfig['step'],
                    'is_required' => true,
                    'status' => true,
                ]);

                // Create fields for this step
                if (!empty($stepConfig['fields'])) {
                    foreach ($stepConfig['fields'] as $index => $fieldConfig) {
                        $fieldData = [
                            'custom_application_step_id' => $step->id,
                            'field_name' => $fieldConfig['name'],
                            'field_type' => $fieldConfig['type'],
                            'field_label' => $fieldConfig['label'],
                            'field_placeholder' => $fieldConfig['placeholder'] ?? null,
                            'is_required' => $fieldConfig['required'],
                            'validation_rules' => $fieldConfig['validation'] ?? null,
                            'field_icon' => $fieldConfig['icon'] ?? null,
                            'helper_text' => $fieldConfig['helper_text'] ?? null,
                            'display_order' => $index + 1,
                            'status' => true,
                        ];

                        // Add options if present
                        if (isset($fieldConfig['options'])) {
                            $fieldData['field_options'] = json_encode($fieldConfig['options']);
                        }

                        // Add file-specific fields
                        if ($fieldConfig['type'] === 'file') {
                            $fieldData['accept_types'] = $fieldConfig['accept'] ?? null;
                            $fieldData['max_file_size'] = isset($fieldConfig['maxSize']) ? 
                                (int) filter_var($fieldConfig['maxSize'], FILTER_SANITIZE_NUMBER_INT) * 1024 : null;
                        }

                        CustomApplicationStepField::create($fieldData);
                    }
                }
            }

            DB::commit();

            return redirect()->route('university.custom-form.index')
                ->with('success', 'Default form template applied successfully! You can now customize it.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to apply template: ' . $e->getMessage());
        }
    }

    /**
     * Reorder steps
     */
    public function reorderSteps(Request $request)
    {
        $admin = Auth::guard('university')->user();

        try {
            DB::beginTransaction();

            foreach ($request->order as $index => $stepId) {
                CustomApplicationStep::where('id', $stepId)
                    ->where('university_id', $admin->university_id)
                    ->update(['display_order' => $index + 1]);
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Steps reordered successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to reorder steps: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder fields within a step
     */
    public function reorderFields(Request $request, $stepId)
    {
        $admin = Auth::guard('university')->user();

        $step = CustomApplicationStep::where('id', $stepId)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        try {
            DB::beginTransaction();

            foreach ($request->order as $index => $fieldId) {
                CustomApplicationStepField::where('id', $fieldId)
                    ->where('custom_application_step_id', $stepId)
                    ->update(['display_order' => $index + 1]);
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Fields reordered successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to reorder fields: ' . $e->getMessage()
            ], 500);
        }
    }
}

