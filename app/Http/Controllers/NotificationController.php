<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(5, min((int) $request->integer('per_page', 10), 50));

        $query = SystemNotification::query()
            ->where('user_id', Auth::id())
            ->latest();

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate($perPage)->withQueryString();

        $unreadCount = SystemNotification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(array_merge(
            $notifications->toArray(),
            ['unread_count' => $unreadCount]
        ));
    }

    public function markRead(SystemNotification $notification)
    {
        abort_unless((int) $notification->user_id === (int) Auth::id(), 403);

        if (is_null($notification->read_at)) {
            $notification->update([
                'read_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markAllRead()
    {
        SystemNotification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return response()->json(['message' => 'All notifications marked as read']);
    }
}
