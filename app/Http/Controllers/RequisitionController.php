<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\Department;
use App\Models\User;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requisitions = Requisition::with(['user.department', 'status'])
            ->orderBy('req_date_added', 'desc')
            ->paginate(20);
            
        return view('requisitions.index', compact('requisitions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('requisitions.create', compact('departments'));
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
        $requisition = Requisition::with([
            'user.department', 
            'items', 
            'attachments', 
            'approvals.approver',
            'status'
        ])->findOrFail($id);
        
        return view('requisitions.show', compact('requisition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $requisition = Requisition::with(['items', 'attachments'])->findOrFail($id);
        $departments = Department::all();
        
        return view('requisitions.edit', compact('requisition', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requisition = Requisition::findOrFail($id);
        
        $request->validate([
            'req_title' => 'required|string|max:255',
            'req_description' => 'required|string',
            'req_priority' => 'required|in:Normal,High,Urgent',
            'req_ref' => 'nullable|string|max:255',
            'req_date_needed' => 'nullable|date',
            'req_justification' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.estimated_cost' => 'nullable|numeric|min:0',
        ]);
        
        // Update requisition
        $requisition->update([
            'req_title' => $request->req_title,
            'req_description' => $request->req_description,
            'req_priority' => $request->req_priority,
            'req_ref' => $request->req_ref,
            'req_date_needed' => $request->req_date_needed,
            'req_justification' => $request->req_justification,
        ]);
        
        // Update items
        $requisition->items()->delete(); // Remove old items
        foreach ($request->items as $itemData) {
            $requisition->items()->create([
                'item_description' => $itemData['description'],
                'item_quantity' => $itemData['quantity'],
                'item_unit' => $itemData['unit'] ?? null,
                'item_estimated_cost' => $itemData['estimated_cost'] ?? null,
            ]);
        }
        
        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $requisition->attachments()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }
        
        return redirect()->route('requisitions.show', $requisition->req_id)
            ->with('success', 'Requisition updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $requisition = Requisition::findOrFail($id);
        
        // Delete associated items and attachments
        $requisition->items()->delete();
        
        // Delete attachment files from storage
        foreach ($requisition->attachments as $attachment) {
            if ($attachment->file_path) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }
        $requisition->attachments()->delete();
        
        // Delete the requisition
        $requisition->delete();
        
        return redirect()->route('requisitions.index')
            ->with('success', 'Requisition deleted successfully!');
    }
}
