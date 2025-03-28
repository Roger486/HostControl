<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// USERS
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

// ACCOMMODATIONS
Route::get('/accommodations', [AccommodationController::class, 'index']);
Route::post('/accommodations', [AccommodationController::class, 'store']);
Route::get('/accommodations/{accommodation}', [AccommodationController::class, 'show']);
Route::put('/accommodations/{accommodation}', [AccommodationController::class, 'update']);
Route::delete('/accommodations/{accommodation}', [AccommodationController::class, 'destroy']);

// RESERVATIONS
Route::get('/reservations', [ReservationController::class, 'index']);
Route::middleware('auth:sanctum')->post('/reservations', [ReservationController::class, 'store']);
Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);
Route::put('/reservations/{reservation}', [ReservationController::class, 'update']);
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);


Route::post('/login', [AuthController::class, 'login']);
