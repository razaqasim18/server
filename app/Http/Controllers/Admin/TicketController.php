<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketDetail;
use Auth;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function view($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticketdetail = TicketDetail::where('ticket_id', $ticket->id)
            ->orderBy('id', 'DESC')
            ->get();
        return view('ticket.admin-detail', [
            'ticket' => $ticket,
            'ticketdetail' => $ticketdetail,
        ]);
    }

    public function changeStatusTicket($id, $status)
    {
        $ticket = Ticket::findOrFail($id)->update([
            'status' => $status,
        ]);
        $msgtext = $status
        ? 'Ticket is closed successfully'
        : 'Ticket is opened successfully';
        if ($ticket) {
            $type = 1;
            $msg = $msgtext;
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
    }

    public function closeTicket($id)
    {
        $ticketdetail = TicketDetail::findOrFail($id);
        $ticket = Ticket::findOrFail($ticketdetail->ticket_id)->update([
            'status' => '1',
        ]);
        if ($ticket) {
            $type = 1;
            $msg = 'Ticket is closed successfully';
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
    }

    public function replyView($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('ticket.admin-reply', ['ticket' => $ticket]);
    }

    public function message($id, Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticketdetail = TicketDetail::create([
            'ticket_id' => $ticket->id,
            'from_id' => Auth::guard('admin')->user()->id,
            'to_id' => $ticket->user_id,
            'message' => $request->message,
            'user_type' => '1',
        ]);
        if ($ticketdetail) {
            return redirect()
                ->route('admin.ticket.reply.view', ['id' => $id])
                ->with('success', 'Message is saved successfully');
        } else {
            return redirect()
                ->route('admin.ticket.reply.view', ['id' => $id])
                ->with('error', 'Something went wrong');
        }
    }
}
