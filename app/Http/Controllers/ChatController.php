<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Eloquent\Builder as Builder1;
use Illuminate\Support\Facades\DB;
use App\Events\MessageSentEvent;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
    public function index()
    {

    }

    public function getUsersToChat(Request $request){

        $users = User::search($request->search)
            ->query(fn (Builder $query) => $query
            ->with('latestMessage')
            ->without('roles'))
            ->paginate(15);

        return $users;
    }

    public function userChatList(){



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
            ->paginate(10);
    }

    public function store(Request $request){

        $message = User::find(auth()->user()->id)->messages()->create([
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        broadcast(new MessageSentEvent(auth()->user(), $message))->toOthers();

    }
}
