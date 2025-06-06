<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\GuestFolioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// --- Auth Endpoints (Public - No auth:sanctum required) ---
Route::post('register', [ApiAuthController::class, 'register']);
Route::post('login', [ApiAuthController::class, 'login']);

// --- Protected API Routes (require Bearer token) ---
Route::middleware('auth:sanctum')->group(function () {

    // REST resources
    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('room-types', RoomTypeController::class);
    Route::apiResource('guests', GuestController::class);
    Route::apiResource('reservations', ReservationController::class);
    Route::apiResource('checkins', CheckinController::class);
    Route::apiResource('folios', GuestFolioController::class);

    // Dashboard stats
    Route::get('dashboard', [DashboardController::class, 'index']);

    // Profile management
    Route::get('profile', [ProfileController::class, 'edit']);
    Route::patch('profile', [ProfileController::class, 'update']);
    Route::delete('profile', [ProfileController::class, 'destroy']);

    // Folio items management
    Route::post('folios/{folio_code}/items', [GuestFolioController::class, 'storeItem']);
    Route::delete('folios/items/{id}', [GuestFolioController::class, 'destroyItem']);

    // Folio creation for checkin
    Route::post('folios/create-for-checkin/{checkin_code}', [GuestFolioController::class, 'createForCheckin']);

    // Print folio
    Route::get('folios/{folio_code}/print', [GuestFolioController::class, 'print']);

    // Reservation actions
    Route::put('reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);
    Route::get('reservations/checkin', [ReservationController::class, 'checkinPage']);
    Route::post('reservations/{id}/checkin', [ReservationController::class, 'doCheckin']);

    // Logout (token revoke)
    Route::post('logout', [ApiAuthController::class, 'logout']);
});
