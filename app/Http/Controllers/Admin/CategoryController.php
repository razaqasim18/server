<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return view('category.admin-list', ['category' => $category]);
    }
    public function insert(Request $request)
    {
        $error = '';
        $category = $request->category;
        if (empty($category)) {
            $error .= "Category is required\n";
        }
        if ($error) {
            $result = ['type' => 0, 'msg' => $error];
            echo json_encode($result);
            exit();
        } else {
            $response = Category::insert([
                'category' => $category,
            ]);
            if ($response) {
                $type = 1;
                $msg = 'Category is saved successfully';
            } else {
                $type = 0;
                $msg = 'Something went wrong';
            }
            $result = ['type' => $type, 'msg' => $msg];
            echo json_encode($result);
            exit();
        }
    }

    public function update(Request $request)
    {
        $error = '';
        $id = $request->id;
        $category = $request->category;
        if (empty($category)) {
            $error .= "Category is required\n";
        }
        if ($error) {
            $result = ['type' => 0, 'msg' => $error];
            echo json_encode($result);
            exit();
        } else {
            $response = Category::findOrFail($id)->update([
                'category' => $category,
            ]);
            if ($response) {
                $type = 1;
                $msg = 'Category is updated successfully';
            } else {
                $type = 0;
                $msg = 'Something went wrong';
            }
            $result = ['type' => $type, 'msg' => $msg];
            echo json_encode($result);
            exit();
        }
    }
    public function delete($id)
    {
        $response = Category::destroy($id);
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
