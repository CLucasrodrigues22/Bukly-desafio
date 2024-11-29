<?php

use App\Http\Controllers\Reservation;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome'));

Route::prefix('/reservations')->group(function () {
    Route::get('/', [Reservation\ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/', [Reservation\ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/{reservation_share}/{token}', Reservation\ShareController::class);
});
