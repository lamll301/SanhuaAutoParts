<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('categories')->group(function () {
    Route::get('/by-slug/{slug}', [CategoryController::class, 'getBySlug']);
    Route::get('/trashed', [CategoryController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [CategoryController::class, 'restore']);
    Route::delete('/{id}/force-delete', [CategoryController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [CategoryController::class, 'handleFormActions']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::get('/', [CategoryController::class, 'index'])->middleware(SortMiddleware::class);
});