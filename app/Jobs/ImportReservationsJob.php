<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportReservationsJob implements ShouldQueue
{
    use Queueable;

    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->userId) {
            // specific user
            $response = Http::get("https://api.example.com/reservations/{$this->userId}");
        } else {
            // all reservations
            $response = Http::get("https://api.example.com/reservations");
        }

        // verify if response is ok
        if ($response->successful()) {
            $reservations = $response->json();

            foreach ($reservations as $reservationData) {
                Reservation::create([
                    'user_id' => $reservationData['user_id'],
                    'name' => $reservationData['name'],
                    'slug' => Str::slug($reservationData['name']),
                    'check_in' => $reservationData['check_in'],
                    'check_out' => $reservationData['check_out'],
                ]);
            }
        }
    }
}
