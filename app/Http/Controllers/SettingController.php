<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * =============================
     * SHOW SETTINGS PAGE
     * =============================
     */
    public function index()
    {
        // Get first settings row
        $settings = DB::table('settings')->first();

        return view('settings', compact('settings'));
    }

    /**
     * =============================
     * SAVE / UPDATE SETTINGS
     * =============================
     */
    public function store(Request $request)
    {
        //  VALIDATION
        $request->validate([
            'app_name' => 'required|string|max:255',
            'admin_email' => 'required|email',
        ]);

        //  CHECK IF SETTINGS EXIST
        $exists = DB::table('settings')->where('id', 1)->exists();

        if ($exists) {
            // UPDATE ONLY
            DB::table('settings')->where('id', 1)->update([
                'app_name' => $request->app_name,
                'admin_email' => $request->admin_email,
                'updated_at' => now(),
            ]);
        } else {
            // INSERT FIRST TIME
            DB::table('settings')->insert([
                'id' => 1,
                'app_name' => $request->app_name,
                'admin_email' => $request->admin_email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}