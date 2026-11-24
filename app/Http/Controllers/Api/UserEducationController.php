<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserEducation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserEducationController extends Controller
{
    /**
     * Get all education records for authenticated user
     */
    public function index(Request $request)
    {
        try {
            $education = Auth::user()->education()->orderBy('start_year', 'desc')->get();

            return response()->json([
                'error' => false,
                'message' => 'Education records retrieved successfully',
                'data' => $education
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve education records',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new education record
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'education_level' => 'required|in:matric,intermediate,graduation,post_graduation,diploma,other',
                'institution_name' => 'required|string|max:255',
                'board_university' => 'nullable|string|max:255',
                'field_of_study' => 'nullable|string|max:255',
                'start_year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
                'end_year' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 10),
                'percentage_cgpa' => 'nullable|numeric|min:0|max:100',
                'grade_type' => 'nullable|in:percentage,cgpa',
                'certificate_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'additional_info' => 'nullable|string',
                'is_current' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $data['user_id'] = Auth::id();

            // Handle file upload
            if ($request->hasFile('certificate_document')) {
                $data['certificate_document'] = $request->file('certificate_document')->store('education_certificates', 'public');
            }

            $education = UserEducation::create($data);

            return response()->json([
                'error' => false,
                'message' => 'Education record added successfully',
                'data' => $education
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to add education record',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an education record
     */
    public function update(Request $request, $id)
    {
        try {
            $education = UserEducation::where('user_id', Auth::id())->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'education_level' => 'sometimes|in:matric,intermediate,graduation,post_graduation,diploma,other',
                'institution_name' => 'sometimes|string|max:255',
                'board_university' => 'nullable|string|max:255',
                'field_of_study' => 'nullable|string|max:255',
                'start_year' => 'sometimes|digits:4|integer|min:1900|max:' . (date('Y') + 1),
                'end_year' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 10),
                'percentage_cgpa' => 'nullable|numeric|min:0|max:100',
                'grade_type' => 'nullable|in:percentage,cgpa',
                'certificate_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'additional_info' => 'nullable|string',
                'is_current' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle file upload
            if ($request->hasFile('certificate_document')) {
                // Delete old file
                if ($education->certificate_document && Storage::disk('public')->exists($education->certificate_document)) {
                    Storage::disk('public')->delete($education->certificate_document);
                }
                $data['certificate_document'] = $request->file('certificate_document')->store('education_certificates', 'public');
            }

            $education->update($data);

            return response()->json([
                'error' => false,
                'message' => 'Education record updated successfully',
                'data' => $education
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update education record',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an education record
     */
    public function destroy($id)
    {
        try {
            $education = UserEducation::where('user_id', Auth::id())->findOrFail($id);

            // Delete certificate file if exists
            if ($education->certificate_document && Storage::disk('public')->exists($education->certificate_document)) {
                Storage::disk('public')->delete($education->certificate_document);
            }

            $education->delete();

            return response()->json([
                'error' => false,
                'message' => 'Education record deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete education record',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

