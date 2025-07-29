<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalWorkflow;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;

class ApprovalWorkflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workflows = ApprovalWorkflow::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('approval-workflows.index', compact('workflows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('user_surname')->get();
        $roles = UserRole::orderBy('ur_name')->get();
        
        return view('approval-workflows.create', compact('users', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:approval_workflows',
            'description' => 'nullable|string',
            'type' => 'required|in:requisition,vehicle_request,general',
            'approval_levels' => 'required|array',
            'approval_levels.*.approver_type' => 'required|in:user,role,department_head',
            'approval_levels.*.approver_id' => 'required_if:approval_levels.*.approver_type,user|exists:users,id',
            'approval_levels.*.role_id' => 'required_if:approval_levels.*.approver_type,role|exists:user_roles,id',
        ]);

        $approval_levels = [];
        foreach ($request->approval_levels as $level => $config) {
            $approval_levels[$level] = [
                'approver_type' => $config['approver_type'],
                'approver_id' => $config['approver_id'] ?? null,
                'role_id' => $config['role_id'] ?? null,
            ];
        }

        ApprovalWorkflow::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'approval_levels' => $approval_levels,
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('approval-workflows.index')
            ->with('success', 'Approval workflow created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ApprovalWorkflow $approvalWorkflow)
    {
        $workflow = $approvalWorkflow->load('creator');
        $approval_levels = $workflow->getApprovalLevels();
        
        return view('approval-workflows.show', compact('workflow', 'approval_levels'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApprovalWorkflow $approvalWorkflow)
    {
        $workflow = $approvalWorkflow;
        $users = User::orderBy('user_surname')->get();
        $roles = UserRole::orderBy('ur_name')->get();
        $approval_levels = $workflow->getApprovalLevels();
        
        return view('approval-workflows.edit', compact('workflow', 'users', 'roles', 'approval_levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApprovalWorkflow $approvalWorkflow)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:approval_workflows,name,' . $approvalWorkflow->id,
            'description' => 'nullable|string',
            'type' => 'required|in:requisition,vehicle_request,general',
            'approval_levels' => 'required|array',
            'approval_levels.*.approver_type' => 'required|in:user,role,department_head',
            'approval_levels.*.approver_id' => 'required_if:approval_levels.*.approver_type,user|exists:users,id',
            'approval_levels.*.role_id' => 'required_if:approval_levels.*.approver_type,role|exists:user_roles,id',
        ]);

        $approval_levels = [];
        foreach ($request->approval_levels as $level => $config) {
            $approval_levels[$level] = [
                'approver_type' => $config['approver_type'],
                'approver_id' => $config['approver_id'] ?? null,
                'role_id' => $config['role_id'] ?? null,
            ];
        }

        $approvalWorkflow->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'approval_levels' => $approval_levels,
        ]);

        return redirect()->route('approval-workflows.index')
            ->with('success', 'Approval workflow updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApprovalWorkflow $approvalWorkflow)
    {
        // Check if workflow is being used
        if ($approvalWorkflow->requisitionApprovals()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete workflow that is being used']);
        }

        $approvalWorkflow->delete();

        return redirect()->route('approval-workflows.index')
            ->with('success', 'Approval workflow deleted successfully');
    }

    /**
     * Toggle workflow active status
     */
    public function toggleStatus(ApprovalWorkflow $approvalWorkflow)
    {
        $approvalWorkflow->update([
            'is_active' => !$approvalWorkflow->is_active
        ]);

        $status = $approvalWorkflow->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('approval-workflows.index')
            ->with('success', "Workflow {$status} successfully");
    }

    /**
     * Get users for delegation
     */
    public function getUsersForDelegation()
    {
        $users = User::where('user_active', 1)
            ->orderBy('user_surname')
            ->get(['id', 'user_name', 'user_surname', 'user_othername']);
        
        return response()->json($users);
    }
}
