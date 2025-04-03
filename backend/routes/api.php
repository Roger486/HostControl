<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 👨‍🦰 USERS

// 🛡️ Admin only
Route::middleware('auth:sanctum')
    ->get('/users', [UserController::class, 'index']);
// 📖 Public access
Route::post('/users', [UserController::class, 'store']);
// ✅ Admin or matching user (self)
Route::middleware('auth:sanctum')
    ->get('/users/{user}', [UserController::class, 'show']);
// ✅ Admin or matching user (self)
Route::middleware('auth:sanctum')
    ->put('/users/{user}', [UserController::class, 'update']);
// 🛡️ Admin only
Route::middleware('auth:sanctum')
    ->delete('/users/{user}', [UserController::class, 'destroy']);
// 🔐 Any logged-in user
Route::middleware('auth:sanctum')
    ->get('/user', [UserController::class, 'me']);

// 🏠 ACCOMMODATIONS

// 📖 Public access
Route::get('/accommodations', [AccommodationController::class, 'index']);
// 🛡️ Admin only
Route::middleware('auth:sanctum')
    ->post('/accommodations', [AccommodationController::class, 'store']);
// 📖 Public access
Route::get('/accommodations/{accommodation}', [AccommodationController::class, 'show']);
// 🛡️ Admin only
Route::middleware('auth:sanctum')
    ->put('/accommodations/{accommodation}', [AccommodationController::class, 'update']);
// 🛡️ Admin only
Route::middleware('auth:sanctum')
    ->delete('/accommodations/{accommodation}', [AccommodationController::class, 'destroy']);

// 🗒️ RESERVATIONS

// 🛡️ Admin only
Route::middleware('auth:sanctum')
    ->get('/reservations', [ReservationController::class, 'index']);
// 🔐 Any logged-in user
Route::middleware('auth:sanctum')
    ->post('/reservations', [ReservationController::class, 'store']);
// ✅ Admin or matching user (guest_id | booked_by_id)
Route::middleware('auth:sanctum')
    ->get('/reservations/{reservation}', [ReservationController::class, 'show']);
// 🛡️ Admin only
Route::middleware('auth:sanctum')
    ->put('/reservations/{reservation}', [ReservationController::class, 'update']);
// 🛡️ Admin only
//Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);

// 👮 AUTH

// 📖 Public access
Route::post('/login', [AuthController::class, 'login']);
// ✅ Matching user (self)
Route::middleware('auth:sanctum')
    ->post('/logout', [AuthController::class, 'logout']);
