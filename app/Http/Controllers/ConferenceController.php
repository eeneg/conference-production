<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAttachment;
use App\Models\Attachment;
use App\Models\PdfContent;
use App\Models\Conference;
use App\Services\AttachmentService;
use App\Services\AttachmentEditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Throwable;

class ConferenceController extends Controller
{

    public function __construct(
        private AttachmentService $attachmentService,
        private AttachmentEditService $editService
    ){}
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

        $this->attachmentService->job($conf->id);

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
            'status' => 'required',
        ]);


        try{

            $conf = Conference::find($id);

            $this->editService->handle($request['attachments'], $conf);

            $this->attachmentService->job($conf->id);

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

        $files = Storage::deleteDirectory('public/' . $conf->id);

        PdfContent::whereHas('attachment', fn ($q) => $q->whereConferenceId($conf->id))
                ->get('id')
                ->each
                ->delete();

        Attachment::where('conference_id', $conf->id)
            ->get('id')
            ->each
            ->delete();

        $conf->delete();

        return redirect(route('conferences.index'));
    }
}
