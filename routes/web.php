<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeatureRequestController;

Route::get('/', [FeatureRequestController::class, 'index']);
Route::resource('feature-requests', FeatureRequestController::class);
