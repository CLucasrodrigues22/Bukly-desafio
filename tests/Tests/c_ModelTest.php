<?php

use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationCreated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

/**
 * Create the Reservation Model.
 */
test('it should check if the model exists', function () {
    $exists = class_exists('App\Models\Reservation');

    expect($exists)->toBeTrue();
});

/**
 * Create relationships between User and Reservation.
 */
test('it should check if the model has a relationship with the user', function () {
    $relationship = (new Reservation)->user();

    expect($relationship)->toBeInstanceOf(BelongsTo::class);

    $relationship = (new User)->reservations();

    expect($relationship)->toBeInstanceOf(HasMany::class);
});

/**
 * Create the Reservation Model Factory.
 */
test('it should check if the model has a factory', function () {
    $exists = class_exists('Database\Factories\ReservationFactory');

    expect($exists)->toBeTrue();

    $user = User::factory()->create();

    Reservation::factory()->count(3)->create(['user_id' => $user->id]);

    expect(Reservation::where('user_id', $user->id)->count())->toBe(3);
});

/**
 * Implement Model Query Scope to filter Reservation for today.
 *
 * Use the 'check_in' column to filter reservations for today.
 */
test('it should check if the model has a query scope to filter reservations for today', function () {
    $user = User::factory()->create();

    Reservation::factory()->count(3)->create([
        'user_id' => $user->id,
        'check_in' => Carbon::now()->subDay()->format('Y-m-d'),
        'check_out' => Carbon::now()->addDay()->format('Y-m-d'),
    ]);

    Reservation::factory()->count(3)->create([
        'user_id' => $user->id,
        'check_in' => Carbon::now()->format('Y-m-d'),
        'check_out' => Carbon::now()->addDays(2)->format('Y-m-d'),
    ]);

    expect($user->reservations()->checkInToday()->count())->toBe(3);
});

/**
 * Create the set mutator for the slug attribute.
 *
 * Use the 'name' column to generate the slug.
 *
 * Use Str::slug() to generate the slug.
 */
test('it should check if the model has a set mutator for the slug attribute', function () {
    $reservation = Reservation::factory()->create(['name' => 'My First Reservation']);

    $this->assertDatabaseHas(Reservation::class, [
        'id' => $reservation->id,
        'slug' => 'my-first-reservation',
    ]);
});

/**
 * Create the get mutator for the slug attribute.
 *
 * Use Str::title() to generate the slug attribute.
 */
test('it should check if the model has a get mutator for the slug attribute', function () {
    $reservation = Reservation::factory()->create(['name' => 'My First Reservation']);

    $this->assertDatabaseHas(Reservation::class, [
        'id' => $reservation->id,
        'slug' => 'my-first-reservation',
    ]);

    expect($reservation->slug)->toBe('My-First-Reservation');
});

/**
 * Create the casts for the check_in and check_out attributes.
 */
test('it should check if the model has casts for the check_in and check_out attributes', function () {
    $reservation = Reservation::factory()->create([
        'check_in' => Carbon::now()->format('Y-m-d'),
        'check_out' => Carbon::now()->addDays(2)->format('Y-m-d'),
    ]);

    expect($reservation->check_in)->toBeInstanceOf(Carbon::class);
    expect($reservation->check_out)->toBeInstanceOf(Carbon::class);
});

/**
 * Create the ReservationCreated Notification.
 *
 * Use the ReservationCreated notification to notify the user when a reservation is created.
 *
 * You can use the Model Event to trigger the notification.
 */
test('it should send a notification when a reservation is created', function () {
    Notification::fake();

    $user = User::factory()->create();

    $reservation = Reservation::factory()->create(['user_id' => $user->id]);

    Notification::assertSentTo($user, ReservationCreated::class, function ($notification) use ($reservation) {
        return $notification->reservation->id === $reservation->id;
    });
});
