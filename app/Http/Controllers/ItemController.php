<?php

namespace App\Http\Controllers;

use App\Models\AssetLog;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(5, min((int) $request->integer('per_page', 10), 50));

        $query = Item::with(['category', 'supplier', 'department', 'activeAssignment.user'])
            ->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
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

        return response()->json($query->paginate($perPage)->withQueryString());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:items,sku'],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'asset_tag' => ['nullable', 'string', 'max:255', 'unique:items,asset_tag'],
            'serial_number' => ['nullable', 'string', 'max:255', 'unique:items,serial_number'],
            'quantity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:available,assigned,maintenance,lost,retired'],
            'location' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
        ]);

        $item = Item::create($validated);

        AssetLog::log(
            $item->id,
            AssetLog::ACTION_CREATED,
            $item->name . ' created by ' . (Auth::user()?->name ?? 'System')
        );

        return response()->json($item->load(['category', 'supplier', 'department']), 201);
    }

    public function show(Item $item)
    {
        $item->load([
            'category',
            'supplier',
            'department',
            'activeAssignment.user',
            'activeAssignment.assignedDepartment',
        ]);

        return response()->json($item);
    }

    public function update(Request $request, Item $item)
    {
        $oldStatus = $item->status;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:items,sku,' . $item->id],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'asset_tag' => ['nullable', 'string', 'max:255', 'unique:items,asset_tag,' . $item->id],
            'serial_number' => ['nullable', 'string', 'max:255', 'unique:items,serial_number,' . $item->id],
            'quantity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:available,assigned,maintenance,lost,retired'],
            'location' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
        ]);

        $item->update($validated);

        AssetLog::log(
            $item->id,
            AssetLog::ACTION_UPDATED,
            'Status changed from ' . $oldStatus . ' to ' . $item->status . ' by ' . (Auth::user()?->name ?? 'System')
        );

        return response()->json($item->fresh(['category', 'supplier', 'department']));
    }

    public function destroy(Item $item)
    {
        AssetLog::log(
            $item->id,
            AssetLog::ACTION_DELETED,
            $item->name . ' permanently deleted by ' . (Auth::user()?->name ?? 'System')
        );

        $item->delete();

        return response()->json(['message' => 'Item deleted successfully']);
    }
}
