<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledegBase;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    function list() {
        $knowledge = KnowledegBase::get();
        return view('knowledgebase.admin-list', ['knowledge' => $knowledge]);
    }

    public function addKnowledge()
    {
        return view('knowledgebase.admin-add');
    }

    public function insertKnowledge(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'message' => 'required',
        ]);
        $knowledge = KnowledegBase::insert([
            'title' => $request->title,
            'description' => $request->message,
        ]);
        if ($knowledge) {
            return redirect()
                ->route('admin.knowledge.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.knowledge.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function viewKnowledge($id)
    {
        $knowledge = KnowledegBase::findOrFail($id);
        return view('knowledgebase.admin-view', [
            'knowledge' => $knowledge,
        ]);
    }

    public function editKnowledge($id)
    {
        $knowledge = KnowledegBase::findOrFail($id);
        return view('knowledgebase.admin-edit', [
            'knowledge' => $knowledge,
        ]);
    }

    public function updateKnowledge($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'message' => 'required',
        ]);

        $updateData = [
            'title' => $request->title,
            'description' => $request->message,
        ];

        $knowledge = KnowledegBase::findOrFail($id);
        $knowledgerespose = $knowledge->update($updateData);
        if ($knowledgerespose) {
            return redirect()
                ->route('admin.knowledge.edit', ['id' => $id])
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.knowledge.edit', ['id' => $id])
                ->with('error', 'Something went wrong');
        }
    }

    public function knowledgeDelete($id)
    {
        $response = KnowledegBase::destroy($id);
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
