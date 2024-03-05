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

        $users = User::without('roles')
            ->where('users.id', '!=', auth()->user()->id)
            ->leftJoin('messages', function ($join) {
                $join->on('users.id', '=', 'messages.user_id')
                    ->orOn('users.id', '=', 'messages.receiver_id');
            })
            ->select('users.id', 'users.name', DB::raw('MAX(messages.created_at) as latest_received_message'))
            ->groupBy('users.id');

        return User::where('users.id', '!=', auth()->user()->id)
            ->without('roles')
            ->leftJoin('messages', function ($join) {
                $join->on('users.id', '=', 'messages.user_id')
                    ->orOn('users.id', '=', 'messages.receiver_id');
            })
            ->select('users.id', 'users.name', DB::raw('MAX(messages.created_at) as latest_received_message'))
            ->where(function($query){
                $query->where('messages.user_id', auth()->user()->id)
                    ->orWhere('messages.receiver_id', auth()->user()->id);
            })
            ->groupBy('users.id')
            ->union($users)
            ->orderByDesc('latest_received_message')
            ->paginate(10);

            //
            // $subQuery = User::without('roles')
            //     ->where('users.id', '!=', auth()->user()->id)
            //     ->leftJoin('messages', function ($join) {
            //         $join->on('users.id', '=', 'messages.user_id')
            //             ->orOn('users.id', '=', 'messages.receiver_id');
            //     })
            //     ->select('users.id', DB::raw('MAX(messages.created_at) as latest_received_message'))
            //     ->where('users.id', '!=', auth()->user()->id)
            //     ->groupBy('users.id');

            // $users = User::without('roles')
            //     ->where('users.id', '!=', auth()->user()->id)
            //     ->leftJoin('messages', function ($join) {
            //         $join->on('users.id', '=', 'messages.user_id')
            //             ->orOn('users.id', '=', 'messages.receiver_id');
            //     })
            //     ->joinSub($subQuery, 'sub', function ($join) {
            //         $join->on('users.id', '=', 'sub.id');
            //     })
            //     ->select('users.id', 'users.name', 'sub.latest_received_message', 'messages.message')
            //     ->orderByDesc('latest_received_message')
            //     ->paginate(10);

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
            'user_id' => auth()->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        broadcast(new MessageSentEvent(auth()->user(), $message))->toOthers();

    }
}
