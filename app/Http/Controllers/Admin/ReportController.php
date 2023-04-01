<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportPayment;

class ReportController extends Controller
{
    public function serverPayment()
    {
        $report = ReportPayment::join('users', 'users.id', '=', 'report_payments.user_id')->where('status', 0)->orderBy('report_payments.created_at')->get();
        return view('report.list', ['report' => $report]);
    }

    public function serverPaymentFilter()
    {
        $reportQuery = ReportPayment::select('name', 'server_ip', 'amount', 'status', 'approve_by', 'report_payments.created_at AS created_at')->join('users', 'users.id', '=', 'report_payments.user_id')->where('status', 0);
        // $reportQuery = ReportPayment::select('name')->join('users', 'users.id', '=', 'report_payments.user_id')->where('status', 0);
        $start_date = (!empty($_GET["start_date"])) ? (date('Y-m-d', strtotime($_GET["start_date"]))) : ('');
        $end_date = (!empty($_GET["end_date"])) ? (date('Y-m-d', strtotime($_GET["end_date"]))) : ('');
        if ($start_date && $end_date) {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            $reportQuery->whereRaw("date(report_payments.created_at) >= '" . $start_date . "' AND date(report_payments.created_at) <= '" . $end_date . "'");
        }
        $reports = $reportQuery->orderBy('report_payments.created_at')->get();
        return datatables()
            ->of($reports)
            ->addColumn('status', function ($row) {
                $html = $row->status ? 'credited' : 'deducted';
                return $html;
            })
            ->addColumn('approve_by', function ($row) {
                $html = ($row->approve_by == '0') ? 'System' : 'User';
                return $html;
            })
            ->addColumn('created_at', function ($row) {
                $html = date('Y-m-d', strtotime($row->created_at));
                return $html;
            })
            ->make(true);
    }
}
