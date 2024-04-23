<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileComment;
use App\Models\File;

class FileCommentController extends Controller
{
    public function index(Request $request){
        return FileComment::leftJoin('users', 'file_comments.user_id', '=', 'users.id')
        ->where('file_id', $request->file_id)
        ->select('file_comments.id', 'file_comments.comment', 'file_comments.created_at', 'users.name', 'users.id as user_id')
        ->orderBy('file_comments.created_at', 'desc')
        ->get();
    }

    public function store(Request $request){

        $request->validate([
            'comment' => 'required'
        ]);

        File::find($request->file_id)->fileComment()->create($request->all());

    }

    public function update(Request $request, String $id){

        $request->validate([
            'comment' => 'required'
        ]);

        FileComment::find($id)->update($request->all());
    }

    public function destroy(String $id){
        FileComment::find($id)->delete();
    }
}
