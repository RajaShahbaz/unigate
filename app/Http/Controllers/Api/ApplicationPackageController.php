<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationPackage;
use App\Models\Payment;
use App\Models\PaymentConfiguration;
use App\Models\School;
use App\Models\User;
use App\Models\UserApplicationPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class ApplicationPackageController extends Controller
{
    /**
     * Purchase an application package
     */
    public function purchase(Request $request)
    {
        try {
            // Basic validation
            $validator = Validator::make($request->all(), [
                'package_id' => 'required|exists:application_packages,id',
                'application_id' => 'nullable|exists:applications,id',
                'user_id' => 'nullable|exists:users,id',
                'promo_code' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->first(),
                    'data' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            if (empty($user)) {
                $user = User::find($request->user_id);
            }

            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => 'User not found'
                ], 401);
            }

            $package = ApplicationPackage::findOrFail($request->package_id);

            if (!$package->status) {
                return response()->json([
                    'error' => true,
                    'message' => 'This package is not available'
                ], 422);
            }

            // Check if user already purchased this package
            $existingPurchase = UserApplicationPackage::where('user_id', $user->id)
                ->where('package_id', $package->id)
                ->where('payment_status', 'completed')
                ->first();

            if ($existingPurchase) {
                return response()->json([
                    'error' => false,
                    'message' => 'Package already purchased',
                    'data' => [
                        'purchase' => $existingPurchase,
                        'already_purchased' => true,
                        'has_package' => true,
                    ]
                ]);
            }

            // Get Stripe configuration (system-wide, no school context)
            $paymentConfig = PaymentConfiguration::whereNull('school_id')
                ->where('payment_method', 'stripe')
                ->first();

            if (!$paymentConfig || !$paymentConfig->status) {
                return response()->json([
                    'error' => true,
                    'message' => 'Stripe payment is not enabled. Please contact support.'
                ], 422);
            }

            if (empty($paymentConfig->secret_key)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Stripe is not configured correctly (missing secret key).'
                ], 422);
            }

            $currency = $package->currency ?: $paymentConfig->currency_code;
            $amount = (float) $package->price;
            $school = null;
            $discountPercentage = 0;
            $discountAmount = 0;
            $finalAmount = $amount;

            if (!empty($request->promo_code)) {
                $school = School::where('promo_code', $request->promo_code)->where('status', 1)->first();
                if (!$school) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Invalid promo code',
                    ], 422);
                }

                $discountPercentage = (float) ($school->discount_percentage ?? 0);
                $discountAmount = round(($amount * $discountPercentage) / 100, 2);
                $finalAmount = max(round($amount - $discountAmount, 2), 0);
            }

            if ($finalAmount <= 0) {
                return response()->json([
                    'error' => true,
                    'message' => 'Invalid package price.'
                ], 422);
            }

            Stripe::setApiKey($paymentConfig->secret_key);

            DB::beginTransaction();

            // Create a pending purchase record
            $purchase = UserApplicationPackage::create([
                'user_id'       => $user->id,
                'package_id'    => $package->id,
                'application_id'=> $request->application_id,
                'amount_paid'   => 0,
                'currency'      => $currency,
                'payment_status'=> 'pending',
                'payment_method'=> 'stripe',
                'transaction_id'=> null,
                'purchased_at'  => null,
            ]);

            // Create Stripe Checkout session (opens in new tab)
            $session = StripeSession::create([
                'line_items' => [[
                    'price_data' => [
                        'currency'     => strtolower($currency),
                        'product_data' => [
                            'name' => $package->name,
                        ],
                        'unit_amount'  => (int) round($finalAmount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode'        => 'payment',
                'metadata'    => [
                    'type'                       => 'application_package',
                    'user_application_package_id'=> $purchase->id,
                    'user_id'                    => $user->id,
                    'package_id'                 => $package->id,
                    'application_id'             => $request->application_id,
                    'promo_code'                 => $request->promo_code,
                    'school_id'                  => $school?->id,
                    'discount_percentage'        => $discountPercentage,
                    'discount_amount'            => $discountAmount,
                ],
                'success_url' => url('application-packages/payment/success') . '/{CHECKOUT_SESSION_ID}',
                'cancel_url'  => url('application-packages/payment/cancel') . '/{CHECKOUT_SESSION_ID}',
            ]);

            $purchase->update([
                'transaction_id' => $session->id,
            ]);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Checkout session created successfully',
                'data' => [
                    'checkout_url' => $session->url,
                    'session_id'   => $session->id,
                    'purchase'     => $purchase,
                    'package'      => $package,
                    'applied_discount' => [
                        'promo_code' => $request->promo_code,
                        'discount_percentage' => $discountPercentage,
                        'discount_amount' => $discountAmount,
                        'original_amount' => $amount,
                        'final_amount' => $finalAmount,
                    ],
                ]
            ]);
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            return response()->json([
                'error' => true,
                'message' => 'Failed to purchase package',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check current user's package status
     */
    public function status(Request $request)
    {
        try {
            $user = Auth::user();
            if (empty($user)) {
                $user = User::find($request->user_id);
            }

            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => 'User not found'
                ], 401);
            }

            $package = ApplicationPackage::where('status', 1)->first();

            if (!$package) {
                return response()->json([
                    'error' => false,
                    'message' => 'No active package configured',
                    'data' => [
                        'package_required' => false,
                        'has_package' => false,
                        'package' => null,
                        'user_package' => null,
                    ]
                ]);
            }

            $userPackage = UserApplicationPackage::where('user_id', $user->id)
                ->where('package_id', $package->id)
                ->where('payment_status', 'completed')
                ->first();

            return response()->json([
                'error' => false,
                'message' => 'Package status retrieved successfully',
                'data' => [
                    'package_required' => true,
                    'has_package' => $userPackage !== null,
                    'package' => $package,
                    'user_package' => $userPackage,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve package status',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stripe checkout success callback
     */
    public function stripeSuccess($sessionId)
    {
        try {
            $paymentConfig = PaymentConfiguration::whereNull('school_id')
                ->where('payment_method', 'stripe')
                ->first();

            if (!$paymentConfig || !$paymentConfig->status || empty($paymentConfig->secret_key)) {
                return view('application.package-payment-result', [
                    'success' => false,
                    'message' => 'Payment configuration is not available. Please contact support.',
                ]);
            }

            Stripe::setApiKey($paymentConfig->secret_key);

            $session = StripeSession::retrieve($sessionId);

            if ($session->payment_status !== 'paid') {
                return view('application.package-payment-result', [
                    'success' => false,
                    'message' => 'Payment not completed. If you have been charged, please contact support.',
                ]);
            }

            $purchaseId = $session->metadata['user_application_package_id'] ?? null;

            if ($purchaseId) {
                $purchase = UserApplicationPackage::find($purchaseId);
            } else {
                $purchase = UserApplicationPackage::where('transaction_id', $sessionId)->first();
            }

            if ($purchase && $purchase->payment_status !== 'completed') {
                $schoolId = $session->metadata['school_id'] ?? null;
                $discountPercentage = (float) ($session->metadata['discount_percentage'] ?? 0);
                $discountAmount = (float) ($session->metadata['discount_amount'] ?? 0);
                $grossAmount = isset($session->amount_total) ? ($session->amount_total / 100) : 0;
                $profitPercentage = 0;
                $profitAmount = 0;
                if (!empty($schoolId)) {
                    $school = School::find($schoolId);
                    $profitPercentage = (float) ($school->profit_percentage ?? 0);
                    $profitAmount = round(($grossAmount * $profitPercentage) / 100, 2);
                }

                $purchase->update([
                    'payment_status' => 'completed',
                    'amount_paid'    => $grossAmount ?: $purchase->amount_paid,
                    'currency'       => $session->currency ?? $purchase->currency,
                    'purchased_at'   => now(),
                ]);

                Payment::updateOrCreate(
                    ['trx_id' => $sessionId],
                    [
                        'payer_id' => $purchase->user_id,
                        'school_id' => $schoolId,
                        'application_package_id' => $purchase->package_id,
                        'date' => now()->toDateString(),
                        'paid_amount' => $grossAmount,
                        'tax_paid' => 0,
                        'payment_method' => 'stripe',
                        'transaction_date' => now()->format('YmdHis'),
                        'transaction_expiry_date' => now()->format('YmdHis'),
                        'transaction_status' => '999',
                        'currency' => strtoupper($session->currency ?? $purchase->currency ?? 'PKR'),
                        'bill_reference' => $sessionId,
                        'mobile_no' => optional(User::find($purchase->user_id))->mobile,
                        'message' => 'Stripe payment success',
                        'discounted_amount' => $discountAmount,
                        'promo_code' => $session->metadata['promo_code'] ?? null,
                        'discount_percentage' => $discountPercentage,
                        'discount_amount' => $discountAmount,
                        'profit_percentage' => $profitPercentage,
                        'profit_amount' => $profitAmount,
                    ]
                );
            }

            return view('application.package-payment-result', [
                'success' => true,
                'message' => 'Payment successful! You can now return to the application form and submit your application.',
            ]);
        } catch (\Exception $e) {
            return view('application.package-payment-result', [
                'success' => false,
                'message' => 'We could not verify your payment. If you have been charged, please contact support.',
            ]);
        }
    }

    /**
     * Stripe checkout cancel callback
     */
    public function stripeCancel($sessionId = null)
    {
        if ($sessionId) {
            $purchase = UserApplicationPackage::where('transaction_id', $sessionId)->first();
            if ($purchase && $purchase->payment_status === 'pending') {
                $purchase->update([
                    'payment_status' => 'cancelled',
                ]);
            }
        }

        return view('application.package-payment-result', [
            'success' => false,
            'message' => 'Payment was cancelled. You can safely close this page and try again from the application form.',
        ]);
    }
}
