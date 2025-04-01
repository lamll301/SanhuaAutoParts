<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('units')->group(function () {
    Route::get('/trashed', [UnitController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [UnitController::class, 'restore']);
    Route::delete('/{id}/force-delete', [UnitController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [UnitController::class, 'handleFormActions']);
    Route::post('/', [UnitController::class, 'store']);
    Route::put('/{id}', [UnitController::class, 'update']);
    Route::delete('/{id}', [UnitController::class, 'destroy']);
    Route::get('/{id}', [UnitController::class, 'show']);
    Route::get('/', [UnitController::class, 'index'])->middleware(SortMiddleware::class);
});