<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\Transaction;
use Auth;
use DB;

class SalesController extends Controller
{
    public function allSales()
    {
        // $sales = Ticket::all();
        $sales = DB::table('tickets')
            ->select(
                'users.id AS userid',
                'users.name AS username',
                'tickets.id AS ticketid',
                'tickets.title AS tickettitle',
                'tickets.status AS ticketstatus',
                'transactions.id AS transid',
                'tickets.created_at AS ticketcreated_at',
                'priorities.priority AS ticketpriority',
                'transactions.status AS transstatus',
                DB::raw(
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 0) AS isseen'
                )
            )
        // ->join('ticket_details', 'ticket_details.ticket_id', '=', 'tickets.id')
            ->join('transactions', 'transactions.ticket_id', '=', 'tickets.id')
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->where('tickets.department_id', '1') // 1 is for sales
            ->orderBy('ticketid', 'DESC')
            ->get();

        return view('sales.admin-list', ['sales' => $sales]);
    }
    public function openSales()
    {
        $sales = DB::table('tickets')
            ->select(
                'users.id AS userid',
                'users.name AS username',
                'tickets.id AS ticketid',
                'tickets.title AS tickettitle',
                'tickets.status AS ticketstatus',
                'transactions.id AS transid',
                'tickets.created_at AS ticketcreated_at',
                'priorities.priority AS ticketpriority',
                'transactions.status AS transstatus',
                DB::raw(
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 0) AS isseen'
                )
            )
        // ->join('ticket_details', 'ticket_details.ticket_id', '=', 'tickets.id')
            ->join('transactions', 'transactions.ticket_id', '=', 'tickets.id')
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->where('tickets.department_id', '1') // 1 is for sales
            ->where('tickets.status', '0')
            ->orderBy('ticketid', 'DESC')
            ->get();
        return view('sales.admin-list', ['sales' => $sales]);
    }
    public function closeSales()
    {
        $sales = DB::table('tickets')
            ->select(
                'users.id AS userid',
                'users.name AS username',
                'tickets.id AS ticketid',
                'tickets.title AS tickettitle',
                'tickets.status AS ticketstatus',
                'transactions.id AS transid',
                'tickets.created_at AS ticketcreated_at',
                'priorities.priority AS ticketpriority',
                'transactions.status AS transstatus',
                DB::raw(
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 0) AS isseen'
                )
            )
        // ->join('ticket_details', 'ticket_details.ticket_id', '=', 'tickets.id')
            ->join('transactions', 'transactions.ticket_id', '=', 'tickets.id')
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->where('tickets.department_id', '1') // 1 is for sales
            ->where('tickets.status', '1')
            ->orderBy('ticketid', 'DESC')
            ->get();
        return view('sales.admin-list', ['sales' => $sales]);
    }

    public function viewSales($id)
    {
        // $sales = DB::table("transactions")
        //     ->select("*", "transactions.id AS transactionsID", "payment_methods.title AS paymenttitle", "transactions.image AS transectionimage")
        //     ->join('users', 'users.id', '=', 'transactions.user_id')
        //     ->join('payment_methods', 'payment_methods.id', '=', 'transactions.user_id')
        //     ->where('transactions.id', $id)
        //     ->first();

        $sales = Transaction::select(
            '*',
            'transactions.id AS transactionsID',
            'payment_methods.title AS paymenttitle',
            'payment_methods.slug AS paymentslug',
            'transactions.image AS transectionimage'
        )
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->join(
                'payment_methods',
                'payment_methods.id',
                '=',
                'transactions.payment_methods_id'
            )
            ->findOrFail($id);

        return view('sales.admin-detail', ['sales' => $sales]);
    }

    public function approvalSales($id)
    {
        DB::beginTransaction();
        // transaction 3
        $transaction = Transaction::where('id', $id)->first();
        $transactionresponse = $transaction->update(['status' => '1']);
        //account
        $account = Account::where('user_id', $transaction->user_id)->first();
        $accountresponse = $account->update([
            'addedamount' => $account->addedamount + $transaction->amount,
            'totalamount' => $account->totalamount + $transaction->amount,
            'pendingamount' => $account->pendingamount - $transaction->amount,
        ]);

        $ticketresponse = Ticket::findOrFail($transaction->ticket_id)->update([
            'is_answer' => Auth::guard('admin')->user()->id,
        ]);

        $message =
            'Hello, <br> Your payment has been successfully approved and balance amount has been added into your account.Thanks';
        $ticketdetail = new TicketDetail();
        $ticketdetail->ticket_id = $transaction->ticket_id;
        $ticketdetail->from_id = Auth::guard('admin')->user()->id;
        $ticketdetail->to_id = $transaction->user_id;
        $ticketdetail->message = $message;
        $ticketdetail->user_type = '1';
        $ticketdetailresponse = $ticketdetail->save();

        if (
            $transactionresponse &&
            $accountresponse &&
            $ticketresponse &&
            $ticketdetailresponse
        ) {
            DB::commit();
            approvePaymentMail($transaction->user_id,$transaction->id);
            $type = 1;
            $msg = 'Transcation is saved successfully';
        } else {
            DB::rollback();
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
    }

    public function rejectSales($id)
    {
        DB::beginTransaction();
        // transaction 3
        $transaction = Transaction::where('id', $id)->first();
        $transactionresponse = $transaction->update(['status' => '-1']);
        //account
        $account = Account::where('user_id', $transaction->user_id)->first();
        $accountresponse = $account->update([
            // 'addedamount' => $account->addedamount - $transaction->amount,
            // 'totalamount' => $account->totalamount - $transaction->amount,
            'pendingamount' => $account->pendingamount - $transaction->amount,
        ]);

        $ticketresponse = Ticket::findOrFail($transaction->ticket_id)->update([
            'is_answer' => Auth::guard('admin')->user()->id,
        ]);

        $message = 'Hello, <br> Your payment has been rejected.<br>Thanks';
        $ticketdetail = new TicketDetail();
        $ticketdetail->ticket_id = $transaction->ticket_id;
        $ticketdetail->from_id = Auth::guard('admin')->user()->id;
        $ticketdetail->to_id = $transaction->user_id;
        $ticketdetail->message = $message;
        $ticketdetail->user_type = '1';
        $ticketdetailresponse = $ticketdetail->save();

        if (
            $transactionresponse &&
            $accountresponse &&
            $ticketresponse &&
            $ticketdetailresponse
        ) {
            DB::commit();
            $type = 1;
            $msg = 'Transcation is saved successfully';
        } else {
            DB::rollback();
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
    }

    public function deleteSales($id)
    {
        DB::beginTransaction();
        $transaction = Transaction::findOrFail($id)->first();

        $account = Account::where('user_id', $transaction->user_id)->first();
        $accountresponse = Account::where(
            'user_id',
            $transaction->user_id
        )->update([
            'pendingamount' => $account->pendingamount - $transaction->amount,
        ]);
        $transactionresponse = Transaction::destroy($id);
        $ticketresponse = Ticket::where(
            'id',
            $transaction->ticket_id
        )->delete();
        if ($accountresponse && $transactionresponse && $ticketresponse) {
            DB::commit();
            $type = 1;
            $msg = 'Saled is deleted successfully';
        } else {
            DB::rollback();
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
    }
}
