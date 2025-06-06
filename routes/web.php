<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\GuestFolioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routes loaded with "web" middleware.
|
*/

// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard route (requires authentication and email verification)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Group routes that require authentication
Route::middleware(['auth'])->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resource routes
    Route::resource('rooms', RoomController::class);
    Route::resource('room-types', RoomTypeController::class);
    Route::resource('guests', GuestController::class);
    Route::resource('reservations', ReservationController::class);
    Route::resource('checkins', CheckinController::class);

    // Additional reservation actions
    Route::get('/reservations/checkin', [ReservationController::class, 'checkinPage'])->name('reservations.checkin.page');
    Route::post('/reservations/{id}/checkin', [ReservationController::class, 'doCheckin'])->name('reservations.doCheckin');
    Route::put('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Folios routes grouped with prefix and name prefix
    Route::prefix('folios')->name('folios.')->group(function () {
        Route::get('/', [GuestFolioController::class, 'index'])->name('index');
        Route::get('/{folio_code}', [GuestFolioController::class, 'show'])->name('show');
        Route::get('/{folio_code}/print', [GuestFolioController::class, 'print'])->name('print');
        Route::post('/{folio_code}/items', [GuestFolioController::class, 'storeItem'])->name('items.store');
        Route::delete('/items/{id}', [GuestFolioController::class, 'destroyItem'])->name('items.destroy');
        Route::delete('/{folio_code}', [GuestFolioController::class, 'destroy'])->name('destroy');
        Route::post('/create-for-checkin/{checkin_code}', [GuestFolioController::class, 'createForCheckin'])->name('create.for.checkin');
    });
});

require __DIR__.'/auth.php';
