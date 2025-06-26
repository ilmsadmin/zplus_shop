<?php

use Illuminate\Support\Facades\Route;
use Zplus\Warranty\Http\Controllers\Admin\WarrantyController;
use Zplus\Warranty\Http\Controllers\Admin\WarrantyPackageController;
use Zplus\Warranty\Http\Controllers\Frontend\WarrantySearchController;

Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('warranty')->name('admin.warranty.')->group(function () {
        
        // Warranty management routes
        Route::get('/', [WarrantyController::class, 'index'])->name('index');
        Route::get('/create', [WarrantyController::class, 'create'])->name('create');
        Route::post('/', [WarrantyController::class, 'store'])->name('store');
        Route::get('/{id}', [WarrantyController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WarrantyController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WarrantyController::class, 'update'])->name('update');
        Route::delete('/{id}', [WarrantyController::class, 'destroy'])->name('destroy');
        
        // API routes for DataGrid and search
        Route::get('/api/warranties', [WarrantyController::class, 'getWarranties'])->name('api.warranties');
        Route::get('/api/search', [WarrantyController::class, 'search'])->name('api.search');
        
        // Warranty packages management routes
        Route::prefix('packages')->name('packages.')->group(function () {
            Route::get('/', [WarrantyPackageController::class, 'index'])->name('index');
            Route::get('/create', [WarrantyPackageController::class, 'create'])->name('create');
            Route::post('/', [WarrantyPackageController::class, 'store'])->name('store');
            Route::get('/{id}', [WarrantyPackageController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [WarrantyPackageController::class, 'edit'])->name('edit');
            Route::put('/{id}', [WarrantyPackageController::class, 'update'])->name('update');
            Route::delete('/{id}', [WarrantyPackageController::class, 'destroy'])->name('destroy');
            
            // API routes
            Route::get('/api/packages', [WarrantyPackageController::class, 'getPackages'])->name('api.packages');
            Route::post('/{id}/toggle-status', [WarrantyPackageController::class, 'toggleStatus'])->name('toggle-status');
        });
    });
});

// Public frontend search routes (no admin middleware)
Route::prefix('warranty-search')->name('warranty.search.')->group(function () {
    Route::get('/', [WarrantySearchController::class, 'index'])->name('index');
    Route::post('/', [WarrantySearchController::class, 'search'])->name('search');
});