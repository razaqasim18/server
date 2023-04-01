<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CustomerDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customerdelete:cron';

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
        // return Command::SUCCESS;
        // Log::info("Customer deleted cron is running!");
        $currentDate = date('Y-m-d');
        \DB::table('users')
            ->where('is_deleted', '1')
            ->whereRaw("DATEDIFF(CURDATE(),is_deleted_at) > 15")
            ->delete();
        \DB::table('admins')
            ->where('is_deleted', '1')
            ->whereRaw("DATEDIFF(CURDATE(),is_deleted_at) > 15")
            ->delete();
        \Storage::put('customer_cron.txt', 'Customer delete cron is running cronjob :' . date('Y-m-d H:i:s'));

    }
}
