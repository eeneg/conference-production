<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Attachments/Index',
            [
                'files' => Attachment::search($request->search)
                    ->query(function($query){
                        $query->with('conference')->with('storage');
                    })
                    ->paginate(10)
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Attachment $attachment)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attachment $attachment)
    {
        return Storage::download('public/Conference_Attachments/' . $attachment->path, $attachment->file_name);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attachment $attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        //
    }

    public function download(Attachment $attachment)
    {
        return Storage::download("public/$attachment->path");
    }

    public function content(Attachment $attachment)
    {
        return response()->stream(function () use ($attachment) {
            echo $attachment->getContent();
        }, 200, ['Content-Type' => $attachment->mime, 'Content-Disposition' => 'inline; filename="' . $attachment->file_name . '"']);
    }
}
