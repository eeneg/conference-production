<?php

namespace App\Http\Controllers;

use App\Models\FileVersionControl;
use App\Models\FileCategory;
use App\Models\File;
use Illuminate\Support\Facades\Storage as FileStorage;
use Illuminate\Http\Request;
use App\Services\FileContentService;
use App\Services\FileUploadedService;

class FileVersionController extends Controller
{
    public function __construct(
        private FileContentService $fileContentService,
        private FileUploadedService $fileUploadedService
    ){}

    public function index(Request $request)
    {
        $fv = FileVersionControl::where('file_id', $request->id)->first();

        $versions = FileVersionControl::where('control_id', $fv->control_id)->get('file_id');

        $nonLatest = File::whereIn('id', $versions->toArray())->where('latest', false)->get();

        $latest = File::whereIn('id', $versions->toArray())->where('latest', true)->get();

        return ['nonLatest' => $nonLatest, 'latest' => $latest];
    }

    public function store(Request $request){

        $old_file = File::find($request->file_id);

        $fv = FileVersionControl::where('file_id', $old_file->id)->first();

        $request->validate([
            'file' => 'required',
            'title' => 'required:|unique:files',
        ]);

        $file = File::create([
            'title'         => $request->title,
            'file_name'     => str_replace(' ','_',$request->file->getClientOriginalName()),
            'path'          => 'public/File_Uploads/'. $old_file->storage_id .'/'. str_replace(' ','_',$request->file->hashName()),
            'storage_id'    => $old_file->storage_id,
            'details'       => $old_file->details,
            'date'          => $old_file->date,
            'latest'        => $request->latest
        ]);

        FileVersionControl::create([
            'control_id' => $fv->control_id,
            'file_id' => $file->id,
        ]);

        FileStorage::putFileAs('public/File_Uploads/'. $file->storage_id, $request->file, str_replace(' ','_',$request->file->hashName()));
        $this->fileContentService->handle($file->id);
        $this->attachCategory($file->id, $old_file->id);

        if($request->latest){
            $this->replaceLatestFile($old_file);
        }
    }

    public function attachCategory($id, $oldID){
        $file = File::find($id);

        $categories = FileCategory::where('file_id', $id)->get('category_id');

        $file->category()->attach(collect(File::find($oldID)->category)->map->id);

        $file->load('category')->searchable();
    }

    public function replaceLatestFile($file){
        $asd = File::find($file->id)->update(['latest' => false]);
    }

    public function update(Request $request, $id){
        $fv = FileVersionControl::where('file_id', $id)->first();

        $file_versions = FileVersionControl::where('control_id', $fv->control_id)->get('file_id');

        $files = File::whereIn('id', $file_versions)->where('latest', true)->first()->update(['latest' => false]);

        File::find($id)->update(['latest' => true]);
    }

    public function destroy($id){

        $fv = FileVersionControl::where('file_id', $id)->first();

        if($fv){
            $fv->delete();
        }

        $f = File::find($id);

        if(count(FileStorage::files('public/File_Uploads/'.$f->storage_id)) == 1){
            FileStorage::deleteDirectory('public/File_Uploads/'.$f->storage_id);
        }else{
            FileStorage::delete($f->path);
        }

        $f->category()->detach();

        $f->pdfContent()->delete();

        $f->delete();

    }
}
