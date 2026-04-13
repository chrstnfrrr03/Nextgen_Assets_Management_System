<?php

namespace App\Http\Controllers;

use App\Models\AssetLog;
use App\Models\Item;
use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category', 'supplier', 'department'])
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($sub) => $sub->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('supplier', fn ($sub) => $sub->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('department', fn ($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }

        $items = $query->paginate(10)->withQueryString();

        return view('inventory.index', compact('items'));
    }

    public function stockIn(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item->increment('quantity', $validated['quantity']);
        $item->refresh();

        $item->syncAutomatedStatus();

        AssetLog::log(
            $item->id,
            AssetLog::ACTION_STOCK_IN,
            "Stock in: +{$validated['quantity']}"
        );

        if ($item->isLowStock()) {
            SystemNotification::create([
                'user_id' => Auth::id(),
                'type' => 'warning',
                'title' => 'Low Stock Alert',
                'message' => "{$item->name} is running low (Qty: {$item->quantity})",
                'url' => route('inventory.index'),
            ]);
        }

        return back()->with('success', 'Stock added successfully.');
    }

    public function stockOut(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['quantity'] > $item->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $item->decrement('quantity', $validated['quantity']);
        $item->refresh();

        $item->syncAutomatedStatus();

        AssetLog::log(
            $item->id,
            AssetLog::ACTION_STOCK_OUT,
            "Stock out: -{$validated['quantity']}"
        );

        if ($item->isLowStock()) {
            SystemNotification::create([
                'user_id' => Auth::id(),
                'type' => 'warning',
                'title' => 'Low Stock Alert',
                'message' => "{$item->name} is running low (Qty: {$item->quantity})",
                'url' => route('inventory.index'),
            ]);
        }

        return back()->with('success', 'Stock removed successfully.');
    }
}