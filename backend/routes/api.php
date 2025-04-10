<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// =========================
// 🔐 AUTHENTICATION
// =========================

// 📖 Public: Login
Route::post('/login', [AuthController::class, 'login']);

// 🔐 Authenticated users: Logout
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


// =========================
// 👤 USERS
// =========================

// 📖 Public: Register a new user
Route::post('/users', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {

    // 🛡️ Admin only (authorization handled in policies)
    Route::get('/users', [UserController::class, 'index']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);

    // 🔐 Get current authenticated user
    Route::get('/user', [UserController::class, 'me']);
});


// =========================
// 🏡 ACCOMMODATIONS
// =========================

// 📖 Public: List and view accommodations
Route::get('/accommodations', [AccommodationController::class, 'index']);
Route::get('/accommodations/{accommodation}', [AccommodationController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // 🛡️ Admin only (authorization handled via policies)
    Route::post('/accommodations', [AccommodationController::class, 'store']);
    Route::put('/accommodations/{accommodation}', [AccommodationController::class, 'update']);
    Route::delete('/accommodations/{accommodation}', [AccommodationController::class, 'destroy']);
});


// =========================
// 📅 RESERVATIONS
// =========================

Route::middleware('auth:sanctum')->group(function () {

    // 🛡️ Admin only
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update']);
    // Optional delete route:
    // Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);

    // ✅ Admin or related user (guest_id or booked_by_id)
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);

    // 🔐 Any authenticated user can create a reservation
    Route::post('/reservations', [ReservationController::class, 'store']);
});
