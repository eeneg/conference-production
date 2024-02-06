<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use App\Models\Category;
use App\Models\File;
use App\Services\FileContentService;
use Inertia\Inertia;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage as FileStorage;
use Exception;
use Throwable;

class FileController extends Controller
{

    public function __construct(
        private FileContentService $fileContentService
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
            'file' => 'required',
            'title' => 'required:|unique:files',
            'storage_id' => 'required',
            'category_id' => 'required',
            'date' => 'required',
            'details' => 'required',
        ],[
            'storage_id.required' => 'Storage Field in required',
            'category_id.required' => 'Category Field in required',
        ]);

        $tr = DB::Transaction(function($e) use ($request) {
            $file = File::create([
                'title'         => $request->title,
                'file_name'     => str_replace(' ','_',$request->file[0]->getClientOriginalName()),
                'path'          => 'public/File_Uploads/'. $request->storage_id . '/' . $request->category_id .'/'. str_replace(' ','_',$request->file[0]->getClientOriginalName()),
                'storage_id'    => $request->storage_id,
                'category_id'   => $request->category_id,
                'details'       => $request->details,
                'date'          => $request->date,
            ]);
            FileStorage::putFileAs('public/File_Uploads/'. $request->storage_id . '/' . $request->category_id, $request->file[0], str_replace(' ','_',$request->file[0]->getClientOriginalName()));
            $this->fileContentService->handle($file);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
