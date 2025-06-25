<?php

use Illuminate\Support\Facades\Route;
use Zplus\ViPOS\Http\Controllers\Admin\PosController;
use Zplus\ViPOS\Http\Controllers\Admin\PosDashboardController;
use Zplus\ViPOS\Http\Controllers\Admin\PosSessionController;
use Zplus\ViPOS\Http\Controllers\Admin\PosTransactionController;

// Test route without authentication
Route::get('/vipos-test', [PosController::class, 'index'])->name('vipos.test');
Route::get('/vipos-fullscreen-test', [PosController::class, 'fullscreen'])->name('vipos.fullscreen.test');

Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('vipos')->name('admin.vipos.')->group(function () {
        // POS Dashboard (now fullscreen by default)
        Route::get('/', [PosController::class, 'index'])->name('index');
        // POS Dashboard (non-fullscreen version with stats)
        Route::get('/dashboard', [PosDashboardController::class, 'index'])->name('dashboard');
        // POS Dashboard API stats
        Route::get('/dashboard/stats', [PosDashboardController::class, 'getStats'])->name('dashboard.stats');
        // POS Fullscreen
        Route::get('/pos/fullscreen', [PosController::class, 'fullscreen'])->name('pos.fullscreen');
        
        // POS Sessions
        Route::prefix('sessions')->name('sessions.')->group(function () {
            Route::get('/', [PosSessionController::class, 'index'])->name('index');
            Route::get('/current', [PosSessionController::class, 'getCurrent'])->name('current');
            Route::get('/{id}', [PosSessionController::class, 'show'])->name('show');
            Route::post('/open', [PosSessionController::class, 'open'])->name('open');
            Route::post('/{id}/close', [PosSessionController::class, 'close'])->name('close');
        });
        
        // POS Transactions
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [PosTransactionController::class, 'index'])->name('index');
            Route::post('/checkout', [PosTransactionController::class, 'checkout'])->name('checkout');
            Route::get('/products', [PosTransactionController::class, 'getProducts'])->name('products');
            Route::get('/categories', [PosTransactionController::class, 'getCategories'])->name('categories');
            Route::get('/customers/search', [PosTransactionController::class, 'searchCustomers'])->name('customers.search');
            Route::post('/customers/quick-create', [PosTransactionController::class, 'quickCreateCustomer'])->name('customers.quick-create');
            
            // Receipt printing routes
            Route::get('/{id}/print', [PosTransactionController::class, 'printReceipt'])->name('print');
            Route::get('/{id}/download', [PosTransactionController::class, 'downloadReceipt'])->name('download');
            Route::get('/{id}/details', [PosTransactionController::class, 'getTransactionDetails'])->name('details');
        });
    });
});
