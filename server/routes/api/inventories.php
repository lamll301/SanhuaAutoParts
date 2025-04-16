<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('inventories')->group(function () {
    Route::get('/trashed', [InventoryController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [InventoryController::class, 'restore']);
    Route::delete('/{id}/force-delete', [InventoryController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [InventoryController::class, 'handleFormActions']);
    Route::post('/', [InventoryController::class, 'store']);
    Route::put('/{id}', [InventoryController::class, 'update']);
    Route::delete('/{id}', [InventoryController::class, 'destroy']);
    Route::get('/{id}', [InventoryController::class, 'show']);
    Route::get('/', [InventoryController::class, 'index'])->middleware(SortMiddleware::class);
});