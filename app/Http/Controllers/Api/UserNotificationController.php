<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('sent_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'error' => false,
                'message' => 'Notifications retrieved successfully',
                'data' => $notifications
            ]);
        } catch (\Throwable $e) {
            ResponseService::logErrorResponse($e, "UserNotificationController index Method");
            ResponseService::errorResponse();
        }
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            
            $notification = Notification::where('user_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();

            $notification->update([
                'is_read' => true
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Notification marked as read',
                'data' => $notification
            ]);
        } catch (\Throwable $e) {
            ResponseService::logErrorResponse($e, "UserNotificationController markAsRead Method");
            ResponseService::errorResponse();
        }
    }

    /**
     * Mark all notifications as read for the authenticated user
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            
            $updated = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'error' => false,
                'message' => 'All notifications marked as read',
                'data' => [
                    'updated_count' => $updated
                ]
            ]);
        } catch (\Throwable $e) {
            ResponseService::logErrorResponse($e, "UserNotificationController markAllAsRead Method");
            ResponseService::errorResponse();
        }
    }
}
