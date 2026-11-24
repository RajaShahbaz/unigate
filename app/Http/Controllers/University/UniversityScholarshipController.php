<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Repositories\Scholarship\ScholarshipInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UniversityScholarshipController extends Controller
{
    private ScholarshipInterface $scholarshipRepository;

    public function __construct(ScholarshipInterface $scholarshipRepository)
    {
        $this->scholarshipRepository = $scholarshipRepository;
    }

    /**
     * Display scholarship management page
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $universityId = $admin->university_id;

        $scholarships = $this->scholarshipRepository->builder()
            ->where('university_id', $universityId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('university.scholarships.index', compact('scholarships', 'admin'));
    }

    /**
     * Show form to create new scholarship
     */
    public function create()
    {
        $admin = Auth::guard('university')->user();
        return view('university.scholarships.create', compact('admin'));
    }

    /**
     * Store new scholarship
     */
    public function store(Request $request)
    {
        $admin = Auth::guard('university')->user();

        $validator = Validator::make($request->all(), [
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['university_id'] = $admin->university_id;

            $this->scholarshipRepository->create($data);

            return redirect()->route('university.scholarships.index')
                ->with('success', 'Scholarship created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create scholarship: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form to edit scholarship
     */
    public function edit($id)
    {
        $admin = Auth::guard('university')->user();

        $scholarship = $this->scholarshipRepository->builder()
            ->where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        return view('university.scholarships.edit', compact('scholarship', 'admin'));
    }

    /**
     * Update scholarship
     */
    public function update(Request $request, $id)
    {
        $admin = Auth::guard('university')->user();

        // Verify ownership
        $scholarship = $this->scholarshipRepository->builder()
            ->where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->scholarshipRepository->update($id, $request->all());

            return redirect()->route('university.scholarships.index')
                ->with('success', 'Scholarship updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update scholarship: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete scholarship
     */
    public function destroy($id)
    {
        $admin = Auth::guard('university')->user();

        // Verify ownership
        $scholarship = $this->scholarshipRepository->builder()
            ->where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        try {
            $this->scholarshipRepository->deleteById($id);

            return redirect()->route('university.scholarships.index')
                ->with('success', 'Scholarship deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete scholarship: ' . $e->getMessage());
        }
    }
}

