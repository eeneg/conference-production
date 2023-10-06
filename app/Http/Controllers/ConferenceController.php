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

        $attachments = Conference::fileHandle($request->attachments, $conf->id);

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

        $e = Attachment::where('conference_id', $conf->id)->orderBy('category_order', 'ASC')->get()->groupBy('category')
        ->map(function($e){
            return collect($e)->sortBy('file_order');
        });

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

            $request_categories = [];

            $request_file = [];

            if(count($request->attachments) > 0){
                foreach($request->attachments as $key => $data){

                    $path = $conf->id . '/' . $data['category'];

                    array_push($request_categories, $data['category']);

                    foreach($data['files'] as $key => $file){
                        $file['file_order'] = $key;
                        $file['path'] = str_replace(' ', '', $path);
                        $file['details'] = $file['file_details'];
                        if(isset($file['id'])){
                            array_push($request_file, $file['id']);
                            Attachment::find($file['id'])->update($file);
                        }else{
                            $new_file = [
                                'category'          => $data['category'],
                                'category_order'    => $data['category_order'],
                                'file_name'         => str_replace(' ', '', $file['file']->getClientOriginalName()),
                                'path'              => str_replace(' ', '', $conf->id . '/' . $data['category']),
                                'details'           => $file['file_details'],
                                'storage_location'  => $file['storage_location'],
                                'file_order'        => $file['file_order'],
                                'storage_location'  => $file['storage_location'],
                            ];
                            $newFile = $conf->attachment()->create($new_file);
                            array_push($request_file, $newFile->id);
                            Storage::putFileAs('public/' . $conf->id . '/' . str_replace(' ', '', $data['category']), $file['file'], str_replace(' ', '', $file['file']->getClientOriginalName()));
                        }
                    }
                }
            }

            $existing_categories = $conf->attachment->pluck('category')->unique()->values();

            $files = Attachment::where('conference_id', $conf->id)->whereNotIn('id', $request_file)->get();

            if(count($files)){
                Attachment::where('conference_id', $conf->id)->whereNotIn('id', $request_file)->delete();
            }

            $attachment = Attachment::where('conference_id', $conf->id)->whereNotIn('category', $request_categories)->delete();

            foreach(array_diff($existing_categories->toArray(), $request_categories) as $folders){
                Storage::deleteDirectory('public/'. $conf->id . '/' . $folders);
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
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);
        $conf = Conference::find($request->id);
        $attachment = Attachment::where('conference_id', $conf->id)->delete();
        $files = Storage::deleteDirectory('public/' . $conf->id);
        $conf->delete();

        return redirect(route('conferences.index'));
    }
}
