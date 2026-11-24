<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApplicationPackage;
use App\Models\Package;
use App\Models\Payment;
use App\Models\School;
use App\Models\UserApplicationPackage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

class JazzCashGatewayController extends Controller
{
    public function payWithWallet(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'application_package_id' => 'required|exists:application_packages,id',
            'CNIC' => 'required|string|max:15',
            'mobile' => 'required|string|max:15',
            'user_id' => 'required|exists:users,id',
            'application_id' => 'nullable|exists:applications,id',
            'promo_code' => 'nullable|string|max:50',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $tax_percentage = 1.5;
        $package = ApplicationPackage::findOrFail($request->application_package_id);

        $data['HashKey'] = config('services.jazz_live.hash_key'); //Your HashKey/integrity salt from transaction Credentials
        
        // Get return URL - use IPN endpoint if not configured
        $returnUrl = config('services.jazz_live.return_url');
        if (empty($returnUrl)) {
            // Fallback to IPN endpoint - must be a full URL
            $returnUrl = url('/api/jazzcash/ipn');
        } else {
            // Ensure it's a full URL (add http:// or https:// if missing)
            if (!preg_match('/^https?:\/\//', $returnUrl)) {
                $returnUrl = url($returnUrl);
            }
        }
        
        // Validate return URL format
        if (!filter_var($returnUrl, FILTER_VALIDATE_URL)) {
            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Invalid return URL configuration. Please set LIVE_jAZZ_RETURN_URL in your .env file.',
            ], 500);
        }
        
        $data['ReturnURL'] = $returnUrl; //Your Return URL, It must be static
        
        $data['PostURL'] = config('services.jazz_live.live_wallet_post_url');
        
        // Validate required configuration
        if (empty($data['HashKey']) || empty($data['PostURL'])) {
            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'JazzCash configuration is incomplete. Please contact support.',
            ], 500);
        }


        date_default_timezone_set("Asia/karachi");
        $pp_amount = (float) $package->price; // Convert to float
        $school = null;
        $discountPercentage = 0;
        $discountAmount = 0;
        if (!empty($request->promo_code)) {
            $school = School::where('promo_code', $request->promo_code)->where('status', 1)->first();
            if (!$school) {
                return response()->json([
                    'status' => 'error',
                    'success' => false,
                    'message' => 'Invalid promo code.',
                ], 422);
            }
            $discountPercentage = (float) ($school->discount_percentage ?? 0);
            $discountAmount = round(($pp_amount * $discountPercentage) / 100, 2);
            $pp_amount = max(round($pp_amount - $discountAmount, 2), 0);
        }
        $pp_amount = number_format($pp_amount, 2, '.', '');
        // $data['pp_Amount'] = 100 * $bill->balance; //The last two digits will be treated as decimal, so multiply the product amount by 100 (e.g. 2x100=200).
        $data['pp_Amount'] = 100 * $pp_amount;
        $data['pp_Version'] = '1.1';
        $data["pp_TxnType"]="MWALLET";

        $data['pp_BillReference'] = "packageRef-". now()->format('YmdHis'); //use AlphaNumeric onlyp
        $data['pp_CNIC'] = $request->CNIC;
        $data['pp_Description'] = $package->description; //use AlphaNumeric only
        $data['pp_Language'] = "EN"; // do not change it
        $data['pp_MerchantID'] = config('services.jazz_live.merchant_id');
        // dd($data);
        $data['pp_MobileNumber'] = $request->mobile;
        $data['pp_Password'] = config('services.jazz_live.password');
        // $data['pp_Password'] = env('SANDBOX_jAZZ_PASSWORD');
        // $data['pp_Password'] ="w05xtv2tf8";
        $data['pp_TxnCurrency'] = "PKR"; // do not change it
        $data['pp_TxnDateTime'] = date('YmdHis'); // use Pakistan Time Zone GMT+5
        $data['pp_TxnExpiryDateTime'] = date('YmdHis', strtotime('+1 Days'));
        $data['pp_TxnRefNo'] = "T" . date('YmdHis') . mt_rand(10, 100);
        $data['ppmpf_1'] = ""; // use to store extra details (use AlphaNumeric only)
        $data['ppmpf_2'] = ""; // use to store extra details (use AlphaNumeric only)
        $data['ppmpf_3'] = ""; // use to store extra details (use AlphaNumeric only)
        $data['ppmpf_4'] = ""; // use to store extra details (use AlphaNumeric only)
        $data['ppmpf_5'] = ""; // use to store extra details (use AlphaNumeric only)
        $HashArray = [
            $data['pp_Amount'],
            $data['pp_BillReference'],
            $data['pp_CNIC'],
            $data['pp_Description'],
            $data['pp_Language'],
            $data['pp_MerchantID'],
            $data['pp_MobileNumber'],
            $data['pp_Password'],
            $data['pp_TxnCurrency'],
            $data['pp_TxnDateTime'],
            $data['pp_TxnExpiryDateTime'],
            $data['pp_TxnRefNo']
        ];

        $SortedArray = $data['HashKey'];
        for ($i = 0; $i < count($HashArray); $i++) {
            if ($HashArray[$i] != 'undefined' and $HashArray[$i] != null and $HashArray[$i] != "") {
                $SortedArray .= "&" . $HashArray[$i];
            }
        }

        $data['pp_SecureHash'] = hash_hmac('sha256', $SortedArray, $data['HashKey']);

        $client = new Client();
        try {
            // Make the request
            $response = $client->post($data['PostURL'], [
                'form_params' => $data, // or 'json' => $data if you need to send JSON
                'verify' => false, // Disable SSL verification
            ]);

            // Get the response body
            $responseBody = $response->getBody()->getContents();
            $return_data = json_decode($responseBody);

            // Check if response has required fields
            if (!isset($return_data->pp_Amount)) {
                throw new \Exception('Invalid response from JazzCash: Missing pp_Amount');
            }

            $pp_amount = (float)($return_data->pp_Amount / 100);
            $pp_amount_without_tax = $pp_amount - round($pp_amount * 0.015, 2);

            DB::beginTransaction();
            try {
                // Create payment record - ensure all decimal values are properly formatted
                $taxPaid = $pp_amount - $pp_amount_without_tax;
                $discountedAmount = isset($return_data->pp_DiscountedAmount)
                    ? (is_numeric($return_data->pp_DiscountedAmount) ? (float)$return_data->pp_DiscountedAmount : 0)
                    : 0;

                $data['tax_paid'] = round($taxPaid, 2);
                $data['payer_id'] = $request->user_id;
                $data['application_package_id'] = $package->id;
                $data['trx_id'] = $return_data->pp_TxnRefNo ?? $data['pp_TxnRefNo'] ?? null;
                $data['date'] = Carbon::now()->format('Y-m-d');
                $data['paid_amount'] = round($pp_amount_without_tax, 2);
                $data['payment_method'] = $return_data->pp_TxnType ?? 'MWALLET';
                $data['transaction_date'] = $return_data->pp_TxnDateTime ?? null;
                $data['transaction_expiry_date'] = date('YmdHis', strtotime('+1 Days'));
                $data['transaction_status'] = $return_data->pp_ResponseCode ?? null;
                $data['currency'] = $return_data->pp_TxnCurrency ?? 'PKR';
                $data['bill_reference'] = $return_data->pp_BillReference ?? null;
                $data['retreival_reference_no'] = $return_data->pp_RetreivalReferenceNo ?? null;
                $data['mobile_no'] = $return_data->pp_MobileNumber ?? $request->mobile ?? null;
                $data['CNIC'] = $return_data->pp_CNIC ?? $request->CNIC ?? null;
                $data['message'] = $return_data->pp_ResponseMessage ?? '';
                $data['discounted_amount'] = round($discountedAmount, 2);
                $data['school_id'] = $school?->id;
                $data['promo_code'] = $request->promo_code;
                $data['discount_percentage'] = $discountPercentage;
                $data['discount_amount'] = $discountAmount;
                $profitPercentage = (float) ($school?->profit_percentage ?? 0);
                $data['profit_percentage'] = $profitPercentage;
                $data['profit_amount'] = round(($data['paid_amount'] * $profitPercentage) / 100, 2);

                $payment = Payment::create($data);

                // Check if payment is successful (999 = success)
                $responseCode = $return_data->pp_ResponseCode ?? null;
                $isSuccess = ($responseCode == '999' || $responseCode == '000');

                // Create or update UserApplicationPackage
                $userPackage = UserApplicationPackage::updateOrCreate(
                    [
                        'user_id' => $request->user_id,
                        'package_id' => $package->id,
                        'application_id' => $request->application_id,
                    ],
                    [
                        'amount_paid' => $isSuccess ? round($pp_amount_without_tax, 2) : 0,
                        'currency' => $data['currency'],
                        'payment_status' => $isSuccess ? 'completed' : 'failed',
                        'payment_method' => 'jazzcash_wallet',
                        'transaction_id' => $data['trx_id'],
                        'purchased_at' => $isSuccess ? now() : null,
                    ]
                );

                DB::commit();

                return response()->json([
                    'status' => $isSuccess ? 'success' : 'error',
                    'success' => $isSuccess,
                    'message' => $isSuccess ? 'Payment successful' : ($return_data->pp_ResponseMessage ?? 'Payment failed'),
                    'data' => [
                        'payment' => $payment,
                        'user_package' => $userPackage,
                        'response' => json_decode($responseBody),
                    ]
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'success' => false,
                    'message' => 'Failed to process payment: ' . $e->getMessage(),
                ], 500);
            }
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function cardPayment(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'application_package_id' => 'required|exists:application_packages,id',
            'CNIC' => 'required|string|max:15',
            'mobile' => 'required|string|max:15',
            'user_id' => 'required|exists:users,id',
            'application_id' => 'nullable|exists:applications,id',
        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 422);
        }

        $package = ApplicationPackage::findOrFail($request->application_package_id);

        // For card payment, we'll need to handle the JazzCash card payment flow
        // This is typically handled via a redirect to JazzCash payment page
        // For now, we'll create a similar structure as wallet payment

        return response()->json([
            'status' => 'info',
            'success' => false,
            'message' => 'Card payment integration requires JazzCash card payment gateway setup. Please use wallet payment for now.',
            'data' => [
                'package' => $package,
            ]
        ], 400);
    }

 public function ipn(Request $request)
    {
        \Log::info('final payment status'.$request->all());

    }
}
