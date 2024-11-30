<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\ImportReservationsJob;
use Illuminate\Console\Command;

class ImportReservationsCommand extends Command
{
    protected $signature = 'app:import-reservations {--user=}';
    protected $description = 'Import reservations from the API for a specific user';

    public function handle(): int
    {
        $userId = $this->option('user');

        if ($userId) {
            $user = User::find($userId);

            if (!$user) {
                $this->error('The user does not exist on database.');
                return 1;
            }

            ImportReservationsJob::dispatch($userId);
            $this->info('The reservation are being imported.');
        } else {
            ImportReservationsJob::dispatch();
            $this->info('The reservations are being imported.');
        }

        return 0;
    }

}


