<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class ChatController extends Controller
{
    public function index()
    {

    }

    public function show($id)
    {
        return Message::where('user_id', auth()->user()->id)->where('receiver_id', $id)->get();
    }

    public function store(Request $request){

        $message = Message::create($request->all());

        return $message;
    }
}
