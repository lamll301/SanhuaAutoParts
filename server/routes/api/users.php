<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\SortMiddleware;
use App\Http\Middleware\AuthenticateWithJWT;

Route::prefix('users')->group(function () {
    Route::middleware([AuthenticateWithJWT::class])->group(function () {
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::patch('/password/reset', [UserController::class, 'resetPassword']);
    });
    Route::get('/trashed', [UserController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [UserController::class, 'restore']);
    Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [UserController::class, 'handleFormActions']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/', [UserController::class, 'index'])->middleware(SortMiddleware::class);
});

