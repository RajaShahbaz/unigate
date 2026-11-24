<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    /**
     * Get user's favorite universities
     */
    public function index(Request $request)
    {
        try {
            $favorites = Auth::user()->favoriteUniversities()
                ->with(['city'])
                ->get();

            return response()->json([
                'error' => false,
                'message' => 'Favorite universities retrieved successfully',
                'data' => $favorites
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve favorite universities',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add university to favorites
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'university_id' => 'required|exists:universities,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();

            // Check if already favorited
            if ($user->favoriteUniversities()->where('university_id', $request->university_id)->exists()) {
                return response()->json([
                    'error' => true,
                    'message' => 'University already in favorites',
                ], 400);
            }

            $user->favoriteUniversities()->attach($request->university_id);

            $university = University::with(['city'])->find($request->university_id);

            return response()->json([
                'error' => false,
                'message' => 'University added to favorites successfully',
                'data' => $university
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to add university to favorites',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove university from favorites
     */
    public function destroy($universityId)
    {
        try {
            $user = Auth::user();
            $user->favoriteUniversities()->detach($universityId);

            return response()->json([
                'error' => false,
                'message' => 'University removed from favorites successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to remove university from favorites',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle favorite status
     */
    public function toggle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'university_id' => 'required|exists:universities,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();

            if ($user->favoriteUniversities()->where('university_id', $request->university_id)->exists()) {
                $user->favoriteUniversities()->detach($request->university_id);
                $message = 'University removed from favorites';
                $isFavorite = false;
            } else {
                $user->favoriteUniversities()->attach($request->university_id);
                $message = 'University added to favorites';
                $isFavorite = true;
            }

            return response()->json([
                'error' => false,
                'message' => $message,
                'data' => [
                    'is_favorite' => $isFavorite,
                    'university_id' => $request->university_id
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to toggle favorite',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

