<?php

namespace App\Http\Controllers;

use App\Models\FileVersionControl;
use App\Models\File;
use Illuminate\Http\Request;

class FileVersionController extends Controller
{
    public function index(Request $request)
    {
        $fv = FileVersionControl::where('file_id', $request->id)->first();

        $versions = FileVersionControl::where('control_id', $fv->control_id)->get('file_id');

        $files = File::whereIn('id', $versions->toArray()[0])->get();

        return $files;
    }

    public function store(Request $request){

        $old_file = File::find($request->file_id);

        $file = File::create([
            'title'         => $old_file->title,
            'file_name'     => str_replace(' ','_',$request->file->getClientOriginalName()),
            'path'          => 'public/File_Uploads/'. $old_file->storage_id .'/'. str_replace(' ','_',$request->file->hashName()),
            'storage_id'    => $old_file->storage_id,
            'details'       => $old_file->details,
            'date'          => $old_file->date,
        ]);

        FileStorage::putFileAs('public/File_Uploads/'. $file->storage_id, $request->file, str_replace(' ','_',$request->file->hashName()));
        $this->fileContentService->handle($file->id);
        $this->attachCategory($file->id, $file->category_id);
        $this->fileUploadedService->handle($file->id);
        $this->replaceLatestFile($old_file);
    }

    public function replaceLatestFile($file){
        File::find($file->id)->update(['latest' => false]);
    }
}
