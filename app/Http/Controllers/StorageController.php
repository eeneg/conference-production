<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Storage;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function index(){

        return Inertia::render('Storage/Index', [
            'storage' => Storage::all()
        ]);

    }

    public function store(Request $request){

        $request->validate([
            'title' => 'required|unique:storages,title',
            'location' => 'required',
        ]);

        Storage::create($request->all());

    }

    public function update(Request $request){

        $request->validate([
            'title' => 'required}unique:storages,title',
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
