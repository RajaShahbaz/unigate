<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Models\UniversityAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UniversityAuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::guard('university')->check()) {
            return redirect()->route('university.dashboard');
        }
        return view('university.auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('university')->attempt($credentials, $remember)) {
            $admin = Auth::guard('university')->user();
            
            // Check if account is active
            if (!$admin->isActive()) {
                Auth::guard('university')->logout();
                return redirect()->back()
                    ->with('error', 'Your account has been deactivated. Please contact support.')
                    ->withInput();
            }

            // Check if university is active
            if (!$admin->university || !$admin->university->status) {
                Auth::guard('university')->logout();
                return redirect()->back()
                    ->with('error', 'Your university has been deactivated. Please contact support.')
                    ->withInput();
            }

            // Update last login
            $admin->update(['last_login_at' => now()]);

            return redirect()->intended(route('university.dashboard'))
                ->with('success', 'Welcome back, ' . $admin->name . '!');
        }

        return redirect()->back()
            ->with('error', 'Invalid email or password')
            ->withInput();
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::guard('university')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('university.login')
            ->with('success', 'You have been logged out successfully');
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        return view('university.auth.change-password');
    }

    /**
     * Handle change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $admin = Auth::guard('university')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect');
        }

        $admin->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()
            ->with('success', 'Password changed successfully');
    }

    /**
     * Show profile edit form
     */
    public function showProfile()
    {
        $admin = Auth::guard('university')->user();
        return view('university.auth.profile', compact('admin'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('university')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $data = $request->only(['name', 'phone', 'position']);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('university_admins', 'public');
        }

        $admin->update($data);

        return redirect()->back()
            ->with('success', 'Profile updated successfully');
    }
}

