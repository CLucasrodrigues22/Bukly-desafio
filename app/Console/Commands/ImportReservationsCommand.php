<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ImportReservationsCommand extends Command
{
    protected $signature = 'app:import-reservations {--user=}';
    protected $description = 'Import reservations from the API for a specific user';

    public function handle(): int
    {
        // receive user id
        $userId = $this->option('user');

        //validate if user exist
        $user = User::find($userId);

        if (!$user) {
            $this->error('The user does not exist on database.');
            return 1; // Código de erro
        }

        $this->info('User found, starting reservation import...');

        return 0;
    }
}
