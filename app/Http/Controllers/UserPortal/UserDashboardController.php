<?php

namespace App\Http\Controllers\UserPortal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\StudentDocument;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $userId = Auth::id();

        $applications = Application::where('user_id', $userId)->get();
        $documents = StudentDocument::where('user_id', $userId)->get();

        $stats = [
            'universities' => University::where('status', 1)->count(),
            'applications' => $applications->count(),
            'documents' => $documents->count(),
            'pending_applications' => $applications->whereIn('status', ['submitted', 'documents_pending', 'under_review'])->count(),
        ];

        $recentApplications = Application::where('user_id', $userId)
            ->with(['university', 'interest'])
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        return view('user-portal.dashboard', compact('stats', 'recentApplications'));
    }

    public function universities(Request $request)
    {
        $universities = University::query()
            ->where('status', 1)
            ->with(['city:id,name'])
            ->orderBy('name')
            ->get();

        return view('user-portal.universities', compact('universities'));
    }

    public function applications()
    {
        $applications = Application::where('user_id', Auth::id())
            ->with(['university', 'interest'])
            ->orderByDesc('updated_at')
            ->get();

        return view('user-portal.applications', compact('applications'));
    }

    public function applicationShow(int $id)
    {
        $application = Application::where('user_id', Auth::id())
            ->with(['university', 'interest', 'fieldResponses.field'])
            ->findOrFail($id);

        return view('user-portal.application-show', compact('application'));
    }

    public function documents()
    {
        $documents = StudentDocument::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('user-portal.documents', compact('documents'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
