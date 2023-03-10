<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Package;
use Auth;

class PackageController extends Controller
{
    public function list()
    {
        $package = Package::with('category:id,category')->get();
        return view('package.admin-list', ['package' => $package]);
    }

    public function addPackage()
    {
        $category = Category::all();
        return view('package.admin-add', ['category' => $category]);
    }

    public function insertPackage(Request $request)
    {
        $this->validate($request, [
            'category' => 'required',
            'package' => 'required|unique:packages',
            'price' => 'required',
        ]);
        $package = Package::create([
            'package' => $request->package,
            'price' => $request->price,
            'category_id' => $request->category,
        ]);
        if ($package) {
            return redirect()
                ->route('admin.package.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.package.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function editPackage($id)
    {
        $category = Category::all();
        $package = Package::findOrFail($id);
        return view('package.admin-edit', [
            'category' => $category,
            'package' => $package,
        ]);
    }

    public function updatePackge($id, Request $request)
    {
        $this->validate($request, [
            'category' => 'required',
            'package' => 'required',
            'price' => 'required',
        ]);
        $updateData = [
            'category_id' => $request->category,
            'package' => $request->package,
            'price' => $request->price,
        ];

        $user = Package::findOrFail($id)->update($updateData);
        if ($user) {
            return redirect()
                ->route('admin.package.edit', ['id' => $id])
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.package.edit', ['id' => $id])
                ->with('error', 'Something went wrong');
        }
    }

    public function packageDelete($id)
    {
        $response = Package::destroy($id);
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
}
