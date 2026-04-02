<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DepartmentController;

Route::get('/', fn () => redirect()->route('dashboard'));

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

    // =============================
    // DASHBOARD
    // =============================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =============================
    // ASSETS
    // =============================
    Route::get('/assets', [ItemController::class, 'index'])->name('assets');
    Route::get('/products', [ItemController::class, 'index'])->name('products');

    Route::get('/assets/create', [ItemController::class, 'create'])->name('assets.create');

    Route::post('/assets', [ItemController::class, 'store'])->name('assets.store');
    Route::put('/assets/{id}', [ItemController::class, 'update'])->name('assets.update');
    Route::delete('/assets/{id}', [ItemController::class, 'destroy'])->name('assets.destroy');

    //  CSV EXPORT (KEEP SIMPLE FOR NOW)
    Route::get('/assets/export', [ItemController::class, 'export'])->name('assets.export');

    // =============================
    // CATEGORIES
    // =============================
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // =============================
    // SUPPLIERS
    // =============================
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // =============================
    // USERS
    // =============================
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // =============================
    // SETTINGS
    // =============================
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');

    // ASSIGN (NEW SYSTEM)
Route::post('/assets/{id}/assign', [ItemController::class, 'assign'])->name('assets.assign');

// RETURN (FIX ERROR HERE)
Route::post('/assignments/{id}/return', [ItemController::class, 'returnAsset'])->name('assets.return');

// Department Routes
Route::get('/departments', [DepartmentController::class, 'index']) ->name('departments');
Route::post('/departments', [DepartmentController::class, 'store']) ->name('departments.store');
Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']) ->name('departments.destroy');


});