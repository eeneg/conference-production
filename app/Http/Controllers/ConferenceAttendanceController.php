<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ConferenceAttendance;
use App\Models\User;

class ConferenceAttendanceController extends Controller
{
    public function index(Request $request){
        $attendance = ConferenceAttendance::where('conference_id', $request->conference_id)->get('user_id');

        return [
            'present' =>
                User::whereIn('id', $attendance)
                ->with(['roles' => function($query){
                    $query->where('title', 'user');
                }])
                ->get(),
            'absent' =>
                User::whereNotIn('id', $attendance)
                ->whereHas('roles', function (Builder $query) {
                    $query->where('title', 'board member');
                })->get()
        ];
    }

    public function store(Request $request){
        $att = ConferenceAttendance::where('conference_id', $request->conference_id)->where('user_id', $request->user_id)->first();
        if($att == null && User::find($request->user_id)->roles->first()->title == 'board member'){
            return ConferenceAttendance::create(['conference_id' => $request->conference_id, 'user_id' => $request->user_id]);
        }
    }

    public function destroy(Request $request){
        $att = ConferenceAttendance::where('conference_id', $request->conference_id)->where('user_id', $request->user_id)->first();

        $att->delete();
    }

    public function searchBM(Request $request){
        return User::search($request->search)
        ->query(fn (Builder $query) => $query
            ->whereHas('roles', function (Builder $query) {
                $query->where('title', 'board member');
            })
        )
        ->get();
    }
}
