<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppPageController extends Controller
{
    /**
     * Get Privacy Policy
     */
    public function privacyPolicy()
    {
        return response()->json([
            'error' => false,
            'message' => 'Privacy Policy retrieved successfully',
            'data' => [
                'title' => 'Privacy Policy',
                'content' => view('app.pages.privacy-policy')->render(),
                'last_updated' => '2026-01-20'
            ]
        ]);
    }

    /**
     * Get Terms & Conditions
     */
    public function termsConditions()
    {
        return response()->json([
            'error' => false,
            'message' => 'Terms & Conditions retrieved successfully',
            'data' => [
                'title' => 'Terms & Conditions',
                'content' => view('app.pages.terms-conditions')->render(),
                'last_updated' => '2026-01-20'
            ]
        ]);
    }

    /**
     * Get Privacy Policy as HTML (for web view)
     */
    public function privacyPolicyView()
    {
        return view('app.pages.privacy-policy');
    }

    /**
     * Get Terms & Conditions as HTML (for web view)
     */
    public function termsConditionsView()
    {
        return view('app.pages.terms-conditions');
    }

    /**
     * Get Account Deletion Request Page (for web view)
     */
    public function accountDeletionView()
    {
        return view('app.pages.account-deletion');
    }
}
