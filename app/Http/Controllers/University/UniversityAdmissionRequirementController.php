<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Repositories\AdmissionRequirement\AdmissionRequirementInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UniversityAdmissionRequirementController extends Controller
{
    private AdmissionRequirementInterface $admissionRequirementRepository;

    public function __construct(AdmissionRequirementInterface $admissionRequirementRepository)
    {
        $this->admissionRequirementRepository = $admissionRequirementRepository;
    }

    /**
     * Display admission requirements management page
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $universityId = $admin->university_id;

        $requirements = $this->admissionRequirementRepository->builder()
            ->with('interest')
            ->where('university_id', $universityId)
            ->orderBy('program_level', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('university.admission-requirements.index', compact('requirements', 'admin'));
    }

    /**
     * Show form to create new admission requirement
     */
    public function create()
    {
        $admin = Auth::guard('university')->user();

        // Get available programs/interests
        $programs = Interest::orderBy('name', 'asc')->get();

        return view('university.admission-requirements.create', compact('admin', 'programs'));
    }

    /**
     * Store new admission requirement
     */
    public function store(Request $request)
    {
        $admin = Auth::guard('university')->user();

        $validator = Validator::make($request->all(), [
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['university_id'] = $admin->university_id;

            $this->admissionRequirementRepository->create($data);

            return redirect()->route('university.admission-requirements.index')
                ->with('success', 'Admission requirement created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create admission requirement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form to edit admission requirement
     */
    public function edit($id)
    {
        $admin = Auth::guard('university')->user();

        $requirement = $this->admissionRequirementRepository->builder()
            ->with('interest')
            ->where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        // Get available programs/interests
        $programs = Interest::orderBy('name', 'asc')->get();

        return view('university.admission-requirements.edit', compact('requirement', 'admin', 'programs'));
    }

    /**
     * Update admission requirement
     */
    public function update(Request $request, $id)
    {
        $admin = Auth::guard('university')->user();

        // Verify ownership
        $requirement = $this->admissionRequirementRepository->builder()
            ->where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->admissionRequirementRepository->update($id, $request->all());

            return redirect()->route('university.admission-requirements.index')
                ->with('success', 'Admission requirement updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update admission requirement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete admission requirement
     */
    public function destroy($id)
    {
        $admin = Auth::guard('university')->user();

        // Verify ownership
        $requirement = $this->admissionRequirementRepository->builder()
            ->where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        try {
            $this->admissionRequirementRepository->deleteById($id);

            return redirect()->route('university.admission-requirements.index')
                ->with('success', 'Admission requirement deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete admission requirement: ' . $e->getMessage());
        }
    }
}

