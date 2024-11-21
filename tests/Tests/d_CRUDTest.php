<?php

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

/**
 * Create the route to list all reservations of the user.
 *
 * Get the reservations of the authenticated user.
 */
test('it should return all reservations of the user', function () {
    $reservations = Reservation::factory()->count(8)->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->get('/reservations');

    $response->assertOk()->assertInertia(function (Assert $page) use ($reservations) {
        $page->component('Reservation/Index');

        $page->has('reservations', $reservations->count());
    });
});

/**
 * Create the route to create a new reservation.
 *
 * Validate the payload to create a new reservation.
 */
test('it should validate the payload to create a new reservation', function () {
    $response = $this->from('/reservations')->post('/reservations', [
        //
    ]);

    $response->assertInvalid([
        'name' => trans('validation.required', ['attribute' => 'name']),
        'check_in' => trans('validation.required', ['attribute' => 'check in']),
        'check_out' => trans('validation.required', ['attribute' => 'check out']),
    ]);

    $response = $this->from('/reservations')->post('/reservations', [
        'name' => 'New Reservation',
        'check_in' => Carbon::now()->subDays(5)->toISOString(),
        'check_out' => Carbon::now()->addDays(1)->toISOString(),
    ]);

    $response->assertInvalid([
        'check_in' => trans('validation.date_format', ['attribute' => 'check in', 'format' => 'Y-m-d']),
        'check_out' => trans('validation.date_format', ['attribute' => 'check out', 'format' => 'Y-m-d']),
    ]);

    $response = $this->from('/reservations')->post('/reservations', [
        'name' => 'New Reservation',
        'check_in' => Carbon::now()->subDays(5)->format('Y-m-d'),
        'check_out' => Carbon::now()->addDays(1)->format('Y-m-d'),
    ]);

    $response->assertInvalid([
        'check_in' => trans('validation.after_or_equal', ['attribute' => 'check in', 'date' => 'today']),
    ]);

    $response = $this->from('/reservations')->post('/reservations', [
        'name' => 'New Reservation',
        'check_in' => Carbon::now()->addDay()->format('Y-m-d'),
        'check_out' => Carbon::now()->addDays(3)->format('Y-m-d'),
    ]);

    $response->assertValid()->assertRedirect('/reservations');

    $this->assertDatabaseHas(Reservation::class, [
        'name' => 'New Reservation',
        'check_in' => Carbon::now()->addDay()->format('Y-m-d 00:00:00'),
        'check_out' => Carbon::now()->addDays(3)->format('Y-m-d 00:00:00'),
    ]);

    $response->assertSessionHas('success', 'Reservation created successfully.');
});

/**
 * Edit the reservation of the user.
 *
 * Validate if try to create a new reservation using the same name.
 */
test('it should edit the reservation', function () {
    $reservation = Reservation::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->from('/reservations')->post('/reservations', [
        'name' => $reservation->name,
        'check_in' => Carbon::now()->addDay()->format('Y-m-d'),
        'check_out' => Carbon::now()->addDays(3)->format('Y-m-d'),
    ]);

    $response->assertInvalid([
        'name' => trans('validation.unique', ['attribute' => 'name']),
    ]);

    $response = $this->from('/reservations')->put("/reservations/{$reservation->id}", [
        'name' => $reservation->name,
        'check_in' => Carbon::now()->addDays(5)->format('Y-m-d'),
        'check_out' => Carbon::now()->addDays(8)->format('Y-m-d'),
    ]);

    $response->assertValid()->assertRedirect('/reservations');

    $this->assertDatabaseHas(Reservation::class, [
        'name' => $reservation->name,
        'check_in' => Carbon::now()->addDays(5)->format('Y-m-d 00:00:00'),
        'check_out' => Carbon::now()->addDays(8)->format('Y-m-d 00:00:00'),
    ]);

    $response->assertSessionHas('success', 'Reservation updated successfully.');
});

/**
 * Create the route to show the reservation.
 */
test('it should return the reservation', function () {
    $reservation = Reservation::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->get("/reservations/{$reservation->id}");

    $response->assertOk()->assertInertia(function (Assert $page) use ($reservation) {
        $page->component('Reservation/Show');

        $page->has('reservation', function (Assert $page) use ($reservation) {
            $page->where('id', $reservation->id)
                ->where('name', $reservation->name)
                ->where('check_in', $reservation->check_in->toISOString())
                ->where('check_out', $reservation->check_out->toISOString())
                ->etc();
        });
    });
});
