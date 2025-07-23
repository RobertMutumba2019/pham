<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::orderBy('dept_name')->paginate(20);
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dept_name' => 'required|unique:departments,dept_name',
        ]);

        Department::create([
            'dept_name' => strtoupper($request->dept_name),
            'dept_added_by' => auth()->id(),
            'dept_date_added' => now(),
        ]);

        return redirect()->route('departments.index')->with('status', 'Department added successfully!');
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
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $request->validate([
            'dept_name' => 'required|unique:departments,dept_name,' . $department->id,
        ]);
        $department->update([
            'dept_name' => strtoupper($request->dept_name),
        ]);
        return redirect()->route('departments.index')->with('status', 'Department updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return redirect()->route('departments.index')->with('status', 'Department deleted successfully!');
    }
}
