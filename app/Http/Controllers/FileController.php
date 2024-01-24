<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use App\Models\File;
use Inertia\Inertia;
use App\Services\UploadFileHandlerService;

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
            'storage' => Storage::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'storage_id' => 'required',
            'details' => 'required',
        ]);

        try{
            $files = $this->uploadFileHandlerService->fileHandle($request->all());
        }catch(Exception $e){
            report($e);

            return $e->getMessage();
        }

        Storage::find($request->storage_id)->files()->createMany($files);

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
