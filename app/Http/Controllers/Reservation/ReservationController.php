<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{
    /**
     * Display a listing of the reservations for the authenticated user.
     *
     * @return Response
     */
    public function index(): Response
    {
        $reservations = Reservation::where('user_id', auth()->id())->get();

        return Inertia::render('Reservation/Index', [
            'reservations' => $reservations
        ]);
    }
}
