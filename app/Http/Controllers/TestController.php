<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage as FileStorage;
use App\Models\File;
use App\Models\Storage;
use App\Models\Category;
use App\Models\FileCategory;
use App\Services\FileContentService;
use App\Services\FileUploadedService;

class TestController extends Controller
{

    public function __construct(
        private FileContentService $fileContentService,
        private FileUploadedService $fileUploadedService
    ){}

    public function store(Request $request){


        foreach($request->files as $e){

            foreach($e as $key => $file){
                $tr = DB::Transaction(function($e) use ($file, $key) {
                    $storage = Storage::all()->select('id')->flatten();
                    $cat = Category::all()->select('id')->flatten()->toArray();
                    shuffle($cat);
                    $cat = array_slice($cat, 0, 5);
                    $file = File::create([
                        'title'         => $file->getClientOriginalName(),
                        'file_name'     => str_replace(' ','_',$file->getClientOriginalName()),
                        'path'          => 'public/File_Uploads/'. $storage[$key % 2 === 0] .'/'. str_replace(' ','_',$file->getClientOriginalName()),
                        'storage_id'    => $storage[$key % 2 === 0],
                        'details'       => 'sample details hahaha fuck you',
                        'date'          => Carbon::today()->subDays(rand(0, 365))->format('Y-m-d'),
                    ]);
                    return $file;
                });
                $storage = Storage::all()->select('id')->flatten();
                $cat = Category::all()->select('id')->flatten()->toArray();
                shuffle($cat);
                $cat = array_slice($cat, 0, 5);
                FileStorage::putFileAs('public/File_Uploads/'. $storage[$key % 2 === 0], $file, str_replace(' ','_',$file->getClientOriginalName()));
                $this->attachCategory($tr->id, $cat);
                $this->fileUploadedService->handle($tr->id);
                $this->fileContentService->handle($tr->id);
            }


        }

    }

    public function attachCategory($id, $categories){
        $file = File::find($id);

        $file->category()->attach($categories);

        $file->load('category')->searchable();
    }
}
