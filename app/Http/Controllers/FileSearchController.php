<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use App\Models\Category;
use Illuminate\Support\Facades\Storage as StorageDownload;
use App\Models\File;
use Inertia\Inertia;
use Exception;
use Throwable;

class FileSearchController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Files/Show', [
            'files' => File::search($request->search)
                ->query(function($query){
                    $query->with('category')->with('storage');
                })->paginate(15),
            'storage' => Storage::all(),
            'category' => Category::where("type", "1")->get(),
        ]);
    }

    public function downloadFile(File $file){
        return StorageDownload::download($file->path, $file->file_name);
    }

}
