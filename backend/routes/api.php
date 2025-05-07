<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationLogController;
use App\Http\Controllers\ReservationServiceController;
use App\Http\Controllers\ServiceController;
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
    Route::get('/users/search', [UserController::class, 'search']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    // 🔐 Current authenticated user actions
    Route::get('/user', [UserController::class, 'me']);
    Route::put('/user', [UserController::class, 'updateSelf']);
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
    Route::post('/accommodations/{accommodation}/images', [AccommodationController::class, 'uploadImage']);
    Route::put('/accommodations/{accommodation}', [AccommodationController::class, 'update']);
    Route::delete('/accommodations/{accommodation}', [AccommodationController::class, 'destroy']);
    Route::delete('/accommodations/images/{image}', [AccommodationController::class, 'deleteImage']);
});


// =========================
// 📅 RESERVATIONS
// =========================

Route::middleware('auth:sanctum')->group(function () {

    // 🛡️ Admin only (authorization handled via policies)
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/guest/{user}', [ReservationController::class, 'getByGuest']);
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update']);
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);
    // TODO: implement soft delete somehow
    // Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);

    // 🔐 Current authenticated user actions
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/user/reservations', [ReservationController::class, 'ownReservations']);
});

// =========================
// 🗒️ RESERVATION LOGS
// =========================

Route::middleware('auth:sanctum')->group(function () {
    // 🛡️ Admin only (authorization handled via policies)
    Route::get('reservation_logs/{reservation}', [ReservationLogController::class, 'index']);
});

// =========================
// 🧑‍🔧 SERVICES
// =========================

// 📖 Public: List and view services
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{service}', [ServiceController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    // 🛡️ Admin only (authorization handled via policies)
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{service}', [ServiceController::class, 'update']);
    Route::delete('/services/{service}', [ServiceController::class, 'destroy']);
    Route::post('/reservations/{reservation}/services', [ReservationServiceController::class, 'attachService']);
    Route::delete('/reservations/{reservation}/services', [ReservationServiceController::class, 'detachService']);

    // 🔐 Current authenticated user actions over services
    Route::get('/user/reservations/{reservation}/services', [ReservationServiceController::class, 'ownAttachedServices']);
    Route::post('user/reservations/{reservation}/services', [ReservationServiceController::class, 'attachOwnService']);
});
