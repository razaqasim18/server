<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::select('*', 'users.id AS customerid')
            ->leftJoin('accounts', 'users.id', '=', 'accounts.user_id')
            ->orderBy('customerid', 'DESC')
            ->get();
        return view('customer.admin-list', ['customers' => $customers]);
    }

    public function addSkype(Request $request)
    {
        $customerid = $request->customerid;
        $skype = $request->skype;
        $error = '';
        if ($skype == '') {
            $error = 'skype number is required' . "\n";
        }

        if ($error) {
            $result = ['type' => 0, 'msg' => $error];
            echo json_encode($result);
            exit();
        } else {
            $response = User::findOrFail($customerid)->update([
                'skype' => $skype,
            ]);
            if ($response) {
                $type = 1;
                $msg = 'Skype is added successfully';
            } else {
                $type = 0;
                $msg = 'Something went wrong';
            }
            $result = ['type' => $type, 'msg' => $msg];
            echo json_encode($result);
            exit();
        }
    }

    public function addWhatsapp(Request $request)
    {
        $customerid = $request->customerid;
        $whatsapp = $request->whatsapp;
        $error = '';
        if ($whatsapp == '') {
            $error = 'whatsapp number is required' . "\n";
        }

        if ($error) {
            $result = ['type' => 0, 'msg' => $error];
            echo json_encode($result);
            exit();
        } else {
            $response = User::findOrFail($customerid)->update([
                'whatsapp' => $whatsapp,
            ]);
            if ($response) {
                $type = 1;
                $msg = 'Whatsapp is added successfully';
            } else {
                $type = 0;
                $msg = 'Something went wrong';
            }
            $result = ['type' => $type, 'msg' => $msg];
            echo json_encode($result);
            exit();
        }
    }

    public function view($id)
    {
        $customers = User::select('*', 'users.id AS customerid')
            ->leftJoin('accounts', function ($join) {
                $join->on('users.id', '=', 'accounts.user_id');
            })
            ->findOrFail($id);
        return view('customer.admin-detail', ['customers' => $customers]);
    }

    public function softDelete($id)
    {
        $response = User::findOrFail($id)->update([
            'is_deleted' => 1,
            'is_deleted_at' => date('Y-m-d'),
        ]);
        if ($response) {
            $type = 1;
            $msg = 'Data is deleted successfully';
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
    }

    public function changeStatus($id, $status)
    {
        $response = User::findOrFail($id)->update([
            'is_block' => $status,
        ]);
        $text = $status ? 'blocked' : 'Un-blocked';
        if ($response) {
            $type = 1;
            $msg = "User is $text successfully";
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit();
    }

    public function addAmount(Request $request)
    {
        $customerid = $request->customerid;
        $amount = $request->amount;
        $error = '';
        if ($amount == '') {
            $error = 'Amount is required' . "\n";
        }

        if ($error) {
            $result = ['type' => 0, 'msg' => $error];
            echo json_encode($result);
            exit();
        } else {
            $account = Account::where('user_id', $customerid)->first();
            $response = $account->update([
                'addedamount' => $account->addedamount + $amount,
                'totalamount' => $account->totalamount + $amount,
            ]);
            if ($response) {
                $type = 1;
                $msg = 'Amount is added successfully';
            } else {
                $type = 0;
                $msg = 'Something went wrong';
            }
            $result = ['type' => $type, 'msg' => $msg];
            echo json_encode($result);
            exit();
        }
    }

    public function deductAmount(Request $request)
    {
        $customerid = $request->customerid;
        $amount = $request->amount;
        $error = '';
        if ($amount == '') {
            $error = 'Amount is required' . "\n";
        }

        if ($error) {
            $result = ['type' => 0, 'msg' => $error];
            echo json_encode($result);
            exit();
        } else {
            $account = Account::where('user_id', $customerid)->first();
            $response = $account->update([
                'addedamount' => $account->addedamount - $amount,
                'totalamount' => $account->totalamount - $amount,
            ]);
            if ($response) {
                $type = 1;
                $msg = 'Amount is deducted successfully';
            } else {
                $type = 0;
                $msg = 'Something went wrong';
            }
            $result = ['type' => $type, 'msg' => $msg];
            echo json_encode($result);
            exit();
        }
    }

    public function add()
    {
        return view('customer.admin-add');
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        DB::beginTransaction();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $account = Account::insert([
            'user_id' => $user->id,
            'totalamount' => 0,
            'addedamount' => 0,
            'pendingamount' => 0,
            'deductedamount' => 0,
        ]);
        if ($account && $user) {
            DB::commit();
            return redirect()->route('admin.customers.add')->with('success', 'User is added successfully');
        } else {
            DB::rollback();
            return redirect()->route('admin.customers.add')->with('success', 'Something went wrong')->withInput(['name' => $request->name, 'email' => $request->email]);
        }
    }
}
