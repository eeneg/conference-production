<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function index(){

        return Inertia::render('Storage/Index');

    }

    public function store(Request $request){

        $request->validate([
            'title' => 'required',
            'location' => 'required',
        ]);

        Storage::create($request->all());

    }

    public function update(Request $request){

        $request->validate([
            'title' => 'required',
            'location' => 'required',
        ]);

        $storage = Storage::find($request->id);

        $storage->update($request->all());

    }

    public function destroy($id){

        $storage = Storage::find($id);

        $storage->delete();

    }
}
