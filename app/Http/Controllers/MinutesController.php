<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\Minutes;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MinutesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $conf = Conference::find($request->id);

        $min = $conf->minute;


        return Inertia::render('Minutes/Create', ['id' => $request->id, 'content' => $min->content ?? null, 'conf_title' => $conf->title]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        Minutes::updateOrCreate(['conference_id' => $request->id],[
            'conference_id' => $request->id,
            'content' => $request->content
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
