<?php

use App\Models\User;
use App\Models\Notification;
use App\Services\CachingService;
use Google\Client;

function send_notification($user, $title, $body, $type, $customData = [], $redirectUrl = null) {
    // Ensure $user is an array
    $userIds = is_array($user) ? $user : [$user];
    
    // Save notifications to database for each user
    foreach ($userIds as $userId) {
        try {
            Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $body,
                'body' => $body,
                'type' => $type,
                'data' => $customData,
                'redirect_url' => $redirectUrl,
                'is_read' => false,
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error but continue with push notification
            logger()->error('Failed to save notification to database', [
                'user_id' => $userId,
                'title' => $title,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    $FcmTokens = User::where('fcm_id', '!=', '')->whereIn('id', $userIds)->get()->pluck('fcm_id');


    $cache = app(CachingService::class);


    $project_id = 'reina-unigate';
    $url = 'https://fcm.googleapis.com/v1/projects/' . $project_id . '/messages:send';

    $access_token = getAccessToken();
    if (!$access_token) {
        logger()->warning('Firebase service credentials not configured. Skipping push notification.', [
            'user_ids' => $user,
            'title' => $title,
        ]);
        return;
    }

    foreach ($FcmTokens as $FcmToken) {

        $data = [
            "message" => [
                "token" => $FcmToken,
                "notification" => [
                    "title" => $title,
                    "body" => $body
                ],
                "data" => [
                    "title" => $title,
                    "body" => $body,
                    "type" => $type,
                    ...$customData
                ],
                "android" => [
                    "notification"=> [
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ],

                ],
                "apns" => [
                    "headers" => [
                        "apns-priority" => "10" // Set APNs priority to 10 (high) for immediate delivery
                    ],
                    "payload" => [
                        "aps" => [
                            "alert" => [
                                "title" => $title,
                                "body" => $body,
                            ],
                            "sound" => "default",
                            "badge" => 1
                        ],
                        "type" => $type,
                        ...$customData
                    ]
                ]
            ]
        ];

        $encodedData = json_encode($data);

        $headers = [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        
        // Close connection
        curl_close($ch);
        
        // Log errors for debugging
        if ($result === FALSE) {
            logger()->error('FCM curl request failed', [
                'error' => $curlError,
                'token' => substr($FcmToken, 0, 20) . '...', // Log partial token for security
                'title' => $title,
            ]);
        } elseif ($httpCode !== 200) {
            logger()->warning('FCM request returned non-200 status', [
                'http_code' => $httpCode,
                'response' => $result,
                'token' => substr($FcmToken, 0, 20) . '...',
                'title' => $title,
            ]);
        } else {
            // Log successful iOS notifications for debugging
            $responseData = json_decode($result, true);
            if (isset($responseData['error'])) {
                logger()->warning('FCM returned error in response', [
                    'error' => $responseData['error'],
                    'token' => substr($FcmToken, 0, 20) . '...',
                    'title' => $title,
                ]);
            }
        }
    }
}

function getAccessToken()
{
    $cache = app(CachingService::class);

    $file_name = 'google-services.json';
    $data = explode("storage/", $file_name ?? '');
    $file_name = end($data);

    if (!$file_name) {
        return null;
    }

    $file_path = base_path('public/storage/'. $file_name);

    if (!file_exists($file_path)) {
        logger()->warning('Firebase service account file missing', ['path' => $file_path]);
        return null;
    }

    $client = new Client();
    $client->setAuthConfig($file_path);
    $client->setScopes(['https://www.googleapis.com/auth/firebase.messaging']);
    $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'] ?? null;

    return $accessToken;
}

