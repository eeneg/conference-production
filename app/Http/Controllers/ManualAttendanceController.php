<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;
use App\Models\ManualAttendance;

class ManualAttendanceController extends Controller
{
    //

    public function store(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $conf = Conference::find($request->conf_id)->manualAttendance()->create($request->all());
    }

    public function destroy($id){
        $att = ManualAttendance::find($id)->delete();
    }
}
