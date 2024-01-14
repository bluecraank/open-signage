<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
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
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.show', compact('user', 'roles'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'roles' => 'integer|in:1,2,3,4',
        ]);

        if(!$request->has('roles') || $request->roles == "") {
            return redirect()->route('users.show', $user->id)->withErrors(['message' =>'You must select a role']);
        }

        // if($user->id == Auth::user()->id) {
        //     return redirect()->route('users.show', $user->id)->withErrors(['message' => 'You cannot change your own roles']);
        // }

        // Remove every role
        $user->roles()->detach();

        // Add the new roles
        $user->assignRole($request->roles);


        return redirect()->route('users.show', $user->id)->with('success', 'Roles updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::whereId($id)->first();

        if(!$user) {
            return redirect()->route('users.index')->withErrors(['message' => 'User not found']);
        }

        if($user->hasRole('admin')) {
            return redirect()->route('users.show', $user->id)->withErrors(['message' => 'You cannot delete an admin']);
        }

        if($user->delete()) {
            return redirect()->route('users.index')->with('success', 'User deleted!');
        }

        return redirect()->route('users.index')->withErrors(['message' => 'Something went wrong!']);
    }
}
