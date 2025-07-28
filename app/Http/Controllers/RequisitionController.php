<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequisitionController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'req_title' => 'required|string|max:255',
            'req_division' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.ri_code' => 'required|string|max:255',
            'items.*.ri_quantity' => 'required|numeric|min:1',
            'items.*.ri_uom' => 'required|string|max:255',
            'items.*.ri_description' => 'required|string|max:255',
            'action' => 'required|in:draft,forward',
        ]);

        $user = $request->user();
        // Check if user is an approver (cannot create requisition)
        if ($user->role && $user->role->is_approver) {
            return response()->json(['error' => 'You cannot create any requisition because you are an Approver'], 403);
        }

        // Delegate to service
        $service = app(\App\Services\RequisitionService::class);
        $result = $service->createRequisition($user, $validated);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 422);
        }

        return response()->json(['message' => 'Requisition created successfully', 'requisition' => $result['requisition']], 201);
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
