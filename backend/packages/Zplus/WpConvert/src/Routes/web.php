<?php

use Illuminate\Support\Facades\Route;
use Zplus\WpConvert\Http\Controllers\WpConvertController;

Route::prefix('admin/wp-convert')->middleware(['web', 'auth:admin'])->group(function () {
    Route::get('/', [WpConvertController::class, 'index'])->name('wp-convert.index');
    Route::post('/convert', [WpConvertController::class, 'convert'])->name('wp-convert.convert');
    Route::get('/download/{filename}', [WpConvertController::class, 'download'])->name('wp-convert.download');
    Route::post('/stats', [WpConvertController::class, 'stats'])->name('wp-convert.stats');
});