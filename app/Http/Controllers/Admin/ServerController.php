<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\DataCenter;
use App\Models\Package;
use App\Models\ReportPayment;
use App\Models\Server;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function allServer()
    {
        $server = DB::table('tickets')
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
                'servers.id AS serverid',
                'servers.id AS alloted',
                DB::raw(
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 0) AS isseen'
                )
            )
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->leftJoin('servers', 'servers.ticket_id', '=', 'tickets.id')
            ->where('tickets.department_id', '3') // 3 is for server
            ->orderBy('ticketid', 'DESC')
            ->get();
        return view('order_server.admin-list-server', [
            'server' => $server,
            'type' => 'all',
        ]);
    }

    public function openServer()
    {
        $server = DB::table('tickets')
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
                'servers.id AS serverid',
                'servers.id AS alloted',
                DB::raw(
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 0) AS isseen'
                )
            )
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->leftJoin('servers', 'servers.ticket_id', '=', 'tickets.id')
            ->where('tickets.department_id', '3') // 3 is for server
            ->where('tickets.status', '0')
            ->orderBy('ticketid', 'DESC')
            ->get();
        return view('order_server.admin-list-server', [
            'server' => $server,
            'type' => 'open',
        ]);
    }

    public function closeServer()
    {
        $server = DB::table('tickets')
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
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 0) AS isseen'
                )
            )
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->leftJoin('servers', 'servers.ticket_id', '=', 'tickets.id')
            ->where('tickets.department_id', '3') // 3 is for server
            ->where('tickets.status', '1')
            ->orderBy('ticketid', 'DESC')
            ->get();
        return view('order_server.admin-list-server', [
            'server' => $server,
            'type' => 'close',
        ]);
    }

    public function deleteOrderServer($id)
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

    public function viewServer($id)
    {
        $datacenter = DataCenter::all();
        $ticket = Ticket::findOrFail($id);
        $server = Server::where('ticket_id', $id)->first();

        $ticketdetail = TicketDetail::where('ticket_id', $ticket->id)
            ->orderBy('id', 'DESC')
            ->get();
        return view('order_server.admin-detail-server', [
            'datacenter' => $datacenter,
            'server' => $server,
            'ticket' => $ticket,
            'ticketdetail' => $ticketdetail,
        ]);
    }

    public function installServer(Request $request)
    {

        $datacenter = $request->datacenter;
        $serverip = $request->serverip;
        $web_user = $request->web_user;
        $web_password = $request->web_password;
        $uuid = $request->uuid;
        $servercost = $request->servercost;
        $serversetupcost = $request->serversetupcost;
        $saleprice = $request->saleprice;

        $ticketid = $request->ticketid;
        $error = '';
        if (empty($datacenter)) {
            $error .= 'Data center is required' . "\n";
        }
        if (empty($serverip)) {
            $error .= 'Server IP is required' . "\n";
        } else {
            $server = Server::where('server_ip', $serverip)->first();
            if ($server) {
                $result = ['type' => 0, 'msg' => 'The serverip has already been taken.'];
                echo json_encode($result);
                exit();
            }
        }
        if (empty($web_user)) {
            $error .= 'Web user is required' . "\n";
        }
        if (empty($web_password)) {
            $error .= 'Web password is required' . "\n";
        }
        if (empty($uuid)) {
            $error .= 'UUID is required' . "\n";
        }
        if (empty($servercost)) {
            $error .= 'Server cost is required' . "\n";
        }
        if (empty($serversetupcost)) {
            $error .= 'Server setup cost is required' . "\n";
        }
        if (empty($saleprice)) {
            $error .= 'Sale cost is required' . "\n";
        }

        if (!empty($error)) {
            $result = ['type' => 0, 'msg' => $error];
            echo json_encode($result);
            exit();
        }

        DB::beginTransaction();

        $ticket = Ticket::select('tickets.id AS ticketid', 'tickets.user_id AS userid', 'tickets.package_id AS packageid')->join('ticket_details', 'ticket_details.ticket_id', '=', 'tickets.id')->where('tickets.id', $ticketid)->first();
        $date = strtotime(date("Y-m-d"));
        $serverresponse = Server::create([
            'data_center_id' => $datacenter,
            'ticket_id' => $ticket->ticketid,
            'user_id' => $ticket->userid,
            'package_id' => $ticket->packageid,
            'server_ip' => $serverip,
            'web_user' => $web_user,
            'web_password' => $web_password,
            'uuid' => $uuid,
            'server_cost' => $servercost,
            'setup_cost' => $serversetupcost,
            'sale_price' => $saleprice,
            'expired_at' => date("Y-m-d", strtotime("+1 month", $date)),
        ]);

        $message = "Hello, <br> This is to inform you that your server installation has been started. Your server IP is ( $serverip ).</br> Once server installation is finished you will receive login details of your order_server.<br>Please feel free to contact our support team if you have any question.<br>Thanks";
        $ticketdetail = new TicketDetail();
        $ticketdetail->ticket_id = $ticket->ticketid;
        $ticketdetail->from_id = Auth::guard('admin')->user()->id;
        $ticketdetail->to_id = $ticket->userid;
        $ticketdetail->message = $message;
        $ticketdetail->user_type = '1';
        $ticketdetailresponse = $ticketdetail->save();

        if ($serverresponse && $ticketdetailresponse) {
            DB::commit();
            installServerMail($serverresponse->id);
            $type = 1;
            $msg = "Server is added successfully";
        } else {
            DB::rollback();

            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
    }

    public function detail($type, $id)
    {
        $server = Server::select('*', 'tickets.id AS ticket_id')
            ->join('data_centers', 'data_centers.id', '=', 'servers.data_center_id')
            ->join('packages', 'packages.id', '=', 'servers.package_id')
            ->join('tickets', 'tickets.id', '=', 'servers.ticket_id')
            ->findorFail($id);
        return view('order_server.detail', [
            'server' => $server,
            'type' => $type,
            'url' => 'admin',
        ]);
    }

    public function serverList()
    {
        $server = Server::select('*', 'servers.id AS serverid')
            ->join('users', 'users.id', '=', 'servers.user_id')
            ->join('data_centers', 'data_centers.id', '=', 'servers.data_center_id')
            ->get();
        $title = 'Server List';
        return view('server.admin-list-server', ['server' => $server, 'title' => $title]);
    }

    public function listByServerid($id)
    {
        $server = Server::select('*', 'servers.id AS serverid')
            ->join('users', 'users.id', '=', 'servers.user_id')
            ->join('data_centers', 'data_centers.id', '=', 'servers.data_center_id')
            ->join('packages', 'packages.id', '=', 'servers.package_id')
            ->join('categories', 'categories.id', '=', 'packages.category_id')
            ->where('categories.id', $id)
            ->get();
        $category = Category::findorFail($id);
        $title = $category->category;
        return view('server.admin-list-server', ['server' => $server, 'title' => $title]);
    }

    public function expiredServerList()
    {
        $server = Server::join('users', 'users.id', '=', 'servers.user_id')
            ->join('data_centers', 'data_centers.id', '=', 'servers.data_center_id')
            ->where('is_expired', '1')
            ->get();
        $title = 'Expired Server List';
        return view('server.admin-list-server', ['server' => $server, 'title' => $title]);
    }

    public function availableServerList()
    {
        $server = Server::join('users', 'users.id', '=', 'servers.user_id')
            ->join('data_centers', 'data_centers.id', '=', 'servers.data_center_id')
            ->where('is_expired', '0')
            ->get();
        $title = 'Expired Server List';
        return view('server.admin-list-server', ['server' => $server, 'title' => $title]);

    }

    public function addExpiry(Request $request)
    {
        $serverid = $request->serverid;
        $expirydate = $request->expirydate;
        $error = '';
        if (empty($expirydate)) {
            $error .= "Expiry date is required" . "\n";
        }
        if (!empty($error)) {
            $result = ['type' => 0, 'msg' => $error];
            echo json_encode($result);
            exit();
        } else {
            $server = Server::find($serverid);
            // $server->expired_at = Carbon::createFromFormat('m/d/Y', $expirydate)->format('Y-m-d');
            $server->expired_at = date("Y-m-d", strtotime($expirydate));
            $server->is_expired = 0;
            if ($server->save()) {
                $result = ['type' => 1, 'msg' => 'Data is saved successfully'];
            } else {
                $result = ['type' => 0, 'msg' => "Something went wrong"];
            }
            echo json_encode($result);
            exit();
        }

    }

    public function renewal($serverid, $userid, $packageid)
    {
        $serverid = $serverid;
        $userid = $userid;

        $useraccountresponse = Account::where('user_id', $userid)->first();
        $packageresponse = Package::find($packageid);
        if ($useraccountresponse->addedamount < $packageresponse->price) {
            $result = ['type' => 0, 'msg' => 'User balance is insufficent'];
            echo json_encode($result);
            exit();
        }
        DB::beginTransaction();
        $server = Server::find($serverid);
        if (date('Y-m-d') > $server->expired_at) {
            $olddays = 30;
        } else {
            $olddays = 30 + dateDiffdays(date('Y-m-d'), $server->expired_at);
        }
        $newexpirydate = date('Y-m-d', strtotime('+' . $olddays . ' days', strtotime(date('Y-m-d'))));
        $server->expired_at = $newexpirydate;
        $server->is_expired = 0;

        // $server->save();
        $report = new ReportPayment;
        $report->user_id = $userid;
        $report->server_id = $serverid;
        $report->server_ip = $server->server_ip;
        $report->amount = $packageresponse->price;
        $report->status = 0;
        $report->created_at = date('Y-m-d H:i:s');
        $report->approve_by = 0;

        $updateaccoutresponse = $useraccountresponse->update([
            'addedamount' =>
            $useraccountresponse->addedamount - $packageresponse->price,
            'deductedamount' => $useraccountresponse->deductedamount + $packageresponse->price,
        ]);

        if ($server->save() && $report->save() && $updateaccoutresponse) {
            DB::commit();
            $result = ['type' => 1, 'msg' => 'Data is saved successfully'];
        } else {
            DB::rollback();
            $result = ['type' => 0, 'msg' => "Something went wrong"];
        }
        echo json_encode($result);
        exit();

    }

    public function addServer()
    {
        $datacenter = DataCenter::all();
        $user = User::all();
        $package = Package::all();
        $category = Category::all();
        return view('server.admin-add-server', [
            'datacenter' => $datacenter,
            'user' => $user,
            'package' => $package,
            'category' => $category,
        ]);
    }

    public function insertServer(Request $request)
    {
        $this->validate($request, [
            'datacenter' => 'required',
            'userid' => 'required',
            'packageid' => 'required',
            'serverip' => 'required|unique:servers,server_ip',
            // 'web_user' => 'required',
            // 'web_password' => 'required',
            // 'uuid' => 'required',
            'servercost' => 'required',
            'serversetupcost' => 'required',
            'saleprice' => 'required',
        ]);
        $package = explode("|", $request->packageid);
        DB::beginTransaction();

        $date = strtotime(date("Y-m-d"));

        $categorydetail = Category::findorFail($request->category);
        $ticket = new Ticket();
        $ticket->user_id = Auth::guard('admin')->user()->id;
        $ticket->title = "Please ready server $categorydetail->category ($package[2])";
        $ticket->department_id = 3; // 3 for server order
        $ticket->package_id = $package[0];
        $ticket->package_price = $package[1];
        $ticket->priority_id = 3; // 1 for high
        $ticket->status = 0; // 0 opening, 1 closed
        $ticket->user_type = 0; //  0 admin , 1 user
        $responseticket = $ticket->save();
        $ticketid = $ticket->id;

        $message = "Hello, <br> This is to inform you that your server installation has been started. Your server IP is ( $request->serverip ).</br> Once server installation is finished you will receive login details of your order_server.<br>Please feel free to contact our support team if you have any question.<br>Thanks";
        $ticketdetail = new TicketDetail();
        $ticketdetail->ticket_id = $ticketid;
        $ticketdetail->from_id = Auth::guard('admin')->user()->id;
        $ticketdetail->to_id = $request->userid;
        $ticketdetail->message = $message;
        $ticketdetail->user_type = '1';
        $ticketdetailresponse = $ticketdetail->save();

        $serverresponse = Server::create([
            'data_center_id' => $request->datacenter,
            'ticket_id' => $ticketid,
            'user_id' => $request->userid,
            'category' => $request->category,
            'package_id' => $package[0],
            'server_ip' => $request->serverip,
            // 'web_user' => $request->web_user,
            // 'web_password' => $request->web_password,
            // 'uuid' => $request->uuid,
            'server_cost' => $request->servercost,
            'setup_cost' => $request->serversetupcost,
            'sale_price' => $request->saleprice,
            'expired_at' => date("Y-m-d", strtotime("+1 month", $date)),
        ]);

        if ($serverresponse && $responseticket && $ticketdetailresponse) {
            DB::commit();
            //  installServerMail($serverresponse->id);
            return redirect()->route('admin.add.server')->with('success', 'Server is added successfully');
        } else {
            DB::rollback();
            return redirect()->route('admin.add.server')->with('error', 'Something went wrong');
        }
    }

    public function getServerSalesPlan($id)
    {
        $category = $id;
        $salepackage = Package::where('category_id', $category)->get();
        echo json_encode($salepackage);
        exit;
    }

    public function deleteServer($id)
    {
        $response = Server::destroy($id);
        if ($response) {
            $type = 1;
            $msg = 'Order server is deleted successfully';
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();

    }

}
