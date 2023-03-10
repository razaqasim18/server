<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketDetail;
use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard');
    }

    public function profile()
    {
        return view('profile');
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'image' => 'image',
        ]);

        $name = $request->name;
        $fileName = null;
        if (!empty($request->file('image'))) {
            $fileName = time() . '.' . $request->file('image')->extension();
            $request
                ->file('image')
                ->move(public_path('uploads/user'), $fileName);
        } else {
            $fileName = $request->showimage;
        }
        $user = Auth::guard('web')->user();
        $user->name = $name;
        if ($fileName) {
            $user->image = $fileName;
        } else {
            $user->image = null;
        }
        if ($user->save()) {
            return redirect()
                ->route('profile')
                ->with('success', 'Profile is saved successfully');
        } else {
            return redirect()
                ->route('profile')
                ->with('error', 'Something went wrong');
        }
    }

    public function password()
    {
        return view('password');
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'password' =>
            'required|min:5|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:5',
        ]);

        $password = $request->password;
        $user = Auth::guard('web')->user();
        $user->password = bcrypt($password);
        if ($user->save()) {
            return redirect()
                ->route('password')
                ->with('success', 'Password is saved successfully');
        } else {
            return redirect()
                ->route('password')
                ->with('error', 'Something went wrong');
        }
    }

    public function getunSeenSalesCount()
    {
        $customerID = Auth::guard('web')->user()->id;
        $query = Ticket::join(
            'ticket_details',
            'ticket_details.ticket_id',
            '=',
            'tickets.id'
        )
            ->where('tickets.department_id', 1)
            ->where('ticket_details.is_seen', 0)
            ->where('ticket_details.user_type', 1) // 1 for user
            ->where('to_id', $customerID);
        $count = $query->count();
        echo $count ? $count : 0;
    }

    public function getUnseenSupportCount()
    {
        $customerID = Auth::guard('web')->user()->id;
        $query = Ticket::join(
            'ticket_details',
            'ticket_details.ticket_id',
            '=',
            'tickets.id'
        )
            ->where('tickets.department_id', 2)
            ->where('ticket_details.is_seen', 0)
            ->where('ticket_details.user_type', 1) // 1 for user
            ->where('to_id', $customerID);
        $count = $query->count();
        echo $count ? $count : 0;
    }

    public function getUnseenServerCount()
    {
        $customerID = Auth::guard('web')->user()->id;
        $query = Ticket::join(
            'ticket_details',
            'ticket_details.ticket_id',
            '=',
            'tickets.id'
        )
            ->where('tickets.department_id', 3)
            ->where('ticket_details.is_seen', 0)
            ->where('ticket_details.user_type', 1) // 1 for user
            ->where('to_id', $customerID);
        $count = $query->count();
        echo $count ? $count : 0;
    }

    public function seenTicket($id)
    {
        $response = TicketDetail::where('ticket_id', $id)
            ->where('user_type', 1)
            ->update([
                'is_seen' => 1,
            ]);
        $result = $response ? true : false;
        echo $result;
    }

    public function view($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticketdetail = TicketDetail::where('ticket_id', $ticket->id)
            ->orderBy('id', 'DESC')
            ->get();
        return view('ticket.customer-ticket-detail', [
            'ticket' => $ticket,
            'ticketdetail' => $ticketdetail,
        ]);
    }

    public function replyView($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('ticket.customer-ticket-reply', ['ticket' => $ticket]);
    }

    public function message($id, Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticketdetail = TicketDetail::create([
            'ticket_id' => $ticket->id,
            'from_id' => Auth::guard('web')->user()->id,
            'to_id' => null,
            'message' => $request->message,
            'user_type' => '0',
        ]);
        if ($ticketdetail) {
            return redirect()
                ->route('ticket.reply.view', ['id' => $id])
                ->with('success', 'Message is saved successfully');
        } else {
            return redirect()
                ->route('ticket.reply.view', ['id' => $id])
                ->with('error', 'Something went wrong');
        }
    }
}
