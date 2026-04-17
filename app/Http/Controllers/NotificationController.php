<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function apiIndex(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $perPage = max(5, min((int) $request->integer('per_page', 10), 50));

        $notifications = $user->notifications()
            ->latest()
            ->paginate($perPage);

        $notifications->getCollection()->transform(function (SystemNotification $notification) {
            return $this->transformNotification($notification);
        });

        return response()->json($notifications);
    }

    public function unreadCount()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return response()->json([
            'count' => $user->notifications()->whereNull('read_at')->count(),
        ]);
    }

    public function markRead($id)
    {
        $notification = $this->findUserNotification($id);

        if (!$notification->read_at) {
            $notification->update([
                'read_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Notification marked as read.',
            'notification' => $this->transformNotification($notification->fresh()),
        ]);
    }

    public function markUnread($id)
    {
        $notification = $this->findUserNotification($id);

        $notification->update([
            'read_at' => null,
        ]);

        return response()->json([
            'message' => 'Notification marked as unread.',
            'notification' => $this->transformNotification($notification->fresh()),
        ]);
    }

    public function markAllRead()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->notifications()
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return response()->json([
            'message' => 'All notifications marked as read.',
        ]);
    }

    protected function findUserNotification($id): SystemNotification
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        return $user->notifications()->findOrFail($id);
    }

    protected function transformNotification(SystemNotification $notification): array
    {
        return [
            'id' => $notification->id,
            'title' => $notification->title,
            'message' => $notification->message,
            'type' => $notification->type,
            'url' => $this->resolveNotificationUrl($notification),
            'source_type' => $notification->source_type,
            'source_id' => $notification->source_id,
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at,
            'is_read' => $notification->is_read,
        ];
    }

    protected function resolveNotificationUrl(SystemNotification $notification): string
    {
        if (!empty($notification->url)) {
            return $notification->url;
        }

        return match ($notification->type) {
            'asset_created',
            'asset_updated',
            'asset_deleted',
            'maintenance_due' => '/items',
            'low_stock',
            'inventory_alert' => '/inventory',
            'assignment_created',
            'assignment_returned',
            'assignment_overdue' => '/assignments',
            'user_created',
            'user_updated' => '/users',
            'settings_updated' => '/settings',
            default => '/notifications',
        };
    }
}
