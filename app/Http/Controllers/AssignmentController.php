<?php

namespace App\Http\Controllers;

use App\Models\AssetLog;
use App\Models\Assignment;
use App\Models\Department;
use App\Models\Item;
use App\Models\User;
use App\Services\SystemNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Assignment::with(['item', 'user', 'assignedDepartment'])->latest('assigned_at');

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('returned_at');
            } elseif ($request->status === 'returned') {
                $query->whereNotNull('returned_at');
            }
        }

        $assignments = $query->paginate(10)->withQueryString();

        return view('assignments.index', compact('assignments'));
    }

    public function create(): View
    {
        $activeItemIds = Assignment::whereNull('returned_at')->pluck('item_id');

        $items = Item::with(['category', 'department'])
            ->whereNotIn('id', $activeItemIds)
            ->where('status', '!=', 'retired')
            ->orderBy('name')
            ->get();

        $users = User::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('assignments.create', compact('items', 'users', 'departments'));
    }

    public function store(Request $request, SystemNotificationService $notificationService): RedirectResponse
    {
        $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.item_id' => ['required', 'exists:items,id'],
            'rows.*.user_id' => ['required', 'exists:users,id'],
            'rows.*.department_id' => ['required', 'exists:departments,id'],
            'rows.*.assigned_at' => ['required', 'date'],
        ]);

        $createdCount = 0;

        foreach ($request->rows as $row) {
            $item = Item::find($row['item_id']);

            if (! $item) {
                continue;
            }

            if ($item->quantity <= 0) {
                continue;
            }

            $alreadyAssigned = Assignment::where('item_id', $row['item_id'])
                ->whereNull('returned_at')
                ->exists();

            if ($alreadyAssigned) {
                continue;
            }

            $assignment = Assignment::create([
                'item_id' => $row['item_id'],
                'user_id' => $row['user_id'],
                'department_id' => $row['department_id'],
                'assigned_at' => $row['assigned_at'],
            ]);

            $assignment->load(['item', 'user', 'assignedDepartment']);

            $item->decrement('quantity');
            $item->refresh();

            $item->update([
                'department_id' => $row['department_id'],
            ]);

            $item->syncAutomatedStatus();

            AssetLog::log(
                $item->id,
                AssetLog::ACTION_ASSIGNED,
                "{$item->name} assigned to {$assignment->user->name}"
            );

            $notificationService->notifyUser(
                $assignment->user_id,
                'info',
                'Asset assigned',
                "{$item->name} assigned to you",
                route('items.show', $item->id)
            );

            $createdCount++;
        }

        return redirect()
            ->route('assignments.index')
            ->with('success', "{$createdCount} assignment(s) processed.");
    }

    public function return(Request $request, Assignment $assignment, SystemNotificationService $notificationService): RedirectResponse
    {
        if ($assignment->returned_at) {
            return back()->with('error', 'This assignment has already been returned.');
        }

        $assignment->load(['item', 'user', 'assignedDepartment']);

        $assignment->update([
            'returned_at' => now(),
        ]);

        $assignment->item->increment('quantity');
        $assignment->item->refresh();
        $assignment->item->syncAutomatedStatus();

        $authUser = Auth::user();

        AssetLog::log(
            $assignment->item_id,
            AssetLog::ACTION_RETURNED,
            ($assignment->item->name ?? 'Asset') . ' returned by ' . ($authUser?->name ?? 'System')
        );

        $notificationService->notifyUser(
            $assignment->user_id,
            'success',
            'Asset return recorded',
            ($assignment->item->name ?? 'Asset') . ' return has been recorded.',
            route('items.show', $assignment->item_id),
            Assignment::class,
            $assignment->id
        );

        $notificationService->notifyAdmins(
            'success',
            'Asset returned',
            ($assignment->item->name ?? 'Asset') . ' has been returned.',
            route('assignments.index'),
            Assignment::class,
            $assignment->id
        );

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Asset returned successfully.');
    }
}