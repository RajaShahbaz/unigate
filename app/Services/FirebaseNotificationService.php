<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDeviceToken;
use App\Models\Notification as NotificationModel;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Firebase\Exception\MessagingException;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials'));
        $this->messaging = $factory->createMessaging();
    }

    /**
     * Send notification based on your existing model structure
     */
    public function sendNotification(NotificationModel $notificationModel, User $specificUser = null)
    {
        $tokens = [];
        $userMap = [];

        switch ($notificationModel->send_to) {
            case 'all':
                // Get all active users in the school
                $users = User::where('school_id', $notificationModel->school_id)
                    ->where('status', 1) // active users
                    ->get();
                break;

            case 'specific':
                if ($specificUser) {
                    $users = collect([$specificUser]);
                } else {
                    // If no specific user provided, you might want to handle this case
                    return false;
                }
                break;

            case 'role_based':
                // You can extend this based on your role system
                $users = User::where('school_id', $notificationModel->school_id)
                    ->where('status', 1)
                    ->get();
                break;

            default:
                return false;
        }

        // Collect all device tokens
        foreach ($users as $user) {
            $userTokens = $user->deviceTokens()->where('is_active', true)->pluck('device_token')->toArray();
            foreach ($userTokens as $token) {
                $tokens[] = $token;
                $userMap[$token] = $user;
            }
        }

        if (empty($tokens)) {
            return false;
        }

        return $this->sendToTokens($tokens, $notificationModel, $userMap);
    }

    /**
     * Send to specific device tokens
     */
    public function sendToTokens(array $tokens, NotificationModel $notificationModel, $userMap = null)
    {
        try {
            // Create Firebase notification
            $firebaseNotification = FirebaseNotification::create(
                $notificationModel->title,
                $notificationModel->message
            );

            // Prepare data payload
            $data = [
                'type' => $notificationModel->type ?? 'general',
                'notification_id' => (string) $notificationModel->id,
                'school_id' => (string) $notificationModel->school_id,
                'session_year_id' => (string) $notificationModel->session_year_id,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ];

            // Merge with existing data if any
            if ($notificationModel->data) {
                $data = array_merge($data, $notificationModel->data);
            }

            // Add image to data if exists
            if ($notificationModel->image) {
                $data['image'] = $notificationModel->image;
            }

            $message = CloudMessage::new()
                ->withNotification($firebaseNotification)
                ->withData($data)
                ->withDefaultSounds();

            // If image exists, add it to Android config for display
            if ($notificationModel->image) {
                $message = $message->withAndroidConfig([
                    'notification' => [
                        'image' => $notificationModel->image
                    ]
                ]);
            }

            // Split tokens into chunks to avoid hitting limits
            $chunks = array_chunk($tokens, 500);
            $results = [];

            foreach ($chunks as $chunk) {
                $report = $this->messaging->sendMulticast($message, $chunk);
                $results[] = $report;
            }

            // Update notification sent_at timestamp
            $notificationModel->update(['sent_at' => now()]);

            // Store individual notifications for each user
            $this->storeUserNotifications($notificationModel, $userMap);

            return $results;

        } catch (MessagingException $e) {
            \Log::error('FCM Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send to specific user (convenience method)
     */
    public function sendToUser(User $user, string $title, string $message, string $image = null, array $data = [], string $type = 'general')
    {
        $notification = NotificationModel::create([
            'title' => $title,
            'message' => $message,
            'image' => $image,
            'send_to' => 'specific',
            'school_id' => $user->school_id,
            'session_year_id' => $user->session_year_id, // Make sure this field exists in User model
            'data' => $data,
            'type' => $type
        ]);

        return $this->sendNotification($notification, $user);
    }

    /**
     * Send to all users in school
     */
    public function sendToSchool($schoolId, string $title, string $message, string $image = null, array $data = [], string $type = 'general', $sessionYearId = null)
    {
        $notification = NotificationModel::create([
            'title' => $title,
            'message' => $message,
            'image' => $image,
            'send_to' => 'all',
            'school_id' => $schoolId,
            'session_year_id' => $sessionYearId,
            'data' => $data,
            'type' => $type
        ]);

        return $this->sendNotification($notification);
    }

    /**
     * Send to topic
     */
    public function sendToTopic(string $topic, string $title, string $message, string $image = null, array $data = [], string $type = 'general')
    {
        try {
            $firebaseNotification = FirebaseNotification::create($title, $message);

            $messageData = array_merge($data, [
                'type' => $type,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ]);

            if ($image) {
                $messageData['image'] = $image;
            }

            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($firebaseNotification)
                ->withData($messageData);

            if ($image) {
                $message = $message->withAndroidConfig([
                    'notification' => [
                        'image' => $image
                    ]
                ]);
            }

            $this->messaging->send($message);

            // Store the broadcast notification
            NotificationModel::create([
                'title' => $title,
                'message' => $message,
                'image' => $image,
                'send_to' => 'topic',
                'school_id' => null, // or your default school
                'session_year_id' => null,
                'data' => $data,
                'type' => $type,
                'sent_at' => now(),
            ]);

            return true;

        } catch (MessagingException $e) {
            \Log::error('FCM Topic Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Store individual notifications for each user
     */
    protected function storeUserNotifications(NotificationModel $notificationModel, $userMap = null)
    {
        if (!$userMap) return;

        $users = array_unique($userMap);

        foreach ($users as $user) {
            NotificationModel::create([
                'title' => $notificationModel->title,
                'message' => $notificationModel->message,
                'image' => $notificationModel->image,
                'send_to' => 'individual',
                'school_id' => $notificationModel->school_id,
                'session_year_id' => $notificationModel->session_year_id,
                'data' => $notificationModel->data,
                'type' => $notificationModel->type,
                'notifiable_id' => $user->id,
                'notifiable_type' => get_class($user),
                'sent_at' => now(),
            ]);
        }
    }

    /**
     * Subscribe user to topic
     */
    public function subscribeToTopic($tokens, string $topic)
    {
        try {
            if (is_string($tokens)) {
                $tokens = [$tokens];
            }

            $this->messaging->subscribeToTopic($topic, $tokens);
            return true;

        } catch (MessagingException $e) {
            \Log::error('FCM Subscribe Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Unsubscribe user from topic
     */
    public function unsubscribeFromTopic($tokens, string $topic)
    {
        try {
            if (is_string($tokens)) {
                $tokens = [$tokens];
            }

            $this->messaging->unsubscribeFromTopic($topic, $tokens);
            return true;

        } catch (MessagingException $e) {
            \Log::error('FCM Unsubscribe Error: ' . $e->getMessage());
            return false;
        }
    }
}
