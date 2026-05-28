<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GreetingController;

Route::get('/', [GreetingController::class, 'index'])->name('greeting.form');
Route::post('/greet', [GreetingController::class, 'greet'])->name('greeting.generate');
Route::post('/clear-cache', [GreetingController::class, 'clearCache'])->name('greeting.clear-cache');
Route::post('/api/bulk-greet', [GreetingController::class, 'bulkGreet'])->name('greeting.bulk');