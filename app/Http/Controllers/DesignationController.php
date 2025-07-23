<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designations = Designation::orderBy('designation_name')->paginate(20);
        return view('designations.index', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('designations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'designation_name' => 'required|unique:designations,designation_name',
        ]);

        Designation::create([
            'designation_name' => strtoupper($request->designation_name),
            'designation_added_by' => auth()->id(),
            'designation_date_added' => now(),
        ]);

        return redirect()->route('designations.index')->with('status', 'Designation added successfully!');
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
        $designation = Designation::findOrFail($id);
        return view('designations.edit', compact('designation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $designation = Designation::findOrFail($id);
        $request->validate([
            'designation_name' => 'required|unique:designations,designation_name,' . $designation->id,
        ]);
        $designation->update([
            'designation_name' => strtoupper($request->designation_name),
        ]);
        return redirect()->route('designations.index')->with('status', 'Designation updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();
        return redirect()->route('designations.index')->with('status', 'Designation deleted successfully!');
    }
}
