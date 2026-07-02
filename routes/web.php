<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeatureRequestController;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('feature-requests', FeatureRequestController::class);
    Route::get('/add-application', [App\Http\Controllers\ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/add-application', [App\Http\Controllers\ApplicationController::class, 'store'])->name('applications.store');
});
