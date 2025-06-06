<?php

use App\Http\Controllers\Auth\ApiAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\GuestFolioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;

// Public API test
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/ping', fn() => response()->json(['message' => 'API is working!']));

// Protected API routes using Sanctum
Route::middleware(['auth:sanctum'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // API resources
    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('room-types', RoomTypeController::class);
    Route::apiResource('guests', GuestController::class);
    Route::apiResource('reservations', ReservationController::class);
    Route::apiResource('checkins', CheckinController::class);

    // Reservation actions
    Route::get('/reservations/{id}/checkin-page', [ReservationController::class, 'checkinPage']);
    Route::post('/reservations/{id}/checkin', [ReservationController::class, 'doCheckin']);
    Route::put('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);

    // Folios
    Route::prefix('folios')->group(function () {
        Route::get('/', [GuestFolioController::class, 'index']);
        Route::get('/{folio_code}', [GuestFolioController::class, 'show']);
        Route::get('/{folio_code}/print', [GuestFolioController::class, 'print']);
        Route::post('/{folio_code}/items', [GuestFolioController::class, 'storeItem']);
        Route::delete('/items/{id}', [GuestFolioController::class, 'destroyItem']);
        Route::delete('/{folio_code}', [GuestFolioController::class, 'destroy']);
        Route::post('/create-for-checkin/{checkin_code}', [GuestFolioController::class, 'createForCheckin']);
    });
});
