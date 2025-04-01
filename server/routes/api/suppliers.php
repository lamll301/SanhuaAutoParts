<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SortMiddleware;
use App\Http\Controllers\SupplierController;

Route::prefix('suppliers')->group(function () {
    Route::get('/trashed', [SupplierController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [SupplierController::class, 'restore']);
    Route::delete('/{id}/force-delete', [SupplierController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [SupplierController::class, 'handleFormActions']);
    Route::post('/', [SupplierController::class, 'store']);
    Route::put('/{id}', [SupplierController::class, 'update']);
    Route::delete('/{id}', [SupplierController::class, 'destroy']);
    Route::get('/{id}', [SupplierController::class, 'show']);
    Route::get('/', [SupplierController::class, 'index'])->middleware(SortMiddleware::class);
});