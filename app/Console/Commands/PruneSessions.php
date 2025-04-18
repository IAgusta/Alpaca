<?php

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PruneSessions extends Command
{
    protected $signature = 'session:clean';
    protected $description = 'Delete expired sessions from the sessions table';

    public function handle()
    {
        $lifetime = config('session.lifetime'); // in minutes
        $cutoff = Carbon::now()->subMinutes($lifetime);

        DB::table('sessions')
            ->where('last_activity', '<', $cutoff->timestamp)
            ->delete();

        $this->info('Expired sessions pruned successfully.');
    }
}
