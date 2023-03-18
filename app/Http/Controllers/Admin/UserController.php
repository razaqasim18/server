<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;

class UserController extends Controller
{
    public function list()
    {
       $user = Admin::where('id', '!=', '1')->orderBy('id', 'DESC')->get();
 return view('user.admin-list', ['user' => $user]);
    }

    public function addUser()
    {
        return view('user.admin-add');
    }

    public function insertUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:5',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:5',
            'is_admin' => 'required',
        ]);
        $user = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => $request->is_admin,
        ]);
        if ($user) {
            return redirect()
                ->route('admin.users.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.users.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function editUser($id)
    {
        $user = Admin::findOrfail($id);
        return view('user.admin-edit', ['user' => $user]);
    }

    public function updateUser($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:5',
            // 'email' => 'required|email',
            'is_admin' => 'required',
        ]);
        $updateData = [
            'name' => $request->name,
            'is_admin' => $request->is_admin,
        ];
        if ($request->password) {
            $updateData['password'] = bcrypt($request->password);
        }

        $user = Admin::findOrFail($id)->update($updateData);
        if ($user) {
            return redirect()
                ->route('admin.users.edit', ['id' => $id])
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.users.edit', ['id' => $id])
                ->with('error', 'Something went wrong');
        }
    }

    public function softDelete($id)
    {
        $response = Admin::findOrFail($id)->update([
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
        $response = Admin::findOrFail($id)->update([
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
}
