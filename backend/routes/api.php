<?php

use App\Http\Controllers\Api\AppUserController;
use App\Http\Controllers\Api\GenerationController;
use Illuminate\Support\Facades\Route;

Route::get('/system-status', function () {
    $maintenance = \App\Models\Setting::isMaintenanceEnabled();
    return response()->json([
        'maintenance' => $maintenance,
        'message' => $maintenance ? \App\Models\Setting::getMaintenanceMessage() : null,
    ], $maintenance ? 503 : 200);
});

Route::post('/users', [AppUserController::class, 'store']);
Route::get('/users/{id}', [AppUserController::class, 'show']);

Route::get('/generations/limit-status', [GenerationController::class, 'checkLimit']);
Route::post('/generations', [GenerationController::class, 'store']);
Route::get('/generations/{id}', [GenerationController::class, 'show']);

