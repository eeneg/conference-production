<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Conference;

class AgendaController extends Controller
{
    public function index(Request $request){
        return Inertia::render('Agenda/Index', [
            'conference' => Conference::find($request->id)
        ]);
    }
}
