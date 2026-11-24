<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->get('status', 'all');
        $genderFilter = $request->get('gender', 'all');
        $schoolFilter = $request->get('school_id', 'all');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = User::role('Student')
            ->with(['school', 'student'])
            ->latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter === 'active');
        }

        if ($genderFilter !== 'all') {
            $query->where('gender', $genderFilter);
        }

        if ($schoolFilter !== 'all') {
            $query->where('school_id', $schoolFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        // Date filter
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Get all results for DataTables (client-side pagination)
        $students = $query->get();

        $stats = [
            'total' => User::role('Student')->count(),
            'active' => User::role('Student')->where('status', 1)->count(),
            'inactive' => User::role('Student')->where('status', 0)->count(),
        ];

        $schools = School::orderBy('name')->get(['id','name']);

        return view('admin.students.index', [
            'students' => $students,
            'stats' => $stats,
            'filters' => [
                'status' => $statusFilter,
                'gender' => $genderFilter,
                'school_id' => $schoolFilter,
                'search' => $search,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
            'schools' => $schools,
        ]);
    }

    public function show($id)
    {
        $student = User::role('Student')
            ->with(['school', 'student', 'applications.university', 'applications.interest'])
            ->findOrFail($id);

        return view('admin.students.show', [
            'student' => $student,
        ]);
    }
}


