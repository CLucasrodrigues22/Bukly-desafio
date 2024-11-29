<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'check_in' => 'required|date_format:Y-m-d|after_or_equal:today',
            'check_out' => 'required|date_format:Y-m-d|after:check_in',
        ]);

        // Gerar o slug antes de criar a reserva
        $slug = Str::slug($validated['name']);

        // Criação da reserva após validação
        $reservation = Reservation::create([...$validated, 'user_id' => auth()->id(), 'slug' => $slug]);

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

}
