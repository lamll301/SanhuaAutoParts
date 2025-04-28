<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseReceiptController;
use App\Http\Middleware\SortMiddleware;

$types = ['imports', 'exports', 'disposals', 'checks', 'cancels'];

foreach ($types as $type) {
    Route::prefix($type)->group(function () {
        Route::get('/trashed', [WarehouseReceiptController::class, 'trashed'])->middleware(SortMiddleware::class);
        Route::patch('/{id}/restore', [WarehouseReceiptController::class, 'restore']);
        Route::delete('/{id}/force-delete', [WarehouseReceiptController::class, 'forceDelete']);
        Route::post('/handle-form-actions', [WarehouseReceiptController::class, 'handleFormActions']);
        Route::post('/', [WarehouseReceiptController::class, 'store']);
        Route::put('/{id}', [WarehouseReceiptController::class, 'update']);
        Route::delete('/{id}', [WarehouseReceiptController::class, 'destroy']);
        Route::get('/{id}', [WarehouseReceiptController::class, 'show']);
        Route::get('/', [WarehouseReceiptController::class, 'index'])->middleware(SortMiddleware::class);
    });
}
