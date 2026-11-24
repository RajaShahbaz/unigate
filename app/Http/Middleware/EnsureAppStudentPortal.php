<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAppStudentPortal
{
    /**
     * University admission app students: role Student and no school (not tied to e-school LMS).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('Student')) {
            return redirect()->route('dashboard');
        }

        if (!empty($user->school_id)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => __('This portal is for university admission accounts. School students should use the mobile app.'),
            ]);
        }

        return $next($request);
    }
}
