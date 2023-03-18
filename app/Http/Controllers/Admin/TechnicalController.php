<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use DB;

class TechnicalController extends Controller
{
    public function allSupport()
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
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 0) AS isseen'
                )
            )
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->where('tickets.department_id', '2') // 2 is for technical support
            ->orderBy('ticketid', 'DESC')
            ->get();
        return view('technical-support.admin-list-support', [
            'support' => $support,
        ]);
    }

    public function openSupport()
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
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 0) AS isseen'
                )
            )
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->where('tickets.department_id', '2') // 1 is for support
            ->where('tickets.status', '0')
            ->orderBy('ticketid', 'DESC')
            ->get();
        return view('technical-support.admin-list-support', [
            'support' => $support,
        ]);
    }
    public function closeSupport()
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
                    '(SELECT COUNT(id) AS isseen from ticket_details WHERE is_seen = 0 AND ticket_id = ticketid AND user_type = 0) AS isseen'
                )
            )
            ->join('priorities', 'priorities.id', '=', 'tickets.priority_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->where('tickets.department_id', '2') // 1 is for support
            ->where('tickets.status', '1')
            ->orderBy('ticketid', 'DESC')
            ->get();
        return view('technical-support.admin-list-support', [
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
