<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UniversityApplicationController extends Controller
{
    /**
     * Display a listing of applications
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $universityId = $admin->university_id;

        $query = Application::byUniversity($universityId)
            ->with(['interest', 'user', 'reviewer']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->byStatus($request->status);
        } else {
            // By default, don't show drafts
            $query->submitted();
        }

        // Filter by program/interest
        if ($request->has('interest_id') && $request->interest_id != '') {
            $query->where('interest_id', $request->interest_id);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('submitted_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('submitted_at', '<=', $request->date_to);
        }

        $applications = $query->orderBy('submitted_at', 'desc')->paginate(20);

        // Get all interests for filter dropdown
        $interests = $admin->university->interests;

        // Get statistics for tabs
        $stats = [
            'all' => Application::byUniversity($universityId)->submitted()->count(),
            'submitted' => Application::byUniversity($universityId)->byStatus('submitted')->count(),
            'documents_pending' => Application::byUniversity($universityId)->byStatus('documents_pending')->count(),
            'under_review' => Application::byUniversity($universityId)->byStatus('under_review')->count(),
            'shortlisted' => Application::byUniversity($universityId)->byStatus('shortlisted')->count(),
            'interview_scheduled' => Application::byUniversity($universityId)->byStatus('interview_scheduled')->count(),
            'accepted' => Application::byUniversity($universityId)->byStatus('accepted')->count(),
            'rejected' => Application::byUniversity($universityId)->byStatus('rejected')->count(),
        ];

        return view('university.applications.index', compact('applications', 'interests', 'stats'));
    }

    /**
     * Display the specified application
     */
    public function show($id)
    {
        $admin = Auth::guard('university')->user();
        $application = Application::byUniversity($admin->university_id)
            ->with(['interest', 'user', 'reviewer', 'fieldResponses.field', 'documentReuploadRequests.requester'])
            ->findOrFail($id);

        return view('university.applications.show', compact('application'));
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, $id)
    {
        $admin = Auth::guard('university')->user();
        $application = Application::byUniversity($admin->university_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:submitted,documents_pending,under_review,shortlisted,interview_scheduled,accepted,rejected',
            'admin_notes' => 'nullable|string',
            'rejection_reason' => 'nullable|string|required_if:status,rejected',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $data = [
            'status' => $request->status,
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
        ];

        if ($request->filled('admin_notes')) {
            $data['admin_notes'] = $request->admin_notes;
        }

        if ($request->status === 'rejected' && $request->filled('rejection_reason')) {
            $data['rejection_reason'] = $request->rejection_reason;
        }

        $application->update($data);
        $title = "Application Status Update";
        $body = 'Status Update on the Application '.$application->application_number;
        $customData = [
            'type' => 'status_update',
            'application_id' => (string) $application->id,
        ];
        send_notification([$application->user_id], $title, $body, 'status_update', $customData);


        return redirect()->back()
            ->with('success', 'Application status updated successfully');
    }

    /**
     * Add or update admin notes
     */
    public function addNotes(Request $request, $id)
    {
        $admin = Auth::guard('university')->user();
        $application = Application::byUniversity($admin->university_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'admin_notes' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $application->update([
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => $admin->id,
        ]);
         $title = "University Comment";
        $body = 'University Admin added new note on the application '.$application->application_number;
        $customData = [
            'type' => 'status_update',
            'application_id' => (string) $application->id,
        ];
        send_notification([$application->user_id], $title, $body, 'status_update', $customData);

        return redirect()->back()
            ->with('success', 'Notes updated successfully');
    }

    /**
     * Bulk update application status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $admin = Auth::guard('university')->user();

        $validator = Validator::make($request->all(), [
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
            'status' => 'required|in:documents_pending,under_review,shortlisted,interview_scheduled,accepted,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $updateData = [
            'status' => $request->status,
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
        ];

        if ($request->filled('admin_notes')) {
            $updateData['admin_notes'] = $request->admin_notes;
        }

        // Only update applications that belong to this university
        Application::byUniversity($admin->university_id)
            ->whereIn('id', $request->application_ids)
            ->update($updateData);

        return redirect()->back()
            ->with('success', count($request->application_ids) . ' applications updated successfully');
    }

    /**
     * Export applications to CSV
     */
    public function export(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $universityId = $admin->university_id;

        $query = Application::byUniversity($universityId)
            ->with(['interest', 'user'])
            ->submitted();

        // Apply same filters as index
        if ($request->has('status') && $request->status != '') {
            $query->byStatus($request->status);
        }
        if ($request->has('interest_id') && $request->interest_id != '') {
            $query->where('interest_id', $request->interest_id);
        }

        $applications = $query->orderBy('submitted_at', 'desc')->get();

        $filename = 'applications_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Application Number',
                'Name',
                'Email',
                'Phone',
                'Program',
                'Status',
                'Submitted At',
                'GPA',
                'Previous School',
            ]);

            // Add data
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->application_number,
                    $application->full_name,
                    $application->email,
                    $application->phone,
                    $application->interest->name ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $application->status)),
                    $application->submitted_at ? $application->submitted_at->format('Y-m-d H:i') : 'N/A',
                    $application->previous_gpa ?? 'N/A',
                    $application->previous_school ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show statistics
     */
    public function statistics()
    {
        $admin = Auth::guard('university')->user();
        $universityId = $admin->university_id;

        // Get various statistics
        $stats = [
            'total' => Application::byUniversity($universityId)->count(),
            'by_status' => Application::byUniversity($universityId)
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status'),
            'by_program' => Application::byUniversity($universityId)
                ->selectRaw('interest_id, count(*) as count')
                ->with('interest:id,name')
                ->groupBy('interest_id')
                ->get(),
            'monthly_trend' => Application::byUniversity($universityId)
                ->whereNotNull('submitted_at')
                ->where('submitted_at', '>=', now()->subMonths(12))
                ->selectRaw('MONTH(submitted_at) as month, count(*) as count')
                ->groupBy('month')
                ->get(),
        ];

        return view('university.applications.statistics', compact('stats'));
    }
}

