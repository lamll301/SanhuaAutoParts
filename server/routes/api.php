<?php

use Illuminate\Support\Facades\Route;

Route::group([], base_path('routes/api/permissions.php'));
Route::group([], base_path('routes/api/roles.php'));
Route::group([], base_path('routes/api/users.php'));
Route::group([], base_path('routes/api/units.php'));
Route::group([], base_path('routes/api/suppliers.php'));
Route::group([], base_path('routes/api/vouchers.php'));
Route::group([], base_path('routes/api/promotions.php'));
Route::group([], base_path('routes/api/articles.php'));
Route::group([], base_path('routes/api/categories.php'));



// Route::prefix('permissions')->group(function () {
//     Route::get('/trashed', [PermissionController::class, 'trashed'])->middleware(SortMiddleware::class);
//     Route::patch('/{id}/restore', [PermissionController::class, 'restore']);
//     Route::delete('/{id}/force-delete', [PermissionController::class, 'forceDelete']);
//     Route::post('/handle-form-actions', [PermissionController::class, 'handleFormActions']);
//     Route::post('/', [PermissionController::class, 'store']);
//     Route::put('/{id}', [PermissionController::class, 'update']);
//     Route::delete('/{id}', [PermissionController::class, 'destroy']);
//     Route::get('/{id}', [PermissionController::class, 'show']);
//     Route::get('/', [PermissionController::class, 'index'])->middleware(SortMiddleware::class);
// });

// Route::prefix('roles')->group(function () {
//     Route::delete('/{id}/permission/{permissionId}', [RoleController::class, 'removePermission']);
//     Route::post('/{id}/permission', [RoleController::class, 'addPermission']);
//     Route::get('/trashed', [RoleController::class, 'trashed'])->middleware(SortMiddleware::class);
//     Route::patch('/{id}/restore', [RoleController::class, 'restore']);
//     Route::delete('/{id}/force-delete', [RoleController::class, 'forceDelete']);
//     Route::post('/handle-form-actions', [RoleController::class, 'handleFormActions']);
//     Route::post('/', [RoleController::class, 'store']);
//     Route::put('/{id}', [RoleController::class, 'update']);
//     Route::delete('/{id}', [RoleController::class, 'destroy']);
//     Route::get('/{id}', [RoleController::class, 'show']);
//     Route::get('/', [RoleController::class, 'index'])->middleware(SortMiddleware::class);
// });

// Route::prefix('users')->group(function () {
//     Route::get('/trashed', [UserController::class, 'trashed'])->middleware(SortMiddleware::class);
//     Route::patch('/{id}/restore', [UserController::class, 'restore']);
//     Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete']);
//     Route::post('/handle-form-actions', [UserController::class, 'handleFormActions']);
//     Route::post('/', [UserController::class, 'store']);
//     Route::put('/{id}', [UserController::class, 'update']);
//     Route::delete('/{id}', [UserController::class, 'destroy']);
//     Route::get('/{id}', [UserController::class, 'show']);
//     Route::get('/', [UserController::class, 'index'])->middleware(SortMiddleware::class);
// });
