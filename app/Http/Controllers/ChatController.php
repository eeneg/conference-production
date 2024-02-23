<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
    public function index()
    {

    }

    public function getUsersToChat(Request $request){
        return User::search($request->search)
            ->query(fn (Builder $query) => $query->select(['id', 'name'])->without('roles')->with('messages')->latest())
            ->paginate(15);
    }

    public function show($id)
    {
        return Message::where('user_id', auth()->user()->id)
            ->where(function($query) use ($id){
                $query->where('receiver_id', $id);
            })
            ->orWhere(function($query) use ($id){
                $query->where('user_id', $id)
                ->where('receiver_id', auth()->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function store(Request $request){

        $message = Message::create([
            'user_id' => auth()->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

    }
}
