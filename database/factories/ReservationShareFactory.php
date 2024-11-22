<?php

namespace Database\Factories;

use App\Models\ReservationShare;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReservationShareFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = ReservationShare::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'payload' => [
                'name' => $this->faker->sentence(3),
                'email' => $this->faker->safeEmail(),
                'check_in' => $this->faker->dateTimeBetween('now', '+5 days')->format('Y-m-d'),
                'check_out' => $this->faker->dateTimeBetween('+6 days', '+10 days')->format('Y-m-d'),
            ],
            'token' => Hash::make(Str::random(60)),
            'expires_at' => Carbon::now()->addDays(7),
        ];
    }
}
