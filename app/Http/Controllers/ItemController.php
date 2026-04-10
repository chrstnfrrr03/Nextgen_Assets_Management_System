<?php

namespace App\Http\Controllers;

use App\Models\AssetLog;
use App\Models\Category;
use App\Models\Department;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = Item::with(['category', 'supplier', 'department', 'activeAssignment.user'])
            ->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('department', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('activeAssignment.user', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $items = $query->paginate(10)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('items.index', [
            'items' => $items,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'departments' => $departments,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
                'category_id' => $request->category_id,
                'department_id' => $request->department_id,
            ],
        ]);
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('items.create', [
            'categories' => $categories,
            'suppliers' => $suppliers,
            'departments' => $departments,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rows.*.name' => 'required|string|max:255',
            'rows.*.category_id' => 'required|exists:categories,id',
            'rows.*.supplier_id' => 'required|exists:suppliers,id',
            'rows.*.department_id' => 'required|exists:departments,id',
            'rows.*.asset_tag' => ['nullable', 'string', 'max:255', 'distinct', 'unique:items,asset_tag'],
            'rows.*.serial_number' => ['nullable', 'string', 'max:255', 'distinct', 'unique:items,serial_number'],
            'rows.*.location' => 'nullable|string|max:255',
            'rows.*.purchase_date' => 'nullable|date',
        ]);

        $authUser = Auth::user();

        foreach ($validated['rows'] as $row) {
            $item = Item::create([
                ...$row,
                'quantity' => 1,
                'status' => 'available',
            ]);

            AssetLog::create([
                'item_id' => $item->id,
                'user_id' => $authUser?->id ?? 1,
                'action' => 'created',
                'notes' => $item->name . ' created with status ' . $item->status . ' by ' . ($authUser?->name ?? 'System'),
            ]);
        }

        return redirect()->route('items.index')->with('success', 'Assets created successfully.');
    }

    public function show(Item $item): View
    {
        $item->load(['category', 'supplier', 'department', 'activeAssignment.user', 'activeAssignment.assignedDepartment']);
        $logs = AssetLog::with('user')
            ->where('item_id', $item->id)
            ->latest()
            ->get();

        return view('items.show', compact('item', 'logs'));
    }

    public function edit(Item $item): View
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('items.edit', compact('item', 'categories', 'suppliers', 'departments'));
    }

    public function update(Request $request, Item $item)
    {
        $oldStatus = $item->status;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'department_id' => 'required|exists:departments,id',
            'location' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
        ]);

        $item->update($validated);

        $authUser = Auth::user();

        AssetLog::create([
            'item_id' => $item->id,
            'user_id' => $authUser?->id ?? 1,
            'action' => 'updated',
            'notes' => 'Status changed from ' . $oldStatus . ' to ' . $item->status . ' by ' . ($authUser?->name ?? 'System'),
        ]);

        return redirect()->route('items.index')->with('success', 'Asset updated.');
    }

    public function destroy(Item $item)
    {
        $authUser = Auth::user();

        AssetLog::create([
            'item_id' => $item->id,
            'user_id' => $authUser?->id ?? 1,
            'action' => 'deleted',
            'notes' => $item->name . ' permanently deleted by ' . ($authUser?->name ?? 'System'),
        ]);

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Asset deleted.');
    }
}