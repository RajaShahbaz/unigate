<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityAdminController extends Controller
{
    /**
     * Get all cities
     */
    public function index(Request $request)
    {
        try {
            $query = City::query();

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $perPage = $request->get('per_page', 15);
            $cities = $query->orderBy('name')->paginate($perPage);

            return response()->json([
                'error' => false,
                'message' => 'Cities retrieved successfully',
                'data' => $cities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve cities',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new city
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'state_province' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $city = City::create($validator->validated());

            return response()->json([
                'error' => false,
                'message' => 'City created successfully',
                'data' => $city
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create city',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update city
     */
    public function update(Request $request, $id)
    {
        try {
            $city = City::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'state_province' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $city->update($validator->validated());

            return response()->json([
                'error' => false,
                'message' => 'City updated successfully',
                'data' => $city
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update city',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete city
     */
    public function destroy($id)
    {
        try {
            $city = City::findOrFail($id);
            $city->delete();

            return response()->json([
                'error' => false,
                'message' => 'City deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete city',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

