<?php

use Illuminate\Support\Facades\Route;

Route::resource('/', App\Http\Controllers\WelcomeController::class);

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
   
    Route::resource('/users', App\Http\Controllers\UserController::class);
    Route::resource('/profile', App\Http\Controllers\ProfileController::class);
    Route::resource('/locations', App\Http\Controllers\LocationController::class);

    Route::resource('/lots', App\Http\Controllers\LotController::class);
    Route::resource('/graves', App\Http\Controllers\GraveController::class);
    Route::resource('/owners', App\Http\Controllers\OwnerController::class);
    Route::resource('/reports', App\Http\Controllers\ReportController::class);
});
require __DIR__.'/auth.php';
