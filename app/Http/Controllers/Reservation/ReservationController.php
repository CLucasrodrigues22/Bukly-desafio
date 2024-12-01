<?php

namespace App\Http\Controllers\Reservation;

use App\Events\ReservationUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Rules\TwoWords;
use Illuminate\Support\Facades\Gate;
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

    public function store(ReservationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // generate slug
        $slug = Str::slug($validated['name']);

        // create
        $reservation = Reservation::create([...$validated, 'user_id' => auth()->id(), 'slug' => $slug]);

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    public function update(ReservationRequest $request, Reservation $reservation)
    {
        $validated = $request->validated();

        // updates the reservation with validated data
        $reservation->update($validated);

        event(new ReservationUpdated($reservation)); // Dispatch event

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation updated successfully.');
    }

    public function show(Reservation $reservation)
    {
        Gate::authorize('view', $reservation);

        return Inertia::render('Reservation/Show', [
            'reservation' => [
                'id' => $reservation->id,
                'name' => $reservation->name,
                'check_in' => $reservation->check_in->toISOString(),
                'check_out' => $reservation->check_out->toISOString(),
            ],
        ]);
    }

    public function destroy(Reservation $reservation)
    {

        $reservation->delete();

        return redirect('/reservations')->with('success', 'Reservation deleted successfully.');
    }

}
