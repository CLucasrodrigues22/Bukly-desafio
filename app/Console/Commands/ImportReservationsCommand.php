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

        $this->info('The reservations are being imported.');

        if ($userId) {
            $user = User::find($userId);

            if (!$user) {
                $this->error('The user does not exist on database.');
                return 1;
            }
            ImportReservationsJob::dispatch($userId);
        } else {
            ImportReservationsJob::dispatch();
        }

        return 0;
    }
}
