<?php

use App\Jobs\ImportReservationsJob;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

/**
 * Create the job to import reservations.
 *
 * The job has the user as an optional parameter.
 */
test('it should create the job', function () {
    Queue::fake();

    ImportReservationsJob::dispatch();

    Queue::assertPushed(ImportReservationsJob::class);
});

/**
 * Import reservations from the API.
 */
test('it should import reservations from the API', function () {
    User::factory()->count(2)->create();

    // Fake the HTTP client
    Http::fake([
        'https://api.example.com/reservations' => Http::response([
            [
                'id' => 'd9314f2d-229d-48d8-bbc6-34144e1dc512',
                'user_id' => 1,
                'name' => 'Reservation 1',
                'check_in' => '2021-01-01',
                'check_out' => '2021-01-02',
            ],
            [
                'id' => '84d4e6de-4b88-4e7f-9169-bc744d107a1c',
                'user_id' => 2,
                'name' => 'Reservation 2',
                'check_in' => '2021-01-03',
                'check_out' => '2021-01-04',
            ],
        ]),
    ]);

    ImportReservationsJob::dispatch();

    $this->assertDatabaseHas(Reservation::class, [
        'user_id' => 1,
        'name' => 'Reservation 1',
        'slug' => 'reservation-1',
        'check_in' => '2021-01-01 00:00:00',
        'check_out' => '2021-01-02 00:00:00',
    ]);

    $this->assertDatabaseHas(Reservation::class, [
        'user_id' => 2,
        'name' => 'Reservation 2',
        'slug' => 'reservation-2',
        'check_in' => '2021-01-03 00:00:00',
        'check_out' => '2021-01-04 00:00:00',
    ]);
});

/**
 * Import reservations from the API for a specific user.
 */
test('it should import reservations from the API for a specific user', function () {
    $user = User::factory()->create();

    // Fake the HTTP client
    Http::fake([
        "https://api.example.com/reservations/{$user->id}" => Http::response([
            [
                'id' => '023a4b47-86df-418c-883f-4d9f8d8648b3',
                'user_id' => $user->id,
                'name' => 'Reservation 1',
                'check_in' => '2021-01-01',
                'check_out' => '2021-01-02',
            ],
            [
                'id' => '46c36ed0-d664-43ba-bc91-40c0e2444bf0',
                'user_id' => $user->id,
                'name' => 'Reservation 2',
                'check_in' => '2021-01-03',
                'check_out' => '2021-01-04',
            ],
        ]),
    ]);

    ImportReservationsJob::dispatch($user->id);

    $this->assertDatabaseHas(Reservation::class, [
        'user_id' => $user->id,
        'name' => 'Reservation 1',
        'slug' => 'reservation-1',
        'check_in' => '2021-01-01 00:00:00',
        'check_out' => '2021-01-02 00:00:00',
    ]);

    $this->assertDatabaseHas(Reservation::class, [
        'user_id' => $user->id,
        'name' => 'Reservation 2',
        'slug' => 'reservation-2',
        'check_in' => '2021-01-03 00:00:00',
        'check_out' => '2021-01-04 00:00:00',
    ]);
});
