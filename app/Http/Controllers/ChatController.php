<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Eloquent\Builder as Builder1;
use Illuminate\Support\Facades\DB;
use App\Events\MessageSentEvent;
use Illuminate\Database\Query\JoinClause;
use App\Models\Message;
use App\Models\User;
use App\Models\Chat;

class ChatController extends Controller
{
    public function index()
    {

    }

    public function getUsersToChat(Request $request){

        $users = User::search($request->search)
            ->query(fn (Builder $query) => $query
            ->where('id', '!=', auth()->user()->id)
            ->without('roles'))
            ->paginate(15);

        return $users;
    }

    public function userChatList(){

        $userId = auth()->user()->id;

        $chatList = User::select('users.id', 'users.name', 'messages.message', 'messages.created_at')
            ->without('roles')
            ->leftJoin('messages', function($join){
                $join->on('users.id', '=', 'messages.sender_id')
                    ->orOn('users.id', '=', 'messages.recipient_id');
            })
            ->where(function(Builder $query){
                $query->where('messages.sender_id', auth()->user()->id)
                    ->orWhere('messages.recipient_id', auth()->user()->id);
            })
            ->whereRaw('messages.created_at = (
                SELECT MAX(created_at) FROM messages
                WHERE (messages.sender_id = users.id OR messages.recipient_id = users.id)
            )')
            ->where('users.id', '!=', auth()->user()->id)
            ->orWhereNull('messages.id')
            ->orderByDesc('created_at')
            ->paginate(10);

        return $chatList;
    }

    public function show($id)
    {
        return Message::where('sender_id', auth()->user()->id)
            ->where(function($query) use ($id){
                $query->where('recipient_id', $id);
            })
            ->orWhere(function($query) use ($id){
                $query->where('sender_id', $id)
                ->where('recipient_id', auth()->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function store(Request $request){
        $message = User::find(auth()->user()->id)->messages()->create([
            // 'sender_id' => auth()->user()->id,
            'recipient_id' => $request->recipient_id,
            'message' => $request->message
        ]);

        // $chat = Chat::updateOrCreate(['id1' => auth()->user()->id, 'id2' => $request->recipient_id], ['id1' => auth()->user()->id, 'id2' => $request->recipient_id, 'latest_message_id' => $message->id]);

        broadcast(new MessageSentEvent(auth()->user(), $message))->toOthers();

    }
}
