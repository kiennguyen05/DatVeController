<?php

use App\Http\Controllers\CustomerTicketController;
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

Route::prefix('/admin')->group(function () {
    Route::get('/datve', [DatVeController::class, 'index']);
    Route::get('/datve/{ma_ve}', [DatVeController::class, 'show']);
    Route::put('/cancel/{ma_ve}', [DatVeController::class, 'cancel']);
    Route::delete('/datve/{ma_ve}', [DatVeController::class, 'destroy']);
    Route::get('/user/tickets', [DatVeController::class, 'getUserTickets']);
});

Route::prefix('customer')->group(function () {
    Route::get('/tickets', [CustomerTicketController::class, 'getUserTickets']);
    Route::put('/cancel/{ma_ve}', [CustomerTicketController::class, 'cancel']);
});

