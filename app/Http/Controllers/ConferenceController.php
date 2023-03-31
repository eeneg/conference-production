<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Conferences/Index', [
            'upcoming' => Conference::where('status', 'upcoming')->paginate(15),
            'finished' => Conference::where('status', 'finished')->paginate(15),
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
            'title' => 'required',
            // 'attachments' => 'required|mimes:pdf|max:10000'
        ]);

        $ar = [];

        foreach($request->file('attachments') as $file)
        {
            $name = $file->getClientOriginalName();

            array_push($ar, ['fileName' => $name, 'path' => $request->title]);

            Storage::putFileAs('public/' . $request->title , $file, $name);
        }

        // dd($request->all());

        Conference::create([
            'title' => $request->title,
            'agenda' => $request->agenda,
            'date' => $request->date,
            'attachments' => $ar,
            'status' => ;
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
