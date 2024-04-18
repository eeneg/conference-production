<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\User;

class NoteController extends Controller
{
    public function index(Request $request){
        return Note::where('conference_id', $request->conference_id)->where('user_id', $request->user_id)->get('note');
    }

    public function store(Request $request){
        User::find(auth()->user()->id)->note()->updateOrCreate(['conference_id' => $request->conference_id, 'user_id' => auth()->user()->id], ['conference_id' => $request->conference_id, 'note' => $request->note]);
    }
}
