<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Package;
use App\Models\Server;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Rules\userBalanceCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServerController extends Controller
{
    public function addServer()
    {
        $category = Category::all();
        return view('order_server.customer-add-server', [
            'category' => $category,
        ]);
    }

    public function getServerSalesPlan($id)
    {
        $category = $id;
        $salepackage = Package::where('category_id', $category)->get();
        echo json_encode($salepackage);
        exit;
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'category' => 'required',
            'saleplan' => 'required',
            'price' => ['required', new userBalanceCheck],
        ]);

        $categoryid = $request->category;
        $saleplan = $request->saleplan;
        $explodeValue = explode('|', $saleplan);
        $packageid = $explodeValue[0];
        $price = $explodeValue[1];
        $categorydetail = Category::findorFail($categoryid);
        $packagedetail = Package::findorFail($packageid);
        // ticket insert
        $ticket = new Ticket();
        $ticket->user_id = Auth::guard('web')->user()->id;
        $ticket->title = "Please ready server $categorydetail->category ($packagedetail->package)";
        $ticket->department_id = 3; // 3 for server order
        $ticket->package_id = $packageid;
        $ticket->package_price = $price;
        $ticket->priority_id = 3; // 1 for high
        $ticket->status = 0; // 0 opening, 1 closed
        $ticket->user_type = 1; //  0 admin , 1 user
        $responseticket = $ticket->save();
        $ticketid = $ticket->id;

        // ticket detail insert
        $message =
        'Hello Sales Team,</br> Please ready ' .
        $packagedetail->package . ' ' .
        $categorydetail->category . '</br>Price:$ ' .
        $packagedetail->price . ' per month';
        $ticketdetail = new TicketDetail();
        $ticketdetail->ticket_id = $ticketid;
        $ticketdetail->from_id = Auth::guard('web')->user()->id;
        $ticketdetail->message = $message;
        $ticketdetail->user_type = '0';
        $resposeticketdetail = $ticketdetail->save();

        // account insert
        $accountresponse = Account::where(
            'user_id',
            Auth::guard('web')->user()->id
        )->first();
        $updateaccoutresponse = $accountresponse->update([
            'addedamount' =>
            $accountresponse->addedamount - $price,
            'deductedamount' => $accountresponse->deductedamount + $price,
        ]);

        if ($responseticket && $resposeticketdetail && $updateaccoutresponse) {
            DB::commit();
            orderServerMail(Auth::guard('web')->user()->id);
            return redirect()
                ->route('order.server.add')
                ->with('success', 'Order server request is saved successfully');
        } else {
            DB::rollback();
            return redirect()
                ->route('order.server.add')
                ->with('error', 'Something went wrong');
        }

    }

    function list() {
        $support = DB::table('tickets')
            ->select(
                'users.id AS userid',
                'users.name AS username',
                'tickets.id AS ticketid',
                'tickets.title AS tickettitle',
                'tickets.status AS ticketstatus',
                'tickets.created_at AS ticketcreated_at',
                'priorities.id AS ticketpriorityid',
                'priorities.priority AS ticketpriority',
                'servers.expired_at AS expired_at',
                'servers.is_expired AS is_expired',
                'servers.id AS alloted',
                'servers.id AS serverid',
                DB::raw(
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 1) AS isseen'
                )
            )
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->leftJoin('servers', 'servers.ticket_id', '=', 'tickets.id')
            ->where('tickets.department_id', '3') // 1 is for sales
            ->orderBy('ticketid')
            ->where('tickets.user_id', Auth::guard('web')->user()->id)
            ->get();

        return view('order_server.customer-list-server', [
            'support' => $support,
        ]);
    }

    public function delete($id)
    {
        // DB::transaction(function ($id) {
        DB::beginTransaction();
        $ticketDetail = Ticket::select('*', 'tickets.user_id AS customerid')->join('ticket_details', 'ticket_details.ticket_id', '=', 'tickets.id')
            ->where('tickets.id', $id)
            ->first();
        $account = Account::where('user_id', $ticketDetail->customerid)->first();
        $accountresponse = Account::where('user_id', $ticketDetail->customerid)
            ->update([
                'deductedamount' => $account->deductedamount - $ticketDetail->package_price,
                'addedamount' => $account->addedamount + $ticketDetail->package_price,
            ]);
        $ticketdeleteresponse = Ticket::destroy($id);
        if ($ticketdeleteresponse && $accountresponse) {
            DB::commit();
            $type = 1;
            $msg = 'Order server is deleted successfully';
        } else {
            DB::rollback();
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
        // });
    }

    public function detail($id)
    {
        $server = Server::select('*', 'tickets.id AS ticket_id')
            ->join('data_centers', 'data_centers.id', '=', 'servers.data_center_id')
            ->join('packages', 'packages.id', '=', 'servers.package_id')
            ->join('tickets', 'tickets.id', '=', 'servers.ticket_id')
            ->findorFail($id);
        return view('order_server.detail', [
            'server' => $server,
        ]);
    }

    public function serverList()
    {
        $server = Server::join('packages', 'packages.id', '=', 'servers.package_id')->where('user_id', Auth('web')->user()->id)->get();
        return view('server.customer-list-server', ['server' => $server, 'type' => 'customer']);
    }

    public function expiredServerList()
    {
        $server = Server::where('user_id', Auth('web')->user()->id)->where('is_expired', '1')->get();
        return view('server.customer-list-server', ['server' => $server, 'type' => 'customer']);
    }

    public function availableServerList()
    {
        $server = Server::where('user_id', Auth('web')->user()->id)->where('is_expired', '0')->get();
        return view('server.customer-list-server', ['server' => $server, 'type' => 'customer']);
    }

}
