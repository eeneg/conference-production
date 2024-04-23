<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use App\Models\Category;
use App\Models\File;
use App\Models\FileVersionControl;
use App\Services\FileContentService;
use App\Services\FileUploadedService;
use App\Services\UpdateNewLatestOnDeleteService;
use Inertia\Inertia;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage as FileStorage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File as FileValidate;
use Exception;
use Throwable;

class FileController extends Controller
{

    public function __construct(
        private FileContentService $fileContentService,
        private FileUploadedService $fileUploadedService,
        private UpdateNewLatestOnDeleteService $updateNewLatestOnDeleteService,
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Files/Index', [
            'storage' => Storage::all(),
            'category' => Category::where("type", "1")->get(),
            'duplicates' => [],
            'error_message' => ''
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function fileCheck(Request $request){

        $existing_file_names = [];

        foreach($request->fileNames as $file){
            $check = File::where('storage_id', $request->storage_id)->where('category_id', $request->category_id)->where('file_name', str_replace(' ','_',$file))->count() > 0;
            if($check){
                array_push($existing_file_names, $file);
            }
        }

        if(count($existing_file_names) > 0){
            return ['response' => 'Duplicate File Names Inside Storage', 'file_names' => $existing_file_names, 'check' => true];
        }else{
            return ['check' => false];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:5120',
            'title' => 'required:|unique:files',
            'storage_id' => 'required',
            'category_id' => 'required|array',
            'category_id.*' => 'array',
            'category_id.*.id' => 'required|string|uuid|exists:categories,id',
            'date' => 'required',
            'details' => 'required',
        ],[
            'storage_id.required' => 'Storage Field in required',
            'category_id.required' => 'Category Field in required',
        ]);

        $tr = DB::Transaction(function($e) use ($request) {
            $file = File::create([
                'title'         => $request->title,
                'file_name'     => str_replace(' ','_',$request->file->getClientOriginalName()),
                'path'          => 'public/File_Uploads/'. $request->storage_id .'/'. str_replace(' ','_',$request->file->hashName()),
                'storage_id'    => $request->storage_id,
                'details'       => $request->details,
                'date'          => $request->date,
            ]);
            return $file;
        });
            FileStorage::putFileAs('public/File_Uploads/'. $request->storage_id, $request->file, str_replace(' ','_',$request->file->hashName()));
            $this->fileContentService->handle($tr->id);
            $this->attachCategory($tr->id, $request->category_id);
            $this->fileUploadedService->handle($tr->id);
    }

    public function attachCategory($id, $categories){
        $file = File::find($id);

        $file->category()->attach(collect($categories)->map->id);

        $file->load('category')->searchable();
    }

    public function renameFile(Request $request, $id){

        $request->validate([
            'file_name' => 'required'
        ]);

        $file = File::find($id);

        $file->update(['file_name' => $request->file_name]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Files/Index', [
            'storage' => Storage::all(),
            'category' => Category::where("type", "1")->get(),
            'file' => File::with(['category'])->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required:|unique:files,title,'.$id,
            'storage_id' => 'required',
            'category_id' => 'required|array',
            'category_id.*' => 'array',
            'category_id.*.id' => 'required|string|uuid|exists:categories,id',
            'date' => 'required',
            'details' => 'required',
        ],[
            'storage_id.required' => 'Storage Field in required',
            'category_id.required' => 'Category Field in required',
        ]);

        $file = File::find($id);

        $file->category()->syncWithPivotValues(collect($request->category_id)->map(function($e){return $e['id'];}), ['active' => true]);

        $file->update([
            'title' => $request->title,
            'storage_id' => $request->storage_id,
            'details' => $request->details,
            'date' => $request->date
        ]);
    }

    public function setFileForReview(Request $request, String $id){
        $file = File::find($id);

        $file->update(['for_review' => $request->status]);

        return redirect(route('file.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $f = File::find($id);

        if($f->latest == true){
            $this->updateNewLatestOnDeleteService->handle($id);
        }

        $fv = FileVersionControl::where('file_id', $id)->first();

        if($fv){
            $fv->delete();
        }

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
