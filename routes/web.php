<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController; // ✅ correct controller

Route::get('/', function () {
    return redirect('/dashboard');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ASSETS
    Route::get('/items', [InventoryController::class, 'index'])->name('items');
    Route::post('/items', [InventoryController::class, 'store'])->name('items.store');
    Route::put('/items/{id}', [InventoryController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [InventoryController::class, 'destroy'])->name('items.destroy');

    // USERS (THIS FIXES YOUR ERROR)
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // SETTINGS
    // SETTINGS
Route::get('/settings', function () {
    return view('settings');
})->name('settings');

Route::post('/settings', function () {
    return back()->with('success', 'Settings saved successfully!');
});
    //Route::view('/settings', 'settings')->name('settings');

    // REPORTS
    Route::view('/reports', 'reports')->name('reports');

});