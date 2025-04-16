<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('imports')->group(function () {
    Route::get('/trashed', [ImportController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [ImportController::class, 'restore']);
    Route::delete('/{id}/force-delete', [ImportController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [ImportController::class, 'handleFormActions']);
    Route::post('/', [ImportController::class, 'store']);
    Route::put('/{id}', [ImportController::class, 'update']);
    Route::delete('/{id}', [ImportController::class, 'destroy']);
    Route::get('/{id}', [ImportController::class, 'show']);
    Route::get('/', [ImportController::class, 'index'])->middleware(SortMiddleware::class);
});