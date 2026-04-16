<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    protected function ensureDefaults(): void
    {
        $defaults = [
            'system_name' => 'NextGen Assets',
            'system_tagline' => 'Management System',
            'company_name' => 'Your Company',
            'support_email' => 'support@company.com',
            'low_stock_threshold' => '5',
            'assignment_overdue_days' => '7',
            'items_per_page' => '10',
            'email_notifications_enabled' => '1',
            'maintenance_alerts_enabled' => '1',
            'allow_user_impersonation' => '1',
        ];

        foreach ($defaults as $key => $value) {
            $exists = DB::table('settings')->where('key', $key)->exists();

            if (! $exists) {
                DB::table('settings')->insert([
                    'key' => $key,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function index()
    {
        $this->ensureDefaults();

        return response()->json(
            DB::table('settings')
                ->orderBy('key')
                ->get()
        );
    }

    public function update(Request $request, string $key)
    {
        $validated = $request->validate([
            'value' => ['nullable', 'string'],
        ]);

        $exists = DB::table('settings')->where('key', $key)->exists();

        if ($exists) {
            DB::table('settings')
                ->where('key', $key)
                ->update([
                    'value' => $validated['value'] ?? null,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $validated['value'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Setting updated']);
    }
}
