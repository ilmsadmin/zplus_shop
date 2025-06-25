<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-vipos', function () {
    return 'ViPOS routes are working!';
})->name('test.vipos');
