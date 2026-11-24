<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UniversityAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('university')->check()) {
            return redirect()->route('university.login')->with('error', 'Please login to access university portal');
        }

        $admin = Auth::guard('university')->user();
        
        // Check if university admin is active
        if (!$admin->isActive()) {
            Auth::guard('university')->logout();
            return redirect()->route('university.login')->with('error', 'Your account has been deactivated. Please contact support.');
        }

        // Check if university is active
        if (!$admin->university || !$admin->university->status) {
            Auth::guard('university')->logout();
            return redirect()->route('university.login')->with('error', 'Your university has been deactivated. Please contact support.');
        }

        return $next($request);
    }
}

