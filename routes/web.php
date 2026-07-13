<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeatureRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('feature-requests', FeatureRequestController::class);
    Route::post('/feature-requests/{featureRequest}/start', [FeatureRequestController::class, 'start'])->name('feature-requests.start');
    Route::post('/feature-requests/{featureRequest}/cancel', [FeatureRequestController::class, 'cancel'])->name('feature-requests.cancel');
    Route::post('/feature-requests/{featureRequest}/complete', [FeatureRequestController::class, 'complete'])->name('feature-requests.complete');
    Route::get('/add-application', [App\Http\Controllers\ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/add-application', [App\Http\Controllers\ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/{application}/edit', [App\Http\Controllers\ApplicationController::class, 'edit'])->name('applications.edit');
    Route::put('/applications/{application}', [App\Http\Controllers\ApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{application}', [App\Http\Controllers\ApplicationController::class, 'destroy'])->name('applications.destroy');


    // User Management (admin only)
    Route::middleware('role:administrator')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    });

    // Settings (all authenticated users)
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
});
