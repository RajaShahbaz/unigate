<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UniversityDashboardController extends Controller
{
    /**
     * Show university dashboard
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $universityId = $admin->university_id;

        // Get statistics
        $stats = [
            'total_applications' => Application::byUniversity($universityId)->count(),
            'pending' => Application::byUniversity($universityId)->whereIn('status', ['submitted', 'documents_pending', 'under_review'])->count(),
            'shortlisted' => Application::byUniversity($universityId)->byStatus('shortlisted')->count(),
            'accepted' => Application::byUniversity($universityId)->byStatus('accepted')->count(),
            'rejected' => Application::byUniversity($universityId)->byStatus('rejected')->count(),
            'this_month' => Application::byUniversity($universityId)
                ->whereMonth('submitted_at', date('m'))
                ->whereYear('submitted_at', date('Y'))
                ->count(),
        ];

        // Recent applications
        $recentApplications = Application::byUniversity($universityId)
            ->with(['interest', 'user'])
            ->submitted()
            ->orderBy('submitted_at', 'desc')
            ->limit(10)
            ->get();

        // Applications by status (for charts)
        $applicationsByStatus = Application::byUniversity($universityId)
            ->select('status', DB::raw('count(*) as count'))
            ->whereNotNull('submitted_at')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Applications by program
        $applicationsByProgram = Application::byUniversity($universityId)
            ->select('interest_id', DB::raw('count(*) as count'))
            ->whereNotNull('submitted_at')
            ->groupBy('interest_id')
            ->with('interest:id,name')
            ->get()
            ->map(function ($item) {
                return [
                    'program' => $item->interest->name ?? 'Unknown',
                    'count' => $item->count
                ];
            });

        // Applications trend (last 12 months)
        $applicationsTrend = Application::byUniversity($universityId)
            ->whereNotNull('submitted_at')
            ->where('submitted_at', '>=', now()->subMonths(12))
            ->select(DB::raw('DATE_FORMAT(submitted_at, "%Y-%m") as month'), DB::raw('count(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        return view('university.dashboard', compact(
            'stats',
            'recentApplications',
            'applicationsByStatus',
            'applicationsByProgram',
            'applicationsTrend',
            'admin'
        ));
    }
}

