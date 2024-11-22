<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Models\ReservationShare;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ShareController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ReservationShare $reservationShare, string $token): Response
    {
        abort_if($reservationShare->isExpired(), 404);

        abort_unless(Hash::check($token, $reservationShare->token), 403);

        return Inertia::render('Reservation/Share', [
            'payload' => $reservationShare->payload,
        ]);
    }
}
