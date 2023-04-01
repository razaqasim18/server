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
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $currentDate = date('Y-m-d');
        Server::where('expired_at', '<=', $currentDate)->update([
            'is_expired' => 1,
        ]);
        \Storage::put('expire_cron.txt', 'Server expiry cron is running cronjob :' . date('Y-m-d H:i:s'));
        // return Command::SUCCESS;
    }
}
