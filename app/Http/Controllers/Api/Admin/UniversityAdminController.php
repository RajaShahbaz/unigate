<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UniversityAdminController extends Controller
{
    /**
     * Get all universities (admin)
     */
    public function index(Request $request)
    {
        try {
            $query = University::with(['city', 'interests']);

            // Search
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $universities = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'error' => false,
                'message' => 'Universities retrieved successfully',
                'data' => $universities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve universities',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new university
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'city_id' => 'required|exists:cities,id',
                'address' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'type' => 'required|in:public,private,semi-government',
                'established_year' => 'nullable|digits:4|integer|min:1800|max:' . date('Y'),
                'facilities' => 'nullable|string',
                'ranking' => 'nullable|numeric|min:0|max:100',
                'tuition_fee_range_min' => 'nullable|numeric|min:0',
                'tuition_fee_range_max' => 'nullable|numeric|min:0',
                'hostel_available' => 'nullable|boolean',
                'scholarship_available' => 'nullable|boolean',
                'admission_criteria' => 'nullable|string',
                'accreditation' => 'nullable|string',
                'status' => 'nullable|boolean',
                'interest_ids' => 'nullable|array',
                'interest_ids.*' => 'exists:interests,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo')->store('universities/logos', 'public');
            }

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $data['cover_image'] = $request->file('cover_image')->store('universities/covers', 'public');
            }

            // Extract interest_ids before creating university
            $interestIds = $data['interest_ids'] ?? [];
            unset($data['interest_ids']);

            $university = University::create($data);

            // Attach interests if provided
            if (!empty($interestIds)) {
                $university->interests()->attach($interestIds);
            }

            $university->load(['city', 'interests']);

            return response()->json([
                'error' => false,
                'message' => 'University created successfully',
                'data' => $university
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create university',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show university details
     */
    public function show($id)
    {
        try {
            $university = University::with(['city', 'interests'])->findOrFail($id);

            return response()->json([
                'error' => false,
                'message' => 'University details retrieved successfully',
                'data' => $university
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve university',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update university
     */
    public function update(Request $request, $id)
    {
        try {
            $university = University::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'city_id' => 'sometimes|exists:cities,id',
                'address' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'type' => 'sometimes|in:public,private,semi-government',
                'established_year' => 'nullable|digits:4|integer|min:1800|max:' . date('Y'),
                'facilities' => 'nullable|string',
                'ranking' => 'nullable|numeric|min:0|max:100',
                'tuition_fee_range_min' => 'nullable|numeric|min:0',
                'tuition_fee_range_max' => 'nullable|numeric|min:0',
                'hostel_available' => 'nullable|boolean',
                'scholarship_available' => 'nullable|boolean',
                'admission_criteria' => 'nullable|string',
                'accreditation' => 'nullable|string',
                'status' => 'nullable|boolean',
                'interest_ids' => 'nullable|array',
                'interest_ids.*' => 'exists:interests,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($university->logo && Storage::disk('public')->exists($university->logo)) {
                    Storage::disk('public')->delete($university->logo);
                }
                $data['logo'] = $request->file('logo')->store('universities/logos', 'public');
            }

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                // Delete old cover
                if ($university->cover_image && Storage::disk('public')->exists($university->cover_image)) {
                    Storage::disk('public')->delete($university->cover_image);
                }
                $data['cover_image'] = $request->file('cover_image')->store('universities/covers', 'public');
            }

            // Extract interest_ids before updating
            $interestIds = $data['interest_ids'] ?? null;
            unset($data['interest_ids']);

            $university->update($data);

            // Sync interests if provided
            if ($interestIds !== null) {
                $university->interests()->sync($interestIds);
            }

            $university->load(['city', 'interests']);

            return response()->json([
                'error' => false,
                'message' => 'University updated successfully',
                'data' => $university
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update university',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete university (soft delete)
     */
    public function destroy($id)
    {
        try {
            $university = University::findOrFail($id);
            $university->delete();

            return response()->json([
                'error' => false,
                'message' => 'University deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete university',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore deleted university
     */
    public function restore($id)
    {
        try {
            $university = University::withTrashed()->findOrFail($id);
            $university->restore();

            return response()->json([
                'error' => false,
                'message' => 'University restored successfully',
                'data' => $university
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to restore university',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manage university interests/programs
     */
    public function updateInterests(Request $request, $id)
    {
        try {
            $university = University::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'interests' => 'required|array',
                'interests.*.interest_id' => 'required|exists:interests,id',
                'interests.*.fee' => 'nullable|numeric|min:0',
                'interests.*.fee_min' => 'nullable|numeric|min:0',
                'interests.*.fee_max' => 'nullable|numeric|min:0',
                'interests.*.duration_years' => 'nullable|integer|min:1|max:10',
                'interests.*.program_details' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            // Additional validation: fee_max should be greater than fee_min
            foreach ($request->interests as $interest) {
                if (isset($interest['fee_min']) && isset($interest['fee_max'])) {
                    if ($interest['fee_max'] < $interest['fee_min']) {
                        return response()->json([
                            'error' => true,
                            'message' => 'Validation error',
                            'data' => ['fee_max' => ['Fee maximum must be greater than or equal to fee minimum']]
                        ], 422);
                    }
                }
            }

            $syncData = [];
            foreach ($request->interests as $interest) {
                $syncData[$interest['interest_id']] = [
                    'fee' => $interest['fee'] ?? null,
                    'fee_min' => $interest['fee_min'] ?? null,
                    'fee_max' => $interest['fee_max'] ?? null,
                    'duration_years' => $interest['duration_years'] ?? null,
                    'program_details' => $interest['program_details'] ?? null,
                ];
            }

            $university->interests()->sync($syncData);
            $university->load('interests');

            return response()->json([
                'error' => false,
                'message' => 'University interests updated successfully',
                'data' => $university
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update university interests',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

