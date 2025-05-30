<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseReceiptController;
use App\Http\Middleware\SortMiddleware;
use App\Http\Middleware\AuthenticateWithJWT;
use App\Http\Middleware\Authorization;


Route::patch('/imports/{id}/approve', [WarehouseReceiptController::class, 'approveImport'])->middleware([
    AuthenticateWithJWT::class, Authorization::class . ':imports.approve'
]);

$types = ['imports', 'exports', 'disposals', 'checks', 'cancels'];
foreach ($types as $type) {
    Route::prefix($type)->group(function () {
        Route::patch('/{id}/restore', [WarehouseReceiptController::class, 'restore'])->middleware([
            AuthenticateWithJWT::class, Authorization::class . ':stock.receipts.manage'
        ]);
        Route::delete('/{id}/force-delete', [WarehouseReceiptController::class, 'forceDelete'])->middleware([
            AuthenticateWithJWT::class, Authorization::class . ':stock.receipts.manage'
        ]);
        Route::post('/handle-form-actions', [WarehouseReceiptController::class, 'handleFormActions'])->middleware([
            AuthenticateWithJWT::class, Authorization::class . ':stock.receipts.manage'
        ]);
        Route::post('/', [WarehouseReceiptController::class, 'store'])->middleware([
            AuthenticateWithJWT::class, Authorization::class . ':stock.receipts.manage'
        ]);
        Route::put('/{id}', [WarehouseReceiptController::class, 'update'])->middleware([
            AuthenticateWithJWT::class, Authorization::class . ':stock.receipts.manage'
        ]);
        Route::delete('/{id}', [WarehouseReceiptController::class, 'destroy'])->middleware([
            AuthenticateWithJWT::class, Authorization::class . ':stock.receipts.manage'
        ]);
        Route::get('/trashed', [WarehouseReceiptController::class, 'trashed'])->middleware([
            SortMiddleware::class, AuthenticateWithJWT::class, Authorization::class . ':view'
        ]);
        Route::get('/{id}', [WarehouseReceiptController::class, 'show'])->middleware([
            AuthenticateWithJWT::class, Authorization::class . ':view'
        ]);
        Route::get('/', [WarehouseReceiptController::class, 'index'])->middleware([
            SortMiddleware::class, AuthenticateWithJWT::class, Authorization::class . ':view'
        ]);
    });
}