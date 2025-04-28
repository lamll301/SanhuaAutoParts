<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('locations')->group(function () {
    Route::get('/trashed', [LocationController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [LocationController::class, 'restore']);
    Route::delete('/{id}/force-delete', [LocationController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [LocationController::class, 'handleFormActions']);
    Route::post('/', [LocationController::class, 'store']);
    Route::put('/{id}', [LocationController::class, 'update']);
    Route::delete('/{id}', [LocationController::class, 'destroy']);
    Route::get('/{id}', [LocationController::class, 'show']);
    Route::get('/', [LocationController::class, 'index'])->middleware(SortMiddleware::class);
});