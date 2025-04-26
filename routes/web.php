<?php

use Illuminate\Support\Facades\Route;

Route::resource('/', App\Http\Controllers\WelcomeController::class);
Route::get('/search', [App\Http\Controllers\WelcomeController::class, 'search']);

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
   
    Route::resource('/users', App\Http\Controllers\UserController::class);
    Route::resource('/profile', App\Http\Controllers\ProfileController::class);
    Route::resource('/locations', App\Http\Controllers\LocationController::class);

    Route::resource('/sales', App\Http\Controllers\SaleController::class);
    Route::resource('/lots', App\Http\Controllers\LotController::class);
    Route::resource('/graves', App\Http\Controllers\GraveController::class);
    Route::resource('/owners', App\Http\Controllers\OwnerController::class);
    Route::resource('/reports', App\Http\Controllers\ReportController::class);

    Route::get('/blockcount', [App\Http\Controllers\WelcomeController::class, 'blockcount']);
    Route::get('/section', [App\Http\Controllers\LotController::class, 'section']);
    Route::post('/sell', [App\Http\Controllers\OwnerController::class, 'sell']);
    Route::post('/changetype', [App\Http\Controllers\GraveController::class, 'changetype']);
    
});
require __DIR__.'/auth.php';
