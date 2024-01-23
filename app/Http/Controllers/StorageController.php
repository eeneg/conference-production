<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Storage;
use App\Models\File;
use App\Models\Attachment;
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
            'title' => 'required|unique:storages,title',
            'location' => 'required',
        ]);

        $storage = Storage::find($request->id);

        $storage->update($request->all());

    }

    public function checkStorageRelation($id){

        $files = File::where('storage_id', $id)->count() == 0;

        $attachments = Attachment::where('storage_id', $id)->count() == 0;

        return $files || $attachments;

    }

    public function destroy(Request $request, $id){

        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $storage = Storage::find($id);

        $storage->delete();

    }
}
