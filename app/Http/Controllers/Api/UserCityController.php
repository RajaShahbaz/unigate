<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserCityController extends Controller
{
    /**
     * Get all available cities
     */
    public function getAllCities()
    {
        try {
            $cities = City::where('status', 1)->get();

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
     * Get user's preferred cities
     */
    public function index(Request $request)
    {
        try {
            $cities = Auth::user()->preferredCities;

            return response()->json([
                'error' => false,
                'message' => 'User preferred cities retrieved successfully',
                'data' => $cities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve user cities',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add cities to user profile (can add multiple at once)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'city_ids' => 'required|array|min:1',
                'city_ids.*' => 'required|exists:cities,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $user->preferredCities()->syncWithoutDetaching($request->city_ids);

            $cities = $user->preferredCities()->get();

            return response()->json([
                'error' => false,
                'message' => 'Preferred cities added successfully',
                'data' => $cities
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to add preferred cities',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a city from user's preferred cities
     */
    public function destroy($cityId)
    {
        try {
            $user = Auth::user();
            $user->preferredCities()->detach($cityId);

            return response()->json([
                'error' => false,
                'message' => 'City removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to remove city',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Replace all user's preferred cities
     */
    public function sync(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'city_ids' => 'required|array',
                'city_ids.*' => 'required|exists:cities,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $user->preferredCities()->sync($request->city_ids);

            $cities = $user->preferredCities()->get();

            return response()->json([
                'error' => false,
                'message' => 'Preferred cities synchronized successfully',
                'data' => $cities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to synchronize cities',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

