<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Department;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::with('department')->orderBy('section_name')->paginate(20);
        return view('sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('dept_name')->get();
        return view('sections.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'section_name' => 'required|unique:sections,section_name',
            'section_dept_id' => 'required|exists:departments,id',
        ]);

        Section::create([
            'section_name' => strtoupper($request->section_name),
            'section_dept_id' => $request->section_dept_id,
            'section_added_by' => auth()->id(),
            'section_date_added' => now(),
        ]);

        return redirect()->route('sections.index')->with('status', 'Section added successfully!');
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
        $section = Section::findOrFail($id);
        $departments = Department::orderBy('dept_name')->get();
        return view('sections.edit', compact('section', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);
        $request->validate([
            'section_name' => 'required|unique:sections,section_name,' . $section->id,
            'section_dept_id' => 'required|exists:departments,id',
        ]);
        $section->update([
            'section_name' => strtoupper($request->section_name),
            'section_dept_id' => $request->section_dept_id,
        ]);
        return redirect()->route('sections.index')->with('status', 'Section updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();
        return redirect()->route('sections.index')->with('status', 'Section deleted successfully!');
    }
}
