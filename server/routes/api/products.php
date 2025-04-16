<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('products')->group(function () {
    Route::get('/trashed', [ProductController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [ProductController::class, 'restore']);
    Route::delete('/{id}/force-delete', [ProductController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [ProductController::class, 'handleFormActions']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
    Route::get('/by-slug/{slug}', [ProductController::class, 'showBySlug']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::get('/', [ProductController::class, 'index'])->middleware(SortMiddleware::class);
});