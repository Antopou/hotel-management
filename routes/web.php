<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\FrontDeskController;
use App\Http\Controllers\GuestFolioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/front-desk/folios/{folio_code}', [GuestFolioController::class, 'showFrontdesk'])->name('front-desk.folios.show');

// Classic frontdesk dashboard (stats, arrivals, departures, in-house, etc)
Route::get('/front-desk', [FrontDeskController::class, 'index'])->name('front-desk.index');

// NEW! Front Desk Room Explorer grid
Route::get('/front-desk/rooms', [FrontDeskController::class, 'rooms'])->name('front-desk.rooms')->middleware('auth');

// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
Route::get('/reports/revenue/export', [ReportController::class, 'exportRevenue'])->name('reports.revenue.export');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resource Routes
    Route::resource('rooms', RoomController::class);
    Route::put('/rooms/{room}/update-status', [RoomController::class, 'updateStatus'])->name('rooms.update-status');
    Route::resource('room-types', RoomTypeController::class);
    Route::resource('guests', GuestController::class);
    Route::resource('reservations', ReservationController::class);
    Route::resource('checkins', CheckinController::class);

    // Reservation check-in/out actions
    Route::get('/reservations/checkin', [ReservationController::class, 'checkinPage'])->name('reservations.checkin.page');
    Route::post('/reservations/{id}/checkin', [ReservationController::class, 'doCheckin'])->name('reservations.doCheckin');
    Route::put('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('/reservations/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');

    // Guest folio routes
    Route::prefix('folios')->name('folios.')->group(function () {
        Route::get('/', [GuestFolioController::class, 'index'])->name('index');
        Route::get('/create', [GuestFolioController::class, 'create'])->name('create');
        Route::post('/', [GuestFolioController::class, 'store'])->name('store');
        Route::get('/export', [GuestFolioController::class, 'export'])->name('export');
        Route::get('/{folio_code}', [GuestFolioController::class, 'show'])->name('show');
        Route::get('/{folio_code}/edit', [GuestFolioController::class, 'edit'])->name('edit');
        Route::put('/{folio_code}', [GuestFolioController::class, 'update'])->name('update');
        Route::get('/{folio_code}/print', [GuestFolioController::class, 'print'])->name('print');
        Route::post('/{folio_code}/items', [GuestFolioController::class, 'storeItem'])->name('items.store');
        Route::delete('/items/{id}', [GuestFolioController::class, 'destroyItem'])->name('items.destroy');
        Route::delete('/{folio_code}', [GuestFolioController::class, 'destroy'])->name('destroy');
        Route::post('/create-for-checkin/{checkin_code}', [GuestFolioController::class, 'createForCheckin'])->name('create.for.checkin');
        
        // Payment routes
        Route::post('/{folio_code}/payments', [GuestFolioController::class, 'storePayment'])->name('payments.store');
        Route::delete('/payments/{payment_id}', [GuestFolioController::class, 'destroyPayment'])->name('payments.destroy');
    });

});

require __DIR__.'/auth.php';
