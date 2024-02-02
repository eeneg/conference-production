<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Reference;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ReferenceController extends Controller
{

    public function index(){
        return Inertia::render('Reference/Index',[
            'reference' => Category::where('type', '2')->with('reference')->get(),
            'refrenceCategory' => Category::where('type', '2')->get(),
        ]);
    }

    public function store(Request $request){

        $request->validate(
            [
                'file' => 'required',
                'category_id' => 'required',
                'title' => 'required|unique:references,title',
                'date' => 'required',
                'details' => 'required'
            ],
            [
                'category_id.required' => 'Category Field required'
            ]
        );

        if($request->hasFile('file')){
            Category::find($request->category_id)->reference()->create([
                'title'         => $request->title,
                'date'          => $request->date,
                'details'       => $request->details,
                'file_name'     => str_replace(' ','_',$request->file[0]->getClientOriginalName()),
                'path'          => '/Reference_Files/' . $request->category_id . '/',
            ]);

            Storage::putFileAs('public/Reference_Files/' . $request->category_id . '/', $request->file[0], str_replace(' ','_',$request->file[0]->getClientOriginalName()));
        }

    }

    public function update(Request $request, string $id){
        $request->validate(
            [
                'category_id' => 'required|unique:references,title',
                'title' => 'required',
                'date' => 'required',
                'details' => 'required'
            ],
            [
                'category_id.required' => 'Category Field required'
            ]
        );

        $ref = Reference::find($id);

        if($request->hasFile('file')){
            $ref->update([
                'title'         => $request->title,
                'category_id'   => $request->category_id,
                'date'          => $request->date,
                'details'       => $request->details,
                'file_name'     => str_replace(' ','_',$request->file[0]->getClientOriginalName()),
                'path'          => '/Reference_Files/' . $request->category_id . '/',
            ]);
            Storage::delete('public/Reference_Files/' . $request->category_id . '/' . str_replace(' ','_',$request->file[0]->getClientOriginalName()));
            Storage::putFileAs('public/Reference_Files/' . $request->category_id . '/', $request->file[0], str_replace(' ','_',$request->file[0]->getClientOriginalName()));
        }else{
            $ref->update([
                'title'         => $request->title,
                'category_id'   => $request->category_id,
                'date'          => $request->date,
                'details'       => $request->details,
            ]);
        }
    }

    public function destroy($id){
        $ref = Referene::find($id);

        Storage::delete('public/Reference_Files/' . $ref->category_id . '/' . $ref->file_name);

        $ref->delete();
    }
}
