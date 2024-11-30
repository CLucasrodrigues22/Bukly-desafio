<?php

use App\Http\Controllers\Reservation;
use App\Http\Middleware\EnsureUserCanDeleteReservation;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome'));

Route::prefix('/reservations')->group(function () {
    Route::get('/', [Reservation\ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/', [Reservation\ReservationController::class, 'store'])->name('reservations.store');
    Route::put('/{reservation}', [Reservation\ReservationController::class, 'update'])->name('reservations.update');
    Route::get('/{reservation}', [Reservation\ReservationController::class, 'show'])->name('reservations.show');
    Route::delete('/{reservation}', [Reservation\ReservationController::class, 'destroy'])
        ->name('reservations.destroy')
        ->middleware(EnsureUserCanDeleteReservation::class);
    Route::get('/{reservation_share}/{token}', Reservation\ShareController::class)->name('reservations.share');
});
