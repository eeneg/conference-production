<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use App\Models\Category;
use App\Models\File;
use Inertia\Inertia;
use Exception;
use Throwable;

class FileController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Files/Show', [
            // 'files' => File::search($request->search)->get(),
            // 'storage' => Storage::all(),
            // 'category' => Category::where("type", "1")->get(),
        ]);
    }

}
