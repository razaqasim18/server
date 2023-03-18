<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketDetail;
use Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function profile()
    {
        return view('admin.profile');
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
                ->move(public_path('uploads/admin'), $fileName);
        } else {
            $fileName = $request->showimage;
        }
        $user = Auth::guard('admin')->user();
        $user->name = $name;
        if ($fileName) {
            $user->image = $fileName;
        } else {
            $user->image = null;
        }
        if ($user->save()) {
            return redirect()
                ->route('admin.profile')
                ->with('success', 'Profile is saved successfully');
        } else {
            return redirect()
                ->route('admin.profile')
                ->with('error', 'Something went wrong');
        }
    }

    public function password()
    {
        return view('admin.password');
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'password' =>
            'required|min:5|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:5',
        ]);

        $password = $request->password;
        $user = Auth::guard('admin')->user();
        $user->password = bcrypt($password);
        if ($user->save()) {
            return redirect()
                ->route('admin.password')
                ->with('success', 'Password is saved successfully');
        } else {
            return redirect()
                ->route('admin.password')
                ->with('error', 'Something went wrong');
        }
    }

    public function unseenSalescount()
    {
        $superAdmin = Auth::guard('admin')->user()->is_admin ? true : false;
        $adminID = Auth::guard('admin')->user()->id;
        $query = Ticket::join(
            'ticket_details',
            'ticket_details.ticket_id',
            '=',
            'tickets.id'
        )
            ->where('tickets.department_id', 1)
            ->where('ticket_details.is_seen', 0)
            ->where('ticket_details.user_type', 0);
        if ($superAdmin) {
            // $query->where('ticket_details.to_ids', null);
            // $query->orWhere(
            //     'ticket_details.to_id',
            //     Auth::guard('admin')->user()->id
            // );

            $query->where(function ($query) use ($adminID) {
                $query
                    ->where('ticket_details.to_id', null)
                    ->orWhere('ticket_details.to_id', $adminID);
            });
        } else {
            $query->where('to_id', $adminID);
        }
        $count = $query->count();
        echo $count ? $count : 0;
    }

    public function unseenSupportcount()
    {
        $superAdmin = Auth::guard('admin')->user()->is_admin ? true : false;
        $adminID = Auth::guard('admin')->user()->id;
        $query = Ticket::join(
            'ticket_details',
            'ticket_details.ticket_id',
            '=',
            'tickets.id'
        )
            ->where('tickets.department_id', 2) //support
            ->where('ticket_details.is_seen', 0)
            ->where('ticket_details.user_type', 0);
        if ($superAdmin) {
            // $query->where('ticket_details.to_ids', null);
            // $query->orWhere(
            //     'ticket_details.to_id',
            //     Auth::guard('admin')->user()->id
            // );

            $query->where(function ($query) use ($adminID) {
                $query
                    ->where('ticket_details.to_id', null)
                    ->orWhere('ticket_details.to_id', $adminID);
            });
        } else {
            $query->where('to_id', $adminID);
        }
        $count = $query->count();
        echo $count ? $count : 0;
    }

    public function unseenServercount()
    {
        $superAdmin = Auth::guard('admin')->user()->is_admin ? true : false;
        $adminID = Auth::guard('admin')->user()->id;
        $query = Ticket::join(
            'ticket_details',
            'ticket_details.ticket_id',
            '=',
            'tickets.id'
        )
            ->where('tickets.department_id', 3) //server
            ->where('ticket_details.is_seen', 0)
            ->where('ticket_details.user_type', 0);
        if ($superAdmin) {
            // $query->where('ticket_details.to_ids', null);
            // $query->orWhere(
            //     'ticket_details.to_id',
            //     Auth::guard('admin')->user()->id
            // );

            $query->where(function ($query) use ($adminID) {
                $query
                    ->where('ticket_details.to_id', null)
                    ->orWhere('ticket_details.to_id', $adminID);
            });
        } else {
            $query->where('to_id', $adminID);
        }
        $count = $query->count();
        echo $count ? $count : 0;
    }

    public function seenTicket($id)
    {
        $response = TicketDetail::where('ticket_id', $id)
            ->where('user_type', 0)
            ->update([
                'is_seen' => 1,
            ]);
        $result = $response ? true : false;
        echo $result;
    }
}
