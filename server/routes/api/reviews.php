<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('reviews')->group(function () {
    Route::post('/', [ReviewController::class, 'store']);
    Route::put('/{id}', [ReviewController::class, 'update']);
    Route::delete('/{id}', [ReviewController::class, 'destroy']);
    Route::get('/product/{slug}', [ReviewController::class, 'getByProductSlug'])->middleware(SortMiddleware::class);
});