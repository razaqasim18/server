<?php

namespace App\Console\Commands;

use App\Models\Server;
use Illuminate\Console\Command;

class ExpireServerCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expirecron:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("Server expiry cron is running cronjob!");
        $currentDate = date('Y-m-d');
        Server::where('expired_at', '<=', $currentDate)->update([
            'is_expired' => 1,
        ]);
        // return Command::SUCCESS;
    }
}
