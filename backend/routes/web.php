<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GenerationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (\App\Models\Setting::isMaintenanceEnabled() && !auth()->check()) {
        return response()->view('errors.503', [
            'exception' => new \Exception(\App\Models\Setting::getMaintenanceMessage())
        ], 503);
    }
    return redirect()->route('admin.settings.index');
});

// Primary IT Admin Authentication Routes
Route::get('/EH-Hero/EH-PORTAL-IT-ADMIN/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/EH-Hero/EH-PORTAL-IT-ADMIN/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/EH-Hero/EH-PORTAL-IT-ADMIN/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Aliases for login
Route::get('/EH-PORTAL-IT-ADMIN/login', fn () => redirect()->route('admin.login'));
Route::get('/login', fn () => redirect()->route('admin.login'));

// Protected IT Admin Routes under prefix: EH-Hero/EH-PORTAL-IT-ADMIN
Route::middleware('auth')->prefix('EH-Hero/EH-PORTAL-IT-ADMIN')->name('admin.')->group(function () {
    // Primary Admin Settings & Maintenance route (directly on /EH-Hero/EH-PORTAL-IT-ADMIN)
    Route::get('/', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/toggle-maintenance', [SettingController::class, 'toggleMaintenance'])->name('settings.toggle-maintenance');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/export', [UserController::class, 'exportCsv'])->name('users.export');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Generations Management
    Route::get('/generations', [GenerationController::class, 'index'])->name('generations.index');
    Route::get('/generations/{id}/download', [GenerationController::class, 'download'])->name('generations.download');
    Route::delete('/generations/{id}', [GenerationController::class, 'destroy'])->name('generations.destroy');

    // Admin Accounts Management
    Route::get('/admins', [AdminUserController::class, 'index'])->name('admins.index');
    Route::get('/admins/create', [AdminUserController::class, 'create'])->name('admins.create');
    Route::post('/admins', [AdminUserController::class, 'store'])->name('admins.store');
    Route::get('/admins/{admin}/edit', [AdminUserController::class, 'edit'])->name('admins.edit');
    Route::put('/admins/{admin}', [AdminUserController::class, 'update'])->name('admins.update');
    Route::delete('/admins/{admin}', [AdminUserController::class, 'destroy'])->name('admins.destroy');

    // Error & Maintenance Page Previews
    Route::get('/preview/404', function () {
        return response()->view('errors.404', [], 404);
    })->name('preview.404');
    Route::get('/preview/503', function () {
        return response()->view('errors.503', ['exception' => new \Exception('Scheduled maintenance is in progress for the Wonder Hero AI engine.')], 503);
    })->name('preview.503');
});

// Aliases without /EH-Hero prefix
Route::get('/EH-PORTAL-IT-ADMIN', fn () => redirect()->route('admin.settings.index'));
Route::get('/EH-PORTAL-IT-ADMIN/{any}', function ($any) {
    return redirect('/EH-Hero/EH-PORTAL-IT-ADMIN/' . $any);
})->where('any', '.*');

// API routes mapped under /EH-Hero/api prefix for reverse proxy / subfolder routing
Route::prefix('EH-Hero/api')->group(base_path('routes/api.php'));

// Legacy admin routes return 404 to protect secret portal
Route::any('/admin{any}', function () {
    return response()->view('errors.404', [], 404);
})->where('any', '.*');

// Public preview routes
Route::get('/preview/404', function () {
    return response()->view('errors.404', [], 404);
});
Route::get('/preview/503', function () {
    return response()->view('errors.503', ['exception' => new \Exception('Our team is fine-tuning the Wonder Anime AI servers to serve up even cooler transformations. We will be back online shortly!')], 503);
});

// Fallback for all other undefined URLs
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
