<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Storage;
use App\Models\Category;

class SettingController extends Controller
{
    public function index(){
        return Inertia::render('Settings/Index',[
            'storage' => Storage::all(),
            'category' => Category::all()
        ]);
    }
}
