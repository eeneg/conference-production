<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage as StorageDownload;
use App\Models\Storage;
use App\Models\Category;
use App\Models\File;
use Inertia\Inertia;
use Exception;
use Throwable;

class FileSearchController extends Controller
{
    public function index(){
        return Inertia::render('Files/Show', [
            'files' => File::with('category')->with('storage')
                ->orderBy('created_at', 'desc')
                ->paginate(15),
            'storage' => Storage::all(),
            'category' => Category::where("type", "1")->get(),
        ]);
    }

    public function searchFile(Request $request)
    {
        $files = File::search($request->search)
            ->query(function($query) use ($request){
                $query->with('storage')
                    ->with('category')
                    ->when($request->storage != null, function($query) use ($request){
                        $query->where('storage_id', $request->storage);
                    })
                    ->when(count($request->category) > 0, function($query) use ($request){
                        $query->whereHas('category', function($query) use ($request){
                            $query->whereIn('categories.id', $request->category);
                        });
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return $files;
    }

    public function downloadFile(File $file){
        return StorageDownload::download($file->path, $file->file_name);
    }

}
