<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeatureRequestController;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('feature-requests', FeatureRequestController::class);
});
