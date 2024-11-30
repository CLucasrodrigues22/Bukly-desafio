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

    public $userId;
    public $user;

    /**
     * Create a new job instance.
     *
     * @param int|null $userId
     */
    public function __construct(int $userId = null)
    {
        $this->userId = $userId;

        if ($this->userId) {
            $this->user = User::find($this->userId);
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->user) {
            $response = Http::get("https://api.example.com/reservations/{$this->userId}");
        } else {
            $response = Http::get("https://api.example.com/reservations");
        }

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
