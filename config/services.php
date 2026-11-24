<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'jazz' => [
    'merchant_id' => env('SANDBOX_JAZZ_MERCHANT_ID'),
    'password' => env('SANDBOX_jAZZ_PASSWORD'),
    'hash_key' => env('SANDBOX_jAZZ_HASHKEY'),
    'return_url' => env('SANDBOX_jAZZ_RETURN_URL'),
    'mobile_no' => env('SANDBOX_jAZZ_MOBILE_NO'),
    'sandbox_wallet_post_url' => env('SANDBOX_WALLET_POST_URL'),
    'sandbox_card_post_url' => env('SANDBOX_CARD_POST_URL'),
    'sandbox_inquiry_post_url' => env('SANDBOX_INQUIRY_POST_URL'),
    ],
    'jazz_live' => [
    'merchant_id' => env('LIVE_JAZZ_MERCHANT_ID'),
    'password' => env('LIVE_jAZZ_PASSWORD'),
    'hash_key' => env('LIVE_jAZZ_HASHKEY'),
    'return_url' => env('LIVE_jAZZ_RETURN_URL'),
    'mobile_no' => env('LIVE_jAZZ_MOBILE_NO'),
    'live_wallet_post_url' => env('LIVE_WALLET_POST_URL'),
    'live_card_post_url' => env('LIVE_CARD_POST_URL'),
    'live_inquiry_post_url' => env('LIVE_INQUIRY_POST_URL'),
    ],
    'sms' => [
        'url' => env('SMS_URL'),
        'id' => env('SMS_ID'),
        'pass' => env('SMS_PASSWORD'),
        'mask' => env('SMS_MASK'),
        'lang' => env('SMS_LANG'),
        ],

];
