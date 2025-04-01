<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('permissions')->group(function () {
    Route::get('/trashed', [PermissionController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [PermissionController::class, 'restore']);
    Route::delete('/{id}/force-delete', [PermissionController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [PermissionController::class, 'handleFormActions']);
    Route::post('/', [PermissionController::class, 'store']);
    Route::put('/{id}', [PermissionController::class, 'update']);
    Route::delete('/{id}', [PermissionController::class, 'destroy']);
    Route::get('/{id}', [PermissionController::class, 'show']);
    Route::get('/', [PermissionController::class, 'index'])->middleware(SortMiddleware::class);
});