<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::orderBy('branch_name')->paginate(20);
        return view('branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
         return view('branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|unique:branches,branch_name',
        ]);

        Branch::create([
            'branch_name' => strtoupper($request->branch_name),
            'branch_added_by' => auth()->id(),
            'branch_date_added' => now(),
        ]);

        return redirect()->route('branches.index')->with('status', 'Branch added successfully!');
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
        $branch = Branch::findOrFail($id);
        return view('branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $request->validate([
            'branch_name' => 'required|unique:branches,branch_name,' . $branch->id,
        ]);
        $branch->update([
            'branch_name' => strtoupper($request->branch_name),
        ]);
        return redirect()->route('branches.index')->with('status', 'Branch updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        return redirect()->route('branches.index')->with('status', 'Branch deleted successfully!');
    }
}
