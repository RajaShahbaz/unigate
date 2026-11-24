<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Delete user account
     * 
     * This endpoint allows authenticated users to delete their own account.
     * According to the account deletion policy:
     * - Account information, preferences, and settings will be deleted
     * - Draft applications will be deleted
     * - Submitted applications may be retained as required by universities
     * - Payment records will be retained for legal compliance
     * - All user tokens will be revoked
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => 'Unauthorized. Please login to delete your account.',
                ], 401);
            }

            // Validate the request - require confirmation
            $validator = Validator::make($request->all(), [
                'confirmation' => 'required|string|in:DELETE',
                'password' => 'required|string', // Optional: require password confirmation
            ], [
                'confirmation.required' => 'Please type "DELETE" to confirm account deletion.',
                'confirmation.in' => 'Please type "DELETE" exactly to confirm account deletion.',
                'password.required' => 'Password is required to confirm account deletion.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'data' => $validator->errors(),
                ], 422);
            }

            // Verify password
            if (!\Hash::check($request->password, $user->password)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Invalid password. Please provide your correct password to delete your account.',
                ], 422);
            }

            // Check for restrictions (as per deletion policy)
            $restrictions = $this->checkDeletionRestrictions($user);
            if (!empty($restrictions)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Account deletion cannot be processed at this time',
                    'data' => [
                        'restrictions' => $restrictions,
                        'contact' => 'privacy@unigate.com',
                    ],
                ], 403);
            }

            DB::beginTransaction();

            try {
                // 1. Delete user preferences and settings
                $this->deleteUserPreferences($user);

                // 2. Delete draft applications (keep submitted ones)
                $this->deleteDraftApplications($user);

                // 3. Revoke all API tokens
                $this->revokeAllTokens($user);

                // 4. Soft delete the user account
                $user->delete();

                DB::commit();

                Log::info('User account deleted', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'deleted_at' => now(),
                ]);

                return response()->json([
                    'error' => false,
                    'message' => 'Your account has been successfully deleted. We\'re sorry to see you go.',
                    'data' => [
                        'deleted_at' => now()->toDateTimeString(),
                        'note' => 'Your account deletion request has been processed. Some data may be retained as required by law or for university records.',
                    ],
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Account deletion failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'error' => true,
                    'message' => 'Failed to delete account. Please contact support if this issue persists.',
                    'data' => config('app.debug') ? $e->getMessage() : null,
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Account deletion error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => true,
                'message' => 'An unexpected error occurred. Please try again later or contact support.',
                'data' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Check if there are any restrictions preventing account deletion
     * 
     * @param \App\Models\User $user
     * @return array
     */
    private function checkDeletionRestrictions($user)
    {
        $restrictions = [];

        // Check for ongoing legal disputes (placeholder - implement based on your system)
        // if ($user->hasLegalDisputes()) {
        //     $restrictions[] = 'There is an ongoing legal dispute associated with your account.';
        // }

        // Check for outstanding payment obligations
        $outstandingPayments = DB::table('payments')
            ->where('payer_id', $user->id)
            ->where('transaction_status', '!=', '999') // Not successful
            ->where('transaction_status', '!=', '000') // Not successful
            ->whereNull('deleted_at')
            ->exists();

        if ($outstandingPayments) {
            $restrictions[] = 'You have outstanding payment obligations. Please resolve these before deleting your account.';
        }

        // Check for active subscriptions (if applicable)
        // if ($user->hasActiveSubscriptions()) {
        //     $restrictions[] = 'You have active subscriptions. Please cancel them before deleting your account.';
        // }

        return $restrictions;
    }

    /**
     * Delete user preferences and settings
     * 
     * @param \App\Models\User $user
     * @return void
     */
    private function deleteUserPreferences($user)
    {
        // Delete user interests
        DB::table('user_interests')->where('user_id', $user->id)->delete();

        // Delete user preferred cities
        DB::table('user_cities')->where('user_id', $user->id)->delete();

        // Delete user favorites
        DB::table('user_favorites')->where('user_id', $user->id)->delete();

        // Delete user education records
        DB::table('user_education')->where('user_id', $user->id)->delete();

        // Delete user notifications
        DB::table('notifications')->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->delete();
    }

    /**
     * Delete draft applications (keep submitted ones)
     * 
     * @param \App\Models\User $user
     * @return void
     */
    private function deleteDraftApplications($user)
    {
        // Only delete draft applications, keep submitted ones for university records
        DB::table('applications')
            ->where('user_id', $user->id)
            ->where('status', 'draft')
            ->update(['deleted_at' => now()]);
    }

    /**
     * Revoke all API tokens for the user
     * 
     * @param \App\Models\User $user
     * @return void
     */
    private function revokeAllTokens($user)
    {
        // Revoke all Sanctum tokens
        $user->tokens()->delete();
    }

    /**
     * Get account deletion information
     * 
     * This endpoint provides information about what will be deleted
     * and what will be retained when the account is deleted.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeletionInfo()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized',
            ], 401);
        }

        // Count user data
        $draftApplications = DB::table('applications')
            ->where('user_id', $user->id)
            ->where('status', 'draft')
            ->count();

        $submittedApplications = DB::table('applications')
            ->where('user_id', $user->id)
            ->where('status', '!=', 'draft')
            ->whereNull('deleted_at')
            ->count();

        $payments = DB::table('payments')
            ->where('payer_id', $user->id)
            ->whereNull('deleted_at')
            ->count();

        return response()->json([
            'error' => false,
            'message' => 'Account deletion information retrieved successfully',
            'data' => [
                'will_be_deleted' => [
                    'account_information' => 'Profile data, preferences, settings',
                    'draft_applications' => $draftApplications . ' draft application(s)',
                    'user_preferences' => 'Interests, preferred cities, favorites',
                    'education_records' => 'All education history',
                    'notifications' => 'All notification preferences and history',
                    'api_tokens' => 'All active API tokens',
                ],
                'will_be_retained' => [
                    'submitted_applications' => $submittedApplications . ' submitted application(s) (for university records)',
                    'payment_records' => $payments . ' payment record(s) (for legal compliance)',
                    'anonymized_data' => 'Aggregated, anonymized data for analytics',
                ],
                'contact' => [
                    'email' => 'privacy@unigate.com',
                    'support' => 'support@unigate.com',
                ],
                'processing_time' => 'Account deletion will be completed within 30 days of verification',
            ],
        ]);
    }
}
