<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use App\Mail\PasswordResetCodeMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'mobile'     => 'required|string|max:20|unique:users,mobile',
            'gender'     => 'nullable',
            'dob'        => 'nullable|date',
            'email'      => 'required|string|email|unique:users,email',
            'password'   => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // try {
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'mobile'     => $request->mobile,
                'gender'     => $request->gender,
                'dob'        => $request->dob,
                'email'      => $request->email,
                'role'       => 'user',
                'password'   => Hash::make($request->password),
            ]);

            $user->assignRole('Student');

            // Generate code
            $code = rand(100000, 999999);

            $user->verification_code = $code;
            $user->verification_expires_at = now()->addMinutes(15);
            $user->save();

            // Send verification email
            try {
                Mail::to($user->email)->send(new VerificationCodeMail($code, $user->first_name . ' ' . $user->last_name));
                Log::info('Verification email sent successfully to: ' . $user->email);
            } catch (\Exception $mailException) {
                Log::error('Failed to send verification email: ' . $mailException->getMessage());
                // Don't fail the registration if email fails, just log it
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully. Please verify your email.',
                'data'  => $user,
            ], 200);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Something went wrong during registration',
        //     ], 500);
        // }
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code'  => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Already Verified',
            ], 422);
        }

        if (!$user->verification_code || $user->verification_code !== $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code',
            ], 400);
        }

        if (now()->greaterThan($user->verification_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code expired',
            ], 400);
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_expires_at = null;
        $user->save();
        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully',
            'token'   => $token,
            'data'  => $user,

        ], 200);
    }


    public function resendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already verified',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Generate a new 6-digit code
            $code = rand(100000, 999999);

            $user->verification_code = $code;
            $user->verification_expires_at = now()->addMinutes(15);
            $user->save();

            // Send verification code email
            try {
                Mail::to($user->email)->send(new VerificationCodeMail($code, $user->first_name . ' ' . $user->last_name));
                Log::info('Resend verification email sent successfully to: ' . $user->email);
            } catch (\Exception $mailException) {
                Log::error('Failed to resend verification email: ' . $mailException->getMessage());
                // Don't fail the request if email fails, just log it
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Verification code resent successfully',

            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $user = User::where('email', $request->email)->first();
            $code = rand(100000, 999999);

            $user->password_reset_code = $code;
            $user->password_reset_expires_at = now()->addMinutes(15);
            $user->save();

            // Send password reset email
            try {
                Mail::to($user->email)->send(new PasswordResetCodeMail($code, $user->first_name . ' ' . $user->last_name));
                Log::info('Password reset email sent successfully to: ' . $user->email);
            } catch (\Exception $mailException) {
                Log::error('Failed to send password reset email: ' . $mailException->getMessage());
                // Don't fail the request if email fails, just log it
            }

            return response()->json([
                'success' => true,
                'message' => 'Password reset code sent to your email',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function verifyResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code'  => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->password_reset_code || $user->password_reset_code !== $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reset code',
            ], 400);
        }

        if (now()->greaterThan($user->password_reset_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Reset code expired',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reset code verified successfully',
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|exists:users,email',
            'code'     => 'required|numeric',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->password_reset_code || $user->password_reset_code !== $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reset code',
            ], 400);
        }

        if (now()->greaterThan($user->password_reset_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Reset code expired',
            ], 400);
        }

        $user->password = Hash::make($request->password);
        $user->password_reset_code = null;
        $user->password_reset_expires_at = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully',
        ], 200);
    }




    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $user = User::where('email', $request->email)->with('education', 'interests', 'PreferredCities')->first();



            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password',
                ], 401);
            }
            if ($request->fcm_id) {
                $user->fcm_id = $request->fcm_id;
                $user->save();
            }

            // Check email verification
            if (is_null($user->email_verified_at)) {
                try {
                    $code = rand(100000, 999999);

                    $user->verification_code = $code;
                    $user->verification_expires_at = now()->addMinutes(15);
                    $user->save();
                    Mail::to($user->email)->send(new VerificationCodeMail($code, $user->first_name . ' ' . $user->last_name));
                    Log::info('Unverified login email sent successfully to: ' . $user->email);
                } catch (\Exception $mailException) {
                    Log::error('Failed to send password unverified login email: ' . $mailException->getMessage());
                    // Don't fail the request if email fails, just log it
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your email before logging in.',
                    'is_verified'  => false,
                    'data'    => $user
                ], 403);
            }

            // Create token (Sanctum)
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token'   => $token,
                'data'    => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during login',
            ], 500);
        }
    }

    public function deleteAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'confirmation' => 'required|string|in:DELETE',
        ], [
            'confirmation.in' => 'Please type DELETE to confirm account deletion.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user->tokens()->delete();
            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Unable to delete account right now. Please try again.',
            ], 500);
        }
    }
}
