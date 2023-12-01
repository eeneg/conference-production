<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Users/Index', [
            'users' => User::search($request->search)->paginate(10)
        ]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Users/Edit', [
            'user' => User::find($id),
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        User::find($id)->update($request->all());
    }

    public function updatePassword(Request $request){

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        User::find($request->id)->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back();

    }

    public function attachRole(Request $request){

        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = User::find($request->user_id);
        $role = Role::find($request->role_id);

        $this->detachRole($request->user_id, $role_id);

        $user->roles()->attach($role->id);

    }

    public function detachRole($user_id, $role_id){

        $exist = UserRole::where('user_id', $user_id)->where($role_id, 'role_id')->get();

        if($exist->count() > 0){
            $user->roles()->dettach($role_id);
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
