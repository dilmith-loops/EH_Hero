<?php

use App\Http\Controllers\Api\AppUserController;
use App\Http\Controllers\Api\GenerationController;
use Illuminate\Support\Facades\Route;

Route::post('/users', [AppUserController::class, 'store']);
Route::get('/users/{id}', [AppUserController::class, 'show']);

Route::post('/generations', [GenerationController::class, 'store']);
Route::get('/generations/{id}', [GenerationController::class, 'show']);
