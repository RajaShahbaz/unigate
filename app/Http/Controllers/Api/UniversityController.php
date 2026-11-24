<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UniversityController extends Controller
{
    /**
     * Get universities based on user preferences (interests and cities) if logged in,
     * otherwise return top universities for guests
     */
    public function getRecommendedUniversities(Request $request)
    {
        try {
            $user = Auth::user();
            $isAuthenticated = Auth::check();
            
            $query = University::with(['city'])->where('status', 1);

            // For authenticated users, use their preferences (unless overridden by request)
            // For guests, show top universities ordered by ranking
            if ($isAuthenticated) {
                // Filter by user's preferred cities if not provided in request
                if (!$request->filled('city_id')) {
                    $cityIds = $user->preferredCities()->pluck('cities.id')->toArray();
                    if (!empty($cityIds)) {
                        $query->whereIn('city_id', $cityIds);
                    }
                }

                // Filter by user's interests if not provided in request
                if (!$request->filled('interest_id')) {
                    $interestIds = $user->interests()->pluck('interests.id')->toArray();
                    if (!empty($interestIds)) {
                        $query->whereHas('interests', function ($q) use ($interestIds) {
                            $q->whereIn('interest_id', $interestIds);
                        });
                    }
                }
            } else {
                // For guests, show top universities ordered by ranking (if available) or by established_year
                // If ranking exists, order by ranking (ascending - lower is better), otherwise by established_year (descending - newer is better)
                $query->orderByRaw('CASE WHEN ranking IS NOT NULL AND ranking > 0 THEN ranking ELSE 999999 END ASC')
                      ->orderBy('established_year', 'desc');
            }

            // Apply request filters (overrides user preferences for authenticated users)
            if ($request->filled('city_id')) {
                $cityIds = is_array($request->city_id) ? $request->city_id : [$request->city_id];
                $query->whereIn('city_id', $cityIds);
            }

            if ($request->has('interest_id')) {
                $interestIds = is_array($request->interest_id) ? $request->interest_id : [$request->interest_id];
                $query->whereHas('interests', function ($q) use ($interestIds) {
                    $q->whereIn('interest_id', $interestIds);
                });
            }

            if ($request->has('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->has('ranking')) {
                $query->where('ranking', '>=', $request->ranking);
            }

            if ($request->has('hostel_available')) {
                $query->where('hostel_available', $request->hostel_available);
            }

            if ($request->has('scholarship_available')) {
                $query->where('scholarship_available', $request->scholarship_available);
            }

            // Filter by fee range in university interests
            if ($request->has('fee_min') || $request->has('fee_max')) {
                $feeMin = $request->get('fee_min');
                $feeMax = $request->get('fee_max');

                $query->whereHas('interests', function ($q) use ($feeMin, $feeMax) {
                    if ($feeMin !== null) {
                        // University's program fee_max should be >= user's fee_min
                        $q->where(function ($subQuery) use ($feeMin) {
                            $subQuery->where('university_interests.fee_max', '>=', $feeMin)
                                     ->orWhereNull('university_interests.fee_max');
                        });
                    }
                    if ($feeMax !== null) {
                        // University's program fee_min should be <= user's fee_max
                        $q->where(function ($subQuery) use ($feeMax) {
                            $subQuery->where('university_interests.fee_min', '<=', $feeMax)
                                     ->orWhereNull('university_interests.fee_min');
                        });
                    }
                });
            }

            // For guests without filters, we already ordered by ranking above
            // Pagination will handle limiting the results

            // Pagination
            $perPage = $request->get('per_page', 10);
            $universities = $query->paginate($perPage);

            // Add is_favorite flag only for authenticated users
            if ($isAuthenticated) {
                $universities->getCollection()->transform(function ($university) use ($user) {
                    $university->is_favorite = $user->favoriteUniversities()->where('university_id', $university->id)->exists();
                    return $university;
                });
            } else {
                // For guests, set is_favorite to false
                $universities->getCollection()->transform(function ($university) {
                    $university->is_favorite = false;
                    return $university;
                });
            }

            return response()->json([
                'error' => false,
                'message' => $isAuthenticated ? 'Recommended universities retrieved successfully' : 'Top universities retrieved successfully',
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
     * Get all universities (for general browsing)
     */
    public function index(Request $request)
    {
        try {
            $query = University::with(['city', 'interests'])
                ->withCount([
                    'galleries as gallery_count' => function($query) {
                        $query->where('status', true);
                    },
                    'scholarships as scholarship_count' => function($query) {
                        $query->where('status', true);
                    },
                    'admissionRequirements as admission_requirement_count' => function($query) {
                        $query->where('status', true);
                    }
                ])
                ->where('status', 1);

            // Search by name
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Filter by city
            if ($request->has('city_id')) {
                $query->where('city_id', $request->city_id);
            }

            // Filter by interest
            if ($request->has('interest_id')) {
                $query->whereHas('interests', function ($q) use ($request) {
                    $q->where('interest_id', $request->interest_id);
                });
            }

            // Filter by type
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }
            if ($request->has('ranking')) {
                $query->where('ranking', $request->ranking);
            }

            // Filter by fee range in university interests
            if ($request->has('fee_min') || $request->has('fee_max')) {
                $feeMin = $request->get('fee_min');
                $feeMax = $request->get('fee_max');

                $query->whereHas('interests', function ($q) use ($feeMin, $feeMax) {
                    if ($feeMin !== null) {
                        // University's program fee_max should be >= user's fee_min
                        $q->where(function ($subQuery) use ($feeMin) {
                            $subQuery->where('university_interests.fee_max', '>=', $feeMin)
                                     ->orWhereNull('university_interests.fee_max');
                        });
                    }
                    if ($feeMax !== null) {
                        // University's program fee_min should be <= user's fee_max
                        $q->where(function ($subQuery) use ($feeMax) {
                            $subQuery->where('university_interests.fee_min', '<=', $feeMax)
                                     ->orWhereNull('university_interests.fee_min');
                        });
                    }
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $universities = $query->paginate($perPage);

            // Add is_favorite flag for authenticated users
            if (Auth::check()) {
                $user = Auth::user();
                $universities->getCollection()->transform(function ($university) use ($user) {
                    $university->is_favorite = $user->favoriteUniversities()->where('university_id', $university->id)->exists();
                    return $university;
                });
            }

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
     * Get single university details with gallery, scholarships, and admission requirements
     */
    public function show($id)
    {
        try {
            $university = University::with([
                'city',
                'interests:id,name',
                'activeGalleries' => function($query) {
                    $query->limit(12); // Limit gallery items
                },
                'activeScholarships' => function($query) {
                    $query->orderBy('created_at', 'desc')->limit(10);
                },
                'activeAdmissionRequirements' => function($query) {
                    $query->with('interest')->orderBy('program_level', 'asc');
                }
            ])->findOrFail($id);

            // Add is_favorite flag for authenticated users
            if (Auth::check()) {
                $user = Auth::user();
                $university->is_favorite = $user->favoriteUniversities()->where('university_id', $university->id)->exists();
            }

            // Add counts
            $university->gallery_count = $university->galleries()->active()->count();
            $university->scholarship_count = $university->scholarships()->active()->count();
            $university->admission_requirement_count = $university->admissionRequirements()->active()->count();

            return response()->json([
                'error' => false,
                'message' => 'University details retrieved successfully',
                'data' => $university
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve university details',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

