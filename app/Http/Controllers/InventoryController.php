<?php

namespace App\Http\Controllers;

use App\Models\AssetLog;
use App\Models\Assignment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category', 'supplier', 'department'])
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
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

        $items = $query->paginate(10)->withQueryString();

        return view('inventory.index', compact('items'));
    }

    public function stockOut(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->decrement('quantity', $validated['quantity']);
        $item->refresh();

        if ($item->quantity <= 0) {
            $item->update([
                'status' => 'maintenance',
                'quantity' => 0,
            ]);
            $item->refresh();
        }

        $authUser = Auth::user();

        AssetLog::create([
            'item_id' => $item->id,
            'user_id' => $authUser?->id ?? 1,
            'action' => 'stock_out',
            'notes' => 'Stock decreased by ' . $validated['quantity'] . '. New qty: ' . $item->quantity,
        ]);

        return back()->with('success', 'Stock out recorded successfully.');
    }

    public function stockIn(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->increment('quantity', $validated['quantity']);
        $item->refresh();

        if ($item->status === 'maintenance' && $item->quantity > 0) {
            $hasActiveAssignment = Assignment::where('item_id', $item->id)
                ->whereNull('returned_at')
                ->exists();

            if (! $hasActiveAssignment) {
                $item->update(['status' => 'available']);
                $item->refresh();
            }
        }

        $authUser = Auth::user();

        AssetLog::create([
            'item_id' => $item->id,
            'user_id' => $authUser?->id ?? 1,
            'action' => 'stock_in',
            'notes' => 'Stock increased by ' . $validated['quantity'] . '. New qty: ' . $item->quantity,
        ]);

        return back()->with('success', 'Stock in recorded successfully.');
    }
}