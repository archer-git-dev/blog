<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\VersionController;
use App\Http\Middleware\CheckRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes - только логин
Route::post('/login', [ApiAuthController::class, 'login']);

// Protected routes (require token) - ВСЕ остальные маршруты
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // Версии теперь защищены
    Route::get('/versions', [VersionController::class, 'index']);
    Route::get('/versions/{version_id}', [VersionController::class, 'show']);
});


Route::middleware(['auth:sanctum', 'check.role'])->prefix('versions')->name('version')->group(function () {
    // Версии теперь защищены
    Route::get('/', [VersionController::class, 'index']);
    Route::get('/{version_id}', [VersionController::class, 'show']);
});
