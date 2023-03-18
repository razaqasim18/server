<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Priority;
use App\Models\TicketDetail;
use App\Models\Ticket;
use DB;
use Auth;

class TechnicalController extends Controller
{
    public function addSupport()
    {
        $priority = Priority::all();
        return view('technical-support.customer-add-support', [
            'priority' => $priority,
        ]);
    }

    public function insertSupport(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'priority' => 'required',
            'description' => 'required',
        ]);

        DB::beginTransaction();

        // ticket insert
        $ticket = new Ticket();
        $ticket->user_id = Auth::guard('web')->user()->id;
        $ticket->title = $request->title;
        $ticket->department_id = 2; // 1 for sales
        $ticket->priority_id = $request->priority; // 1 for high
        $ticket->status = 0; // 0 opening, 1 closed
        $ticket->user_type = 1; //  0 admin , 1 user
        $responseticket = $ticket->save();
        $ticketid = $ticket->id;

        // ticket detail insert
        $message = $request->description;
        $ticketdetail = new TicketDetail();
        $ticketdetail->ticket_id = $ticketid;
        $ticketdetail->from_id = Auth::guard('web')->user()->id;
        // $ticketdetail->to_id  = $ticketid;
        $ticketdetail->message = $message;
        $ticketdetail->user_type = '0';
        $resposeticketdetail = $ticketdetail->save();

        if ($responseticket && $resposeticketdetail) {
            DB::commit();
            return redirect()
                ->route('technicalsupport.add')
                ->with(
                    'success',
                    'Technical support request is saved successfully'
                );
        } else {
            DB::rollback();
            return redirect()
                ->route('technicalsupport.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function listSupport()
    {
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
                DB::raw(
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 1) AS isseen'
                )
            )
            // ->join('ticket_details', 'ticket_details.ticket_id', '=', 'tickets.id')
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->where('tickets.department_id', '2') // 1 is for sales
            ->orderBy('ticketid')
            ->where('tickets.user_id', Auth::guard('web')->user()->id)
            ->get();

        return view('technical-support.customer-list-support', [
            'support' => $support,
        ]);
    }

    public function deleteSupport($id)
    {
        if (Ticket::destroy($id)) {
            $type = 1;
            $msg = 'Ticket is deleted successfully';
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
    }
}
