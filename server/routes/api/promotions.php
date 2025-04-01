<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PromotionController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('promotions')->group(function () {
    Route::get('/trashed', [PromotionController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [PromotionController::class, 'restore']);
    Route::delete('/{id}/force-delete', [PromotionController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [PromotionController::class, 'handleFormActions']);
    Route::post('/', [PromotionController::class, 'store']);
    Route::put('/{id}', [PromotionController::class, 'update']);
    Route::delete('/{id}', [PromotionController::class, 'destroy']);
    Route::get('/{id}', [PromotionController::class, 'show']);
    Route::get('/', [PromotionController::class, 'index'])->middleware(SortMiddleware::class);
});