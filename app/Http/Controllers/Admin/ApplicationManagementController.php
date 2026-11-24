<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationManagementController extends Controller
{
    /**
     * Display a listing of applications for Super Admins.
     */
    public function index(Request $request)
    {
        $statusOptions = [
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'documents_pending' => 'Documents Pending',
            'under_review' => 'Under Review',
            'shortlisted' => 'Shortlisted',
            'interview_scheduled' => 'Interview Scheduled',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'withdrawn' => 'Withdrawn',
        ];

        $query = Application::with(['user', 'university', 'interest'])->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('university_id')) {
            $query->where('university_id', $request->university_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($sub) use ($search) {
                        $sub->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Date filter
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        if ($dateFrom) {
            $query->whereDate('submitted_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('submitted_at', '<=', $dateTo);
        }

        // Get all results for DataTables (client-side pagination)
        $applications = $query->get();

        $statusStats = Application::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $universities = University::orderBy('name')->get(['id', 'name']);

        return view('admin.applications.index', [
            'applications' => $applications,
            'statusOptions' => $statusOptions,
            'statusStats' => $statusStats,
            'universities' => $universities,
            'filters' => [
                'status' => $request->status ?? 'all',
                'university_id' => $request->university_id,
                'search' => $request->search,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ]);
    }

    public function show($id)
    {
        $application = Application::with([
            'user',
            'university',
            'interest',
            'reviewer',
            'fieldResponses.field'
        ])->findOrFail($id);

        return view('admin.applications.show', [
            'application' => $application,
        ]);
    }
}


