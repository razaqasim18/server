<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Package;
use App\Models\ReportPayment;
use App\Models\Server;
use Illuminate\Console\Command;

class ServerRenew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serverrenew:cron';

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
        $server = Server::where('is_expired', '1')->get();
        foreach ($server as $row) {
            $useraccountresponse = Account::where('user_id', $row->user_id)->first();
            $packageresponse = Package::find($row->package_id);
            if ($useraccountresponse->addedamount >= $packageresponse->price) {
                $useraccountresponse->update(['addedamount' =>
                    $useraccountresponse->addedamount - $packageresponse->price,
                    'deductedamount' => $useraccountresponse->deductedamount + $packageresponse->price,
                ]);

                $report = new ReportPayment;
                $report->user_id = $row->user_id;
                $report->server_id = $row->id;
                $report->server_ip = $row->server_ip;
                $report->amount = $packageresponse->price;
                $report->status = 0;
                $report->created_at = date('Y-m-d H:i:s');
                $report->approve_by = 0;
                $report->save();

                $row->expired_at = date('Y-m-d', strtotime('+30 days', strtotime(date('Y-m-d'))));
                $row->is_expired = 0;
                $row->save();
            }
        }
        \Storage::put('server_renew_cron.txt', 'Server expiry cron is running cronjob :' . date('Y-m-d H:i:s'));
        // return Command::SUCCESS;
    }
}
