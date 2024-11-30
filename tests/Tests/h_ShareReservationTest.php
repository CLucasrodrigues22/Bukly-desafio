<?php

/**
 * Here you need to test the share reservation feature.
 *
 * See these files for reference:
 * - app\Http\Controllers\Reservation\ShareController
 * - app\Models\ReservationShare
 * - database\factories\ReservationShareFactory
 * - 000004_create_reservation_shares_table.php
 * - routes/web.php
 */

/**
 * This test is to check if the share is expired.
 *
 * If the share is expired, it should return 404.
 */

use App\Models\ReservationShare;
use Carbon\Carbon;
use Illuminate\Support\Str;

test('it should return 404 when share is expired', function () {
    $expiredShare = ReservationShare::factory()->create([
        'expires_at' => Carbon::now()->subDay(),
    ]);

    $token = 'example_token';

    $this->get(route('reservations.share', ['reservation_share' => $expiredShare->id, 'token' => $token]))
        ->assertNotFound(); // return 404
});

/**
 * This test is to check if the token is invalid.
 *
 * If the token is invalid, it should return 403.
 */
test('it should return 403 when token is invalid', function () {
    $reservationShare = ReservationShare::factory()->create();

    $invalidToken = Str::random(60);

    $response = $this->get(route('reservations.share', [
        'reservation_share' => $reservationShare->id,
        'token' => $invalidToken
    ]));

    $response->assertStatus(403);
});

/**
 * This test is to check if the share page is rendered.
 *
 * If token is valid and share is not expired, it should render the share page.
 */
test('it should render the share page', function () {
    //
});
