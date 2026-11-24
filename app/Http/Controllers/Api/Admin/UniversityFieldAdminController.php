<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniversityApplicationField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UniversityFieldAdminController extends Controller
{
    /**
     * Get all custom fields for a university
     */
    public function index(Request $request, $universityId)
    {
        try {
            $query = UniversityApplicationField::where('university_id', $universityId);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $fields = $query->ordered()->get();

            return response()->json([
                'error' => false,
                'message' => 'Custom fields retrieved successfully',
                'data' => $fields
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve custom fields',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new custom field
     */
    public function store(Request $request, $universityId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'field_name' => 'required|string|max:255',
                'field_label' => 'required|string|max:255',
                'field_description' => 'nullable|string',
                'field_type' => 'required|in:text,textarea,number,date,file,select,checkbox,radio,email,url',
                'field_options' => 'nullable|array',
                'file_types' => 'nullable|string',
                'max_file_size' => 'nullable|integer|min:1|max:10240',
                'is_required' => 'nullable|boolean',
                'sort_order' => 'nullable|integer',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $data['university_id'] = $universityId;

            // If no sort_order provided, set to last
            if (!isset($data['sort_order'])) {
                $lastField = UniversityApplicationField::where('university_id', $universityId)
                    ->orderBy('sort_order', 'desc')
                    ->first();
                $data['sort_order'] = $lastField ? $lastField->sort_order + 1 : 0;
            }

            $field = UniversityApplicationField::create($data);

            return response()->json([
                'error' => false,
                'message' => 'Custom field created successfully',
                'data' => $field
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create custom field',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single custom field
     */
    public function show($universityId, $fieldId)
    {
        try {
            $field = UniversityApplicationField::where('university_id', $universityId)
                ->findOrFail($fieldId);

            return response()->json([
                'error' => false,
                'message' => 'Custom field retrieved successfully',
                'data' => $field
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve custom field',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update custom field
     */
    public function update(Request $request, $universityId, $fieldId)
    {
        try {
            $field = UniversityApplicationField::where('university_id', $universityId)
                ->findOrFail($fieldId);

            $validator = Validator::make($request->all(), [
                'field_name' => 'sometimes|string|max:255',
                'field_label' => 'sometimes|string|max:255',
                'field_description' => 'nullable|string',
                'field_type' => 'sometimes|in:text,textarea,number,date,file,select,checkbox,radio,email,url',
                'field_options' => 'nullable|array',
                'file_types' => 'nullable|string',
                'max_file_size' => 'nullable|integer|min:1|max:10240',
                'is_required' => 'nullable|boolean',
                'sort_order' => 'nullable|integer',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $field->update($validator->validated());

            return response()->json([
                'error' => false,
                'message' => 'Custom field updated successfully',
                'data' => $field
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update custom field',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete custom field
     */
    public function destroy($universityId, $fieldId)
    {
        try {
            $field = UniversityApplicationField::where('university_id', $universityId)
                ->findOrFail($fieldId);

            // Check if field has responses
            if ($field->responses()->count() > 0) {
                return response()->json([
                    'error' => true,
                    'message' => 'Cannot delete field with existing responses. Consider deactivating it instead.',
                    'data' => null
                ], 400);
            }

            $field->delete();

            return response()->json([
                'error' => false,
                'message' => 'Custom field deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete custom field',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder custom fields
     */
    public function reorder(Request $request, $universityId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'field_orders' => 'required|array',
                'field_orders.*.id' => 'required|exists:university_application_fields,id',
                'field_orders.*.sort_order' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            foreach ($request->field_orders as $order) {
                UniversityApplicationField::where('id', $order['id'])
                    ->where('university_id', $universityId)
                    ->update(['sort_order' => $order['sort_order']]);
            }

            return response()->json([
                'error' => false,
                'message' => 'Fields reordered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to reorder fields',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle field status (activate/deactivate)
     */
    public function toggleStatus($universityId, $fieldId)
    {
        try {
            $field = UniversityApplicationField::where('university_id', $universityId)
                ->findOrFail($fieldId);

            $field->update(['status' => !$field->status]);

            return response()->json([
                'error' => false,
                'message' => 'Field status updated successfully',
                'data' => $field
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update field status',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
