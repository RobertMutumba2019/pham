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
        // Validate input - updated to match form field names
        $validated = $request->validate([
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
            'action' => 'required|in:draft,submit',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        $user = $request->user();
        // Check if user is an approver (cannot create requisition)
        if ($user->role && $user->role->is_approver) {
            return back()->withErrors(['error' => 'You cannot create any requisition because you are an Approver']);
        }

        DB::beginTransaction();
        try {
            // Create requisition
            $ref = $user->id . time();
            $status = $validated['action'] === 'draft' ? -1 : 1;
            $requisitionNumber = $validated['action'] === 'submit' ? $this->generateRequisitionNumber() : null;

            $requisition = Requisition::create([
                'req_number' => $requisitionNumber,
                'req_title' => $validated['req_title'],
                'req_description' => $validated['req_description'],
                'req_priority' => $validated['req_priority'],
                'req_ref' => $validated['req_ref'] ?? $ref,
                'req_date_needed' => $validated['req_date_needed'],
                'req_justification' => $validated['req_justification'],
                'req_added_by' => $user->id,
                'req_date_added' => now(),
                'req_status' => $status,
            ]);

            // Create requisition items
            foreach ($validated['items'] as $itemData) {
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

            DB::commit();

            $message = $validated['action'] === 'draft' 
                ? 'Requisition saved as draft successfully!' 
                : 'Requisition submitted successfully!';

            return redirect()->route('requisitions.show', $requisition->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create requisition: ' . $e->getMessage()]);
        }
    }

    /**
     * Generate a new requisition number
     */
    private function generateRequisitionNumber()
    {
        $dm = date('y') . date('m');
        $suffix = "RQN" . $dm;
        $last = Requisition::whereNotNull('req_number')
            ->where('req_number', 'like', $suffix . '%')
            ->orderByDesc('id')
            ->first();
        $next = 1;
        if ($last && preg_match('/RQN\d{4}(\d+)/', $last->req_number, $matches)) {
            $next = intval($matches[1]) + 1;
        }
        return $suffix . str_pad($next, 4, '0', STR_PAD_LEFT);
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
