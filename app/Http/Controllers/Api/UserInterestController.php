<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserInterestController extends Controller
{
    /**
     * Get all available interests
     */
    public function getAllInterests()
    {
        try {
            $interests = Interest::where('status', 1)->get();

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
     * Get user's selected interests
     */
    public function index(Request $request)
    {
        try {
            $interests = Auth::user()->interests;

            return response()->json([
                'error' => false,
                'message' => 'User interests retrieved successfully',
                'data' => $interests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve user interests',
                'data' => $e->getMessage()
            ], 500);
        }
    }
    public function all(Request $request)
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
     * Add interests to user profile (can add multiple at once)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'interest_ids' => 'required|array|min:1',
                'interest_ids.*' => 'required|exists:interests,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();

            // Sync interests (will remove old ones and add new ones)
            $user->interests()->syncWithoutDetaching($request->interest_ids);

            $interests = $user->interests()->get();

            return response()->json([
                'error' => false,
                'message' => 'Interests added successfully',
                'data' => $interests
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to add interests',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove an interest from user profile
     */
    public function destroy($interestId)
    {
        try {
            $user = Auth::user();
            $user->interests()->detach($interestId);

            return response()->json([
                'error' => false,
                'message' => 'Interest removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to remove interest',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Replace all user interests
     */
    public function sync(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'interest_ids' => 'required|array',
                'interest_ids.*' => 'required|exists:interests,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $user->interests()->sync($request->interest_ids);

            $interests = $user->interests()->get();

            return response()->json([
                'error' => false,
                'message' => 'Interests added successfully',
                'data' => $interests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to add interests',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

