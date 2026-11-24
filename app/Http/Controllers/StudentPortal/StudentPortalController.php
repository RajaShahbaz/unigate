<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\AccountController as ApiAccountController;
use App\Models\Application;
use App\Models\ApplicationPackage;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\StudentDocument;
use App\Models\UserApplicationPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentPortalController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $applications = Application::where('user_id', $user->id)->get();

        $statistics = [
            'total_applications' => $applications->count(),
            'pending' => $applications->whereIn('status', ['submitted', 'documents_pending', 'under_review'])->count(),
            'accepted' => $applications->where('status', 'accepted')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
            'draft' => $applications->where('status', 'draft')->count(),
            'shortlisted' => $applications->where('status', 'shortlisted')->count(),
            'withdrawn' => $applications->where('status', 'withdrawn')->count(),
        ];

        $recentApplications = Application::where('user_id', $user->id)
            ->with(['university', 'interest'])
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        return view('student-portal.dashboard', compact('statistics', 'recentApplications'));
    }

    public function education()
    {
        $records = Auth::user()->education()->orderByDesc('end_year')->get();

        return view('student-portal.education', compact('records'));
    }

    public function interests()
    {
        $interests = Auth::user()->interests()->orderBy('name')->get();

        return view('student-portal.interests', compact('interests'));
    }

    public function preferredCities()
    {
        $cities = Auth::user()->preferredCities()->orderBy('name')->get();

        return view('student-portal.preferred-cities', compact('cities'));
    }

    public function favorites()
    {
        $favorites = Auth::user()->favoriteUniversities()->orderBy('name')->get();

        return view('student-portal.favorites', compact('favorites'));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderByDesc('sent_at')
            ->orderByDesc('created_at')
            ->get();

        return view('student-portal.notifications', compact('notifications'));
    }

    public function markNotificationRead(int $id)
    {
        $notification = Notification::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $notification->update(['is_read' => true]);

        return redirect()->route('student.portal.notifications')->with('success', __('Marked as read.'));
    }

    public function markAllNotificationsRead()
    {
        Notification::where('user_id', Auth::id())->where('is_read', false)->update(['is_read' => true]);

        return redirect()->route('student.portal.notifications')->with('success', __('All notifications marked as read.'));
    }

    public function account()
    {
        $user = Auth::user();

        $draftApplications = DB::table('applications')
            ->where('user_id', $user->id)
            ->where('status', 'draft')
            ->whereNull('deleted_at')
            ->count();

        $submittedApplications = DB::table('applications')
            ->where('user_id', $user->id)
            ->where('status', '!=', 'draft')
            ->whereNull('deleted_at')
            ->count();

        $paymentsCount = DB::table('payments')
            ->where('payer_id', $user->id)
            ->whereNull('deleted_at')
            ->count();

        return view('student-portal.account', compact('draftApplications', 'submittedApplications', 'paymentsCount'));
    }

    public function deleteAccount(Request $request)
    {
        $request->merge([
            'confirmation' => $request->input('confirmation'),
            'password' => $request->input('password'),
        ]);

        $response = app(ApiAccountController::class)->deleteAccount($request);
        $payload = json_decode($response->getContent(), true);

        if (($payload['error'] ?? true) === true) {
            $message = $payload['message'] ?? __('Could not delete account.');
            $data = $payload['data'] ?? null;
            if (is_array($data)) {
                return back()->withErrors($data)->withInput();
            }

            return back()->withErrors(['password' => $message])->withInput();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', $payload['message'] ?? __('Your account has been deleted.'));
    }

    public function documents()
    {
        $documents = StudentDocument::where('user_id', Auth::id())->orderByDesc('created_at')->get();

        return view('student-portal.documents', compact('documents'));
    }

    public function applications()
    {
        $applications = Application::where('user_id', Auth::id())
            ->with(['university', 'interest'])
            ->orderByDesc('updated_at')
            ->get();

        return view('student-portal.applications', compact('applications'));
    }

    public function applicationShow(int $id)
    {
        $application = Application::where('user_id', Auth::id())
            ->with(['university', 'interest', 'fieldResponses.field'])
            ->findOrFail($id);

        return view('student-portal.application-show', compact('application'));
    }

    public function packages()
    {
        $packages = ApplicationPackage::where('status', true)->orderBy('name')->get();
        $purchases = UserApplicationPackage::where('user_id', Auth::id())
            ->with('package')
            ->orderByDesc('created_at')
            ->get();

        return view('student-portal.packages', compact('packages', 'purchases'));
    }

    public function payments()
    {
        $payments = Payment::where('payer_id', Auth::id())
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return view('student-portal.payments', compact('payments'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
