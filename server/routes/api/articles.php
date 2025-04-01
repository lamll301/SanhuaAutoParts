<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('articles')->group(function () {
    Route::get('/trashed', [ArticleController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [ArticleController::class, 'restore']);
    Route::delete('/{id}/force-delete', [ArticleController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [ArticleController::class, 'handleFormActions']);
    Route::post('/', [ArticleController::class, 'store']);
    Route::put('/{id}', [ArticleController::class, 'update']);
    Route::delete('/{id}', [ArticleController::class, 'destroy']);
    Route::get('/{id}', [ArticleController::class, 'show']);
    Route::get('/', [ArticleController::class, 'index'])->middleware(SortMiddleware::class);
});