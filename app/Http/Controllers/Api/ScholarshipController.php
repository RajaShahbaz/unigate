<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Scholarship\ScholarshipInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ScholarshipController extends Controller
{
    private ScholarshipInterface $scholarshipRepository;

    public function __construct(ScholarshipInterface $scholarshipRepository)
    {
        $this->scholarshipRepository = $scholarshipRepository;
    }

    /**
     * Get all scholarships
     */
    public function index(Request $request)
    {
        try {
            $query = $this->scholarshipRepository->builder()->with('university');

            // Filter by university
            if ($request->has('university_id')) {
                $query->where('university_id', $request->university_id);
            }

            // Filter by scholarship type
            if ($request->has('scholarship_type')) {
                $query->ofType($request->scholarship_type);
            }

            // Filter by amount type
            if ($request->has('amount_type')) {
                $query->where('amount_type', $request->amount_type);
            }

            // Filter renewable scholarships
            if ($request->has('renewable') && $request->renewable) {
                $query->where('renewable', true);
            }

            // Only active scholarships
            if (!$request->has('include_inactive')) {
                $query->active();
            }

            // Only scholarships accepting applications
            if ($request->has('accepting_applications') && $request->accepting_applications) {
                $query->acceptingApplications();
            }

            // Order by created date
            $query->orderBy('created_at', 'desc');

            // Pagination
            $perPage = $request->get('per_page', 15);
            $scholarships = $query->paginate($perPage);

            return response()->json([
                'error' => false,
                'message' => 'Scholarships retrieved successfully',
                'data' => $scholarships
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve scholarships',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single scholarship
     */
    public function show($id)
    {
        try {
            $scholarship = $this->scholarshipRepository->findById($id, ['*'], ['university']);

            return response()->json([
                'error' => false,
                'message' => 'Scholarship retrieved successfully',
                'data' => $scholarship
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Scholarship not found',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new scholarship
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'university_id' => 'required|exists:universities,id',
                'name' => 'required|string|max:255',
                'scholarship_type' => 'required|string|max:100',
                'description' => 'nullable|string',
                'eligibility_criteria' => 'nullable|string',
                'amount' => 'nullable|numeric|min:0',
                'percentage' => 'nullable|numeric|min:0|max:100',
                'amount_type' => 'required|in:fixed,percentage,full',
                'total_slots' => 'nullable|integer|min:1',
                'available_slots' => 'nullable|integer|min:0',
                'application_start_date' => 'nullable|date',
                'application_end_date' => 'nullable|date|after_or_equal:application_start_date',
                'required_documents' => 'nullable|string',
                'application_process' => 'nullable|string',
                'contact_email' => 'nullable|email',
                'contact_phone' => 'nullable|string|max:20',
                'renewable' => 'nullable|boolean',
                'renewable_years' => 'nullable|integer|min:1',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'data' => $validator->errors()
                ], 422);
            }

            $scholarship = $this->scholarshipRepository->create($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Scholarship created successfully',
                'data' => $scholarship
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create scholarship',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a scholarship
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'university_id' => 'sometimes|required|exists:universities,id',
                'name' => 'sometimes|required|string|max:255',
                'scholarship_type' => 'sometimes|required|string|max:100',
                'description' => 'nullable|string',
                'eligibility_criteria' => 'nullable|string',
                'amount' => 'nullable|numeric|min:0',
                'percentage' => 'nullable|numeric|min:0|max:100',
                'amount_type' => 'sometimes|required|in:fixed,percentage,full',
                'total_slots' => 'nullable|integer|min:1',
                'available_slots' => 'nullable|integer|min:0',
                'application_start_date' => 'nullable|date',
                'application_end_date' => 'nullable|date',
                'required_documents' => 'nullable|string',
                'application_process' => 'nullable|string',
                'contact_email' => 'nullable|email',
                'contact_phone' => 'nullable|string|max:20',
                'renewable' => 'nullable|boolean',
                'renewable_years' => 'nullable|integer|min:1',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'data' => $validator->errors()
                ], 422);
            }

            $scholarship = $this->scholarshipRepository->update($id, $request->all());

            return response()->json([
                'error' => false,
                'message' => 'Scholarship updated successfully',
                'data' => $scholarship
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update scholarship',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a scholarship
     */
    public function destroy($id)
    {
        try {
            $this->scholarshipRepository->deleteById($id);

            return response()->json([
                'error' => false,
                'message' => 'Scholarship deleted successfully',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete scholarship',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get scholarships by university ID (convenience method)
     */
    public function getByUniversity($universityId, Request $request)
    {
        try {
            $query = $this->scholarshipRepository->builder()
                ->where('university_id', $universityId);

            // Only active scholarships by default
            if (!$request->has('include_inactive')) {
                $query->active();
            }

            // Filter by type
            if ($request->has('scholarship_type')) {
                $query->ofType($request->scholarship_type);
            }

            // Only accepting applications
            if ($request->has('accepting_applications') && $request->accepting_applications) {
                $query->acceptingApplications();
            }

            $query->orderBy('created_at', 'desc');

            $perPage = $request->get('per_page', 20);
            $scholarships = $query->paginate($perPage);

            return response()->json([
                'error' => false,
                'message' => 'University scholarships retrieved successfully',
                'data' => $scholarships
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve university scholarships',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

