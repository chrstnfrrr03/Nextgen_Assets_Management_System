<?php

namespace App\Http\Controllers;

use App\Models\AssetLog;
use App\Models\Item;
use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    protected function getIntSetting(string $key, int $default): int
    {
        $value = DB::table('settings')->where('key', $key)->value('value');

        if (!is_numeric($value)) {
            return $default;
        }

        return (int) $value;
    }

    public function index(Request $request)
    {
        $lowStockThreshold = $this->getIntSetting('low_stock_threshold', 5);
        $perPage = max(5, min((int) $request->integer('per_page', 10), 50));

        $query = Item::with(['category', 'supplier', 'department'])
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhereHas('category', fn($sub) => $sub->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('supplier', fn($sub) => $sub->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('department', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('stock')) {
            if ($request->stock === 'out') {
                $query->where('quantity', 0);
            } elseif ($request->stock === 'low') {
                $query->where('quantity', '>', 0)->where('quantity', '<', $lowStockThreshold);
            } elseif ($request->stock === 'available') {
                $query->where('quantity', '>', 0);
            }
        }

        $items = $query->paginate($perPage)->withQueryString();

        return response()->json(array_merge(
            $items->toArray(),
            [
                'summary' => [
                    'totalItems' => Item::count(),
                    'lowStockCount' => Item::where('quantity', '>', 0)->where('quantity', '<', $lowStockThreshold)->count(),
                    'outOfStockCount' => Item::where('quantity', 0)->count(),
                ],
            ]
        ));
    }

    public function stockIn(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item->increment('quantity', $validated['quantity']);
        $item->refresh();

        if (method_exists($item, 'syncAutomatedStatus')) {
            $item->syncAutomatedStatus();
        }

        AssetLog::log(
            $item->id,
            AssetLog::ACTION_STOCK_IN,
            "Stock in: +{$validated['quantity']}"
        );

        return response()->json([
            'message' => 'Stock added successfully',
            'item' => $item->load(['category', 'supplier', 'department']),
        ]);
    }

    public function stockOut(Request $request, Item $item)
    {
        $lowStockThreshold = $this->getIntSetting('low_stock_threshold', 5);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['quantity'] > $item->quantity) {
            return response()->json(['message' => 'Not enough stock available.'], 422);
        }

        $item->decrement('quantity', $validated['quantity']);
        $item->refresh();

        if (method_exists($item, 'syncAutomatedStatus')) {
            $item->syncAutomatedStatus();
        }

        AssetLog::log(
            $item->id,
            AssetLog::ACTION_STOCK_OUT,
            "Stock out: -{$validated['quantity']}"
        );

        if ((int) $item->quantity < $lowStockThreshold) {
            SystemNotification::create([
                'user_id' => Auth::id(),
                'type' => 'warning',
                'title' => 'Low Stock Alert',
                'message' => "{$item->name} is running low (Qty: {$item->quantity})",
                'url' => '/inventory',
            ]);
        }

        return response()->json([
            'message' => 'Stock removed successfully',
            'item' => $item->load(['category', 'supplier', 'department']),
        ]);
    }
}
