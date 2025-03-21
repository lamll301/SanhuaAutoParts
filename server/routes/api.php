<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SortMiddleware;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('permissions')->group(function () {
    Route::get('/trashed', [PermissionController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::patch('/{id}/restore', [PermissionController::class, 'restore']);
    Route::delete('/{id}/force-delete', [PermissionController::class, 'forceDelete']);
    Route::post('/handle-form-actions', [PermissionController::class, 'handleFormActions']);
    Route::post('/', [PermissionController::class, 'store']);
    Route::put('/{id}', [PermissionController::class, 'update']);
    Route::delete('/{id}', [PermissionController::class, 'destroy']);
    Route::get('/{id}', [PermissionController::class, 'show']);
    Route::get('/', [PermissionController::class, 'index'])->middleware(SortMiddleware::class);
});

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