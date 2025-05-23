<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Middleware\SortMiddleware;
use App\Http\Middleware\AuthenticateWithJWT;

Route::prefix('articles')->group(function () {
    Route::patch('/{id}/approve', [ArticleController::class, 'approve'])->middleware(AuthenticateWithJWT::class);
    Route::get('/by-slug/{slug}', [ArticleController::class, 'getBySlug']);
    Route::get('/by-category/{categorySlug}', [ArticleController::class, 'getByCategory']);
    Route::get('/published', [ArticleController::class, 'getPublished'])->middleware(SortMiddleware::class);
    Route::get('/trashed', [ArticleController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [ArticleController::class, 'restore']);
    Route::delete('/{id}/force-delete', [ArticleController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [ArticleController::class, 'handleFormActions']);
    Route::post('/', [ArticleController::class, 'store'])->middleware(AuthenticateWithJWT::class);
    Route::put('/{id}', [ArticleController::class, 'update']);
    Route::delete('/{id}', [ArticleController::class, 'destroy']);
    Route::get('/{id}', [ArticleController::class, 'show']);
    Route::get('/', [ArticleController::class, 'index'])->middleware(SortMiddleware::class);
});