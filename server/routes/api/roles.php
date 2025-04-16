<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Middleware\SortMiddleware;

Route::prefix('roles')->group(function () {
    Route::get('/trashed', [RoleController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [RoleController::class, 'restore']);
    Route::delete('/{id}/force-delete', [RoleController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [RoleController::class, 'handleFormActions']);
    Route::post('/', [RoleController::class, 'store']);
    Route::put('/{id}', [RoleController::class, 'update']);
    Route::delete('/{id}', [RoleController::class, 'destroy']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::get('/', [RoleController::class, 'index'])->middleware(SortMiddleware::class);
});