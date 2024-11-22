<?php

use App\Jobs\ImportReservationsJob;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

/**
 * Create the Command to show all reservations of the system or a specific user
 */
test('it should have a command class', function () {
    $exists = class_exists('App\Console\Commands\ImportReservationsCommand');

    expect($exists)->toBeTrue();
});

/**
 * The command should receive the user id and should validate if exists on database.
 */
test('it should return error if user does not exist', function () {
    $this->artisan('app:import-reservations', [
        '--user' => User::max('id') + 1,
    ])->expectsOutput('The user does not exist on database.')->assertFailed();
});

/**
 * The command should dispatch the job to import the reservations.
 */
test('it should return table with all reservations of the system', function () {
    Queue::fake();

    $this->artisan('app:import-reservations')
        ->expectsOutput('The reservations are being imported.')
        ->assertSuccessful();

    Queue::assertPushed(ImportReservationsJob::class, function ($job) {
        return is_null($job->user);
    });
});

/**
 * The command should dispatch the job to import the reservations of a specific user.
 */
test('it should return table with all reservations of a specific user', function () {
    Queue::fake();

    $user = User::factory()->create();

    $this->artisan('app:import-reservations', [
        '--user' => $user->id,
    ])->expectsOutput('The reservations are being imported.')->assertSuccessful();

    Queue::assertPushed(ImportReservationsJob::class, function ($job) use ($user) {
        return $job->user == $user->id;
    });
});
