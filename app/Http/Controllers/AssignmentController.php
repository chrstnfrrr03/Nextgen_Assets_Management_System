<?php

namespace App\Http\Controllers;

use App\Models\AssetLog;
use App\Models\Assignment;
use App\Models\Item;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(5, min((int) $request->integer('per_page', 10), 50));

        $query = Assignment::with(['item', 'user', 'assignedDepartment'])
            ->latest('assigned_at');

        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('item', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('asset_tag', 'like', "%{$search}%");
                })
                    ->orWhereHas('user', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('assignedDepartment', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('returned_at');
            } elseif ($request->status === 'returned') {
                $query->whereNotNull('returned_at');
            }
        }

        return response()->json($query->paginate($perPage)->withQueryString());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'user_id' => ['required', 'exists:users,id'],
            'department_id' => ['required', 'exists:departments,id'],
        ]);

        $item = Item::findOrFail($validated['item_id']);

        if ($item->quantity <= 0) {
            return response()->json(['message' => 'Item is out of stock'], 422);
        }

        $alreadyAssigned = Assignment::where('item_id', $validated['item_id'])
            ->whereNull('returned_at')
            ->exists();

        if ($alreadyAssigned) {
            return response()->json(['message' => 'Item is already assigned'], 422);
        }

        $assignment = Assignment::create([
            'item_id' => $validated['item_id'],
            'user_id' => $validated['user_id'],
            'department_id' => $validated['department_id'],
            'assigned_at' => now(),
        ]);

        $assignment->load(['item', 'user', 'assignedDepartment']);

        $item->decrement('quantity');
        $item->refresh();
        $item->update(['department_id' => $validated['department_id']]);

        if (method_exists($item, 'syncAutomatedStatus')) {
            $item->syncAutomatedStatus();
        }

        AssetLog::log(
            $item->id,
            AssetLog::ACTION_ASSIGNED,
            "{$item->name} assigned to {$assignment->user->name}"
        );

        $this->notifyAdmins(
            'assignment_created',
            'Asset Assigned',
            "Asset '{$item->name}' was assigned to '{$assignment->user->name}'.",
            '/assignments',
            'assignment',
            $assignment->id
        );

        $this->notifyUser(
            (int) $assignment->user_id,
            'assignment_created',
            'Asset Assigned To You',
            "You were assigned asset '{$item->name}'.",
            '/assignments',
            'assignment',
            $assignment->id
        );

        if ((int) $item->quantity <= 5) {
            $this->notifyAdmins(
                'low_stock',
                'Low Stock Alert',
                "Asset '{$item->name}' is low in stock with quantity {$item->quantity}.",
                '/inventory',
                'item',
                $item->id
            );
        }

        return response()->json($assignment, 201);
    }

    public function returnItem(Assignment $assignment)
    {
        if ($assignment->returned_at) {
            return response()->json(['message' => 'Assignment already returned'], 422);
        }

        $assignment->load(['item', 'user', 'assignedDepartment']);

        $assignment->update([
            'returned_at' => now(),
        ]);

        $assignment->item->increment('quantity');
        $assignment->item->refresh();

        if (method_exists($assignment->item, 'syncAutomatedStatus')) {
            $assignment->item->syncAutomatedStatus();
        }

        AssetLog::log(
            $assignment->item_id,
            AssetLog::ACTION_RETURNED,
            ($assignment->item->name ?? 'Asset') . ' returned by ' . (Auth::user()?->name ?? 'System')
        );

        $this->notifyAdmins(
            'assignment_returned',
            'Asset Returned',
            "Asset '{$assignment->item->name}' was returned from '{$assignment->user->name}'.",
            '/assignments',
            'assignment',
            $assignment->id
        );

        return response()->json([
            'message' => 'Asset returned successfully',
            'assignment' => $assignment->fresh(['item', 'user', 'assignedDepartment']),
        ]);
    }

    protected function notifyAdmins(
        string $type,
        string $title,
        string $message,
        string $url,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            SystemNotification::create([
                'user_id' => $admin->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'read_at' => null,
            ]);
        }
    }

    protected function notifyUser(
        int $userId,
        string $type,
        string $title,
        string $message,
        string $url,
        ?string $sourceType = null,
        ?int $sourceId = null
    ): void {
        SystemNotification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'read_at' => null,
        ]);
    }
}
