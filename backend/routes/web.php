<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GenerationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');

// Protected Admin Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/export', [UserController::class, 'exportCsv'])->name('users.export');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Generations Management
    Route::get('/generations', [GenerationController::class, 'index'])->name('generations.index');
    Route::get('/generations/{id}/download', [GenerationController::class, 'download'])->name('generations.download');
    Route::delete('/generations/{id}', [GenerationController::class, 'destroy'])->name('generations.destroy');

    // Settings Management
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Error & Maintenance Page Previews
    Route::get('/preview/404', function () {
        return response()->view('errors.404', [], 404);
    })->name('preview.404');
    Route::get('/preview/503', function () {
        return response()->view('errors.503', ['exception' => new \Exception('Scheduled maintenance is in progress for the Wonder Hero AI engine.')], 503);
    })->name('preview.503');
});

// Public preview routes
Route::get('/preview/404', function () {
    return response()->view('errors.404', [], 404);
});
Route::get('/preview/503', function () {
    return response()->view('errors.503', ['exception' => new \Exception('Our team is fine-tuning the Wonder Anime AI servers to serve up even cooler transformations. We will be back online shortly!')], 503);
});

