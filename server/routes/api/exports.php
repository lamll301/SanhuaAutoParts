<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('exports')->group(function () {
    Route::get('/trashed', [ExportController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [ExportController::class, 'restore']);
    Route::delete('/{id}/force-delete', [ExportController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [ExportController::class, 'handleFormActions']);
    Route::post('/', [ExportController::class, 'store']);
    Route::put('/{id}', [ExportController::class, 'update']);
    Route::delete('/{id}', [ExportController::class, 'destroy']);
    Route::get('/{id}', [ExportController::class, 'show']);
    Route::get('/', [ExportController::class, 'index'])->middleware(SortMiddleware::class);
});