<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UniversityProgramController extends Controller
{
    /**
     * Display a listing of programs/courses offered by the university
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $university = $admin->university;

        // Get all programs offered by this university with pivot data
        $programs = $university->interests()
            ->withPivot('fee', 'fee_min', 'fee_max', 'duration_years', 'program_details')
            ->orderBy('interests.name')
            ->get();

        // Get all available interests that are not yet added
        $availableInterests = Interest::whereNotIn('id', $programs->pluck('id'))
            ->orderBy('name')
            ->get();

        return view('university.programs.index', compact('programs', 'availableInterests', 'university'));
    }

    /**
     * Store a newly added program
     */
    public function store(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $university = $admin->university;

        $validator = Validator::make($request->all(), [
            'interest_id' => 'required|exists:interests,id',
            'fee' => 'nullable|numeric|min:0',
            'fee_min' => 'nullable|numeric|min:0',
            'fee_max' => 'nullable|numeric|min:0|gte:fee_min',
            'duration_years' => 'nullable|integer|min:1|max:10',
            'program_details' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if program already exists
        if ($university->interests()->where('interest_id', $request->interest_id)->exists()) {
            return redirect()->back()
                ->with('error', 'This program is already added to your university')
                ->withInput();
        }

        // Attach the program with additional data
        $university->interests()->attach($request->interest_id, [
            'fee' => $request->fee,
            'fee_min' => $request->fee_min,
            'fee_max' => $request->fee_max,
            'duration_years' => $request->duration_years,
            'program_details' => $request->program_details,
        ]);

        return redirect()->route('university.programs.index')
            ->with('success', 'Program added successfully');
    }

    /**
     * Show the form for editing a program
     */
    public function edit($interestId)
    {
        $admin = Auth::guard('university')->user();
        $university = $admin->university;

        // Get the program with pivot data
        $program = $university->interests()
            ->withPivot('fee', 'fee_min', 'fee_max', 'duration_years', 'program_details')
            ->where('interest_id', $interestId)
            ->firstOrFail();

        return view('university.programs.edit', compact('program', 'university'));
    }

    /**
     * Update the specified program
     */
    public function update(Request $request, $interestId)
    {
        $admin = Auth::guard('university')->user();
        $university = $admin->university;

        $validator = Validator::make($request->all(), [
            'fee' => 'nullable|numeric|min:0',
            'fee_min' => 'nullable|numeric|min:0',
            'fee_max' => 'nullable|numeric|min:0|gte:fee_min',
            'duration_years' => 'nullable|integer|min:1|max:10',
            'program_details' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the pivot data
        $university->interests()->updateExistingPivot($interestId, [
            'fee' => $request->fee,
            'fee_min' => $request->fee_min,
            'fee_max' => $request->fee_max,
            'duration_years' => $request->duration_years,
            'program_details' => $request->program_details,
        ]);

        return redirect()->route('university.programs.index')
            ->with('success', 'Program updated successfully');
    }

    /**
     * Remove the specified program from the university
     */
    public function destroy($interestId)
    {
        $admin = Auth::guard('university')->user();
        $university = $admin->university;

        // Check if there are any applications for this program
        $applicationsCount = DB::table('applications')
            ->where('university_id', $university->id)
            ->where('interest_id', $interestId)
            ->count();

        if ($applicationsCount > 0) {
            return redirect()->back()
                ->with('error', "Cannot remove this program. There are {$applicationsCount} application(s) associated with it.");
        }

        // Detach the program
        $university->interests()->detach($interestId);

        return redirect()->route('university.programs.index')
            ->with('success', 'Program removed successfully');
    }

    /**
     * Bulk add multiple programs at once
     */
    public function bulkAdd(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $university = $admin->university;

        $validator = Validator::make($request->all(), [
            'interest_ids' => 'required|array|min:1',
            'interest_ids.*' => 'exists:interests,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $addedCount = 0;
        $skippedCount = 0;

        foreach ($request->interest_ids as $interestId) {
            // Check if already exists
            if (!$university->interests()->where('interest_id', $interestId)->exists()) {
                $university->interests()->attach($interestId, [
                    'fee' => null,
                    'fee_min' => null,
                    'fee_max' => null,
                    'duration_years' => null,
                    'program_details' => null,
                ]);
                $addedCount++;
            } else {
                $skippedCount++;
            }
        }

        $message = "Added {$addedCount} program(s) successfully";
        if ($skippedCount > 0) {
            $message .= " ({$skippedCount} already existed)";
        }

        return redirect()->route('university.programs.index')
            ->with('success', $message);
    }

    /**
     * Get program statistics
     */
    public function statistics()
    {
        $admin = Auth::guard('university')->user();
        $university = $admin->university;

        $stats = [
            'total_programs' => $university->interests()->count(),
            'programs_with_fees' => $university->interests()
                ->wherePivotNotNull('fee')
                ->orWherePivotNotNull('fee_min')
                ->count(),
            'applications_by_program' => DB::table('applications')
                ->select('interest_id', DB::raw('count(*) as count'))
                ->where('university_id', $university->id)
                ->whereNotNull('submitted_at')
                ->groupBy('interest_id')
                ->get()
                ->pluck('count', 'interest_id'),
        ];

        return view('university.programs.statistics', compact('stats', 'university'));
    }
}

