<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\File;

class CategoryController extends Controller
{


    public function store(Request $request){

        $request->validate([
            'title' => 'required|unique:categories,title',
            'type' => 'required'
        ]);

        Category::create($request->all());

    }

    public function update(Request $request){

        $request->validate([
            'title' => 'required|unique:categories,title',
            'type' => 'required'
        ]);

        $category = Category::find($request->id);

        $category->update($request->all());

    }

    public function checkCategoryRelation($id){

        $files = File::where('category_id', $id)->count() == 0;

        return $files;

    }

    public function destroy(Request $request, $id){

        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $category = Category::find($id);

        $category->delete();

    }
}
