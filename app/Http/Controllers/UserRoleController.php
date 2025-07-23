<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRole;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = UserRole::orderBy('ur_name')->paginate(20);
        return view('user_roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user_roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ur_name' => 'required|unique:user_roles,ur_name',
        ]);

        UserRole::create([
            'ur_name' => strtoupper($request->ur_name),
            'ur_added_by' => auth()->id(),
            'ur_date_added' => now(),
        ]);

        return redirect()->route('user-roles.index')->with('status', 'Role added successfully!');
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
    public function edit($id)
    {
        $role = UserRole::findOrFail($id);
        return view('user_roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = UserRole::findOrFail($id);
        $request->validate([
            'ur_name' => 'required|unique:user_roles,ur_name,' . $role->id,
        ]);
        $role->update([
            'ur_name' => strtoupper($request->ur_name),
        ]);
        return redirect()->route('user-roles.index')->with('status', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = UserRole::findOrFail($id);
        $role->delete();
        return redirect()->route('user-roles.index')->with('status', 'Role deleted successfully!');
    }
}
