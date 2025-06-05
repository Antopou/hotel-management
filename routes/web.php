<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('rooms', RoomController::class);
    Route::resource('room-types', RoomTypeController::class);
    Route::resource('guests', GuestController::class);
    Route::resource('reservations', ReservationController::class);
    Route::get('/reservations/checkin', [ReservationController::class, 'checkinPage'])->name('reservations.checkin.page');
    Route::post('/reservations/{id}/checkin', [ReservationController::class, 'doCheckin'])->name('reservations.doCheckin');
    Route::resource('checkins', CheckinController::class);
    Route::put('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

});

require __DIR__.'/auth.php';

