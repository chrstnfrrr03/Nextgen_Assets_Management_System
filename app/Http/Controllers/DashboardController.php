<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected function getIntSetting(string $key, int $default): int
    {
        $value = DB::table('settings')->where('key', $key)->value('value');

        if (!is_numeric($value)) {
            return $default;
        }

        return (int) $value;
    }

    public function index()
    {
        $lowStockThreshold = $this->getIntSetting('low_stock_threshold', 5);
        $overdueDays = $this->getIntSetting('assignment_overdue_days', 7);

        return response()->json([
            'total_assets' => Item::count(),
            'available' => Item::where('status', 'available')->count(),
            'assigned' => Assignment::whereNull('returned_at')->count(),
            'maintenance' => Item::where('status', 'maintenance')->count(),
            'low_stock' => Item::where('quantity', '<', $lowStockThreshold)->count(),
            'overdue' => Assignment::whereNotNull('assigned_at')
                ->whereNull('returned_at')
                ->where('assigned_at', '<', now()->subDays($overdueDays))
                ->count(),
            'notifications_count' => 0,
            'recent_assignments' => Assignment::with(['item', 'user', 'assignedDepartment'])
                ->latest('assigned_at')
                ->limit(5)
                ->get(),
            'recent_items' => Item::with(['category', 'supplier', 'department'])
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
