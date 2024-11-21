<?php

use Illuminate\Support\Facades\Schema;

/**
 * Create the reservations table
 */
test('it should check if reservations table exists', function () {
    $exists = Schema::hasTable('reservations');

    expect($exists)->toBeTrue();
});

/**
 * Add columns to your table:
 *
 * user_id : int not null - foreign key
 * name: string not null
 * slug: string not null - unique
 * check_in: date not null
 * check_out: date not null
 * created_at: timestamp nullable
 * updated_at: timestamp nullable
 */
test('it should check if reservations table has columns', function () {
    $columns = Schema::getColumnListing('reservations');

    expect($columns)->toMatchArray([
        'id',
        'user_id',
        'name',
        'slug',
        'check_in',
        'check_out',
        'created_at',
        'updated_at',
    ]);
});

/**
 * Add foreign key and indexes to your table:
 *
 * slug: unique
 * user_id: foreign key
 * check_in and check_out: index
 */
test('it should check if reservations table has foreign key and indexes correctly', function () {
    $indexes = Schema::getIndexListing('reservations');

    $foreignKeys = Schema::getForeignKeys('reservations');

    expect($indexes)->toHaveCount(3);
    expect($indexes)->toContain('primary');
    expect($indexes)->toContain('reservations_slug_unique');
    expect($indexes)->toContain('reservations_check_in_check_out_index');

    expect($foreignKeys)->toHaveCount(1);
    expect(data_get($foreignKeys, '{first}.foreign_table'))->toBe('users');
    expect(data_get($foreignKeys, '{first}.columns.{first}'))->toBe('user_id');
    expect(data_get($foreignKeys, '{first}.foreign_columns.{first}'))->toBe('id');
});
