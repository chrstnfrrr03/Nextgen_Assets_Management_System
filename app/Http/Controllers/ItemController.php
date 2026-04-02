<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use App\Models\AssetLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\Department;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Item::with([
            'category',
            'supplier',
            'assignments.user',
            'assignments.department'
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('part_name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('part_no', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        $categories = Category::all();
        $suppliers = Supplier::all();
        $users = User::all();
        $departments = Department::all();

        $logs = AssetLog::with(['user','item'])->latest()->take(20)->get();

        return view('items', compact(
            'items',
            'categories',
            'suppliers',
            'users',
            'departments',
            'search',
            'logs'
        ));
    }

    public function create()
    {
        return view('items-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_no' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'part_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = Item::create([
            'part_no' => $request->part_no,
            'brand' => $request->brand,
            'part_name' => $request->part_name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'status' => 'available',
        ]);

        AssetLog::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'new_values' => json_encode($item->toArray()),
        ]);

        return back()->with('success', 'Asset created successfully');
    }

    // =========================================
    // ✅ ASSIGN (FINAL PRODUCTION VERSION)
    // =========================================
    // =========================================
// ✅ ASSIGN (FINAL - ERD CORRECT)
// =========================================
public function assign(Request $request, $id)
{
    $item = Item::findOrFail($id);

    // ✅ VALIDATION (UPDATED)
    $request->validate([
        'department_id' => 'required|exists:departments,id',
        'user_id' => 'nullable|exists:users,id',
        'quantity' => 'required|integer|min:1',
    ]);

    // ✅ CHECK AVAILABLE STOCK
    $available = $item->availableQuantity();

    if ($available <= 0) {
        return back()->with('error', 'Item is out of stock');
    }

    if ($request->quantity > $available) {
        return back()->with('error', 'Not enough stock available');
    }

    // ✅ CREATE ASSIGNMENT
    $assignment = Assignment::create([
        'item_id' => $item->id,
        'department_id' => $request->department_id,
        'user_id' => $request->user_id, // optional
        'quantity' => $request->quantity,
        'assigned_at' => now(),
    ]);

    // ✅ LOG
    AssetLog::create([
        'item_id' => $item->id,
        'user_id' => Auth::id(),
        'action' => 'assigned',
        'new_values' => json_encode([
            'assignment_id' => $assignment->id,
            'department_id' => $request->department_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
        ]),
    ]);

    return back()->with('success', 'Assigned successfully');
}

    // =========================================
    // ✅ RETURN ASSET (SAFE)
    // =========================================
    public function returnAsset($id)
    {
        $assignment = Assignment::findOrFail($id);

        if ($assignment->returned_at) {
            return back()->with('error', 'Already returned');
        }

        $assignment->update([
            'returned_at' => now()
        ]);

        AssetLog::create([
            'item_id' => $assignment->item_id,
            'user_id' => Auth::id(),
            'action' => 'returned',
            'old_values' => json_encode([
                'assignment_id' => $assignment->id,
                'quantity' => $assignment->quantity
            ]),
        ]);

        return back()->with('success', 'Returned successfully');
    }

    // DO NOT USE FOR ASSIGNMENT
    public function update(Request $request, $id)
    {
        return back();
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        AssetLog::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'old_values' => json_encode($item->toArray()),
        ]);

        $item->delete();

        return back()->with('success', 'Asset deleted successfully');
    }

    // =========================================
    // CSV EXPORT (FINAL)
    // =========================================
    public function export()
    {
        $items = Item::with(['assignments.user','assignments.department'])->get();

        $filename = "assets.csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate",
            "Expires" => "0"
        ];

        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Code',
                'Brand',
                'Name',
                'Assigned',
                'Available Qty'
            ]);

            foreach ($items as $item) {

                $assigned = $item->assignments
                    ->whereNull('returned_at')
                    ->map(function ($a) {
                        return (optional($a->user)->name ?? optional($a->department)->name)
                            . " ({$a->quantity})";
                    })->implode(', ');

                fputcsv($file, [
                    $item->part_no,
                    $item->brand,
                    $item->part_name,
                    $assigned ?: '-',
                    $item->availableQuantity()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}