<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApplicationAdminController extends Controller
{
    /**
     * Get all applications for the university (admin view)
     */
    public function index(Request $request)
    {
        try {
            // In a real app, you'd filter by the admin's university
            // For now, we'll allow filtering by university_id
            $query = Application::with(['user', 'university', 'interest', 'reviewer', 'fieldResponses.field']);

            // Filter by university
            if ($request->has('university_id')) {
                $query->byUniversity($request->university_id);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->byStatus($request->status);
            }

            // Filter by interest/program
            if ($request->has('interest_id')) {
                $query->where('interest_id', $request->interest_id);
            }

            // Search by application number or student name
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('application_number', 'like', '%' . $search . '%')
                        ->orWhere('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            }

            // Filter by date range
            if ($request->has('date_from')) {
                $query->whereDate('submitted_at', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('submitted_at', '<=', $request->date_to);
            }

            // Only show submitted applications by default
            if (!$request->has('include_drafts') || !$request->include_drafts) {
                $query->submitted();
            }

            $perPage = $request->get('per_page', 15);
            $applications = $query->orderBy('submitted_at', 'desc')->paginate($perPage);

            // Add statistics
            $stats = [
                'total' => Application::byUniversity($request->university_id ?? null)->count(),
                'submitted' => Application::byUniversity($request->university_id ?? null)->byStatus('submitted')->count(),
                'documents_pending' => Application::byUniversity($request->university_id ?? null)->byStatus('documents_pending')->count(),
                'under_review' => Application::byUniversity($request->university_id ?? null)->byStatus('under_review')->count(),
                'shortlisted' => Application::byUniversity($request->university_id ?? null)->byStatus('shortlisted')->count(),
                'accepted' => Application::byUniversity($request->university_id ?? null)->byStatus('accepted')->count(),
                'rejected' => Application::byUniversity($request->university_id ?? null)->byStatus('rejected')->count(),
            ];

            return response()->json([
                'error' => false,
                'message' => 'Applications retrieved successfully',
                'data' => $applications,
                'statistics' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve applications',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single application details (admin view)
     */
    public function show($id)
    {
        try {
            $application = Application::with([
                'user',
                'university',
                'interest',
                'reviewer',
                'fieldResponses.field'
            ])->findOrFail($id);

            return response()->json([
                'error' => false,
                'message' => 'Application retrieved successfully',
                'data' => $application
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve application',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $application = Application::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:submitted,documents_pending,under_review,shortlisted,interview_scheduled,accepted,rejected,withdrawn',
                'admin_notes' => 'nullable|string',
                'rejection_reason' => 'nullable|string|required_if:status,rejected',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $data = [
                'status' => $request->status,
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ];

            if ($request->has('admin_notes')) {
                $data['admin_notes'] = $request->admin_notes;
            }

            if ($request->status === 'rejected' && $request->has('rejection_reason')) {
                $data['rejection_reason'] = $request->rejection_reason;
            }

            $application->update($data);

            $application->load(['user', 'university', 'interest', 'reviewer']);

            return response()->json([
                'error' => false,
                'message' => 'Application status updated successfully',
                'data' => $application
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update application status',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add admin notes to application
     */
    public function addNotes(Request $request, $id)
    {
        try {
            $application = Application::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'admin_notes' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $application->update([
                'admin_notes' => $request->admin_notes,
                'reviewed_by' => Auth::id(),
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Notes added successfully',
                'data' => $application
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to add notes',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update application status
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'application_ids' => 'required|array',
                'application_ids.*' => 'exists:applications,id',
                'status' => 'required|in:documents_pending,under_review,shortlisted,interview_scheduled,accepted,rejected',
                'admin_notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $updateData = [
                'status' => $request->status,
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ];

            if ($request->has('admin_notes')) {
                $updateData['admin_notes'] = $request->admin_notes;
            }

            Application::whereIn('id', $request->application_ids)->update($updateData);

            return response()->json([
                'error' => false,
                'message' => 'Applications updated successfully',
                'data' => [
                    'updated_count' => count($request->application_ids)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update applications',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get application statistics
     */
    public function statistics(Request $request)
    {
        try {
            $universityId = $request->get('university_id');

            $query = Application::query();
            if ($universityId) {
                $query->byUniversity($universityId);
            }

            $stats = [
                'total_applications' => (clone $query)->count(),
                'draft' => (clone $query)->byStatus('draft')->count(),
                'submitted' => (clone $query)->byStatus('submitted')->count(),
                'under_review' => (clone $query)->byStatus('under_review')->count(),
                'shortlisted' => (clone $query)->byStatus('shortlisted')->count(),
                'interview_scheduled' => (clone $query)->byStatus('interview_scheduled')->count(),
                'accepted' => (clone $query)->byStatus('accepted')->count(),
                'rejected' => (clone $query)->byStatus('rejected')->count(),
                'withdrawn' => (clone $query)->byStatus('withdrawn')->count(),
                'pending_review' => (clone $query)->whereIn('status', ['submitted', 'under_review'])->count(),
                'this_month' => (clone $query)->whereMonth('submitted_at', date('m'))->whereYear('submitted_at', date('Y'))->count(),
                'this_year' => (clone $query)->whereYear('submitted_at', date('Y'))->count(),
            ];

            // Get applications by program/interest
            $byInterest = (clone $query)->submitted()
                ->selectRaw('interest_id, count(*) as count')
                ->groupBy('interest_id')
                ->with('interest:id,name')
                ->get()
                ->map(function ($item) {
                    return [
                        'interest' => $item->interest->name ?? 'Unknown',
                        'count' => $item->count
                    ];
                });

            return response()->json([
                'error' => false,
                'message' => 'Statistics retrieved successfully',
                'data' => [
                    'overview' => $stats,
                    'by_program' => $byInterest
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve statistics',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export applications (placeholder for CSV/Excel export)
     */
    public function export(Request $request)
    {
        try {
            // This is a placeholder - implement actual export logic based on your needs
            return response()->json([
                'error' => false,
                'message' => 'Export functionality - to be implemented',
                'data' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to export applications',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
