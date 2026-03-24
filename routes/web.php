<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return redirect('/dashboard');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

// =============================
// SUPPLIERS
// =============================
Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =============================
    // ASSETS
    // =============================
    Route::get('/items', [InventoryController::class, 'index'])->name('items');
    Route::post('/items', [InventoryController::class, 'store'])->name('items.store');
    Route::put('/items/{id}', [InventoryController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [InventoryController::class, 'destroy'])->name('items.destroy');

    
    // =============================
    // USERS (FIXED + CLEAN)
    // =============================
    Route::get('/users', [UserController::class, 'index'])->name('users');

    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit'); // ✅ FIXED

    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update'); // ✅ ADDED

    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // =============================
    // SETTINGS
    // =============================
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    Route::post('/settings', function () {
        return back()->with('success', 'Settings saved successfully!');
    });

    // =============================
    // REPORTS
    // =============================
    Route::view('/reports', 'reports')->name('reports');
    
    //Route::view('/reports', 'reports')->name('reports');
    

});