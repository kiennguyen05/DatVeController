<?php

use App\Http\Controllers\DatVeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * @OA\PathItem(path="/api")
 */


 Route::middleware(['auth', 'admin'])->group(function () {
    // Route cho admin
    Route::get('/admin', function () {
        return view('admin.dashboard');
    });
});

Route::prefix('/admin/datve')->group(function () {
    Route::get('/', [DatVeController::class, 'index']);
    Route::put('/approve/{ma_ve}', [DatVeController::class, 'approve']);
    Route::put('/cancel/{ma_ve}', [DatVeController::class, 'cancel']);
    Route::delete('/{ma_ve}', [DatVeController::class, 'destroy']);
});

