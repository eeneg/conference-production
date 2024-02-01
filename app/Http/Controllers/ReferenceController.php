<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    public function store(Request $request){

        $request->validate([
            'file' => 'required',
            'title' => 'required',
            'date' => 'required',
            'details' => 'required'
        ]);



    }

    public function update(Request $request){

    }

    public function destroy($id){

    }
}
