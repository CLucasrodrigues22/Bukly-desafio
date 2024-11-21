<?php

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Install the Laravel Auditing Package
 *
 * @see https://packagist.org/packages/owen-it/laravel-auditing
 */
test('it should check if the package is installed', function () {
    $output = null;
    exec('composer show', $output);

    expect(collect($output)->filter(function ($item) {
        return Str::contains($item, 'owen-it/laravel-auditing');
    })->count())->toBe(1);
});

/**
 * Publish the config and migration files
 */
test('it should publish the config and migration files', function () {
    $exists = File::exists(config_path('audit.php'));

    expect($exists)->toBeTrue();

    $exists = Schema::hasTable('audits');

    expect($exists)->toBeTrue();
});

/**
 * Create new audits for the User model.
 *
 * For this test to pass, you need to change the config('audit.console') to true.
 */
test('it should create a new audit', function () {
    $user = User::factory()->create();

    $user->fill(['name' => 'New Name'])->save();

    expect($user->audits()->count())->toBe(2);
});
