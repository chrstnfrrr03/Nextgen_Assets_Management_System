<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::get('/', function () {
    return redirect('/dashboard');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

    // 🔥 THIS LINE IS THE MOST IMPORTANT
    Route::get('/dashboard', [ItemController::class, 'index'])->name('dashboard');
    Route::put('/items/{id}', [ItemController::class, 'update']);
    Route::post('/items', [ItemController::class, 'store']);
    Route::delete('/items/{id}', [ItemController::class, 'destroy']);
});
