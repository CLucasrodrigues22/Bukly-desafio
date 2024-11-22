<?php

namespace Database\Seeders;

use App\Models\ReservationShare;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ReservationShare::factory()->count(10)->create();
    }
}
