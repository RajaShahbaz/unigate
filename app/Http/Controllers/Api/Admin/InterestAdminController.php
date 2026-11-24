<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InterestAdminController extends Controller
{
    /**
     * Get all interests
     */
    public function index(Request $request)
    {
        try {
            $query = Interest::query();

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $perPage = $request->get('per_page', 15);
            $interests = $query->orderBy('name')->paginate($perPage);

            return response()->json([
                'error' => false,
                'message' => 'Interests retrieved successfully',
                'data' => $interests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve interests',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new interest
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:interests,name',
                'description' => 'nullable|string',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $interest = Interest::create($validator->validated());

            return response()->json([
                'error' => false,
                'message' => 'Interest created successfully',
                'data' => $interest
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create interest',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update interest
     */
    public function update(Request $request, $id)
    {
        try {
            $interest = Interest::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255|unique:interests,name,' . $id,
                'description' => 'nullable|string',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $interest->update($validator->validated());

            return response()->json([
                'error' => false,
                'message' => 'Interest updated successfully',
                'data' => $interest
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update interest',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete interest
     */
    public function destroy($id)
    {
        try {
            $interest = Interest::findOrFail($id);
            $interest->delete();

            return response()->json([
                'error' => false,
                'message' => 'Interest deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete interest',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

