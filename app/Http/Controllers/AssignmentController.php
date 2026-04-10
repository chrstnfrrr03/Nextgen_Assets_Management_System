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
        'rows.*.department_id' => ['required', 'exists:departments,id'], // ✅ FIXED
        'rows.*.assigned_at' => ['required', 'date'],
    ]);

    $createdCount = 0;
    $authUser = Auth::user();

    foreach ($request->rows as $row) {

        // Prevent double assignment
        $alreadyAssigned = Assignment::where('item_id', $row['item_id'])
            ->whereNull('returned_at')
            ->exists();

        if ($alreadyAssigned) {
            continue;
        }

        //  CREATE ASSIGNMENT
        $assignment = Assignment::create([
            'item_id' => $row['item_id'],
            'user_id' => $row['user_id'],
            'department_id' => $row['department_id'], // FIXED
            'assigned_at' => $row['assigned_at'],
        ]);

        $assignment->load(['item', 'user', 'assignedDepartment']);

        //  UPDATE ITEM STATUS + DEPARTMENT
        $assignment->item->update([
            'status' => 'assigned',
            'department_id' => $row['department_id'],
        ]);

        //  LOG ACTIVITY
        AssetLog::create([
            'item_id' => $row['item_id'],
            'user_id' => $authUser?->id ?? 1,
            'action' => 'assigned',
            'notes' => ($assignment->item->name ?? 'Asset') . 
                ' assigned to ' . ($assignment->user->name ?? 'User') . 
                ' in ' . ($assignment->assignedDepartment->name ?? 'Department'),
        ]);

        //  NOTIFY USER
        $notificationService->notifyUser(
            $assignment->user_id,
            'info',
            'Asset assigned to you',
            ($assignment->item->name ?? 'Asset') . ' has been assigned to you.',
            route('items.show', $assignment->item_id),
            Assignment::class,
            $assignment->id
        );

        //  NOTIFY ADMINS
        $notificationService->notifyAdmins(
            'info',
            'New assignment created',
            ($assignment->item->name ?? 'Asset') . ' assigned to ' . ($assignment->user->name ?? 'User'),
            route('assignments.index'),
            Assignment::class,
            $assignment->id
        );

        $createdCount++;
    }

    return redirect()
        ->route('assignments.index')
        ->with('success', "{$createdCount} assignment(s) processed successfully.");
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

        $hasOtherActive = Assignment::where('item_id', $assignment->item_id)
            ->whereNull('returned_at')
            ->where('id', '!=', $assignment->id)
            ->exists();

        if (! $hasOtherActive) {
            $assignment->item->update(['status' => 'available']);
        }

        $authUser = Auth::user();

        AssetLog::create([
            'item_id' => $assignment->item_id,
            'user_id' => $authUser?->id ?? 1,
            'action' => 'returned',
            'notes' => ($assignment->item->name ?? 'Asset') . ' returned by ' . ($authUser?->name ?? 'System'),
        ]);

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