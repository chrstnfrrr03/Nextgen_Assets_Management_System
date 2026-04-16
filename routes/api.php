<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::post('/login', [AuthController::class, 'apiLogin']);
    Route::post('/logout', [AuthController::class, 'apiLogout']);

    Route::middleware('auth')->group(function () {
        Route::get('/me', [ProfileController::class, 'apiMe']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto']);

        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::get('/items', [ItemController::class, 'index']);
        Route::post('/items', [ItemController::class, 'store']);
        Route::get('/items/{item}', [ItemController::class, 'show']);
        Route::put('/items/{item}', [ItemController::class, 'update']);
        Route::delete('/items/{item}', [ItemController::class, 'destroy']);

        Route::get('/assignments', [AssignmentController::class, 'index']);
        Route::post('/assignments', [AssignmentController::class, 'store']);
        Route::put('/assignments/{assignment}/return', [AssignmentController::class, 'returnItem']);

        Route::get('/inventory', [InventoryController::class, 'index']);
        Route::post('/inventory/{item}/stock-in', [InventoryController::class, 'stockIn']);
        Route::post('/inventory/{item}/stock-out', [InventoryController::class, 'stockOut']);

        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('departments', DepartmentController::class);
        Route::apiResource('suppliers', SupplierController::class);

        Route::post('/users/stop-impersonation', [UserController::class, 'stopImpersonation']);
        Route::post('/users/{user}/impersonate', [UserController::class, 'impersonate']);
        Route::apiResource('users', UserController::class);

        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::put('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
        Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllRead']);

        Route::get('/settings', [SettingController::class, 'index']);
        Route::put('/settings/{key}', [SettingController::class, 'update']);

        
    });
});
