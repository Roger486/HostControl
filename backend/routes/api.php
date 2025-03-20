<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::apiResource('accommodations', AccommodationController::class);
