<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PollSetActiveEvent;
use App\Events\PollVoteSubmittedEvent;
use App\Models\Poll;
use App\Models\Conference;
use App\Models\PollVote;
use App\Models\UserRole;
use App\Models\Role;

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

    public function getPoll($id){
        return Poll::find($id)->only('id', 'title', 'details');
    }

    public function submitPollResponse(Request $request){
        $request->validate([
            'poll_id' => 'required',
            'user_id' => 'required',
            'vote'  => 'required'
        ],[
            'vote.required' => 'Field Required'
        ]);

        PollVote::updateOrCreate([
            'poll_id'   => $request->poll_id,
            'user_id'   => $request->user_id,
        ],[
            'poll_id'   => $request->poll_id,
            'user_id'   => $request->user_id,
            'vote'      => $request->vote
        ]);

        $count = $this->countPollVotes($request->poll_id);

        broadcast(new PollVoteSubmittedEvent($count));

    }

    public function endPoll(Request $request){

        $count = $this->countPollVotes($request->poll_id);
        $res = '';

        if($count['tr'] > $count['fa']){
            $res = 'Passed';
        }else if($count['tr'] < $count['fa']){
            $res = 'Rejected';
        }else if($count['tr'] == $count['fa']){
            $res = 'Impasse';
        }

        Poll::find($request->poll_id)->update([
            'agree_count' => $count['tr'],
            'disagree_count' => $count['fa'],
            'result' => $res,
            'concluded' => true
        ]);
    }

    public function countPollVotes($poll_id){

        $tr = PollVote::where('poll_id', $poll_id)->where('vote', true)->count();
        $fa = PollVote::where('poll_id', $poll_id)->where('vote', false)->count();

        $u = UserRole::where('role_id', Role::where('title', 'board member')->first()->id)->count();

        return ['tr' => $tr, 'fa' => $fa, 'u' => $u];
    }

    public function setPollActive(Request $request){
        $request->validate([
            'id' => 'required|exists:polls,id'
        ]);

        $poll = Poll::find($request->id)->update(['active' => $request->value]);

        broadcast(new PollSetActiveEvent($request->id, $request->initiatorID, $request->value));
    }

    public function destroy(Request $request){

        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $p = PollVote::where('poll_id', $request->id)->delete();
        $d = Poll::find($request->id)->delete();
    }
}
