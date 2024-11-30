<?php

namespace App\Jobs;

use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportReservationsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Chama a API externa para pegar as reservas
        $response = Http::get('https://api.example.com/reservations');

        // Verifica se a resposta foi bem-sucedida
        if ($response->successful()) {
            $reservations = $response->json();

            foreach ($reservations as $reservationData) {
                // Atribui os dados e cria a reserva
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
