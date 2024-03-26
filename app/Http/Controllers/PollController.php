<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\Conference;

class PollController extends Controller
{
    public function index(Request $request){
        return Poll::where('conference_id', $request->id)->get();
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required',
        ]);

        Conference::find($request->conf_id)->poll()->create([
            'title' => $request->title,
            'details' => $request->details
        ]);
    }

    public function destroy(Request $request){

        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        return Poll::find($request->id)->delete();
    }
}
