<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Storage;
use App\Models\Category;
use App\Models\Reference;

class SettingController extends Controller
{
    public function index(){
        return Inertia::render('Settings/Index',[
            'storage' => Storage::paginate(5),
            'category' => Category::paginate(5),
            'refrence' => Reference::paginate(5),
        ]);
    }
}
