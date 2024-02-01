<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use App\Models\Category;
use App\Models\File;
use Inertia\Inertia;
use App\Services\UploadFileHandlerService;
use Exception;
use Throwable;

class FileController extends Controller
{

    public function __construct(
        private UploadFileHandlerService $uploadFileHandlerService,
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
            $check = File::where('storage_id', $request->storage_id)->where('file_name', str_replace(' ','_',$file))->count() > 0;
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
            'files' => 'required',
            'storage_id' => 'required',
            'category_id' => 'required',
            'date' => 'required',
            'details' => 'required',
        ]);

        try{
            $files = $this->uploadFileHandlerService->fileHandle($request->all());
        }catch(Exception $e){
            report($e);
            return $e->getMessage();
        }

        $file = Storage::find($request->storage_id)->files()->createMany($files);

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
