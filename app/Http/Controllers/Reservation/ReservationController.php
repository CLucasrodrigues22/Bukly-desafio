<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Validation\Rule;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{
    public function index(): Response
    {
        $reservations = Reservation::where('user_id', auth()->id())->get();

        return Inertia::render('Reservation/Index', [
            'reservations' => $reservations
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:reservations',
            'check_in' => 'required|date_format:Y-m-d|after_or_equal:today',
            'check_out' => 'required|date_format:Y-m-d|after:check_in',
        ]);

        // generate slug
        $slug = Str::slug($validated['name']);

        // create
        $reservation = Reservation::create([...$validated, 'user_id' => auth()->id(), 'slug' => $slug]);

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    public function update(Request $request, Reservation $reservation)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('reservations')->where('user_id', auth()->id())->ignore($reservation->id), // Ignora a reserva atual
            ],
            'check_in' => 'required|date_format:Y-m-d|after_or_equal:today',
            'check_out' => 'required|date_format:Y-m-d|after:check_in',
        ]);

        // Atualiza a reserva com os dados validados
        $reservation->update($validated);

        // Redireciona de volta com sucesso
        return redirect()->route('reservations.index')
            ->with('success', 'Reservation updated successfully.');
    }

}
