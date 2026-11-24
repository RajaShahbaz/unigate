<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AdmissionRequirement\AdmissionRequirementInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AdmissionRequirementController extends Controller
{
    private AdmissionRequirementInterface $admissionRequirementRepository;

    public function __construct(AdmissionRequirementInterface $admissionRequirementRepository)
    {
        $this->admissionRequirementRepository = $admissionRequirementRepository;
    }

    /**
     * Get all admission requirements
     */
    public function index(Request $request)
    {
        try {
            $query = $this->admissionRequirementRepository->builder()
                ->with(['university', 'interest']);

            // Filter by university
            if ($request->has('university_id')) {
                $query->where('university_id', $request->university_id);
            }

            // Filter by program/interest
            if ($request->has('interest_id')) {
                $query->forProgram($request->interest_id);
            }

            // Filter by program level
            if ($request->has('program_level')) {
                $query->forLevel($request->program_level);
            }

            // Only general requirements (not program-specific)
            if ($request->has('general_only') && $request->general_only) {
                $query->general();
            }

            // Only active requirements
            if (!$request->has('include_inactive')) {
                $query->active();
            }

            // Order by created date
            $query->orderBy('program_level', 'asc')
                  ->orderBy('created_at', 'desc');

            // Pagination
            $perPage = $request->get('per_page', 15);
            $requirements = $query->paginate($perPage);

            return response()->json([
                'error' => false,
                'message' => 'Admission requirements retrieved successfully',
                'data' => $requirements
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve admission requirements',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single admission requirement
     */
    public function show($id)
    {
        try {
            $requirement = $this->admissionRequirementRepository->findById(
                $id, 
                ['*'], 
                ['university', 'interest']
            );

            return response()->json([
                'error' => false,
                'message' => 'Admission requirement retrieved successfully',
                'data' => $requirement
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Admission requirement not found',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new admission requirement
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'university_id' => 'required|exists:universities,id',
                'interest_id' => 'nullable|exists:interests,id',
                'program_level' => 'required|string|max:100',
                'general_requirements' => 'nullable|string',
                'academic_requirements' => 'nullable|string',
                'minimum_percentage' => 'nullable|numeric|min:0|max:100',
                'minimum_gpa' => 'nullable|numeric|min:0|max:5',
                'entrance_tests' => 'nullable|string',
                'minimum_test_score' => 'nullable|integer|min:0',
                'required_documents' => 'nullable|string',
                'language_requirements' => 'nullable|string',
                'language_test_score' => 'nullable|numeric|min:0',
                'specific_requirements' => 'nullable|string',
                'application_deadline' => 'nullable|date',
                'application_fee' => 'nullable|numeric|min:0',
                'admission_process' => 'nullable|string',
                'estimated_processing_days' => 'nullable|integer|min:1',
                'interview_required' => 'nullable|string',
                'portfolio_required' => 'nullable|string',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'data' => $validator->errors()
                ], 422);
            }

            $requirement = $this->admissionRequirementRepository->create($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Admission requirement created successfully',
                'data' => $requirement
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create admission requirement',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an admission requirement
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'university_id' => 'sometimes|required|exists:universities,id',
                'interest_id' => 'nullable|exists:interests,id',
                'program_level' => 'sometimes|required|string|max:100',
                'general_requirements' => 'nullable|string',
                'academic_requirements' => 'nullable|string',
                'minimum_percentage' => 'nullable|numeric|min:0|max:100',
                'minimum_gpa' => 'nullable|numeric|min:0|max:5',
                'entrance_tests' => 'nullable|string',
                'minimum_test_score' => 'nullable|integer|min:0',
                'required_documents' => 'nullable|string',
                'language_requirements' => 'nullable|string',
                'language_test_score' => 'nullable|numeric|min:0',
                'specific_requirements' => 'nullable|string',
                'application_deadline' => 'nullable|date',
                'application_fee' => 'nullable|numeric|min:0',
                'admission_process' => 'nullable|string',
                'estimated_processing_days' => 'nullable|integer|min:1',
                'interview_required' => 'nullable|string',
                'portfolio_required' => 'nullable|string',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'data' => $validator->errors()
                ], 422);
            }

            $requirement = $this->admissionRequirementRepository->update($id, $request->all());

            return response()->json([
                'error' => false,
                'message' => 'Admission requirement updated successfully',
                'data' => $requirement
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update admission requirement',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an admission requirement
     */
    public function destroy($id)
    {
        try {
            $this->admissionRequirementRepository->deleteById($id);

            return response()->json([
                'error' => false,
                'message' => 'Admission requirement deleted successfully',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete admission requirement',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get admission requirements by university ID (convenience method)
     */
    public function getByUniversity($universityId, Request $request)
    {
        try {
            $query = $this->admissionRequirementRepository->builder()
                ->with('interest')
                ->where('university_id', $universityId)
                ->active();

            // Filter by program level
            if ($request->has('program_level')) {
                $query->forLevel($request->program_level);
            }

            // Filter by program/interest
            if ($request->has('interest_id')) {
                $query->forProgram($request->interest_id);
            }

            // Only general requirements
            if ($request->has('general_only') && $request->general_only) {
                $query->general();
            }

            $query->orderBy('program_level', 'asc')
                  ->orderBy('created_at', 'desc');

            $perPage = $request->get('per_page', 20);
            $requirements = $query->paginate($perPage);

            return response()->json([
                'error' => false,
                'message' => 'University admission requirements retrieved successfully',
                'data' => $requirements
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve university admission requirements',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get admission requirements by program/interest
     */
    public function getByProgram($universityId, $interestId, Request $request)
    {
        try {
            $requirements = $this->admissionRequirementRepository->builder()
                ->with(['university', 'interest'])
                ->where('university_id', $universityId)
                ->where('interest_id', $interestId)
                ->active()
                ->orderBy('program_level', 'asc')
                ->get();

            return response()->json([
                'error' => false,
                'message' => 'Program admission requirements retrieved successfully',
                'data' => $requirements
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve program admission requirements',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

