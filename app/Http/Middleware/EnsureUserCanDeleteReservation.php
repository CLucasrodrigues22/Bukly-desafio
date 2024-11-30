<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanDeleteReservation
{
    public function handle(Request $request, Closure $next): Response
    {
        $reservation = $request->route('reservation');

        if (!$reservation || $reservation->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
