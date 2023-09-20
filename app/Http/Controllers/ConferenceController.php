<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConferenceResource;
use App\Models\Attachment;
use App\Models\Conference;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Inertia\Inertia;
use Throwable;

class ConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Conferences/Index', [
            'search' => $request->search,
            'upcoming' => Conference::pending()->paginate(5),
            'finished' => Conference::search($request->search)
                ->query(fn ($q) => $q->completed())
                ->paginate(5),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Conferences/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:conferences',
            'date' => 'required|date',
            'agenda' => 'required',
            'status' => 'required',
        ]);


        try{

            $conf = Conference::create([
                'title' => $request->title,
                'agenda' => $request->agenda,
                'date' => $request->date,
                'status' => $request->status
            ]);

        }catch(Throwable $e){

            report($e);

            return $e->getMessage();

        }

        $attachments = Conference::fileHandle($request->attachments, $request->title);

        Conference::find($conf->id)->attachment()->createMany($attachments);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $conf = Conference::find($id);

        return Inertia::render('Conferences/Show', $conf);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $conf = Conference::find($id);

        $e = Attachment::where('conference_id', $conf->id)->orderBy('category_order', 'ASC')->get()->groupBy('category');

        $attachments = [];

        foreach($e as $i => $e){

            $attachment = ['category' => $i, 'category_order' => $e[0]['category_order'], 'files' => []];

           foreach($e as $file){

                array_push($attachment['files'], [
                    'id' => $file->id,
                    'conference_id' => $file->conference_id,
                    'category' => $file->category,
                    'category_order' => $file->category_order,
                    'file_order' => $file->file_order,
                    'name' => $file->file_name,
                    'path' => $file->path,
                    'file_details' => $file->details,
                    'storage_location' => $file->storage_location,
                ]);

            };

            array_push($attachments, $attachment);

        }

        $conf->attachments = $attachments;

        return Inertia::render('Conferences/Edit', ['conf' => $conf, 'edit' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'title' => Rule::unique('conferences')->ignore($id),
            'date' => 'required|date',
            'agenda' => 'required',
            'status' => 'required',
        ]);

        try{

            $conf = Conference::find($id);

            if($conf->title !== $request->title){
                Storage::move('public/' . $conf->title, 'public/' . $request->title);
            }

            if(count($request->attachments) > 0){
                foreach($request->attachments as $key => $data){
                    foreach($data['files'] as $key => $file){
                        dd($file);
                    }
                }
            }

            $conf->update([
                'title' => $request->title,
                'agenda' => $request->agenda,
                'date' => $request->date,
                'status' => $request->status,
            ]);

        }catch(Throwable $e){

            report($e);

            return $e->getMessage();

        }




    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
